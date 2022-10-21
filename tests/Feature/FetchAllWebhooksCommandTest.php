<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Client\Response as ClientResponse;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class FetchAllWebhooksCommandTest extends TestCase
{
    protected $webhook1 = [
        'id' => 'webhook-1',
        'idModel' => 'trello-id-model-1',
        'description' => '',
        'callbackURL' => '',
        'active' => 1,
        'consecutiveFailures' => 1,
        'firstConsecutiveFailDate' => '',
    ];

    protected $webhook2 = [
        'id' => 'webhook-2',
        'idModel' => 'trello-id-model-2',
        'description' => '',
        'callbackURL' => '',
        'active' => 0,
        'consecutiveFailures' => 0,
        'firstConsecutiveFailDate' => '',
    ];

    /** @test */
    function fetch_all_webhooks()
    {
        Http::fake([
            '*' => Http::response([
                $this->webhook1,
                $this->webhook2
            ])
        ]);

        $this->artisan('trello-webhooks:all')
            ->expectsTable(
                ['id', 'idModel', 'active', 'consecutiveFailures'],
                [
                    [
                        $this->webhook1['id'],
                        $this->webhook1['idModel'],
                        $this->webhook1['active'],
                        $this->webhook1['consecutiveFailures'
                    ]
                ],
                    [
                        $this->webhook2['id'],
                        $this->webhook2['idModel'],
                        $this->webhook2['active'],
                        $this->webhook2['consecutiveFailures']
                    ],
                ]
            )->assertExitCode(0);

        Http::assertSentInOrder([
            $this->assertFetchAllWebhooksRequest()
        ]);
    }

    /** @test */
    function webhooks_not_fetched()
    {
        Http::fake([
            '*' => Http::response([], Response::HTTP_INTERNAL_SERVER_ERROR)
        ]);

        $this->artisan('trello-webhooks:all')
            ->assertExitCode(1);

        Http::assertSentInOrder([
            $this->assertWebhookCreationFailsRequest()
        ]);
    }

     protected function assertFetchAllWebhooksRequest()
    {
        return function (Request $request) {
            $this->assertSame(
                'https://api.trello.com/1/tokens/'.config('services.trello.token').'/webhooks',
                $request->url()
            );

            $this->assertSame('GET', $request->method());

            $this->assertSame([], $request->data());

            return true;
        };
    }

    protected function assertWebhookCreationFailsRequest()
    {
        return function (Request $request, ClientResponse $response) {
            $this->assertSame(
                'https://api.trello.com/1/tokens/'.config('services.trello.token').'/webhooks',
                $request->url()
            );

            $this->assertSame('GET', $request->method());

            $this->assertSame([], $request->data());

            $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());

            return true;
        };
    }
}
