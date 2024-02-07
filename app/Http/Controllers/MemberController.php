<?php

namespace App\Http\Controllers;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MemberController extends Controller
{
    public function index()
    {
        if (request('clear'))
        {
            session()->put('search', request(''));
        }
        self::saveRequestsToSession('search');

        $members = DB::table('VbE_view_wpforms_members')
            ->select('entry_id', 'name', 'phone', 'email')
            ->where(function (Builder $query) {
                $query->orWhere('VbE_view_wpforms_members.name', 'like', '%' . session()->get('search') . '%')
                      ->orWhere('VbE_view_wpforms_members.email', 'like', '%' . session()->get('search') . '%')
                      ->orWhere('VbE_view_wpforms_members.entry_id', request('entry_id'));
            })
            ->distinct()
            ->orderBy('name', 'asc')
            ->paginate(20);

        return view(
            'members',
            [
                'first_day' => Carbon::createFromDate(date('Y'), 1, 1),
                'members' => $members,
                'search' => session()->get('search')
            ]
        );
    }

    private static function saveRequestsToSession($requestName)
    {
        if (request($requestName) != '')
        {
            session()->put($requestName, request($requestName));
        }
    }
    public function membersTransactions()
    {
        self::saveRequestsToSession('status');
        self::saveRequestsToSession('search');
        self::saveRequestsToSession('entry_id');
        self::saveRequestsToSession('year');

        if (request('clear'))
        {
            session()->put('status', request(''));
            session()->put('search', request(''));
            session()->put('entry_id', request(''));
            session()->put('year', request(''));
        }

        $status = session()->get('status');

        $statusWhere = strlen($status) == 0 ? ['VbE_view_wpforms_members.status', '<>', $status] : ['VbE_view_wpforms_members.status', '=',  $status];
        $memberInfo = [];
        $showMemberInfo = false;
        $members = DB::table('VbE_view_wpforms_members')
            ->select('VbE_view_wpforms_members.*',
                    'VbE_custom_payments_notifications.type',
                    'VbE_custom_payments_notifications.member_mail_sent_at',
                    'VbE_custom_payments_notifications.walynw_mail_sent_at')
            ->leftJoin('VbE_custom_payments_notifications',
                'VbE_custom_payments_notifications.payment_id', '=',
                'VbE_view_wpforms_members.id')
            ->where([$statusWhere]);

        if (session()->get('year'))
        {
            $date = Carbon::createFromDate(intval(session()->get('year')), 1, 1);
            $members = $members->where('date_updated_gmt', '>=', $date);
        }

        // if selected member, get member transactions
        if (session()->get('entry_id'))
        {
            $showMemberInfo = true;
            $memberInfo = DB::table('VbE_view_wpforms_members_details')
                ->where('entry_id', session()->get('entry_id'))
                ->get();
            $members = $members->where('VbE_view_wpforms_members.entry_id', session()->get('entry_id'));
        } // else apply search
        else {
            $members = $members
                ->where(function (Builder $query) {
                    $query->orWhere('VbE_view_wpforms_members.name', 'like', '%' . session()->get('search') . '%')
                        ->orWhere('VbE_view_wpforms_members.email', 'like', '%' . session()->get('search') . '%');
                });
        }
        $members = $members
            ->orderBy('name')
            ->orderByDesc('date_updated_gmt')
            ->paginate(20);


        $years = [];
        $current_year = date('Y');
        for ($i = 2020; $i <= $current_year; $i++)
        {
            $years[] = $i;
        }

        return view(
            'members_transactions',
            [
                'members' => $members,
                'showMemberInfo' => $showMemberInfo,
                'search' => session()->get('search'),
                'year' => session()->get('year'),
                'status' => $status,
                'memberInfo' => $memberInfo,
                'years' => $years,
                'statuses' => ['pending', 'completed', 'processed']
            ]
        );
    }
}
