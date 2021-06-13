<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\NotFoundException;
use App\Exceptions\InvalidArgumentException;
use Illuminate\Support\Facades\Validator;

class MainService
{
    /**
     * Store file to disk.
     * Return ulr file.
     * 
     * @param string
     * @return string
     */
    public function store_file($file)
    {
        return Storage::url($file->store('images', 'public'));
    }

    /**
     *  if not be exist return error.
     *
     * @param object|null
     * @return App\Exceptions\NotFoundException|true
     */
    public function check_exist($object = null, $message = 'not found')
    {
        if(!$object) {
            throw new NotFoundException($message); 
        }

        return  true;
    }

    /**
     * Validate data.
     *
     * @param array $data
     * @param array $rule
     * @return App\Exceptions\InvalidArgumentException|true
     */
    public function validate($data, $rule)
    {
        $validator = Validator::make($data, $rule);

        if($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return true;
    }
}