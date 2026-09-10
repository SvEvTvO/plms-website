<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryGroup extends Model
{
    protected $fillable = ['user_id', 'name', 'icon'];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
