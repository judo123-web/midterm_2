<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function userid($id){
        return Order::where('user_id','=',$id)->get();
    }

    protected $fillable = [
        'order_unique_code',
        'paid_amount',
        'user_id',
    ];

    public function products(){
        return $this->belongsToMany(Product::class);
    }
}
