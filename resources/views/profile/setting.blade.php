<x-app-layout>
    <div class="min-h-full items-center mr-8 ml-20 border-white shadow-md bg-white/50 rounded-[40px] mt-20">
        <div class="px-12 font-poppins">
            <div class="flex items-center justify-between pt-10">
                {{-- Avatar and Name --}}
                <div class="flex items-center gap-14">
                    @if (Auth::user()->profile_photo_path)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Profile Photo"
                            class="object-cover rounded-full w-44 h-44 ">
                    @else
                        <svg class="w-44 h-44" viewBox="3 3 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12Z" fill="#C0C0C0" fill-opacity="0.24"></path> <circle cx="12" cy="10" r="4" fill="#A9A9A9"></circle> <path fill-rule="evenodd" clip-rule="evenodd" d="M18.2209 18.2462C18.2791 18.3426 18.2613 18.466 18.1795 18.5432C16.5674 20.0662 14.3928 21 12 21C9.60728 21 7.43264 20.0663 5.82057 18.5433C5.73877 18.466 5.72101 18.3427 5.77918 18.2463C6.94337 16.318 9.29215 15 12.0001 15C14.7079 15 17.0567 16.3179 18.2209 18.2462Z" fill="#A9A9A9"></path> </g></svg>
                    @endif
                    <div class="flex flex-col gap-2">
                        <p class="text-4xl font-bold">{{ Auth::user()->name }}</p>
                        <p class="text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            {{-- Edit Button --}}
            <div class="flex pt-4 pb-6" id="edit-button-container">
                <button id="edit-profile-btn"
                    class="flex items-center gap-4 px-6 py-2 text-xl font-bold text-black transition-transform duration-200 bg-white shadow-xl hover:scale-110 rounded-2xl">
                    <svg class="w-6 h-6" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <title>edit_cover [#1481]</title>
                            <desc>Created with Sketch.</desc>
                            <defs> </defs>
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Dribbble-Light-Preview" transform="translate(-419.000000, -359.000000)"
                                    fill="#000000">
                                    <g id="icons" transform="translate(56.000000, 160.000000)">
                                        <path
                                            d="M384,209.210475 L384,219 L363,219 L363,199.42095 L373.5,199.42095 L373.5,201.378855 L365.1,201.378855 L365.1,217.042095 L381.9,217.042095 L381.9,209.210475 L384,209.210475 Z M370.35,209.51395 L378.7731,201.64513 L380.4048,203.643172 L371.88195,212.147332 L370.35,212.147332 L370.35,209.51395 Z M368.25,214.105237 L372.7818,214.105237 L383.18415,203.64513 L378.8298,199 L368.25,208.687714 L368.25,214.105237 Z"
                                            id="edit_cover-[#1481]"> </path>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                    Edit
                </button>
                <div class="flex gap-4 mt-4">
                    <form id="photo-upload-form" action="{{ route('profile.photo.upload') }}" method="POST"
                        enctype="multipart/form-data" class="flex items-center gap-2">
                        @csrf
                        <input type="file" id="photo-input" name="photo" accept="image/*" class="p-1 border rounded" />
                    </form>
                    <form id="photo-delete-form" action="{{ route('profile.photo.delete') }}" method="POST"
                        class="flex items-center">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 text-white transition bg-red-600 rounded hover:bg-red-700">Delete
                            Photo</button>
                    </form>
                </div>
            </div>

            {{-- Profile Edit Mode --}}
            <div id="profile-edit-mode" class="hidden pt-4 pb-6">
                <div class="w-full p-8 bg-white shadow-xl rounded-3xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Delete Account Section (Sekarang Selalu Terlihat) --}}
            <div id="delete-account-section" class="flex justify-end pb-10">
                <div class="w-full">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editProfileBtn = document.getElementById('edit-profile-btn');
            const editProfileMode = document.getElementById('profile-edit-mode');
            const editButtonContainer = document.getElementById('edit-button-container');

            function toggleEditMode(isEditing) {
                if (isEditing) {
                    editProfileMode.classList.remove('hidden');
                    editButtonContainer.classList.add('hidden');
                    // Kode untuk menyembunyikan delete-account-section telah dihapus
                } else {
                    editProfileMode.classList.add('hidden');
                    editButtonContainer.classList.remove('hidden');
                    // Kode untuk menampilkan kembali delete-account-section telah dihapus
                }
            }

            if (editProfileBtn) {
                editProfileBtn.addEventListener('click', () => toggleEditMode(true));
            }

            // Photo upload auto-submit
            const photoInput = document.getElementById('photo-input');
            const photoForm = document.getElementById('photo-upload-form');

            if (photoInput && photoForm) {
                photoInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        if (confirm('Are you sure you want to upload a profile photo?')) {
                            photoForm.submit();
                        } else {
                            this.value = ''; // Reset input if cancelled
                        }
                    }
                });
            }

            document.addEventListener('click', function(e) {
                if (e.target && e.target.id === 'cancel-edit-btn') {
                    e.preventDefault();
                    toggleEditMode(false);
                }
            });
        });
    </script>
</x-app-layout>
