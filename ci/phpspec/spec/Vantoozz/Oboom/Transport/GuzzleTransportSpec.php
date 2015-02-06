<?php

namespace spec\Vantoozz\Oboom\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Message\ResponseInterface;
use PhpSpec\ObjectBehavior;
use RuntimeException;

class GuzzleTransportSpec extends ObjectBehavior
{
    public function let(Client $guzzleClient)
    {
        $this->beConstructedWith($guzzleClient);
    }

    public function it_calls_oboom_successful(Client $guzzleClient, ResponseInterface $response)
    {
        $url    = 'http://example.com';
        $params = ['one' => 1, 'two' => 2];

        $response->json()->shouldBeCalled()->willReturn([200, 'Success']);

        $guzzleClient->get($url, [
            'query' => $params
        ])->shouldBeCalled()->willReturn($response);;


        $this->call($url, $params)->shouldReturn([200, 'Success']);;
    }

    public function it_handle_bad_oboom_response(Client $guzzleClient, ResponseInterface $response)
    {
        $url = 'http://example.com';
        $response->json()->shouldBeCalled()->willReturn("something went wrong");

        $guzzleClient->get($url, [
            'query' => []
        ])->willReturn($response);

        $this->shouldThrow(new RuntimeException('Bad response: something went wrong'))->during('call', [$url]);
    }

    public function it_handle_failed_oboom_response(Client $guzzleClient, ResponseInterface $response)
    {
        $url = 'http://example.com';
        $response->json()->shouldBeCalled()->willReturn([400, 'Bad Request']);

        $guzzleClient->get($url, [
            'query' => []
        ])->willReturn($response);

        $this->shouldThrow(new RuntimeException('Bad Request', 400))->during('call', [$url]);
    }

    public function it_handle_unexpected_oboom_response(Client $guzzleClient, ResponseInterface $response)
    {
        $url = 'http://example.com';
        $response->json()->shouldBeCalled()->willReturn([400]);

        $guzzleClient->get($url, [
            'query' => []
        ])->willReturn($response);

        $this->shouldThrow(new RuntimeException('API error', 400))->during('call', [$url]);
    }
}
