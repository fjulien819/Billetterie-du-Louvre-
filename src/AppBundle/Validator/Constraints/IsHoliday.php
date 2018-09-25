<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 19/09/2018
 * Time: 15:35
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
/**
 * @Annotation
 */
class IsHoliday extends Constraint
{
    public $message = "Réservation impossible pour cette date";
}