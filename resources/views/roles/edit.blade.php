@extends('layouts.app')
@section('content')
@inject('model','Spatie\Permission\Models\Role' )
@inject('permissions', 'Spatie\Permission\Models\Permission')
<div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">{{$title}}</h3>
    </div>
   
        {!! Form::model($model,['route' => ['admin.roles.update', ['role'=>$role->id]],
        'method'=>'put'
        
        ]) !!}
        <div class="card-body">
          <div class="form-group">
            <label for="name">Name</label>
            {!! Form::text('name',old('name',$role->name), ['class'=>'form-control','placeholder'=>'Enter Role Name']) !!}
            @error('name')
            <small style="color: #dc3545">{{ $message }}</small> 
            @enderror
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Permissions</label>
            {!! Form::select('permissions[]', $permissions->pluck('name','id')->toArray(),$role->permissions->pluck('id'), ['class'=>'form-control ','multiple' => true]) !!}
            @error('permissions')
            <small style="color: #dc3545">{{ $message }}</small> 
            @enderror
        </div> 
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">{{$title}}</button>
        </div>
        {!! Form::close() !!}
  </div>
  @endsection