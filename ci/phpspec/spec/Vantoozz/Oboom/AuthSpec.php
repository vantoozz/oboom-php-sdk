<?php

namespace spec\Vantoozz\Oboom;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use RuntimeException;
use Vantoozz\Oboom\Transport\TransportInterface;

class AuthSpec extends ObjectBehavior
{
    public function let(TransportInterface $transport)
    {
        $this->beConstructedWith($transport);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Vantoozz\Oboom\Auth');
    }

    public function it_sets_config()
    {
        $this->setConfig([
            'username' => 'username',
            'password' => 'password',
        ])->shouldReturn($this);
    }

    public function it_requires_user_config_param()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('No username given'))
            ->during('setConfig', [['password' => 'password']]);
    }

    public function it_requires_password_config_param()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('No password given'))
            ->during('setConfig', [['source' => 'source', 'username' => 'username']]);
    }

    public function it_logins(TransportInterface $transport)
    {
        $transport->call('https://www.oboom.com/1/login', [
            'auth'   => 'john@example.com',
            'pass'   => 'f9cc0aeaefc9fb9cab93bc1f3c426958',
            'source' => '/#app'
        ])->shouldBeCalled()->willReturn([0 => 200, 1 => ['session' => 'cb597b3e-cfc4-4329-abe0-5dc2b64a8e9a']]);

        $this->setConfig([
            'username' => 'john@example.com',
            'password' => '1234567',
        ])->login()->shouldReturn($this);
    }

    public function it_handle_bad_api_response(TransportInterface $transport)
    {
        $transport->call('https://www.oboom.com/1/login', [
            'auth'   => 'john@example.com',
            'pass'   => 'f9cc0aeaefc9fb9cab93bc1f3c426958',
            'source' => '/#app'
        ])->shouldBeCalled()->willReturn(123);

        $this->setConfig([
            'username' => 'john@example.com',
            'password' => '1234567',
        ]);
        $this->shouldThrow(new RuntimeException('API error'))->during('login');
    }

    public function it_returns_token(TransportInterface $transport)
    {
        $transport->call('https://www.oboom.com/1/login', [
            'auth'   => 'john@example.com',
            'pass'   => 'f9cc0aeaefc9fb9cab93bc1f3c426958',
            'source' => '/#app'
        ])->shouldBeCalled()->willReturn([0 => 200, 1 => ['session' => 'cb597b3e-cfc4-4329-abe0-5dc2b64a8e9a']]);

        $this->setConfig([
            'username' => 'john@example.com',
            'password' => '1234567',
        ])->login();

        $this->getToken()->shouldReturn('cb597b3e-cfc4-4329-abe0-5dc2b64a8e9a');
    }

}
