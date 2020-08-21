<?php

namespace App\Http\Controllers;

use App\Mail\ThanksSubscribeMail;
use App\Models\Bike;
use App\Models\BikeBrand;
use App\Models\BikeType;
use App\Models\Member;
use App\User;
use Illuminate\Http\Request;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class BikeTypeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }


    public function saveBikeType(Request $request ){
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
            $getBikeBrand = BikeType::getByUUID($request->uuid);
            if(!count($getBikeBrand)){
                return [
                    "errors" => 1,
                    "msg" => trans('bikeType.noTypeFoundWithThisData')
                ];
            }
            BikeType::edit([
                "name_ar" => $request->name_ar,
                "name_en" => $request->name_en,
            ] , $request->uuid);
            return response()->json(['errors' => 0 , 'msg'=> trans('bikeType.bikeTypeUpdated')]);
        }else{
            BikeType::add([
                "name_ar" => $request->name_ar,
                "name_en" => $request->name_en,
            ]);
            return response()->json(['errors' => 0 , 'msg'=> trans('bikeType.newBikeTypeAdded')]);
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
         $bikeTypes = BikeType::get();
        return response()->json(['errors' => 0 , 'msg'=> $bikeTypes]);
    }


    public function deleteBikeType($uuid = '' , $lang = 'ar' ){
        if($lang == 'en'){
            app()->setLocale("en");
        }
        $user =  checkUserLogedInAndAdmin();
        if($user['errors'] == "1"){
            return $user;
        }
        $bikeType = BikeType::getByUUID($uuid);
        if(!count($bikeType)){
            return [
                'errors' => 1 ,
                'msg'=> trans('bikeType.noTypeFoundWithThisData')
            ];
        }
        BikeType::where("uuid" , $uuid)->delete();
        // the token is valid and we have found the user via the sub claim
        return response()->json(['errors' => 0 , 'msg'=> trans('bikeType.brandDeleted')]);
    }

}
