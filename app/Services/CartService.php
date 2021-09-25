<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Services\MainService;
use App\Exceptions\NotFoundException;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\CartRepositoryInterface;

class CartService extends MainService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CartRepositoryInterface $cartRepository,
    ){}

    /**
     * Preparation cart data.
     *
     * @param string
     * @return array
     */
    public function createCartData($product)
    {
        return [
            'user_id'    => Auth::id(),
            'product_id' => $product->id,
            'price'      => $product->price,
            'discount'   => $product->discount,
        ];
    }

    /**
     * Preparation cart data.
     *
     * @param  int
     * @return true|App\Exceptions\InvalidArgumentException
     */
    public function cartOwnerUser(int $id)
    {
        $user = Auth::user();

        $userOwner = $user->carts()->where('id', $id)->exists();

        if (!$userOwner) {
            throw new NotFoundException(__('messages.not_found', ['name' => 'cart']));
        }

        return true;
    }

    /**
     * Update carts.
     *
     * @param int $userId
     * @return  Illuminate\Database\Eloquent\Collection
     */
    public function updateCart(int $userId)
    {
        $updateCarts = $this->cartRepository->checkUpdateCart($userId);
       
        if ($updateCarts->isNotEmpty()) {
            foreach ($updateCarts as $key => $cart) {
                $product = $this->productRepository->find($cart->product_id);
    
                $this->cartRepository->update([
                    'price' => $product->price,
                    'discount' => $product->discount,
                ], $cart);
            }
        }

        return $updateCarts;
    }
}