<?php

use  App\Enums\UsersEnums;
function isAdmin(){
    if(in_array(Auth::user()->type , UsersEnums::$systemIds)){
        return true;
    }
    return false;
}
