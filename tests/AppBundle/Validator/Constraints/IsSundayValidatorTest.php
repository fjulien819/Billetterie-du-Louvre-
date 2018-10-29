<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 28/10/2018
 * Time: 17:04
 */

namespace Tests\AppBundle\Validator\Constraints;


use AppBundle\Validator\Constraints\IsSunday;
use AppBundle\Validator\Constraints\IsSundayValidator;


class IsSundayValidatorTest extends ValidatorTestAbstract
{

    protected function getValidatorInstance()
    {
        return new IsSundayValidator();
    }

    /**
     *@dataProvider dateForTestOk
     */
    public function testValidationOk($date)
    {
        $constraint = new IsSunday();
        $validator = $this->initValidator();

        $date = new \DateTime($date);
        $validator->validate($date, $constraint);

    }

    /**
     *@dataProvider dateForTestKo
     */
    public function testValidationKo($date)
    {
        $constraint = new IsSunday();
        $validator = $this->initValidator($constraint->message);

        $date = new \DateTime($date);
        $validator->validate($date, $constraint);


    }

    public function dateForTestOk()
    {
        return
            [
                ['2018-11-05'], // Monday
                ['2018-11-06'], // Thuesday
                ['2018-11-07'], // Wednesday
                ['2018-11-08'], // Thursday
                ['2018-11-09'], // Friday
                ['2018-11-10'], // Saturday

            ]
        ;
    }

    public function dateForTestKo()
    {
        return
            [
                ['2018-11-11']  // Sunday


            ]
            ;
    }
}