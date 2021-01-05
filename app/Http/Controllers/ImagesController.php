<?php

namespace App\Http\Controllers;

use Image;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ClientErrorResponseException;


class ImagesController extends Controller
{
    const ENDPOINT = 'api/v1/admin/companies';

    private $client;

    private $headers = [];

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('APP_SWEET_API'),
            'headers' => [
                'cache-control' => 'no-cache',
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ]
        ]);
    }

    private function thumbnail(Carbon $date, string $realPath, string $imageName)
    {
        $pathThumbnail = 'campaings/thumbnails/'. $date->year . '/' . $date->month . '/';
        $thumbnailPath = storage_path('app/public/' . $pathThumbnail);

        File::makeDirectory($thumbnailPath, 0777, true, true);

        $thumbnail = Image::make($realPath)->resize(100, 56);
        $thumbnail->save($thumbnailPath . $imageName);

        session()->put('thumbnail', $pathThumbnail.$imageName);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function upload(Request $request) : string
    {
        $this->validate($request, [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        $imageUpload = $request->file('file');
        $imageName = uniqid(time()) . '.' . $imageUpload->getClientOriginalExtension();

        $date = Carbon::now();

        $path = 'campaings/images/'. $date->year . '/' . $date->month . '/';
        $destinationPath = storage_path('app/public/' . $path);

        File::makeDirectory($destinationPath, 0777, true, true);
        $realPath = $imageUpload->getRealPath();
        $image = Image::make($imageUpload->getRealPath());

        if ($image->width() <  540 && $image->height() < 260) {
            return \GuzzleHttp\json_encode([
                'status' =>'error',
                'message' => 'Invalid image size',
            ], 200);
        }

        if (($image->width() > 540 && $image->width() < 576) || ($image->height() > 260 && $image->height() < 290)) {
            $image->resize(540, 260);
        } elseif ($image->width() > 576 || $image->height() > 290) {
            $image->resize(576, 290);
        }

        $image->save($destinationPath . $imageName);

        $this->thumbnail($date, $realPath, $imageName);

        session()->put('image', $path.$imageName);

        return \GuzzleHttp\json_encode([
            'path' => $path,
            'name' => $imageName,
        ], 200);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function uploadProductsServices(Request $request) : string
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

        $date = Carbon::now();

        $path = 'bonus/products-services/images/'. $date->year . '/' . $date->month . '/';
        $destinationPath = storage_path('app/public/' . $path);

        File::makeDirectory($destinationPath, 0777, true, true);

        $image = Image::make($imageUpload->getRealPath());
        $image->resize(100, 100);
        $image->save($destinationPath . $imageName);

        session()->put('imagePS', $path.$imageName);
        session()->put('imagePathPS', $path);
        session()->put('imageNamePS', $imageName);

        return \GuzzleHttp\json_encode([
            'path' => $path,
            'name' => $imageName,
        ], 200);
    }

    public function delete(Request $request)
    {
        //dd(Storage::delete(sprintf('%s%s', urldecode($request->input('path')), $request->input('image'))));
        $status = 'error';

        //dd($request->all());
        $file = sprintf('%s%s', storage_path('app/public/' . urldecode($request->input('path'))), $request->input('image'));

        if (!file_exists($file)) {
            return \GuzzleHttp\json_encode([
                'status' => $status,
                'name' => $request->input('image'),
                'message' => 'File does not exist.',
            ], 200);
        }

        if (unlink($file)) {
            $pathThumbnail = str_replace('images', 'thumbnails', $file);
            if (file_exists($pathThumbnail)) {
                unlink($pathThumbnail);
            }
            $status = 'success';
        }

        return \GuzzleHttp\json_encode([
            'status' => $status,
            'name' => $request->input('image'),
        ], 200);
    }
}
