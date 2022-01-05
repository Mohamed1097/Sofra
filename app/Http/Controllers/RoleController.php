<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles=Role::paginate();
        $message=null;
        if (!$roles->count()) {
            $message='There is No Role';
        }
        return view('roles.index',['title'=>'Roles','roles'=>$roles,'message'=>$message]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roles.create',['title'=>'Create New Role']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=validator()->make($request->all(),['name'=>'required|string','permissions'=>'required|array','permissions.*'=>'required|exists:permissions,id']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        $role=Role::create(['name'=>$request->name]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('admin.roles.index');

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
        $role=Role::findorFail($id);
        $title='Edit '.$role->name;
        return view('roles.edit',['title'=>$title,'role'=>$role]);
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
        $validator=validator()->make($request->all(),['name'=>'required|string','permissions'=>'required|array','permissions.*'=>'required|exists:permissions,id']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        $role=Role::findOrFail($id);
        $role->update(['name'=>$request->name]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('admin.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     $role=Role::findOrFail($id);
    //     if (!$role) {
    //         # code...
    //     }
    //     $roleName=$role->name;
    //     $role->delete();
    //     return responseJson(1,'')

    // }
}
