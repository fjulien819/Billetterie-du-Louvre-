<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 25/09/2018
 * Time: 11:57
 */

namespace AppBundle\Validator\Constraints;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsSunday extends Constraint
{
    public $message = "Réservation impossible pour ce jour";
}