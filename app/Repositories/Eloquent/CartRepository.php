<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Models\User;
use App\Contracts\Repositories\CartRepositoryInterface;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    public function __construct(private Cart $cart, private User $user) 
    {
        parent::__construct($cart);
    }

    /**
     * Get carts.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getCart($userId)
    {
        return $this->user->find($userId)->carts->where('status', 'cart');
    }
}
