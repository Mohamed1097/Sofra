<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Meal;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function addComment(Request $request)
    {
        $validator=validator()->make($request->all(),[
            'restaurant_id'=>'required|exists:restaurants,id',
            'rate'=>'required|numeric|between:1,5',
            'content'=>'required|string',
        ]);
        if($validator->fails())
        {
            return responseJson(0,'failed',$validator->errors());
        }
        $comment=$request->user()->comments()->create($request->all());
        return responseJson(1,'تم اضافة التعليق بنجاح',['comment'=>$comment]);
    }
    public function getRestaurants(Request $request)
    {
        $restaurants=new Restaurant();
        if($request->has('city_id'))
        {
            $validator=validator()->make($request->all(),['city_id'=>'exists:cities,id']);
            if($validator->fails())
            {
                return responseJson(0,$validator->errors()->first());
            }
            $restaurants=$restaurants->where('city_id',$request->city_id);
        }
        if($request->has('keyword'))
        {
            $restaurants=$restaurants->where('name','like','%'.$request->keyword.'%');
        }
        if(!$restaurants->count())
        {
            return responseJson(0,'there is no Restaurants');
        }
        $restaurants=$restaurants->where('is_active',1)->where('is_busy',0)->paginate();
        return responseJson(1,'success',$restaurants);
    }
    public function getRestaurant(Request $request)
    {
        $validator=validator()->make($request->all(),[
            'restaurant_id'=>'required|exists:restaurants,id'
        ]);
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first(),$validator->errors());
        }
        $restaurant=Restaurant::where('id',$request->restaurant_id)->with('city','neighborhood')->first(['name','min_charge','delivery_price','contact_phone','contact_whatsapp','restaurant_image','is_opened','city_id','neighborhood_id']);
        if (!$restaurant->is_active) {
            return responseJson(0,'there is something wrong try again later');  
        }
        return responseJson(1,'success',$restaurant);
    }
    public function addOrder(Request $request)
    {
        $validator=validator()->make($request->all(),[
            'restaurant_id'=>'required|exists:restaurants,id',
            'meals'=>'required|array',
            'meals.*'=>'required|exists:meals,id',
            'quantity'=>'required|array',
            'quantity.*'=>'integer|min:1',
            'address'=>'required|string',
            'notes'=>'required|array',
        ]);
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first(),$validator->errors());
        }
        $restaurant=Restaurant::find($request->restaurant_id);
        if (!$restaurant->is_active) {
            return responseJson(0,'هذالمطعم لا يستطيع استقبال طلبك حاليا');
        }
        if ($restaurant->is_busy) {
            return responseJson(0,'هذالمطعم لا يستطيع استقبال طلبك حاليا');
        }
        if(!$restaurant->is_opened)
        {
            return responseJson('0','المطعم مغلق حاليا');
        }
        $nonRestaurantMeals = Meal::whereIn('id',$request->meals)->where('restaurant_id','!=',$restaurant->id)->first();
        if($nonRestaurantMeals)
        {
            return responseJson(0,'there is something wrong try again later');
        }
        if(count($request->meals)!=count($request->quantity)||count($request->meals)!=count($request->notes))
        {
            return responseJson(0,'there is something wrong try again later');  
        }
        // start queries
        // DB::startTransaction();
        $order=$request->user()->orders()->create([
            'restaurant_id'=>$restaurant->id,
            'delivery_price'=>$restaurant->delivery_price,
            'status'=>'pending',
            'address'=>$request->address
        ]);
        $index=0;
        $cost=0;
        foreach ($request->meals as $mealId) {
            $meal=Meal::find($mealId);
            $cost+=$meal->price*$request->quantity[$index];
            $order->meals()->attach([
                $meal->id => [
                'quantity'=>$request->quantity[$index],
                'note'=>$request->notes[$index],
                'meal_price'=>$meal->price,
                ]
            ]);
            $index++;
        }
        if($cost<$restaurant->min_charge)
        {
            // DB::rollback();
            return responseJson(0, 'الطلب لابد أن لا يكون أقل من ' . $restaurant->min_charge . ' ريال');
        }
        $totalCost=$cost+$restaurant->delivery_price;
        $commission=$cost*settings()->commission;
        $net=$totalCost-$commission;
        $order->update([
            'price'=>$cost,
            'total_price'=>$totalCost,
            'commission'=>$commission,
            'net'=>$net
        ]);
        $notification=new Notification();
        $notification->notificationable()->associate($restaurant);
        $notification->save();
        $notification->update([
            'order_id'=>$order->id,
            'title'=>'لديك طلب جديد',
            'body'=>$request->user()->name .  ' لديك طلب جديد من العميل ',
        ]);
        // DB::commit();
        // end queries
        $tokens=$restaurant->tokens->pluck('token');
        if($tokens->count())
        {
            $send=notifyByFirebase($notification->title,$notification->body,$tokens->toArray(),['order_id'=>$order->id]);
            info("firebase result: " . $send);
        }
        return responseJson(1,'تم ارسال طلبك للمطعم برجاء انتظار التاكيد',$order);
    }
    public function currentOrders()
    {
        $orders=request()->user()->orders()->where('status','accepted')->orWhere('status','pending')->paginate();  
        if($orders->count())
        {
            return responseJson(1,'success',$orders);
        }
        return responseJson(0,'لا يوجد طلبات حاليه');
    }
    public function previousOrders()
    {
        $orders=request()->user()->orders()->where('status','canceled')->
        orWhere('status','delivered')->orWhere('status','rejected')->paginate();
        if($orders->count())
        {
            return responseJson(1,'success',$orders);
        }
        return responseJson(0,'لا يوجد طلبات سابقه');
    }
    public function cancelOrder(Request $request)
    {
        $validator=validator()->make($request->all(),[
            'order_id'=>'required|exists:orders,id'
        ]);
        if($validator->fails())
        {
            return responseJson(0,'there something wrong try again later');
        }
        $order=Order::find($request->order_id);
        if($order->client_id!=$request->user()->id||$order->status=='delivered'
        ||$order->status=='canceled'||$order->status=='rejected')
        {
            return responseJson(0,'there something wrong try again later');   
        }
        $order->status='canceled';
        $order->save();
        $restaurant=$order->restaurant;
        $notification=new Notification();
        $notification->notificationable()->associate($restaurant);
        $notification->save();
        $notification->update([
            'title'=>'تم الغاء  الطلب من ' .$order->client->name,
            'body'=>'تم الغاء  الطلب من ' .$order->client->name,
            'order_id'=>$order->id
        ]);
        $tokens=$restaurant->tokens->pluck('token');
        if($tokens->count())
        {
            $send=notifyByFirebase($notification->title,$notification->body,$tokens->toArray(),['order_id'=>$order->id]);
            info("firebase result: " . $send);
        }
        return responseJson(1,'تم الغاء الطلب بنجاح',$order);
    }
    public function getOrder(Request $request)
    {
        $client=$request->user();
        $validator=validator()->make($request->all(),[
            'order_id'=>'required|exists:orders,id'
        ]);
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first());
        }
        $order=Order::find($request->order_id);
        if($order->client_id!=$client->id)
        {
            return responseJson(0,'there something wrong try again later');   
        }
        return responseJson(1,'success',$order->meals);
    }
    public function getOffers(Request $request)
    {
        $offers=new Offer();
        if($request->has('restaurant_id'))
        {
            $validator=validator()->make($request->all(),[
                'restaurant_id'=>'exists:restaurants,id',
            ]);
            if($validator->fails())
            {
                return responseJson(0,'there something wrong try again later');
            }
            $offers=$offers->where('end_date', '>=', Carbon::now())->where('restaurant_id',$request->restaurant_id)->with('restaurant')->paginate();

            if($offers->count())
            {
             return responseJson(1,'success',$offers);  
            }
            return responseJson(0,' لا يوجد عروض لهذا المطعم');      
        }
        else
        {
            $offers=$offers->where('end_date', '>=', Carbon::now())->with('restaurant')->paginate();
            if($offers->count())
            {
             return responseJson(1,'success',$offers);  
            }
            return responseJson(0,' لا يوجد عروض حاليا');    
        }
    }
    public function getMeal(Request $request)
    {
        $validator=validator()->make($request->all(),[
            'restaurant_id'=>'required|exists:restaurants,id',
            'meal_id'=>'required|exists:meals,id',
        ]);
        if($validator->fails())
        {
            return responseJson(0,$validator->errors()->first());
        }
        $meal=Meal::where('id',$request->meal_id)->where('restaurant_id',$request->restaurant_id)->with('restaurant')->first();
        if($meal)
        {
            return responseJson(1,'success',$meal);
        }
        return responseJson(0,'There Something Wrong Try Again Later');
    }
    public function getMeals(Request $request)
    {
        $validator=validator()->make($request->all(),['restaurant_id'=>'required|exists:restaurants,id']);
       if($validator->fails())
       {
           return responseJson(0,'failed',$validator->errors());
       }
       $meals=Meal::where('restaurant_id',$request->restaurant_id)->paginate();
       return responseJson(1,'success',['meals',$meals]);
    }
    public function getComments(Request $request)
    {
        $validator=validator()->make($request->all(),['restaurant_id'=>'required|exists:restaurants,id']);
        if($validator->fails())
        {
            return responseJson(0,'failed',$validator->errors());
        }
        $Comments=Comment::where('restaurant_id',$request->restaurant_id)->paginate();
        if($Comments->count())
        return responseJson(1,'success',['reviews',$Comments]);
        return responseJson(0,'لا يوجد مرجعات على هذا المطعم');
    }
}
