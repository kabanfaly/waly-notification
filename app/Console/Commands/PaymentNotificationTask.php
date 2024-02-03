<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PaymentNotificationTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment_notification:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Payments notification';

    protected $isProduction;

    public function __construct()
    {
        parent::__construct();
        $this->isProduction = config('app.env') === 'production';
        Log::info($this->isProduction ? 'Env Production' : 'Env Test');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->sendNewNotifications();
        $this->updateMemberNotifications();
        $this->updateWalyNotifications();

        $this->info('Notification task executed successfully!');
    }

    /**
     * Send mails for all new payments.
     */
    private function sendNewNotifications()
    {
        Log::info("Sending mails for new payments --> Start");

        $payments_notifications_ids = DB::table('VbE_custom_payments_notifications')->select('payment_id');
        $notifications = DB::table('VbE_view_wpforms_payments_done')
            ->whereNotIn('id', $payments_notifications_ids)
            ->limit(5)
            ->get();

        foreach ($notifications as $notification)
        {
            $member_mail_sent_at = null;
            $walynw_mail_sent_at = null;
            $body = $this->buidBody($notification);

            $member_mail = $this->isProduction ? $notification->email : config('app.testmail');

            if (Mail::to($member_mail)->send(new NotificationMail($body, 'notifications.member-payment-confirmation', 'Confirmation de paiement')))
            {
                $member_mail_sent_at = Carbon::now();
                Log::info("Member Mail sent to {$notification->email}");
            }

            $walymail = $this->isProduction ? config('app.walynw_email') : config('app.testmail');
            if (Mail::to($walymail)->send(new NotificationMail($body, 'notifications.walynw-payment-notification', 'Notification de paiement')))
            {
                $walynw_mail_sent_at = Carbon::now();
                Log::info("Walynw Mail sent to {$notification->email}");
            }

            DB::table('VbE_custom_payments_notifications')->insert([
                [
                    'payment_id' => $notification->id,
                    'member_mail_sent_at' => $member_mail_sent_at,
                    'walynw_mail_sent_at' => $walynw_mail_sent_at
                ],
            ]);
        }
        Log::info("Sending mails for new payments --> End");
    }

    private function updateMemberNotifications()
    {
        Log::info("Resending mails to member for new payments --> Start");

        $payments_notifications_ids = DB::table('VbE_custom_payments_notifications')
            ->select('payment_id')
            ->where('member_mail_sent_at', null);

        $notifications = DB::table('VbE_view_wpforms_payments_done')
            ->whereIn('id', $payments_notifications_ids)
            ->limit(5)
            ->get();

        foreach ($notifications as $notification)
        {
            $member_mail_sent_at = null;
            $member_mail = $this->isProduction ? $notification->email : config('app.testmail');

            if (Mail::to($member_mail)->send(new NotificationMail($this->buidBody($notification), 'notifications.member-payment-confirmation', 'Confirmation de paiement')))
            {
                $member_mail_sent_at = Carbon::now();
                Log::info("Update - Member Mail sent to {$notification->email}");
            }

            DB::table('VbE_custom_payments_notifications')
                ->where('payment_id', $notification->id)
                ->update(['member_mail_sent_at' => $member_mail_sent_at]);
        }
        Log::info("Resending mails to members for new payments --> End");
    }

    private function updateWalyNotifications()
    {
        Log::info("Resending mails to waly for new payments --> Start");

        $payments_notifications_ids = DB::table('VbE_custom_payments_notifications')
            ->select('payment_id')
            ->where('walynw_mail_sent_at', null);

        $notifications = DB::table('VbE_view_wpforms_payments_done')
            ->whereIn('id', $payments_notifications_ids)
            ->limit(5)
            ->get();

        foreach ($notifications as $notification)
        {
            $walynw_mail_sent_at = null;
            $walymail = $this->isProduction ? config('app.walynw_email') : config('app.testmail');

            if (Mail::to($walymail)->send(new NotificationMail($this->buidBody($notification), 'notifications.walynw-payment-notification', 'Notification de paiement')))
            {
                $walynw_mail_sent_at = Carbon::now();
                Log::info("Update - Walynw Mail sent to {$notification->email}");
            }


            DB::table('VbE_custom_payments_notifications')
                ->where('payment_id', $notification->id)
                ->update(['walynw_mail_sent_at' => $walynw_mail_sent_at]);
        }
        Log::info("Resending mails to waly for new payments --> End");
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
