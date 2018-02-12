<?php

namespace ReservationBundle\Controller;

use ReservationBundle\Entity\Billet;
use ReservationBundle\Entity\Reservation;
use ReservationBundle\Entity\Statistique;
use ReservationBundle\Entity\User;
use ReservationBundle\Utils\RandomStringGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Reservation controller.
 *
 */
class ReservationController extends Controller
{
    /**
     * @param $endpoint
     * @param $data
     * @return mixed
     */
    protected function getStripeInfo($endpoint, $data)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api.stripe.com/v1/$endpoint",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => $this->container->getParameter('stripe_private_key'),
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_POSTFIELDS => http_build_query($data)
        ]);
        $response = json_decode(curl_exec($ch));
        curl_close($ch);
        if (property_exists($response, 'error')) {
            throw new Exception($response->error->message);
        }
        return $response;
    }

    /**
     * send mail pour envoyer les billets au visiteur
     */
    protected function sendBillet($email, $billets)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Biller de reservation')
            ->setFrom(['envoimailtest06@gmail.com' => 'ReservationMuseum'])
            ->setTo($email)
            ->setCharset('utf-8')
            ->setContentType('text/html')
            ->setBody($this->renderView('ReservationBundle:billet:template-billet.html.twig', ['billets' => $billets]));
        return $this->get('mailer')->send($message);

    }

    /**
     * qui affiche la première page d'acceuil du site
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tarrifs = $em
            ->getRepository('ReservationBundle:Tarrif')
            ->findAll();

        return $this->render('ReservationBundle:SinglePage:index.html.twig', ['tarrifs' => $tarrifs]);
    }

    /**
     * une fonction qui permet de sauvegarde la reservation dans la base de données
     * @param $data
     * @return Response
     */
    public function addReservation($data)
    {
        $em = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setEmail($data['emailclient']);
        $user->setNom($data['nomclient']);

        $em->persist($user);
        $em->flush();

        for ($i = 1; $i <= $data['nb_billet']; $i++) {
            // On récupère le tarrif par $libelle
            $tarrif_id = $em->getRepository('ReservationBundle:Tarrif')
                ->getTarrifIdByLibelle($data["motif$i"]);
            $tarrif = $em->getRepository('ReservationBundle:Tarrif')
                ->find($tarrif_id[0]['id']);

            $billet = new Billet();
            // On crée l'instance de la classe qui va generer la code de reservation
            $generator = new RandomStringGenerator;

            // Appel à la methode pour génerer
            $code_de_reservation = $generator->generate(20);

            $billet->setCodeDeReservation($code_de_reservation);
            $billet->setTarrifs($tarrif);
            $billet->setTypeBillet($data['type_billet']);
            $billet->setNom($data["nom$i"]);
            $billet->setPays($data["pays$i"]);
            $billet->setPrenom($data["prenom$i"]);

            $date = new \DateTime($data["datenaissance$i"]);
            $billet->setDateDeNaissance($date);
            $reservation = new Reservation();
            $reservation->setJour(new \DateTime($data['date_reservation_formated']));

            $reservation->setBillets($billet);
            $reservation->setUser($user);

            $em->persist($billet);
            $em->persist($reservation);
            $em->flush();

        }
        /**
         * On recupère les billets reserve par le visiteur
         */
        $billets = $em->getRepository('ReservationBundle:Reservation')
            ->getBilletByUserId($user->getId());

        //Envoi par email des billets
        $this->sendBillet($user->getEmail(), $billets);

        //Mise à jour on ajout Statistique
        $this->addOrUpdateStatistiqueByDate($data['date_reservation_formated'], $data['nb_billet']);

        return new Response(
            '<html><body>Save suceess</body></html>'
        );

    }

    /**
     * Recuperation des informations
     * @param Request $request
     */
    public function getInfoAction(Request $request)
    {
        $data = $request->request->all();

        $customer = $this->getStripeInfo('customers', [
            'source' => $data['stripeToken'],
            'description' => $data['nomclient'],
            'email' => $data['emailclient']
        ]);

        $this->getStripeInfo('charges', [
            'amount' => $data['montant_total'] * 100,
            'currency' => 'eur',
            'customer' => $customer->id
        ]);

        $this->addReservation($data);

        $request->getSession()->getFlashBag()
            ->add('success', 'Réservation effectué avec succès, merci de votre confiance');
        $url = $this->generateUrl('home_page');

        return $this->redirect($url);
    }

    /**
     * Fontion pour verifier la disponibilite de la date de visite selectionnez par le visiteur
     * @param Request $request
     * @return JsonResponse
     */
    public function verificationBilletAction(Request $request)
    {
        $data = $request->query->all();

        $em = $this->getDoctrine()->getManager();
        $nombre_billet_max = $this->container->getParameter('nombre_billet_max');

        $statistique = $em
            ->getRepository('ReservationBundle:Statistique')
            ->verifieDisponibiliteByDate(new \DateTime($data['date']));
        if ($statistique) {
            if ($statistique[0]['nbBilletVendu'] > $nombre_billet_max) {
                $response = array(
                    'success' => true,
                    'content' => array(
                        'message' => 'date non disponible'
                    )
                );
            } else {
                $response = array(
                    'success' => true,
                    'content' => array(
                        'message' => 'disponible'
                    )
                );
            }
        } else {
            $response = array(
                'success' => true,
                'content' => array(
                    'message' => 'disponible'
                )
            );
        }

        return new JsonResponse($response);
    }

    public function  addOrUpdateStatistiqueByDate($date, $nbrBilletAcheter)
    {
        $em = $this->getDoctrine()->getManager();
        //On verifie d'abord si la date existe
        $statistique = $em->getRepository('ReservationBundle:Statistique')
            ->verifieDisponibiliteByDate(new \DateTime($date));
        //Si la date existe on fait la mise jour de nombre de billet
        if ($statistique) {
            $totalBillet = $statistique[0]['nbBilletVendu'] + $nbrBilletAcheter;
            return $updateStat = $em->getRepository('ReservationBundle:Statistique')
                ->updateStatByDate(new \DateTime($date), $totalBillet);
        } //Sinon on Stock la date dans stat
        else {
            $doctrinemanager = $this->getDoctrine()->getManager();
            $statisque = new Statistique();
            $statisque->setDate(new \DateTime($date));
            $statisque->setNbBilletVendu($nbrBilletAcheter);
            $doctrinemanager->persist($statisque);
            $doctrinemanager->flush();
            return $statisque;
        }
    }
}
