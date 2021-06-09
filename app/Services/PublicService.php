<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;

class PublicService
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
}