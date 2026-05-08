<section>
    {{-- Form Verifikasi Email (Jika perlu) --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="">
        @csrf
        @method('patch')

        <div class="">
            <div class="flex flex-col justify-center gap-10 md:flex-row">

                {{-- KOLOM KIRI: Informasi Profil --}}
                <div class="flex-1">
                    <header>
                        <h2 class="text-xl font-bold text-black">
                            {{ __('Profile Information') }}
                        </h2>
                    </header>

                    <div>
                        <x-input-label class="block text-sm font-medium text-gray-600" for="name"
                            :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text"
                            class="block py-2 px-4 mt-1 text-black mb-2 bg-[#F2F6FF] border border-gray-400 rounded-lg"
                            :value="old('name', $user->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label class="block text-sm font-medium text-gray-600" for="email"
                            :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email"
                            class="block py-2 px-4 mt-1 mb-2 text-black bg-[#F2F6FF] border border-gray-400 w-full rounded-lg"
                            :value="old('email', $user->email)" required autocomplete="username" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                            <div>
                                <p class="mt-2 text-sm text-gray-800">
                                    {{ __('Your email address is unverified.') }}
                                    <button form="send-verification"
                                        class="text-sm text-gray-600 underline hover:text-gray-900">
                                        {{ __('Click here to re-send verification email.') }}
                                    </button>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Garis Pembatas Vertikal (Opsional, hanya muncul di layar besar) --}}
                <div class="hidden border-l border-gray-300 md:block"></div>

                {{-- KOLOM KANAN: Update Password --}}
                <div class="flex-1">
                    <header>
                        <h2 class="text-xl font-bold text-black">
                            {{ __('Update Password') }}
                        </h2>
                        <p class="mb-2 text-sm text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
                    </header>

                    <div>
                        <x-input-label class="block text-sm font-medium text-gray-600" for="current_password"
                            :value="__('Current Password')" />
                        <x-text-input id="current_password" name="current_password" type="password"
                            class="block py-2 mb-2 px-4 mt-1 text-black bg-[#F2F6FF] border border-gray-400 w-full rounded-lg"
                            autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                    </div>

                    <div class="flex gap-x-4">
                        <div class="flex-1">
                            <x-input-label class="block text-sm font-medium text-gray-600" for="password"
                                :value="__('New Password')" />
                            <x-text-input id="password" name="password" type="password"
                                class="block py-2 mb-2 px-4 mt-1 text-black bg-[#F2F6FF] border border-gray-400 w-full rounded-lg"
                                autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex-1">
                            <x-input-label class="block text-sm font-medium text-gray-600" for="password_confirmation"
                                :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                                class="block py-2 px-4 mt-1 mb-2 text-black bg-[#F2F6FF] border border-gray-400 w-full rounded-lg"
                                autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi (Di bawah kedua kolom) --}}
        <div class="flex items-center gap-4 pt-10">
            <button type="submit"
                class="inline-flex items-center px-6 py-1 text-base font-bold text-black transition duration-150 ease-in-out bg-[#C1D3EF] shadow-sm font-poppins rounded-3xl hover:bg-[#7b99ca] focus:outline-none">
                {{ __('Save') }}
            </button>

            <button type="button" id="cancel-edit-btn"
                class="inline-flex items-center px-6 py-1 text-base font-bold text-black transition duration-150 ease-in-out bg-gray-300 font-poppins rounded-3xl hover:bg-gray-400 focus:outline-none">
                {{ __('Cancel') }}
            </button>

            @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
