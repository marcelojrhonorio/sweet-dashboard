<?php

namespace App\Http\Controllers\Stamps;

use DataTables;
use App\Models\Stamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class StampsController extends Controller
{

    public function index()
    {
        return view('stamps.index');
    }

    public function store(Request $request)
    {
        $params = [
            'title'             => $request->input('title'),
            'description'       => $request->input('description'),
            'icon'              => session()->get('iconPS') ?? '',
            'type'              => $this->getType($request->input('type')),
            'required_amount'   => $request->input('required_amount'),
        ];

        $stamp = Stamp::create($params);

        return response()->json([
            'success' => true,
            'data'    => $stamp,
        ]);        

    }

    public function update(Request $request, $id)
    {
        $stamp = Stamp::find($id);
        
        if(empty($stamp)){
            return response()->json([
                'success' => false,
                'data'    => [],
            ], 404);
        }

        $stamp->title           =   $request->input('title');
        $stamp->description     =   $request->input('description');
        $stamp->icon            =   $request->input('icon');
        $stamp->type            =   $this->getType($request->input('type'));
        $stamp->required_amount =   $request->input('required_amount');
        $stamp->save();

        return response()->json([
            'success' => true,
            'data'    => $stamp,
        ]);
    }

    public function search(Request $request)
    {
        $stamp = Stamp::select(
            'id',
            'title',
            'description',
            'icon',
            'type',
            'required_amount'
        );

        return datatables()->of($stamp)->toJson(); 
    }

    public function destroy(Request $request, $id)
    {
        $stamp = Stamp::find($id);
        $stamp->delete();

        return response()->json([
            'success' => true,
            'data'    => $id,
        ]);
    }

    private function getType($id)
    {
        $type = [
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
        ];

        return $type[$id];
    }
}
