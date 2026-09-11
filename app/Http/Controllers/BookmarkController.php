<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Website;
use App\Models\CategoryGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BookmarkController extends Controller
{
    /**
     * Tampilkan form Tambah Website (Bookmark)
     */
    public function create()
    {
        $categories = Category::with('group')->orderBy('name', 'asc')->get();
        $allTags = Tag::where('status', 'approved')->get(['id', 'name', 'category_id']);

        // AMBIL SEMUA GRUP UNTUK PILIHAN INLINE CREATION
        $groups = CategoryGroup::orderBy('name', 'asc')->get();

        return view('bookmarks.create', compact('categories', 'allTags', 'groups'));
    }

    /**
     * Proses penyimpanan Website
     */
    public function store(Request $request)
    {
        // 1. Validasi Input (Tambahkan category_icon dan group_icon)
        $validated = $request->validate([
            'url' => 'required|url|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            'new_category_name' => 'nullable|string|max:255',
            'category_icon' => 'nullable|string|max:50', // <-- Tambahan Baru
            'group_id' => 'nullable|exists:category_groups,id',
            'new_group_name' => 'nullable|string|max:255',
            'group_icon' => 'nullable|string|max:50', // <-- Tambahan Baru
            'custom_title' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'pricing_type' => 'required|in:free,freemium,premium',
            'payment_model' => 'nullable|in:subscription,one_time',
            'price_range' => 'nullable|string',
        ]);

        // 2. Logika Inline Creation (Grup & Kategori dengan Icon)
        $finalCategoryId = $validated['category_id'] ?? null;

        if ($request->filled('new_category_name')) {
            $finalGroupId = $validated['group_id'] ?? null;

            // Jika membuat Grup Baru
            if ($request->filled('new_group_name')) {
                $group = CategoryGroup::firstOrCreate(
                    ['name' => $validated['new_group_name']],
                    [
                        'user_id' => auth()->id(),
                        'icon' => $validated['group_icon'] ?? 'folder' // Simpan Ikon Grup
                    ]
                );
                $finalGroupId = $group->id;
            }

            // Buat Kategori Baru
            $category = Category::firstOrCreate(
                [
                    'name' => $validated['new_category_name'],
                    'category_group_id' => $finalGroupId
                ],
                [
                    'slug' => \Illuminate\Support\Str::slug($validated['new_category_name']) . '-' . uniqid(),
                    'user_id' => auth()->id(),
                    'icon' => $validated['category_icon'] ?? 'folder' // Simpan Ikon Kategori
                ]
            );
            $finalCategoryId = $category->id;
        }

        // Validasi ekstra: Pastikan pada akhirnya category_id tersedia
        if (!$finalCategoryId) {
            return back()->withErrors(['category_id' => 'Kategori wajib dipilih atau dibuat baru.'])->withInput();
        }

        // 3. Normalisasi URL
        $originalUrl = $request->url;
        $parsedUrl = parse_url($originalUrl);
        $host = preg_replace('/^www\./', '', $parsedUrl['host'] ?? '');
        $path = rtrim($parsedUrl['path'] ?? '', '/');
        $cleanUrl = strtolower($host . $path);
        $urlHash = md5($cleanUrl);

        // 4. Cek Website
        $website = Website::where('url_hash', $urlHash)->first();
        if (!$website) {
            $meta = $this->fetchWebsiteMeta($originalUrl);
            $website = Website::create([
                'original_url' => $originalUrl,
                'url_hash' => $urlHash,
                'title' => $meta['title'],
                'description' => $meta['description'],
                'icon_url' => $meta['icon_url'],
            ]);
        }

        // 5. Cek Bookmark Duplikat
        $existingBookmark = Bookmark::where('user_id', auth()->id())
                                    ->where('website_id', $website->id)
                                    ->first();
        if ($existingBookmark) {
            return redirect()->back()->withErrors(['url' => 'Kamu sudah menyimpan website ini sebelumnya.'])->withInput();
        }

        // 6. Simpan Bookmark (Menggunakan $finalCategoryId)
        $bookmark = Bookmark::create([
            'user_id' => auth()->id(),
            'website_id' => $website->id,
            'category_id' => $finalCategoryId, // <-- Menggunakan ID yang sudah divalidasi
            'custom_title' => $validated['custom_title'] ?? null,
            'is_public' => false,

            // Simpan Data Harga
            'pricing_type' => $validated['pricing_type'],
            'payment_model' => $validated['pricing_type'] === 'free' ? null : ($validated['payment_model'] ?? null),
            'price_range' => $validated['pricing_type'] === 'free' ? null : ($validated['price_range'] ?? null),
        ]);

        // 7. Proses Tags (Pribadi & Otomatis Aktif)
        if (!empty($validated['tags'])) {
            $tagIds = [];
            $inputTags = array_slice($validated['tags'], 0, 5);

            foreach ($inputTags as $tagName) {
                $cleanTagName = trim($tagName);
                if ($cleanTagName === '') continue;

                // Cari tag di dalam KATEGORI YANG DIPILIH, kalau tidak ada buat tag pribadi baru
                $tag = Tag::firstOrCreate(
                    [
                        'category_id' => $finalCategoryId,
                        'name' => $cleanTagName,
                        'user_id' => auth()->id() // <-- Scope Tag Pribadi
                    ],
                    [
                        'slug' => \Illuminate\Support\Str::slug($cleanTagName) . '-' . uniqid(),
                        'status' => 'approved' // <-- Langsung aktif untuk user
                    ]
                );

                $tagIds[] = $tag->id;
            }
            $bookmark->tags()->sync($tagIds);
        }

        return redirect()->route('dashboard')->with('success', 'Website berhasil disimpan ke Koleksi Pribadi!');
    }

    /**
     * FUNGSI BANTUAN: Auto-Fetch Meta Data (Scraper Sederhana)
     */
    private function fetchWebsiteMeta($url)
    {
        $data = [
            'title' => null,
            'description' => null,
            'icon_url' => null,
        ];

        try {
            // Ambil favicon via Google S2 (Sangat stabil)
            $host = parse_url($url, PHP_URL_HOST);
            $data['icon_url'] = "https://www.google.com/s2/favicons?domain={$host}&sz=64";

            // Ambil konten HTML dengan limit waktu 3 detik agar tidak lemot
            $response = Http::timeout(3)->get($url);

            if ($response->successful()) {
                $html = $response->body();

                // Cari Judul
                if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $matches)) {
                    $data['title'] = trim(strip_tags($matches[1]));
                }

                // Cari Deskripsi Meta
                if (preg_match('/<meta[^>]*name=["\']description["\'][^>]*content=["\'](.*?)["\'][^>]*>/is', $html, $matches)) {
                    $data['description'] = trim($matches[1]);
                }
            }
        } catch (\Exception $e) {
            // Jika gagal scrape (misal web diproteksi), biarkan kosong
        }

        // Fallback jika tidak ada judul
        if (empty($data['title'])) {
            $data['title'] = $host ?? 'Website Tanpa Judul';
        }

        return $data;
    }

    // ==========================================
    // LOGIKA PUBLISH & UNPUBLISH
    // ==========================================
    public function publish(Request $request, Bookmark $bookmark)
    {
        // Pastikan bookmark ini milik user yang sedang login
        if ($bookmark->user_id !== auth()->id()) abort(403);

        $request->validate([
            'custom_title' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ]);

        $category = Category::findOrFail($request->category_id);

        $bookmark->update([
            'custom_title' => $request->custom_title,
            'is_public' => true,
            'category_id' => $category->id // Pindahkan ke kategori publik
        ]);

        // Proses Tag Publik
        $tagIds = [];
        if (!empty($request->tags)) {
            foreach(array_slice($request->tags, 0, 5) as $tagName) {
                $cleanName = trim($tagName);
                if($cleanName === '') continue;

                // Buatkan tag global (user_id = null)
                $tag = Tag::firstOrCreate([
                    'category_id' => $category->id,
                    'name' => $cleanName,
                    'user_id' => null
                ], [
                    'slug' => \Illuminate\Support\Str::slug($cleanName) . '-' . uniqid(),
                    'status' => 'pending' // Butuh acc admin
                ]);
                $tagIds[] = $tag->id;
            }
        }

        $bookmark->tags()->sync($tagIds);

        return back()->with('success', 'Website berhasil mengudara di Komunitas Publik! 🚀');
    }

    public function unpublish(Bookmark $bookmark)
    {
        if ($bookmark->user_id !== auth()->id()) abort(403);

        // Tarik kembali statusnya jadi privat
        $bookmark->update(['is_public' => false]);

        return back()->with('success', 'Website ditarik dari publik dan kembali menjadi privat 🔒.');
    }

    // Menampilkan halaman Edit
    public function edit(Bookmark $bookmark)
    {
        if ($bookmark->user_id !== auth()->id()) abort(403);

        $bookmark->load(['website', 'tags']);
        
        $categories = Category::with('group')->orderBy('name', 'asc')->get();
        $allTags = Tag::where('status', 'approved')->get(['id', 'name', 'category_id']);
        
        // AMBIL SEMUA GRUP UNTUK PILIHAN INLINE CREATION
        $groups = CategoryGroup::orderBy('name', 'asc')->get();

        return view('bookmarks.edit', compact('bookmark', 'categories', 'allTags', 'groups'));
    }

    // Memproses data Update
    public function update(Request $request, Bookmark $bookmark)
    {
        if ($bookmark->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'url' => 'required|url|max:2048',
            'category_id' => 'nullable|exists:categories,id', // Nullable karena bisa buat baru
            'new_category_name' => 'nullable|string|max:255',
            'category_icon' => 'nullable|string|max:50',
            'group_id' => 'nullable|exists:category_groups,id',
            'new_group_name' => 'nullable|string|max:255',
            'group_icon' => 'nullable|string|max:50',
            'custom_title' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'pricing_type' => 'required|in:free,freemium,premium',
            'payment_model' => 'nullable|in:subscription,one_time',
            'price_range' => 'nullable|string',
        ]);

        // 1. Logika Inline Creation (Grup & Kategori)
        $finalCategoryId = $validated['category_id'] ?? null;

        if ($request->filled('new_category_name')) {
            $finalGroupId = $validated['group_id'] ?? null;

            if ($request->filled('new_group_name')) {
                $group = CategoryGroup::firstOrCreate(
                    ['name' => $validated['new_group_name']],
                    ['user_id' => auth()->id(), 'icon' => $validated['group_icon'] ?? 'folder']
                );
                $finalGroupId = $group->id;
            }

            $category = Category::firstOrCreate(
                ['name' => $validated['new_category_name'], 'category_group_id' => $finalGroupId],
                [
                    'slug' => \Illuminate\Support\Str::slug($validated['new_category_name']) . '-' . uniqid(),
                    'user_id' => auth()->id(),
                    'icon' => $validated['category_icon'] ?? 'folder'
                ]
            );
            $finalCategoryId = $category->id;
        }

        if (!$finalCategoryId) {
            return back()->withErrors(['category_id' => 'Kategori wajib dipilih atau dibuat baru.'])->withInput();
        }

        // 2. Normalisasi & Update Website URL (Sesuai MD5 dari fungsi Store kamu)
        $originalUrl = $request->url;
        $parsedUrl = parse_url($originalUrl);
        $host = preg_replace('/^www\./', '', $parsedUrl['host'] ?? '');
        $path = rtrim($parsedUrl['path'] ?? '', '/');
        $cleanUrl = strtolower($host . $path);
        $urlHash = md5($cleanUrl);

        $website = \App\Models\Website::firstOrCreate(
            ['url_hash' => $urlHash],
            ['original_url' => $originalUrl]
        );

        // 3. Update Data Bookmark
        $bookmark->update([
            'website_id' => $website->id,
            'category_id' => $finalCategoryId,
            'custom_title' => $validated['custom_title'] ?? null,
            'pricing_type' => $validated['pricing_type'],
            'payment_model' => $validated['pricing_type'] === 'free' ? null : ($validated['payment_model'] ?? null),
            'price_range' => $validated['pricing_type'] === 'free' ? null : ($validated['price_range'] ?? null),
        ]);

        // 4. Sinkronisasi Tags (Inline Creation)
        $tagIds = [];
        if (!empty($validated['tags'])) {
            foreach(array_slice($validated['tags'], 0, 5) as $tagName) {
                $cleanName = trim($tagName);
                if($cleanName === '') continue;
                
                $tag = Tag::firstOrCreate([
                    'category_id' => $finalCategoryId,
                    'name' => $cleanName,
                    'user_id' => auth()->id()
                ], [
                    'slug' => \Illuminate\Support\Str::slug($cleanName) . '-' . uniqid(),
                    'status' => 'approved'
                ]);
                $tagIds[] = $tag->id;
            }
        }
        $bookmark->tags()->sync($tagIds);

        return redirect()->route('dashboard')->with('success', 'Data Website berhasil diperbarui!');
    }
}
