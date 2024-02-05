<?php

namespace App\Http\Controllers;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;


class MemberController extends Controller
{
    public function index()
    {
        $members = DB::table('VbE_view_wpforms_members')
            ->select('entry_id', 'name', 'phone', 'email')
            ->where(function (Builder $query) {
                $query->orWhere('VbE_view_wpforms_members.name', 'like', '%' . request('search') . '%')
                      ->orWhere('VbE_view_wpforms_members.email', 'like', '%' . request('search') . '%')
                      ->orWhere('VbE_view_wpforms_members.entry_id', request('entry_id'));
            })
            ->distinct()
            ->orderBy('name', 'asc')
            ->paginate(20);

        return view(
            'members',
            [
                'members' => $members,
                'search' => request('search'),
            ]
        );
    }

    public function membersTransactions()
    {
        $status = request('status');
        $statusWhere = strlen($status) == 0 ? ['VbE_view_wpforms_members.status', '<>', $status] : ['VbE_view_wpforms_members.status', '=',  $status];
        $memberInfo = [];
        $showMemberInfo = false;
        $members = DB::table('VbE_view_wpforms_members')
            ->select('VbE_view_wpforms_members.*',
                    'VbE_custom_payments_history.type',
                    'VbE_custom_payments_history.member_mail_sent_at',
                    'VbE_custom_payments_history.walynw_mail_sent_at')
            ->leftJoin('VbE_custom_payments_history',
                'VbE_custom_payments_history.payment_id', '=',
                'VbE_view_wpforms_members.id')
            ->where([$statusWhere]);

            if (request('entry_id'))
            {
                $showMemberInfo = true;
                $memberInfo = DB::table('VbE_view_wpforms_members_details')
                    ->where('entry_id', request('entry_id'))
                    ->get();
                $members = $members->where('VbE_view_wpforms_members.entry_id', request('entry_id'));
            } else {
                $members = $members
                    ->where(function (Builder $query) {
                        $query->orWhere('VbE_view_wpforms_members.name', 'like', '%' . request('search') . '%')
                            ->orWhere('VbE_view_wpforms_members.email', 'like', '%' . request('search') . '%');
                    });
            }
            $members = $members
                ->orderBy('name')
                ->orderByDesc('date_updated_gmt')
                ->paginate(20);

        return view(
            'members_transactions',
            [
                'members' => $members,
                'showMemberInfo' => $showMemberInfo,
                'search' => request('search'),
                'status' => $status,
                'memberInfo' => $memberInfo,
                'statuses' => ['pending', 'completed', 'processed']
            ]
        );
    }
}
