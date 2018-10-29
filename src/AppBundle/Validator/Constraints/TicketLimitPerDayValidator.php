<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 19/09/2018
 * Time: 11:17
 */

namespace AppBundle\Validator\Constraints;



use AppBundle\Entity\Ticket;
use AppBundle\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TicketLimitPerDayValidator extends ConstraintValidator
{
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if($value->getVisiteDay() instanceof \DateTime)
        {

            /** @var TicketRepository $repo */
            $repo = $this->em->getRepository(Ticket::class);
            $nbrTicket =  $repo->getNbrTickets($value->getVisiteDay());


            if (($value->getNbrTickets()+ $nbrTicket) > $constraint->limit)
            {
                $ticketForSale = $constraint->limit - $nbrTicket ;
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ ticketForSale }}', $ticketForSale)
                    ->atPath('nbrTickets')
                    ->addViolation();
            }



        }


    }


}