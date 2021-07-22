<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Contracts\Repositories\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(private Product $product) 
    {
        parent::__construct($product);
    }

    public function getProducts() 
    {
        return $this->product->with('category:id,name')->get();
    }
}
