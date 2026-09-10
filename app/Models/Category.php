<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['user_id', 'category_group_id', 'name', 'slug', 'icon', 'color'];

    public function group()
    {
        return $this->belongsTo(CategoryGroup::class, 'category_group_id');
    }

    // Tambahkan fungsi relasi ke Tags ini
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}
