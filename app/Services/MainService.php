<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\NotFoundException;

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
    public function check_exist($object = null, $message = "not found")
    {
        if(!$object) {
            throw new NotFoundException($message); 
        }

        return  true;
    }
}