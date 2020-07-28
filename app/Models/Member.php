<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    protected $table = 'users';
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'center_id', 'name', 'email', 'account_id', 'type' , 'uuid' , 'password' , 'justRegistered', 'updated_at',  'deleted_at', 'created_at',
    ];

    /**
     * @return all accounts order by id ASC
     */
    public static function getAll(){
        return self::all();
    }

    public static function getAllOrderByIdDesc(){
        return self::orderBy('id' , "DESC")->where("id" , "!=" , "1")->get();
    }
    public static function getAllOrderByIdDescByPagination($numberOfPage = 5){
        return self::orderBy('id' , "DESC")->where("id" , "!=" , "1")->paginate($numberOfPage);
    }

    public static function getAllOrderByIdDescByAccountid($account_id){
        return self::orderBy('id' , "DESC")->where("id" , "!=" , "1")->where("account_id" , $account_id)->get();
    }

    public static function getAllOrderByIdDescByAccountidByPagination($account_id , $numberOfPage = 5){
        return self::orderBy('id' , "DESC")->where("id" , "!=" , "1")->where("account_id" , $account_id)->paginate($numberOfPage);
    }

    public static function getUserByEmail($email = '' ,$account_id = 0){
//        return $account_id;
        return self::where('email' , $email)->where("account_id" , $account_id)->get();
    }

    public static function getUserByUuId($uuId){
        return self::where('uuid' , $uuId)->first();
    }

    public static function updateUser($uuId , $data){
        return self::where('uuid' , $uuId)->update($data);
    }

    public static function add($inputs = []){
        return Member::create($inputs);
    }
    public static function edit($inputs = [] , $userId = 0){
        return Member::where("uuid" , $userId)->update($inputs);
    }

    // Relations
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function center(){
        return $this->belongsTo('App\Models\Center' , 'center_id' );
    }

    public function account(){
        return $this->belongsTo('App\Models\Account' , 'account_id' );
    }
}
