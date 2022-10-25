<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyTrelloIPsMiddleware;
use App\Http\Middleware\VerifyTrelloWebhookSignatureMiddleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PostWebhooksTrelloTest extends TestCase
{
    /** @test */
    public function verify_trello_webhook_middleware_is_applied_for_trello_post_request()
    {
        $this->assertContains(VerifyTrelloWebhookSignatureMiddleware::class, $this->getMiddlewareFor('post.trello'));
    }

    /** @test */
    public function verify_trello_server_ip_middleware_is_applied_for_trello_post_request()
    {
        $this->assertContains(VerifyTrelloIPsMiddleware::class, $this->getMiddlewareFor('post.trello'));
    }

    /** @test */
    public function post_webhooks_trello()
    {
        $response = $this->withoutMiddleware()
            ->postJson(
                route('post.trello'),
                ['idModel' => 'abc123']
            );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertContent('');
    }

    /**
     * Get array of middleware for given route.
     *
     * @param  string  $route
     * @return array
     */
    protected function getMiddlewareFor($route)
    {
        return array_map(function ($middleware) {
            return explode(':', $middleware)[0];
        }, Route::getRoutes()->getByName($route)->gatherMiddleware());
    }
}
