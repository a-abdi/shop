<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use App\Contracts\Repositories\CartRepositoryInterface;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    public function __construct(private Cart $cart, private User $user, private Product $product) 
    {
        parent::__construct($cart);
    }

    /**
     * Get carts.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getProductsCart($userId)
    {
        $productsId = $this->user->find($userId)->carts()->where('status', 'cart')->pluck('product_id');

        return $this->product->whereIn('id', $productsId)->get();;
    }
}
