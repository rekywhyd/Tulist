<x-guest-layout>
    <div class="flex flex-col items-center font-serif">
        <h1 class="my-2 text-4xl font-bold text-gray-900">Forget Password?</h1>
        <div class="mb-4 text-xs font-semibold text-gray-500 font-poppins">
            {{ __('Enter your email, and we will send you a password reset link') }}
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-text-input id="email" placeholder="Email" class="block w-full mt-1 mb-4" type="email" name="email"
                :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex justify-center mt-6 mb-5 transition-transform duration-200 font-poppins hover:hover:scale-105">
            <x-primary-button
                class="flex justify-center px-[30px] py-[16px] text-xs font-medium text-white bg-[#163769] border border-transparent shadow-sm rounded-2xl hover:bg-[#132C51] focus:outline-none">
                {{ __('Email Password Reset') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
