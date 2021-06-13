<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Contracts\Repositories\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService,
        private CategoryRepositoryInterface $categoryRepository,
    ){}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->categoryRepository->all();

        return $this->successResponse($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->categoryService->validateStore($request->all());

        $newCategory = $this->categoryRepository->create($request->all());

        return $this->successResponse($newCategory, __('messages.stored', [
            'name' => 'category'
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
        $category = $this->categoryRepository->find($id);

        // Check exist category.
        $this->categoryService->checkExist($category, __('messages.not_found', [
            'name' => 'category'
        ]));

        return $this->successResponse($category);
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
        $category = $this->categoryRepository->find($id);
        
        // Check exist category.
        $this->categoryService->checkExist($category, __('messages.not_found', [
            'name' => 'category'
        ]));
        
        $this->categoryService->validateUpdate($request->all(), $id);

        $this->categoryRepository->update($request->all(), $category);

        return $this->successResponse(message: __('messages.updated', [
            'name' => 'category'
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
        $category = $this->categoryRepository->find($id);

        // Check exist category.
        $this->categoryService->checkExist($category, __('messages.not_found', [
            'name' => 'category'
        ]));

        $this->categoryRepository->destroy($category->id);

        return $this->successResponse(message: __('messages.deleted', [
            'name' => 'category'
        ]));
    }
}
