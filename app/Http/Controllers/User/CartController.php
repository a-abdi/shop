<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\InvalidArgumentException;
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
       
        $this->cartService->updateCart($userId);

        $cart = $this->cartRepository->getCart($userId);

        return $this->successResponse($cart);
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

        $productExist = $this->cartRepository->checkExistCart(Auth::id(), $request->productId);

        if ($productExist) {
            throw new InvalidArgumentException(__('messages.exist', [
                "name" => "product"
            ]));
        }

        $cartData = $this->cartService->createCartData($product);

        $this->cartRepository->create($cartData);

        $userCart = $this->cartRepository->getCart($cartData['user_id']);

        return $this->successResponse($userCart, __('messages.added', [
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
        $this->cartService->cartOwnerUser($id);

        $cartQuantity = $request->only('quantity');
        
        $this->cartService->validateUpdate($cartQuantity);

        $cart = $this->cartRepository->find($id);
        
        $this->cartService->checkNotExist($cart->order_id);
        
        $this->cartRepository->update($cartQuantity, $cart);

        $cart = $this->cartRepository->getCart(Auth::id());

        return $this->successResponse($cart, __('messages.updated', [
            'name' => 'cart'
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->cartService->cartOwnerUser($id);
        
        $this->cartRepository->destroy($id);
        
        $user = Auth::user();

        $carts = $this->cartRepository->getCart($user->id);

        return $this->successResponse($carts,  __('messages.deleted', [
            'name' => 'cart'
        ]));
    }
}
