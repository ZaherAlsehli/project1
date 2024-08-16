<?php
namespace app\Traits;

trait ApiResponse{
    
    public function rseponse($data=null,$message=null,$status=null){
    $array=[
    'data'=>$data,
    'message'=>$message,
    'status'=>$status,];
    
    return response($array,$status);}
}
