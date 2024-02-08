<?php

namespace App\Http\Controllers;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MemberController extends Controller
{

    /**
     * Members
     */

    public function members()
    {
        if (request('clear'))
        {
            session()->put('search', request(''));
        }
        self::saveRequestsToSession('search');

        $members = DB::table('VbE_view_wpforms_members')
            ->where(function (Builder $query) {
                $query->orWhere('VbE_view_wpforms_members.name', 'like', '%' . session()->get('search') . '%')
                      ->orWhere('VbE_view_wpforms_members.email', 'like', '%' . session()->get('search') . '%')
                      ->orWhere('VbE_view_wpforms_members.entry_id', request('entry_id'));
            })
            ->orderBy('name', 'asc')
            ->paginate(20);

        return view(
            'members',
            [
                'members' => $members,
                'search' => session()->get('search')
            ]
        );
    }

    /**
     * Members subscriptions
     */

    public function membersSubscriptions()
    {
        $currentYear = date('Y');
        if (request('clear'))
        {
            session()->put('search', '');
            session()->put('year', '');
        }

        self::saveRequestsToSession('year');
        self::saveRequestsToSession('search');

        if (!request('year')) {
            session()->put('year', $currentYear);
        }

        $members = DB::table('VbE_view_wpforms_members')
            ->select('VbE_view_wpforms_members.*',
                'VbE_custom_subscriptions_notifications.member_mail_sent_at',
                'VbE_custom_subscriptions_notifications.walynw_mail_sent_at'
                )
            ->leftJoin('VbE_custom_subscriptions_notifications',
                'VbE_custom_subscriptions_notifications.entry_id', '=',
                'VbE_view_wpforms_members.entry_id')
            ->where(function (Builder $query) {
                $query->orWhere('VbE_view_wpforms_members.name', 'like', '%' . session()->get('search') . '%')
                      ->orWhere('VbE_view_wpforms_members.email', 'like', '%' . session()->get('search') . '%')
                      ->orWhere('VbE_view_wpforms_members.entry_id', request('entry_id'));
            });

        if (session()->get('year'))
        {
            $firstDayOfYear = Carbon::createFromDate(session()->get('year'), 1, 1);
            $lastDayOfYear = Carbon::createFromDate(session()->get('year'), 12, 31);
            $members = $members
                ->whereNotNull('VbE_custom_subscriptions_notifications.member_mail_sent_at')
                ->whereBetween('VbE_custom_subscriptions_notifications.member_mail_sent_at', [$firstDayOfYear, $lastDayOfYear]);
        }

        $members = $members->orderBy('name', 'asc')
            ->paginate(20);

        return view(
            'members_subscriptions',
            [
                'members' => $members,
                'year' => session()->get('year'),
                'years' => $this->years(),
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

    /**
     * Members transactions
     */
    public function membersTransactions()
    {
        self::saveRequestsToSession('status');
        self::saveRequestsToSession('search');
        self::saveRequestsToSession('entry_id');
        self::saveRequestsToSession('year');

        if (request('clear'))
        {
            session()->put('status', '');
            session()->put('search', '');
            session()->put('entry_id', '');
            session()->put('year', '');
        }

        $status = session()->get('status');

        $statusWhere = strlen($status) == 0 ? ['VbE_view_wpforms_members_payments.status', '<>', $status] : ['VbE_view_wpforms_members_payments.status', '=',  $status];
        $memberInfo = [];
        $showMemberInfo = false;
        $members = DB::table('VbE_view_wpforms_members_payments')
            ->select('VbE_view_wpforms_members_payments.*',
                    'VbE_custom_payments_notifications.type',
                    'VbE_custom_payments_notifications.member_mail_sent_at',
                    'VbE_custom_payments_notifications.walynw_mail_sent_at')
            ->leftJoin('VbE_custom_payments_notifications',
                'VbE_custom_payments_notifications.payment_id', '=',
                'VbE_view_wpforms_members_payments.id')
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
            $members = $members->where('VbE_view_wpforms_members_payments.entry_id', session()->get('entry_id'));
        } // else apply search
        else {
            $members = $members
                ->where(function (Builder $query) {
                    $query->orWhere('VbE_view_wpforms_members_payments.name', 'like', '%' . session()->get('search') . '%')
                        ->orWhere('VbE_view_wpforms_members_payments.email', 'like', '%' . session()->get('search') . '%');
                });
        }
        $members = $members
            ->orderByDesc('date_updated_gmt')
            ->orderBy('name')
            ->paginate(20);

        return view(
            'members_transactions',
            [
                'members' => $members,
                'showMemberInfo' => $showMemberInfo,
                'search' => session()->get('search'),
                'year' => session()->get('year'),
                'status' => $status,
                'memberInfo' => $memberInfo,
                'years' => $this->years(),
                'statuses' => ['pending', 'completed', 'processed']
            ]
        );
    }

    private function years()
    {
        $years = [];
        $current_year = date('Y');
        for ($i = 2020; $i <= $current_year; $i++)
        {
            $years[] = $i;
        }
        return $years;
    }
}
