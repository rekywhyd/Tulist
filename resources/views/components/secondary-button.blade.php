<button {{ $attributes->merge(['type' => 'button', 'class' => 'px-4 py-2 font-bold text-gray-700 bg-white rounded-xl font-poppins duration-200 hover:scale-90']) }}>
    {{ $slot }}
</button>
