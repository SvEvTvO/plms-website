<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_url',
        'url_hash',
        'title',
        'description',
        'icon_url'
    ];

    // 1 Website bisa disimpan (dibookmark) oleh banyak User
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    // Tambahkan fungsi ini di dalam class Website
    public function publicBookmarks()
    {
        return $this->hasMany(Bookmark::class)->where('is_public', true);
    }
}
