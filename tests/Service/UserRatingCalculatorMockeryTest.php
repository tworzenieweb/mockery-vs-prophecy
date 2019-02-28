<?php

namespace Jobleads\Service;


use Jobleads\Model\User;
use Mockery as M;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class UserRatingCalculatorMockeryTest extends MockeryTestCase
{
    public function testIncreaseUserRating()
    {
        $user = M::mock(User::class);
        $user->shouldReceive('getRating')->times(4)->andReturn(2, 2, 4, 4);
        $user->shouldReceive('getId')->once()->andReturn(1);
        $user->shouldReceive('setRating')->with(4)->once();

        $eventDispatcher = M::mock(EventDispatcher::class);
        $eventDispatcher->shouldReceive('userRatingIncreasing')->with(1, 2)->once();
        $eventDispatcher->shouldReceive('userRatingIncreased')->with(1, 4)->once();

        $flashBag = M::mock(FlashBag::class);
        $flashBag->shouldReceive('addMessage')->with('User rating changed from 2 to 4')->once();

        $translate = M::mock(Translate::class);
        $translate->shouldReceive('translate')
                  ->with('User rating changed from %s to %s')
                  ->once()
                  ->andReturn('User rating changed from %s to %s');

        $calc = new UserRatingCalculator($eventDispatcher, $flashBag, $translate);
        $calc->increaseUserRating($user, 2);
    }
}
