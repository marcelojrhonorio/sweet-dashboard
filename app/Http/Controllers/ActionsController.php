<?php

namespace App\Http\Controllers;

use DB;
use Log;
use DataTables;
use App\Models\Action;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\ActionTypeMeta;
use App\Jobs\ActionDisabledJob;

class ActionsController extends Controller
{
    const ENDPOINT = 'api/v1/admin/actions';

    protected $client;

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

    public function index(Request $request)
    {
        return view('actions.index')->with([
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

    public function store(Request $request)
    {
        $gender = '';

        if('' == $request->input('filter_gender')) {
            $gender = 'M|F';
        } else if('A' == $request->input('filter_gender')) {
            $gender = 'M|F';
        } else{
            $gender = $request->input('filter_gender');
        }

        $response = $this->client->post(self::ENDPOINT, [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('api_key')
            ],
            'json' => [
                'action_category_id'     => $request->input('category'),
                'action_type_id'         => $request->input('type'),
                'order'                  => $request->input('order'),
                'enabled'                => (!$request->input('exchange_id')) ? $request->input('enabled') : true,
                'action_type_url'        => $request->input('typeUrl'),
                'title'                  => $request->input('title'),
                'description'            => $request->input('description'),
                'grant_points'           => $request->input('points'),
                'path_image'             => (session()->get('imagePS') && (!$request->input('exchange_id'))) ? session()->get('imagePS') : $request->input('path'), 
                'filter_ddd'             => $request->input('filter_ddd'),
                'filter_gender'          => $gender,
                'filter_cep'             => $request->input('filter_cep'),
                'filter_operation_begin' => $request->input('filter_operation_begin'),
                'filter_age_begin'       => $request->input('filter_age_begin'),
                'filter_operation_end'   => $request->input('filter_operation_end'),
                'filter_age_end'         => $request->input('filter_age_end'),
                'exchange_id'            => $request->input('exchange_id') ?? null,
            ],
        ]); 
  
        session()->forget('imagePS');
        session()->forget('imagePathPS');
        session()->forget('imageNamePS');

        $content = $response->getBody()->getContents();
        $decoded = \GuzzleHttp\json_decode($content);

        $res = response()->json($decoded, 201);
        
        // schedule action (Digital Influencer) deactivation
        if($request->input('exchange_id')){  
            $actions_id = $res->original->data->id;

            $exchange_sm = 
                DB::select(' 
                    SELECT *
                    FROM sweet.customer_exchanged_points_sm WHERE id = ' . $request->input('exchange_id') 
            );

            $product_services_id = $exchange_sm[0]->product_services_id;

            $product_services = 
                DB::select('
                    SELECT *
                    FROM sweet.products_services WHERE id = ' . $product_services_id 
            );

            $exibition_time = $product_services[0]->exibition_time;

            $date = now()->addDays($exibition_time);

            //dispatch job 
            $job = (new ActionDisabledJob($actions_id))->onQueue('disabled_action')->delay($date);   
            dispatch($job);             
        } 
  
        return response()->json($decoded, 201);
    }

    public function update(Request $request, $id)
    {
        $gender = '';

        if('' == $request->input('filter_gender')) {
            $gender = 'M|F';
        } else if('A' == $request->input('filter_gender')) {
            $gender = 'M|F';
        } else{
            $gender = $request->input('filter_gender');
        }

        $endpoint = self::ENDPOINT . '/' . $id;

        $data = [
            'action_category_id'     => $request->input('category'),
            'action_type_id'         => $request->input('type'),
            'order'                  => $request->input('order'),
            'enabled'                => $request->input('enabled'),
            'action_type_url'        => $request->input('typeUrl'),
            'title'                  => $request->input('title'),
            'description'            => $request->input('description'),
            'grant_points'           => $request->input('points'),
            'path_image'             => session()->get('imagePS') ?? $request->input('image'),
            'filter_ddd'             => $request->input('filter_ddd'),
            'filter_gender'          => $gender,
            'filter_cep'             => $request->input('filter_cep'),
            'filter_operation_begin' => $request->input('filter_operation_begin'),
            'filter_age_begin'       => $request->input('filter_age_begin'),
            'filter_operation_end'   => $request->input('filter_operation_end'),
            'filter_age_end'         => $request->input('filter_age_end'),
            'exchange_id'            => $request->input('exchange_id') ?? null,
        ];

        $response = $this->client->put($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('api_key')
            ],
            'json' => [
                'action_category_id'     => $request->input('category'),
                'action_type_id'         => $request->input('type'),
                'order'                  => $request->input('order'),
                'enabled'                => $request->input('enabled'),
                'action_type_url'        => $request->input('typeUrl'),
                'title'                  => $request->input('title'),
                'description'            => $request->input('description'),
                'grant_points'           => $request->input('points'),
                'path_image'             => session()->get('imagePS') ?? $request->input('image'),
                'filter_ddd'             => $request->input('filter_ddd'),
                'filter_gender'          => $gender,
                'filter_cep'             => $request->input('filter_cep'),
                'filter_operation_begin' => $request->input('filter_operation_begin'),
                'filter_age_begin'       => $request->input('filter_age_begin'),
                'filter_operation_end'   => $request->input('filter_operation_end'),
                'filter_age_end'         => $request->input('filter_age_end'),
                'exchange_id'            => $request->input('exchange_id') ?? null,
            ],
        ]);

        session()->forget('imagePS');
        session()->forget('imagePathPS');
        session()->forget('imageNamePS');

        $content = $response->getBody()->getContents();
        $decoded = \GuzzleHttp\json_decode($content);

        return response()->json($decoded, 200);
    }

    public function searchFilter(Request $request)
    {        
        $values = $request->get('values');

        return count(self::getFilterUsers($values));
    }

    private static function getFilterUsers($data)
    {  
        $ddd = $data['filter_ddd'];
        $ddd = str_replace("|", ",", $ddd); 

        $cep = $data['filter_cep'];

        $ceps = explode("|", $cep);           

        /** verify condition for apply mask */
        if(8 === strlen($ceps[0])){
            $c = '';
            for ($i=0; $i < count($ceps); $i++) { 
                if($i === 0){
                    $c = $c . self::applyMask($ceps[$i], '##.###-###');  
                } else {
                    $c = $c .'|'. self::applyMask($ceps[$i], '##.###-###');  
                }
            }

            $cep = str_replace("|", "','", $c);

        } elseif(10 === strlen($ceps[0]))
        {
            $cep = str_replace("|", "','", $cep);
        }
                
        $gender = (string) $data['filter_gender'];

        if(('A' === $gender) || ('' === $gender))
            $gender = 'M|F';

        $gender = str_replace("|", "','", $gender); 

        $operation_begin = (string) $data['filter_operation_begin'];        
        $operation_end = (string) $data['filter_operation_end'];        
        $age_begin = $data['filter_age_begin'];        
        $age_end = $data['filter_age_end']; 

        $age = 'YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(c.birthdate)))';

        /* dynamic query filter conditions */
        if('' !== $ddd){
            $query_ddd = ' AND c.ddd IN('. $ddd .')';
        } else {
            $query_ddd = '';
        }

        if('' !== $gender){
            $query_gender = 'c.gender IN(' ."'". $gender."'" .')';
        } else {
            $query_gender = '';
        }

        if('' !== $cep){
            $query_cep = ' AND c.cep IN(' . "'". $cep . "'".')';
        } else {
            $query_cep = '';
        }

        if((('' !== $age_begin) && (0 != $age_begin)) || (('' !== $age_end) && (0 != $age_end))){
            $query_age = ' AND ('. $age .' ' . $operation_begin . ' ' .$age_begin . ' )';

            if($operation_end){
                $query_age = ' AND ('. $age .' ' . $operation_begin . ' ' .$age_begin . ' AND '. $age . ' ' . $operation_end . ' '. $age_end .')';
            }
        } else {
            $query_age = '';
        }

        if(('' === $query_gender) && ('' === $query_ddd) && ('' === $query_cep) && ('' === $query_age)) {
            $query_where = '';
        } else {  
            $query_where = ' WHERE ';
        } 
        
        $users = 
            DB::select('
                SELECT DISTINCT c.id
                FROM sweet.customers c'. $query_where . $query_gender . $query_ddd  . $query_cep . $query_age 
        );
       
        return $users;
    }

    private static function applyMask($val, $mask)
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

    public function destroy($id)
    {
        $endpoint = self::ENDPOINT . '/' . $id;

        $response = $this->client->delete($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('api_key')
            ]
        ]);

        $content = $response->getBody()->getContents();
        $decoded = \GuzzleHttp\json_decode($content);

        return response()->json($decoded, 200);
    }

    public function search(Request $request)
    {
        $actions = Action::with([
            'actionCategory:id,name',
            'actionType:id,name',
            'actionTypeMetas',
        ])->orderBy('order', 'asc');

        return datatables()->of($actions)->toJson();
    }

    public function getById(int $id)
    {
        $action = Action::find($id) ?? null;

        if($action) {
            $actionTypeMeta = ActionTypeMeta::where('action_id', $action->id)->first() ?? null; 
            return [
                'action' => $action,
                'actionTypeMeta' => $actionTypeMeta,
            ];
        }

        return null;
    }
}
