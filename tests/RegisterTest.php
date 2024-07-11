<?php
namespace Tests;

use Conkal\Columbus\Requests\RegisterRequest;
use Conkal\Columbus\Client;


class RegisterTest extends TestCase
{
    public function test_it_should_register()
    {
        $client = new Client();
        $request = new RegisterRequest(getenv('USERNAME'), getenv('PASSWORD'));
        var_dump($client->register($request));
        
    }
}