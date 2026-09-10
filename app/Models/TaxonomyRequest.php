<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxonomyRequest extends Model
{
    protected $fillable = [
        'user_id', 'type', 'name', 'target_parent_id', 'icon', 'color', 'status', 'admin_notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Membantu mengambil relasi Induk Grup (jika user mengajukan Kategori)
    public function targetGroup()
    {
        return $this->belongsTo(CategoryGroup::class, 'target_parent_id');
    }

    // Membantu mengambil relasi Induk Kategori (jika user mengajukan Tag)
    public function targetCategory()
    {
        return $this->belongsTo(Category::class, 'target_parent_id');
    }
}
