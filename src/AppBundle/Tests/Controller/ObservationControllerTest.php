<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ObservationControllerTest extends WebTestCase
{
    public function testSaveobservation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/saveObservation');
    }

}
