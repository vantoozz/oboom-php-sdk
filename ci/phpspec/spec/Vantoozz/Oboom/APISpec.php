<?php

namespace spec\Vantoozz\Oboom;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class APISpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Vantoozz\Oboom\API');
    }

    function it_sets_config()
    {
        $this->setConfig([
            'username' => 'username',
            'password' => 'password',
            'source'   => 'source'
        ])->shouldReturn($this);
    }

    function it_requires_user_config_param()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('No username given'))
            ->during('setConfig', [['source' => 'source', 'password' => 'password']]);
    }

    function it_requires_password_config_param()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('No password given'))
            ->during('setConfig', [['source' => 'source', 'username' => 'username']]);
    }

    function it_requires_source_config_param()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('No source given'))
            ->during('setConfig', [['username' => 'username', 'password' => 'password']]);
    }

    function it_logins()
    {
        $this->setConfig([
            'username' => 'vantoozz@gmail.com',
            'password' => 'bc5e6b6e',
            'source'   => '200'
        ])->login()->shouldReturn($this);
    }

}
