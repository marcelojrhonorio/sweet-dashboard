<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResearchPixel;

class ResearchPixelsController extends Controller
{

    public function index()
    {
        
    }

    public function search(Request $request, $researchId)
    {
        $pixels = ResearchPixel::where('research_id', $researchId)->get();
        return response()->json($pixels);
    }


    public function store(Request $request)
    {
        $inputs = $request->only([
            'research_id',
            'affiliate_id',
            'type',
            'goal_id',
            'has_redirect',
            'link_redirect'
        ]);

        $pixel = ResearchPixel::create($inputs);

        return response()->json([
            'success' => true,
            'data'    => $pixel, 
        ]);
    }


    public function update(Request $request, $id)
    {
        $pixel = ResearchPixel::find($id);

        if (empty($pixel)) {
            return response()->json([
                'success' => false,
                'data'    => [],
            ], 404);
        }
        
        $pixel->affiliate_id   = $request->input('affiliate_id');
        $pixel->type           = $request->input('type');
        $pixel->goal_id        = $request->input('goal_id'); 
        $pixel->has_redirect   = $request->input('has_redirect');
        $pixel->link_redirect  = $request->input('link_redirect');
        $pixel->save();

        return response()->json([
            'success' => true,
            'data'    => $pixel,
        ]);        
    }

    public function destroy(Request $request, $id)
    {
        $pixel = ResearchPixel::find($id);

        if (empty($pixel)) {
            return response()->json([
                'success' => false,
                'data'    => [],
            ], 404);
        }

        $pixel->delete();

        return response()->json([
            'success' => true,
            'data'    => $pixel,
        ]);        
    }
}
