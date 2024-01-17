<x-layout title="">
    <div class="p-4">
        <div class="p-4 md:p-10 rounded max-w-lg mx-auto mt-24 bg-gradient-to-r from-[#0F3B61] to-[#CBEEF9]">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="py-4 px-4 md:py-6 md:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-2xl font-bold uppercase mb-1">
                            Connexion
                        </h2>
                    </div>
                    <form method="POST" action="/users/authenticate">
                        @csrf
                        <div class="mb-6">
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">E-mail</label>
                            <div class="flex">
                                <span
                                    class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 rounded-l-md border border-r-0 border-gray-300 dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    @
                                </span>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="" required>
                            </div>
                            @error('email')
                                <p class="text-red-700 text-center text-xs mt-1">{!! __($message) !!}</p>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Mot de passe</label>
                            <div class="flex">
                                <span
                                    class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 rounded-l-md border border-r-0 border-gray-300 dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input type="password" name="password" id="password" value="{{ old('password') }}"
                                    class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="" required>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{!! __($message) !!}</p>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full mb-6 text-white bg-[#0F3B61] hover:bg-[#0c375c] focus:ring-4 focus:outline-none focus:ring-[#0F3B61] font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-[#0F3B61] dark:hover:bg-[#0c375c] dark:focus:ring-[#0F3B61]">
                            Se connecter
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
