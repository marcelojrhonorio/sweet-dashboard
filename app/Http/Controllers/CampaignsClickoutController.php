<?php

namespace App\Http\Controllers;

use Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ClientErrorResponseException;

/**
 * Class CampaignsController
 * @package App\Http\Controllers
 */
class CampaignsClickoutController extends Controller
{
    const ENDPOINT = 'api/v1/admin/campaigns-clickout';
    private $client;
    private $headers = [];

    /**
     * CampaignsController constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('APP_SWEET_API'),
            'headers' => [
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ]
        ]);
    }

    public function index()
    {

    }

    public function delete(Request $request)
    {
        $response = $this->client->delete(sprintf('%s/%d', self::ENDPOINT, $request->get('id')), [
            'headers' => [
                'Authorization' => 'Bearer ' .  session()->get('api_key'),
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
            ]
        ]);

        $arrayReturn = [];

        if ($response->getStatusCode() == 204) {
            $arrayReturn =  ['entity' => $response->getHeader('entity')];
        }

        return response()->json($arrayReturn, 200);

    }

    public function update(Request $request)
    {
        $response = $this->client->request('PUT', sprintf('%s/%d', self::ENDPOINT, $request->get('id')),  [
            'headers' => [
                'Authorization' => 'Bearer ' .  session()->get('api_key'),
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
            ],
            'json' => [
                'answer' => urldecode($request->get('answer')),
                'affirmative' => (urldecode($request->get('affirmative')) == 'Sim' ? 1 : 0),
                'link' => urldecode($request->get('link')),
            ],
        ]);

        return  $response->getBody()->getContents();
    }

    public function create(Request $request)
    {
        try {

            $response = $this->client->request('POST', self::ENDPOINT, [
                'headers' => [
                    'Authorization' => 'Bearer ' . session()->get('api_key'),
                    'Content-Type' => 'application/json',
                    'cache-control' => 'no-cache',
                    'accept' => 'application/json',
                ],
                'json' => [
                    'answer' => $request->get('answer'),
                    'affirmative' => $request->get('affirmative'),
                    'link' => $request->get('link'),
                    'campaigns_id' => $request->get('idCampaign'),
                ],
            ]);

            return $response->getBody()->getContents();

        } catch (ClientException $e) {

            $content = [];
            preg_match('/{.*}/i', $e->getMessage(), $content);

            return  response()->json([
                'status' => $e->getCode(),
                'errors' => \GuzzleHttp\json_decode($content[0], true)['errors'],
            ], 422);
        }

    }

}
