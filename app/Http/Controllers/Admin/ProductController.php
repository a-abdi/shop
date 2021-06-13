<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ProductService;
use App\Contracts\Repositories\ProductRepositoryInterface;

class ProductController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private Product $product,
        private ProductService $productService,
    ){}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->productRepository->all();

        return $this->successResponse($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // check validate data
        $this->productService->validate_store($request->all());

        // store image in disk
        $image_url = $this->productService->store_file($request->image);

        // create product data for save in database
        $product_data = $this->product->data($request->except('image'), $image_url);
        
        // save product in database
        $new_product = $this->productRepository->create($product_data);

        // Create http address for image.
        $new_product['image_src'] = asset($new_product['image_src']);

        // return new product 
        return $this->successResponse($new_product, __('messages.stored', [
            'name' => 'product'
            ]) ,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->productRepository->find($id);

        // Check exist product.
        $this->productService->check_exist($product);

        // Create http address for image.
        $product['image_src'] = asset($product['image_src']);

        return $this->successResponse($product);
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
        $this->productService->validate_update($request->all());

        $product = $this->productRepository->find($id);

        // Check exist product.
        $this->productService->check_exist($product);

        $this->productRepository->update($request->except('image'), $product);

        return $this->successResponse(message: __('messages.updated', [
            'name' => 'product'
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
        $product = $this->productRepository->find($id);

        // Check exist product.
        $this->productService->check_exist($product);

        $this->productRepository->destroy($product->id);

        return $this->successResponse(message: __('messages.deleted', [
            'name' => 'product'
        ]));
    }
}
