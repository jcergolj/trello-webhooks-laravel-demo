<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

// trello submits get request to webhooks/trello endpoint to verify
// that endpoint is valid and active
Route::get('webhooks/trello', function () {
    return response()->json([], Response::HTTP_OK);
});
