<?php

namespace Tests\Unit;

use App\Http\Middleware\VerifyTrelloIPsMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class VerifyTrelloIPsMiddlewareTest extends TestCase
{
    /** @test */
    public function continue_if_trello_request_is_from_valid_ip_range()
    {
        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            ['REMOTE_ADDR' => '18.234.32.224'],
        );

        $expectedResponse = new Response('allowed', Response::HTTP_OK);
        $next = function () use ($expectedResponse) {
            return $expectedResponse;
        };

        $actualResponse = (new VerifyTrelloIPsMiddleware())->handle($request, $next);

        $this->assertSame($expectedResponse, $actualResponse);
    }

    /** @test */
    public function bad_request_exception_is_thrown_if_request_is_outside_valid_range()
    {
        $this->withExceptionHandling();
        $this->expectException(HttpException::class);

        $middleware = new VerifyTrelloIPsMiddleware();

        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            ['REMOTE_ADDR' => '129.0.0.1'],
        );

        $middleware->handle($request, function ($request) {
            $this->fail('Next middleware was called when it should not have been.');
        });
    }
}
