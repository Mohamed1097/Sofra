<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function check(Request $request)
    {
        $validator=validator()->make($request->all(),['email'=>'required|email|exists:users,email','password'=>'required']);
        if($validator->fails())
        {
            return redirect()->back()->with('fail','Email Or Password Is Invalid');
        }
        $creds = $request->only('email','password');
        if( Auth::guard('web')->attempt($creds) ){
            return redirect()->route('admin.home');
        }
        else
        {
            return redirect()->back()->with('fail','Email Or Password Is Invalid');
        }
    }
    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('admin.login');
    }
    public function index()
    {
        $users=User::paginate();
        return view('users.index',['title'=>'Users','users'=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create',['title'=>'Create New User']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=validator()->make($request->all(), [
            'name' => 'required|string|min:2',
            'password' => 'required|string|min:8|confirmed',
            'email' => 'required|email|unique:users,email',
            'roles'  => 'required|array',
            'roles.*'=>'exists:roles,id'
        ]);
        if($validator->fails())
        {
            return redirect(route('admin.users.create'))->withErrors($validator->errors());
        }
        $request->merge(['password'=>Hash::make($request->password)]);
        $user=User::create($request->all());
        $user->syncRoles($request->roles);
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user=User::findOrFail($id);
        $title='Edit '.$user->name;
        return view('users.edit',['title'=>$title,'user'=>$user]);
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
        $validator=validator($request->all(),['roles'=>'required|array','roles.*'=>'required|exists:roles,id']);
        if ($validator->fails()) {
            return redirect()->route('admin.users.edit')->withErrors($validator->errors());
        }
        $user=User::findOrFail($id);
        $user->syncRoles($request->roles);
        return redirect()->route('admin.users.index');
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
