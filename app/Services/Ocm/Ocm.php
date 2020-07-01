<?php

namespace App\Services\Ocm;

class Ocm
{
    protected $url;

    protected $token;

    public function __construct($url, $token)
    {
        $this->url = $url;
        $this->token = $token;
    }

    public function lead()
    {
        return new Lead($this->url, $this->token);
    }
}