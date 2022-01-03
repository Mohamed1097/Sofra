<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use Carbon\Carbon;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator= validator()->make($request->all(),[
            'name'=>'required|string|min:2',
            'email'=> 'required|email:rfc,dns|unique:clients,email',
            'password' => ['required', 
            'min:8','string','confirmed'],
            'phone'=>'required|regex:/(01)[0-9]{9}/|digits:11|unique:clients,phone',
            'neighborhood_id'=>'required|exists:neighborhoods,id',
            'city_id'=>'required|exists:cities,id'
        ]);
        if($validator->fails())
        {
           return responseJson(0,[$validator->errors()->first()],$validator->errors());    
        }
        $client=Client::create($request->all());
        $client->api_token=Str::random(60);
        $client->password=bcrypt($request->password);
        $client->save();
        return responseJson('1','تمت الاضافه بنجاح',['api_token'=>$client->api_token,'client'=>$client]);  
    }
    public function login(Request $request)
    { 
        $validator= validator()->make($request->all(),[
            'phone'=>'required',
            'password' => 'required', 
        ]); 
        if($validator->fails())
        {
            return responseJson('0','بيناتك خاطئ'); 
        }
        $client=Client::where('phone',$request->phone)->first();
        if($client)
        {
            if(Hash::check($request->password,$client->password))
            {
                if (!$client->is_active) {
                    return responseJson(0,'غير مسموح لك بدخول');
                }
                $client->api_token=Str::random(60);
                $client->save();
                return responseJson('1','تم تسجيل الدخول بنجاح',['api_token'=>$client->api_token,'client'=>$client]);
            }
            else
            {
                return responseJson('0','بيناتك خاطئ'); 
            }
        }
        else
        {
            return responseJson('0','بيناتك خاطئ');
        }
        
    }
    public function getProfile(Request $request)
    {
        return responseJson(1,'success',Client::with('city','neighborhood')->findOrFail($request->user()->id));
    }
    public function editProfile(Request $request)
    {
        $validator= validator()->make($request->all(),[
            'name'=>'required|string|min:2',
            'email'=> 'required|email:rfc,dns|unique:clients,email,'.$request->user()->id,
            'phone'=>'required|regex:/(01)[0-9]{9}/|digits:11|unique:clients,phone,'.$request->user()->id,
            'neighborhood_id'=>'required|exists:neighborhoods,id',
            'city_id'=>'required|exists:cities,id'
        ]);
        if($validator->fails())
        {
           return responseJson(0,[$validator->errors()->first()],$validator->errors());    
        }
        $client=$request->user();
        if($client->update($request->all()))
        {
            return responseJson(1,'success',$client);
        }
        return responseJson(0,'there something wrong try again later');  
    }
    public function forgetPassword(Request $request)
    {
        $validator=validator()->make($request->all(),['phone'=>'required|exists:clients,phone']);
        if($validator->fails())
        {
            return responseJson(0,'there something wrong try again later');
        }
        $client=Client::where('phone',$request->phone)->first();
        $client->pin_code=rand(100000, 999999);
        $client->expired_code_date=Carbon::now()->addMinutes(10)->toDateTimeString();
        $client->save();
        Mail::to($client->email)
                ->bcc('mi0530838@gmail.com') 
                ->send(new ResetPassword($client));
        if(!count(Mail::failures()))
        {
            return responseJson(1,'please,Check Your Email ,The code is valid for ten minutes');
        }
        return responseJson(0,'there something wrong try again later');
    }
    public function resetPassword(Request $request)
    {
        $validator=validator()->make($request->all(),['code'=>'required','password'=>'required|string|min:8|confirmed']);
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first(),$validator->errors());
        }
        $client=Client::where('pin_code',$request->code)->first();
        if($client)
        {
            if($client->expired_code_date>Carbon::now()->toDateTimeString())
            {
                $client->pin_code=null;
                $client->expired_code_date=null;
                $client->password=bcrypt($request->password);
                $client->save();
                return responseJson(1,'تم تعديل كلمة المرور بنجاح');
            }
            $client->pin_code=null;
            $client->expired_code_date=null;
            return responseJson(0,'Your code is expired, try again later');
        }
        return responseJson(0,'there something wrong try again later'); 
    }
}
