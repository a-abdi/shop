<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\Admin;

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
        'admin_id',
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
        'admin_id',
    ];

    public function getImageSrcAttribute($value)
    {
        return asset($value);
    }

    /**
     * Get the users for the product.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'orders');
    }

     /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the admin that owns the product.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
