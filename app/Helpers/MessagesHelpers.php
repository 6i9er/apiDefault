<?php
/**
 * messages used to flash message
 * @param array $errors
 * @param int $type 1-success 2-error 3-worning 4- error important with button to close
 *                  5- Modal 6- success important with button to close
 *                  7- Worning important with button to close , 8-only msg
 * @param string $modaltitle
 */
function errorMessage($errors = [] , $type = 1 ,$modaltitle=""){
    if($type == 5){
        flash()->overlay(getErrorMessageDataForLiTag($errors ), $modaltitle);
        return "aaaaaaa";
    }else{
        foreach ($errors as $error){
            pushMessage($error , $type);
        }
    }

}

function pushMessage($message = '' , $type = 1){
    if($type == 1){//Success
        flash($message)->success();
    }elseif ($type == 2){
        flash($message)->error();
    }elseif ($type == 3){
        flash($message)->warning();
    }elseif ($type == 4){
        flash($message)->error()->important();
    }elseif ($type == 6){
        flash($message)->success()->important();
    }elseif ($type == 7){
        flash($message)->warning()->important();
    }elseif ($type == 8){
        return $message[0];
    }
}
function getErrorMessageDataForLiTag($messages , $messageType = 'danger'){
    $data = "<ul class='alert alert-".$messageType."'>";
    foreach ($messages as $message){
        $data.= "<li>". $message . "</li>";
    }
    $data.= "</ul>";
    return $data;
}
