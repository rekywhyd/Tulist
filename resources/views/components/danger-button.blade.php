<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center tracking-widest px-4 py-2 text-xl font-bold text-white ease-in-out bg-red-600 rounded-xl font-poppins hover:bg-red-500 active:bg-red-700 focus:outline-none']) }}>
    {{ $slot }}
</button>
