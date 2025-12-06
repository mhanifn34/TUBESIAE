@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-white py-8">
    <div class="max-w-4xl mx-auto px-4">
        
        <!-- Back Button -->
        <button type="button"
                onclick="window.history.back()"
                class="mb-6 text-gray-600 hover:text-gray-900 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </button>

        <!-- Create Community Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-8 py-10">
                <h1 class="text-4xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-users"></i>
                    Buat Komunitas Baru
                </h1>
                <p class="text-blue-100 mt-2">Mulai komunitas dan kumpulkan orang-orang dengan minat yang sama</p>
            </div>

            <!-- Form -->
            <form action="{{ route('communities.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf

                <!-- Community Image -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-4">
                        <i class="fas fa-image text-blue-500 mr-2"></i>
                        Logo Komunitas
                    </label>
                    <div class="flex items-start gap-6">
                        <!-- Preview -->
                        <div id="image-preview" class="w-32 h-32 rounded-xl border-4 border-blue-100 shadow-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center overflow-hidden">
                            <i class="fas fa-users text-5xl text-gray-400"></i>
                        </div>
                        
                        <!-- Upload Button -->
                        <div class="flex-1">
                            <label for="image" class="cursor-pointer inline-flex items-center gap-2 px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                <i class="fas fa-cloud-upload-alt"></i>
                                Pilih Gambar
                            </label>
                            <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(event)">
                            <p class="text-sm text-gray-500 mt-2">Format: JPG, PNG, GIF (Max 2MB)</p>
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200 my-8">

                <!-- Community Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag text-blue-500 mr-2"></i>
                        Nama Komunitas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           placeholder="Contoh: Pecinta Kopi Jakarta"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           required>
                    <p class="text-sm text-gray-500 mt-1">Nama yang unik dan mudah diingat</p>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Community Description -->
                <div class="mb-8">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-align-left text-blue-500 mr-2"></i>
                        Deskripsi Komunitas <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="6"
                              placeholder="Jelaskan tentang komunitas ini, tujuan, dan siapa yang bisa bergabung..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                              required>{{ old('description') }}</textarea>
                    <div class="flex justify-between items-center mt-2">
                        <p class="text-sm text-gray-500">Maksimal 1000 karakter</p>
                        <span id="char-count" class="text-sm text-gray-400">0/1000</span>
                    </div>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                    <h4 class="font-semibold text-blue-900 mb-2 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        Panduan Membuat Komunitas
                    </h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Gunakan nama yang jelas dan mudah dicari</li>
                        <li>• Jelaskan tujuan dan topik komunitas dengan detail</li>
                        <li>• Tetap hormati dan patuhi peraturan platform</li>
                        <li>• Anda akan menjadi admin/moderator komunitas ini</li>
                    </ul>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4">
                    <button type="button"
                            onclick="window.history.back()"
                            class="flex-1 bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-300 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-blue-500 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-600 transform transition-all duration-300 hover:scale-105 hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-check"></i>
                        Buat Komunitas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Preview image
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
        }
        reader.readAsDataURL(file);
    }
}

// Character counter
const textarea = document.getElementById('description');
const charCount = document.getElementById('char-count');

if (textarea && charCount) {
    textarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = `${length}/1000`;
        
        if (length > 1000) {
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-gray-400');
        } else {
            charCount.classList.add('text-gray-400');
            charCount.classList.remove('text-red-500');
        }
    });
}
</script>
@endsection