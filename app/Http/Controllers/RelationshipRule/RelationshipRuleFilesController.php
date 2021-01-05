<?php

namespace App\Http\Controllers\RelationshipRule;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class RelationshipRuleFilesController extends Controller
{
    public function store(Request $request)
    {
        $date            = Carbon::now();
        $fileName        = $date->year   . '-' . 
                           $date->month  . '-' .
                           $date->hour   . '-' .
                           $date->minute . '-' .
                           $date->second . '.html';

        $path            = '/bonus/relationship-rules/html';

        $finalPath = $request->file('html')->storeAs(
            $path, $fileName
        );

        return response()->json([
            'success' => true,
            'data'    => $fileName,
        ], 201);        
    }

    public function downloadFile($fileName)
    {
        return response()->download(storage_path("app/public/bonus/relationship-rules/html/" . $fileName));
    }
}
