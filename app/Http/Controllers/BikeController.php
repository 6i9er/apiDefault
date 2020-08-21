<?php

namespace App\Http\Controllers;

use App\Mail\ThanksSubscribeMail;
use App\Models\Bike;
use App\Models\BikeBrand;
use App\Models\BikeReview;
use App\Models\BikeSubBrand;
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

class BikeController extends Controller
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


    public function saveBikeForSale(Request $request ){
        if($request['lang']){
            if($request['lang'] == 'en'){
                app()->setLocale("en");
            }
        }
        $user =  checkUserLogedIn();
        if($user['errors'] == "1"){
            return $user;
        }
        $validator = Validator::make($request->all(), [
            'uuid' => '',
            'brand_id' => 'required|exists:bike_brand,uuid',
            'sub_brand_id' => 'required|exists:bike_sub_brand,uuid',
            'type_id' => 'required|exists:bike_type,uuid',
            'model' => 'required',
            'cc' => 'required',
            'price' => 'required',
            'mobile' => 'required',
            'country' => 'required',
            'city' => 'required',
            'address' => '',
            'description' => '',
            'published' => '', // not sent
            'user_id' => '', //not sent
        ]);
        if ($validator->fails()) {
            return [
                "errors" => 1,
                "msg" => $validator->errors()->all()
            ];
        }

        $getBikeBrand = BikeBrand::getByUUID($request->brand_id);
        $getBikeSubBrand = BikeSubBrand::getByUUID($request->sub_brand_id);
        $getBikeType = BikeType::getByUUID($request->type_id);
        if($request->uuid != ""){
            $getBike = Bike::getByUUID($request->uuid);
            if(!count($getBike)){
                return [
                    "errors" => 1,
                    "msg" => trans('bike.noBikeFoundWithThisData')
                ];
            }
            Bike::edit([
               "brand_id" => $getBikeBrand->id,
               "sub_brand_id" => $getBikeSubBrand->id ,
               "type_id" => $getBikeType->id ,
               "model" => $request->model,
               "cc" => $request->cc,
               "price" => $request->price,
               "mobile" => $request->mobile,
               "country" => $request->country,
               "city" => $request->city,
               "address" => $request->address,
               "description" => $request->description,
               "published" => 0,
            ] , $request->uuid);
            return response()->json(['errors' => 0 , 'msg'=> trans('bike.bikeUpdated')]);
        }else{
            Bike::add([
                "brand_id" => $getBikeBrand->id,
                "sub_brand_id" => $getBikeSubBrand->id ,
                "type_id" => $getBikeType->id ,
                "model" => $request->model,
                "cc" => $request->cc,
                "price" => $request->price,
                "mobile" => $request->mobile,
                "country" => $request->country,
                "city" => $request->city,
                "address" => $request->address,
                "description" => $request->description,
                "user_id" => $user['msg']->id,
                "published" => 0,
            ]);
            return response()->json(['errors' => 0 , 'msg'=> trans('bike.newBikeAdded')]);
        }
    }

    public function saveBikeForReview(Request $request ){
        if($request['lang']){
            if($request['lang'] == 'en'){
                app()->setLocale("en");
            }
        }
        $user =  checkUserLogedIn();
        if($user['errors'] == "1"){
            return $user;
        }
        $validator = Validator::make($request->all(), [
            'uuid' => '',
            'brand_id' => 'required|exists:bike_brand,uuid',
            'sub_brand_id' => 'required|exists:bike_sub_brand,uuid',
            'type_id' => 'required|exists:bike_type,uuid',
            'model' => 'required',
            'cc' => 'required',
            'price' => 'required',
            'mobile' => 'required',
            'country' => 'required',
            'city' => 'required',
            'address' => '',
            'description' => '',
            'published' => '', // not sent
            'user_id' => '', //not sent
            'url' => 'required'
        ]);
        if ($validator->fails()) {
            return [
                "errors" => 1,
                "msg" => $validator->errors()->all()
            ];
        }

        $getBikeBrand = BikeBrand::getByUUID($request->brand_id);
        $getBikeSubBrand = BikeSubBrand::getByUUID($request->sub_brand_id);
        $getBikeType = BikeType::getByUUID($request->type_id);
        if($request->uuid != ""){
            $getBike = Bike::getByUUID($request->uuid);
            if(!count($getBike)){
                return [
                    "errors" => 1,
                    "msg" => trans('bike.noBikeFoundWithThisData')
                ];
            }

            $getVideoURL = explode("?v=" , $request->url);
            if(count($getVideoURL) < "2"){
                return [
                    "errors" => 1,
                    "msg" => trans('bike.pleaseEnterYoutubeURL')
                ];
            }
            $checkURL = BikeReview::where("url" , $getVideoURL[1])->first();
            if(count($checkURL)){
                if($checkURL->bike_id != $getBike->id){
                    return [
                        "errors" => 1,
                        "msg" => trans('bike.sorryThisVideoAlreadyExist')
                    ];
                }
            }


            Bike::edit([
               "brand_id" => $getBikeBrand->id,
               "sub_brand_id" => $getBikeSubBrand->id ,
               "type_id" => $getBikeType->id ,
               "model" => $request->model,
               "cc" => $request->cc,
               "price" => $request->price,
               "mobile" => $request->mobile,
               "country" => $request->country,
               "city" => $request->city,
               "address" => $request->address,
               "description" => $request->description,
               "published" => 0,
            ] , $request->uuid);



            $getReviewURL = BikeReview::where("bike_id" , $getBike->id)->first();
            if(count($getReviewURL)){
                BikeReview::editByBikeID([
                    "url" => $getVideoURL[0],
                ] , $getBike->id);
            }else{
                BikeReview::add([
                    "bike_id" => $getBike->id,
                    'url' => $getVideoURL[1],
                ]);
            }

            return response()->json(['errors' => 0 , 'msg'=> trans('bike.bikeUpdated')]);
        }else{
            $getVideoURL = explode("?v=" , $request->url);
            if(count($getVideoURL) < "2"){
                return [
                    "errors" => 1,
                    "msg" => trans('bike.pleaseEnterYoutubeURL')
                ];
            }
            $checkURL = BikeReview::where("url" , $getVideoURL[1])->first();
            if(count($checkURL)){
                return [
                    "errors" => 1,
                    "msg" => trans('bike.sorryThisVideoAlreadyExist')
                ];
            }
         $newBike =    Bike::add([
                "brand_id" => $getBikeBrand->id,
                "sub_brand_id" => $getBikeSubBrand->id ,
                "type_id" => $getBikeType->id ,
                "model" => $request->model,
                "cc" => $request->cc,
                "price" => $request->price,
                "mobile" => $request->mobile,
                "country" => $request->country,
                "city" => $request->city,
                "address" => $request->address,
                "description" => $request->description,
                "user_id" => $user['msg']->id,
                "published" => 0,
                "topic_type" => 2,
            ]);

            BikeReview::add([
                "bike_id" => $newBike->id,
                'url' => $getVideoURL[1],
            ]);
            return response()->json(['errors' => 0 , 'msg'=> trans('bike.newBikeAdded')]);
        }
    }

    public function index(Request $request ){
        if($request['lang']){
            if($request['lang'] == 'en'){
                app()->setLocale("en");
            }
        }
        $userData =  checkUserLogedIn();
        $user = array();
        if($userData['errors'] != "1"){
            $user = $userData['msg'];
        }
        $bikes = Bike::getAllPusblishedBikes();
        return [
            'errors' => 0 ,
            'bikes'=> $bikes,
            'user'=> $user
        ];
    }


    public function getUserBikes($uuid = '' , $lang = 'ar' ){
        if($lang == 'en'){
            app()->setLocale("en");
        }
//        $user =  checkUserLogedIn();
//        if($user['errors'] == "1"){
//            return $user;
//        }
        $getUser = Member::getUserByUuId($uuid);
        if(!count($getUser)){
            return[
              "errors" => 1,
              "msg" => trans('bikes.noUserFoundWithThisData')
            ];
        }
        $getUserBikes = Bike::getByUserID($getUser->id);

        return [
            'errors' => 0 ,
            'msg'=> $getUserBikes
        ];
    }

    public function getMyBikes($lang = 'ar' ){
        if($lang == 'en'){
            app()->setLocale("en");
        }
        $userData =  checkUserLogedIn();
        $user = array();
        if($userData['errors'] == "1"){
            return $userData;
        }else{
            $user = $userData['msg'];
        }
        $getUserBikes = Bike::getByUserID($user->id);

        return [
            'errors' => 0 ,
            'msg'=> $getUserBikes,
            'user'=> $user
        ];
    }

    public function getBikeForSale($uuid = '' ,$lang = 'ar' ){
        if($lang == 'en'){
            app()->setLocale("en");
        }
        $userData =  checkUserLogedIn();
        $user = array();
        if($userData['errors'] == "1"){
//            return $userData;
        }else{
            $user = $userData['msg'];
        }
        $getBike = Bike::getByUUID($uuid);

        return [
            'errors' => 0 ,
            'msg'=> $getBike,
            'user'=> $user
        ];
    }

    public function getBikeForReview($uuid = '' ,$lang = 'ar' ){
        if($lang == 'en'){
            app()->setLocale("en");
        }
        $userData =  checkUserLogedIn();
        $user = array();
        if($userData['errors'] == "1"){
//            return $userData;
        }else{
            $user = $userData['msg'];
        }
        $getBike = Bike::getByUUIDForReview($uuid);

        return [
            'errors' => 0 ,
            'msg'=> $getBike,
            'user'=> $user
        ];
    }

    public function publishBike($uuid = '' ,$lang = 'ar' ){
        if($lang == 'en'){
            app()->setLocale("en");
        }
        $userData =  checkUserLogedInAndAdmin();
        $user = array();
        if($userData['errors'] == "1"){
            return $userData;
        }else{
            $user = $userData['msg'];
        }
        $getBike = Bike::getByUUID($uuid);
        if(!count($getBike)){
            return [
              "errors" => 1,
              "msg" => trans('bike.noBikeFoundWithThisData')
            ];
        }
        Bike::edit([
            "published" => 1
        ] , $uuid);

        return [
            'errors' => 0 ,
            'msg'=> trans('bike.bikePublished'),
        ];
    }

    public function unPublishBike($uuid = '' ,$lang = 'ar' ){
        if($lang == 'en'){
            app()->setLocale("en");
        }
        $userData =  checkUserLogedInAndAdmin();
        $user = array();
        if($userData['errors'] == "1"){
            return $userData;
        }else{
            $user = $userData['msg'];
        }
        $getBike = Bike::getByUUID($uuid);
        if(!count($getBike)){
            return [
              "errors" => 1,
              "msg" => trans('bike.noBikeFoundWithThisData')
            ];
        }
        Bike::edit([
            "published" => 0
        ] , $uuid);

        return [
            'errors' => 0 ,
            'msg'=> trans('bike.bikeUnPublished'),
        ];
    }

//    public function deleteBikeSubBrand($uuid = '' , $lang = 'ar' ){
//        if($lang == 'en'){
//            app()->setLocale("en");
//        }
//        $user =  checkUserLogedInAndAdmin();
//        if($user['errors'] == "1"){
//            return $user;
//        }
//        $bikeSubBrand = BikeSubBrand::getByUUID($uuid);
//        if(!count($bikeSubBrand)){
//            return [
//                'errors' => 1 ,
//                'msg'=> trans('bikeSubBrand.noSubBrandFoundWithThisData')
//            ];
//        }
//        BikeSubBrand::where("uuid" , $uuid)->delete();
//        // the token is valid and we have found the user via the sub claim
//        return response()->json(['errors' => 0 , 'msg'=> trans('bikeSubBrand.subBrandDeleted')]);
//    }

}
