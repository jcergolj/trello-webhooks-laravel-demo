<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Client\Response as ClientResponse;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class GetWebhooksTrelloTest extends TestCase
{
    /** @test */
    function get_webhooks_trello()
    {
        $response = $this->get('webhooks/trello');

        $response->assertNoContent();
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
