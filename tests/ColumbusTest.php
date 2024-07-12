<?php
namespace Tests;

use Conkal\Columbus\Columbus;


class ColumbusTest extends TestCase
{
    public function test_it_should_register()
    {
        $client = new Columbus(getenv('USER_NAME'), getenv('PASSWORD'));
        $client->register();
        $this->assertNotNull($client->getRegisterId());
        
    }
}