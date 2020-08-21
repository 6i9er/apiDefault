<?php

namespace App\Http\Controllers;

use App\Mail\ThanksSubscribeMail;
use App\Models\Bike;
use App\Models\BikeBrand;
use App\Models\BikeSubBrand;
use App\Models\Member;
use App\User;
use Illuminate\Http\Request;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class BikeSubBrandController extends Controller
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


    public function saveBikeSubBrand(Request $request ){
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
            'brand_id' => 'required|exists:bike_brand,uuid',
        ]);
        if ($validator->fails()) {
            return [
                "errors" => 1,
                "msg" => $validator->errors()->all()
            ];
        }
        if($request->uuid != ""){
            $getBikeBrand = BikeBrand::getByUUID($request->brand_id);
            $getBikeSubBrand = BikeSubBrand::getByUUID($request->uuid);
            if(!count($getBikeSubBrand)){
                return [
                    "errors" => 1,
                    "msg" => trans('bikeSubBrand.noSubBrandFoundWithThisData')
                ];
            }
            BikeSubBrand::edit([
                "name_ar" => $request->name_ar,
                "name_en" => $request->name_en,
                "brand_id" => $getBikeBrand->id,
            ] , $request->uuid);
            return response()->json(['errors' => 0 , 'msg'=> trans('bikeSubBrand.bikeSubBrandUpdated')]);
        }else{
            $getBikeBrand = BikeBrand::getByUUID($request->brand_id);
            BikeSubBrand::add([
                "name_ar" => $request->name_ar,
                "name_en" => $request->name_en,
                "brand_id" => $getBikeBrand->id,
            ]);
            return response()->json(['errors' => 0 , 'msg'=> trans('bikeSubBrand.newBikeSubBrandAdded')]);
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
         $bikeSubBrands = BikeSubBrand::get();

        return response()->json(['errors' => 0 , 'msg'=> $bikeSubBrands]);
    }


    public function deleteBikeSubBrand($uuid = '' , $lang = 'ar' ){
        if($lang == 'en'){
            app()->setLocale("en");
        }
        $user =  checkUserLogedInAndAdmin();
        if($user['errors'] == "1"){
            return $user;
        }
        $bikeSubBrand = BikeSubBrand::getByUUID($uuid);
        if(!count($bikeSubBrand)){
            return [
                'errors' => 1 ,
                'msg'=> trans('bikeSubBrand.noSubBrandFoundWithThisData')
            ];
        }
        BikeSubBrand::where("uuid" , $uuid)->delete();
        // the token is valid and we have found the user via the sub claim
        return response()->json(['errors' => 0 , 'msg'=> trans('bikeSubBrand.subBrandDeleted')]);
    }

}
