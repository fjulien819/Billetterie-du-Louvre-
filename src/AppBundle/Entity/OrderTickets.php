<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * OrderTickets
 *
 * @ORM\Table(name="order_tickets")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderTicketsRepository")
 */
class OrderTickets
{
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
     *
     */
    private $nbrTickets;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visiteDay", type="datetime")
     * @Assert\Date()
     * @Assert\GreaterThanOrEqual("today")
     *
     *
     */
    private $visiteDay;

    /**
     * @var string
     * @ORM\Column(name="ticketType", type="string", length=255)
     * @Assert\Choice({"journee", "demiJournee"})
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
     * @return OrderTickets
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
     * @return OrderTickets
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
     * @return OrderTickets
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
     * @return OrderTickets
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


}

