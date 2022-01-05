@extends('layouts.app')
@inject('model','App\Models\user' )
@inject('roles', 'Spatie\Permission\Models\Role')
@section('content')
<div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">{{$user->name}}</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    {!! Form::model($model,[
        'url'=>url(route('admin.users.update',['user'=>$user->id])),
        'method'=>'PUT'
        ]) !!}
    <div class="card-body">
        <div class="form-group">
          <label for="exampleInputEmail1">Roles</label>
          <br>
          <input type="checkbox" class="select-all"><label for="">Select All</label>
          {!! Form::select('roles[]', $roles->pluck('name','id')->toArray(),$user->roles->pluck('id'), ['class'=>'form-control','multiple' => true]) !!}
          @error('roles')
          <small style="color: #dc3545">{{ $message }}</small> 
          @enderror
        </div> 
         
  </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button class="btn btn-primary" type='submit'>{{$title}}</button>
      </div>
    {!! Form::close() !!}
  </div>
  @endsection