<?php

namespace App\Http\Controllers\PointsValidation;

use DB;
use DataTables;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Traits\SweetStaticApiTrait;
use App\Http\Controllers\Controller;
use App\Models\PointsValidation\EmailForwarding\CustomersForwarding;
use App\Models\PointsValidation\EmailForwarding\CustomersForwardingEmail;
use App\Models\PointsValidation\EmailForwarding\CustomersForwardingPrint;
use App\Models\PointsValidation\EmailForwarding\CustomersForwardingStatus;

class EmailForwardingController extends Controller
{
    use SweetStaticApiTrait;

    public function index()
    {
        return view('points_validation.email_forwarding.index');
    }
 
    public function search()
    {
        self::verifyStatusForwarding();
       
        $users = CustomersForwardingStatus::get();

        return datatables()->of($users)->toJson();
    }

    private static function verifyStatusForwarding()
    {
        $users = CustomersForwardingStatus::get();

        //verificação para atualização do status geral
        foreach ($users as $user) 
        {
            $customersForwarding = CustomersForwarding::where('customers_id', $user->customers_id)->get();

            $flag = 0;

            foreach ($customersForwarding as $cf) 
            {
                $customersForwardingEmail = CustomersForwardingEmail::where('customers_forwarding_id', $cf->id)->get();

                foreach ($customersForwardingEmail as $cfe) 
                {
                    if(null == $cfe->status) {
                        $flag++;
                    }                  
                }               
            }

            if($flag === 0) {
                $user->status = true;
                $user->update();
            } else {
                
                $user->status = false;
                $user->update();
            }
        }

        return;
    }

    public function edit(int $id)
    {
        $customersForwarding = CustomersForwarding::where('customers_id', $id)->get();

        $customer = Customer::find($id) ?? null;

        $array = []; 

        foreach ($customersForwarding as $cf) 
        {
            $customersForwardingEmail = CustomersForwardingEmail::where('customers_forwarding_id', $cf->id)->whereNull('status')->get();
            $customersForwardingPrint = CustomersForwardingPrint::where('customers_forwarding_id', $cf->id)->get();

            $data = [
                'name' => $customer->fullname,
                'customers_id' => $customer->id,
                'customersForwarding' => $cf,
                'customersForwardingEmail' => $customersForwardingEmail,
                'customersForwardingPrint' => $customersForwardingPrint
            ];

            array_push($array, $data);
        }
        
        return view('points_validation.email_forwarding.edit')->with([
            'action' => 'edit',
            'datas' => $array,
        ]);
    }

    public function forwardingOk(Request $request)
    {
        $id = $request->input('id');
        $customers_id = $request->input('customers_id');
        
        $customersForwardingEmail = CustomersForwardingEmail::find($id) ?? null;

        if((null == $customersForwardingEmail->status) || (0 == $customersForwardingEmail->status)) {
            self::updateCustomersPoints($customers_id);
            $customersForwardingEmail->status = 1;
            $customersForwardingEmail->update();

            return response()->json([
                'success' => true,
                'data'    => $customersForwardingEmail,
            ]);
        }

        return response()->json([
            'success' => false,
            'data'    => $customersForwardingEmail,
        ]);
    }

    public function forwardingNot(Request $request)
    {
        $id = $request->input('id');
        $customers_id = $request->input('customers_id');
        
        $customersForwardingEmail = CustomersForwardingEmail::find($id) ?? null;

        if((1 == $customersForwardingEmail->status) || (null == $customersForwardingEmail->status)) {
            if(1 == $customersForwardingEmail->status) {
                self::subtractCustomersPoints($customers_id);
            }

            $customersForwardingEmail->status = 0;
            $customersForwardingEmail->update();

            return response()->json([
                'success' => true,
                'data'    => $customersForwardingEmail,
            ]);
        }

        return response()->json([
            'success' => false,
            'data'    => $customersForwardingEmail,
        ]);
    }

    private static function subtractCustomersPoints($customers_id)
    {
        $customer = Customer::find($customers_id) ?? null;

        if($customer){
            $customer->points = $customer->points - 5;
            $customer->update();
        }

        return $customer;
    }

    private static function updateCustomersPoints($customers_id)
    {
        $customer = Customer::find($customers_id) ?? null;

        if($customer){
            $customer->points = $customer->points + 5;
            $customer->update();
        }

        return $customer;
    }

}
