<?php

namespace App\Http\Controllers\ResearchesSponsored;

use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Traits\SweetStaticApiTrait;
use App\Http\Controllers\Controller;
use App\Models\ResearchesSponsored\Research;
use App\Models\ResearchesSponsored\MiddlePage;
use App\Models\ResearchesSponsored\ResearchesMiddlePage;

class ResearchesController extends Controller
{
    use SweetStaticApiTrait;

    public function index()
    {
        return view('researches.sponsored.index');
    }

    public function search()
    {
        $researches = Research::select(
            'id',
            'title',
            'subtitle',
            'description',
            'points',
            'final_url',
            'enabled'
        )->where('deleted_at', '=', null);

        return datatables()->of($researches)->toJson();
    }

    public function edit(int $id)
    {
        //get the survey information
        $research = self::getResearcheById($id);

        //get questions registered for the survey
        $researche_questions = self::getResearcheQuestions($id);
        
        //get middle_page for edited search
        $obj = self::getMiddlePages($id);

        if($obj){
            $middle_pages = get_object_vars($obj[0]);
        } else {
            $middle_pages = null; 
        }

        $question_options = self::getQuestionOptions($researche_questions);      

        $all_questions = self::getAllQuestionsOptions();

        foreach($question_options as $questions)
        {   
            $options = ' ';
            for ($i=0; $i < count($questions); $i++) 
            { 
                $q = get_object_vars($questions[$i]);
                
                //tratamento para não pegar valor repetido             
                if(($i != 0) && ($questions[$i]->questions_id == $questions[$i-1]->questions_id)) {
                    continue;
                }
            }   
        }

        //middle page of research
        $middlePages = [];
        
        $researchesMiddlePages = ResearchesMiddlePage::whereNull('deleted_at')->where('researches_id', $id)->get() ?? null;
        
        foreach($researchesMiddlePages as $researchesMiddlePage){
            $middle = MiddlePage::whereNull('deleted_at')->where('id', $researchesMiddlePage->middle_pages_id)->first() ?? null;
            //Log::debug($middle);

            array_push($middlePages, $middle);
        }

        //Log::debug($researche_questions);

        return view('researches.sponsored.create')->with([
            'action' => 'edit',
            'research' => $research,
            'researche_questions' => $researche_questions,
            'middle_pages' => $middle_pages,   
            'all_questions' => $all_questions,   
            'question_options' => $question_options,  
            //'middlePages' => $middlePages,
        ]);
    }

    public function verifyUrl(Request $request)
    {
        $url = $request->get('url');
        $research_id = $request->get('research_id') ?? null;

        try {            
            $response = self::executeSweetApi(
                'GET',
                'api/researches/v1/frontend/researche/verify/url',
                [
                   'url' => $url, 
                   'research_id' => $research_id,  
                ]
            );  
            
            if(!is_null($response)) {
                return response()->json([
                    'success' => true,
                    'data'    => $response,
                ]); 
            }

            return response()->json([
                'success' => false,
                'data'    => [],
            ]);                    
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }

    public function getDataMiddlePages(Request $request)
    {
        $id = $request->get('research_id');
        $middlePages = MiddlePage::whereNull('deleted_at')->orderBy('created_at', 'desc')->get() ?? null;

        if($middlePages){
            $researche_questions = self::getResearcheQuestions((int) $id);
            $question_options = self::getQuestionOptions($researche_questions);    
            
            foreach($question_options as $questions)
            {   
                $options = ' ';
                for ($i=0; $i < count($questions); $i++) 
                { 
                    $q = get_object_vars($questions[$i]);
                    
                    //tratamento para não pegar valor repetido             
                    if(($i != 0) && ($questions[$i]->questions_id == $questions[$i-1]->questions_id)) {
                        continue;
                    }
                }   
            }

            return view('researches.sponsored.includes.form-middle-page')->with([
                'middlePages'    => $middlePages,
                'action' => '',
                'researche_questions' => $researche_questions,
                'question_options' => $question_options,  
            ]);

            return response()->json([
                'success' => true,
                'data'    => $middlePages,
                'researche_questions' => $researche_questions,
                'question_options' => $question_options,  
            ]);  
        }

        return response()->json([
            'success' => false,
            'data'    => [],
        ]);  
    }


    private static function getQuestionOptions($researche_questions)
    {
        try {

            $array = [];

            foreach($researche_questions as $researche_question)
            {                
                $response = self::executeSweetApi(
                    'GET',
                    'api/researches/v1/frontend/question-option?where[questions_id]='.$researche_question->questions_id,
                    []
                );  
                array_push($array, $response->data);
            }
            
            //Log::debug($array);
            
            return $array;  
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }

    public function create()
    {
        return view('researches.sponsored.create')->with([
            'action' => 'create',
        ]);
    }

    public static function getResearcheById(int $id)
    {
        try {
            
            $response = self::executeSweetApi(
                'GET',
                'api/researches/v1/frontend/researche/'.$id,
                []
            );  
            
            return $response;  
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }

    public static function getResearcheQuestions(int $id)
    {
        try {
            
            $response = self::executeSweetApi(
                'GET',
                'api/researches/v1/frontend/researche-question?where[researches_id]='.$id,
                []
            );  
            
            return $response->data;  
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }

    public static function getMiddlePages(int $id)
    {
        try {
            
            $response = self::executeSweetApi(
                'GET',
                'api/researches/v1/frontend/researches-middle-page?where[researches_id]='.$id,
                []
            );  
            
            return $response->data;  
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }

    public function getResearcheId()
    {
        try {
            
            $response = self::executeSweetApi(
                'GET',
                'api/researches/v1/frontend/researche',
                []
            );  
            
            return $response->data;   
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {            
            $response = self::executeSweetApi(
                'POST',
                'api/researches/v1/frontend/researche',
                [
                    'title'  => $request->get('title'),
                    'subtitle' => $request->get('subtitle'),
                    'description' => $request->get('description'),
                    'points' => $request->get('points'),
                    'final_url' => $request->get('final_url'),
                    'enabled' => $request->get('enabled'),
                ]
            );     

            return response()->json([
                'success' => true,
                'result'  => $response,
            ]);        
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $research = Research::find($id);

        if (empty($research)) {
            return response()->json([
                'success' => false,
                'data'    => [],
            ], 404);
        }   
        
        $research->title        = $request->input('title');
        $research->subtitle     = $request->input('subtitle');
        $research->description  = $request->input('description');
        $research->points       = $request->input('points');
        $research->final_url    = $request->input('final_url');
        $research->enabled      = $request->input('enabled');
        $research->save();

        return response()->json([
            'success' => true,
            'data'    => $research,
        ]);
    }

    public function delete(int $id)
    {
        try {  
                      
            $response = self::executeSweetApi(
                'POST',
                'api/researches/v1/frontend/researche/'.$id,
                []
            );     

            return response()->json([
                'success' => true,
                'data'  => $response,
            ]);        
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }

    }

    private static function getAllQuestionsOptions()
    {
        $questions = self::getQuestions();

        $options = [];

        foreach ($questions as $question) {
            $op = self::getOptions($question->id);
            array_push($options, $op);            
        }

        return $options;
    }

    private static function getQuestions()
    {
        try {
            
            $response = self::executeSweetApi(
                'GET',
                'api/researches/v1/frontend/question',
                []
            );  
            
            return $response->data;  
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }

    }

    private static function getOptions(int $questions_id)
    {
        try {
            
            $response = self::executeSweetApi(
                'GET',
                'api/researches/v1/frontend/question-option?where[questions_id]='.$questions_id,
                []
            );  
            
            return $response->data;  
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }
}