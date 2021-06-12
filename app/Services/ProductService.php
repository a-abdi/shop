<?php

namespace App\Services;

use Exception;
use App\Services\MainService;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\NotFoundException;

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

    /**
     * Validate update product.
     *
     * @param array
     * @return App\Exceptions\NotFoundException|true
     */
    public function validate_update($data)
    {
        // base64_decode ( string $string , bool $strict = false ) : string|false

        $validator = Validator::make($data, [
            'name'        => 'nullable|string|min:3|max:64',
            'price'       => 'nullable|numeric|min:1|max:9999999999',
            'discount'    => 'nullable|numeric|min:0|max:9999999999',
            'quantity'    => 'nullable|numeric|min:1|max:9999999999',
            'description' => 'nullable|string|min:10',
            // 'image'       => 'nullable|file|image',
        ]);
        
        if($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return true;
    }
}