<?php

namespace spec\Vantoozz\Oboom;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class APISpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Vantoozz\Oboom\API');
    }
}
