<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use GuzzleHttp\Client;


class LoginApi
{
    public function auth(Request $request)
    {
        $client = new Client([
            'base_uri' => env('APP_SWEET_API'),
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        $response = $client->get('api/v1/auth/login/?'.http_build_query([
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ]), [
            'headers' => [
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ]
        ]);

        if ($response->getStatusCode() == 401) {
            return response()->json([
                'status' => 'fail',
                'result' => ''
            ], 201);
        }

        $response = \GuzzleHttp\json_decode($response->getBody()->getContents());

        if ($response->status == 'success') {
            $request->session()->put('email', $request->get('email'));
            $request->session()->put('api_key', $response->api_key);
            $request->session()->put('menu', $response->access);
            if (!empty($response->companies)) {
                $request->session()->put('userCompanies', $response->companies);
            }

            return response()->json([
                'status' => $response->status,
                'result' => $response->api_key
            ], 201);
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}
