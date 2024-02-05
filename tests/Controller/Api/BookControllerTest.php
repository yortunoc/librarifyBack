<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public function testCreateBook()
    {
        $client = static::createClient();
        $client->request('POST', '/api/books',
            [], [], ['CONTENT_TYPE' => 'application/json'],
            '{"title": "creacion de book con nuevo servicio upload"}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateBookEmptyData()
    {
        $client = static::createClient();
        $client->request('POST', '/api/books',
            [], [], ['CONTENT_TYPE' => 'application/json'],
            '');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}