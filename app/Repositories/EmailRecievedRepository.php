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

    private function canSend($dataId)
    {

        $emailContent = EmailContent::where('user_id', $dataId)->first();
        $mailServerSetting = MailServer::where('user_id', $dataId)->first();


        if (!$emailContent || !$mailServerSetting || $emailContent->auto_replied == 0 || $emailContent->end_auto_replied < now()->format('Y-m-d')) {
            $message = !$emailContent || $emailContent->auto_replied == 0
                       ? "Auto-reply is disabled"
                       : (!$mailServerSetting ? "Mail server settings not found" : "Auto-reply period has ended");
            return [
                "sukses" => false,
                "pesan" => $message,
                "datas" => null
            ];
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

        if (!$client->getFolder('Sent')) {
            $client->disconnect();
            return [
                "sukses" => false,
                "pesan" => "Sent folder not found on mail server."
            ];
        }

        return [
            "sukses" => true,
            "pesan" => "Mail server settings and content found",
            "emailContent" => $emailContent,
            "mailServerSetting" => $mailServerSetting,
            "client" => $client
        ];
    }

    private function sendAutoReply($mailServerSetting, $emailContent, $user, $client)
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

                $exists = EmailReceived::where('user_id', $user->id)
                    ->where('message_id', $originalMessageId)
                    ->where('subject', $originSubject)
                    ->where('from_email', $emailFrom)
                    ->where('body', $body)
                    ->where('received_at', $receivedAt)
                    ->first();

                if ($exists) {
                    continue; // Skip this message if it already exists
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

                $transport = (new Swift_SmtpTransport($mailServerSetting->host, 587, 'tls'))
                    ->setUsername($mailServerSetting->username)
                    ->setPassword($mailServerSetting->password);

                $mailer = new Swift_Mailer($transport);

                $ccEmails = $emailContent->cc ? array_map('trim', explode(',', $emailContent->cc)) : [];

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

                if (!$mailer->send($replyMessage)) {
                    DB::rollBack();
                    return [
                        "sukses" => false,
                        "pesan" => "Failed to send Auto Reply"
                    ];
                }

                // Increment the success counter after sending a reply
                $successCount++;

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



    public function getAllById($dataId, $search, $page)
    {
        try {

            $user = User::find($dataId);

            if (!$user) {
                return [
                    "sukses" => false,
                    "pesan" => "User not found for ID"
                ];
            }

            $model = EmailReceived::with($this->relations)->where('user_id', $user->id);

            if ($search === null) {
                $query = $model->orderBy('updated_at','desc');
            } else {
                $query = $model
                ->where('from_email', 'like', '%'.$search.'%')
                ->orWhere('subject', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc');
            }

            $cantSend = $this->canSend($dataId);

            if ($cantSend['sukses'] === true) {

                $sendAutoReply = $this->sendAutoReply(
                    $cantSend['mailServerSetting'],
                    $cantSend['emailContent'],
                    $user,
                    $cantSend['client']
                );

                if ($sendAutoReply['sukses'] === true) {
                    if ($sendAutoReply['jumlah_berhasil'] != 0) {
                        return [
                            "sukses" => true,
                            "pesan"  => $sendAutoReply['pesan'],
                            "datas"  => $query->paginate($page),
                            'jumlah_berhasil' => "Number Of Recently Sent Auto Replies " . $sendAutoReply['jumlah_berhasil']
                        ];
                    } else {
                        return [
                            "sukses" => true,
                            "pesan"  => $sendAutoReply['pesan'],
                            "datas"  => $query->paginate($page),
                            'jumlah_berhasil' => "No New Email Incoming"
                        ];
                    }


                } else {
                    return [
                        "sukses" => false,
                        "pesan" => $sendAutoReply['pesan'],
                        "datas"  => $query->paginate($page)
                    ];
                }

            } else {
                return [
                    "sukses" => false,
                    "pesan"  => $cantSend['pesan'],
                    "datas"  =>  $query->paginate($page)
                ];
            }


        } catch (\Exception $e) {

            $model = EmailReceived::with($this->relations)->where('user_id', $user->id);

            if ($search === null) {
                $query = $model->orderBy('updated_at','desc');
            } else {
                $query = $model
                ->where('from_email', 'like', '%'.$search.'%')
                ->orWhere('subject', 'like', '%'.$search.'%')
                ->orderBy('updated_at','desc');
            }

            return [
                "sukses" => false,
                "pesan" =>  $e->getMessage(),
                "datas"  => $query->paginate($page)
            ];
        }
    }
}
