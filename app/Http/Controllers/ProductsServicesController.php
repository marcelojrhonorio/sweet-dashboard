<?php

namespace App\Http\Controllers;

use Cache;
use App\Http\Requests;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Traits\SweetStaticApiTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ClientErrorResponseException;

class ProductsServicesController extends Controller
{
    use SweetStaticApiTrait;

    const ENDPOINT = 'api/v1/admin/products-services';

    private $client;

    private $headers = [];

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

    private function getResources()
    {
        $response = $this->client->get(self::ENDPOINT . '/resources/', [
            'headers' => [
                'Authorization' => 'Bearer ' .  session()->get('api_key'),
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
            ]
        ]);

        $json = \GuzzleHttp\json_decode($response->getBody()->getContents())->results;

       return [
                'categories' => $json->categories,
            ];

    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()  : \Illuminate\View\View
    {       
        $stamps = $this->getStamps();
        return view('products_services.index', [
            'resources' => $this->getResources(),
            'stamps'    => $stamps->original['data'],
            ]);
            
    }

    public function search()
    {
        Log::debug('searching');

        $response = $this->client->get(self::ENDPOINT, [
            'headers' => [
                'Authorization' => 'Bearer ' .  session()->get('api_key'),
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
            ]
        ]);

        $data = response()->json(\GuzzleHttp\json_decode($response->getBody()->getContents())->results);

        return $data;
    }

    public function getProductServiceStamps(Request $request)
    {
        $product_id = $request->input('product_id');

        try {            

            $response = self::executeSweetApi(
                'GET',
                'api/v1/frontend/products-services/'.$product_id,
                []
            );

            $product_service = $response->results;

            if($product_service) {
                
               $stamps_product = self::getStampProduct($product_service->id);

               $product_service->category_id = (int) $product_service->category_id;
               $product_service->points = (int) $product_service->points;

               session()->put('imagePS', $product_service->path_image);
                
               if($stamps_product) {
                    $product_service_stamps = [
                        'product' => $product_service,
                        'stamps'  => $stamps_product,
                    ];
               } else {
                    $product_service_stamps = [
                        'product' => $product_service,
                        'stamps'  => [],
                    ];
               }

                return  response()->json([
                    'success' => true,
                    'data'    => $product_service_stamps,
                ]);  
            }

            return  response()->json([
                'success' => false,
                'data'    => [],
            ]);  
                 
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }

    private static function getStampProduct(int $product_id)
    {
        try {            

            $response = self::executeSweetApi(
                'GET',
                'api/v1/product-service-stamps?where[product_id]='.$product_id,
                []
            );

            $stamp_product = $response->data;

            if($stamp_product) {
                $array = [];

                foreach($stamp_product as $data) {
                    $stamp = $data->stamp;
                    array_push($array, $stamp);
                }
                return $array;
            }
            return null;                 
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }        
    }

    public function save(Request $request)
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
                    'category' => $request->get('category'),
                    'title' => $request->get('title'),
                    'description' => $request->get('description'),
                    'points' => $request->get('points'),
                    'image' => session()->get('imagePS') ?? '',
                    'social_network' => $request->get('social_network') ?? null,
                    'exibition_time' => $request->get('exibition_time') ?? null,
                ],
            ]);

            $responseGetBody = $response->getBody()->getContents();

            $json = \GuzzleHttp\json_decode($responseGetBody);
            $obj = get_object_vars($json);

            $stamps = $request->get('stamps');
            foreach($stamps as $stamp)
            {
                self::createProductServiceStamps($stamp, $obj['result']->id);
            }

            session()->forget('imagePS');
            session()->forget('imagePathPS');
            session()->forget('imageNamePS');

            return $responseGetBody;

        } catch (ClientException $e) {

            $content = [];
            preg_match('/{.*}/i', $e->getMessage(), $content);

            return  response()->json([
                'status' => $e->getCode(),
                'errors' => \GuzzleHttp\json_decode($content[0], true)['errors'],
            ], 422);
        }
    }

    private static function createProductServiceStamps(int $stamps_id, int $product_id)
    {
        try {
            
            $response = self::executeSweetApi(
                'POST',
                'api/v1/product-service-stamps/',
                [
                    'stamps_id'  => $stamps_id,
                    'product_id' => $product_id,
                ]
            );     

           return;           
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
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
                'category' => $request->get('category'),
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'points' => $request->get('points'),
                'image' => session()->get('imagePS') ?? '',
                'social_network' => $request->get('social_network') ?? null,
                'exibition_time' => $request->get('exibition_time') ?? null,
            ],
        ]);

        $responseGetBody = $response->getBody()->getContents();

        //selos para update
        $stamps = $request->get('stamps');
        self::deleteProductStamps($request->get('id'));

        foreach($stamps as $stamp)
        {
            self::createProductServiceStamps($stamp, $request->get('id'));
        }

        session()->forget('imagePS');
        session()->forget('imagePathPS');
        session()->forget('imageNamePS');

        return $responseGetBody;
    }

    private static function deleteProductStamps(int $id)
    { 
        try {
            
            $response = self::executeSweetApi(
                'POST',
                'api/v1/product-service-stamps/delete?id='.$id,
                []
            ); 

            return $response;                
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }    

    private static function getProductStamps(int $stamps_id, int $product_id)
    {
        try {
            
            $response = self::executeSweetApi(
                'GET',
                'api/v1/product-service-stamps?where[stamps_id]='.$stamps_id.'&where[product_id]='.$product_id,
                []
            ); 
            
           return $response;           
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }


    private static function updateProductServiceStamps(int $stamps_id, int $product_id)
    {
        try {
            
            $response = self::executeSweetApi(
                'PUT',
                'api/v1/product-service-stamps/',
                [
                    'stamps_id'  => $stamps_id,
                    'product_id' => $product_id,
                ]
            );     

           return $response;           
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }

    /**
     * exclusao lógica
     *
     * @param Request $request
     * @return string
     */
    public function delete(Request $request) :string
    {
        self::deleteProductStamps($request->get('id'));

        $response = $this->client->request('POST', sprintf('%s/%d', self::ENDPOINT, $request->get('id')),  [
            'headers' => [
                'Authorization' => 'Bearer ' .  session()->get('api_key'),
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
            ],
            'json' => ['status' => 0],
        ]);
        return $response->getBody()->getContents();
    }

    public function getStamps()
    {

        try {

            $response = self::executeSweetApi(
                'GET',
                'api/stamps/v1/frontend/stamps',
                []
            );

            return  response()->json([
                'success' => true,
                'data'    => $response->data,
            ]); 
            
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }

    public function getStampsById(Request $request)
    {
        $id = $request->get('ids_stamp');

        try {
                $response = self::executeSweetApi(
                    'GET',
                    'api/stamps/v1/frontend/stamps/'. $id,
                    []
                );

            return  response()->json([
                'success' => true,
                'data'    => $response,
            ]); 
            
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }
}
