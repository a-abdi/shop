<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'user_id',
        'discount',
        'image_src',
        'product_code',
        'quantity',
        'description',
        'category_id',
    ];

    /**
     * Fill product data.
     *
     * @param object
     * @return array
     */
    public function product_data($product) {
        return [
            "name" => $product->name,
            "product_code" => Str::random(8),
            'user_id'=> Auth::id(),
            'category_id'=> 1,
            "price" => $product->price,
            "discount" => $product->discount,
            "description" => $product->description,
            "quantity" => $product->quantity,
            "image_src" => $url,
        ];
    }
}
