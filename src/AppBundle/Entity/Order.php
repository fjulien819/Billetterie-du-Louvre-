<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as LouvreAssert;

/**
 * Order
 *
 * @ORM\Table(name="order_tickets")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderTicketsRepository")
 * @LouvreAssert\CheckTicketType()
 * @LouvreAssert\TicketLimitPerDay(limit=Order::TICKET_lIMIT_PER_DAY)
 */
class Order
{
    const TYPE_FULL_DAY = "journee";
    const TYPE_HALF_DAY = "demiJournee";
    const MIN_TICKETS_COUNT = 1;
    const MAX_TICKETS_COUNT = 10;
    const TICKET_lIMIT_PER_DAY = 1000;


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(name="nbrTickets", type="integer")
     * @Assert\Type(type="integer", message="La valeur {{ value }} n'est pas un nombre entier.")
     * @Assert\Range(
     *      min = Order::MIN_TICKETS_COUNT,
     *      max = Order::MAX_TICKETS_COUNT,
     *      minMessage = "Minimum de billets non atteint",
     *      maxMessage = "Maximum de billets dépassé"
     * )
     *
     */

    private $nbrTickets;

    /**
     * @var string
     * @ORM\Column(name="idOrder", type="string", length=255)
     *
     */
    private $idOrder;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visiteDay", type="datetime")
     * @Assert\Date(message="{{ value }} n'est pas une date valide.")
     * @Assert\GreaterThanOrEqual("today", message="Vous ne pouvez pas réserver pour une date inférieur à celle d'aujourd'hui.")
     * @LouvreAssert\IsSunday()
     * @LouvreAssert\IsTuesday()
     * @LouvreAssert\IsHoliday()
     *
     *
     */
    private $visiteDay;

    /**
     * @var string
     * @ORM\Column(name="ticketType", type="string", length=255)
     * @Assert\Choice({"journee", "demiJournee"})
     *
     *
     */
    private $ticketType;

    /**
     * @var int
     *
     * @ORM\Column(name="totalPrice", type="integer")
     */
    private $totalPrice;

    /**
     *
     *@ORM\OneToMany(targetEntity="Ticket", mappedBy="orderTickets",  cascade={"persist"})
     */
    private $tickets;

    /**
     * @ORM\Column(name="email", type="string", length=255)
     * @var string
     * @Assert\Email
     */
    private $email;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nbrTickets
     *
     * @param integer $nbrTickets
     *
     * @return Order
     */
    public function setNbrTickets($nbrTickets)
    {
        $this->nbrTickets = $nbrTickets;

        return $this;
    }

    /**
     * Get nbrTickets
     *
     * @return int
     */
    public function getNbrTickets()
    {
        return $this->nbrTickets;
    }

    /**
     * Set visiteDay
     *
     * @param \DateTime $visiteDay
     *
     * @return Order
     */
    public function setVisiteDay($visiteDay)
    {
        $this->visiteDay = $visiteDay;

        return $this;
    }

    /**
     * Get visiteDay
     *
     * @return \DateTime
     */
    public function getVisiteDay()
    {
        return $this->visiteDay;
    }

    /**
     * Set ticketType
     *
     * @param string $ticketType
     *
     * @return Order
     */
    public function setTicketType($ticketType)
    {
        $this->ticketType = $ticketType;

        return $this;
    }

    /**
     * Get ticketType
     *
     * @return string
     */
    public function getTicketType()
    {
        return $this->ticketType;
    }

    /**
     * Set totalPrice
     *
     * @param integer $totalPrice
     *
     * @return Order
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return int
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ticket
     *
     * @param Ticket $ticket
     *
     * @return Order
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param Ticket $ticket
     */
    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Set idOrder.
     *
     * @param string $idOrder
     *
     * @return Order
     */
    public function setIdOrder($idOrder)
    {
        $this->idOrder = $idOrder;

        return $this;
    }

    /**
     * Get idOrder.
     *
     * @return string
     */
    public function getIdOrder()
    {
        return $this->idOrder;
    }
}
