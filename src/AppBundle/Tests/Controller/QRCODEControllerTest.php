<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QRCODEControllerTest extends WebTestCase
{
    public function testGetqr()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getQR');
    }

}
