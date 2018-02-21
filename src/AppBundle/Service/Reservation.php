<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Reservation
{
    private $container;
    private $mailer;
    private $templating;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->mailer = $this->container->get('mailer');
        $this->templating = $this->container->get('templating');
    }

    /**
     * @param $endpoint
     * @param $data
     * @return mixed
     */
    public function getStripeInfo($endpoint, $data)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api.stripe.com/v1/$endpoint",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERPWD => $this->container->getParameter('stripe_private_key'),
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_POSTFIELDS => http_build_query($data)
        ]);
        $response = json_decode(curl_exec($ch));

        if (curl_errno($ch)) {
            curl_close($ch);
            throw new \Exception('cannot connect to Stripe API');
        }

        if (property_exists($response, 'error')) {
            throw new \Exception($response->error->message);
        }
        return $response;
    }

    /**
     * send mail pour envoyer les billets au visiteur
     */
    public function sendBillet($email, $billets)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Biller de reservation')
            ->setFrom(['envoimailtest06@gmail.com' => 'ReservationMuseum'])
            ->setTo($email)
            ->setCharset('utf-8')
            ->setContentType('text/html')
            ->setBody($this->templating->render(':billet:template-billet.html.twig', ['billets' => $billets]));
        return $this->mailer->send($message);
    }

}