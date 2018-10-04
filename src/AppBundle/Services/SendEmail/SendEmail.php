<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 03/10/2018
 * Time: 10:58
 */

namespace AppBundle\Services\SendEmail;


use AppBundle\Entity\Order;


class SendEmail
{

    const FROM_EMAIL = "billetterie@julienf-oc.fr";
    const SUBJECT_EMAIL = "Voici vos billets !";

    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }
    public function sendTicket(Order $order)
    {
        $message = (new \Swift_Message(self::SUBJECT_EMAIL))
            ->setFrom(self::FROM_EMAIL)
            ->setTo($order->getEmail())
            ->setBody($this->twig->render('email/sendTicket.html.twig', array("order" => $order)),'text/html');
            $this->mailer->send($message);

    }

}