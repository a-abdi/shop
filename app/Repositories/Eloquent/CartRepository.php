<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Contracts\Repositories\CartRepositoryInterface;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    public function __construct(private Cart $cart) 
    {
        parent::__construct($cart);
    }
}
