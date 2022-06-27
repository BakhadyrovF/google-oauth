<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirectToAuth(Request $request)
    {
        return Socialite::driver('google')
            ->with([
                'access_type' => 'offline',
                'prompt' => 'consent'
            ])
            ->redirect();
    }

    public function authCallback(Request $request)
    {
        $code = $request->query('code');
        $request = Request::create('http://127.0.0.1:8000/api/auth/sign-in', 'POST', ['code' => $code]);
        $response = app()->handle($request);


        return response()->json(json_decode($response->getContent()))
            ->withCookie($response->headers->getCookies()[0]);


    }
}
