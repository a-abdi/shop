<?php

namespace App\Services;

use App\Services\MainService;

class ProductService extends MainService
{
    /**
     * Validate store product.
     *
     * @param array
     * @return App\Exceptions\InvalidArgumentException|true
     */
    public function validate_store($data)
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
    public function validate_update($data)
    {
        // base64_decode ( string $string , bool $strict = false ) : string|false

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
}