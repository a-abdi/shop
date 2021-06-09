<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\PublicService;
use App\Contracts\Repositories\ProductRepositoryInterface;

class ProductController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private Product $product,
        private ProductService $productService,
        private PublicService $publicService,
    ){}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'products' => $products
        ], 200);
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
        $image_url = $this->publicService->store_file($request->image);

        // create product data for save in database
        $product_data = $this->product->data($request->except('image'), $image_url);
        
        // save product in database
        $new_product = $this->productRepository->create($product_data);

        // return new product 
        return $this->successResponse($new_product, 201);
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
        return response()->json([
            'product' => $product
        ], 200);
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
        //
    }
}
