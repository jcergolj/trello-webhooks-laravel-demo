<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyTrelloWebhookSignatureMiddleware
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $hashed = base64_encode(
            hash_hmac(
                'sha1',
                $request->getContent().route('post.trello'),
                config('services.trello.oauth_secret'),
                true
            )
        );

        abort_if($request->header('X-Trello-Webhook') !== $hashed, Response::HTTP_BAD_REQUEST, 'Bad Webhook.');

        return $next($request);
    }
}
