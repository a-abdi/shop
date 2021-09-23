<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Services\MainService;
use App\Exceptions\NotFoundException;

class CartService extends MainService
{
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
     * @return true | App\Exceptions\InvalidArgumentException
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
}