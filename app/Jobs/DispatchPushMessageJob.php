<?php

namespace App\Jobs;

use DB;
use Log;
use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use App\Traits\GetAppMessageTrait;
use App\Models\MobileApp\AppMessage;
use Illuminate\Queue\SerializesModels;
use App\Models\MobileApp\AppMessageType;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\MobileApp\AppNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\MobileApp\AppAllowedCustomer;

class DispatchPushMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 4000;

    use GetAppMessageTrait;

    private $messageId;
    private $notificationId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notificationId, $messageId)
    {
        $this->messageId = $messageId;
        $this->notificationId = $notificationId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = AppMessage::find($this->messageId) ?? null;

        if(false === isset($message->id) || (null === $message->id)){
            Log::debug("[APP_PUSH] Mensagem não encontrada.");
            return;
        } 
        
        $type = AppMessageType::find($message->message_types_id);       
 
        if(false === isset($type->id) || (null === $type)){
             Log::debug("[APP_PUSH] Tipo não encontrado.");
             return;
         } 
 
         if(null !== $message->opened_at){
             Log::debug("[APP_PUSH] Mensagem já lida. Não será enviada.");
             return;           
         }
 
         $customer = Customer::find($message->customers_id) ?? null;
 
         if(false === isset($customer->id) || (null === $customer)){
             Log::debug("[APP_PUSH] Usuário não encontrado.");
             return;
         } 

         if(null === $customer->onesignal_id){
            Log::debug("[APP_PUSH] OneSignalId não encontrado.");
            return;
        } 

         $notification = AppNotification::find($this->notificationId) ?? null;
 
         if((null === $notification) || (null !== $notification->job_canceled_at)){
             Log::debug("[APP_PUSH] Notificação foi cancelada.");
             return;
         } 
 
         $allowed = 
            DB::select('
                SELECT *
                FROM sweet.app_allowed_customers WHERE deleted_at IS NULL AND customers_id='.$message->customers_id
            );
         
         
         if((null === $allowed) || ($allowed[0]->access_expired_at < Carbon::now()->toDateTimeString())){
            Log::debug("[APP_PUSH] O usuário não tem permissão de acesso ao app.");
            return;
        }          
 
         $oneSignalData = [
             'app_id' => '5e596715-7eb0-47a1-85e6-27cb19f81255',
             'headings' => [
                 'pt' => self::replaceStringVariables($message->customers_id, $type->push_title),
             ],
             'contents' => [
                 'en' => self::replaceStringVariables($message->customers_id, $type->push_text),
                 'pt' => self::replaceStringVariables($message->customers_id, $type->push_text),
             ],
             'android_accent_color' => '0060c6c5',
             'data' => [
                 'message_text' => self::replaceStringVariables($message->customers_id, $type->text),
                 'link' => $message->link,
                 'image' => $type->image_path,
                 'message_id' => (string) $message->id,
                 'customers_id' => (string) $customer->id,
             ],
             'include_player_ids' => [$customer->onesignal_id],
         ];
 
         $fields = json_encode($oneSignalData);
             
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
         curl_setopt($ch, CURLOPT_HEADER, FALSE);
         curl_setopt($ch, CURLOPT_POST, TRUE);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
         $response = curl_exec($ch); 

         $message->response_onesignal_api = $response;
         $message->update();

         $json = json_decode($response, true);
         
         /**
          * O campo 'already_queue' deve ser incrementado quando 'response_onesignal_api' retornar um ID
          */
        
         if(!isset($json['errors']))
         {
            $notification = AppNotification::find($this->notificationId);
            $notification->already_queue = $notification->already_queue + 1;
            $notification->update();
         }  
         
         $this->refreshStatusNotification();             
 
         curl_close($ch);         
    }

    private function refreshStatusNotification()
    {
        /**
        *  CONDIÇÕES
        *  Não Enviado: 'sweet.app_notification.already_queue' === 0
        *  Enviando: 'sweet.app_notification.already_queue' > 0 && 'sweet.app_notification.already_queue' < total 
        *  Enviado: 'sweet.app_notification.already_queue' === 'sweet.app_notification.total'
        */   

        $notification = AppNotification::find($this->notificationId);

        $not_sent = ($notification->already_queue == 0);
        $sending = (($notification->already_queue > 0) && ($notification->already_queue < $notification->total));
        $sent = (($notification->already_queue == $notification->total) && ($notification->already_queue != 0));

        if($not_sent){
            $notification->status = 'Não Enviado';
            $notification->update();
        }

        if($sending){
            $notification->status = 'Enviando';
            $notification->update();
        }

        if($sent){
            $notification->status = 'Enviado';
            $notification->update();
        }

        return;
    }

    
}
