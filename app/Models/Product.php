<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'details',
        'price',
        'stock',
        'category_id'
    ];

    /**
     * Get the category that owns the product (Many-to-One).
     */
    public function category()
    {
        // Product belongs to a Category
        return $this->belongsTo(Category::class);
    }
}
