<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

define('MEMBER_COMPLETED_PAYMENT_MAIL_TEMPLATE', 'notifications.member-payment-confirmation');
define('MEMBER_PENDING_PAYMENT_MAIL_TEMPLATE', 'notifications.member-pending-subscription');
define('MEMBER_COMPLETED_PAYMENT_SUBJECT', 'Confirmation de paiement');
define('MEMBER_PENDING_PAYMENT_SUBJECT', 'Paiement en attente');

define('WALY_COMPLETED_PAYMENT_MAIL_TEMPLATE', 'notifications.member-payment-confirmation');
define('WALY_PENDING_PAYMENT_MAIL_TEMPLATE', 'notifications.member-pending-subscription');
define('WALY_COMPLETED_PAYMENT_SUBJECT', 'Notification de paiement');
define('WALY_PENDING_PAYMENT_SUBJECT', 'Notification de paiement en attente');
define('MAX_SENT', 5);

class MailNotificationTask extends Command
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

    protected $memberMailTemplates = [
        'pending' => MEMBER_PENDING_PAYMENT_MAIL_TEMPLATE,
        'completed' => MEMBER_COMPLETED_PAYMENT_MAIL_TEMPLATE,
        'processed' => MEMBER_COMPLETED_PAYMENT_MAIL_TEMPLATE,
    ];

    protected $walyMailTemplates = [
        'pending' => WALY_PENDING_PAYMENT_MAIL_TEMPLATE,
        'completed' => WALY_COMPLETED_PAYMENT_MAIL_TEMPLATE,
        'processed' =>WALY_COMPLETED_PAYMENT_MAIL_TEMPLATE,
    ];

    protected $memberMailSubjects = [
        'pending' => MEMBER_PENDING_PAYMENT_SUBJECT,
        'completed' => MEMBER_COMPLETED_PAYMENT_SUBJECT,
        'processed' => MEMBER_COMPLETED_PAYMENT_SUBJECT,
    ];

    protected $walyMailSubjects = [
        'pending' => WALY_PENDING_PAYMENT_SUBJECT,
        'completed' => WALY_COMPLETED_PAYMENT_SUBJECT,
        'processed' => WALY_COMPLETED_PAYMENT_SUBJECT,
    ];

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

        $this->info('Notification task executed successfully!');
    }

    /**
     * Send mails for all new payments.
     */
    private function sendNewNotifications()
    {
        Log::info("Sending mails for new payments --> Start");

        $notifications = DB::table('VbE_view_wpforms_members')
            ->select('VbE_view_wpforms_members.*',
                'VbE_custom_payments_history.payment_id',
                'VbE_custom_payments_history.type',
                'VbE_custom_payments_history.member_mail_sent_at',
                'VbE_custom_payments_history.walynw_mail_sent_at')
            ->leftJoin('VbE_custom_payments_history',
                'VbE_custom_payments_history.payment_id', '=',
                'VbE_view_wpforms_members.id')
            ->orderBy('VbE_view_wpforms_members.date_created_gmt', 'desc')
            ->get();

        $totalSent = 0;
        foreach ($notifications as $notification)
        {
            if ($totalSent != MAX_SENT)
            {
                $member_mail_sent_at = null;
                $walynw_mail_sent_at = null;
                $body = $this->buidBody($notification);
                $status = $notification->status;
                $member_mail = $this->isProduction ? $notification->email : config('app.testmail');
                $walymail = $this->isProduction ? config('app.walynw_email') : config('app.testmail');

                if ($notification->type == null) { // new notification: insertion in VbE_custom_payments_history table
                    Log::info("New Notification");
                    $this->sendMailToMember($notification, $body, $member_mail, $member_mail_sent_at);
                    $this->sendMailToWaly($notification, $body, $walymail, $walynw_mail_sent_at);

                    DB::table('VbE_custom_payments_history')->insert([
                        [
                            'payment_id' => $notification->id,
                            'type' => $status,
                            'member_mail_sent_at' => $member_mail_sent_at,
                            'walynw_mail_sent_at' => $walynw_mail_sent_at
                        ],
                    ]);
                    $totalSent++;
                } elseif ($notification->type == 'pending' && $notification->status !== 'pending') // Member has completed his pending transaction: Update current notification
                {
                    Log::info("Update Notification: Pending -> complete");

                    $this->sendMailToMember($notification, $body, $member_mail, $member_mail_sent_at);
                    $this->sendMailToWaly($notification, $body, $walymail, $walynw_mail_sent_at);

                    DB::table('VbE_custom_payments_history')
                        ->where('payment_id', $notification->id)
                        ->update(
                            [
                                'type' => $status,
                                'member_mail_sent_at' => $member_mail_sent_at,
                                'walynw_mail_sent_at' => $walynw_mail_sent_at
                            ],
                        );
                    $totalSent++;
                } elseif ($notification->payment_id !== null && $notification->member_mail_sent_at == null) // Resend mail to member if sending failed
                {
                    Log::info("Update Notification: Mail to member");

                    $this->sendMailToMember($notification, $body, $member_mail, $member_mail_sent_at);

                    DB::table('VbE_custom_payments_history')
                        ->where('payment_id', $notification->id)
                        ->update(
                            [
                                'type' => $status,
                                'member_mail_sent_at' => $member_mail_sent_at,
                            ],
                        );
                    $totalSent++;
                } elseif ($notification->payment_id !== null && $notification->walynw_mail_sent_at == null) // Resend mail to waly if sending failed
                {
                    Log::info("Update Notification: Mail to Waly");

                    $this->sendMailToWaly($notification, $body, $walymail, $walynw_mail_sent_at);

                    DB::table('VbE_custom_payments_history')
                        ->where('payment_id', $notification->id)
                        ->update(
                            [
                                'type' => $status,
                                'walynw_mail_sent_at' => $walynw_mail_sent_at
                            ],
                        );
                    $totalSent++;
                }
                Log::info("Sending mails for new payments --> End");
            }
        }
    }

    private function sendMailToMember($notification, $body, $member_mail, &$member_mail_sent_at)
    {
        $status = $notification->status;
        // Send mail to member
        if (Mail::to($member_mail)->send(new NotificationMail($body, $this->memberMailTemplates[$status], $this->memberMailSubjects[$status])))
        {
            $member_mail_sent_at = Carbon::now();
            Log::info("Member Mail sent to {$notification->email}");
        }
    }

    private function sendMailToWaly($notification, $body, $walymail, &$walynw_mail_sent_at)
    {
        $status = $notification->status;
        // Send mail to waly
        if (Mail::to($walymail)->send(new NotificationMail($body, $this->walyMailTemplates[$status], $this->walyMailSubjects[$status])))
        {
            $walynw_mail_sent_at = Carbon::now();
            Log::info("Walynw Mail sent to {$notification->email}");
        }
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
