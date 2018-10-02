<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 16/09/2018
 * Time: 10:49
 */

namespace AppBundle\Services\Cart;


use AppBundle\Entity\Ticket;
use AppBundle\Services\Checkout\Checkout;
use AppBundle\Services\PriceCalculator\PriceCalculator;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Cart
{
    const SESSION_ORDER_KEY = "order";


    private $session;
    private $priceCalculator;
    private $checkout;


    public function __construct(SessionInterface $session, PriceCalculator $priceCalculator, Checkout $checkout, $email)
    {
        $this->session = $session;
        $this->priceCalculator = $priceCalculator;
        $this->checkout = $checkout;
    }

    public function addTicket(Ticket $ticket)
    {

        if (!$this->fullCart())
        {
            $ticket->setPrice($this->priceCalculator->getTicketPrice($ticket));
            $this->getOrder()->addTicket($ticket);

        }
        else
        {
            dump("max de tickets");
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

        if ($this->session->has(self::SESSION_ORDER_KEY))
        {
            return $this->session->get(self::SESSION_ORDER_KEY);
        }else{
            throw new NotFoundHttpException();
        }
    }

    public function deleteCart()
    {
        if ($this->session->has(self::SESSION_ORDER_KEY))
        {
            $this->session->remove(self::SESSION_ORDER_KEY);
        }

    }

    public function setOrder($order)
    {
        $this->session->set(self::SESSION_ORDER_KEY, $order);
    }

    public function fullCart()
    {
        $nbrTickets = $this->getOrder()->getNbrTickets();
        if (count($this->getOrder()->getTickets()) < $nbrTickets)
        {
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
    public function payment($token, $description)
    {
        try
        {
            $email = $this->getOrder()->getEmail();
            $totalPrice = $this->getOrder()->getTotalPrice();
            $this->checkout->charge($email, $token, $totalPrice, $description);
            return true;
        }
        catch (\Exception $e)
        {
            $err  = $e->getMessage();
            return $err;
        }

    }
}