<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\UnauthorizedException;

final class GoogleService
{
    public const TOKENS_ENDPOINT = 'https://www.googleapis.com/oauth2/v4/token';
    public const USER_INFO_ENDPOINT = 'https://www.googleapis.com/oauth2/v3/userinfo';

    protected string $clientId;
    protected string $clientSecret;
    protected string $redirectUri;
    protected string $grantType;

    public function __construct()
    {
        $this->clientId = config('services.google.client_id');
        $this->clientSecret = config('services.google.client_secret');
        $this->redirectUri = config('services.google.redirect');
        $this->grantType = 'authorization_code';
    }

    public function getTokens($code)
    {
        $query = http_build_query([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => $this->grantType,
            'redirect_uri' => $this->redirectUri,
            'code' => $code
        ]);

        $url = self::TOKENS_ENDPOINT . '?' . $query;
        $response = Http::withHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
            ->post($url);

        return $response->status() === 200
            ? $response->object()
            : throw new UnauthorizedException('Invalid code provided', 401);
    }

    public function getUserWithToken(string $token)
    {
        $response = Http::withToken($token)
            ->get(self::USER_INFO_ENDPOINT);

        return $response->status() === 200
            ? $response->object()
            : throw new UnauthorizedException('Invalid token provided', 401);
    }
}
