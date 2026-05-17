@extends('layouts.dashboard')

@section('content')
    <div class="flex flex-col items-center justify-center px-20 mx-auto mt-20 text-black max-w-7xl font-poppins">
        <h3 class="text-5xl font-bold">
            Contact Us
        </h3>
        <p class="mt-5 text-xl">
            Thank you for your interest in Tulist.
        </p>
        <p class="mb-4 text-xl">
            If you have any questions, suggestions, or feedback, please feel free to reach out to us via email.
        </p>
        <a class="flex items-center gap-4 mb-20 text-2xl font-bold" href="">
            <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="#000000" viewBox="0 0 24 24">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path
                        d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z">
                    </path>
                </g>
            </svg>
            tulist@gmail.com</a>


        <h3 class="my-4 text-5xl font-bold">
            Tulist Development Team
        </h3>

        <h3 class="mt-6 mb-2 text-2xl font-bold">
            Naufal Zaky Ramadhan as Back-end
        </h3>
        <a class="flex items-center gap-4 mb-4 text-2xl font-bold" href="">
            <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM15 9C15 10.6569 13.6569 12 12 12C10.3431 12 9 10.6569 9 9C9 7.34315 10.3431 6 12 6C13.6569 6 15 7.34315 15 9ZM12 20.5C13.784 20.5 15.4397 19.9504 16.8069 19.0112C17.4108 18.5964 17.6688 17.8062 17.3178 17.1632C16.59 15.8303 15.0902 15 11.9999 15C8.90969 15 7.40997 15.8302 6.68214 17.1632C6.33105 17.8062 6.5891 18.5963 7.19296 19.0111C8.56018 19.9503 10.2159 20.5 12 20.5Z"
                        fill="#000000"></path>
                </g>
            </svg>
            naufalzaky@gmail.com</a>

        <h3 class="mt-6 mb-2 text-2xl font-bold">
            Panca Kirana Rizaldi as UI UX Designer
        </h3>
        <a class="flex items-center gap-4 mb-4 text-2xl font-bold" href="">
            <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM15 9C15 10.6569 13.6569 12 12 12C10.3431 12 9 10.6569 9 9C9 7.34315 10.3431 6 12 6C13.6569 6 15 7.34315 15 9ZM12 20.5C13.784 20.5 15.4397 19.9504 16.8069 19.0112C17.4108 18.5964 17.6688 17.8062 17.3178 17.1632C16.59 15.8303 15.0902 15 11.9999 15C8.90969 15 7.40997 15.8302 6.68214 17.1632C6.33105 17.8062 6.5891 18.5963 7.19296 19.0111C8.56018 19.9503 10.2159 20.5 12 20.5Z"
                        fill="#000000"></path>
                </g>
            </svg>
            pancakirana@gmail.com</a>


        <h3 class="mt-6 mb-2 text-2xl font-bold">
            Reky Wahyudi as Front-end
        </h3>
        <a class="flex items-center gap-4 mb-4 text-2xl font-bold" href="">
            <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM15 9C15 10.6569 13.6569 12 12 12C10.3431 12 9 10.6569 9 9C9 7.34315 10.3431 6 12 6C13.6569 6 15 7.34315 15 9ZM12 20.5C13.784 20.5 15.4397 19.9504 16.8069 19.0112C17.4108 18.5964 17.6688 17.8062 17.3178 17.1632C16.59 15.8303 15.0902 15 11.9999 15C8.90969 15 7.40997 15.8302 6.68214 17.1632C6.33105 17.8062 6.5891 18.5963 7.19296 19.0111C8.56018 19.9503 10.2159 20.5 12 20.5Z"
                        fill="#000000"></path>
                </g>
            </svg>
            rekywahyudi@gmail.com</a>


        <p class="mt-8 mb-4 text-xl">
            Each of us contributes to the best of our ability to make Tulist a simple, functional, and user-friendly application.
        </p>
    </div>
@endsection
