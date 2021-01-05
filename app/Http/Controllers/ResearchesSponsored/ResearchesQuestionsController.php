<?php

namespace App\Http\Controllers\ResearchesSponsored;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\SweetStaticApiTrait;
use App\Http\Controllers\Controller;
use App\Models\ResearchesSponsored\MiddlePage;
use App\Models\ResearchesSponsored\ResearchQuestions;
use App\Models\ResearchesSponsored\ResearchesMiddlePage;

class ResearchesQuestionsController extends Controller
{
    use SweetStaticApiTrait;

    public function verifyOrdering(int $researches_id)
    {
        try {
            
            $response = self::executeSweetApi(
                'GET',
                'api/researches/v1/frontend/researche-question?where[researches_id]='.$researches_id,
                []
            ); 
            
            return response()->json([
                'success' => true,
                'data'    => $response->data,
            ], 200); 
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }

    public function updateOrderQuestions(Request $request, int $id)
    {
        $questions_id = $request->get('questions_id');
        $researches_id = $request->get('researches_id');
        $order = $request->get('order');

        DB::table('sweet_researches.researche_questions')
            ->where('id', $id)
            ->update([
                'researches_id' => $researches_id,
                'questions_id'  => $questions_id,
                'ordering'      => $order,
            ]);                                               

        return response()->json([
            'success' => true,
            'data'    => [],
        ]);      

    }

    public function getResearchesQuestions(int $id)
    {
        $questions = self::getQuestions($id);

        $options = [];

        foreach ($questions as $question) {
            $op = self::getOptions($question->questions_id);
            array_push($options, $op);            
        }

        //middle page of research
        $middlePages = [];
        
        $researchesMiddlePages = ResearchesMiddlePage::where('researches_id', $id)->get() ?? null;
        
        foreach($researchesMiddlePages as $researchesMiddlePage){
            $middle = MiddlePage::whereNull('deleted_at')->where('id', $researchesMiddlePage->middle_pages_id)->first() ?? null;
         
            array_push($middlePages, [
                'researches_middle_pages' => $researchesMiddlePage,
                'middle' => $middle,
            ]);
        }

        return response()->json([
            'success' => true,
            'data'    => $options,
            'middlePages' => $middlePages,
        ], 200); 
        
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

    private static function getQuestions(int $id)
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

    public function getResearchQuestion(Request $request)
    {
        $questions_id = $request->get('questions_id');
        $researches_id = $request->get('researches_id');
        
        $question = ResearchQuestions::where('researches_id', $researches_id)
                                     ->where('questions_id', $questions_id)->first() ?? null;
                        
        if(is_null($question)){
            return response()->json([
                'success' => false,
                'data'    => [], 
            ]); 
        }

        return response()->json([
            'success' => true,
            'data'    => $question,
        ]);
    }

    public function insertResearchQuestions(Request $request)
    {        
        $questions_id = $request->get('questions_id');
        $researches_id = $request->get('researches_id');
        $order = $request->get('order');

        $question = ResearchQuestions::where('researches_id', $researches_id)
                                     ->where('questions_id', $questions_id)->first() ?? null;

        if(is_null($question))
        {
            $res_questions = ResearchQuestions::where('researches_id', $researches_id)->get() ?? null;

            foreach($res_questions as $res_question)
            {
                if($res_question->ordering === $order)
                {
                    //criar um novo e dar update
                    $research_question1 = new ResearchQuestions();
                    $research_question1->researches_id = $researches_id;
                    $research_question1->questions_id = $questions_id;
                    $research_question1->ordering = $order;
                    $research_question1->save();

                    //reordenar os demais
                    foreach($res_questions as $res)
                    {
                        if($res->ordering >= $order) 
                        {
                            DB::table('sweet_researches.researche_questions')
                                ->where('id', $res->id)
                                ->update([
                                    'researches_id' => $res->researches_id,
                                    'questions_id'  => $res->questions_id,
                                    'ordering'      => ((int) $res->ordering + 1),
                                ]);  
                        }
                    }

                    return response()->json([
                        'success' => true,
                        'data'    => [],
                    ]); 
                }
            }

            $response = self::executeSweetApi(
                'POST',
                'api/researches/v1/frontend/researche-question/',
                [
                    'researches_id' => $researches_id,
                    'questions_id'  => $questions_id,
                    'ordering'      => $order
                ]
            );                                   

            return response()->json([
                'success' => true,
                'data'    => $response,
            ]);               
        }

        if(!$question->ordering){
            $question->deleted_at = null;
            $question->update();
        }

        $res_questions = ResearchQuestions::where('researches_id', $researches_id)->get() ?? null;

        $flag = true;

        foreach($res_questions as $res_question)
        {
            if($res_question->ordering === $order)
            {
                $flag = false;

                DB::table('sweet_researches.researche_questions')
                    ->where('id', $res_question->id)
                    ->update([
                        'researches_id' => $res_question->researches_id,
                        'questions_id'  => $res_question->questions_id,
                        'ordering'      => self::checkOrderUpdate($res_question->researches_id, $question->ordering),
                    ]);               

                DB::table('sweet_researches.researche_questions')
                    ->where('id', $question->id)
                    ->update([
                        'researches_id' => $question->researches_id,
                        'questions_id'  => $question->questions_id,
                        'ordering'      => $order,
                    ]);  

                return response()->json([
                    'success' => true,
                    'data'    => self::getNewResearchQuestion($res_question->id), //$res_question,
                ]);  
            }
        }  

        if($flag)
        {
            DB::table('sweet_researches.researche_questions')
                    ->where('id', $question->id)
                    ->update([
                        'researches_id' => $question->researches_id,
                        'questions_id'  => $question->questions_id,
                        'ordering'      => $order,
                    ]);  

                return response()->json([
                    'success' => true,
                    'data'    => $question,
                ]);  
        }             

    }

    private static function getNewResearchQuestion($res_ques_id)
    {
        $res = DB::select("SELECT * FROM sweet_researches.researche_questions WHERE id = ". $res_ques_id ."");
        return $res;
    }

    private static function checkOrderUpdate($researches_id, $order)
    {
        /**
         * Lógica para verificação de order para atualização.
         */

        $res_questions = ResearchQuestions::where('researches_id', $researches_id)->get() ?? null;

        $flag = false;

        foreach($res_questions as $res)
        {
            if($res->ordering == $order) {
                $flag = $res->ordering;
            }
        }

        if($flag) {
            return $flag; 
        }

        $aux = $order + 1;

        foreach($res_questions as $res)
        {
            if($res->ordering == $aux) {                
                $aux = $res->ordering + 1;
            }
        }
        
        return $aux;
    }

    private static function deleteResearchQuestion($researches_id)
    {
        try { 

            $response = self::executeSweetApi(
                'POST',
                'api/researches/v1/frontend/researche-question/'.$researches_id,
                []
            );      

            return response()->json([
                'success' => true,
                'data'    => $response,
            ]);      
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }

    }

    public function removeResearchQuestion(Request $request)
    {
        $questions_id = $request->get('questions_id');
        $researches_id = $request->get('researches_id');

        try { 

            $researchQuestions = ResearchQuestions::where('researches_id', $researches_id)
                                                  ->where('questions_id', $questions_id)->first() ?? null;

            $response = self::executeSweetApi(
                'POST',
                'api/researches/v1/frontend/researche-question/remove/'.$researchQuestions->id,
                []
            ); 

            return response()->json([
                'success' => true,
                'data'    => $response,
            ]);      
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }

    public function updateResearchQuestions(Request $request)
    {
        $questions = $request->get('questions');
        $researches_id = $request->get('researches_id');

        try { 

            $del = self::deleteResearchQuestion($researches_id);
            
            self::createResearchQuestions($researches_id, $questions);

            return response()->json([
                'success' => true,
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

    private static function createResearchQuestions(int $researches_id, $questions)
    {
        try { 
            
            $order = 0;
            foreach ($questions as $question) 
            {
                $order++;

                $response = self::executeSweetApi(
                    'POST',
                    'api/researches/v1/frontend/researche-question/',
                    [
                        'researches_id' => $researches_id,
                        'questions_id'  => $question,
                        'ordering'      => $order
                    ]
                );   
            }
                    

            return response()->json([
                'success' => true,
                'data'    => $response,
            ]);      
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }
}