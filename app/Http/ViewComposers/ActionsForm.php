<?php

namespace App\Http\ViewComposers;

use GuzzleHttp\Client;
use Illuminate\View\View;

class ActionsForm
{
    const ENDPOINT_CATEGORIES = 'api/v1/admin/actions/categories';

    const ENDPOINT_TYPES = 'api/v1/admin/actions/types';

    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('APP_SWEET_API'),
            'headers'  => [
                'cache-control' => 'no-cache',
                'accept'        => 'application/json',
                'content-type'  => 'application/json',
            ],
        ]);
    }

    public function compose(View $view)
    {
        $categories = $this->client->get(self::ENDPOINT_CATEGORIES, [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('api_key')
            ]
        ]);

        $categories = $categories->getBody()->getContents();
        $categories = \GuzzleHttp\json_decode($categories)->data;

        $types = $this->client->get(self::ENDPOINT_TYPES, [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('api_key')
            ]
        ]);

        $types = $types->getBody()->getContents();
        $types = \GuzzleHttp\json_decode($types)->data;

        $view->with([
            'categories' => $categories,
            'types'      => $types,
        ]);
    }
}

// $response = $this->client->get(self::ENDPOINT, [
//     'headers' => [
//         'Authorization' => 'Bearer ' . session()->get('api_key')
//     ]
// ]);

// $content = $response->getBody()->getContents();
// $decoded = \GuzzleHttp\json_decode($content)->data;
