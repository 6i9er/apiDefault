<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $table = 'rooms';
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name_ar', 'name_en', 'price', 'picture', 'type', 'updated_at',  'deleted_at', 'created_at',
    ];


}
