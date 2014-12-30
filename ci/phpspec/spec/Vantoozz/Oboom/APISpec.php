<?php

namespace spec\Vantoozz\Oboom;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Vantoozz\Oboom\Auth;
use Vantoozz\Oboom\Transport\TransportInterface;

class APISpec extends ObjectBehavior
{
    public function let(TransportInterface $transport, Auth $auth)
    {
        $this->beConstructedWith($transport, $auth);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Vantoozz\Oboom\API');
    }

    public function it_make_api_calls(TransportInterface $transport, Auth $auth){

        $auth->getToken()->willReturn('cb597b3e-cfc4-4329-abe0-5dc2b64a8e9a');

        $transport->call('https://api.oboom.com/method', [
            'param1'   => 'aaa',
            'param2'   => 'bbb',
            'token' => 'cb597b3e-cfc4-4329-abe0-5dc2b64a8e9a'
        ])->shouldBeCalled();

        $this->call('api.oboom.com', '/method', ['param1'=>'aaa', 'param2'=>'bbb']);
    }

    public function it_requires_correct_endpoint(){
        $this
            ->shouldThrow(new InvalidArgumentException('No such endpoint'))
            ->during('call', ['incorrect_endpoint', '/method']);
    }


}
