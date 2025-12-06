<div id="createPostModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">
        
        <div class="flex items-center justify-between p-4 border-b sticky top-0 bg-white">
            <h2 class="text-xl font-bold">Buat Post</h2>
            <button onclick="closeCreatePostModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="flex border-b">
            <button onclick="switchTab('post')" id="tabPost" class="px-6 py-3 border-b-2 border-blue-500 text-blue-500 font-medium">Post</button>
            <button onclick="switchTab('jualbeli')" id="tabJualBeli" class="px-6 py-3 text-gray-600">Jual Beli</button>
            <button onclick="switchTab('draf')" id="tabDraf" class="px-6 py-3 text-gray-600">Draf (0)</button>
        </div>

        <div id="contentPost" class="tab-content">
            
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                
                <div class="flex gap-6">
                    <div class="flex-1">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Judul</label>
                            <input type="text" name="title" placeholder="Tulis judul yang menarik ya" 
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">
                                Deskripsi 
                                <span class="bg-gray-900 text-white text-xs px-2 py-1 rounded ml-2">beta</span>
                            </label>
                            <textarea name="content" rows="10" 
                                      class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                      placeholder="Tulis sesuatu..." required></textarea>
                            </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Gambar (Opsional)</label>
                            <div class="border-2 border-dashed rounded-lg p-8 text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600 text-sm">Drag gambar ke sini atau Upload</p>
                                
                                <input type="file" name="image" accept="image/*" class="hidden" id="imageUpload">
                                
                                <button type="button" onclick="document.getElementById('imageUpload').click()" 
                                        class="mt-2 text-blue-500 hover:underline text-sm">Pilih File</button>
                                <p class="text-xs text-gray-500 mt-2">Maks 10 MB</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Video (Opsional)</label>
                            <div class="border-2 border-dashed rounded-lg p-8 text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600 text-sm">Drag video ke sini atau Upload</p>
                                
                                <input type="file" name="video" accept="video/*" class="hidden" id="videoUpload">
                                
                                <button type="button" onclick="document.getElementById('videoUpload').click()" 
                                        class="mt-2 text-blue-500 hover:underline text-sm">Pilih File</button>
                                <p class="text-xs text-gray-500 mt-2">Maks 1024 MB • 5 Menit</p>
                            </div>
                        </div>
                    </div>

                    <div class="w-80">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Pilih Komunitas</label>
                            <select name="community_id" required ...>
                                <option value="">Pilih Komunitas</option>
                                
                                @foreach($modalCommunities as $community) 
                                    <option value="{{ $community->id }}">{{ $community->name }}</option>
                                @endforeach
                            
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="button" class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-50">
                                <i class="far fa-save"></i>
                            </button>
                            <button type="submit" class="flex-1 px-4 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                Post
                            </button>
                        </div>

                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-medium mb-2">Membuat Konten di COMMORA</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Please be nice, No SARA, No Porn.</li>
                                <li>• Jangan lupa baca peraturan komunitas setempat ya.</li>
                                <li>• Perhatikan <a href="#" class="text-blue-500">syarat dan ketentuan umum</a>.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
            </div>

        <div id="contentJualBeli" class="tab-content hidden">

            @if ($errors->any())
                <div class="p-4 m-6 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <strong class="font-bold">Whoops! Kayaknya ada yang salah:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                
                <div class="flex gap-6">
                    <div class="flex-1">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Jual atau Beli?</label>

                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    
                                    <input type="radio" name="listing_type" value="jual" class="mr-2" required>
                                    <span>Jual</span>
                        
                                </label>
                                <label class="flex items-center">
                                    
                                    <input type="radio" name="listing_type" value="beli" class="mr-2">
                                    <span>Beli</span>
                        
                                </label>
                            </div>
                            
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Judul</label>
                            <input type="text" name="title" placeholder="Tulis judul yang menarik ya" 
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Harga</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" name="price" placeholder="0" 
                                       class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Gambar Barang (Opsional)</label>
                            <div class="border-2 border-dashed rounded-lg p-8 text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600 text-sm">Drag gambar ke sini atau Upload</p>
                                
                                <input type="file" name="image" accept="image/*" class="hidden" id="listingImageUpload">
                                <button type="button" onclick="document.getElementById('listingImageUpload').click()" 
                                        class="mt-2 text-blue-500 hover:underline text-sm">Pilih File</button>
                                
                                <p class="text-xs text-gray-500 mt-2">Maks 10 MB</p>
                            </div>
                        
                        </div>

                    <div class="w-80">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Pilih Komunitas</label>
                            <select name="community_id" required ...>
                                <option value="">Pilih Komunitas</option>
                                
                                @foreach($modalCommunities as $community) 
                                    <option value="{{ $community->id }}">{{ $community->name }}</option>
                                @endforeach
                            
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="button" class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-50">
                                <i class="far fa-save"></i>
                            </button>
                            <button type="submit" class="flex-1 px-4 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                Post Jual Beli
                            </button>
                        </div>
                        
                        </div>
                </div>
            </form>
            </div>

        <div id="contentDraf" class="tab-content hidden">
            <div class="text-center py-12 text-gray-500">
                <i class="far fa-file-alt text-5xl mb-4"></i>
                <p>Belum ada draf tersimpan</p>
            </div>
        </div>

    </div>
</div>

<script>
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('#createPostModal .tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('#createPostModal [id^="tab"]').forEach(tab => {
        tab.classList.remove('border-b-2', 'border-blue-500', 'text-blue-500', 'font-medium');
        tab.classList.add('text-gray-600');
    });
    
    // Show selected tab content
    // Perbaikan kecil di JS biar lebih robust
    const contentId = 'content' + tabName.charAt(0).toUpperCase() + tabName.slice(1);
    document.getElementById(contentId).classList.remove('hidden');
    
    // Add active state to selected tab
    const tabId = 'tab' + tabName.charAt(0).toUpperCase() + tabName.slice(1);
    const activeTab = document.getElementById(tabId);
    activeTab.classList.add('border-b-2', 'border-blue-500', 'text-blue-500', 'font-medium');
    activeTab.classList.remove('text-gray-600');
}

function closeCreatePostModal() {
    document.getElementById('createPostModal').classList.add('hidden');
}

// Opsional: Bikin modalnya kebuka (buat testing)
// Panggil ini dari tombol "Buat Post" utama lo
function openCreatePostModal() {
    document.getElementById('createPostModal').classList.remove('hidden');
    // Selalu reset ke tab 'Post' setiap kali dibuka
    switchTab('post'); 
}
</script>