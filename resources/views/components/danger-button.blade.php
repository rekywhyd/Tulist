<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-4 py-2 font-bold text-white bg-red-600 rounded-xl font-poppins duration-200 hover:scale-110']) }}>
    {{ $slot }}
</button>
