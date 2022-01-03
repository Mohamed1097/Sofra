<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $path='images/'.date('Y').'/'.date('M').'/'.date('d');
        $validator= validator()->make($request->all(),[
            'name'=>'required|string|min:2',
            'email'=> 'required|email:rfc,dns|unique:restaurants,email',
            'password' => ['required','min:8','string','confirmed'],
            'phone'=>'required|regex:/(01)[0-9]{9}/|digits:11|unique:restaurants,phone',
            'neighborhood_id'=>'required|exists:neighborhoods,id',
            'city_id'=>'required|exists:cities,id',
            'min_charge'=>'required|between:0,99.99',
            'delivery_price'=>'required|between:0,99.99',
            'contact_phone'=>'required|regex:/(01)[0-9]{9}/|digits:11|unique:restaurants,contact_phone',
            'contact_whatsapp'=>'required|regex:/(01)[0-9]{9}/|digits:11|unique:restaurants,contact_whatsapp',
            'restaurant_image'=>'required|mimes:jpeg,jpg,png,gif|max:2000000',
            'food_category'=>'required|array',
            'food_category.*'=>'exists:food_categories,id|distinct|integer'
        ]);
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first(),$validator->errors());    
        }
        $imageName = $request->restaurant_image->hashName();
        $request->restaurant_image->move(public_path($path), $imageName);
        $restaurant=Restaurant::create($request->all());
        $restaurant->password=bcrypt($request->password);
        $restaurant->api_token=Str::random(60);
        $restaurant->restaurant_image=$path.'/'.$imageName;
        $restaurant->foodCategory()->attach($request->food_category);
        $restaurant->save();
        $restaurant->payment()->create(['amount'=>0]);
        return responseJson('1','تمت الاضافه بنجاح',['api_token'=>$restaurant->api_token,'restaurant'=>$restaurant]);  
    }
    public function login(Request $request)
    { 
        $validator= validator()->make($request->all(),[
            'phone'=>'required',
            'password' => 'required', 
        ]); 
        if($validator->fails())
        {
            return responseJson('0','بيناتك خاطئ1');  
        }
        $restaurant=Restaurant::where('phone',$request->phone)->first();
        if($restaurant)
        {
            if(Hash::check($request->password,$restaurant->password))
            {
                $restaurant->api_token=Str::random(60);
                $restaurant->save();
                return responseJson('1','تم تسجيل الدخول بنجاح',['api_token'=>$restaurant->api_token,'restaurant'=>$restaurant]);
            }
            return responseJson('0','بيناتك خاطئ');  
        }
        return responseJson('0','بيناتك خاطئ');
        
    }
    public function getProfile(Request $request)
    {
        return responseJson(1,'success',Restaurant::with('city','neighborhood')->findOrFail($request->user()->id));
    }
    public function editProfile(Request $request)
    {
        $validator= validator()->make($request->all(),[
            'name'=>'required|string|min:2',
            'email'=> 'required|email:rfc,dns|unique:restaurants,email,'.$request->user()->id,
            'password' => ['min:8','string','confirmed'],
            'phone'=>'required|regex:/(01)[0-9]{9}/|digits:11|unique:restaurants,phone,'.$request->user()->id,
            'neighborhood_id'=>'required|exists:neighborhoods,id',
            'city_id'=>'required|exists:cities,id',
            'min_charge'=>'required|between:0,99.99',
            'delivery_price'=>'required|between:0,99.99',
            'contact_phone'=>'required|regex:/(01)[0-9]{9}/|digits:11|unique:restaurants,contact_phone,'.$request->user()->id,
            'contact_whatsapp'=>'required|regex:/(01)[0-9]{9}/|digits:11|unique:restaurants,contact_whatsapp,'.$request->user()->id,
            'restaurant_image'=>'mimes:jpeg,jpg,png,gif|max:2000000',
            'food_category'=>'required|array',
            'food_category.*'=>'exists:food_categories,id|distinct|integer',
            'is_opened'=>'required|boolean'
        ]);
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first(),$validator->errors());    
        }
        $restaurant=$request->user();
        if($request->has('restaurant_image'))
        {
            if(FacadesFile::exists(public_path($restaurant->restaurant_image)))
            {
                FacadesFile::delete(public_path($restaurant->restaurant_image)); 
            }
            $path='images/'.date('Y').'/'.date('M').'/'.date('d');
            $imageName = $request->restaurant_image->hashName();
            $request->restaurant_image->move($path, $imageName);
            $restaurant->update($request->except('id'));
            $restaurant->restaurant_image=$path.'/'.$imageName;
            $restaurant->save();
        }
        if($request->has('password'))
        {
            $restaurant->password=bcrypt($request->password);
            $restaurant->save(); 
        }
        $restaurant->update($request->except(['password','restaurant_image']));
        $restaurant->save();
        return responseJson(1,'success',$restaurant);
    }
    public function forgetPassword(Request $request)
    {
        $validator=validator()->make($request->all(),['phone'=>'required|exists:restaurants,phone']);
        if($validator->fails())
        {
            return responseJson(0,'there something wrong try again later');
        }
        $restaurant=Restaurant::where('phone',$request->phone)->first();
        $restaurant->pin_code=rand(100000, 999999);
        $restaurant->expired_code_date=Carbon::now()->addMinutes(10)->toDateTimeString();
        $restaurant->save();
        Mail::to($restaurant->email)
                ->bcc('mi0530838@gmail.com') 
                ->send(new ResetPassword($restaurant));
        if(!count(Mail::failures()))
        {
            return responseJson(1,'please,Check Your Email, The code is valid for ten minutes');
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
        $restaurant=Restaurant::where('pin_code',$request->code)->first();
        if($restaurant)
        {
            if($restaurant->expired_code_date>Carbon::now()->toDateTimeString())
            {
                $restaurant->pin_code=null;
                $restaurant->expired_code_date=null;
                $restaurant->password=Hash::make($request->password);
                $restaurant->save();
                return responseJson(1,'تم تعديل كلمة المرور بنجاح');
            }
            $restaurant->pin_code=null;
            $restaurant->expired_code_date=null;
            return responseJson(0,'Your code is expired, try again later');
        }
        return responseJson(0,'there something wrong try again later'); 
    }

}
