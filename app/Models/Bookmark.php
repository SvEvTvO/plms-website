<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'website_id',
        'category_id',
        'custom_title',
        'is_public',
        'pricing_type',   // <-- Tambahan Baru
        'payment_model',  // <-- Tambahan Baru
        'price_range'     // <-- Tambahan Baru
    ];

    // Bookmark ini milik siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Bookmark ini menyimpan website apa?
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    // Bookmark ini masuk kategori apa?
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Bookmark ini punya tag apa saja? (Many-to-Many)
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
