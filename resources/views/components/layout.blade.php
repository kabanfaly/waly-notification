@props(['title' => '', 'active' => 'paiements'])
@php
    $active_class = 'bg-blue-700 text-blue-700 md:dark:text-blue-500';
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" />
    <link href="/css/app.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Notification</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/flowbite.js') }}"></script>
</head>

<body>
    <main class="bg-white flex flex-row">

        <div class="grow">
            <nav class="bg-white sm:px-4 dark:bg-gray-900 fixed w-full z-20 top-0 left-0 border-b border-gray-200 dark:border-gray-600">
                <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                    <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse no-underline">
                        <img class="w-32" src="{{ asset('images/logo.png') }}" class="mr-3 h-6 sm:h-9" alt="waly Logo"><br>
                        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Gestionnaire de notifications</span>
                    </a>
                    @auth
                        <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                            <button type="button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                                id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                                <span class="sr-only">Open user menu</span>
                                <img class="w-8 h-8 rounded-full" src="{{ asset('images/no-image-male.png') }}" alt="user photo" />
                            </button>
                            <!-- Dropdown menu -->
                            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
                                <div class="px-4 py-3">
                                    <span class="block text-sm text-gray-900 dark:text-white">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                                    <span class="block text-sm  text-gray-500 truncate dark:text-gray-400">{{ auth()->user()->email }}</span>
                                </div>
                                <ul class="py-2" aria-labelledby="user-menu-button">
                                    <li>
                                        <a href="/account/profile"
                                            class="block py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                            <i class="fa-solid fa-user mr-2"></i> {!! __('users.my_account') !!}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/logout" class="block py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                            <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>Déconnexion
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <button type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-expanded="false">
                                <span class="sr-only">Open main menu</span>
                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                                </svg>
                            </button>
                        </div>

                        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
                            <ul class="flex flex-col font-medium md:p-0 border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                                <li>
                                    <a href="/" class="<?= $active === 'paiements' ? $active_class : 'text-gray-900' ?> block no-underline py-2 px-3 mt-2 rounded md:bg-transparent md:p-0 " aria-current="page">Paiements</a>
                                <li>
                                    <a href="/members" class="<?= $active === 'members' ? $active_class : 'text-gray-900' ?> block no-underline py-2 px-3 mt-2 rounded md:bg-transparent md:p-0">Membres</a>
                                </li>
                                <li>
                                    <a href="/transactions" class="<?= $active === 'transactions' ? $active_class : 'text-gray-900' ?> block no-underline py-2 px-3 mt-2 rounded md:bg-transparent md:p-0">Transactions</a>
                                </li>
                            </ul>
                        </div>
                      @endauth
                </div>
            </nav>

            <div class="md:mt-40 border-gray-200 rounded-lg mb-24">
                <div class="rounded-lg mb-2 pl-4 text-center">
                    <h2 class="text-[#0F3B61] text-2xl font-bold mb-6">{!! __($title) !!}</h2>
                </div>
                <div class="min-h-screen">
                    <x-flash-message />
                    {{ $slot }}
                </div>
            </div>
            <footer
                class="fixed bottom-0 left-0 w-full md:px-60 shadow text-white h-24 mt-24 opacity-90 p-4 bg-[#0F3B61] md:p-6 dark:bg-gray-800">
                <div class="py-6 px-4 md:flex md:items-center md:justify-between">
                    <span class="text-sm dark:text-gray-300 sm:text-center">&copy;
                        @php
                            echo date('Y');
                        @endphp
                        <a href="https://walynetwork.com/" class="hover:underline">Waly Network</a>
                    </span>
                </div>
            </footer>
        </div>
    </main>
</body>
</html>
