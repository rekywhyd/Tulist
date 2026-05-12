<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="antialiased text-black bg-[#EAF0FA] font-poppins bg-[url('../images/bg-auth.png')] bg-cover bg-center bg-no-repeat">
    <div class="pb-10 mx-16 my-12 rounded-[40px] bg-white/60 backdrop-blur-lg">
        <a href="{{ url('/') }}">
            <img class="w-[175px] ml-12 pt-4" src="{{ Vite::asset('resources/images/logo-navbar.png') }}"
                alt="Logo">
        </a>

        <div class="relative min-h-[400px]">
            <img class="absolute z-50 hidden sm:block max-w-[520px] mt-[75px] ml-[64px]"
                src="{{ Vite::asset('resources/images/time-management.png') }}" alt="time management">

            <div class="w-[570px] ml-[540px] px-[90px] py-8 my-6 bg-white shadow-2xl rounded-[50px]">
                <div class="flex flex-col items-center font-serif">
                    {{-- Judul Formulir --}}
                    <h2 class="my-2 text-4xl font-bold text-gray-900">Sign Up</h2>
                    <p class="mb-6 text-sm font-semibold text-gray-500">Create an account, it's free</p>
                </div>

                {{-- Formulir Pendaftaran --}}
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="flex gap-4 mb-5 font-poppins">
                        <!-- Name -->
                        <x-text-input id="name"
                            class="block w-full px-4 py-[14px] mt-1 text-xs shadow-sm rounded-2xl" type="text"
                            name="name" :value="old('name')" required autofocus autocomplete="name"
                            placeholder="Name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />

                        <!-- Email -->
                        <x-text-input id="email"
                            class="block w-full px-4 py-[14px] mt-1 text-xs shadow-sm rounded-2xl" type="email"
                            name="email" :value="old('email')" required autocomplete="username" placeholder="Email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="relative mb-5 font-poppins">
                        <x-text-input id="password"
                            class="block w-full px-4 py-[14px] mt-1 text-xs shadow-sm rounded-2xl" type="password"
                            name="password" required autocomplete="new-password" placeholder="Password" />

                        <button type="button" data-toggle-password data-target-input="#password"
                            class="absolute right-0 flex items-center pl-2 pr-3 text-gray-400 border-l-2 border-gray-200 inset-y-2 hover:text-gray-600">
                            <svg class="w-5 h-5 transition-transform duration-200 icon-eye hover:hover:scale-110"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                <path fill-rule="evenodd"
                                    d="M1.323 11.447C2.811 6.976 7.028 3.75 12 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a.75.75 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z"
                                    clip-rule="evenodd" />
                            </svg>

                            <svg class="hidden w-5 h-5 transition-transform duration-200 icon-eye-slash hover:hover:scale-110"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                <path fill-rule="evenodd"
                                    d="M1.323 11.447C2.811 6.976 7.028 3.75 12 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a.75.75 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z"
                                    clip-rule="evenodd" />

                                <path fill-rule="evenodd"
                                    d="M3.53 2.47a.75.75 0 00-1.06 1.06l18 18a.75.75 0 101.06-1.06l-18-18z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="relative mb-5 font-poppins">
                        <x-text-input id="password_confirmation"
                            class="block w-full px-4 py-[14px] mt-1 text-xs shadow-sm rounded-2xl" type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="Confirm Password" />

                        <button type="button" data-toggle-password data-target-input="#password_confirmation"
                            class="absolute right-0 flex items-center pl-2 pr-3 text-gray-400 border-l-2 border-gray-200 inset-y-2 hover:text-gray-600">
                            <svg class="w-5 h-5 transition-transform duration-200 icon-eye hover:hover:scale-110"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                <path fill-rule="evenodd"
                                    d="M1.323 11.447C2.811 6.976 7.028 3.75 12 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a.75.75 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z"
                                    clip-rule="evenodd" />
                            </svg>

                            <svg class="hidden w-5 h-5 transition-transform duration-200 icon-eye-slash hover:hover:scale-110"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                <path fill-rule="evenodd"
                                    d="M1.323 11.447C2.811 6.976 7.028 3.75 12 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a.75.75 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z"
                                    clip-rule="evenodd" />

                                <path fill-rule="evenodd"
                                    d="M3.53 2.47a.75.75 0 00-1.06 1.06l18 18a.75.75 0 101.06-1.06l-18-18z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Tombol Submit -->
                    <div
                        class="flex justify-center mb-5 transition-transform duration-200 font-poppins hover:hover:scale-105">
                        <x-primary-button
                            class="flex justify-center px-12 py-[16px] text-xs font-medium text-white bg-[#163769] border border-transparent shadow-sm rounded-2xl hover:bg-[#132C51] focus:outline-none">
                            {{ __('Create account') }}
                        </x-primary-button>
                    </div>

                    <!-- Pemisah "Or Sign Up with" -->
                    <div class="flex items-center my-4">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <span class="mx-4 text-xs text-gray-500 font-poppins">Or Sign Up with</span>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>

                    <!-- Social Logins -->
                    <div class="flex justify-center pb-2 space-x-6">
                        <!-- Google -->
                        <a href="{{ route('google.login') }}"
                            class="transition-transform duration-200 hover:scale-110">
                            <svg class="w-6 h-6 mt-2" viewBox="-0.5 0 48 48" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                fill="#000000">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <title>Google-color</title>
                                    <desc>Created with Sketch.</desc>
                                    <defs> </defs>
                                    <g id="Icons" stroke="none" stroke-width="1" fill="none"
                                        fill-rule="evenodd">
                                        <g id="Color-" transform="translate(-401.000000, -860.000000)">
                                            <g id="Google" transform="translate(401.000000, 860.000000)">
                                                <path
                                                    d="M9.82727273,24 C9.82727273,22.4757333 10.0804318,21.0144 10.5322727,19.6437333 L2.62345455,13.6042667 C1.08206818,16.7338667 0.213636364,20.2602667 0.213636364,24 C0.213636364,27.7365333 1.081,31.2608 2.62025,34.3882667 L10.5247955,28.3370667 C10.0772273,26.9728 9.82727273,25.5168 9.82727273,24"
                                                    id="Fill-1" fill="#FBBC05"> </path>
                                                <path
                                                    d="M23.7136364,10.1333333 C27.025,10.1333333 30.0159091,11.3066667 32.3659091,13.2266667 L39.2022727,6.4 C35.0363636,2.77333333 29.6954545,0.533333333 23.7136364,0.533333333 C14.4268636,0.533333333 6.44540909,5.84426667 2.62345455,13.6042667 L10.5322727,19.6437333 C12.3545909,14.112 17.5491591,10.1333333 23.7136364,10.1333333"
                                                    id="Fill-2" fill="#EB4335"> </path>
                                                <path
                                                    d="M23.7136364,37.8666667 C17.5491591,37.8666667 12.3545909,33.888 10.5322727,28.3562667 L2.62345455,34.3946667 C6.44540909,42.1557333 14.4268636,47.4666667 23.7136364,47.4666667 C29.4455,47.4666667 34.9177955,45.4314667 39.0249545,41.6181333 L31.5177727,35.8144 C29.3995682,37.1488 26.7323182,37.8666667 23.7136364,37.8666667"
                                                    id="Fill-3" fill="#34A853"> </path>
                                                <path
                                                    d="M46.1454545,24 C46.1454545,22.6133333 45.9318182,21.12 45.6113636,19.7333333 L23.7136364,19.7333333 L23.7136364,28.8 L36.3181818,28.8 C35.6879545,31.8912 33.9724545,34.2677333 31.5177727,35.8144 L39.0249545,41.6181333 C43.3393409,37.6138667 46.1454545,31.6490667 46.1454545,24"
                                                    id="Fill-4" fill="#4285F4"> </path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                        
                    </div>

                    <!-- Link ke Halaman Login -->
                    <div class="mt-4 text-center font-poppins">
                        <span class="text-sm text-black">Already have an account?</span>
                        <a class="text-sm font-semibold text-[#6AA6FF] hover:underline" href="{{ route('login') }}">
                            {{ __('Login') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
