<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// app/Models/ProductImage.php
class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'image_url'];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
