@extends('layouts.app')
@section('content')
<div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">{{$title}}</h3>
    </div>
   
        {!! Form::model($foodCategory,['route' => ['admin.food-categories.update', ['food_category'=>$foodCategory->id]],
        'method'=>'put'
        
        ]) !!}
        <div class="card-body">
          <div class="form-group">
            <label for="name">Name</label>
            {!! Form::text('name',old('name',$foodCategory->name), ['class'=>'form-control','placeholder'=>'Enter Food Category']) !!}
            @error('name')
            <small style="color: #dc3545">{{ $message }}</small> 
            @enderror
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">{{$title}}</button>
        </div>
        {!! Form::close() !!}
  </div>
  @endsection