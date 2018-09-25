<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 25/09/2018
 * Time: 12:00
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsTuesdayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value->format('D') === "Tue") {
            $this->context->buildViolation($constraint->message)
                ->atPath('visiteDay')
                ->addViolation();
        }
    }
}