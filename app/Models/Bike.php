<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Bike extends Model
{
    protected $table = 'bikes';
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'brand_id', 'sub_brand_id', 'type_id', 'model', 'cc' , 'uuid' , 'updated_at',  'deleted_at', 'created_at',
         'price' , 'mobile' , 'country',  'city', 'address','topic_type',
         'description' , 'user_id' , 'published',
    ];


    public static function add($inputs = []){
        $inputs['uuid'] = Uuid::generate()->string;
        return Bike::create($inputs);
    }

    public static function edit($inputs = [] , $userId = 0){
        return Bike::where("uuid" , $userId)->update($inputs);
    }

    public static function getByUUID($uuid = 0){
        return Bike::where("uuid" , $uuid)->with("user","brand","subBrand","type",'images')->first();
    }

    public static function getAllPusblishedBikes(){
        return Bike::where("published" , 1)->with("user","brand","subBrand","type")->get();
    }

    public static function getByUUIDForReview($uuid = 0){
        return Bike::where("uuid" , $uuid)->with("user","review","brand","subBrand","type")->first();
    }

    public static function getByUserID($userID = 0){
        return Bike::where("user_id" , $userID)->get();
    }

    public function user(){
        return $this->belongsTo('App\Models\Member', 'user_id' );
    }

    public function brand(){
        return $this->belongsTo('App\Models\BikeBrand', 'brand_id' );
    }

    public function subBrand(){
        return $this->belongsTo('App\Models\BikeSubBrand', 'sub_brand_id' );
    }
    public function type(){
        return $this->belongsTo('App\Models\BikeType', 'sub_brand_id' );
    }

    public function images(){
        return $this->hasMany('App\Models\BikeImage', 'bike_id' );
    }

    public function review(){
        return $this->hasOne('App\Models\BikeReview', 'bike_id' );
    }
}
