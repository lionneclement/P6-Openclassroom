<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnonyTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testAnonymousResponse($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider urlProvider
     */
    public function testUserResponse($url)
    {
        $client = self::createClient();
        $client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW'   => 'password',
            ]
        );
        $this->assertNotEquals(200, $client->getResponse()->getStatusCode());
    }
    /**
     * @dataProvider urlProvider
     */
    public function testAdminResponse($url)
    {
        $client = self::createClient();
        $client->request(
            'GET', $url, [], [], [
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => 'password',
            ]
        );
        $this->assertNotEquals(200, $client->getResponse()->getStatusCode());
    }

    public function urlProvider()
    {
        yield ['/anony/password/forgot'];
        yield ['/anony/register'];
        yield ['/anony/login'];
    }
}
