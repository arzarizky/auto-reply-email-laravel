<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\EmailRecievedRepository;
use Carbon\Carbon;
use App\Models\User;  // Import model User

class CheckAndReplyEmails extends Command
{
    protected $signature = 'email:check-reply';
    protected $description = 'Check and send auto-replies for emails';
    protected $emailRecievedRepo;

    public function __construct(EmailRecievedRepository $emailRecievedRepo)
    {
        parent::__construct();
        $this->emailRecievedRepo = $emailRecievedRepo;
    }

    public function handle()
    {
        // Timestamp saat ini
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');
        $this->info("\n");
        $this->info("\033[1;32m-----------------------------------------------------------\033[0m");
        $this->info("\033[1;32m[$timestamp] Checking and Replying to Emails - Process Started\033[0m");
        $this->info("\033[1;32m-----------------------------------------------------------\033[0m");
        $this->info("\n");  // Adding space between sections

        $users = User::get();  // Get all users
        $completedUsers = 0;

        foreach ($users as $user) {
            $response = $this->emailRecievedRepo->canSend($user->id);

            if ($response['sukses'] === false) {
                $this->info("\033[1;33m[$timestamp] ⏳ Skipping user: \033[1;34m{$user->email}\033[0m");
                $this->info("\033[1;31m[$timestamp] 🚫 Reason: \033[0m{$response['pesan']}");
                $this->info("\n");
                continue;
            }

            $sendAutoReply = $this->emailRecievedRepo->sendAutoReply(
                $response['mailServerSetting'],
                $response['emailContent'],
                $user,
                $response['client']
            );

            if ($sendAutoReply['sukses'] === true) {
                if ($sendAutoReply['sukses'] === true && $sendAutoReply['jumlah_berhasil'] != 0) {
                    $completedUsers++;
                    $this->info("\033[1;32m[$timestamp] ✅ Number Of Recently Sent Auto Replies {$sendAutoReply['jumlah_berhasil']} for \033[1;34m{$user->email}\033[0m");
                    $result['jumlah_berhasil'] = "Number Of Recently Sent Auto Replies: " . $sendAutoReply['jumlah_berhasil'];

                } else {
                    $this->info("\033[1;33m[$timestamp] ⏳ Skipping user: \033[1;34m{$user->email}\033[0m");
                    $this->info("\033[1;32m[$timestamp] ✅ No New Email Incoming {$user->email}\033[0m");
                }
            } else {

                if ($sendAutoReply['pesan'] === "No new messages found for auto-reply.") {
                    $this->info("\033[1;33m[$timestamp] ⏳ Skipping user: \033[1;34m{$user->email}\033[0m");
                    $this->info("\033[1;32m[$timestamp] ✅ No New Email Incoming {$user->email}\033[0m");
                } else {
                    $this->info("\033[1;31m[$timestamp] ❌ Failed to send auto-reply to: \033[1;34m{$user->email}\033[0m");
                    $this->info("\033[1;31m[$timestamp] 🚫 Reason: \033[0m{$sendAutoReply['pesan']}");
                }

            }

            $this->info("\n");
        }

        // Output hasil akhir
        $this->info("\033[1;32m[$timestamp] -----------------------------------------------------------\033[0m");
        $this->info("\033[1;32m[$timestamp] Email Checking and Replying Process Completed\033[0m");
        $this->info("\033[1;32m[$timestamp] -----------------------------------------------------------\033[0m");
        $this->info("\n");  // Adding space before final result output

        if ($completedUsers > 0) {
            $this->info("\033[1;36m[$timestamp] ✅ Total Auto-Replies New Messages Sent: $completedUsers\033[0m");
            $this->info("\n");
        } else {
            $this->info("\033[1;33m[$timestamp] 🔔 No new auto-replies sent.\033[0m");
            $this->info("\n");
        }
    }
}
