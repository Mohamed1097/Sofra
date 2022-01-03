<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;


class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients=new Client();
        $message=null;
        if ($request->has('filter')) {
            $validator=validator()->make($request->all(),['filter'=>'integer|between:1,2']);
            if($validator->fails())
            {
                return abort(404);
            } 
            if ($request->filter==1) {
                $validator=validator()->make($request->all(),['keyword'=>'required|exists:clients,phone']);
                if($validator->fails())
                {
                    $message='there is no Client with this phone';
                    return view('clients.index',['title'=>'Clients','message'=>$message,'clients'=>null]);
                }
                $clients=$clients->where('phone',$request->keyword);
                return view('clients.index',['title'=>'Clients','clients'=>$clients->get(),'message'=>null]);
            }
            elseif ($request->filter==2) {
                $validator=validator()->make($request->all(),['keyword'=>'required|exists:clients,email']);
                if($validator->fails())
                {
                    $message='there is no Client with this Email';
                    return view('clients.index',['title'=>'Clients','message'=>$message,'clients'=>null]);
                }
                $clients=$clients->where('email',$request->keyword);
                return view('clients.index',['title'=>'Clients','clients'=>$clients->get(),'message'=>null]);
            }
        }
        return view('clients.index',['title'=>'Clients','clients'=>$clients->paginate(2 ),'message'=>null]);
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
        $client=Client::findOrFail($id);
        $orders=$client->orders();
        $message=null;
        if($request->has('status'))
        {
            $validator=validator()->make($request->all(),['status'=>'string|in:pending,accepted,canceled,rejected,delivered']);
            if($validator->fails())
            {
                return abort(404);
            }
            $orders=$orders->where('status',$request->status);
        }
        
        if(!$orders->count())
        {
            $message=$client->name.' Have No Orders';
        }
        $title=$client->name.' Details';
        return view('clients.show',['title'=>$title,'client'=>$client,'orders'=>$orders->paginate(1)->appends(request()->query()),'message'=>$message]);  
       
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
        $validator=validator()->make($request->all(),['is_active'=>'required|boolean']);
        if($validator->fails())
        {
            return responseJson(0,'there is something wrong try Again Later');
        }
        else
        {

            $client=Client::findOrFail($id);
            $client->is_active=$request->is_active;
            $client->api_token=null;
            $client->save();
            return responseJson(1,'success',['is_active'=>$client->is_active]);
        }
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
