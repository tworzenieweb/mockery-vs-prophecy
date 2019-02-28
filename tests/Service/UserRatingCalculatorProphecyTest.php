<?php

namespace Jobleads\Service;


use Jobleads\Model\User;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Promise\CallbackPromise;

class UserRatingCalculatorProphecyTest extends TestCase
{
    public function testIncreaseUserRating()
    {
        $user = $this->prophesize(User::class);
        $user->getRating()->willReturn(2);
        $user->getId()->willReturn(1);

        $storeRatingForGetter = new CallbackPromise(function ($args) use ($user) {
            $user->getRating()->willReturn($args[0]);
        });
        $user->setRating(Argument::type('integer'))->will($storeRatingForGetter);

        $disp = $this->prophesize(EventDispatcher::class);
        $disp->userRatingIncreasing(1, 2)->shouldBeCalled();
        $disp->userRatingIncreased(1, 4)->shouldBeCalled();

        $flashBag = $this->prophesize(FlashBag::class);
        $flashBag->addMessage('User rating changed from 2 to 4')->shouldBeCalled();

        $translate = $this->prophesize(Translate::class);
        $translate->translate(Argument::cetera())->willReturnArgument(0);

        $calc = new UserRatingCalculator($disp->reveal(), $flashBag->reveal(), $translate->reveal());
        $calc->increaseUserRating($user->reveal(), 2);
    }
}
