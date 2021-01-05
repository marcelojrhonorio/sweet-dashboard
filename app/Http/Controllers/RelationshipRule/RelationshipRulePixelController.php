<?php

namespace App\Http\Controllers\RelationshipRule;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Jobs\RelationshipRuleJob;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Queue;
use App\Models\RelationshipRules\RelationshipRule;
use App\Models\RelationshipRules\RelationshipRuleCustomers;

class RelationshipRulePixelController extends Controller
{
    //
    public function pixelDispatch(Request $request,string $typeDispatch, string $email, int $value, int $delay)
    {
        $c = Customer::where('email',$email)->first();
        $relationshipRule = ('order' === $typeDispatch ? self::getRelationShipRuleOrder($value) :  RelationshipRule::find($value));
        
        $rc = (isset($relationshipRule->id) && isset($c->id)) ? 
                RelationshipRuleCustomers::where('customer_id',$c->id)
                    ->where('relationship_rule_id', $relationshipRule->id)->first() : 
                    null;

        if((!$rc && isset($relationshipRule->id)) && isset($c->id)){

            $job = new RelationshipRuleJob($relationshipRule->html_message, $relationshipRule->subject, $c->id);
            Queue::pushOn('sweetmedia_relationship_rule_pixel', $job);
            RelationshipRuleCustomers::create(['relationship_rule_id'=>$relationshipRule->id,'customer_id'=>$c->id]);
        }

        return self::returnPixel();
    }

    private static function getRelationShipRuleOrder (int $order) {
        $rr = RelationshipRule::where('order', $order)
                ->where('enabled', true)
                ->first();

        if(null === $rr) {
            $rr = RelationshipRule::where('order', '>', $order)
                    ->where('enabled', true)
                    ->first();
        }

        return $rr;
    }

    private static function returnPixel()
    {
        $image="\x47\x49\x46\x38\x37\x61\x1\x0\x1\x0\x80\x0\x0\xfc\x6a\x6c\x0\x0\x0\x2c\x0\x0\x0\x0\x1\x0\x1\x0\x0\x2\x2\x44\x1\x0\x3b";
        return \response($image,200)->header('Content-Type', 'image/gif');
    }
}
