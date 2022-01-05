@extends('layouts.app')
@section('content')
@inject('model','App\Models\User' )
@inject('roles', 'Spatie\Permission\Models\Role')
<div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">{{$title}}</h3>
    </div>
   
    {!! Form::model($model,['route' => ['admin.users.store'],        
        ]) !!}
        <div class="card-body">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter The User Name">
            @error('name')
            <small style="color: #dc3545">{{ $message }}</small> 
            @enderror
        </div>
        <div class="form-group">
          <label for="display name">Email</label>
          <input type="text" class="form-control" id="email" name="email" placeholder="Enter The Email">
          @error('Email')
          <small style="color: #dc3545">{{ $message }}</small> 
          @enderror
      </div>
      <div class="form-group">
        <label for="display name">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter The Password">
        @error('Password')
        <small style="color: #dc3545">{{ $message }}</small> 
        @enderror
    </div>
    <div class="form-group">
        <label for="display name">confirm Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter The confirm Password">
        @error('Password_confirmation')
        <small style="color: #dc3545">{{ $message }}</small> 
        @enderror
    </div>
      <div class="form-group">
        <label for="exampleInputEmail1">Roles</label>
        {!! Form::select('roles[]', $roles->pluck('name','id')->toArray(),null, ['class'=>'form-control','multiple' => true]) !!}
        @error('roles')
        <small style="color: #dc3545">{{ $message }}</small> 
        @enderror
      </div> 
       
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">{{$title}}</button>
        </div>
        {!! Form::close() !!}
  </div>
  @endsection
