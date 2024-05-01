<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
define('SUBSCRIPTION_MAX_SENT', 20);
define('NOTIFICATION_NB_MONTH_MAX', 4); // TODO: set this to 3

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

    /**
     * Get the subscription date for member.
     */
    private function getMemberSubscriptionDate($entryId)
    {
        return DB::table('VbE_view_wpforms_members_payments')
            ->where('entry_id', $entryId)
            ->orderBy('date_created_gmt')
            ->limit(1)
            ->value('date_created_gmt');
    }

    private function canBeNotified($member)
    {
        // Get the subscription date for member.
        $subscriptionDate = $this->getMemberSubscriptionDate($member->entry_id);
        if (!$subscriptionDate) {
            return false;
        }
        $subscriptionDate = Carbon::parse($subscriptionDate);
        // Subscription anniversary date.
        $subscriptionRenawalDate = Carbon::createFromDate(date('Y'), $subscriptionDate->month, $subscriptionDate->day);

        if ($subscriptionDate->year < 2023) { // for members subscribed before 2023, consider the 1 day or the year as the subscription anniversary date.
            $subscriptionRenawalDate = Carbon::createFromDate(date('Y'), 1, 1);
        }

        $today = Carbon::now();
        if ($today->diffInMonths($subscriptionRenawalDate) <= NOTIFICATION_NB_MONTH_MAX) { // anniversary in NOTIFICATION_NB_MONTH_MAX months or past NOTIFICATION_NB_MONTH_MAX months ago.
            Log::info("{$member->name} Subscription date: {$subscriptionDate} - Renewal date: {$subscriptionRenawalDate}");
            return $subscriptionRenawalDate->toDateString();
        }

        return false;
    }

    /**
     * Get the subscription date for all members.
     */


    private function notifyForSubscriptionPayments() {
        Log::info("Reminder: Sending mails subscription --> Start");

        $firstDayOfYear = Carbon::now()->startOfYear();
        $lastDayOfYear = Carbon::now()->endOfYear();
        $firstDayOfMonth = Carbon::now()->startOfMonth();
        $lastDayOfMonth = Carbon::now()->endOfMonth();
        // Payments made in current year
        $currentYearcompletedPaymentsArray = DB::table('VbE_view_wpforms_members_payments')->select('entry_id')
            ->whereBetween('VbE_view_wpforms_members_payments.date_updated_gmt', [$firstDayOfYear, $lastDayOfYear])
            ->whereIn('VbE_view_wpforms_members_payments.status', ['processed', 'completed'])
            ->get();

        $currentYearCompletedPaymentsIds = [];
        foreach($currentYearcompletedPaymentsArray as $entryId) {
            $currentYearCompletedPaymentsIds[] = $entryId->entry_id;
        }
        // Members who have not been notified yet in current year
        $members = DB::table('VbE_view_wpforms_members')
            ->whereNotIn('VbE_view_wpforms_members.entry_id', $currentYearCompletedPaymentsIds)
            ->get();

        // notifications sent on current year
        $currentYearSubscriptionNotificationArray = DB::table('VbE_custom_subscriptions_notifications')->select('entry_id')
            ->whereBetween('VbE_custom_subscriptions_notifications.member_mail_sent_at', [$firstDayOfYear, $lastDayOfYear])
            ->get();

        $currentYearSubscriptionNotificationIdsCount = [];
        foreach($currentYearSubscriptionNotificationArray as $entryId) {
            $currentYearSubscriptionNotificationIdsCount[$entryId->entry_id][] = $entryId->entry_id;
        }
        $totalSent = 0;
        foreach ($members as $member)
        {   // isNew = if the member has not been notified yet in current year
            $isNew = !array_key_exists($member->entry_id, $currentYearSubscriptionNotificationIdsCount);
            $canBenotified = $this->canBeNotified($member); // if the member can be notified
            $notificationCount = $isNew ? 1 : count($currentYearSubscriptionNotificationIdsCount[$member->entry_id]);

            $canReceive = $canBenotified !== false && ($isNew  || (!$isNew && $notificationCount < NOTIFICATION_NB_MONTH_MAX));
            // Send only 3 notifications
            if ($totalSent != SUBSCRIPTION_MAX_SENT && $canReceive)
            {
                // Current month notification
                $subcriptionNotification = DB::table('VbE_custom_subscriptions_notifications')
                    ->whereBetween('member_mail_sent_at', [$firstDayOfMonth, $lastDayOfMonth])
                    ->where('entry_id', $member->entry_id)
                    ->count();
                if ($subcriptionNotification == 0) {
                    $member_mail_sent_at = null;
                    $walynw_mail_sent_at = null;
                    $member->date = $canBenotified;
                    $body = $this->buidBody($member);

                    $member_mail = $this->isProduction ? $member->email : config('app.testmail');

                    if (Mail::to($member_mail)->send(new NotificationMail($body, 'notifications.member-subscription', "Rappel de renouvellement de votre cotisation annuelle")))
                    {
                        $member_mail_sent_at = Carbon::now();
                        Log::info("Subscription: Member Mail sent to {$member->email}");
                    }

                    $walymail = $this->isProduction ? config('app.walynw_email') : config('app.testmail');
                    if (Mail::to($walymail)->send(new NotificationMail($body, 'notifications.walynw-subscription', "Notif.  Renouvellement de la cotisation annuelle ($member->name - Rappel N° $notificationCount)")))
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
                    $totalSent++;
                } else {
                    Log::info("Subscription: Member {$member->name} has already been notified this month");
                }
            } else {
                Log::info("Subscription: Member {$member->name} can not be notified");
            }
        }

        Log::info("Reminder: Sending mails subscription --> End");
    }

    private function buidBody($member)
    {
        $amount = formatAmount($member->amount);
        return [
            'name' => $member->name,
            'email' => $member->email,
            'date' => $member->date,
            'total_amount' => formatAmount($member->amount),
            'entry_id' => $member->entry_id,
            'payment_url' => url('/payment/subscription/'. $member->entry_id),
            'professional_user_url' => $amount > 30 ? false : url('/payment/subscription/'. $member->entry_id) . '?isProfessional=true'
        ];
    }
}
