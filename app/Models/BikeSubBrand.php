<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class BikeSubBrand extends Model
{
    protected $table = 'bike_sub_brand';
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name_ar', 'name_en','uuid','brand_id' , 'updated_at',  'deleted_at', 'created_at',
    ];

    /**
     * @return all accounts order by id ASC
     */
    public static function getAll(){
        return self::all();
    }

    public static function add($inputs = []){
        $inputs['uuid'] = Uuid::generate()->string;
        return BikeSubBrand::create($inputs);
    }

    public static function edit($inputs = [] , $userId = 0){
        return BikeSubBrand::where("uuid" , $userId)->update($inputs);
    }

    public static function getByUUID($userId = 0){
        return BikeSubBrand::where("uuid" , $userId)->first();
    }

    public function brand(){
        return $this->belongsTo('App\Models\BikeBrand', 'brand_id' );
    }
}
