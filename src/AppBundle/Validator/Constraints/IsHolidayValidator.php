<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 19/09/2018
 * Time: 15:36
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsHolidayValidator extends ConstraintValidator
{

    /**
     * @param $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        // Jour fériés
        $year = $value->format('Y');

        if ($year <= 2037)
        {
            $easterDate = easter_date($year);
            $easterDay = date('j', $easterDate);
            $easterMonth = date('n', $easterDate);
            $easterYear = date('Y', $easterDate);

            $publicHolidays = array(
                // Jours feries fixes
                mktime(0, 0, 0, 1, 1, $year),// 1er janvier
                mktime(0, 0, 0, 5, 1, $year),// Fete du travail
                mktime(0, 0, 0, 5, 8, $year),// Victoire des allies
                mktime(0, 0, 0, 7, 14, $year),// Fete nationale
                mktime(0, 0, 0, 8, 15, $year),// Assomption
                mktime(0, 0, 0, 11, 1, $year),// Toussaint
                mktime(0, 0, 0, 11, 11, $year),// Armistice
                mktime(0, 0, 0, 12, 25, $year),// Noel

                // Jour feries qui dependent de paques
                mktime(0, 0, 0, $easterMonth, $easterDay + 1, $easterYear),// Lundi de paques
                mktime(0, 0, 0, $easterMonth, $easterDay + 40, $easterYear),// Ascension
                mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear), // Pentecote
            );

            //Date de commande
            $orderDate = $value->getTimestamp();

            if (in_array($orderDate, $publicHolidays))
            {
                $this->context->buildViolation($constraint->message)
                    ->atPath('visiteDay')
                    ->addViolation();
            }


        }
         else
        {
            $this->context->buildViolation($constraint->message)
                ->atPath('visiteDay')
                ->addViolation();
        }


    }

}