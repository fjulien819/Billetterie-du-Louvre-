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
            $nbrTicket =  count($repo->getNbrTickets($value->getVisiteDay()));

            //si limit 0 ticketforSale = negatif
            if ($constraint->limit === 0)
            {
                $ticketForSale = 0;
            }
            else
            {
                $ticketForSale = $constraint->limit - $nbrTicket ;
            }


            $nbrTicket += $value->getNbrTickets();

            if ($nbrTicket > $constraint->limit)
            {

                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ ticketForSale }}', $ticketForSale)
                    ->atPath('nbrTickets')
                    ->addViolation();
            }



        }


    }


}