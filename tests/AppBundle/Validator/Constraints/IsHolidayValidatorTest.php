<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 28/10/2018
 * Time: 17:04
 */

namespace Tests\AppBundle\Validator\Constraints;


use AppBundle\Validator\Constraints\IsHoliday;
use AppBundle\Validator\Constraints\IsHolidayValidator;


class IsHolidayValidatorTest extends ValidatorTestAbstract
{

    protected function getValidatorInstance()
    {
        return new IsHolidayValidator();
    }

    /**
     *@dataProvider dateForTestOk
     */
    public function testValidationOk($date)
    {
        $constraint = new IsHoliday();
        $validator = $this->initValidator();

        $date = new \DateTime($date);
        $validator->validate($date, $constraint);

    }

    /**
     *@dataProvider dateForTestKo
     */
    public function testValidationKo($date)
    {
        $constraint = new IsHoliday();
        $validator = $this->initValidator($constraint->message);

        $date = new \DateTime($date);
        $validator->validate($date, $constraint);


    }

    public function dateForTestOk()
    {
        return
            [
                ['2018-11-05'],
                ['2016-05-10'],
                ['2020-03-20']

            ]
        ;
    }

    public function dateForTestKo()
    {
        return
            [
                ['2018-01-01'],
                ['2018-05-01'],
                ['2018-05-08'],
                ['2018-07-14'],
                ['2018-08-15'],
                ['2018-11-01'],
                ['2018-11-11'],
                ['2018-12-25'],


                ['2018-04-01'], // Easter 2018
                ['2018-05-10'], // Ascent
                ['2018-05-20']  // Pentecost

            ]
            ;
    }
}