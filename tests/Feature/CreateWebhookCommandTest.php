<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Client\Response as ClientResponse;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class CreateWebhookCommandTest extends TestCase
{
    protected $trelloModelId = 'trello-model-id';

    /** @test */
    function webhook_is_created()
    {
        Http::fake([
            '*' => Http::response()
        ]);

        $this->artisan('trello-webhooks:create '.$this->trelloModelId)->assertExitCode(0);

        Http::assertSentInOrder([
            $this->assertCreateWebhookRequest()
        ]);
    }

    /** @test */
    function webhook_is_not_created()
    {
        Http::fake([
            '*' => Http::response([], Response::HTTP_CONFLICT)
        ]);

        $this->artisan('trello-webhooks:create '.$this->trelloModelId)->assertExitCode(1);

        Http::assertSentInOrder([
            $this->assertWebhookCreationFailsRequest()
        ]);
    }

     protected function assertCreateWebhookRequest()
    {
        return function (Request $request) {
            $this->assertSame('https://api.trello.com/1/webhooks', $request->url());

            $this->assertSame('POST', $request->method());

            $this->assertSame($this->trelloModelId, $request->data()['idModel']);
            $this->assertSame('webhook description', $request->data()['description']);
            $this->assertSame(
                config('app.url').'/webhooks/trello',
                $request->data()['callbackURL']
            );

            return true;
        };
    }

    protected function assertWebhookCreationFailsRequest()
    {
        return function (Request $request, ClientResponse $response) {
            $this->assertSame('https://api.trello.com/1/webhooks', $request->url());

            $this->assertSame('POST', $request->method());

            $this->assertSame($this->trelloModelId, $request->data()['idModel']);
            $this->assertSame('webhook description', $request->data()['description']);
            $this->assertSame(
                config('app.url').'/webhooks/trello',
                $request->data()['callbackURL']
            );

            $this->assertSame(Response::HTTP_CONFLICT, $response->getStatusCode());

            return true;
        };
    }
}
