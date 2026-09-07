<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MasterCategory;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Website;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin
        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin PLMS',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Buat Master Categories (Menggunakan Nama Ikon Tabler & Hex Color)
        $masterAI = MasterCategory::create(['user_id' =>$user->id, 'name' => 'AI Tools', 'slug' => 'ai-tools', 'icon' => 'robot', 'color' => '#6366f1']); // Indigo
        $masterDev = MasterCategory::create(['user_id' =>$user->id, 'name' => 'Development', 'slug' => 'development', 'icon' => 'code', 'color' => '#10b981']); // Emerald
        $masterDesign = MasterCategory::create(['user_id' =>$user->id, 'name' => 'Design', 'slug' => 'design', 'icon' => 'palette', 'color' => '#f43f5e']); // Rose

        // 3. Buat Categories
        $catAICoding = Category::create(['user_id' =>$user->id, 'master_category_id' => $masterAI->id, 'name' => 'AI Coding', 'slug' => 'ai-coding']);$catAIGen = Category::create(['user_id' => $user->id, 'master_category_id' =>$masterAI->id, 'name' => 'Generative AI', 'slug' => 'generative-ai']);

        $catFramework = Category::create(['user_id' =>$user->id, 'master_category_id' => $masterDev->id, 'name' => 'Frameworks', 'slug' => 'frameworks']);$catHosting = Category::create(['user_id' => $user->id, 'master_category_id' =>$masterDev->id, 'name' => 'Hosting', 'slug' => 'hosting']);

        $catUI = Category::create(['user_id' =>$user->id, 'master_category_id' => $masterDesign->id, 'name' => 'UI Design', 'slug' => 'ui-design']);$catInspo = Category::create(['user_id' => $user->id, 'master_category_id' =>$masterDesign->id, 'name' => 'Inspiration', 'slug' => 'inspiration']);

        // 4. Buat Tags
        $tagLaravel = Tag::create(['user_id' =>$user->id, 'name' => 'Laravel', 'slug' => 'laravel']);
        $tagTailwind = Tag::create(['user_id' =>$user->id, 'name' => 'Tailwind CSS', 'slug' => 'tailwind-css']);
        $tagProductivity = Tag::create(['user_id' =>$user->id, 'name' => 'Productivity', 'slug' => 'productivity']);

        // 5. Buat Data Website Dummy
        $websitesData = [
            [
                'name' => 'Cursor', 'url' => 'https://cursor.sh', 'pricing_type' => 'freemium',
                'description' => 'The AI-first code editor.', 'is_favorite' => true,
                'categories' => [$catAICoding->id], 'tags' => [$tagProductivity->id]
            ],
            [
                'name' => 'ChatGPT', 'url' => 'https://chat.openai.com', 'pricing_type' => 'freemium',
                'description' => 'OpenAI language model.', 'is_favorite' => true,
                'categories' => [$catAIGen->id], 'tags' => [$tagProductivity->id]
            ],
            [
                'name' => 'Laravel', 'url' => 'https://laravel.com', 'pricing_type' => 'free',
                'description' => 'The PHP Framework for Web Artisans.', 'is_favorite' => true,
                'categories' => [$catFramework->id], 'tags' => [$tagLaravel->id]
            ],
            [
                'name' => 'Vercel', 'url' => 'https://vercel.com', 'pricing_type' => 'freemium',
                'description' => 'Develop. Preview. Ship.', 'is_favorite' => false,
                'categories' => [$catHosting->id], 'tags' => []
            ],
            [
                'name' => 'Supabase', 'url' => 'https://supabase.com', 'pricing_type' => 'freemium',
                'description' => 'The open source Firebase alternative.', 'is_favorite' => true,
                'categories' => [$catHosting->id], 'tags' => []
            ],
            [
                'name' => 'Figma', 'url' => 'https://figma.com', 'pricing_type' => 'freemium',
                'description' => 'The collaborative interface design tool.', 'is_favorite' => true,
                'categories' => [$catUI->id], 'tags' => [$tagProductivity->id]
            ],
            [
                'name' => 'Dribbble', 'url' => 'https://dribbble.com', 'pricing_type' => 'free',
                'description' => 'Discover the world’s top designers & creatives.', 'is_favorite' => false,
                'categories' => [$catInspo->id], 'tags' => []
            ],
            [
                'name' => 'Tailwind UI', 'url' => 'https://tailwindui.com', 'pricing_type' => 'paid',
                'description' => 'Official Tailwind CSS UI components.', 'is_favorite' => false,
                'categories' => [$catUI->id, $catFramework->id], 'tags' => [$tagTailwind->id]
            ],
        ];

        foreach ($websitesData as $data) {$website = Website::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name'] . '-' . time()),
                'url' => $data['url'],
                'description' => $data['description'],
                'pricing_type' => $data['pricing_type'],
                'is_favorite' => $data['is_favorite'],
                'status' => 'active',
            ]);

            $website->categories()->attach($data['categories']);
            if (!empty($data['tags'])) {
                $website->tags()->attach($data['tags']);
            }
        }
    }
}
