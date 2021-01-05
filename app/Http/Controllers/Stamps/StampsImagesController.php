<?php

namespace App\Http\Controllers\Stamps;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class StampsImagesController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        $iconUpload = $request->file('icon');

        $iconName = uniqid(time()) . '.' . $iconUpload->getClientOriginalExtension();

        $date            = Carbon::now();
        $path            = 'bonus/stamps/images/'. $date->year . '/' . $date->month . '/';
        $destinationPath = storage_path('app/public/' . $path);

        File::makeDirectory($destinationPath, 0777, true, true);

        $icon = \Image::make($iconUpload->getRealPath());
        $icon->resize(500, 500);
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
}
