<?php

namespace Conkal\Columbus\Tests;

use Conkal\Columbus\Columbus;
use PHPUnit\Framework\TestCase;

class ColumbusTest extends TestCase
{
    public function test_it_should_register()
    {
        $client = new Columbus(getenv('USER_NAME'), getenv('PASSWORD'),getenv('FROM'));
        $client->register();
        $this->assertNotNull($client->getRegisterId());
    }

    public function test_it_should_send_sms()
    {
        $client = new Columbus(getenv('USER_NAME'), getenv('PASSWORD'),getenv('FROM'));
        $client->register();
        $response = $client->sendSMS(getenv('RECIPIENT'), 'Pastanız Hazır!');
        $this->assertEquals('0', $response['errorCode']);
    }
}