<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Profile Photo Upload -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl text-center mx-auto">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Update Profile Photo</h3>

                    @if(session('status') === 'photo-updated')
                        <div class="text-green-600 mb-3 font-semibold">
                            Profile photo updated successfully!
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <img src="{{ $user->photo ? asset('storage/profile-photos/' . $user->photo) : asset('default.png') }}" 
                                 class="mx-auto rounded-full border border-gray-300 p-1" 
                                 width="120" height="120" alt="Profile Photo">
                        </div>

                        <input type="file" name="photo" required class="block mx-auto mb-4">
                        @error('photo')
                            <p class="text-red-600">{{ $message }}</p>
                        @enderror

                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Save Photo
                        </button>
                    </form>
                </div>
            </div>

            <!-- Update Profile Information -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete User -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
