<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\CartRepositoryInterface;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private CartRepositoryInterface $categoryRepository,
        private CategoryService $categoryService,
    ){}
    
    /**
     * Return all category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return object
     */
    public function index(Request $request) 
    {
        $categories = $this->categoryRepository->all();

        return $this->successResponse($categories);
    }
}
