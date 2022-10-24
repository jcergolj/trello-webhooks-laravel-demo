<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

// trello submits get request upon webhook creation to verify callbackURL endpoint
Route::get('trello', function () {
    return response()->noContent(Response::HTTP_OK);
});


// for all consecutive webhooks request trello uses POST
Route::post('trello', function (Request $request) {
    info($request->getContent());

    return response()->noContent(Response::HTTP_OK);
});
