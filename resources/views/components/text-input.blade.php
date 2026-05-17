@props(['disabled' => false])

@php
    // Cek apakah ada error untuk input ini
    $hasError = $errors->has($attributes->get('name'));
@endphp

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'border shadow-sm rounded-xl focus:outline-none ' .
        // --- Logika IF-ELSE untuk style ---
        ($hasError && $attributes->get('name') !== 'email'
            ? // --- KELAS JIKA ADA ERROR DAN BUKAN EMAIL ---
            'border-red-600 text-red-600 placeholder:text-red-600 focus:ring-2 focus:ring-red-600'
            : // --- KELAS JIKA NORMAL ATAU EMAIL DENGAN ERROR ---
            'border-gray-500 focus:ring-2 focus:ring-[#6AA6FF]'),
]) !!}>
