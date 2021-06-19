<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\CartRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Services\ProductService;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private UserRepositoryInterface $userRepository,
        private CartRepositoryInterface $cartRepository,
        private ProductService $productService,
        private CartService $cartService,
    ){}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id();

        $carts = $this->cartRepository->getCart($userId);

        return $this->successResponse($carts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = $this->productRepository->find($request->productId);

        // Check exist product.
        $this->productService->checkExist($product, __('messages.not_found', [
            'name' => 'product'
        ]));
        
        $cartData = $this->cartService->cartData($request->productId);

        $newCart = $this->cartRepository->create($cartData);
        
        return $this->successResponse($newCart, __('messages.created', [
            'name' => 'cart'
        ]), 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cart = $this->cartRepository->find($id);

        // Check exist cart.
        $this->cartService->checkExist($cart, __('messages.not_found', [
            'name' => 'cart'
        ]));

        $this->cartRepository->destroy($cart->id);

        return $this->successResponse(message: __('messages.deleted', [
            'name' => 'cart'
        ]));
    }
}
