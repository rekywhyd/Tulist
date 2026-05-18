<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-4 py-2 text-white bg-[#0E213D] rounded-xl font-poppins duration-200 hover:scale-110']) }}>
    {{ $slot }}
</button>
