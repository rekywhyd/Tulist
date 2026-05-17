@extends('layouts.dashboard')

@section('content')
    <div class="px-20 mx-auto text-black max-w-7xl font-poppins">

        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-6xl font-bold">
                    Say Hi <br> To TuList
                </h3>

                <p class="mt-5 text-3xl">
                    Tulist is a small space that helps you jot down important things in life. From daily plans to spur-of-the-moment ideas, you can easily save and manage everything.
                </p>
            </div>

            <img class="w-[600px]" src="{{ Vite::asset('resources/images/notesBook.png') }}" alt="Notes Book">
        </div>

        <img class="w-[300px]" src="{{ Vite::asset('resources/images/pencil.png') }}" alt="Pencil">


        <h3 class="text-6xl font-bold">
            One Team, One Vision
        </h3>
        <p class="mt-8 text-3xl text-justify">
            Tulist was developed as part of an internship project that brought us together with a common goal: to create simple yet meaningful solutions to help manage everyday activities. Despite coming from different disciplines and backgrounds, we were united by a common commitment — to produce work that not only functions but also provides the best possible user experience.
        </p>
        <p class="mt-10 text-3xl text-justify">
            As an internship project, Tulist is a reflection of collaborative work and dedication. We strive to provide a concise, easy-to-understand application that can assist users in maintaining daily organization and productivity. Every decision we make is based on user experience and sound design principles.
        </p>
        <p class="mt-10 mb-32 text-3xl text-justify">
            We believe that any work, no matter how small, can have a positive impact when created with dedication. Tulist is our first step on this professional journey — a testament to the power of collaboration, vision, and quality to create a valuable product.
        </p>

        <img class="w-[600px] mx-auto" src="{{ Vite::asset('resources/images/map.png') }}" alt="Map">
    </div>
@endsection
