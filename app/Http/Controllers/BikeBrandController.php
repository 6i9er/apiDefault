<?php

namespace App\Http\Controllers;

use App\Mail\ThanksSubscribeMail;
use App\Models\Bike;
use App\Models\BikeBrand;
use App\Models\Member;
use App\User;
use Illuminate\Http\Request;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class BikeBrandController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth' , ['except' => ["saveForgetPassword" , "forgetPassword"]]);
    }


    public function saveBikeBrand(Request $request ){
        if($request['lang']){
            if($request['lang'] == 'en'){
                app()->setLocale("en");
            }
        }
        $user =  checkUserLogedInAndAdmin();
        if($user['errors'] == "1"){
            return $user;
        }
        $validator = Validator::make($request->all(), [
            'uuid' => '',
            'name_ar' => 'required',
            'name_en' => 'required',
        ]);
        if ($validator->fails()) {
            return [
                "errors" => 1,
                "msg" => $validator->errors()->all()
            ];
        }
        if($request->uuid != ""){
            $getBikeBrand = BikeBrand::getByUUID($request->uuid);
            if(!count($getBikeBrand)){
                return [
                    "errors" => 1,
                    "msg" => trans('bikeBrand.noBrandFoundWithThisData')
                ];
            }
            BikeBrand::edit([
                "name_ar" => $request->name_ar,
                "name_en" => $request->name_en,
            ] , $request->uuid);
            return response()->json(['errors' => 0 , 'msg'=> trans('bikeBrand.bikeBrandUpdated')]);
        }else{
            BikeBrand::add([
                "name_ar" => $request->name_ar,
                "name_en" => $request->name_en,
            ]);
            return response()->json(['errors' => 0 , 'msg'=> trans('bikeBrand.newBikeBrandAdded')]);
        }
    }

    public function index(Request $request ){
        if($request['lang']){
            if($request['lang'] == 'en'){
                app()->setLocale("en");
            }
        }
        $user =  checkUserLogedInAndAdmin();
        if($user['errors'] == "1"){
            return $user;
        }
         $bikeBrands = BikeBrand::get();

        return response()->json(['errors' => 0 , 'msg'=> $bikeBrands]);
    }


    public function deleteBikeBrand($uuid = '' , $lang = 'ar' ){
        if($lang == 'en'){
            app()->setLocale("en");
        }
        $user =  checkUserLogedInAndAdmin();
        if($user['errors'] == "1"){
            return $user;
        }
        $bikeBrand = BikeBrand::getByUUID($uuid);
        if(!count($bikeBrand)){
            return [
                'errors' => 1 ,
                'msg'=> trans('bikeBrand.noBrandFoundWithThisData')
            ];
        }
        BikeBrand::where("uuid" , $uuid)->delete();
        // the token is valid and we have found the user via the sub claim
        return response()->json(['errors' => 0 , 'msg'=> trans('bikeBrand.brandDeleted')]);
    }

}
