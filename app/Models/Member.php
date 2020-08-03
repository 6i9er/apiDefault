<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

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
        'id', 'name', 'email',   'uuid' , 'password' ,'gender' , 'username' ,
        'user_type', 'user_status', 'forget_token',   'profile_pic' ,
        'remember_token' , 'phone', 'updated_at',  'deleted_at', 'created_at',
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
        $inputs['uuid'] = Uuid::generate()->string;
        return Member::create($inputs);
    }
    public static function edit($inputs = [] , $userId = 0){
        return Member::where("uuid" , $userId)->update($inputs);
    }

}
