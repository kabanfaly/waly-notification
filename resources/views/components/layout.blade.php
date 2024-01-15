@props(['title' => ''])
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
</head>

<body>
    <main class="bg-white flex flex-row">
        <div class="grow">
            <nav
                class="md:px-60 bg-white px-2 m sm:px-4 py-2.5 dark:bg-gray-900 w-full z-20 top-0 left-0 border-b border-gray-200 dark:border-gray-600">
                <div class="container flex flex-wrap justify-between items-center mx-auto">
                    <a href="/" class="flex items-center">
                        <img class="w-32" src="{{ asset('images/logo.png') }}" class="mr-3 h-6 sm:h-9"
                            alt="ubus Logo">
                    </a>
                </div>
                <div>Gestionnaire de notifications</div>
            </nav>
            @auth
                <div class="md:order-3">
                    <ul {{ $attributes->merge(['class' => 'flex flex-row items-center md:mt-0 md:text-sm md:font-medium md:bg-white']) }}>
                        <li>
                            <div class="flex ml-4 bg-gray-50">
                                <button type="button"
                                    class="flex mr-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                                    data-dropdown-placement="bottom">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="w-8 h-8 rounded-full" src="{{ asset('images/no-image-male.png') }}" alt="user photo" />
                                </button>
                                <!-- Dropdown menu -->
                                <div class="hidden z-50 my-4 text-base list-none bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
                                    id="user-dropdown" data-popper-reference-hidden="" data-popper-escaped="" data-popper-placement="bottom"
                                    style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(0px, 11px, 0px);">
                                    <div class="py-3 px-4 text-center">
                                        <a href="/account/profile">
                                        <span class="block text-sm text-gray-900 dark:text-white">
                                            {{ auth()->user()->display_name }}
                                        </span>
                                        <span class="block text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                            {{ auth()->user()->user_email }}
                                        </span>
                                        </a>
                                    </div>
                                    <ul class="py-1" aria-labelledby="user-menu-button">
                                        <li>
                                            <a href="/logout"
                                                class="block py-2 px-4 text-sm bg-gray-50 text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> {!! __('users.signout') !!}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            @endauth
            <div class="md:mt-20 border-gray-200 rounded-lg mb-24">
                <div class="rounded-lg mb-2 pl-4 text-center">
                    <h2 class="text-[#0F3B61] text-2xl font-bold mb-6">{!! __($title) !!}</h2>
                </div>
                <div class="min-h-screen">
                    {{ $slot }}
                </div>
            </div>
            <footer
                class="fixed bottom-0 left-0 w-full md:px-60 shadow text-white h-24 mt-24 opacity-90 p-4 bg-[#0F3B61] shadow md:p-6 dark:bg-gray-800">
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
