<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{

    public function index()
    {
        $search = request('search');
        $payments = DB::table('VbE_view_wpforms_payments_done')
            ->leftJoin('VbE_custom_payments_notifications',
                'VbE_custom_payments_notifications.entry_id', '=',
                'VbE_view_wpforms_payments_done.entry_id')
            ->select('VbE_view_wpforms_payments_done.*',
                'VbE_custom_payments_notifications.member_mail_sent_at',
                'VbE_custom_payments_notifications.walynw_mail_sent_at')
            ->where('VbE_custom_payments_notifications.type', "=", 'payment')
            ->where('VbE_view_wpforms_payments_done.name', 'like', '%' . $search . '%')
            ->orWhere('VbE_view_wpforms_payments_done.email', 'like', '%' . $search . '%')
            ->orderBy('VbE_view_wpforms_payments_done.date_created_gmt', 'desc')
            ->paginate(20);

        return view(
            'index',
            [
                'payments' => $payments,
                'search' => $search,
            ]
        );
    }
}
