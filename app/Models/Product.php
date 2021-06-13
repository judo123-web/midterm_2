<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'name',
        'category_id',
        'price',
        'stock',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class);
    }
}
