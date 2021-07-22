<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;

class ProductController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private ProductService $productService,
        private CategoryRepositoryInterface $categoryRepository,
    ){}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->productRepository->getProducts();

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
        $this->productService->validateStore($request->except('category'));

        // find category bye name
        $category = $this->categoryRepository->where('name', $request->category);

        // check exist category
        $this->productService->checkExist($category, __('messages.not_found', [
            'name' => 'category'
        ]));

        // store image in disk
        $imageSrc = $this->productService->storeFile($request->image);

        // create product data for save in database
        $productData = $this->productService->createProductData($request->except(['image', 'category']), $imageSrc, $category->id);
        
        // save product in database
        $newProduct = $this->productRepository->create($productData);

        // return new product 
        return $this->successResponse($newProduct, __('messages.stored', [
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
        $this->productService->checkExist($product, __('messages.not_found', [
            'name' => 'product'
        ]));

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
        $this->productService->validateUpdate($request->all());

        $product = $this->productRepository->find($id);

        // Check exist product.
        $this->productService->checkExist($product, __('messages.not_found', [
            'name' => 'product'
        ]));

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
        $this->productService->checkExist($product, __('messages.not_found', [
            'name' => 'product'
        ]));

        $this->productRepository->destroy($product->id);

        return $this->successResponse(message: __('messages.deleted', [
            'name' => 'product'
        ]));
    }
}
