<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Client\Response as ClientResponse;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class DeleteWebhookCommandTest extends TestCase
{
    protected $trelloModelId = 'trello-model-id';

    /** @test */
    function webhook_is_deleted()
    {
        Http::fake([
            '*' => Http::response()
        ]);

        $this->artisan('trello-webhooks:delete '.$this->trelloModelId)->assertExitCode(0);

        Http::assertSentInOrder([
            $this->assertDeleteWebhookRequest()
        ]);
    }

    /** @test */
    function webhook_is_not_deleted()
    {
        Http::fake([
            '*' => Http::response([], Response::HTTP_INTERNAL_SERVER_ERROR)
        ]);

        $this->artisan('trello-webhooks:delete '.$this->trelloModelId)->assertExitCode(1);

        Http::assertSentInOrder([
            $this->assertWebhookDeletionFailsRequest()
        ]);
    }

     protected function assertDeleteWebhookRequest()
    {
        return function (Request $request) {
            $this->assertSame(
                'https://api.trello.com/1/webhooks/'.$this->trelloModelId,
                $request->url()
            );

            $this->assertSame('DELETE', $request->method());

            $this->assertSame([], $request->data());

            return true;
        };
    }

    protected function assertWebhookDeletionFailsRequest()
    {
        return function (Request $request, ClientResponse $response) {
            $this->assertSame(
                'https://api.trello.com/1/webhooks/'.$this->trelloModelId,
                $request->url()
            );

            $this->assertSame('DELETE', $request->method());

            $this->assertSame([], $request->data());

            $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());

            return true;
        };
    }
}
