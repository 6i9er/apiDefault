<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class BikeType extends Model
{
    protected $table = 'bike_type';
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

    public static function add($inputs = []){
        $inputs['uuid'] = Uuid::generate()->string;
        return BikeType::create($inputs);
    }

    public static function edit($inputs = [] , $userId = 0){
        return BikeType::where("uuid" , $userId)->update($inputs);
    }


    public static function getByUUID($uuid = ''){
        return self::where("uuid" , $uuid)->first();
    }


}
