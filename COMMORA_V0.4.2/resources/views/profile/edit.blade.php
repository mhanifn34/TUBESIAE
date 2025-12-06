@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-white py-8">
    <div class="max-w-3xl mx-auto px-4">
        
        <!-- Back Button -->
        <button type="button"
                onclick="window.location.href='/profile'"
                class="mb-4 text-gray-600 hover:text-gray-900 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Profile</span>
        </button>

        <!-- Edit Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-user-edit"></i>
                    Edit Profile
                </h1>
            </div>
            
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Avatar Upload -->
                <div class="text-center">
                    <label class="block text-sm font-medium text-gray-700 mb-4">Foto Profile</label>
                    <div class="flex flex-col items-center">
                        <div id="avatar-preview" class="w-32 h-32 rounded-full border-4 border-blue-200 shadow-lg bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center overflow-hidden mb-4">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-user text-5xl text-blue-500"></i>
                            @endif
                        </div>
                        <label for="avatar" class="cursor-pointer px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors inline-flex items-center gap-2">
                            <i class="fas fa-camera"></i>
                            Pilih Foto
                        </label>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                        @error('avatar')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <hr class="border-gray-200">
                
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user text-blue-500 mr-2"></i>Nama
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', Auth::user()->name) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope text-blue-500 mr-2"></i>Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', Auth::user()->email) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left text-blue-500 mr-2"></i>Bio
                    </label>
                    <textarea id="bio" 
                              name="bio" 
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                              placeholder="Ceritakan tentang diri Anda...">{{ old('bio', Auth::user()->bio) }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Maksimal 500 karakter</p>
                    @error('bio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="button"
                            onclick="window.location.href='/profile'"
                            class="flex-1 bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-300 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-blue-500 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-600 transform transition-all duration-300 hover:scale-105 hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection