<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'slug', 'url', 'description',
        'favicon_url', 'image_url', 'pricing_type',
        'why_saved', 'notes', 'status', 'is_favorite', 'last_visited_at'
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
        'last_visited_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'website_category');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'website_tag');
    }
}
