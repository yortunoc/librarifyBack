<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiTest extends WebTestCase
{

    public function testInvalidCredentials()
    {
        $client = static::createClient();
        $client->request('GET', '/api/login_check',
            [], [], ['CONTENT_TYPE' => 'application/json'],
            '{
                        "username": "invalid_user",
                        "password": "123"
                    }');
        $this->getMockBuilder(Response::class);
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }


    public function testValidCredentials()
    {
        $client = static::createClient();
        $client->request('GET', '/api/login_check',
            [], [], ['CONTENT_TYPE' => 'application/json'],
            '{
                        "username": "lluvia@gmail.com",
                        "password": "123"
                    }');
        $this->getMockBuilder(Response::class);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}