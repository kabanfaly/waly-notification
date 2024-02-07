@php
    $inputClass = 'rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500';
@endphp
<x-layout title="Historique des transactions" active="transactions">
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg md:ml-10 md:mr-10 text-sm text-left text-gray-500 dark:text-gray-400">
        <x-table-search action="/transactions" :search="$search" has_filter>
            <div class="mb-4 pt-4 bg-gray-100 rounded-lg">
                <div class="grid gap-4 mb-6 md:grid-cols-4 md:mr-10 rounded-lg">
                    <div class="flex mb-4">
                        <x-fa-icon color="gray" icon="puzzle-piece" />
                        <select id="status" name="status" class="{{ $inputClass }}">
                            <option value=""> Tous les status </option>
                            @foreach ($statuses as $s)
                                @php
                                    $selected = $status == $s ? 'selected' : '';
                                @endphp
                                <option value="{{ $s }}" {{ $selected }}>{!! __('notification.' . $s) !!}</option>;
                            @endforeach
                        </select>
                    </div>
                    <div class="flex mb-4">
                        <span class="mt-2">A partir de &nbsp;</span>
                        <x-fa-icon color="gray" icon="calendar" />
                        <select id="year" name="year" class="{{ $inputClass }}">
                            @foreach ($years as $y)
                                @php
                                    $selected = $year == $y ? 'selected' : '';
                                @endphp
                                <option value="{{ $y }}" {{ $selected }}>{!!  $y !!}</option>;
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </x-table-search>
        @if ($showMemberInfo)
            @php
                $diplayedFields = [];
                $name = $gender = $email = $phone = $age = $degree = $domain = $status = $linkedin = "";
            @endphp
            @foreach ($memberInfo as $info)
                @if (!in_array($info->field_id, $diplayedFields))
                    @if ($info->field_id == 26)
                        @php
                            $gender = $info->value;
                        @endphp
                    @endif
                    @if ($info->field_id == 12)
                        @php
                            $name = $info->value;
                        @endphp
                    @endif
                    @if ($info->field_id == 10)
                        @php
                            $email = $info->value;
                        @endphp
                    @endif
                    @if ($info->field_id == 16)
                        @php
                            $phone = $info->value;
                        @endphp
                    @endif
                    @if ($info->field_id == 27)
                        @php
                            $age = $info->value;
                        @endphp
                    @endif
                    @if ($info->field_id == 33)
                        @php
                            $degree = $info->value;
                        @endphp
                    @endif
                    @if ($info->field_id == 34)
                        @php
                            $domain = $info->value;
                        @endphp
                    @endif
                    @if ($info->field_id == 142)
                        @php
                            $linkedin = $info->value;
                        @endphp
                    @endif
                    @if ($info->field_id == 88 || $info->field_id == 143)
                        @php
                            $status = $info->value;
                        @endphp
                    @endif
                    @php
                        $diplayedFields[] = $info->field_id;
                    @endphp
                @endif
            @endforeach
            <div class="flex flex-wrap justify-between mx-auto p-4">
                <div>
                    <div class="flex text-2xl font-semibold text-blue-700">
                        <x-fa-simple-icon icon="user" />{{ ucwords($name) }}
                    </div>
                    <div class="flex font-normal text-gray-500">
                        <x-fa-simple-icon icon="venus-mars" />{{ $gender }}
                    </div>
                    <div class="flex font-normal text-gray-500">
                        <x-fa-simple-icon icon="envelope" />{{ $email }}
                    </div>
                    <div class="flex font-normal text-gray-500">
                        <x-fa-simple-icon icon="phone" />{{ $phone }}
                    </div>
                    <div class="flex font-normal text-gray-500">
                        <span class="font-bold">Age : </span>&nbsp;{{ $age }}
                    </div>
                </div>
                <div>
                    <div class="flex font-normal text-gray-500">
                        <x-fa-simple-icon icon="certificate" />{!! $degree !!}
                    </div>
                    <div class="flex font-normal text-gray-500">
                        <x-fa-simple-icon icon="suitcase" />{{ $domain }}
                    </div>
                    <div class="flex font-normal text-gray-500">
                        <x-fa-simple-icon icon="money-bill" />{!! $status !!}
                    </div>
                    @if ($linkedin)
                        <div class="flex font-normal text-gray-500">
                            <i class="fa-brands fa-linkedin text-[#0F3B61] mr-2 mt-1"></i><a href="https://{{ $linkedin  }}" target="_blank">{!! $linkedin !!}</a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        <table class="w-full">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="py-3 px-6">
                        #
                    </th>
                    @if (!$showMemberInfo)
                        <th scope="col" class="py-3 px-6">
                            Nom
                        </th>
                        <th scope="col" class="py-3 px-6">
                            Téléphone
                        </th>
                    @endif
                    <th scope="col" class="py-3 px-6">
                        Paiement
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Montant
                    </th>
                    <th scope="col" class="py-3 px-6">
                        ID Transaction
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Date
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Description
                    </th>
                    <th scope="col" class="py-3 px-6 text-orange-500">
                        Date d'envoi membre
                    </th>
                    <th scope="col" class="py-3 px-6 text-green-500">
                        Date d'envoi waly
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $num = 1;
                @endphp
                @foreach ($members as $member)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="py-4 px-6">
                            {{ $num++ }}
                        </td>
                        @if (!$showMemberInfo)
                            <th scope="row"
                                class="flex items-center py-4 px-6 text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="pl-3">
                                    <div class="text-base font-semibold">
                                        <a class="font-bold no-underline" href="transactions?entry_id={{ $member->entry_id }}">
                                            {{ ucwords($member->name) }}
                                        </a>
                                    </div>
                                    <div class="font-normal text-gray-500">{{ $member->email }}</div>
                                </div>
                            </th>
                            <td class="py-4 px-6">
                                {{ $member->phone }}
                            </td>
                        @endif
                        <td class="py-4 px-6">
                            @if ($member->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                                    {!! __('notification.' . $member->status) !!}
                                </span>
                            @else
                                <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                    {!! __('notification.' . $member->status) !!}
                                </span>
                            @endif

                        </td>
                        <td class="py-4 px-6">
                            {{ number_format($member->total_amount, 2) }} {{ $member->currency }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $member->transaction_id }}
                        </td>
                        <td class="py-4 px-6 font-bold">
                            {{ $member->date_updated_gmt }}
                        </td>
                         <td class="py-4 px-6 text-xs text-blue-400">
                            @if ($member->type === 'pending')
                                <span>Notification de rappel de paiement en attente</span>
                            @elseif ($member->type === 'completed' || $member->type === 'processed')
                                <span>Notification de transaction complétée</span>
                            @else
                                <span></span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            {{ $member->member_mail_sent_at }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $member->walynw_mail_sent_at }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mx-auto pb-10 pt-10">
            {{ $members->links() }} Total : {{ $members->total() }}
        </div>
    </div>
</x-layout>
