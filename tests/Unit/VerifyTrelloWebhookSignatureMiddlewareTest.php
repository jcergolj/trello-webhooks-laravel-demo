<?php

namespace Tests\Unit;

use App\Http\Middleware\VerifyTrelloWebhookSignatureMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class VerifyTrelloWebhookSignatureMiddlewareTest extends TestCase
{
    /** @test */
    public function if_webhook_signature_is_verified_continue()
    {
        $request = Request::create(
            'post.trello',
            'POST',
            [],
            [],
            [],
            [],
            json_encode(['idModel' => 'abc123'])
        );

        $request->headers->set('X-Trello-Webhook', '1STn4SQAzacft3ND9jw5S9GZLZ8=');

        $expectedResponse = new Response('allowed', Response::HTTP_OK);
        $next = function () use ($expectedResponse) {
            return $expectedResponse;
        };

        $actualResponse = (new VerifyTrelloWebhookSignatureMiddleware())->handle($request, $next);

        $this->assertSame($expectedResponse, $actualResponse);
    }

    /** @test */
    public function if_webhook_signature_is_not_verified_abort()
    {
        $this->withExceptionHandling();

        $this->expectException(HttpException::class);

        $request = Request::create(
            'post.trello',
            'POST',
        );

        (new VerifyTrelloWebhookSignatureMiddleware())->handle($request, function () {
        });
    }
}
