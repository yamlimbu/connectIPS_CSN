<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.api.base_url');
    }

    public function get($endpoint, $params = [])
    {
        return Http::get($this->baseUrl . $endpoint, $params);
    }

    public function post($endpoint, $data)
    {
        return Http::asForm()->post($this->baseUrl . $endpoint, $data);
    }
}
