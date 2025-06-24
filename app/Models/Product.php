<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// app/Models/Product.php
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
    'name', 'description', 'price', 'stock', 'category_id', 'new_prod', 'discount'
    ];


    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
