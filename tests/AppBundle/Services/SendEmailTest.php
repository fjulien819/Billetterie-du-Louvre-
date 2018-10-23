<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 18/10/2018
 * Time: 15:49
 */

namespace Tests\AppBundle\Services;



use AppBundle\Entity\Order;
use AppBundle\Services\SendEmail\SendEmail;
use PHPUnit\Framework\TestCase;
use Swift_Mailer;
use Twig_Environment;

class SendEmailTest extends TestCase
{
    private $mailer;
    private $twig;

    public function setUp()
    {
        $this->mailer = $this
            ->getMockBuilder(Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->setMethods(['send'])
            ->getMock();

        $this->twig = $this
            ->getMockBuilder(Twig_Environment::class)
            ->disableOriginalConstructor()
            ->setMethods(['render'])
            ->getMock();
    }


    public function testSendTicket()
    {
        $order = new Order();
       $sendEmail = new SendEmail($this->mailer, $this->twig);

       $this->mailer
           ->expects($this->once())
           ->method('send')
           ->willReturn(1);

       $result = $sendEmail->sendTicket($order);

        $this->assertSame(1, $result);

    }

}