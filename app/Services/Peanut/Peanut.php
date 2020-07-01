<?php

namespace App\Services\Peanut;

class Peanut
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

    public function injectionLog()
    {
        return new InjectionLog($this->url, $this->token);
    }

    public function pbxCall()
    {
        return new PbxCall($this->url, $this->token);
    }
}