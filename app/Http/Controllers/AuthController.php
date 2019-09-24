<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        $loginEndpoint = config('services.passport.login_endpoint');
        try {
            $response = $http->post($loginEndpoint, [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->username,
                    'password' => $request->password,
                ]
            ]);
            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $ex) {
            return response()->json([
                'error_description' => 'Login Failed'
            ], $ex->getCode());
        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            return response()->json(
                [
                    'error_description' => 'Something went wrong on the server.',
                    'ex' => $ex->getMessage(),
                    'loginEndpoint' => $loginEndpoint
                ],
                500
            );
        }
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json('You are logged out', 200);
    }
}
