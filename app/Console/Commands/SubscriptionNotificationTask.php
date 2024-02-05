<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SubscriptionNotificationTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription_notification:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Member subscription notifications";

    protected $isProduction;

    public function __construct()
    {
        parent::__construct();
        $this->isProduction = config('app.env') === 'production';
        Log::info($this->isProduction ? 'Env Production' : 'Env Test');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->notifyPendingTransactions();
        $this->info('Subscription notification task executed successfully!');
    }

    private function notifyPendingTransactions() {
        Log::info("Sending mails for uncomplete transactions --> Start");
        $payments_history_ids = DB::table('VbE_custom_payments_notifications')->select('payment_id')
            ->where('type', 'pending');

        $membersWithPendingTransactions = DB::table('VbE_view_wpforms_members')
            ->where('status', 'pending')
            ->whereNotIn('id', $payments_history_ids)
            ->limit(5)
            ->get();

        foreach ($membersWithPendingTransactions as $member)
        {
            $member_mail_sent_at = null;
            $walynw_mail_sent_at = null;
            $body = $this->buidBody($member);

            $member_mail = $this->isProduction ? $member->email : config('app.testmail');

            if (Mail::to($member_mail)->send(new NotificationMail($body, 'notifications.member-pending-subscription', 'Paiement en attente')))
            {
                $member_mail_sent_at = Carbon::now();
                Log::info("Member Mail sent to {$member->email}");
            }

            $walymail = $this->isProduction ? config('app.walynw_email') : config('app.testmail');
            if (Mail::to($walymail)->send(new NotificationMail($body, 'notifications.walynw-pending-subscription', 'Notification de paiement en attente')))
            {
                $walynw_mail_sent_at = Carbon::now();
                Log::info("Walynw Mail sent to {$member->email}");
            }

            DB::table('VbE_custom_payments_notifications')->insert([
                [
                    'payment_id' => $member->id,
                    'type' => 'pending',
                    'member_mail_sent_at' => $member_mail_sent_at,
                    'walynw_mail_sent_at' => $walynw_mail_sent_at
                ],
            ]);
        }

        Log::info("Sending mails for uncomplete transactions --> End");
    }

    private function buidBody($notification)
    {
        return [
            'name' => $notification->name,
            'email' => $notification->email,
            'total_amount' => $notification->total_amount,
            'date_created_gmt' => $notification->date_created_gmt,
            'transaction_id' => $notification->transaction_id,
            'currency' => $notification->currency,
        ];
    }
}
