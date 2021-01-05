<?php

namespace App\Http\Controllers\Exchanges\SocialNetwork;

use DataTables;
use App\Models\Action;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\SweetStaticApiTrait;
use App\Http\Controllers\Controller;
use App\Models\CustomerExchangedPointsSm;

class SocialNetworkExchangesController extends Controller
{
    use SweetStaticApiTrait;

    public function index (Request $request)
    {
        return view('exchanges.social-network.index')->with([
            'operations' => [
                '>'  => 'Maior >',
                '>=' => 'Maior igual >=',
                '='  => ' Igual =',
                '<'  => 'Menor <',
                '<=' => 'Menor igual <=',
                '<>' => 'Diferente <>',
            ],
            'filter_users' => 0,
        ]);
    }

    public function search (Request $request) 
    {
        $exchanges = DB::table('customers')
            ->join('customer_exchanged_points_sm', 'customer_exchanged_points_sm.customers_id', '=', 'customers.id')
            ->select('customer_exchanged_points_sm.id as id', 
                     'customers.id as customers_id', 
                     'customers.fullname as fullname', 
                     'customers.email as email', 
                     'customer_exchanged_points_sm.subject as subject', 
                     'customer_exchanged_points_sm.profile_link as profile_link', 
                     'customer_exchanged_points_sm.profile_picture as profile_picture', 
                     'customer_exchanged_points_sm.created_at as created_at', 
                     'customer_exchanged_points_sm.status as status')
            ->orderBy('customer_exchanged_points_sm.id', 'DESC');

        return datatables()->of($exchanges)->toJson();
    }
    
    public function update(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        $customerExchangedPointsSm = CustomerExchangedPointsSm::find($id) ?? null;

        if('disapproved' === $status){            
            $customer = Customer::find($customerExchangedPointsSm->customers_id) ?? null;
            $customer->points = $customer->points + $customerExchangedPointsSm->points;
            $customer->update();
        } 

        $customerExchangedPointsSm->status = $status;
        $customerExchangedPointsSm->update();
   
        return response()->json([
            'success' => true,
            'data'    => $customerExchangedPointsSm,
            'action'  => Action::where('exchange_id', $customerExchangedPointsSm->id)->first() ?? null ,
        ]);
    }
}
