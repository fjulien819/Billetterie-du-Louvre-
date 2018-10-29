<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 28/10/2018
 * Time: 12:02
 */

namespace Tests\AppBundle\Validator\Constraints;


use AppBundle\Entity\Order;
use AppBundle\Entity\Ticket;
use AppBundle\Repository\TicketRepository;
use AppBundle\Validator\Constraints\TicketLimitPerDay;
use AppBundle\Validator\Constraints\TicketLimitPerDayValidator;
use Doctrine\ORM\EntityManagerInterface;


class TicketLimitPerDayValidatorTest extends ValidatorTestAbstract
{
    private $emMock;
    private $repoMock;

    public function setUp()
    {
       $this->emMock = $this
           ->createMock(EntityManagerInterface::class)
       ;

       $this->repoMock = $this
           ->createMock(TicketRepository::class)
       ;
    }

    protected function getValidatorInstance()
    {
        return new TicketLimitPerDayValidator($this->emMock);
    }
    public function testValidationOk()
    {
        $this->repoMock
            ->expects($this->once())
            ->method('getNbrTickets')
            ->willReturn(567);

        $this->emMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->repoMock);

        $constraint = new TicketLimitPerDay(['limit'=> 1000]);
        $validator = $this->initValidator();

        $order = new Order();
        $order->setVisiteDay(new \DateTime('2018-10-29'));
        $order->setNbrTickets(2);

        $validator->validate($order, $constraint);

    }

    public function testValidationKo()
    {
        $this->repoMock
            ->expects($this->once())
            ->method('getNbrTickets')
            ->willReturn(996);

        $this->emMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->repoMock);

        $constraint = new TicketLimitPerDay(['limit'=> 1000]);
        $validator = $this->initValidator($constraint->message);
        $order = new Order();
        $order->setVisiteDay(new \DateTime('2018-10-28'));
        $order->setNbrTickets(5);

        $validator->validate($order, $constraint);

    }
}