<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Billet;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class VerificationController extends Controller
{
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

    /**
     * fonction pour valider si la date est valide
     */
    protected function verificationStatistique($_date_requested)
    {
        $em = $this->getDoctrine()->getManager();
        $statistique = $em->getRepository('AppBundle:Statistique')
            ->verifieDisponibiliteByDate(new \DateTime($_date_requested));
        $nombre_billet_max = $this->container->getParameter('nombre_billet_max');

        if ($statistique) {
            if ($statistique[0]['nbBilletVendu'] > $nombre_billet_max) return false;
            else return true;
        }

        return true;
    }

    /**
     * @Route("/reserver-billet", name="acheter_billet")
     */
    public function acheterUnBillet(Request $request)
    {
        if($request->getMethod() === 'POST'){
            $validator = $this->get('validator');
            $data = $request->request->all();

            $reservation_billet = new Billet();
            $reservation_billet->setTypeBillet($data['type_billet']);
            $reservation_billet->setDateDeNaissance($data['date_reservation_formated']);
            $reservation_billet->setNom('Museum');
            $reservation_billet->setPrenom('Museum');
            $reservation_billet->setCodeDeReservation('museum123');
            $reservation_billet->setPays('France');

            $error_type = $this->getErrorForm($validator->validate($reservation_billet));
            $old_values = $data;

            if(count($error_type) > 0){
                return $this->render(':Reservation:reservation-form.html.twig',['errors'=>$error_type,'old_values'=>$old_values]);
            }else{
                $this->get('session')->set('data_billet',$data);
                $date_valid = $this->verificationStatistique($data['date_reservation_formated']);
                if($date_valid == true) return $this->redirectToRoute('info_billet');
                else return $this->render(':Reservation:reservation-form.html.twig',['errors'=>['date_de_naissance'=>'Date non disponible veuillez sélectionner un autre date'],'old_values'=>$old_values]);
            }
        }

        return $this->render(':Reservation:reservation-form.html.twig', ['errors' => [],'old_values'=>[]]);
    }

    /**
     * @Route("/info-billet", name="info_billet")
     *
     */
    public function infoBilletAction(Request $request)
    {
        $data = $this->get('session')->get('data_billet');
        if($request->getMethod() === 'POST'){
            $data = $request->request->all();
            $this->get('session')->set('data_billet',$data);

            return $this->redirectToRoute('pay_reservation');
        }
        return $this->render(':Reservation:billet-form.html.twig',['data'=>$data]);
    }

}
