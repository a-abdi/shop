<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Contracts\Repositories\CartRepositoryInterface;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    public function __construct(private Cart $cart, private User $user, private Product $product) 
    {
        parent::__construct($cart);
    }

    /**
     * Get carts.
     * @param int $userId
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getCart(int $userId)
    {
        return $this->user->find($userId)->carts()->whereNull('order_id')->get();
    }

    /**
     * Get total price cart.
     * 
     * @param int $userId
     * @return int 
     */
    public function totalPrice(int $userId)
    {
        return $this->user->find($userId)->carts()->whereNull('order_id')->sum('price');
    }

    /**
     * Get total discount cart.
     * 
     * @param int $userId
     * @return int 
     */
    public function totalDiscount(int $userId)
    {
        return $this->user->find($userId)->carts()->whereNull('order_id')->sum('discount');
    }
}
