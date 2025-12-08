<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'details',
        'image',     // ✔ SINGLE IMAGE
        'size',
        'color',
        'category',
        'price',
        'tag_ids',   // ✔ multiple tags JSON
    ];

    protected $casts = [
        'tag_ids' => 'array',   // convert json ↔ array
    ];
}
