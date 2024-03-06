<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    private static $client;
    private static $bearerToken;
    public static function setUpBeforeClass(): void
    {
        self::$client = static::createClient();
        self::$client->request('GET', '/api/login_check',
            [], [], ['CONTENT_TYPE' => 'application/json'],
            '{
                        "username": "lluvia@gmail.com",
                        "password": "123"
                    }');
        $data = json_decode(self::$client->getResponse()->getContent(), true);
        self::$bearerToken = $data['token'];
    }

    public function testAuthenticateBooks()
    {
        self::$client->request('GET', '/api/books', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . self::$bearerToken,
        ]);
        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());
    }

    public function testCreateBook()
    {
        self::$client->request('POST', '/api/books',
            [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer ' . self::$bearerToken],
            '{"title": "creacion de book con nuevo servicio upload"}');
        $this->assertEquals(201, self::$client->getResponse()->getStatusCode());
    }

    public function testCreateBookEmptyData()
    {
        self::$client->request('POST', '/api/books',
            [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTHORIZATION' => 'Bearer ' . self::$bearerToken],'');
        $this->assertEquals(400, self::$client->getResponse()->getStatusCode());
    }
}