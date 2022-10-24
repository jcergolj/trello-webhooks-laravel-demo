<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;

class GetWebhooksTrelloTest extends TestCase
{
    /** @test */
    function get_webhooks_trello()
    {
        $response = $this->get('webhooks/trello');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertContent('');
    }
}
