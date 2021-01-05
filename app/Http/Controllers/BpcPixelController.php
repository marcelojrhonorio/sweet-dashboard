<?php

namespace App\Http\Controllers;

use Browser;
use App\Models\BpcLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class BpcPixelController extends Controller
{
    public function pixelDispatch(Request $request)
    {
        $default = 'unknown';

        $bpcLead = new BpcLead();
        $bpcLead->ip_address      = $request->ip()            ?? $default;
        $bpcLead->sub_id          = $request->query('sub_id') ?? $default;
        $bpcLead->browser_name    = Browser::browserName()    ?? $default;
        $bpcLead->browser_family  = Browser::browserFamily()  ?? $default;
        $bpcLead->platform_name   = Browser::platformName()   ?? $default;
        $bpcLead->platform_family = Browser::platformFamily() ?? $default;
        $bpcLead->device_family   = Browser::deviceFamily()   ?? $default;
        $bpcLead->device_model    = Browser::deviceModel()    ?? $default;
        $bpcLead->save();

        return self::returnPixel();
    }

    private static function returnPixel()
    {
        $image="\x47\x49\x46\x38\x37\x61\x1\x0\x1\x0\x80\x0\x0\xfc\x6a\x6c\x0\x0\x0\x2c\x0\x0\x0\x0\x1\x0\x1\x0\x0\x2\x2\x44\x1\x0\x3b";
        return \response($image,200)->header('Content-Type', 'image/gif');
    }

    public function pixelDispatchApproved(Request $request)
    {
        $bpcLead = BpcLead::where('ip_address', $request->ip())->first() ?? null;

        if (null != $bpcLead) {
            $bpcLead->status = 'approved';
            $bpcLead->update();
        }

        return self::returnPixelApproved();
    }

    private static function returnPixelApproved()
    {
        $image="\x47\x49\x46\x38\x37\x61\x1\x0\x1\x0\x80\x0\x0\xfc\x6a\x6c\x0\x0\x0\x2c\x0\x0\x0\x0\x1\x0\x1\x0\x0\x2\x2\x44\x1\x0\x3b";
        return \response($image,200)->header('Content-Type', 'image/gif');
    }
}
