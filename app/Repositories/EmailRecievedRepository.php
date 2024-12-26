<?php

namespace App\Repositories;

use App\Interfaces\EmailRecievedRepositoryInterface;
use App\Models\EmailReceived;
use App\Models\EmailReply;
use App\Models\EmailContent;
use App\Models\MailServer;
use App\Models\User;
use Webklex\IMAP\Facades\Client;
use Carbon\Carbon;
use Swift_SmtpTransport;
use Swift_Mailer;
use Illuminate\Support\Facades\DB;

class EmailRecievedRepository implements EmailRecievedRepositoryInterface
{
    protected $relations = [
        'user',
        'emailReply'
    ];

    public function canSend($dataId)
    {
        $emailContent = EmailContent::where('user_id', $dataId)->first();
        $mailServerSetting = MailServer::where('user_id', $dataId)->first();

        // Early exit for conditions not met
        if (!$emailContent || !$mailServerSetting) {
            return $this->generateErrorResponse("Mail server settings or email content not found");
        }

        if ($emailContent->auto_replied == 0 || $emailContent->end_auto_replied < now()->format('Y-m-d')) {
            return $this->generateErrorResponse("Auto-reply is disabled or the period has ended");
        }

        $client = Client::make([
            'host'          => $mailServerSetting->host,
            'port'          => $mailServerSetting->port,
            'encryption'    => $mailServerSetting->encryption,
            'validate_cert' => true,
            'username'      => $mailServerSetting->username,
            'password'      => $mailServerSetting->password,
            'protocol'      => $mailServerSetting->protocol,
        ]);

        $client->connect();

        // Check if 'Sent' folder exists
        if (!$client->getFolder('Sent')) {
            $client->disconnect();
            return $this->generateErrorResponse("Sent folder not found on mail server.");
        }

        return [
            "sukses" => true,
            "pesan" => "Mail server settings and content found",
            "emailContent" => $emailContent,
            "mailServerSetting" => $mailServerSetting,
            "client" => $client
        ];
    }

    public function generateErrorResponse($message)
    {
        return [
            "sukses" => false,
            "pesan" => $message,
            "datas" => null
        ];
    }

    public function sendAutoReply($mailServerSetting, $emailContent, $user, $client)
    {
        try {

            $inbox = $client->getFolder('INBOX');

            $messages = $inbox->query()
                ->since($emailContent->start_auto_replied)
                ->unseen()
                ->unanswered()
                ->get();

            if ($messages->isEmpty()) {
                $client->disconnect();
                return [
                    "sukses" => false,
                    "pesan" => "No new messages found for auto-reply."
                ];
            }

            DB::beginTransaction();

            $successCount = 0;

            foreach ($messages as $originalMessage) {
                $originSubject = $originalMessage->getSubject()->first() ?? "Tidak Ada Subject";
                $emailFrom = $originalMessage->getFrom()->first()->mail ?? null;
                $nameFrom = $originalMessage->getFrom()->first()->personal ?? "Tidak Ada Nama Pengirim";
                $body = $originalMessage->getHTMLBody() ?: $originalMessage->getTextBody() ?: "Tidak Ada Body";
                $receivedAt = $originalMessage->getDate();

                if ($emailFrom === null) {

                    DB::rollBack();
                    return [
                        "sukses" => false,
                        "pesan" => "Pengirim Tidak Mempunyai Email"
                    ];
                }

                $originalMessageId = $originalMessage->getMessageId()->first();

                // Check if this message already exists in the database
                $exists = EmailReceived::where('user_id', $user->id)
                    ->where('message_id', $originalMessageId)
                    ->where('subject', $originSubject)
                    ->where('from_email', $emailFrom)
                    ->where('body', $body)
                    ->where('received_at', $receivedAt)
                    ->first();

                // If it exists, skip it
                if ($exists) {
                    continue;
                }

                // Create a new EmailReceived record
                $received = EmailReceived::create([
                    'user_id'     => $user->id,
                    'message_id'  => $originalMessageId,
                    'subject'     => $originSubject,
                    'from_email'  => $emailFrom,
                    'from_name'   => $nameFrom,
                    'body'        => $body,
                    'received_at' => $receivedAt
                ]);

                // Create SwiftMailer transport and initialize the mailer
                $transport = (new Swift_SmtpTransport($mailServerSetting->host, 587, 'tls'))
                    ->setUsername($mailServerSetting->username)
                    ->setPassword($mailServerSetting->password);

                $mailer = new Swift_Mailer($transport);

                // Handle CC Emails
                $ccEmails = $emailContent->cc ? array_map('trim', explode(',', $emailContent->cc)) : [];

                // Prepare the reply message
                $replyMessage = (new \Swift_Message("Re: " . $originSubject))
                    ->setFrom([$user->email => $user->name])
                    ->setTo([$emailFrom => $nameFrom])
                    ->setBody($emailContent->body, 'text/html')
                    ->addPart(strip_tags($emailContent->body), 'text/plain');

                if (!empty($ccEmails)) {
                    $replyMessage->setCc($ccEmails);
                }

                $replyMessage->getHeaders()->addTextHeader('In-Reply-To', $originalMessageId);
                $replyMessage->getHeaders()->addTextHeader('References', $originalMessageId);

                // Send the reply message via SMTP
                if (!$mailer->send($replyMessage)) {
                    DB::rollBack();
                    return [
                        "sukses" => false,
                        "pesan" => "Failed to send Auto Reply"
                    ];
                }

                // Increment the success counter after sending a reply
                $successCount++;

                // Log the reply in the EmailReply model
                EmailReply::create([
                    'user_id'           => $user->id,
                    'received_email_id' => $received->id,
                    'to_email'          => $emailFrom,
                    'from_email'        => $user->email,
                    'subject'           => $originSubject,
                    'body'              => $emailContent->body,
                    'cc'                => $emailContent->cc,
                    'success'           => 1,
                    'replied_at'        => Carbon::now()
                ]);

                // Store the sent reply in the Sent folder
                $sentFolder = $client->getFolder('Sent');
                $sentFolder->appendMessage($replyMessage->toString(), [
                    'date' => Carbon::now()->toRfc2822String()
                ]);
            }

            DB::commit();

            return [
                "sukses" => true,
                "pesan" => "Success to send Auto Reply",
                "jumlah_berhasil" => $successCount
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "sukses" => false,
                "pesan" => $e->getMessage()
            ];
        }
    }


    public function processMessage($originalMessage, $emailContent, $user)
    {
        $originSubject = $originalMessage->getSubject()->first() ?? "No Subject";
        $emailFrom = $originalMessage->getFrom()->first()->mail ?? null;
        $nameFrom = $originalMessage->getFrom()->first()->personal ?? "No Sender Name";
        $body = $originalMessage->getHTMLBody() ?: $originalMessage->getTextBody() ?: "No Body";
        $receivedAt = $originalMessage->getDate();

        if ($emailFrom === null) {
            return $this->generateErrorResponse("Sender does not have an email");
        }

        $originalMessageId = $originalMessage->getMessageId()->first();

        $exists = EmailReceived::where('user_id', $user->id)
            ->where('message_id', $originalMessageId)
            ->where('subject', $originSubject)
            ->where('from_email', $emailFrom)
            ->where('body', $body)
            ->where('received_at', $receivedAt)
            ->first();

        if ($exists) {
            return ["sukses" => true]; // Skip already processed message
        }

        $received = EmailReceived::create([
            'user_id'     => $user->id,
            'message_id'  => $originalMessageId,
            'subject'     => $originSubject,
            'from_email'  => $emailFrom,
            'from_name'   => $nameFrom,
            'body'        => $body,
            'received_at' => $receivedAt
        ]);

        return ['sukses' => true, 'received' => $received];
    }

    public function sendAutoReplyEmail($originalMessage, $emailContent, $user, $mailServerSetting)
    {
        $transport = (new Swift_SmtpTransport($mailServerSetting->host, 587, 'tls'))
            ->setUsername($mailServerSetting->username)
            ->setPassword($mailServerSetting->password);

        $mailer = new Swift_Mailer($transport);
        $ccEmails = $emailContent->cc ? array_map('trim', explode(',', $emailContent->cc)) : [];

        $replyMessage = (new \Swift_Message("Re: " . $originalMessage->getSubject()->first()))
            ->setFrom([$user->email => $user->name])
            ->setTo([$originalMessage->getFrom()->first()->mail => $originalMessage->getFrom()->first()->personal])
            ->setBody($emailContent->body, 'text/html')
            ->addPart(strip_tags($emailContent->body), 'text/plain');

        if (!empty($ccEmails)) {
            $replyMessage->setCc($ccEmails);
        }

        $replyMessage->getHeaders()->addTextHeader('In-Reply-To', $originalMessage->getMessageId()->first());
        $replyMessage->getHeaders()->addTextHeader('References', $originalMessage->getMessageId()->first());

        $mailer->send($replyMessage);

        // Log the reply in the database
        EmailReply::create([
            'user_id'           => $user->id,
            'received_email_id' => $received->id,
            'to_email'          => $originalMessage->getFrom()->first()->mail,
            'from_email'        => $user->email,
            'subject'           => $originalMessage->getSubject()->first(),
            'body'              => $emailContent->body,
            'cc'                => $emailContent->cc,
            'success'           => 1,
            'replied_at'        => Carbon::now()
        ]);

        // Add message to Sent folder
        $sentFolder = $client->getFolder('Sent');
        $sentFolder->appendMessage($replyMessage->toString(), [
            'date' => Carbon::now()->toRfc2822String()
        ]);
    }

    public function getAllById($dataId, $search, $page)
    {
        try {
            $user = User::find($dataId);
            if (!$user) {
                return $this->generateErrorResponse("User not found for ID");
            }

            $query = EmailReceived::with($this->relations)->where('user_id', $user->id);

            if ($search) {
                $query->where('from_email', 'like', '%' . $search . '%')
                    ->orWhere('subject', 'like', '%' . $search . '%');
            }

            $query->orderBy('updated_at', 'desc');

            $cantSend = $this->canSend($dataId);

            if ($cantSend['sukses'] === true) {

                $sendAutoReply = $this->sendAutoReply($cantSend['mailServerSetting'], $cantSend['emailContent'], $user, $cantSend['client']);

                $result = [
                    "sukses" => true,
                    "pesan" => $sendAutoReply['pesan'],
                    "datas" => $query->paginate($page)
                ];

                if ($sendAutoReply['sukses'] === true && $sendAutoReply['jumlah_berhasil'] != 0) {

                    $result['jumlah_berhasil'] = "Number Of Recently Sent Auto Replies: " . $sendAutoReply['jumlah_berhasil'];

                } else {

                    $result['jumlah_berhasil'] = "No New Email Incoming";

                }
                return $result;
            } else {
                return [
                    "sukses" => false,
                    "pesan" => $cantSend['pesan'],
                    "datas" => $query->paginate($page)
                ];
            }

        } catch (\Exception $e) {
            return $this->generateErrorResponse($e->getMessage());
        }
    }

    // public function autoReplyForCommand($userEmail)
    // {
    //     $user = User::where('email', $userEmail)->first()->id;

    //     if (!$user) {
    //         return $this->generateErrorResponse("User not found for ID");
    //     }

    //     $cantSend = $this->canSend($user);

    //     if ($cantSend['sukses'] === true) {

    //         $sendAutoReply = $this->sendAutoReply($cantSend['mailServerSetting'], $cantSend['emailContent'], $user, $cantSend['client']);

    //         $result = [
    //             "sukses" => true,
    //             "pesan" => $sendAutoReply['pesan'],
    //         ];

    //         if ($sendAutoReply['sukses'] === true && $sendAutoReply['jumlah_berhasil'] != 0) {
    //             $result['jumlah_berhasil'] = "Number Of Recently Sent Auto Replies: " . $sendAutoReply['jumlah_berhasil'];
    //         } else {
    //             $result['jumlah_berhasil'] = "No New Email Incoming";
    //         }

    //         return $result;

    //     } else {
    //         return [
    //             "sukses" => false,
    //             "pesan" => $cantSend['pesan'],
    //         ];
    //     }
    // }
}
