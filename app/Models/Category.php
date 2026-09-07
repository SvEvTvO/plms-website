<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'master_category_id', 'name', 'slug', 
        'description', 'icon', 'sort_order', 'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function masterCategory()
    {
        return $this->belongsTo(MasterCategory::class);
    }

    public function websites()
    {
        return $this->belongsToMany(Website::class, 'website_category');
    }
}
