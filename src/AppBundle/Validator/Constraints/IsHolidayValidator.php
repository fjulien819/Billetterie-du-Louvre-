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

    public function validate($value, Constraint $constraint)
    {
        // Calcul de la date de Pâques
        $orderYear = $value->format('Y');
        $easterDate = date("d/m", easter_date($orderYear));

        // Jours de fermeture du musée
        $LouvreClosed = array("01/05", "01/11", "25/12", "Tue");

        // Dates à laquelles les billets ne sont pas disponibles
        $publicHolidays = array("01/01", "08/05", "14/01", "15/08", "11/11", $easterDate, "Sun");


        foreach ($LouvreClosed as $day) {
            if (($value->format('D') === $day) OR ($value->format('d/m') === $day)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ string }}', 'Le musée est fermé le 1er mai, le 1er novembre, le 25 décembre ainsi que tous les mardis.')
                    ->atPath('visiteDay')
                    ->addViolation();
            }

        }

        foreach ($publicHolidays as $day) {
            if (($value->format('D') === $day) OR ($value->format('d/m') === $day)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ string }}', 'L\'achat de billets pour les jours fériés, ainsi que les dimanches, n\'est pas disponible.')
                    ->atPath('visiteDay')
                    ->addViolation();
            }

        }
    }

}