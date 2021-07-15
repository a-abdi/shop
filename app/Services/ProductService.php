<?php

namespace App\Services;

use App\Services\MainService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductService extends MainService
{
    /**
     * Validate store product.
     *
     * @param array
     * @return App\Exceptions\InvalidArgumentException|true
     */
    public function validateStore($data)
    {
        $rule = [
            'name'        => 'required|string|min:3|max:64',
            'price'       => 'required|numeric|min:1|max:9999999999',
            'discount'    => 'nullable|numeric|min:0|max:9999999999',
            'quantity'    => 'required|numeric|min:1|max:9999999999',
            'image'       => 'required|file|image',
            'description' => 'required|string|min:10',
        ];

        $this->validate($data, $rule);

        return true;
    }

    /**
     * Validate update product.
     *
     * @param array
     * @return App\Exceptions\InvalidArgumentException|true
     */
    public function validateUpdate($data)
    {
        $rule = [
            'name'        => 'nullable|string|min:3|max:64',
            'price'       => 'nullable|numeric|min:1|max:9999999999',
            'discount'    => 'nullable|numeric|min:0|max:9999999999',
            'quantity'    => 'nullable|numeric|min:1|max:9999999999',
            'description' => 'nullable|string|min:10',
            // 'image'       => 'nullable|file|image',
        ];

        $this->validate($data, $rule);

        return true;
    }

    /**
     * Create data for save.
     *
     * @param array  $data
     * @param string $imageSrc
     * @param int    $categoryId
     * @return array
     */
    public function createProductData($data, $imageSrc, $categoryId)
    {

        $productData = array_merge($data, [
                "product_code" => Str::random(8),
                'admin_id'      => Auth::id(),
                'category_id'  => $categoryId,
                "image_src"    => $imageSrc,
            ]);

        return $productData;
    }
}