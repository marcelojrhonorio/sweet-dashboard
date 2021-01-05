<?php

namespace App\Http\Controllers;

use DataTables;
use Illuminate\Http\Request;
use App\Models\Research;

class ResearchesController extends Controller
{
    public function index()
    {
        return view('researches.index');
    }

    public function search()
    {
        $researches = Research::select(
            'id',
            'title',
            'hasoffers_id',
            'points'
        );

        return datatables()->of($researches)->toJson();
    }

    public function store(Request $request)
    {
        $inputs = $request->only([
            'title',
            'hasoffers_id',
            'points',
        ]);

        $research = Research::create($inputs);

        return response()->json([
            'success' => true,
            'data'    => $research,
        ]);
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
        $research->hasoffers_id = $request->input('hasoffers_id');
        $research->points       = $request->input('points');
        $research->save();

        return response()->json([
            'success' => true,
            'data'    => $research,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $research = Research::find($id);

        if (empty($research)) {
            return response()->json([
                'success' => false,
                'data'    => [],
            ], 404);
        }

        $research->delete();

        return response()->json([
            'success' => true,
            'data'    => $research,
        ]);
    }
}
