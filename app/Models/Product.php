<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'details',
        'price',
        'stock',
        'category_id',
        'photo',
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
