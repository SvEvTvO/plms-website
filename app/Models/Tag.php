<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['user_id', 'category_id', 'name', 'slug', 'status'];

    // Relasi ke Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke Bookmark (Tetap Many-to-Many)
    public function bookmarks()
    {
        return $this->belongsToMany(Bookmark::class);
    }
}
