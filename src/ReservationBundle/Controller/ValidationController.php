<?php

namespace ReservationBundle\Controller;

use ReservationBundle\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Validation controller.
 *
 */
class ValidationController extends Controller
{
    /**
     * @Route("/validation-etape-un", name="step_one")
     */
    public function indexAction(Request $request)
    {
        $validator = $this->get('validator');
        $data = $request->request->all();
        $reservation = new Reservation();
        $reservation->setJour($data['date_reservation_formated']);
        $reservation->setBillets('');
        $errors = $validator->validate($reservation);
        if(count($errors ) > 0){
            $response = [
                'status' => false,
                'errors' => $this->getErrors($errors)
            ];
        }else{
            $response = [
                'status' => true
            ];
        }

        return new JsonResponse($response);
    }

    protected function getErrors($errors){
        $error_validation = [];
        foreach ($errors as $error){
            $error_validation[$error->getPropertyPath()] = $error->getMessage();
        }
        return $error_validation;
    }
}
