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

    /**
     * une fonction qui permet de tester si la page d'acceuil marche parfaitement sans erreur
     *
     */
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Détails', $crawler->filter('.etape1')->text());
    }

    /**
     * une fonction qui nous permet de verifier si dans le contenu div (Renseignements) de l'etape 2 contien une classe .etape2
     */
    public function testBillet()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Renseignements', $crawler->filter('.etape2')->text());
    }

    /**
     * Une fonction qui permet de verifier la requête de verification de date de disponibilité
     */

    public function testVerificationDisponibilite()
    {
        $client = static::createClient();
        $client->request('GET', '/verif-date-reservation?date=27-02-2018');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * une fonction qui permet de verifier si la verification du statistique fonctionne à la perfection
     */
    public function testFonctionVerificationStatistique()
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