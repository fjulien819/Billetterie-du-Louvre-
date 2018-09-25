<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 18/09/2018
 * Time: 17:53
 */

namespace AppBundle\Validator\Constraints;


use AppBundle\Entity\OrderTickets;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckTicketTypeValidator extends ConstraintValidator
{


    private $currentDate;
    private $limitTime;

    const TIMEZONE = "Europe/Paris";
    const H_LIMIT = 14;


    public function __construct()
    {
        $this->currentDate = new \DateTime();
        $this->limitTime = new \DateTime();
        $timeZone = new \DateTimeZone(self::TIMEZONE);
        $this->currentDate->setTimezone($timeZone);
        $this->limitTime->setTime(self::H_LIMIT,0, 0);

    }

    public function validate($object, Constraint $constraint)
    {

        if ($this->currentDate->format('Y-m-d') === $object->getVisiteDay()->format('Y-m-d'))
        {

            if ($this->currentDate->format('H:i:s') > $this->limitTime->format('H:i:s'))
            {
                if ($object->getTicketType() === OrderTickets::TYPE_FULL_DAY)
                {

                   $this->context->buildViolation($constraint->message)
                       ->setParameter('{{ string }}', self::H_LIMIT ."h")
                        ->atPath('ticketType')
                        ->addViolation();

                }

            }

        }



    }



}