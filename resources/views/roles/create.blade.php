@extends('layouts.app')
@section('content')
@inject('permissions', 'Spatie\Permission\Models\Permission')
<div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">{{$title}}</h3>
    </div>
   
    <form action={{route('admin.roles.store')}} method="POST">
        @csrf
        <div class="card-body">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter The Role Name">
            @error('name')
            <small style="color: #dc3545">{{ $message }}</small> 
            @enderror
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Permissions</label>
            <input type="checkbox" class="select-all"><label for="">Select All</label>
            <select class="selectpicker input form-control" multiple data-mdb-filter="true" name="permissions[]">
              @foreach ($permissions->all() as $permission)
                  <option value={{$permission->id}}>{{$permission->name}}</option>
              @endforeach
            </select>
            @error('permissions')
            <small style="color: #dc3545">{{ $message }}</small> 
            @enderror
        </div> 
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">{{$title}}</button>
        </div>
      </form>
  </div>
  @endsection
