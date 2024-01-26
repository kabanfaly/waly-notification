<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $search = request('search');
        $members = DB::table('VbE_view_wpforms_members')
            ->leftJoin('VbE_custom_payments_notifications',
                'VbE_custom_payments_notifications.entry_id', '=',
                'VbE_view_wpforms_members.entry_id')
            ->select('VbE_view_wpforms_members.*',
                'VbE_custom_payments_notifications.member_mail_sent_at',
                'VbE_custom_payments_notifications.walynw_mail_sent_at')
            ->where('VbE_custom_payments_notifications.type', "=", 'member')
            ->where('VbE_view_wpforms_members.name', 'like', '%' . $search . '%')
            ->orWhere('VbE_view_wpforms_members.email', 'like', '%' . $search . '%')
            ->orderBy('VbE_view_wpforms_members.date_created_gmt', 'desc')
            ->paginate(20);

        return view(
            'members',
            [
                'payments' => $members,
                'search' => $search,
            ]
        );
    }
}
