<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator=validator()->make($request->all(),[
            'phone'=>'exists:restaurants,phone',
            'email'=>'exists:restaurants,email',
            'active'=>'boolean',
            'busy'=>'boolean',
            'open'=>'boolean',
            'warned'=>'boolean',
            'name'=>'string',
            'city'=>'exists:cities,name',
            'foodCategories'=>'array',
            'foodCategories.*'=>'exists:food_categories,id',
        ]);
        // if ($validator->fails()) {
        //     $message='there is no Restaurant with this Info';
        //     return view('restaurants.index',['title'=>'restaurants','message'=>$message,'restaurants'=>null]);
        // }
        $restaurants=new Restaurant();
        $message=null;
        if($request->foodCategories!=null)
        {
                $restaurants=$restaurants->orWhereHas('foodCategories',function($query) use($request){
                    $query->whereIn('food_category_id',$request->foodCategories);
                });
            info($restaurants->toSql());
        }
        if ($request->name!=null) {
            $restaurants=$restaurants->where('name','like','%'.$request->name.'%');
        }
        if($request->city!=null)
        {
            $restaurants=$restaurants->whereHas('city',function($query) use ($request){
                $query->where('name',$request->city);
            });
            info($restaurants->toSql());
        }
        if($request->phone!=null)
        {
            $restaurants=$restaurants->where('phone',$request->phone);
        }
        if($request->email!=null)
        {
            $restaurants=$restaurants->where('email',$request->email);
        }
        if($request->active!=null)
        {
            $restaurants=$restaurants->where('is_active',$request->active);
        }
        if($request->busy!=null)
        {
            $restaurants=$restaurants->where('is_busy',$request->busy);
        }
        if($request->open!=null)
        {
            $restaurants=$restaurants->where('is_opened',$request->open);
        }
        if($request->warned!=null)
        {
            $restaurants=$restaurants->where('is_warned',$request->warned);
        }
        if (!$restaurants->count()) {
            $message='there is no Restaurant with this Info';
            info($restaurants->toSql());
            return view('restaurants.index',['title'=>'Restaurants','message'=>$message,'restaurants'=>null]);
        }
        info($restaurants->toSql());
        return view('restaurants.index',['title'=>'Restaurants','restaurants'=>$restaurants->paginate(1)->appends(request()->query()),'message'=>null]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $restaurant=Restaurant::findOrFail($id);
        $orders=$restaurant->orders();
        $offers=$restaurant->offers();
        $meals=$restaurant->meals();
        $orderMessage=null;
        $offerMessage=null;
        $mealMessage=null;
        if($request->has('status'))
        {
            $validator=validator()->make($request->all(),['status'=>'string|in:pending,accepted,canceled,rejected,delivered']);
            if($validator->fails())
            {
                return abort(404);
            }
            $orders=$orders->where('status',$request->status);
        }
        if(!$offers->count())
        {
            $offerMessage=$restaurant->name.' Have No Offers';
        }
        if(!$orders->count())
        {
            $orderMessage=$restaurant->name.' Have No Orders';
        }
        if(!$meals->count())
        {
            $mealMessage=$restaurant->name.' Have No Meals';
        }
        $title=$restaurant->name.' Details';
        $meals=$meals->orderBySales()->paginate(1,['*'],'meal');
        return view('restaurants.show',[
        'title'=>$title,'restaurant'=>$restaurant,
        'orders'=>$orders->paginate(1,['*'],'order')->appends(request()->query()),
        'orderMessage'=>$orderMessage,'offers'=>$offers->paginate(1,['*'],'offer')->appends(request()->query()),
        'offerMessage'=>$offerMessage,
        'meals'=>$meals->appends(request()->query()),
        'mealMessage'=>$mealMessage
    ]);  
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator=validator($request->all(),[
            'is_active'=>'boolean',
            'is_busy'=>'boolean',
            'is_opened'=>'boolean',
            'is_warned'=>'boolean',
        ]);
        if ($validator->fails()) {
            return responseJson(0,'failed');
        }
        $restaurant=Restaurant::find($id);
        if ($restaurant->update($request->all())) {
            if ($request->has('is_active')) {
                $restaurant->api_token=null;
                $restaurant->save();
            }
            return responseJson(1,'success',$request->all());
        }
        return responseJson(0,'failed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
