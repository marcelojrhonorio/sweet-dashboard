<?php
/**
 * @todo Add docs.
 */

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

/**
 * @todo Add docs.
 */
class ActionsImagesController extends Controller
{
    /**
     * @todo Add docs.
     */
    const ENDPOINT = 'api/v1/admin/actions';

    /**
     * @todo Add docs.
     */
    protected $client;

    /**
     * @todo Add docs.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('APP_SWEET_API'),
            'headers'  => [
                'cache-control' => 'no-cache',
                'accept'        => 'application/json',
                'content-type'  => 'application/json',
            ],
        ]);
    }

    /**
     * @todo Add docs.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        $imageUpload = $request->file('image');

        if (session()->has('imageNamePS')) {
            $imageName = session()->get('imageNamePS');
        } else {
            $imageName = uniqid(time()) . '.' . $imageUpload->getClientOriginalExtension();
        }

        $date            = Carbon::now();
        $path            = 'bonus/actions/images/'. $date->year . '/' . $date->month . '/';
        $destinationPath = storage_path('app/public/' . $path);

        File::makeDirectory($destinationPath, 0777, true, true);

        $image = \Image::make($imageUpload->getRealPath());
        $image->resize(210, 175);
        $image->save($destinationPath . $imageName);

        session()->put('imagePS', $path.$imageName);
        session()->put('imagePathPS', $path);
        session()->put('imageNamePS', $imageName);

        $data = [
            'path' => $path,
            'name' => $imageName,
        ];

        return response()->json([
            'success' => true,
            'data'    => $data,
        ], 201);
    }

    public function uploadImage(Request $request)
    {
        $this->validate($request, [
            'action-image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        $imageUpload = $request->file('action-image');

        if (session()->has('imageNamePS')) {
            $imageName = session()->get('imageNamePS');
        } else {
            $imageName = uniqid(time()) . '.' . $imageUpload->getClientOriginalExtension();
        }

        $date            = Carbon::now();
        $path            = 'bonus/actions/images/'. $date->year . '/' . $date->month . '/';
        $destinationPath = storage_path('app/public/' . $path);

        File::makeDirectory($destinationPath, 0777, true, true);

        $image = \Image::make($imageUpload->getRealPath());
        $image->resize(210, 175);
        $image->save($destinationPath . $imageName);

        session()->put('imageActionPS', $path.$imageName);
        session()->put('imageActionPathPS', $path);
        session()->put('imageActionNamePS', $imageName);

        $data = [
            'path' => $path,
            'name' => $imageName,
        ];

        return response()->json([
            'success' => true,
            'data'    => $data,
        ], 201);
    }

    /**
     * @todo Add docs.
     */
    public function update(Request $request, $id)
    {}

    /**
     * @todo Add docs.
     */
    public function destroy($id)
    {}
}
