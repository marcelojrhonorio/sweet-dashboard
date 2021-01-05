<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Services\AllInTransacionalService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class RelationshipRuleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1000;

    private $_relationshipRulePath;
    private $_customer_id;
    private $_relationshipRuleFileSubject;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $relationshipRuleFileName = null,string $relationshipRuleFileSubject = null, string $customer_id = null)
    {
        //
        $this->_customer_id                 = $customer_id;
        $this->_relationshipRulePath  = storage_path().'/app/public/bonus/relationship-rules/html/'.$relationshipRuleFileName;
        $this->_relationshipRuleFileSubject = $relationshipRuleFileSubject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $c = Customer::find($this->_customer_id);

        $allInService = new AllInTransacionalService();
        $allInToken = $allInService::getToken();

        $curl = curl_init("https://transacional.allin.com.br/api/?method=enviar_email&output=json&encode=UTF8&token={$allInToken}");

        $file =(string) file_get_contents($this->_relationshipRulePath);
        $view = view('emails.relationshiprule')->with(['external'=>$file])->render();

        // Enviar email
        $json = [
            'nm_envio'          => $c->fullname,
            'nm_email'          => $c->email,
            'nm_subject'        => $this->_relationshipRuleFileSubject,
            'nm_remetente'      => 'Sweet Bonus',
            'email_remetente'   => 'envio@sweetbonusclub.com',
            'nm_reply'          => 'envio@sweetbonusclub.com',
            'dt_envio'          => date('Y-m-d'),
            'hr_envio'          => date('H:i'),
            'html'              => base64_encode($view),
        ];

        Log::debug('[RELATIONSHIP_RULE] subject: ' . $this->_relationshipRuleFileSubject);

        $json = json_encode($json);

        $curl_post_data = ['dados' => $json];

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $curl_response = curl_exec($curl);
        Log::debug(print_r($curl_response,1));
        curl_close($curl);

    }
}
