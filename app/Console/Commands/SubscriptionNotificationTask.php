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
        $this->notifyForSubscriptionPayments();
        $this->info('Subscription notification task executed successfully!');
    }

    private function notifyForSubscriptionPayments() {
        Log::info("Reminder: Sending mails subscription --> Start");
        $firstDayOfYear = Carbon::createFromDate(date('Y'), 1, 1);
        $lastDayOfYear = Carbon::createFromDate(date('Y'), 12, 31);

        // Members who haven't made a payment yet for current year
        $subcriptionNotificationArray = DB::table('VbE_custom_subscriptions_notifications')->select('VbE_custom_subscriptions_notifications.entry_id')
            ->join('VbE_view_wpforms_members_payments', 'VbE_view_wpforms_members_payments.entry_id', '=',
            'VbE_custom_subscriptions_notifications.entry_id')
            ->whereBetween('VbE_custom_subscriptions_notifications.member_mail_sent_at', [$firstDayOfYear, $lastDayOfYear])
            ->whereBetween('VbE_view_wpforms_members_payments.date_updated_gmt', [$firstDayOfYear, $lastDayOfYear])
            ->whereIn('VbE_view_wpforms_members_payments.status', ['processed', 'completed'])
            ->get();

        $subcriptionNotificationIds = [];
        foreach($subcriptionNotificationArray as $entryId) {
            $subcriptionNotificationIds[] = $entryId->entry_id;
        }
        $membersWithPendingTransactions = DB::table('VbE_view_wpforms_members')
            ->whereNotIn('entry_id', ($subcriptionNotificationIds))
            ->get();

        foreach ($membersWithPendingTransactions as $member)
        {
            $member_mail_sent_at = null;
            $walynw_mail_sent_at = null;
            $body = $this->buidBody($member);

            $member_mail = $this->isProduction ? $member->email : config('app.testmail');

            if (Mail::to($member_mail)->send(new NotificationMail($body, 'notifications.member-subscription', 'Rappel de paiement de cotisation')))
            {
                $member_mail_sent_at = Carbon::now();
                Log::info("Subscription: Member Mail sent to {$member->email}");
            }

            $walymail = $this->isProduction ? config('app.walynw_email') : config('app.testmail');
            if (Mail::to($walymail)->send(new NotificationMail($body, 'notifications.walynw-subscription', 'Notification de rappel de paiement en cotisation')))
            {
                $walynw_mail_sent_at = Carbon::now();
                Log::info("Subscription: Walynw Mail sent to {$member->email}");
            }

            DB::table('VbE_custom_subscriptions_notifications')->insert([
                [
                    'entry_id' => $member->entry_id,
                    'member_mail_sent_at' => $member_mail_sent_at,
                    'walynw_mail_sent_at' => $walynw_mail_sent_at
                ],
            ]);
        }

        Log::info("Reminder: Sending mails subscription --> End");
    }

    private function buidBody($notification)
    {
        return [
            'name' => $notification->name,
            'email' => $notification->email,
            'total_amount' => str_replace('&#36; ', '', $notification->amount)
        ];
    }
}
