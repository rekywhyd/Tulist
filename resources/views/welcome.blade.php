@extends('layouts.dashboard')

@section('content')
    <div class="px-4 mx-auto mt-10 sm:px-8 lg:px-12 max-w-7xl">

        <!-- hero section -->
        <section class="flex flex-row items-center justify-between w-full gap-6 mx-auto sm:gap-10">
            <div class="w-1/2 mb-10 text-left lg:mb-0 lg:ml-8">
                <h1 class="font-serif text-2xl font-bold text-gray-900 sm:text-3xl sm:leading-tight lg:text-7xl">

                    Working,<br>
                    Finish it
                </h1>
                <p class="mt-3 text-sm font-bold text-gray-600 sm:text-base lg:text-xl font-poppins">

                    Manage all your work tasks more effectively. Record, organize, and complete office tasks without overstrain.
                </p>
                @if (Route::has('login'))
                    <div class="flex items-center mt-4 text-lg lg:ml-24">
                        @auth
                            {{-- Tautan Home (Jika sudah login) --}}
                            <a href="{{ url('/home') }}"
                                class="px-9 py-2 text-white font-bold hover:scale-110 transition-all duration-200 rounded-full border bg-blue-800 shadow-xl ease-out border-blue-600">
                                Started Now
                            </a>
                        @else
                            {{-- Tautan Login (Jika belum login) --}}
                            <a href="{{ route('login') }}"
                                class="px-9 py-2 text-white font-bold hover:scale-110 transition-all duration-200 rounded-full border bg-blue-800 shadow-xl ease-out border-blue-600">
                                Started Now
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
            <div class="w-full lg:w-auto">
                <img class="w-full max-w-[420px] sm:max-w-[700px] lg:max-w-[800px] h-auto" src="{{ Vite::asset('resources/images/hero-section.png') }}" alt="Hero Section">
            </div>
        </section>
        <!-- hero section -->

        <section class="pt-16 mt-6">
            <h2 class="pl-0 font-serif text-3xl font-bold text-left text-gray-900 sm:pl-16 sm:text-4xl lg:text-5xl" data-aos="fade">

                Features
            </h2>

            <div class="flex flex-col items-center gap-6 mt-6 font-serif">

                <!-- feature 1 -->
                <div
                    class="flex flex-col md:flex-row md:items-center md:justify-between w-full max-w-[1050px] py-6 px-4 md:px-0 text-[#D5E2F5] bg-[#132C51] rounded-3xl mb-16" data-aos="fade-left">
                    <div class="md:px-16 md:pb-12 md:ml-4">
                <h3 class="mb-4 text-2xl font-bold sm:text-3xl sm:mb-6 lg:text-5xl">Organize Jobdesk</h3>
                        <p class="text-base text-white sm:text-xl lg:text-2xl">
                            Save and manage every office responsibility systematically so that it is easily accessible and nothing is missed.
                        </p>
                    </div>
                    <div class="w-full py-4 md:w-auto md:py-4">
                        <img class="w-full max-w-[700px] h-auto" src="{{ Vite::asset('resources/images/organize.png') }}" alt="Organize Jobdesk">
                    </div>
                </div>
                <!-- feature 1 -->

                <!-- feature 2 -->
                <div
                    class="flex flex-col md:flex-row-reverse md:items-center md:justify-between w-full max-w-[1050px] py-6 px-4 md:px-0 text-[#D5E2F5] bg-[#132C51] rounded-3xl mb-16" data-aos="fade-right">
                    <div class="md:order-last md:px-16 md:ml-4 md:mr-8 md:text-right md:pb-14">
                        <h3 class="mb-4 text-2xl font-bold sm:text-3xl sm:mb-6 lg:text-5xl">Smart Scheduling</h3>
                        <p class="text-base text-white sm:text-xl lg:text-2xl">
                            Organize your daily schedule and meetings efficiently, so that every working hour is more productive.
                        </p>
                    </div>
                    <div class="w-full md:w-auto md:pl-16 md:pt-8">
                        <img class="w-full max-w-[600px] h-auto" src="{{ Vite::asset('resources/images/smart.png') }}" alt="Smart Scheduling">
                    </div>
                </div>
                <!-- feature 2 -->

                <!-- feature 3 -->
                <div
                    class="flex flex-col md:flex-row md:items-center md:justify-between w-full max-w-[1050px] py-6 px-4 md:px-0 text-[#D5E2F5] bg-[#132C51] rounded-3xl mb-16" data-aos="fade-left">
                    <div class="md:px-16 md:ml-4 md:pb-14">
                        <h3 class="mb-4 text-2xl font-bold sm:text-3xl sm:mb-6 lg:text-5xl">Deadline Tracking</h3>
                        <p class="text-base text-white sm:text-xl lg:text-2xl">
                            Get automatic reminders for every deadline, ensuring all tasks are completed on time.
                        </p>
                    </div>
                    <div class="w-full md:w-auto md:py-0 md:pr-12">
                        <img class="w-full max-w-[600px] h-auto" src="{{ Vite::asset('resources/images/deadline.png') }}" alt="Deadline Tracking">
                    </div>
                </div>
                <!-- feature 3 -->

                <!-- feature 4 -->
                <div
                    class="flex flex-col md:flex-row-reverse md:items-center md:justify-between w-full max-w-[1050px] py-6 px-4 md:px-0 text-[#D5E2F5] bg-[#132C51] rounded-3xl mb-16" data-aos="fade-right">
                    <div class="md:order-last md:px-16 md:ml-4 md:mr-8 md:text-right md:pb-14">
                        <h3 class="mb-4 text-2xl font-bold sm:text-3xl sm:mb-6 lg:text-5xl">Time Management</h3>
                        <p class="text-base text-white sm:text-xl lg:text-2xl">
                            Manage time effectively to complete work faster and on target.
                        </p>
                    </div>
                    <div class="w-full md:w-auto md:pl-16 md:pt-10 md:pb-8">
                        <img class="w-full max-w-[600px] h-auto" src="{{ Vite::asset('resources/images/time.png') }}" alt="Time Management">
                    </div>
                </div>
                <!-- feature 4 -->

                <!-- feature 5 -->
                <div
                    class="flex flex-col md:flex-row md:items-center md:justify-between w-full max-w-[1050px] py-6 px-4 md:px-0 pt-8 text-[#D5E2F5] bg-[#132C51] rounded-3xl mb-16" data-aos="fade-left">
                    <div class="md:pl-16 md:ml-0 md:pb-14">
                        <h3 class="mb-4 text-2xl font-bold sm:text-3xl sm:mb-6 lg:text-5xl">Progress Overview</h3>
                        <p class="text-base text-white sm:text-xl lg:text-2xl">
                            See the progress of all work in real-time so you are always in control.
                        </p>
                    </div>
                    <div class="w-full py-0 md:w-auto md:pr-0">
                        <img class="w-full max-w-[500px] h-auto" src="{{ Vite::asset('resources/images/progress.png') }}" alt="Progress Overview">
                    </div>
                </div>
                <!-- feature 5 -->

            </div>
        </section>
    </div>
@endsection

