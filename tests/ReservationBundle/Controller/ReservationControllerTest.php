<?php

namespace ReservationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('Détails', $crawler->filter('.etape1')->text());


    }

    public function testBillet()
    {
        $client = static::createClient();



        $this->assertContains('Renseignements', $crawler->filter('.etape2')->text());
    }
}
