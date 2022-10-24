<?php

use App\Http\Middleware\VerifyTrelloIPsMiddleware;
use App\Http\Middleware\VerifyTrelloWebhookSignatureMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

// trello submits get request upon webhook creation to verify callbackURL endpoint
Route::middleware([VerifyTrelloIPsMiddleware::class])
    ->get('trello', function () {
        return response()->noContent(Response::HTTP_OK);
    })->name('get.trello');

// for all consecutive webhooks request trello uses POST
Route::middleware([VerifyTrelloWebhookSignatureMiddleware::class, VerifyTrelloIPsMiddleware::class])
    ->post('trello', function (Request $request) {
        info($request->getContent());

        // ideally a job is dispatched here and OK status is returned straight away

        return response()->noContent(Response::HTTP_OK);
    })->name('post.trello');
