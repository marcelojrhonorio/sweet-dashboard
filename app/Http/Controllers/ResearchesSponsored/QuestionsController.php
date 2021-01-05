<?php

namespace App\Http\Controllers\ResearchesSponsored;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Traits\SweetStaticApiTrait;
use App\Http\Controllers\Controller;

class QuestionsController extends Controller
{
    use SweetStaticApiTrait;

    public function getQuestionOptions(Request $request)
    {   
        try { 

            $response = self::executeSweetApi(
                'GET',
                'api/researches/v1/frontend/question-option',
                []
            );         

            return response()->json([
                'success' => true,
                'data'    => $response->data,
            ]);      
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }

    }

    public function getQuestionOptionsFormat(Request $request)
    {
        try { 

            $response = self::executeSweetApi(
                'GET',
                'api/researches/v1/frontend/question',
                []
            );     
            
            $questions = $response->data;

           $options = [];

            foreach($questions as $question)
            {
                foreach($question->question_option as $question_option)
                {
                    $option = self::getDescriptionOption($question_option->options_id);
                    array_push($options, $option);
                }
            }

            return response()->json([
                'success'    => true,
                'questions'  => $questions,
                'options'    => $options,
            ]);    
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }
    
    private static function getDescriptionOption(int $options_id)
    {
        try { 

            $response = self::executeSweetApi(
                'GET',
                'api/researches/v1/frontend/option/'.$options_id,
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

    public function getQuestionOptionsByQuestion(int $questions_id)
    {   
        try { 

            $response = self::executeSweetApi(
                'GET',
                'api/researches/v1/frontend/question-option?where[questions_id]='.$questions_id,
                []
            );         

            return response()->json([
                'success' => true,
                'data'    => $response->data,
            ]);      
                
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

            $question = self::executeSweetApi(
                'POST',
                'api/researches/v1/frontend/question',
                [
                    'description'   => $request->get('description'),
                    'one_answer'    => $request->get('one_answer'),
                    'extra_information' => $request->get('extra_information') ?? null,
                ]
            );  
            
            $options = self::insertOptions($request->get('options'));            
            $question_options = self::insertQuestionOptions($question, $options);

            return response()->json([
                'success' => true,
                'data'    => $question_options,
            ]);        
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }
    }

    private static function insertQuestionOptions($question, $options)
    {
        $array = [];

        try {   
            
            foreach ($options as $option) 
            {
                $response = self::executeSweetApi(
                    'POST',
                    'api/researches/v1/frontend/question-option',
                    [
                        'questions_id' => $question->data->id,
                        'options_id' => $option->data->id,
                    ]
                ); 

                array_push($array, $response);
            }            

            return $array;        
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }

    }

    private static function insertOptions($options)
    {
        $array = [];

        try {   
            
            foreach ($options as $option) 
            {
                $response = self::executeSweetApi(
                    'POST',
                    'api/researches/v1/frontend/option',
                    [
                        'description' => $option,
                    ]
                ); 

                array_push($array, $response);
            }            

            return $array;        
                
        } catch (RequestException $exception) {
            Log::debug($exception->getMessage());
        } catch (ConnectException $exception) {
            Log::debug($exception->getMessage());
        } catch (ClientException $exception) {
            Log::debug($exception->getMessage());
        }

    }

   
}