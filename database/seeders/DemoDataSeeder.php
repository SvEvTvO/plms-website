<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Website;
use App\Models\Category;
use App\Models\Bookmark;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua kategori beserta tag-tag lokal di dalamnya
        $categories = Category::with('tags')->get();

        if ($categories->isEmpty()) {
            $this->command->info('Kategori kosong! Jalankan DatabaseSeeder dulu ya.');
            return;
        }

        $this->command->info('Membuat data Website Populer...');

        $websitesData = [
            // Development
            ['url' => 'https://laravel.com', 'title' => 'Laravel', 'desc' => 'The PHP framework for web artisans.'],
            ['url' => 'https://tailwindcss.com', 'title' => 'Tailwind CSS', 'desc' => 'A utility-first CSS framework for rapidly building modern interfaces.'],
            ['url' => 'https://react.dev', 'title' => 'React', 'desc' => 'A library for building user interfaces.'],
            ['url' => 'https://developer.mozilla.org', 'title' => 'MDN Web Docs', 'desc' => 'Web development documentation for HTML, CSS, JavaScript, and web APIs.'],
            ['url' => 'https://github.com', 'title' => 'GitHub', 'desc' => 'A platform for source code hosting, collaboration, and software development.'],
            ['url' => 'https://stackoverflow.com', 'title' => 'Stack Overflow', 'desc' => 'A community-driven question and answer platform for programmers.'],

            // AI
            ['url' => 'https://chatgpt.com', 'title' => 'ChatGPT', 'desc' => 'An AI assistant for writing, analysis, brainstorming, coding, and research.'],
            ['url' => 'https://huggingface.co', 'title' => 'Hugging Face', 'desc' => 'A platform for sharing and working with machine learning models and datasets.'],
            ['url' => 'https://www.kaggle.com', 'title' => 'Kaggle', 'desc' => 'A platform for data science competitions, datasets, notebooks, and learning.'],
            ['url' => 'https://replicate.com', 'title' => 'Replicate', 'desc' => 'Run and experiment with machine learning models through a simple API.'],
            ['url' => 'https://www.perplexity.ai', 'title' => 'Perplexity', 'desc' => 'An AI-powered search and answer engine.'],

            // Design
            ['url' => 'https://www.figma.com', 'title' => 'Figma', 'desc' => 'A collaborative interface design and prototyping platform.'],
            ['url' => 'https://coolors.co', 'title' => 'Coolors', 'desc' => 'A fast tool for generating and exploring color palettes.'],
            ['url' => 'https://unsplash.com', 'title' => 'Unsplash', 'desc' => 'A large library of freely usable photography and visual inspiration.'],
            ['url' => 'https://fonts.google.com', 'title' => 'Google Fonts', 'desc' => 'A directory of open-source fonts for digital products and websites.'],
            ['url' => 'https://www.remove.bg', 'title' => 'Remove.bg', 'desc' => 'A tool for automatically removing image backgrounds.'],

            // Productivity
            ['url' => 'https://www.notion.so', 'title' => 'Notion', 'desc' => 'A workspace for notes, documents, project planning, and knowledge management.'],
            ['url' => 'https://trello.com', 'title' => 'Trello', 'desc' => 'A visual project management tool based on boards, lists, and cards.'],
            ['url' => 'https://todoist.com', 'title' => 'Todoist', 'desc' => 'A task management app for organizing personal and work tasks.'],
            ['url' => 'https://calendar.google.com', 'title' => 'Google Calendar', 'desc' => 'A calendar and scheduling service for managing events and time.'],

            // Business & Finance
            ['url' => 'https://stripe.com', 'title' => 'Stripe', 'desc' => 'Financial infrastructure and payments tools for internet businesses.'],
            ['url' => 'https://www.canva.com', 'title' => 'Canva', 'desc' => 'An online visual design platform for creating presentations, graphics, and content.'],
            ['url' => 'https://mailchimp.com', 'title' => 'Mailchimp', 'desc' => 'Tools for email marketing, audience management, and marketing automation.'],
            ['url' => 'https://www.shopify.com', 'title' => 'Shopify', 'desc' => 'An e-commerce platform for building and managing online stores.'],

            // Learning & Reference
            ['url' => 'https://www.wikipedia.org', 'title' => 'Wikipedia', 'desc' => 'A free collaborative encyclopedia and general reference resource.'],
            ['url' => 'https://www.coursera.org', 'title' => 'Coursera', 'desc' => 'An online learning platform offering courses and professional education.'],
            ['url' => 'https://www.freecodecamp.org', 'title' => 'freeCodeCamp', 'desc' => 'A free platform for learning programming and web development.'],

            // Media & Entertainment
            ['url' => 'https://www.youtube.com', 'title' => 'YouTube', 'desc' => 'A video platform for education, entertainment, tutorials, and creator content.'],
            ['url' => 'https://open.spotify.com', 'title' => 'Spotify', 'desc' => 'A streaming platform for music, podcasts, and audio content.'],

            // Travel & Utilities
            ['url' => 'https://www.google.com/maps', 'title' => 'Google Maps', 'desc' => 'A mapping and navigation service for places, routes, and geographic information.'],
            ['url' => 'https://www.wolframalpha.com', 'title' => 'WolframAlpha', 'desc' => 'A computational knowledge engine for calculations, facts, and structured queries.'],
        ];

        $websites = [];
        foreach ($websitesData as $data) {
            $parsedUrl = parse_url($data['url']);
            $host = preg_replace('/^www\./', '', $parsedUrl['host'] ?? '');
            $path = rtrim($parsedUrl['path'] ?? '', '/');
            $cleanUrl = strtolower($host . $path);
            $urlHash = md5($cleanUrl);

            $websites[] = Website::firstOrCreate(
                ['url_hash' => $urlHash],
                [
                    'original_url' => $data['url'],
                    'title' => $data['title'],
                    'description' => $data['desc'],
                    'icon_url' => "https://www.google.com/s2/favicons?domain={$host}&sz=64"
                ]
            );
        }

        $this->command->info('Membuat User Dummy...');

        $usersData = [
            ['name' => 'Andi Developer', 'email' => 'andi@demo.com'],
            ['name' => 'Budi Designer', 'email' => 'budi@demo.com'],
            ['name' => 'Citra Marketer', 'email' => 'citra@demo.com'],
            ['name' => 'Dimas Researcher', 'email' => 'dimas@demo.com'],
            ['name' => 'Eka Product Builder', 'email' => 'eka@demo.com'],
            ['name' => 'Fajar Gamer', 'email' => 'fajar@demo.com'],
            ['name' => 'Gita Creator', 'email' => 'gita@demo.com'],
            ['name' => 'Hana Student', 'email' => 'hana@demo.com'],
            ['name' => 'Iqbal Entrepreneur', 'email' => 'iqbal@demo.com'],
            ['name' => 'Joko Tech Explorer', 'email' => 'joko@demo.com'],
        ];

        $users = [];
        foreach ($usersData as $ud) {
            $users[] = User::firstOrCreate(
                ['email' => $ud['email']],
                ['name' => $ud['name'], 'password' => Hash::make('password123'), 'email_verified_at' => now()]
            );
        }

        $this->command->info('Menyebarkan 30 Bookmark Demo ke 10 User...');

        // Setiap user mendapatkan tepat 3 website berbeda.
        // Total demo bookmark = 10 users × 3 website = 30 postingan.
        $shuffledWebsites = collect($websites)->shuffle();

        foreach ($users as $index => $user) {
            $userWebsites = $shuffledWebsites->slice($index * 3, 3);

            foreach ($userWebsites as $website) {
                // Pilih 1 kategori secara acak untuk website ini.
                $category = $categories->random();

                $bookmark = Bookmark::firstOrCreate([
                    'user_id' => $user->id,
                    'website_id' => $website->id,
                ], [
                    'category_id' => $category->id,
                    'custom_title' => rand(0, 1) ? $website->title . ' (Favorit)' : null,
                    'is_public' => true,
                ]);

                // Tag hanya berasal dari kategori yang dipilih.
                $availableTags = $category->tags;

                if ($availableTags->count() > 0) {
                    $takeCount = min(rand(2, 4), $availableTags->count());
                    $randomTags = $availableTags->random($takeCount)->pluck('id');
                    $bookmark->tags()->syncWithoutDetaching($randomTags);
                }
            }
        }

        $this->command->info('✅ Selesai! 10 user demo dan 30 postingan website berhasil ditanamkan.');
    }
}
