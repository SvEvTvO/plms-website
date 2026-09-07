<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class WebsiteController extends Controller
{

    public function index(Request $request)
    {
        $query = auth()->user()->websites()->with(['categories', 'tags']);

        // Cek apakah user sedang melakukan pencarian/filter aktif
        $isFiltering = $request->filled('search') || $request->filled('category') || $request->filled('pricing');

        // Cek status tab (Active / Archived)
        $status = $request->query('status', 'active');
        $query->where('status', $status);

        // 1. Logika Filter & Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                ->orWhere('description', 'ilike', "%{$search}%")
                ->orWhere('url', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        if ($request->filled('pricing')) {
            $query->where('pricing_type', $request->pricing);
        }

        // Data Flat (Digunakan JIKA user sedang melakukan pencarian)
        $websites = $query->latest()->paginate(24)->withQueryString();

        // 2. Data Grouped (Digunakan untuk tampilan Default / Folder View)
        // Mengambil hierarki: MasterCategory -> Category -> Websites
        $masterCategories = auth()->user()->masterCategories()
            ->with(['categories.websites' => function ($q) use ($status) {
                // Hanya ambil website yang statusnya sesuai tab (active/archived)
                $q->where('status', $status)->latest();
            }])
            ->orderBy('name')
            ->get();

        return view('websites.index', compact('websites', 'masterCategories', 'isFiltering', 'status'));
    }

    public function create()
    {
        // Ambil master category beserta category anaknya untuk dropdown ber-group
        $masterCategories = auth()->user()->masterCategories()->with('categories')->orderBy('name')->get();

        return view('websites.create', compact('masterCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url|max:2048',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'pricing_type' => 'required|in:free,freemium,paid',
            'categories' => 'required|array', // Harus memilih minimal 1 kategori
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|string', // Format: "laravel, php, backend"
            'why_saved' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // 1. Simpan Website
        $website = auth()->user()->websites()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'] . '-' . time()), // Antisipasi nama web sama
            'url' => $validated['url'],
            'description' => $validated['description'],
            'pricing_type' => $validated['pricing_type'],
            'why_saved' => $validated['why_saved'],
            'notes' => $validated['notes'],
        ]);

        // 2. Sync Kategori (Many-to-Many)
        $website->categories()->sync($validated['categories']);

        // 3. Proses & Sync Tags (Otomatis Buat Baru Jika Belum Ada)
        if (!empty($validated['tags'])) {
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            $tagIds = [];

            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag = auth()->user()->tags()->firstOrCreate(
                        ['name' => $tagName],
                        ['slug' => Str::slug($tagName)]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            $website->tags()->sync($tagIds);
        }

        return redirect()->route('websites.index')->with('success', 'Website saved successfully.');
    }

    public function toggleFavorite(Website $website)
    {
        // Pastikan user hanya bisa mengubah status website miliknya sendiri
        if ($website->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $website->is_favorite = !$website->is_favorite;
        $website->save();

        return response()->json([
            'is_favorite' => $website->is_favorite,
            'message' => $website->is_favorite ? 'Added to favorites' : 'Removed from favorites'
        ]);

    }



    public function edit(Website $website)
    {
        if (request()->user()->cannot('update', $website)) {
            abort(403, 'Unauthorized action.');
        }
        $masterCategories = auth()->user()->masterCategories()->with('categories')->orderBy('name')->get();
        $website->load('categories', 'tags');
        // Siapkan data kategori yang sudah terpilih
        $selectedCategories = $website->categories->pluck('id')->toArray();
        // Gabungkan tag menjadi string yang dipisahkan koma
        $tagsString = $website->tags->pluck('name')->implode(', ');
        return view('websites.edit', compact('website', 'masterCategories', 'selectedCategories', 'tagsString'));
    }

    public function update(Request $request, Website $website)
    {
        if ($request->user()->cannot('update', $website)) {
            abort(403);
        }

        $validated = $request->validate([
            'url' => 'required|url|max:2048',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'pricing_type' => 'required|in:free,freemium,paid',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|string',
            'why_saved' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $website->update([
            'name' => $validated['name'],
            'url' => $validated['url'],
            'description' => $validated['description'],
            'pricing_type' => $validated['pricing_type'],
            'why_saved' => $validated['why_saved'],
            'notes' => $validated['notes'],
        ]);

        $website->categories()->sync($validated['categories']);

        if (isset($validated['tags'])) {
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag = auth()->user()->tags()->firstOrCreate(
                        ['name' => $tagName],
                        ['slug' => \Illuminate\Support\Str::slug($tagName)]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            $website->tags()->sync($tagIds);
        } else {
            $website->tags()->detach(); // Hapus semua tag jika form dikosongkan
        }

        return redirect()->route('websites.index')->with('success', 'Website updated successfully.');
    }

    public function destroy(Website $website)
    {
        if (request()->user()->cannot('delete', $website)) {
            abort(403);
        }

        $website->delete();

        return redirect()->route('websites.index')->with('success', 'Website deleted successfully.');
    }


    public function toggleArchive(Website $website)
    {
        if (request()->user()->cannot('update', $website)) {
            abort(403);
        }

        $website->status = $website->status === 'active' ? 'archived' : 'active';
        $website->save();

        $msg = $website->status === 'archived' ? 'Website archived.' : 'Website restored to active.';
        return back()->with('success', $msg);
    }


    public function fetchMetadata(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        try {
            // Fetch halaman dengan batas waktu 5 detik agar tidak hang
            $response = Http::timeout(5)->get($request->url);
            $html = $response->body();

            // Ekstrak Title
            preg_match('/<title[^>]*>(.*?)<\/title>/ims', $html, $titleMatches);
            $title = isset($titleMatches[1]) ? trim(strip_tags($titleMatches[1])) : '';

            // Ekstrak Meta Description (Standard & Open Graph)
            preg_match('/<meta[^>]*name=["\']description["\'][^>]*content=["\'](.*?)["\'][^>]*>/ims', $html, $descMatches);
            if (empty($descMatches)) {
                preg_match('/<meta[^>]*property=["\']og:description["\'][^>]*content=["\'](.*?)["\'][^>]*>/ims', $html, $descMatches);
            }
            $description = isset($descMatches[1]) ? trim(strip_tags($descMatches[1])) : '';

            // Decode HTML entities (misal: &amp; menjadi &)
            return response()->json([
                'title' => html_entity_decode($title),
                'description' => html_entity_decode($description),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Could not fetch metadata. Website might be blocking requests.'
            ], 500);
        }
    }
}
