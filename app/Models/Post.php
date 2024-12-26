<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'category_id',
        'description',
        'text',
        'file_path',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
