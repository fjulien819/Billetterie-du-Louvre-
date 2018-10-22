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

    /**
     * SendEmail constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param Order $order
     * @return int
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendTicket(Order $order)
    {



        $message = (new \Swift_Message(self::SUBJECT_EMAIL))
            ->setFrom(self::FROM_EMAIL)
            ->setTo($order->getEmail());


        $logoWhiteSrc = $message->embed(\Swift_Image::fromPath('img/logo_white_png.png'));
        $logoBlackSrc = $message->embed(\Swift_Image::fromPath('img/logo_black_png.png'));
        $message->setBody($this->twig->render('email/sendTicket.html.twig', array("order" => $order, "logoWhiteSrc" => $logoWhiteSrc, "logoBlackSrc" => $logoBlackSrc)), 'text/html');

        return $this->mailer->send($message);

    }

}