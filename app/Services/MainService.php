<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\NotFoundException;
use App\Exceptions\InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class MainService
{
    /**
     * Store file to disk.
     * Return ulr file.
     * 
     * @param string
     * @return string
     */
    public function storeFile($file)
    {
        return Storage::url($file->store('images', 'public'));
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