@extends('layouts.app')
<!-- Content Wrapper. Contains page content -->
@section('content')
    <!-- Main content -->
        <div class="card">
            <div class="card-header">
              <h3 class="card-title">{{$title}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              @error('message')
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i>Message</h5>
               {{$message}}
              </div>
              @enderror
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Roles</th>
                  <th>Control</th>
                </tr>
                </thead>
                <tbody>
                    @isset($message)
                    <td colspan="7" class="text-center">
                      {{$message}}  
                    </td>  
                    @endisset
                    @foreach ($users as $user)
                    <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                      @foreach ($user->roles as $role)
                      <p class="text-success">{{$role->name}}</p> 
                      @endforeach
                      </td>
                      <td>
                        <a class="btn btn-info" href={{route("admin.users.edit",['user'=>$user->id])}}>
                          <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger delete-btn" type='submit' element={{$user->name}} data-toggle="modal" data-target='#delete-modal' url={{route('admin.users.destroy',['user'=>$user->id])}}>
                          <i class="fas fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                    @endforeach
                    
                </tbody>
                <tfoot>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Roles</th>
                  <th>Control</th>
                </tr>
                </tfoot>
              </table>
              <br>
              <div class="row" style="float:right;margin-right: 0%;">{{ $users->links('pagination::bootstrap-4') }}</div> 
              <a class="btn btn-primary" href={{route('admin.users.create')}}>
                <i class="fas fa-plus"></i>
                <span>Add New User</span>
              </a>
            </div>
          </div>
    </section>
    
  @endsection