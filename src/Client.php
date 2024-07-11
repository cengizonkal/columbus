<?php

namespace Conkal\Columbus;

use Conkal\Columbus\Requests\RegisterRequest;

class Client
{
    private $soapClient;

    public function register(RegisterRequest $register)
    {
        $this->soapClient = new \SoapClient($register->wsdl);
        $this->soapClient->register($register);
    }
}
