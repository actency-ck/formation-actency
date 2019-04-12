<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DemoControllerTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testSomething($url, $code)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        $this->assertSame($code, $client->getResponse()->getStatusCode());
      //  $this->assertContains('Formulaire Task', $crawler->filter('h1')->text());
      //  $client->followRedirect();

    }

    public function urlProvider()
    {
        return [
            ['/add', Response::HTTP_OK],
            ['/en/notFound', Response::HTTP_NOT_FOUND],
        ];
    }

}
