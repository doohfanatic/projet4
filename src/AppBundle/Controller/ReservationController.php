<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Billet;
use AppBundle\Entity\Reservation;
use AppBundle\Entity\Statistique;
use AppBundle\Entity\User;
use AppBundle\Utils\RandomStringGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class ReservationController extends Controller
{

    /**
     * @Route("/", name="home_page")
     */
    public function indexAction()
    {
        return $this->render(':Home:index.html.twig');
    }

    /**
     * @Route("/paiement", name="pay_reservation")
     */
    public function getInfoAction(Request $request)
    {
        $stripe_manager = $this->get('reservation_service');
        $data_final = $this->get('session')->get('data_billet');
        $validator = $this->get('validator');

        if($request->getMethod() === 'POST'){
            $data = $request->request->all();
            $data_final['stripe_info'] = $data;

            $user = new User();
            $user->setNom($data['nomclient']);
            $user->setEmail($data['emailclient']);
            $error_type = $this->getErrorForm($validator->validate($user));
            $old_values = $data;

            if(count($error_type) > 0){
                return $this->render(':Reservation:stripe-form.html.twig',['errors'=>$error_type,'old_values'=>$old_values]);
            }else{
                $data_final['stripe_info'] = $data;

              /*  $customer = $stripe_manager->getStripeInfo('customers', [
                    'source' => $data['stripeToken'],
                    'description' => $data['nomclient'],
                    'email' => $data['emailclient']
                ]);

                $stripe_manager->getStripeInfo('charges', [
                    'amount' => $data_final['montant_total'] * 100,
                    'currency' => 'eur',
                    'customer' => $customer->id
                ]);*/
            }

            $this->addReservation($data_final);

            $request->getSession()->getFlashBag()
                ->add('success', 'Réservation effectué avec succès, merci de votre confiance');

            return $this->redirectToRoute('home_page');
        }

        return $this->render(':Reservation:stripe-form.html.twig', array(
            'old_values' => [], 'errors' => []
        ));
    }

    /**
     * fonction pour enregistrer la reservation dans bdd
     */
    protected function addReservation($data_recup)
    {

        $mail_manager = $this->get('reservation_service');
        $em = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setEmail($data_recup['stripe_info']['emailclient']);
        $user->setNom($data_recup['stripe_info']['nomclient']);

        $em->persist($user);

        for ($i = 1; $i <= $data_recup['nb_billet']; $i++) {
            // On récupère le tarrif par $libelle
            $tarrif_id = $em->getRepository('AppBundle:Tarrif')
                ->getTarrifIdByLibelle($data_recup["motif$i"]);

            $tarrif = $em->getRepository('AppBundle:Tarrif')
                ->find($tarrif_id['id']);

            $billet = new Billet();
            // On crée l'instance de la classe qui va generer la code de reservation
            $generator = new RandomStringGenerator();

            // Appel à la methode pour génerer
            $code_de_reservation = $generator->generate(20);

            $billet->setCodeDeReservation($code_de_reservation);
            $billet->setTarrifs($tarrif);
            $billet->setTypeBillet($data_recup['type_billet']);
            $billet->setNom($data_recup["nom$i"]);
            $billet->setPays($data_recup["pays$i"]);
            $billet->setPrenom($data_recup["prenom$i"]);

            $date = new \DateTime($data_recup["datenaissance$i"]);
            $billet->setDateDeNaissance($date);
            $reservation = new Reservation();
            $reservation->setJour(new \DateTime($data_recup['date_reservation_formated']));

            $reservation->setBillets($billet);
            $reservation->setUser($user);

            $em->persist($billet);
            $em->persist($reservation);
            $em->flush();

        }

        //On recupère les billets reserve par le visiteur
        $billets = $em->getRepository('AppBundle:Reservation')
            ->getBilletByUserId($user->getId());

        //Envoi par email des billets
        $mail_manager->sendBillet($user->getEmail(), $billets);

        //Mise à jour on ajout Statistique
        $this->addOrUpdateStatistiqueByDate($data_recup['date_reservation_formated'], $data_recup['nb_billet']);

        return $user;
    }

    /**
     * fonction pour mettre à jour la table statistiques
     */
    protected function  addOrUpdateStatistiqueByDate($date, $nbrBilletAcheter)
    {
        $em = $this->getDoctrine()->getManager();
        //On verifie d'abord si la date existe
        $statistique = $em->getRepository('AppBundle:Statistique')
            ->verifieDisponibiliteByDate(new \DateTime($date));
        //Si la date existe on fait la mise jour de nombre de billet
        if ($statistique) {
            $totalBillet = $statistique[0]['nbBilletVendu'] + $nbrBilletAcheter;
            return $updateStat = $em->getRepository('AppBundle:Statistique')
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

    /**
     * fonction pour recupere l'erreur du formulaire
     */
    protected function getErrorForm($errors){
        $error_validation = [];
        foreach ($errors as $error){
            $error_validation[$error->getPropertyPath()] = $error->getMessage();
        }
        return $error_validation;
    }
}
