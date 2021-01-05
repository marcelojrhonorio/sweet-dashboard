<?php

namespace App\Http\Controllers;

use DB;
use DataTables;
use GuzzleHttp\Client;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ClientErrorResponseException;
use Illuminate\Support\Facades\Log;

class CustomersController extends Controller
{
    const ENDPOINT_CUSTOMERS = 'api/v1/admin/customers';

    const ENDPOINT_RESOURCES = 'api/v1/admin/campaign-resources';

    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('APP_SWEET_API'),
            'headers'  => [
                'cache-control' => 'no-cache',
                'accept'        => 'application/json',
                'content-type'  => 'application/json',
            ]
        ]);
    }

    public function index()
    {
        $url = self::ENDPOINT_RESOURCES . '?q=campaigns|companies&status=0';

        $response = $this->client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('api_key'),
                'cache-control' => 'no-cache',
                'Content-Type'  => 'application/json',
                'accept'        => 'application/json',
            ]
        ]);

        $body = $response->getBody()->getContents();

        $results = \GuzzleHttp\json_decode($body)->results;

        return view('customers.index', [
            'campaigns' => $results->campaigns,
            'companies' => $results->companies,
        ]);
    }

    public function search(Request $request)
    {
        $customers = Customer::select(
            'id',
            'fullname',
            'email',
            'cpf',
            'points',
            'gender',
            'birthdate',
            'state',
            'city',
            'phone_number',
            'confirmed',
            'created_at',
            'changed_password'
        );

        return datatables()
            ->of($customers)
            ->toJson();
    }

    public function export(Request $request)
    {

        $file = 'CUSTOMERS.csv'; 
        $directory = '/var/lib/mysql-files/';
        
        $customers = DB::select("
            SELECT *
            FROM customers_export
            INTO OUTFILE '".$directory.$file."'
            FIELDS ENCLOSED BY '\"'
            TERMINATED BY ';'
            ESCAPED BY '\"'
            LINES TERMINATED BY '\r\n'
        ");

        //return response()->download($directory.$file);
        return 'ok';
    }

    public function destroy(Request $request, $id)
    {
        $customer = Customer::find($id);

        if (empty($customer)) {
            return response()->json([
                'success' => false,
                'data'    => [],
            ], 404);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'data'    => $customer,
        ]);
    }

    public function getPoints($customer_id){
        $points = DB::select(
            "CALL sweet.pontos_sintetico({$customer_id})"
        );
        return $points;
    }

    public function resetPassword($id, Request $request) {
        $customer = Customer::find($id);
        $customer->changed_password = false;
        $customer->save();

        return response()->json([
            'success' => true,
            'data'    => $customer,
        ]);
    }

    public function getIndications($id, $type)
    {
        $indications = DB::select("select id, ip_address, fullname, email, cep, cpf, ddd, phone_number, birthdate, status_indication from sweet.customers where indicated_by = ".$id." and confirmed = 1 and updated_personal_info_at is not null and deleted_at is null");
       
        if(1 == $type) {
            return view('customers.partials.customer-indications')->with(
                'indications', $indications
            );
        }           

        return response()->json([
            'success' => true,
            'data'    => $indications,
        ]);          
    }

    public function updateStatusIndications(Request $request)
    {
        $id = $request->input('customer_id');
        $status = $request->input('status');

        $customer = Customer::find($id);

        if (!empty($customer)) {
           $customer->status_indication = $status;
           $customer->update();

            return response()->json([
                'success' => true,
                'data'    => $customer,
            ]);
        }
    }

   
}
