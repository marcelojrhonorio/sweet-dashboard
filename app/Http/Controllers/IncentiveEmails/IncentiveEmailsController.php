<?php

namespace App\Http\Controllers\IncentiveEmails;

use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\IncentiveEmails\IncentiveEmail;
use App\Models\IncentiveEmails\CheckinIncentiveEmail;

class IncentiveEmailsController extends Controller
{
    public function index()
    {
        return view('incentive_emails.index');
    }

    public function search()
    {
        $incentiveEmails = IncentiveEmail::select(
            'id',
            'code',
            'title',
            'description',
            'points',
            'redirect_link'
        );

        return datatables()->of($incentiveEmails)->toJson();        
    }
    
    public function store(Request $request)
    {
        $inputs = $request->only([
            'code',
            'title',
            'description',
            'points',
            'redirect_link',
        ]);

        $incentiveEmail = IncentiveEmail::create($inputs);

        return response()->json([
            'success' => true,
            'data'    => $incentiveEmail,
        ]);

    }

    public function update(Request $request, $id)
    {
        $incentiveEmail = IncentiveEmail::find($id);

        if(empty($incentiveEmail)){
            return response()->json([
                'success' => false,
                'data'    => [],
            ], 404);
        }

        $incentiveEmail->title          = $request->input('title');
        $incentiveEmail->description    = $request->input('description');
        $incentiveEmail->points         = $request->input('points');
        $incentiveEmail->redirect_link  = $request->input('redirect_link');
        $incentiveEmail->save();

        return response()->json([
            'success' => true,
            'data'    => $incentiveEmail,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $incentiveEmail = IncentiveEmail::find($id);

        if (empty($incentiveEmail)) {
            return response()->json([
                'success' => false,
                'data'    => [],
            ], 404);
        }

        $incentiveEmail->delete();

        return response()->json([
            'success' => true,
            'data'    => $incentiveEmail,
        ]);
    }
}
