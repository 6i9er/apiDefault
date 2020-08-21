<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class BikeImage extends Model
{
    protected $table = 'bike_images';
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'image_path', 'bike_id','uuid' , 'updated_at',  'deleted_at', 'created_at',
    ];

    /**
     * @return all accounts order by id ASC
     */
    public static function getAll(){
        return self::all();
    }

    public static function add($inputs = []){
        $inputs['uuid'] = Uuid::generate()->string;
        return BikeImage::create($inputs);
    }

    public static function edit($inputs = [] , $userId = 0){
        return BikeImage::where("uuid" , $userId)->update($inputs);
    }

    public function bike(){
        return $this->belongsTo('App\Models\Bike', 'bike_id' );
    }
}
