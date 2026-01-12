<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleClient;
use Google\Service\Calendar;

class GoogleController extends Controller
{
    public function redirect()
    {
        $client = GoogleClient::make();
        return redirect($client->createAuthUrl());
    }

    public function callback(Request $request)
    {
        $client = GoogleClient::make();
        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        session(['google_token' => $token]);

        return redirect('/calendar');
    }
}

