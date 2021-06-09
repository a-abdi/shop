<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidArgumentException;

class ProductService
{
    /**
     * Validate store product.
     *
     * @param array
     * @return true|array
     */
    public function validate_store($data)
    {
        $validator = Validator::make($data, [
            'name'        => 'required|string|min:3|max:64',
            'price'       => 'required|numeric|min:1|max:9999999999',
            'discount'    => 'nullable|numeric|min:0|max:9999999999',
            'quantity'    => 'required|numeric|min:1|max:9999999999',
            'description' => 'required|string|min:10',
            'image'       => 'required|file|image',
        ]);

        if($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return true;
    }
}