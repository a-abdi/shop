<?php

namespace App\Services;

use App\Services\MainService;

class CategoryService extends MainService
{
    /**
     * Validate store category.
     *
     * @param array
     * @return App\Exceptions\InvalidArgumentException|true
     */
    public function validateStore($data)
    {
        $rule = [
            'name'        => 'required|string|unique:categories|min:3|max:255',
            'description' => 'required|string|min:10',
        ];

        $this->validate($data, $rule);

        return true;
    }

    /**
     * Validate update category.
     *
     * @param array
     * @return App\Exceptions\InvalidArgumentException|true
     */
    public function validateUpdate($data, $id)
    {
        $rule = [
            'name'        => 'nullable|string|unique:categories,name,'. $id .'|min:3|max:255',
            'description' => 'nullable|string|min:10',
        ];

        $this->validate($data, $rule);

        return true;
    }
}