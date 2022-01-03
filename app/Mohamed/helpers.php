<?php

use App\Models\Settings;
use Carbon\Carbon;

function responseJson($status,$message,$data=null){
    $response=[
        'status'=>$status,
        'message'=>$message,
        'data'=>$data
    ];
    return response()->json($response);

}
function assoc($array){
    $array=array_unique($array);
    $new_array = [];
    $i=0;
    foreach ($array as $value) 
        {
            $new_array['item'.$i] = $value;
            $i++;
        }
    return $new_array;
}
function notifyByFirebase($title,$body,$tokens,$data = [])        // paramete 5 =>>>> $type
{
    $registrationIDs = $tokens;
    $fcmMsg = array(
        'body' => $body,
        'title' => $title,
        'sound' => "default",
        'color' => "#203E78"
    );
    $fcmFields = array(
        'registration_ids' => $registrationIDs,
        'priority' => 'high',
        'notification' => $fcmMsg,
        'data' => $data
    );
    //dd(env('FIREBASE_API_ACCESS_KEY'));
    $headers = array(
         'Authorization: key='.env('FIREBASE_API_ACCESS_KEY'),
         'Content-Type: application/json'
     );



    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
function getUrl($route,$needle)
{
    $route=explode('/',$route);
    return in_array($needle,$route);
}
function checkChange($rules,$request,$model)
{
    $notChanged=[];
    $postData=[];
    foreach($rules as $key =>$rule)
    {
        if(isset($request[$key])&&isset($model[$key]))
        {
          if($request[$key]==$model[$key])
          {
              array_push($notChanged,$key);
              unset($rules[$key]);
          }
        }
    }
    $postData=['rules'=>$rules,'notChanged'=>$notChanged];
    return $postData;
   
}
function settings()
{
    $settings=Settings::first();
    return $settings;

}
// function now()
// {
//     return Carbon::now();
// }

// function smsMisr($to, $message)
// {
//     $url = 'https://smsmisr.com/api/webapi/?';
//     $push_payload = array(
//         "username" => "*****",
//         "password" => "*****",
//         "language" => "2",
//         "sender" => "ipda3",
//         "mobile" => '2' . $to,
//         "message" => $message,
//     );

//     $rest = curl_init();
//     curl_setopt($rest, CURLOPT_URL, $url.http_build_query($push_payload));
//     curl_setopt($rest, CURLOPT_POST, 1);
//     curl_setopt($rest, CURLOPT_POSTFIELDS, $push_payload);
//     curl_setopt($rest, CURLOPT_SSL_VERIFYPEER, true);  //disable ssl .. never do it online
//     curl_setopt($rest, CURLOPT_HTTPHEADER,
//         array(
//             "Content-Type" => "application/x-www-form-urlencoded"
//         ));
//     curl_setopt($rest, CURLOPT_RETURNTRANSFER, 1); //by ibnfarouk to stop outputting result.
//     $response = curl_exec($rest);
//     curl_close($rest);
//     return $response;
// }