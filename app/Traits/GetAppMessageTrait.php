<?php

namespace App\Traits;

use Log;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\MobileApp\AppMessage;
use App\Models\MobileApp\AppMessageType;

trait GetAppMessageTrait
{
    private static function handleGetMessages (int $customerId) 
    {
        $appMessages = AppMessage::where('customers_id', '=', $customerId)->orderBy('created_at', 'desc')->get();

        $formatedMessage = [];

        foreach($appMessages as $message){
            $appMessageType = AppMessageType::find($message->message_types_id);
            $customer = Customer::find($customerId);

            $data = [
                'customers_id' => $message->customers_id,
                'created_at' => (string) $message->created_at,
                'opened_at' => $message->opened_at,
                'body' => [
                    'message_id' => (string) $message->id,
                    'message_text' => $appMessageType->text,
                    // 'link' => $message->link,
                    'link' => str_replace("##nm_id##", $customer->id, $message->link),
                    'image' => $appMessageType->image_path,        
                ], 
                'push' => [
                    // 'title' => $appMessageType->push_title,
                    'title' => str_replace("##nm_name##", explode(' ', $customer->fullname)[0], $appMessageType->push_title),
                    'text' => $appMessageType->push_text,
                ],
            ];
            array_push($formatedMessage, $data);
        }

        return $formatedMessage;
    }

    /**
     * Provisional disabled method.
     */
    private static function replaceStringVariables (int $customerId, string $string)
    {
        $customer = Customer::find($customerId);

        $string = str_replace("##nm_name##", explode(' ', $customer->fullname)[0], $string);
        $string = str_replace("##nm_id##", $customer->id, $string);

        return $string;
    }
}