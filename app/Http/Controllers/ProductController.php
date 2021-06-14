<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        // private Product $product,
        private ProductService $productService,
        // private CategoryRepositoryInterface $categoryRepository,
    ){}
    
    /**
     * Return all pruduct.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return object
     */
    public function index(Request $request) 
    {
        $products = $this->productRepository->all();

        return $this->successResponse($products);
    }

    /**
    * Return one pruduct.
    *
    * @param  string
    * @return object
    */
    public function show($id) 
    {
        $product = $this->productRepository->find($id);

        // Check exist product.
        $this->productService->checkExist($product, __('messages.not_found', [
            'name' => 'product'
        ]));

        return $this->successResponse($product);
    }
}
