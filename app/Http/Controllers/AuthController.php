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
            header('Content-Type: application/json');
            $body = $response->getBody()->getContents();
            echo $body; die;
        } catch (\GuzzleHttp\Exception\BadResponseException $ex) {
            if ($ex->getCode() === 400) {
                return response()->json(
                    [
                        'error' => 'Something went wrong on the server.',
                        'ex' => $ex->getMessage(),
                        'loginEndpoint' => $loginEndpoint
                    ],
                    500
                );
            } else if ($ex->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $ex->getCode());
            }
            return response()->json(
                [
                    'error' => 'Something went wrong on the server.',
                    'ex' => $ex->getMessage(),
                ],
                500
            );
        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            return response()->json(
                [
                    'error' => 'Something went wrong on the server.',
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
