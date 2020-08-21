<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class BikeReview extends Model
{
    protected $table = 'bike_reviews';
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'url', 'bike_id','uuid' , 'updated_at',  'deleted_at', 'created_at',
    ];

    /**
     * @return all accounts order by id ASC
     */
    public static function getAll(){
        return self::all();
    }

    public static function add($inputs = []){
        $inputs['uuid'] = Uuid::generate()->string;
        return BikeReview::create($inputs);
    }

    public static function edit($inputs = [] , $userId = 0){
        return BikeReview::where("uuid" , $userId)->update($inputs);
    }
    public static function editByBikeID($inputs = [] , $bikeId = 0){
        return BikeReview::where("bike_id" , $bikeId)->update($inputs);
    }

    public function bike(){
        return $this->belongsTo('App\Models\Bike', 'bike_id' );
    }
}
