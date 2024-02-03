<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

    public function index()
    {
        $search = request('search');
        $payments = DB::table('VbE_view_wpforms_payments_done')
            ->select('VbE_view_wpforms_payments_done.*',
                'VbE_custom_payments_history.member_mail_sent_at',
                'VbE_custom_payments_history.walynw_mail_sent_at')
            ->leftJoin('VbE_custom_payments_history',
                'VbE_custom_payments_history.payment_id', '=',
                'VbE_view_wpforms_payments_done.id')
            ->where('VbE_view_wpforms_payments_done.name', 'like', '%' . $search . '%')
            ->orWhere('VbE_view_wpforms_payments_done.email', 'like',  '%' . $search . '%')
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

