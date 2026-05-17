<x-app-layout>
    <div class="min-h-full items-center mr-8 pt-6 ml-20 border-white shadow-md bg-white/50 rounded-[40px] mt-20">
        <div class="flex flex-col justify-center h-full font-poppins">
            
            <div class="ml-10">
                <p class="text-sm font-medium text-gray-500 ">We Are Here To Help You</p>
                <h1 class="text-3xl leading-tight text-gray-900">
                    <span class="font-extrabold">Discuss</span> Your Chemical Solution Needs
                </h1>
            </div>

            <div class="px-12 pt-5 bg-[#F2F6FF] rounded-2xl">
                @if (session('success'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('help.submit') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block mb-1 text-sm font-semibold text-gray-600">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-2 transition duration-150 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Your Full Name">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="problem" class="block mb-1 text-sm font-semibold text-gray-600">Problem</label>
                        <textarea id="problem" name="problem" rows="4"
                            class="w-full px-4 py-2 transition duration-150 border @error('problem') border-red-500 @else border-gray-300 @enderror rounded-lg resize-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Text here">{{ old('problem') }}</textarea>
                        @error('problem')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end mb-5">
                        <button type="submit"
                            class="px-8 py-3 font-semibold text-white transition duration-200 hover:hover:scale-110 bg-[#163769] shadow-lg rounded-xl hover:bg-[#132C51]">
                            Submit
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>