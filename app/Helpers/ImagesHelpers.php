<?php



function uploadSingleImage($file , $destination , $filename , $deleteFile = "1" , $allowedExtenssions = ['jpeg','jpg','png']){
    $allowedfileExtension = $allowedExtenssions;
    $destinationPath = public_path().$destination;
    $extension = $file->getClientOriginalExtension();
    if(in_array($extension,$allowedfileExtension)){
        if($deleteFile == "1"){
            if($filename != ""){
                if(file_exists($destinationPath."/".$filename)){
                    unlink($destinationPath."/".$filename);
                }
            }
        }
        $filename = "cms-".time().rand(000,999)."-".\Webpatser\Uuid\Uuid::generate()->string.".".$extension;
        $file->move($destinationPath , $filename);
        return $filename;
    }
}

