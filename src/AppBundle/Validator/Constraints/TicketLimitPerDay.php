<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 19/09/2018
 * Time: 11:15
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
/**
 * @Annotation
 */
class TicketLimitPerDay extends Constraint
{

    public $message = '{{ ticketForSale }} billet(s) disponible(s) pour cette date';
    public $limit;

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}