<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 16/09/2018
 * Time: 10:49
 */

namespace AppBundle\Services\Cart;


use AppBundle\Entity\Order;
use AppBundle\Entity\Ticket;
use AppBundle\Services\Checkout\Checkout;
use AppBundle\Services\PriceCalculator\PriceCalculator;
use AppBundle\Services\SendEmail\SendEmail;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Cart
{
    const SESSION_ORDER_KEY = "order";
    const ORDER_ID_LENGTH = "15";
    const ORDER_ID_CHARS = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";


    private $session;
    private $priceCalculator;
    private $checkout;
    private $em;
    private $sendEmail;


    public function __construct(SessionInterface $session, PriceCalculator $priceCalculator, Checkout $checkout, EntityManagerInterface $em, SendEmail $sendEmail)
    {
        $this->session = $session;
        $this->priceCalculator = $priceCalculator;
        $this->checkout = $checkout;
        $this->em = $em;
        $this->sendEmail = $sendEmail;
    }

    public function addTicket(Ticket $ticket)
    {

        if (!$this->fullCart()) {
            $this->priceCalculator->computeTicketPrice($ticket);
            $this->getOrder()->addTicket($ticket);
            $this->priceCalculator->computeTotalPrice($this->getOrder());
        }

    }


    /**
     *
     *
     * Return order from session
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function getOrder()
    {

        if ($this->session->has(self::SESSION_ORDER_KEY)) {
            return $this->session->get(self::SESSION_ORDER_KEY);
        } else {
            throw new NotFoundHttpException();
        }

    }

    public function deleteCart()
    {
        if ($this->session->has(self::SESSION_ORDER_KEY)) {
            $this->session->remove(self::SESSION_ORDER_KEY);
        }

    }

    public function setOrder(Order $order)
    {
        $this->session->set(self::SESSION_ORDER_KEY, $order);
    }

    public function generateIdOrder()
    {
        $clen = strlen(self::ORDER_ID_CHARS) - 1;
        $id = '';

        for ($i = 0; $i < self::ORDER_ID_LENGTH; $i++) {
            $id .= self::ORDER_ID_CHARS[mt_rand(0, $clen)];
        }

        return $id;
    }

    public function fullCart()
    {
        $nbrTickets = $this->getOrder()->getNbrTickets();
        if (count($this->getOrder()->getTickets()) < $nbrTickets) {
            return false;
        }
        return true;
    }

    /**
     * Genere un nouveau ticket associé à la commande n cours
     *
     * @return Ticket
     */
    public function generateTicket()
    {
        $ticket = new Ticket();
        return $ticket->setOrderTickets($this->getOrder());

    }

    /**
     * @param $token
     * @param $description
     * @return bool
     */
    public function payment()
    {
        $order = $this->getOrder();
        $totalPrice = $order->getTotalPrice();
        $orderId = $order->getIdOrder();

        if($this->checkout->charge($orderId, $totalPrice, "Billetterie du Louvre"))
        {
            $order->setIdOrder($this->generateIdOrder());

            $this->em->persist($order);
            $this->em->flush();

            $this->sendEmail->sendTicket($order);
            $this->deleteCart();

            return true;
        }


        return false;
    }

}