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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'product_code',
    ];

    /**
     * Fill product data.
     *
     * @param array
     * @param string
     * @return array
     */
    public function data($product, $image_url) {
        return array_merge($product, [
            "product_code" => Str::random(8),
            'user_id'=> Auth::id(),
            'category_id'=> 1,
            "image_src" => $image_url,
        ]);
    }
}
