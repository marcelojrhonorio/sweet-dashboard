<?php

namespace App\Http\Controllers;

use Cache;
use DataTables;
use App\Http\Requests;
use GuzzleHttp\Client;
use App\Models\Campaigns;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ClientErrorResponseException;


class CompaniesController extends Controller
{
    const ENDPOINT = 'api/v1/admin/companies';
    private $client;
    private $headers = [];

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

    public function index()  : \Illuminate\View\View
    {
        return view('companies.index', []);
    }

    public function search(Request $request)
    {
        $response = $this->client->get(self::ENDPOINT, [
            'headers' => [
                'Authorization' => 'Bearer ' .  session()->get('api_key'),
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
            ]
        ]);

        return response()->json(\GuzzleHttp\json_decode($response->getBody()->getContents())->results);   
    }

    /**
     * @param Request $request
     * @return string
     * /
   public function delete(Request $request) :string
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
    }*/
    /**
     * exclusao lógica
     *
     * @param Request $request
     * @return string
     */
    public function delete(Request $request) :string
    {
        $response = $this->client->request('PATCH', sprintf('%s/%d', self::ENDPOINT, $request->get('id')),  [
            'headers' => [
                'Authorization' => 'Bearer ' .  session()->get('api_key'),
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
            ],
            'json' => ['status' => 0],
        ]);

        Cache::flush();
        return $response->getBody()->getContents();
    }

    public function create(Request $request): \Illuminate\View\View
    {
       return view('companies.create');
    }

    public function save(Request $request)
    {
        try {

             $data = [
                'name' => $request->get('name'),
                'cnpj' => returnNumber($request->get('cnpj')),
                'nickname' => $request->get('nickname'),
             ];

             $response = $this->client->request('POST', self::ENDPOINT, [
                'headers' => [
                    'Authorization' => 'Bearer ' . session()->get('api_key'),
                    'Content-Type' => 'application/json',
                    'cache-control' => 'no-cache',
                    'accept' => 'application/json',
                ],
                'json' => $data,
             ]);

             Cache::flush();
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

    public function update(Request $request)
    {
        $data = [
            'name' => $request->get('name'),
            'cnpj' => returnNumber($request->get('cnpj')),
            'nickname' => $request->get('nickname'),
        ];

        $response = $this->client->request('PUT', sprintf('%s/%d', self::ENDPOINT, $request->get('id')),  [
            'headers' => [
                'Authorization' => 'Bearer ' .  session()->get('api_key'),
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
            ],
            'json' => $data,
        ]);

        Cache::flush();
        return $response->getBody()->getContents();
    }
}
