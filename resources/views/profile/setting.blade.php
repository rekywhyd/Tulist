<x-app-layout>
    <div class="min-h-full items-center mr-8 ml-20 border-white shadow-md bg-white/50 rounded-[40px] mt-20">
        <div class="px-12 font-poppins">
            <div class="flex items-center justify-between pt-6">
                {{-- Avatar and Name --}}
                <div class="flex items-center gap-16">
                    <svg class="w-44 h-44 transition-all text-black hover:text-[#0E213D] duration-200 hover:hover:scale-110"
                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_iconCarrier">
                            <path d="M12.12 12.78C12.05 12.77 11.96 12.77 11.88 12.78C10.12 12.72 8.71997 11.28 8.71997 9.50998C8.71997 7.69998 10.18 6.22998 12 6.22998C13.81 6.22998 15.28 7.69998 15.28 9.50998C15.27 11.28 13.88 12.72 12.12 12.78Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M18.74 19.3801C16.96 21.0101 14.6 22.0001 12 22.0001C9.40001 22.0001 7.04001 21.0101 5.26001 19.3801C5.36001 18.4401 5.96001 17.5201 7.03001 16.8001C9.77001 14.9801 14.25 14.9801 16.97 16.8001C18.04 17.5201 18.64 18.4401 18.74 19.3801Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                    </svg>
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
                    <svg class="w-6 h-6" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>edit_cover [#1481]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-419.000000, -359.000000)" fill="#000000"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M384,209.210475 L384,219 L363,219 L363,199.42095 L373.5,199.42095 L373.5,201.378855 L365.1,201.378855 L365.1,217.042095 L381.9,217.042095 L381.9,209.210475 L384,209.210475 Z M370.35,209.51395 L378.7731,201.64513 L380.4048,203.643172 L371.88195,212.147332 L370.35,212.147332 L370.35,209.51395 Z M368.25,214.105237 L372.7818,214.105237 L383.18415,203.64513 L378.8298,199 L368.25,208.687714 L368.25,214.105237 Z" id="edit_cover-[#1481]"> </path> </g> </g> </g> </g></svg>
                    Edit
                </button>
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

            document.addEventListener('click', function(e) {
                if (e.target && e.target.id === 'cancel-edit-btn') {
                    e.preventDefault();
                    toggleEditMode(false);
                }
            });
        });
    </script>
</x-app-layout>