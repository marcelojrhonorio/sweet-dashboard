<?php
/**
 * @todo Add docs.
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\ClientErrorResponseException;

/**
 * @todo Add docs.
 */
class ClairvoyantController extends Controller
{

    const ENDPOINT = '/api/v1/frontend/clairvoyant';
    const ENDPOINT_LIST = '/api/v1/frontend/clairvoyant/list';

    private $client;

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

    public function create(Request $req){
        $validator = Validator::make($req->all(), [
            'first_name'    => 'required|max:60',
            'email_address' => 'email|required|max:40',
            'ddd_home'      => 'nullable|max:2',
            'phone_home'    => 'nullable|max:14',
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
            ], 422);
        }


        $data = [
            'first_name'    =>   $req->input('first_name'),
            'email_address' =>   $req->input('email_address'),
            'ddd_home'      =>   $req->input('ddd_home'),
            'phone_home'    =>   $req->input('phone_home'),
        ];

        $response = $this->client->request('POST', self::ENDPOINT, [
            'headers' => [
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
            ],
            'json' => $data,
        ]);

        $data = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        
        $url='http://www.estrelafone.com.br/pt/clientV2/createClientV2.json?civilite=X&emailAddress='.$req->input('email_address').'&nom='.$req->input('first_name').'&prenom='.$req->input('first_name').'&dob=XX-XX-XXXX&ipAddress=XXX.XXX.XXX.XX&telephoneNumber=55'.$req->input('ddd_home').$req->input('phone_home').'&origin=Astrocentro1&c=238&s=ca_astra_br&conversation=1&countryCode=BR&telCountry=BR';


        $client2 = new \GuzzleHttp\Client();
        $res = $client2->get($url);

        return \GuzzleHttp\json_encode($data);
    }

    public function cadastros(Request $request){
        $response = $this->client->get(self::ENDPOINT_LIST, [
            'headers' => [
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
            ]
        ]);

        $cadastros = \GuzzleHttp\json_decode($response->getBody()->getContents())->results;

        return view('clairvoyant.cadastros', compact('cadastros'));      
    }

    public function index(Request $request)
    {
        return view('clairvoyant.index');
    }
}
