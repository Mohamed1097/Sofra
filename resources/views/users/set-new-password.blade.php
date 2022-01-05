@extends('layouts.app')
@section('content')
@inject('roles', 'App\Models\Role')
<div class="card card-primary">
  @error('message')
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i>Message</h5>
               {{$message}}
              </div>
              @enderror
    <div class="card-header">
      <h3 class="card-title">{{$title}}</h3>
    </div>
    <form action={{route('admin.set-password')}} method="POST">
        @csrf
        <div class="card-body">
        <div class="form-group">
            <label for="current-password">Current Password</label>
            <input type="password" class="form-control" id="current-password" name="current-password" placeholder="Enter The Current Password">
            @error('current-password')
            <small style="color: #dc3545">{{ $message }}</small> 
            @enderror
        </div>
      <div class="form-group">
        <label for="Password">New Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter The New Password">
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
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">{{$title}}</button>
        </div>
      </form>
  </div>
  @endsection
