<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Contracts\Repositories\CartRepositoryInterface;
use Illuminate\Support\Facades\DB;

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
        return $this->user->find($userId)->carts()->whereNull('order_id')->with('product:id,image_src,name')->get();
    }

    /**
     * check update price and discount.
     * 
     * @param int $userId
     * @return null|Illuminate\Database\Eloquent\Collection
     */
    public function checkUpdateCart(int $userId)
    {
        return $this->cart->whereExists(function ($query) use ($userId) {
            $query->select(DB::raw(1))
                  ->from('products')
                  ->where('carts.user_id', $userId)
                  ->whereNull('carts.order_id')
                  ->whereColumn('products.id', 'carts.product_id')
                  ->where(function ($query) {
                      $query->whereColumn('products.price', '<>' ,'carts.price')
                      ->orWhereColumn('products.discount', '<>' ,'carts.discount');
                  });
        })->get();
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

     /**
     * Update order_id in cart.
     * 
     * @param int $userId
     * @param int $orderId
     * @return int 
     */
    public function registerOrder(int $userId, int $orderId)
    {
        return $this->user->find($userId)->carts()->whereNull('order_id')->update(['order_id'=> $orderId]);
    }

     /**
     * Checks the product in the cart.
     * 
     * @param int $userId
     * @param int $productId
     * @return bool 
     */
    public function checkExistCart(int $userId, int $productId)
    {
        return $this->user->find($userId)->carts()->whereNull('order_id')->where('product_id', $productId)->exists();
    }
}
