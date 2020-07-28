<?php
function getUrlPath($urlPath){
    return url(''). "/" . App::getLocale() . "/" .$urlPath;
}
function getPageNameForTitle($hasParameter = '0' , $titleName = ''){
//    return count(explode( url(App::getLocale()), url()->current() ));
    if($hasParameter == '0') {
        if(count(explode( url(App::getLocale()), url()->current() )) > 1){
            return str_replace("/" , "" , explode( url(App::getLocale()), url()->current() )[1] );
        }else{
            return "Wrong Page";
        }
    }else {
        return $titleName;
    }
//    echo url(''). "/" . App::getLocale() . "/" .$urlPath;
}
