<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Exceptions\NotFoundException;
use App\Exceptions\InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class MainService
{
    /**
     * Store file to disk.
     * Return ulr file.
     * 
     * @param mixed $file
     * @param string $folderName
     * @return string
     */
    public function storeFile(mixed $file, string $folderName = 'images')
    {
        return Storage::url($file->store($folderName, 'public'));
    }

    /**
     *  if not be exist return error.
     *
     * @param object|null
     * @return App\Exceptions\NotFoundException|true
     */
    public function checkExist($object = null, $message = 'not found')
    {
        if(!$object) {
            throw new NotFoundException($message); 
        }

        return  true;
    }

    /**
     *  if be exist return exception Not Found.
     *
     * @param bool|null
     * @param string|null
     * @return App\Exceptions\NotFoundException|true
     */
    public function checkNotExist(bool $value = null,string $message = 'not found')
    {
        if($value) {
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
    public function validate(array $data, array $rule)
    {
        $validator = Validator::make($data, $rule);

        if($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return true;
    }

    /**
     * Create token data.
     *
     * @param  int 
     * @return string
     */
    public function createToken($tokenLength)
    {
        return Str::random($tokenLength);
    }

     /**
     * Make hash value.
     *
     * @param  string  
     * @return string
     */
    public function makeHash($value)
    {
        return Hash::make($value);
    }
}