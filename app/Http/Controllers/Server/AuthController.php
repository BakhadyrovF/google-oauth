<?php

namespace App\Http\Controllers\Server;

use App\Http\Controllers\Controller;
use App\Services\GoogleService;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    public function signIn(Request $request, GoogleService $service)
    {
        $data = $request->validate(['code' => ['required', 'string']]);

        $googleTokens = $service->getTokens($data['code']);

        $googleUser = $service->getUserWithToken($googleTokens->access_token);

        $user = \App\Models\User::query()
            ->updateOrCreate([
                'google_id' => $googleUser->sub
            ], [
                'name' => $googleUser->name,
                'email' => $googleUser->email
            ]);

        $tokenId = $user->tokens->id ?? null;

        $user->tokens()
            ->updateOrCreate([
                'id' => $tokenId
            ], [
                'token' => $googleTokens->access_token,
                'refresh_token' => $googleTokens->refresh_token
            ]);

        $tokenCookie = cookie('access_token', $googleTokens->access_token, 60, '/api');

        return response()->json([
            'message' => 'Signed In',
            'data' => $user
        ])->withCookie($tokenCookie);
    }

    public function currentUser(Request $request)
    {
//        dd(decrypt_cookie($request->cookie('access_token')));
        return auth('google')->user();
    }
}

