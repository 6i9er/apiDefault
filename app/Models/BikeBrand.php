<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class BikeBrand extends Model
{
    protected $table = 'bike_brand';
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name_ar', 'name_en','uuid' , 'updated_at',  'deleted_at', 'created_at',
    ];

    /**
     * @return all accounts order by id ASC
     */
    public static function getAll(){
        return self::all();
    }

    public static function getByUUID($uuid = ''){
        return self::where("uuid" , $uuid)->first();
    }

    public static function add($inputs = []){
        $inputs['uuid'] = Uuid::generate()->string;
        return BikeBrand::create($inputs);
    }

    public static function edit($inputs = [] , $userId = 0){
        return BikeBrand::where("uuid" , $userId)->update($inputs);
    }
}
