<?php

namespace App\Http\Controllers\ResearchesSponsored;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Models\ResearchesSponsored\MiddlePage;
use App\Models\ResearchesSponsored\ResearchesMiddlePage;

class MiddlePagesController extends Controller
{
    public function create()
    {
        return view('researches.sponsored.includes.form-middle-page')->with([
            'action' => '',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->input('params');

        $params = [
            'title'             => $data['title'],
            'description'       => $data['description'],
            'image_path'        => session()->get('iconPS') ?? '',
            'redirect_link'     => $data['redirect_link'],
        ];

        $middlePage = MiddlePage::create($params);

        return response()->json([
            'success' => true,
            'data'    => $middlePage,
        ]);        

    }

    public function getDataMiddlePage(Request $request)
    {
        $middlePages = MiddlePage::whereNull('deleted_at')->orderBy('created_at', 'desc')->get() ?? null;

        if($middlePages){
            return response()->json([
                'success' => true,
                'data'    => $middlePages,
            ]);  
        }

        return response()->json([
            'success' => false,
            'data'    => [],
        ]);  
    }

    public function icon(Request $request)
    {  
        $this->validate($request, [
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        $iconUpload = $request->file('icon');

        $iconName = uniqid(time()) . '.' . $iconUpload->getClientOriginalExtension();

        $date            = Carbon::now();
        $path            = 'bonus/middle-pages/images/'. $date->year . '/' . $date->month . '/';
        $destinationPath = storage_path('app/public/' . $path);

        File::makeDirectory($destinationPath, 0777, true, true);

        $icon = \Image::make($iconUpload->getRealPath());
        //$icon->resize(300, 250);
        $icon->save($destinationPath . $iconName);

        session()->put('iconPS', $path.$iconName);
        session()->put('iconPathPS', $path);
        session()->put('iconNamePS', $iconName);

        $data = [
            'path' => $path,
            'name' => $iconName,
        ];

        return response()->json([
            'success' => true,
            'data'    => $data,
        ], 201);
    }

    public function researchesMiddlePages(Request $request)
    {
        $data = $request->input('params');        

        $researcheMiddlePage = ResearchesMiddlePage::create($data);

        return response()->json([
            'success' => true,
            'data'    => $researcheMiddlePage,
        ]); 
    }

    public function update(Request $request, $id)
    {
        $researcheMiddlePage = ResearchesMiddlePage::where('middle_pages_id', '=', $id)
                                                   ->where('researches_id', '=', $request->input('researches_id'))->first();
        
        if (empty($researcheMiddlePage)) {
         return response()->json([
             'success' => false,
             'data'    => [],
         ], 404);
        }  

        $researcheMiddlePage->researches_id = $request->input('researches_id');
        $researcheMiddlePage->options_id = $request->input('options_id');
        $researcheMiddlePage->questions_id = $request->input('questions_id');
        $researcheMiddlePage->save();
                                    
        $middlePage = MiddlePage::find($id);

        if (empty($middlePage)) {
            return response()->json([
                'success' => false,
                'data'    => [],
            ], 404);
        }   
        
        $middlePage->title = $request->input('title');
        $middlePage->description = $request->input('description');
        $middlePage->redirect_link = $request->input('redirect_link');
        $middlePage->image_path = $request->input('image_path');
        $middlePage->save();

        return response()->json([
            'success' => true,
            'data'    => $middlePage,
        ]);
    }

    public function verifyMiddlePage(Request $request)
    {
        /**
         * Se tiver alguma modificação da MiddlePage,
         * verificar se exste alguma research que a utiliza.
         */

        $id = $request->input('id');
        $title = $request->input('title');
        $description = $request->input('description');
        $redirectLink = $request->input('redirectLink');
        $researches_id = $request->input('researches_id');
        $image = $request->input('image');

        $middlePage = MiddlePage::find($id) ?? null;

        $data = [
            'id' => $id,
            'title' => $title,
            'researches_id' => $researches_id,
            'description' => $description,
            'redirectLink' => $redirectLink,
            'image' => $image,
            'middlePage' => $middlePage,
        ];

        $modification = self::hasModification($data);
        $researches = self::hasResearchMiddlePage($id, $researches_id);

        if($modification && $researches) {
            return response()->json([
                'success' => true,
                'data'    => $data,
            ]); 
        } 
        
        return response()->json([
            'success' => false,
            'data'    => $data,
        ]);        
    }

    private static function hasModification($data)
    {
        $condidition1 = ($data['title'] != $data['middlePage']->title);
        $condidition2 = ($data['description'] != $data['middlePage']->description);
        $condidition3 = ($data['redirectLink'] != $data['middlePage']->redirect_link);
        $condidition4 = ($data['image'] != $data['middlePage']->image_path);        

        if($condidition1 || $condidition2 || $condidition3 || $condidition4) {
            return true;
        }

        return false;
    }

    private static function hasResearchMiddlePage($middle_pages_id, $researches_id) 
    {
        $researcheMiddlePage = ResearchesMiddlePage::where('middle_pages_id', '=', $middle_pages_id)
                                                   ->where('researches_id', '<>', $researches_id)->first() ?? null;

        if($researcheMiddlePage) {
            return true;
        }

        return false;
    }
}