<?php

namespace App\Http\Controllers\MobileApp;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Traits\SweetStaticApiTrait;
use App\Http\Controllers\Controller;
use App\Jobs\DispatchPushMessageJob;
use App\Models\MobileApp\AppMessage;
use App\Models\MobileApp\AppMessageType;
use App\Models\MobileApp\AppNotification;
use App\Models\ResearchesSponsored\Research;
use App\Models\IncentiveEmails\IncentiveEmail;

class AppNotificationController extends Controller
{
    use SweetStaticApiTrait;

    public function index()
    {
        return view('app_mobile.index');
    }

    public function search()
    {
        $appNotification = AppNotification::select(
            'id',
            'title',
            'total',
            'already_queue',
            'status'
        )->orderBy('id', 'desc');
        
        return datatables()->of($appNotification)->toJson();
    }

    public function create()
    {
        return view('app_mobile.create')->with([
            'action'     => 'create',
            'types'      => self::getMessageTypes(),
            'researches' => self::getReseraches(),
            'incentive_email' => self::getIncentiveEmail(),
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

    private static function getReseraches()
    {
        $researches = Research::where('enabled', 1)->whereNull('deleted_at')->get() ?? null;
        return $researches;
    }

    private static function getMessageTypes()
    {     
        $appMessageType = AppMessageType::where('title', '<>', 'ssi')->get();

        return $appMessageType;
    }

    public function store(Request $request)
    {
        $data = [
            'title' => $request->get('title') ?? '',
            'filter_ddd' => (string) $request->get('filter_ddd') ?? '',
            'filter_gender' => $request->get('filter_gender') ?? '',
            'filter_cep' => $request->get('filter_cep') ?? '',
            'filter_operation_begin' => $request->get('filter_operation_begin') ?? '',
            'filter_operation_end' => $request->get('filter_operation_end') ?? '',
            'link_notification' => $request->get('link_notification') ?? '',
            'code_incentive_email' => $request->get('code_incentive_email') ?? '',
            'research_selected' => $request->get('research_selected') ?? '',
            'codetype' => $request->get('codetype') ?? '',
            'scheduling' => $request->get('scheduling') ?? '',
        ];

        if (!empty($request->get('filter_age_begin'))) {
            $data['filter_age_begin'] = $request->get('filter_age_begin');
        } else {
            $data['filter_age_begin'] = '';
        }

        if (!empty($request->get('filter_age_end'))) {
            $data['filter_age_end'] = $request->get('filter_age_end');
        } else {
            $data['filter_age_end'] = '';
        }
        
        return view('app_mobile.create')->with([
            'data'    => $data,
            'researches' => self::getReseraches(),
            'incentive_email' => self::getIncentiveEmail(),
            'action'  => 'create',
            'types'   => self::getMessageTypes(),
            'operations' => [
                '>'  => 'Maior >',
                '>=' => 'Maior igual >=',
                '='  => ' Igual =',
                '<'  => 'Menor <',
                '<=' => 'Menor igual <=',
                '<>' => 'Diferente <>',
            ],
            'filter_users' => count(self::getFilterUsers($data)),
        ]);
    }

    private static function getIncentiveEmail()
    {
        $incentiveEmails = IncentiveEmail::all();
        return $incentiveEmails;
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
        $test = env('TEST_PUSH_APP');   

        $age = 'YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(c.birthdate)))';

        /* dynamic query filter conditions */
        $not_in = ' AND c.email NOT IN ('. "'". $test ."'".')';  

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

        if(('' !== $age_begin) || ('' !== $age_end)){
            $query_age = ' AND ('. $age .' ' . $operation_begin . ' ' .$age_begin . ' AND '. $age . ' ' . $operation_end . ' '. $age_end .')';
        } else {
            $query_age = '';
        }

        if(('' === $query_gender) && ('' === $query_ddd) && ('' === $query_cep) && ('' === $query_age)) {
            $query_where = '';
        } else {
            $query_where = ' WHERE ';
        }  

        $deleted_at = ' AND al.deleted_at is null';       
                
        $users = 
            DB::select('
                SELECT DISTINCT c.id
                FROM sweet.app_allowed_customers al 
                    INNER JOIN sweet.customers c
                        ON c.id = al.customers_id'. $query_where . $query_gender . $query_ddd  . $query_cep . $query_age . $not_in . $deleted_at
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

    public function createNotification(Request $request)
    {
        $values = $request->get('values');
        $action = $request->get('action');  

        $notification = self::saveNotification($action, $values);

        if($notification){
            return response()->json([
                'success' => true,
                'data'    => $notification,
            ]);
        }
        
        return response()->json([
            'success' => false,
            'data'    => [],
        ]);
    }

    private static function saveNotification($action, $values)
    {
        $type = AppMessageType::find((int) $values['title']);

        $test = env('TEST_PUSH_APP');
        
        if('test' === $action){
            $users = 
                DB::select("
                    SELECT c.id
                    FROM sweet.app_allowed_customers al 
                        INNER JOIN sweet.customers c
                        ON c.id = al.customers_id WHERE c.email in ('".$test."')"
                );        
        } else {
            $users = self::getFilterUsers($values);
        }

        $messages = [];

        foreach ($users as $user) 
        {      
           $appMessage = AppMessage::where('customers_id', $user->id)
                                   ->where('real_link', self::getLink($user->id, $values['url_link'], $values['type']))
                                   ->first() ?? null;

            /*  
                Logic to check users who have a message with the incentive email code 
                (returned an error or didn't open the message) and those who don't have 
                a message with the email code.
            */

           if(null !== $appMessage) 
           {
                $json = json_decode($appMessage->response_onesignal_api, true);

                if(null === $appMessage->opened_at || isset($json['errors'])){
                    array_push($messages, $appMessage);
                }
                
           } else {
               $appMessage = new AppMessage();
               $appMessage->customers_id = $user->id;
               $appMessage->message_types_id = $values['title'];
               $appMessage->link = '';
               $appMessage->real_link = self::getLink($user->id, $values['url_link'], $values['type']);
               $appMessage->save();  
 
               $appMessage->link = env('STORE_URL') . '/app-messages/' . $appMessage->id;
               $appMessage->update();                 

               array_push($messages, $appMessage);
           }           
        }

        $appNotification = new AppNotification();

        $dateFormat = self::getDate(Carbon::now()->toDateTimeString());

        if('test' === $action) {  
            $appNotification->title =  '[' . $type->title . '] Teste enviado em '. $dateFormat['date'] .' às ' . $dateFormat['hour'] . '.';
            $appNotification->total = substr_count($test, '@'); 
        } else {
            $appNotification->title =  '[' . $type->title . '] Push enviado em '. $dateFormat['date'] .' às ' . $dateFormat['hour'] . '.';
            $appNotification->total = $values['filter_users']; 
        }        
               
        $appNotification->status = 'Não Enviado';
        $appNotification->already_queue = 0;
        $appNotification->save();
        
        $scheduling = (int) $values['scheduling'];              
        
        if(('send' === $action) || ('test' === $action))
        {
            foreach($messages as $message)
            {
                $message->app_notifications_id = $appNotification->id;
                $message->update();   

                if(is_null($message->opened_at)) {
                    $job = self::getJob($appNotification, $message->id, $scheduling, $action, $type);  
                    dispatch($job);  
                }                
            }  
        } else 
        {
            foreach($messages as $message)
            {
                $message->app_notifications_id = $appNotification->id;
                $message->update();  
            }  
        }  

        return $appNotification;
    }

    private static function getJob($appNotification, $messageId, $scheduling, $action, $type)
    {               
        switch ($scheduling) {
            case 0:
                $date = now();
                break;

            case 30:
                $date = now()->addMinutes(30);
                break;
            
            case 100:
                $date = now()->addMinutes(10);
                break;

            default:
                $date = now()->addHours($scheduling);
                break;
        } 
        
        if(0 != $scheduling) {
            $formatDate = self::getDate($date);

            if('test' === $action) {
                $appNotification->title = '[' . $type->title . '] Teste agendado para '. $formatDate['date'] .' às ' . $formatDate['hour'] . '.';
            } else {
                $appNotification->title = '[' . $type->title . '] Push agendado para '. $formatDate['date'] .' às ' . $formatDate['hour'] . '.';
            }
           
            $appNotification->update();
        }

        return (new DispatchPushMessageJob($appNotification->id, $messageId))->onQueue('app_message_dash')->delay($date);         
    }

    private static function getDate($data)
    {
        $data = explode(" ", $data);

        $date = explode("-", $data[0]);
        $date = $date[2] . '/' . $date[1] . '/' . $date[0];
        $hour = $data[1];

        $format = [
            'date' => $date,
            'hour' => $hour,
        ];
        
        return $format;
    }

    private static function getLink(string $customers_id, string $code, int $type)
    {
        if(1 == $type) {
            $url = env('STORE_URL') . '/incentive-emails/postback?customers_id=##id_panel##&incentive_email_code='. $code;
            $url = str_replace("##id_panel##", $customers_id, $url);
        } else {
            $research = Research::find($code) ?? null;

            if($research && $research->enabled) {
                $url = env('SWEETBONUS_URL') . '/research/' . $research->final_url . '/' . $customers_id;
            }            
        }        
        
        return $url;
    }

    public function refresh(Request $request)
    {
        $id = $request->get('id');

        $appNotification = AppNotification::find($id) ?? null;

        if(is_null($appNotification)){
            return response()->json([
                'success' => false,
                'data'    => [],
            ]);
        }

        $not_sent = ($appNotification->already_queue == 0);
        $sending = (($appNotification->already_queue > 0) && ($appNotification->already_queue < $appNotification->total));
        $sent = (($appNotification->already_queue == $appNotification->total) && ($appNotification->already_queue != 0));

        if($not_sent){
            $appNotification->status = 'Não Enviado';
            $appNotification->update();
        }

        if($sending){
            $appNotification->status = 'Enviando';
            $appNotification->update();
        }

        if($sent){
            $appNotification->status = 'Enviado';
            $appNotification->update();
        }
        
        return response()->json([
            'success' => true,
            'data'    => $appNotification,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $notification_id = $request->get('id');
        $action = $request->get('action');

        $notification = AppNotification::find($notification_id) ?? null;

        if(!is_null($notification->job_canceled_at)){
            return response()->json([
                'success' => false,
                'data'    => $notification,
                'status'  => 'canceled',
            ]);
        }

        if('Enviado' === $notification->status){
            return response()->json([
                'success' => false,
                'data'    => $notification,
                'status'  => 'sent',
            ]);
        }

        if('again' == $action) {
            $appMessages = AppMessage::where('app_notifications_id', '=', $notification_id)
                        ->where('response_onesignal_api', 'like', '%error%')
                        ->orWhereNull('response_onesignal_api')  
                        ->get();
        } else {
            $appMessages = AppMessage::where('app_notifications_id', '=', $notification_id)
                        ->where('response_onesignal_api', 'like', '%error%')
                        ->orWhereNull('response_onesignal_api')  
                        ->get();
        }

        foreach($appMessages as $message)
        {     
            $job = (new DispatchPushMessageJob($notification_id, $message->id))->onQueue('app_message_dash');
            dispatch($job);  
        }
        
        return response()->json([
            'success' => true,
            'data'    => $appMessages,
        ]);
    }

    public function cancel(Request $request)
    {
        $id = $request->get('id');
        $notification = AppNotification::find($id) ?? null;

        if(is_null($notification)){
            return response()->json([
                'success' => false,
                'data'    => [],
            ]);
        }

        $notification->job_canceled_at = Carbon::now()->toDateTimeString();
        $notification->update();

        return response()->json([
            'success' => true,
            'data'    => $notification,
        ]);
    }

}
