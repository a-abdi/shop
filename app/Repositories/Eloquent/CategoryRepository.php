<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Contracts\Repositories\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(private Category $category) 
    {
        parent::__construct($category);
    }
}
