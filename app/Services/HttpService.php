<?php

namespace App\Services;

use App\Services\MainService;
use Illuminate\Support\Facades\Http;

class HttpService extends MainService
{
    /**
     * Send data to url.
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

    /**
     * Get base domain fronted to redirect.
     *
     * @return string
     */
    public function baseDomainFrontend()
    {
        return 'http://api.a-abdi.ir:3000';
    }
}