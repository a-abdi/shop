<?php

namespace App\Services;

use App\Services\MainService;
use Illuminate\Support\Facades\Http;

class HttpService extends MainService
{
    /**
     * send data to url.
     *
     * @param array
     * @param string
     * @param array
     * @return object
     */
    public function post(array $header, string $url, array $data)
    {
        return Http::withHeaders($header)->post($url, $data);
    }

  
}