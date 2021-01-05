<?php

namespace App\Http\Controllers\Exchanges;

use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\SweetStaticApiTrait;
use App\Http\Controllers\Controller;

class ExchangesController extends Controller
{
    use SweetStaticApiTrait;

    public function index (Request $request)
    {
        return view('exchanges.index');
    }

    public function store (Request $request)
    {
        return 'ok';
    }

    public function update (Request $request, $id)
    {
        $exchange = DB::table('customer_exchanged_points')
            ->where('id', $id)
            ->first();

        DB::table('customer_exchanged_points')
            ->where('id', $exchange->id)
            ->update([
                'points'                  => $request->input('points'),
                'product_services_id'     => $request->input('product_id'),
                'status_id'               => $request->input('status_id'),
                'tracking_code'           => $request->input('tracking_code'),
                'delivery_forecast'       => $request->input('delivery_forecast') == '' ? null : $request->input('delivery_forecast'),
                'address'                 => $request->input('address'),
                'number'                  => $request->input('number'),
                'reference_point'         => $request->input('reference'),
                'neighborhood'            => $request->input('neighborhood'),
                'city'                    => $request->input('city'),
                'state'                   => $request->input('state'),
                'cep'                     => $request->input('cep'),
                'additional_information'  => $request->input('additional_information'),
            ]);

        
        DB::table('customers')
            ->where('id', $exchange->customers_id)
            ->update([
                'phone_number' => $request->input('customer_phone'),
                'ddd'          => $request->input('customer_ddd'),
            ]);

        return response()->json([
            'success' => true,
            'data' => [],
        ]);
    }

    public function updateProduct (Request $request)
    {
        $exchangeId = $request->query('exchange_id');
        $selectedProductId = $request->query('selected_product_id');

        $productService = DB::table('products_services')
            ->where('id', $selectedProductId)
            ->first();

        $exchange = DB::table('customer_exchanged_points')
            ->where('id', $exchangeId)
            ->first();

        $customer = DB::table('customers')
            ->where('id', $exchange->customers_id)
            ->first();

        /**
         * 1. Credita os pontos de volta para o customer
         * 2. Debita os pontos da troca do novo produto
         * 3. Atualiza o ID e os pontos do produto na tabela de troca
         */

        DB::table('customers')
            ->where('id', $customer->id)
            ->update(['points' => ($customer->points + $exchange->points)]);
        
        DB::table('customers')
            ->where('id', $customer->id)
            ->update(['points' => (($customer->points + $exchange->points) - $productService->points)]);

        DB::table('customer_exchanged_points')
            ->where('id', $exchangeId)
            ->update([
                'product_services_id' => $productService->id,
                'points' => $productService->points,   
            ]);    

        return response()->json([
            'success' => true,
            'data' => [],
        ]);
    }

    public function destroy (Request $request, $id)
    {
        return 'ok';
    }

    public function edit (Request $request, $id)
    {
        $customerExchangedPoint = $this->getCustomerExchangedPoint($id);
        $productServices        = $this->getProductServices();
        $exchangePointsStatus   = $this->getExchangePointsStatus();
        $states                 = $this->getState();

        // caso o usuário já tenha sido excluído da base
        if (null === $customerExchangedPoint->customer) {
            $customer = DB::table('customers')->where('id', $customerExchangedPoint->customers_id)->get();
            $customerExchangedPoint->customer = $customer[0];
        }

        $year  = substr($customerExchangedPoint->customer->birthdate, 0, 4);
        $month = substr($customerExchangedPoint->customer->birthdate, 5, 2);
        $day   = substr($customerExchangedPoint->customer->birthdate, 8, 2);

        $customerExchangedPoint->customer->birthdate = ($day . '/' . $month . '/' . $year);
        $customerExchangedPoint->customer->cpf = $this->applyMask($customerExchangedPoint->customer->cpf, '###.###.###-##');

        return view('exchanges.edit')->with([
            'customer_exchanged_point' => $customerExchangedPoint,
            'products_services'        => $productServices,
            'exchanged_points_status'  => $exchangePointsStatus,
            'states'                   => $states,
        ]);
    }

    public function search (Request $request) 
    {
        $exchanges = DB::table('v_exchanged_points')
            ->orderBy('id', 'DESC');

        return datatables()->of($exchanges)->toJson();
    }

    public function updateStatus (Request $request) 
    {
        $exchange = $this->getCustomerExchangedPoint($request->input('exchange_id'));
        $selectedStatusId = $request->input('selected_status_id');

        DB::table('customer_exchanged_points')
            ->where('id', $exchange->id)
            ->update(['status_id' => $selectedStatusId]);

        if (8 == $selectedStatusId) {
            DB::table('customer_exchanged_points')
                ->where('id', $exchange->id)
                ->update(['points'  => 0]);
            
            DB::table('customers')
                ->where('id', $exchange->customers_id)
                ->update(['points' => ($exchange->customer->points + $exchange->points)]);
        }

        // reversão de troca de pontos
        
        if(8 == $exchange->status_id) {
            DB::table('customer_exchanged_points')
                ->where('id', $exchange->id)
                ->update(['points'  => $exchange->points + $exchange->product_service->points]);

            DB::table('customers')
                ->where('id', $exchange->customers_id)
                ->update(['points' => ($exchange->customer->points - $exchange->product_service->points)]);
        }

        return response()->json([
            'success' => true,
            'data' => [],
        ]);
    }

    private function getCustomerExchangedPoint ($id)
    {
        $response = self::executeSweetApi(
            'GET',
            '/api/exchange/v1/frontend/exchanged-points/' . $id,
            []
        );

        return $response;
    }

    private function getProductServices ()
    {
        $productServices = DB::table('products_services')
            ->orderBy('title')
            ->get();

        return $productServices;
    }

    private function getExchangePointsStatus ()
    {
        $status = DB::table('exchanged_points_status')->get();

        return $status;
    }

    private function getState ()
    {
        return $states = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 
        'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
    }

    private function applyMask($val, $mask)
    {
        $maskared = '';
        $k = 0;

        for($i = 0; $i<=strlen($mask)-1; $i++)
        {
            if($mask[$i] == '#')
            {
                if(isset($val[$k]))
                $maskared .= $val[$k++];
            }

            else
            {
                if(isset($mask[$i]))
                $maskared .= $mask[$i];
            }
        }

        return $maskared;
    }

}