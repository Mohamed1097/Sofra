<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Contact;
use App\Models\FoodCategory;
use App\Models\Neighborhood;
use App\Models\Notification;
use App\Models\Token;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function getCities(Request $request)
    {
        
        return responseJson(1,'success',City::paginate());
    }
    public function getNeighborhoods(Request $request)
    {
        $validator=validator()->make($request->all(),['city_id'=>'required|exists:cities,id']);
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first());
        }
        return responseJson(1,'success',['Neighborhoods'=>Neighborhood::where('city_id',$request->city_id)->paginate()]);
    }
    public function getFoodCategories(Request $request)
    {
        return responseJson(1,'success',FoodCategory::paginate());
    }
    public function getSettings()
    {
        $settings=settings();
        if($settings)
        return responseJson(1,'success',$settings);
        return responseJson(0,'there is No Settings');
    }
    public function getNotifications(Request $request)
    {
        $notifications=$request->user()->notifications;
        if($notifications->count())
        {
            return responseJson(1,'success',$notifications);
        }
        return responseJson(0,'لا يوجد لديك اشعارات');
    }
    public function readNotification(Request $request)
    {
        $validator=validator()->make($request->all(),[
            'notification_id'=>'required|exists:notifications,id',
        ]);
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first());
        }
        $notification=Notification::find($request->notification_id);
        if(!$request->user()->notifications->find($notification->id)||$notification->is_read)
        {
            return responseJson(0,'there something wrong try again later');
        }
        $notification->is_read=1;
        $notification->save();
        return responseJson(1,'success');
    }
    public function registerToken(Request $request)
    {
        $validator=validator()->make($request->all(),[
            'type'=>'required|in:android,ios',
            'token'=>'required|string|unique:tokens,token'
        ]);
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first(),$validator->errors());
        }
        $request->user()->tokens()->create($request->all());
        $token=new Token();
        $token->tokenable()->associate($request->user());
        $token->type=$request->type;
        $token->token=$request->token;
        $token->save();
        return responseJson(1,'success',$token);
    }
    public function removeToken(Request $request)
    {
        $validator=validator()->make($request->all(),[
            'token'=>'required|string'
        ]);
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first(),$validator->errors());
        }
        $token=$request->user()->tokens->where('token',$request->token)->first()->toSql();
        dd($token);
        if ($token) 
        {
            $token->delete();
            return responseJson(1,'success');
        }
        return responseJson(0,'failed');
    }
    public function sendMessage(Request $request)
    {
        $validator=validator()->make($request->all(),['title'=>'required|string','message'=>'required|string']);
        if ($validator->fails()) {
            return responseJson(0,$validator->errors()->first(),$validator->errors());
        }
        $message=new Contact();
        $message->sender()->associate($request->user());
        $message->message_title=$request->title;
        $message->message=$request->message;
        $message->save();
        return responseJson(1,'send successful',$message);
    }
    public function test()
    {
       
    }
}
