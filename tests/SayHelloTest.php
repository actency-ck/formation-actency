<?php

namespace App\Tests;

use App\Services\SayHello;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Translation\Translator;

class SayHelloTest extends WebTestCase
{
    private static $translation;

    public function setUp()
    {
        self::bootKernel();

        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();

        // gets the special container that allows fetching private services
        $container = self::$container;
        self::$translation = $container->get('translator');

    }

    public function testSomething()
    {
        self::$translation->setLocale('fr');

        $translator = new Translator('fr');
        $say = new SayHello(self::$translation);
        $this->assertEquals('Symfony is Great !', $say->sayHello());
    }
}
