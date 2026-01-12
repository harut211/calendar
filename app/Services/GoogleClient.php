<?php


namespace App\Services;

use Google\Client;

class GoogleClient
{
    public static function make()
    {
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setScopes([
            \Google\Service\Calendar::CALENDAR
        ]);

        return $client;
    }
}
