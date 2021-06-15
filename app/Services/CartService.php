<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Services\MainService;

class CartService extends MainService
{
    /**
     * Preparation cart data.
     *
     * @param string
     * @return array
     */
    public function cartData($productId)
    {
        return [
            'user_id'    => Auth::id(),
            'product_id' => $productId,
            'status'     => 'active',
        ];
    }
    
}