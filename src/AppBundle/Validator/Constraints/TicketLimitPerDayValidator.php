<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 19/09/2018
 * Time: 11:17
 */

namespace AppBundle\Validator\Constraints;



use AppBundle\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TicketLimitPerDayValidator extends ConstraintValidator
{
    private $em;

    const LIMIT_TICKETS_PER_DAY = 0;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function validate($value, Constraint $constraint)
    {
        $em = $this->em->getRepository(Ticket::class);
        $nbrTicket =  count($em->getNbrTickets($value->getVisiteDay()));

        //si limit 0 ticketforSale = negatif
        if (self::LIMIT_TICKETS_PER_DAY === 0)
        {
            $ticketForSale = 0;
        }
        else
        {
            $ticketForSale = self::LIMIT_TICKETS_PER_DAY - $nbrTicket ;
        }


        $nbrTicket += $value->getNbrTickets();

        if ($nbrTicket > self::LIMIT_TICKETS_PER_DAY)
        {

            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ ticketForSale }}', $ticketForSale)
                ->atPath('nbrTickets')
                ->addViolation();
        }



    }


}