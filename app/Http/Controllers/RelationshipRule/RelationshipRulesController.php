<?php

namespace App\Http\Controllers\RelationshipRule;

use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\RelationshipRules\RelationshipRule;

class RelationshipRulesController extends Controller
{
    public function index(Request $request)
    {
        return view('relationship_rule.index');
    }

    public function search()
    {
        $relationship = RelationshipRule::select(
            'id',
            'subject',
            'html_message',
            'order',
            'enabled'
        );

        return datatables()->of($relationship)->toJson();
    }

    public function store(Request $request)
    {
        $inputs = $request->only([
            'subject',
            'html_message',
            'order',
            'enabled',
        ]);

        $relationship = RelationshipRule::create($inputs);

        return response()->json([
            'success' => true,
            'data'    => $relationship,
        ]);        
    }

    public function update(Request $request, $id)
    {
        $relationship = RelationshipRule::find($id);

        if(empty($relationship)){
            return response()->json([
                'success' => false,
                'data'    => [],
            ], 404);
        }

        $relationship->subject       =  $request->input('subject');
        $relationship->html_message  =  $request->input('html_message');
        $relationship->order         =  $request->input('order');
        $relationship->enabled       =  $request->input('enabled');
        $relationship->save();

        return response()->json([
            'success' => true,
            'data'    => $relationship,
        ]);

        
    }

    public function destroy(Request $request, $id)
    {
        $relationship = RelationshipRule::find($id);

        if (empty($relationship)) {
            return response()->json([
                'success' => false,
                'data'    => [],
            ], 404);
        }

        $relationship->delete();

        return response()->json([
            'success' => true,
            'data'    => $relationship,
        ]);
    }
}
