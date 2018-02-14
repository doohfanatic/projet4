<?php

namespace ReservationBundle\Tests\Controller;

use PHPUnit\Framework\TestCase;
use ReservationBundle\Controller\ReservationController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ReservationBundle\Entity\Statistique;


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
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Renseignements', $crawler->filter('.etape2')->text());
    }

    public function testVerificationDisponibilite()
    {
        $client = static::createClient();
        $client->request('GET', '/verif-date-reservation?date=27-02-2018');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAjoutNouvelleTarrif()
    {

        $client = static::createClient();
        $container = $client->getContainer();

        $em = $container->get('doctrine')->getManager();
        $statistique = $em
            ->getRepository('ReservationBundle:Statistique')
            ->verifieDisponibiliteByDate(new \DateTime());

        $reservation = new ReservationController();
        $result = $reservation->verificationStatistique($statistique, 1000);

        $response = array(
            'success' => true,
            'content' => array(
                'message' => 'disponible'
            )
        );
        $this->assertEquals(new JsonResponse($response), $result);

    }


}