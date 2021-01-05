<?php

namespace App\Http\Controllers;

use Cache;
use DataTables;
use App\Models\Action;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\ActionTypeMeta;
use App\Models\CampaignFieldType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ClientErrorResponseException;

class CampaignsController extends Controller
{
    const ENDPOINT = 'api/v1/admin/campaigns';

    const ENDPOINT_TYPES = 'api/v1/admin/campaign-types';

    const ENDPOINT_RESOURCES = 'api/v1/admin/campaign-resources';

    const ENDPOINT_CATEGORIES = 'api/v1/admin/actions/categories';

    const ENDPOINT_CAMPAIGN_TYPES = 'api/v1/admin/actions/types';

    private $client;

    private $headers = [];

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

    private function getDataForm()
    {
        $campaignsDataCache = Cache::remember('CampaignsDataCache', 22 * 60, function() {

            // get all data and caching results
            $response = $this->client->get(self::ENDPOINT_RESOURCES, [
                'headers' => [
                    'Authorization' => 'Bearer ' .  session()->get('api_key'),
                    'Content-Type' => 'application/json',
                    'cache-control' => 'no-cache',
                    'accept' => 'application/json',
                ]
            ]);

            $json = \GuzzleHttp\json_decode($response->getBody()->getContents())->results;

            return [
                'types' => $json->campaignsType,
                'companies' => $json->companies,
                'domains' => $json->domains,
                'clusters' => $json->clusters,
                'operations' => [
                    '>' => 'Maior >',
                    '>=' => 'Maior igual >=',
                    '=' => ' Igual =',
                    '<' => 'Menor <',
                    '<=' => 'Menor igual <=',
                    '<>' => 'Diferente <>',
                ],
            ];

        });

        return $campaignsDataCache;
    }

    public function index()  : \Illuminate\View\View
    {
        $response = $this->client->get(self::ENDPOINT_RESOURCES . '?q=types|companies&status=0', [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('api_key'),
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
            ]
        ]);

        $result = \GuzzleHttp\json_decode($response->getBody()->getContents())->results;

        return view('campaigns.index', [
            'campaignsType' => $result->campaignsType,
            'companies' => $result->companies,
            'operations' => [
                '>' => 'Maior >',
                '>=' => 'Maior igual >=',
                '=' => ' Igual =',
                '<' => 'Menor <',
                '<=' => 'Menor igual <=',
                '<>' => 'Diferente <>',
            ],
        ]);
    }

    public function search(Request $request)
    {
        $data = [];

        $any = [
            'name',
            'id_has_offers',
            'companies',
            'campaignsTypes',
            'status'
        ];

        $check = false;

        foreach($any as $item) {
            if($request->has($item)) {
                $check = true;
                break;
            }
        }

        if ($check) {
            $data = [
                'name'              => $request->get('name'),
                'id_has_offers'     => $request->get('id_has_offers'),
                'companies_id'      => $request->get('companies'),
                'campaign_types_id' => $request->get('campaignsTypes'),
                'status'            => $request->get('status'),
            ];
        }

        if (session()->has('userCompanies')) {
            $data['companies_id'] = implode(',', session()->get('userCompanies'));
        }

        $filters = [];

        $filterIn = '';

        foreach ($data as $key => $value) {
            if ($key == 'userid' || $value == '') {
                continue;
            }

            if ($key == 'companies_id') {
                $filterIn = $value;
                continue;
            }

            $filters['campaigns.' . $key] = $value;
        }

        if (false === array_key_exists('campaigns.status', $filters)) {
            $filters['campaigns.status'] = 1;
        }

        $campaigns = DB::table('campaigns')
            ->select(
                'campaigns.id',
                'campaigns.name',
                'campaigns.title',
                'campaigns.question',
                'campaigns.path_image',
                'campaigns.path_thumbnail',
                'campaigns.status',
                'campaigns.mobile',
                'campaigns.desktop',
                'campaigns.actions',
                'campaigns.postback_url',
                'campaigns.config_page',
                'campaigns.config_email',
                'campaigns.visualized',
                'campaigns.id_has_offers',
                'campaigns.campaign_types_id',
                'companies_id',
                'campaigns.order',
                DB::raw('(select count(c.id) from campaign_answers c where c.campaigns_id = campaigns.id) AS total_answers' )
            )
            ->orderBy('campaigns.order');

        if (false === empty($filterIn)) {
            $campaigns->whereIn('campaigns.companies_id', explode(',', $filterIn));
        }

        if (count($filters) > 0) {
            $campaigns->where($filters);
        }

        return datatables()->of($campaigns)->toJson();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()  : \Illuminate\View\View
    {
        //http://api.sweetmedia.local/api/v1/admin/campaigns/


        $response = $this->client->get('/api/v1/admin/number-max-order', [
            'headers' => [
                'Authorization' => 'Bearer ' .  session()->get('api_key'),
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
            ],
        ]);

        $order = \GuzzleHttp\json_decode($response->getBody()->getContents())->results->order;

        $categories = $this->client->get(self::ENDPOINT_CATEGORIES, [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('api_key')
            ]
        ]);

        $categories = $categories->getBody()->getContents();
        $categories = \GuzzleHttp\json_decode($categories)->data;

        $types = $this->client->get(self::ENDPOINT_CAMPAIGN_TYPES, [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('api_key')
            ]
        ]);

        $types = $types->getBody()->getContents();
        $types = \GuzzleHttp\json_decode($types)->data;

        $actions = Action::all();  
        
        return view('campaigns.create', $this->getDataForm() + compact('order'))->with(['action_categories' => $categories,'action_types'=> $types, 'actions' => $actions]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $data = [];

        /**
         *  Se checkbox 'Ações' e 'Vincular' selecionados:
         *  -> criar campaign e action, vinculando as duas. (editAction)
         *  
         *  Se checkbox 'Ações' selecionado e 'Vincular' não selecionado:
         *  -> criar campaign, criar action e vincular as duas. (createAction)
         * 
         *  Se checkbox 'Ações' selecionado e 'Vincular' não selecionado:
         *  -> criar campaign.
         */

        $action;

        //checkbox 'Ações' selecionado
        if($request->get('actions')) 
        {
            $data_action = [
                'action-id' => $request->get('link_action'),
                'action-category' => $request->get('action-category'),
                'action-type' => $request->get('action-type'),
                'action-url' => $request->get('action-url'),
                'action-title' => $request->get('action-title'),
                'action-description' => $request->get('action-description'),
                'action-points' => $request->get('action-points'),
                'action-order' => $request->get('action-order'),
                'action-enabled' => $request->get('action-enabled'),
                'action-image' => $request->get('image_path'),
                'action_filter_gender' => $request->get('action_filter_gender'),
                'action_filter_operation_begin' => $request->get('action_filter_operation_begin'),
                'action_filter_age_begin' => $request->get('action_filter_age_begin'),
                'action_filter_operation_end' => $request->get('action_filter_operation_end'),
                'action_filter_age_end' => $request->get('action_filter_age_end'),
                'action_filter_ddd' => $request->get('action_filter_ddd'),
                'action_filter_cep' => $request->get('action_filter_cep'),
            ];

            if ('' === $data_action['action_filter_age_begin']) {
                $data_action['action_filter_age_begin'] = 0;
            } 

            if ('' === $data_action['action_filter_age_end']) {
                $data_action['action_filter_age_end'] = 0;
            } 

            //checkbox 'Vincular' selecionado 
            if($request->get('link-to-action')) {
                $action = self::editAction($data_action);
            } else {
                $action = self::createAction($data_action);
            }           
        } 

        try {

            $data['campaigns'] = [
                'name' => $request->get('name'),
                'title' => $request->get('title'),
                'question' => $request->get('question'),
                'path_image' => (session()->get('image') ?? ''),
                'path_thumbnail' => (session()->get('thumbnail') ?? ''),
                'status' => true,
                'mobile' => ($request->get('mobile') ?? false),
                'desktop' => ($request->get('desktop') ?? false),
                'actions_id' => $action->id ?? null,
                'actions' => ($request->get('actions') ?? false),
                'postback_url' => $request->get('postback_url'),
                'config_page' => ($request->get('config_page') ?? ''),
                'config_email' => ($request->get('config_email') ?? ''),
                'visualized' => 0,
                'id_has_offers' => $request->get('id_has_offers'),
                'campaign_types_id' => $request->get('campaigntypes'),
                'companies_id' => $request->get('companies'),
                'filter_ddd' => $request->get('filter_ddd') ?? '',
                'filter_gender' => $request->get('filter_gender') ?? '',
                'filter_cep' => $request->get('filter_cep') ?? '',
                'filter_operation_begin' => $request->get('filter_operation_begin') ?? '',
                'filter_operation_end' => $request->get('filter_operation_end') ?? '',
            ];

            if (!empty($request->get('order'))) {
                $data['campaigns']['order'] = $request->get('order');
            }

            if ('' === $request->get('filter_age_begin')) {
                $data['campaigns']['filter_age_begin'] = 0;
            } else {
                $data['campaigns']['filter_age_begin'] = $request->get('filter_age_begin');               
            }

            if ('' === $request->get('filter_age_end')) {
                $data['campaigns']['filter_age_end'] = 0;                
            } else {
                $data['campaigns']['filter_age_end'] = $request->get('filter_age_end');
            }

            $data['clusters'] = $request->get('clusters');
            $data['domains'] = $request->get('domains');

            foreach ($request->get('campaigns_clickout_answer') as $key => $value) {
                $data['campaigns_clickout'][$key] = [
                    'answer' => $value,
                    'affirmative' => (!empty($request->get('campaigns_clickout_affirmative')) ? (array_key_exists($key, $request->get('campaigns_clickout_affirmative')) ?? false) : false),
                    'link' => $request->get('campaigns_clickout_link')[$key],
                ];
            }

            foreach ($request->input('catch-input') as $input) {
                $data['catch_inputs'][] = [
                    'label'                   => $input['label'],
                    'campaign_field_type_id'  => $input['type'],
                ];
            }

            $response = $this->client->request('POST', self::ENDPOINT, [
                'headers' => [
                    'Authorization' => 'Bearer ' . session()->get('api_key'),
                    'Content-Type' => 'application/json',
                    'cache-control' => 'no-cache',
                    'accept' => 'application/json',
                ],
                'json' => ['data' => $data],
            ]);

            $response = \GuzzleHttp\json_decode($response->getBody()->getContents())->status;

            $message = 'Ops, ocorreu algum erro ao cadastrar a campanha!';
            $type = 'error';

            if ($response == 'success') {
                $message = 'Campanha cadastrada com sucesso!';
                $type = 'success';
                session()->forget('image');
                session()->forget('thumbnail');
            }

            \Session::flash('flash_message', [
                'message' => $message,
                'title' => 'Campanha',
                'class' => $type,
            ]);

            return  redirect()->route('index.campaigns');

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            \Session::flash('flash_message', [
                'message' => $e->getResponse()->getReasonPhrase(),
                'title' => 'StatusCod:' . $e->getResponse()->getStatusCode(),
                'class' => 'error',
            ]);

            return back();
        }
    }

    private static function createAction($data)
    {
        $action = new Action();
        $action->action_category_id = $data['action-category'];
        $action->action_type_id = $data['action-type'];
        $action->order = $data['action-order'];
        $action->enabled = $data['action-enabled'];
        $action->filter_ddd = $data['action_filter_ddd'];
        $action->filter_operation_begin = $data['action_filter_operation_begin'];
        $action->filter_age_begin = $data['action_filter_age_begin'];
        $action->filter_operation_end = $data['action_filter_operation_end'];
        $action->filter_age_end = $data['action_filter_age_end'];
        $action->filter_gender = $data['action_filter_gender'];
        $action->filter_cep = $data['action_filter_cep'];
        $action->title = $data['action-title'];
        $action->description = $data['action-description'];
        $action->path_image = $data['action-image'];
        $action->grant_points = $data['action-points'];
        $action->save();

        $actionTypeMeta = new ActionTypeMeta();
        $actionTypeMeta->action_id = $action->id;
        $actionTypeMeta->action_type_id = $data['action-type'];
        $actionTypeMeta->key = 'url';
        $actionTypeMeta->value = $data['action-url'];
        $actionTypeMeta->save();

        return $action;
    }

    private static function editAction($data)
    {
        $action = Action::find($data['action-id']) ?? null;
        $action->action_category_id = $data['action-category'];
        $action->action_type_id = $data['action-type'];
        
        $actionTypeMeta = ActionTypeMeta::where('action_id', $action->id)->first() ?? null;
        $actionTypeMeta->action_type_id = $data['action-type'];
        $actionTypeMeta->value = $data['action-url'];
        $actionTypeMeta->update();

        $action->order = $data['action-order'];
        $action->enabled = $data['action-enabled'];
        $action->filter_ddd = $data['action_filter_ddd'];
        $action->filter_operation_begin = $data['action_filter_operation_begin'];
        $action->filter_age_begin = $data['action_filter_age_begin'];

        if(('>=' !== $data['action_filter_operation_begin']) && ('>' !== $data['action_filter_operation_begin'])) {
            $action->filter_operation_end = '';
            $action->filter_age_end = 0;
        } else {
            $action->filter_operation_end = $data['action_filter_operation_end'];
            $action->filter_age_end = $data['action_filter_age_end'];
        }
        
        $action->filter_gender = $data['action_filter_gender'];
        $action->filter_cep = $data['action_filter_cep'];
        $action->title = $data['action-title'];
        $action->description = $data['action-description'];
        $action->path_image = $data['action-image'];
        $action->grant_points = $data['action-points'];
        $action->update();

        return $action;
    }

    public function edit(int $id)
    {
        $response = $this->client->get(self::ENDPOINT . '/' . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' .  session()->get('api_key'),
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
            ]
        ]);

        $result = \GuzzleHttp\json_decode($response->getBody()->getContents())->result;

        $clusters = [];

        foreach ($result->clusters as $key) {
            $clusters[] = $key->id;
        }

        $domains = [];

        foreach ($result->domains as $key) {
            $domains[] = $key->id;
        }

        $clickout = [];

        foreach ($result->clickout as $key) {
            $clickout[] = [
                'id' => $key->id,
                'answer' => $key->answer,
                'affirmative' => $key->affirmative,
                'link' => $key->link,
            ];
        }

        $fields = [];

        foreach ($result->fields as $field) {
            $fields[] = [
                'id'    => $field->id,
                'label' => $field->label,
                'type'  => [
                    'id'   => $field->type->id,
                    'name' => $field->type->name,
                ],
            ];
        }

        $fieldTypes = CampaignFieldType::all();
        $fieldTypes = empty($fieldTypes) ? [] : $fieldTypes->toJson();

        $data = $this->getDataForm() + [
            'edit'          => true,
            'clustersCheck' => &$clusters,
            'domainsCheck'  => &$domains,
            'clickout'      => &$clickout,
            'fields'        => $fields,
            'fieldTypes'    => $fieldTypes,
        ];

        unset($clusters, $domains, $clickout);

        $categories = $this->client->get(self::ENDPOINT_CATEGORIES, [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('api_key')
            ]
        ]);

        $categories = $categories->getBody()->getContents();
        $categories = \GuzzleHttp\json_decode($categories)->data;

        $types = $this->client->get(self::ENDPOINT_CAMPAIGN_TYPES, [
            'headers' => [
                'Authorization' => 'Bearer ' . session()->get('api_key')
            ]
        ]);

        $types = $types->getBody()->getContents();
        $types = \GuzzleHttp\json_decode($types)->data;

        $allActions = Action::all();  

        $action = Action::find($result->actions_id) ?? null;

        if($action) {
            $actionTypeMeta = ActionTypeMeta::where('action_id', $action->id)->first() ?? null;
        }

        return view('campaigns.edit', $data)->with([
                'actions'           => $allActions,
                'actionTypeMeta'    => $actionTypeMeta ?? null,
                'actionCampaign'    => $action,
                'campaign'          => $result,
                'action_categories' => $categories,
                'action_types'      => $types,
            ]);
    }

    /**
     * @param int $active
     * @param int $id
     * @return string
     */
    public function status(int $active, int $id) :string
    {
        $response = $this->client->request('PATCH', sprintf('%s/%d', self::ENDPOINT, $id),  [
            'headers' => [
                'Authorization' => 'Bearer ' .  session()->get('api_key'),
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
            ],
            'json' => ['status' => $active],
        ]);

        return  $response->getBody()->getContents();
    }

    public function update(Request $request)
    {
        $data = [];

        /**
         *  Se checkbox 'Ações' e 'Vincular' selecionados:
         *  -> criar campaign e action, vinculando as duas. (editAction)
         *  
         *  Se checkbox 'Ações' selecionado e 'Vincular' não selecionado:
         *  -> criar campaign, criar action e vincular as duas. (createAction)
         * 
         *  Se checkbox 'Ações' selecionado e 'Vincular' não selecionado:
         *  -> criar campaign.
         */

        $action = null;

        //checkbox 'Ações' selecionado
        if($request->get('actions')) 
        { 
            $data_action = [
                'action-id' => $request->get('link_action'),
                'action-category' => $request->get('action-category'),
                'action-type' => $request->get('action-type'),
                'action-url' => $request->get('action-url'),
                'action-title' => $request->get('action-title'),
                'action-description' => $request->get('action-description'),
                'action-points' => $request->get('action-points'),
                'action-order' => $request->get('action-order'),
                'action-enabled' => $request->get('action-enabled'),
                'action-image' => $request->get('image_path'),
                'action_filter_gender' => $request->get('action_filter_gender'),
                'action_filter_operation_begin' => $request->get('action_filter_operation_begin'),
                'action_filter_age_begin' => $request->get('action_filter_age_begin'),
                'action_filter_operation_end' => $request->get('action_filter_operation_end'),
                'action_filter_age_end' => $request->get('action_filter_age_end'),
                'action_filter_ddd' => $request->get('action_filter_ddd'),
                'action_filter_cep' => $request->get('action_filter_cep'),
            ];

            if ('' === $data_action['action_filter_age_begin']) {
                $data_action['action_filter_age_begin'] = 0;
            } 

            if ('' === $data_action['action_filter_age_end']) {
                $data_action['action_filter_age_end'] = 0;
            } 

            //checkbox 'Vincular' selecionado 
            if($request->get('link-to-action')) {
                $action = self::editAction($data_action);
            } else {
                $action = self::createAction($data_action);
            }           
        }    

        try {
            $data['campaigns'] = [
                'name' => $request->get('name'),
                'title' => $request->get('title'),
                'question' => $request->get('question'),
                'status' => true,
                'mobile' => ($request->get('mobile') ?? false),
                'desktop' => ($request->get('desktop') ?? false),
                'actions_id' => $action->id ?? null,
                'actions' => ($request->get('actions') ?? false),
                'postback_url' => $request->get('postback_url'),
                'config_page' => ($request->get('config_page') ?? ''),
                'config_email' => ($request->get('config_email') ?? ''),
                'visualized' => 0,
                'id_has_offers' => $request->get('id_has_offers'),
                'campaign_types_id' => $request->get('campaign_types_id'),
                'companies_id' => $request->get('companies'),
                'filter_ddd' => $request->get('filter_ddd') ?? '',
                'filter_gender' => $request->get('filter_gender') ?? '',
                'filter_cep' => $request->get('filter_cep') ?? '',
                'filter_operation_begin' => $request->get('filter_operation_begin') ?? '',
                'filter_operation_end' => $request->get('filter_operation_end') ?? '',
            ];            

            if (!empty($request->get('order'))) {
                $data['campaigns']['order'] = $request->get('order');
            }

            if (!empty($request->get('filter_age_begin'))) {
                $data['campaigns']['filter_age_begin'] = $request->get('filter_age_begin');
            }

            if (!empty($request->get('filter_age_end'))) {
                $data['campaigns']['filter_age_end'] = $request->get('filter_age_end');
            }

            if (session()->has('image')) {
                $data['campaigns']['path_image'] = session()->get('image');
            }

            if (session()->has('thumbnail')) {
                $data['campaigns']['path_thumbnail'] = session()->get('thumbnail');
            }

            $data['clusters'] = $request->get('clusters');
            $data['domains'] = $request->get('domains');

            $response = $this->client->request('PUT', sprintf('%s/%d', self::ENDPOINT, $request->get('campaign_id')), [
                'headers' => [
                    'Authorization' => 'Bearer ' . session()->get('api_key'),
                    'Content-Type' => 'application/json',
                    'cache-control' => 'no-cache',
                    'accept' => 'application/json',
                ],
                'json' => ['data' => $data],
            ]);

            $response = \GuzzleHttp\json_decode($response->getBody()->getContents())->status;

            $message = 'ops, ocorreu algum erro ao cadastrar a campanha!';
            $type = 'error';

            if ($response == 'success') {
                $message = 'Campanha atualizada com sucesso!';
                $type = 'success';
                session()->forget('image');
                session()->forget('thumbnail');
            }

            \Session::flash('flash_message', [
                'message' => $message,
                'title' => 'Campanha',
                'class' => $type,
            ]);
            return  redirect()->route('index.campaigns');

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            \Session::flash('flash_message', [
                'message' => $e->getResponse()->getReasonPhrase(),
                'title' => 'StatusCod:' . $e->getResponse()->getStatusCode(),
                'class' => 'error',
            ]);

            return back();
        }
    }
}
