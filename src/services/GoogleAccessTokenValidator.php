<?php

namespace FmcExample\GoogleLogin\services;

use Google_Client;
use Exception;
use Google_Service_Oauth2;

class GoogleAccessTokenValidator
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client(['client_id' => config('config.client_id')]);
    }

    public function validateAndGetUserInfo($accessToken)
    {
        $this->client->setAccessToken($accessToken);
        $oauth2 = new Google_Service_Oauth2($this->client);
        $userInfo = $oauth2->userinfo->get();

        return [
            'google_id' => $userInfo->getId() ?? '',
            'email' => $userInfo->getEmail() ?? '',
            'name' => $userInfo->getName() ?? '',
            'picture' => $userInfo->getPicture() ?? '',
        ];
    }
}
