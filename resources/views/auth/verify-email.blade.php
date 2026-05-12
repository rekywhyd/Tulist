<x-guest-layout>
    <div class="flex flex-col items-center font-serif">
        <h1 class="mt-2 mb-4 text-4xl font-bold text-gray-900">Verify your email</h1>
        <div class="text-xs font-semibold text-gray-500 font-poppins">
            {{ __('Please verify your email via the link we just sent. If you didn\'t receive it, we can resend it.') }}
        </div>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mt-4 text-xs font-semibold text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div
                class="flex justify-center mt-6 mb-5 transition-transform duration-200 font-poppins hover:hover:scale-105">
                <x-primary-button
                    class="flex justify-center px-[30px] py-[16px] text-xs font-medium text-white bg-[#163769] border border-transparent shadow-sm rounded-2xl hover:bg-[#132C51] focus:outline-none">
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="mt-4 font-semibold text-[#6AA6FF] hover:underline font-poppins text-sm underline rounded-md hover:text-gray-900 focus:outline-none">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
