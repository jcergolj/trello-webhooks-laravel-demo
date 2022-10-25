<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyTrelloIPsMiddleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class GetWebhooksTrelloTest extends TestCase
{
    /** @test */
    public function verify_trello_server_ip_middleware_is_applied_for_trello_get_request()
    {
        $this->assertContains(VerifyTrelloIPsMiddleware::class, $this->getMiddlewareFor('get.trello'));
    }

    /** @test */
    public function get_webhooks_trello()
    {
        $response = $this->withoutMiddleware()
            ->get('webhooks/trello');

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
