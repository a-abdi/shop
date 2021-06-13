<?php

namespace App\Services;

use App\Services\MainService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


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
            'description' => 'required|string|min:10',
            'image'       => 'required|file|image',
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
     * @param string $image_url
     * @param int    $category_id
     * @return array
     */
    public function createProductData($data, $image_url, $category_id)
    {

        $product_data = array_merge($data, [
                "product_code" => Str::random(8),
                'user_id'      => Auth::id(),
                'category_id'  => $category_id,
                "image_src"    => $image_url,
            ]);

        return $product_data;
    }
}