<?php

namespace App\Http\Controllers\Api\restaurant;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File as FacadesFile;
use Carbon\Carbon;



class RestaurantController extends Controller
{
    public function addMeal(Request $request)
    {
        $path='images/'.date('Y').'/'.date('M').'/'.date('d');
        $validator=validator()->make($request->all(),[
            'name'=>'required|string',
            'content'=>'required|string',
            'price'=>'required|between:0,99.99',
            'price_in_offer'=>'required|between:0,99.99',
            'meal_image'=>'required|mimes:jpeg,jpg,png,gif|max:2048',
            'preparation_time'=>'required|integer'
        ]);
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first(),$validator->errors());
        }
        $imageName = $request->meal_image->hashName();
        $request->meal_image->move(public_path($path), $imageName);
        $meal=$request->user()->meals()->create($request->all());
        $meal->meal_image=$path.'/'.$imageName;
        $meal->save();
        return responseJson(1,'تم اضافة الو جبه بنجاح',['meal'=>$meal]);
    }
    public function editMeal(Request $request)
    {
        $restaurantId=$request->user()->id;
        $validator=validator()->make($request->all(),[
            'id'=>'required|exists:meals,id'
        ]);
        if($validator->fails())
        {
            return responseJson(0,'there is something Wrong, try Again later');
        }
        $meal=Meal::where('id',$request->id)->where('restaurant_id',$restaurantId)->first();
        if($meal!=null)
        {
            $validator=validator()->make($request->all(),[
                'name'=>'required|string|unique:meals,name,'.$meal->id,
                'content'=>'required|string',
                'price'=>'required|between:0,99.99',
                'price_in_offer'=>'required|between:0,99.99',
                'preparation_time'=>'required|integer',
                'meal_image'=>'mimes:jpeg,jpg,png,gif|max:2000000',
            ]);
            if($validator->fails())
            {
                return responseJson(0,$validator->errors()->first(),$validator->errors());
            }
            if($request->has('meal_image'))
            {
                if(FacadesFile::exists(public_path($meal->meal_image)))
                {
                    FacadesFile::delete(public_path($meal->meal_image)); 
                }
                $path='images/'.date('Y').'/'.date('M').'/'.date('d');
                $imageName = $request->meal_image->hashName();
                $request->meal_image->move($path, $imageName);
                $meal->update($request->except('id'));
                $meal->meal_image=$path.'/'.$imageName;
                $meal->save();
            }
            $meal->update($request->all());
            $meal->save();
            return responseJson(1,'تم تعديل الوجبه بنجاح',$meal);
        }
        return responseJson(0,'there is something Wrong, try Again later');
    }
    public function deleteMeal(Request $request)
    {
        $validator=validator($request->all(),['meal_id'=>'required|exists:meals,id']);
        if($validator->fails())
        {
            return responseJson(0,'there something wrong try again later');
        }
        $meal=Meal::where('id',$request->meal_id)->where('restaurant_id',$request->user()->id)->first();
        if($meal!=null)
        {
            if ($meal->orders()->where('status','pending')->orWhere('status','accepted')->count()) {
                return responseJson(0,'لا تستطيع مسح هذه الوجبه حاليا');
                
            }
            if(FacadesFile::exists(public_path($meal->meal_image)))
            {
                FacadesFile::delete(public_path($meal->meal_image)); 
            }
            $meal->delete();
            return responseJson(1,'تم مسح الوجبه بنجاح');
        }
        else{
            return responseJson(0,'there something wrong try again later');
        }
    }
    public function addOffer(Request $request)
    {
        $restaurantId=$request->user()->id;
        $validator=validator()->make($request->all(),[
            'name'=>'required|string',
            'description'=>'required|string',
            'start_date'=>'required|date|before_or_equal:end_date|after_or_equal:'.Carbon::now()->toDateString(),
            'end_date'=>'required|date|after_or_equal:start_date|after_or_equal:'.Carbon::now()->toDateString()
        ]); 
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first(),$validator->errors());
        }
        $request->merge(['restaurant_id'=>$restaurantId]);
        $offer=Offer::create($request->all());
        return responseJson(1,'success',$offer);
    }
    public function deleteOffer(Request $request)
    {
        $validator=validator()->make($request->all(),['id'=>'required|exists:offers,id']);
        if($validator->fails())
        {
            return responseJson(0,'there something wrong try again later');
        }
        $offer=Offer::where('restaurant_id',$request->user()->id)->findOrfail($request->id);
        if(!$offer)
        {
            return responseJson(0,'there something wrong try again later');
        }
        $offer->delete();
        return responseJson(1,'تم مسح العرض بنجاح');
    }
    public function getOffer(Request $request)
    {
        $validator=validator()->make($request->all(),[
            'id'=>'required|exists:offers,id'
        ]);
        if($validator->fails())
        {
            return responseJson(0,'there something wrong try again later');
        }
        $offer=Offer::where('restaurant_id',$request->user()->id)->findOrfail($request->id);
        if(!$offer)
        {
            return responseJson(0,'there something wrong try again later');
        }
        return responseJson(1,'success',$offer);
        
    }
    public function editOffer(Request $request)
    {
        $validator=validator()->make($request->all(),[
            'id'=>'required|exists:offers,id',
            'name'=>'required|string',
            'description'=>'required|string',
            'start_date'=>'required|date|before_or_equal:end_date|after_or_equal:'.Carbon::now(),
            'end_date'=>'required|date|after_or_equal:start_date|after_or_equal:'.Carbon::now()
        ]); 
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first(),$validator->errors());
        }
        $offer=Offer::where('restaurant_id',$request->user()->id)->findOrfail($request->id);
        if($offer)
        {
            $offer->update($request->all());
            return responseJson(1,'success',$offer);
        }
        return responseJson(0,'there something wrong try again later');
    }
    public function previousOrders()
    {
        $orders=request()->user()->orders()->where('status','rejected')->orWhere('status','delivered')->paginate();
        if($orders->count())
        {
            return responseJson(1,'success',$orders);
        }
        return responseJson(0,'لا يوجد طلبات سابقه');
    }
    public function currentOrders()
    {
        $orders=request()->user()->orders()->where('status','accepted')->paginate();  
        if($orders->count())
        {
            return responseJson(1,'success',$orders);
        }
        return responseJson(0,'لا يوجد طلبات حاليه');
    }
    public function newOrders()
    {
        $orders=request()->user()->orders()->where('status','pending')->paginate();  
        if($orders->count())
        {
            return responseJson(1,'success',$orders);
        }
        return responseJson(0,'لا يوجد طلبات جديده');
    }
    public function acceptOrder(Request $request)
    {
        $validator=validator()->make($request->all(),[
            'order_id'=>'required|exists:orders,id'
        ]);
        if($validator->fails())
        {
            return responseJson(0,'there something wrong try again later');
        }
        $order=Order::find($request->order_id);
        if($order->restaurant_id!=$request->user()->id||$order->status!='pending')
        {
            return responseJson(0,'there something wrong try again later');   
        }
        $order->status='accepted';
        $order->accept_expired_date=Carbon::now()->addMinutes(1)->toDateTimeString();
        $order->save();
        $notification=new Notification();
        $client=$order->client;
        $notification->notificationable()->associate($client);
        $notification->save();
        $notification->update([
            'title'=>'تم قبول طلبك من ' .$order->restaurant->name,
            'body'=>'تم قبول طلبك من ' .$order->restaurant->name,
            'order_id'=>$order->id
        ]);
        $tokens=$client->tokens->pluck('token');
        if($tokens->count())
        {
            $send=notifyByFirebase($notification->title,$notification->body,$tokens->toArray(),['order_id'=>$order->id]);
            info("firebase result: " . $send);
        }

        return responseJson(1,' تم قبول الطلب بنجاح',$order);
    }
    public function deliveryOrder(Request $request)
    {
        // orders delivered sum(price)  total sales  50000
        // $comm = orders deli sum(comm) total comm owed by rest 5000
        // $paid = payments restaurant id sum(amount)  // total commm paid by restaurant 1000
        // $comm - $paid  4000
        $validator=validator()->make($request->all(),[
            'order_id'=>'required|exists:orders,id'
        ]);
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first());
        }
        $order=Order::find($request->order_id);
        if($order->restaurant_id!=$request->user()->id||$order->status!='accepted')
        {
            return responseJson(0,'there something wrong try again later');   
        }
        $order->status='delivered';
        $order->save();
        $orders=$request->user()->orders->where('status','accepted')->
                where('accept_expired_date','<',Carbon::now()->toDateTimeString());
        if (!$orders->count()) {
            $request->user()->is_busy=0;
            $request->user()->save();
        }
        $payment=$request->user()->payment;
        $payment->commission+=$order->commission;
        $payment->save();
        return responseJson(1,'تم توصيل الطلب بنجاح',$order);
    }
    public function rejectOrder(Request $request)
    {
        $validator=validator()->make($request->all(),[
            'order_id'=>'required|exists:orders,id'
        ]);
        if($validator->fails())
        {
            return responseJson(0,'there something wrong try again later');
        }
        $order=Order::find($request->order_id);
        if($order->restaurant_id!=$request->user()->id||$order->status!='pending')
        {
            return responseJson(0,'there something wrong try again later');   
        }
        $order->status='rejected';
        $order->save();
        $notification=new Notification();
        $client=$order->client;
        $notification->notificationable()->associate($client);
        $notification->save();
        $notification->update([
            'title'=>'تم رفض طلبك من ' .$order->restaurant->name,
            'body'=>'تم رفض طلبك من ' .$order->restaurant->name,
            'order_id'=>$order->id
        ]);
        $tokens=$client->tokens->pluck('token');
        if($tokens->count())
        {
            $send=notifyByFirebase($notification->title,$notification->body,$tokens->toArray(),['order_id'=>$order->id]);
            info("firebase result: " . $send);
        }
        return responseJson(1,'تم رفض الطلب بنجاح',$order);
    }
    public function getOrder(Request $request)
    {
        $restaurant=$request->user();
        $validator=validator()->make($request->all(),[
            'order_id'=>'required|exists:orders,id'
        ]);
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first());
        }
        $order=Order::find($request->order_id);
        if($order->restaurant_id!=$restaurant->id)
        {
            return responseJson(0,'there something wrong try again later');   
        }
        return responseJson(1,'success',$order->meals);
    }
    public function getOffers(Request $request)
    {
        $offers=$request->user()->offers()->with('restaurant')->where('end_date', '>=', Carbon::now())->paginate();
        if($offers->count())
        {
            return responseJson(1,'success',$offers);  
        }
        return responseJson(0,'ليس لديك عروض من الممكن اضافة عروض جديده');
    }
    public function getMeal(Request $request)
    {
        $validator=validator()->make($request->all(),[
            'meal_id'=>'required|exists:meals,id',
        ]);
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first());
        }
        $meal=$request->user()->meals()->with('restaurant')->find($request->meal_id);
        if($meal)
        {
            return responseJson(1,'success',$meal);
        }
        return responseJson(0,'There Something Wrong Try Again Later');
    }
    public function getMeals(Request $request)
    {
        $meals=$request->user()->meals()->paginate();
        if ($meals->count()) 
        {
            return responseJson(1,'success',$meals);
        }
        return responseJson(0,'لا يوجد لديك وجبات من الممكن اضافة وجبات');
    }
    public function getComments(Request $request)
    {
        $comments=$request->user()->comments()->paginate();
        if($comments->count())
            return responseJson(1,'success',['reviews',$comments]);
            return responseJson(0,'لا يوجد مرجعات لمطعمك ');    
        # code...
    }
    public function getAppCommission(Request $request)
    {
        $payment=$request->user()->payment;
        $commission=$payment->commission;
        return responseJson(1,'success',['commission'=>$commission,'net'=>$commission-$payment->amount]);
    }
   
}
