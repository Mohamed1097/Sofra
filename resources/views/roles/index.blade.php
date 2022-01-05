@extends('layouts.app')
@section('content')
<!-- Content Wrapper. Contains page content -->
      
    <!-- Main content -->
        <div class="card">
            <div class="card-header">
              <h3 class="card-title">{{$title}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Control</th>
                  </tr>
                  </thead>
                    <tbody>
                      @if (isset($message))
                      <tr>
                        <td colspan="20" class="text-center">{{$message}}</td>   
                      </tr>
                      @else
                      @foreach ($roles as $role)
                      <tr>
                        <td>{{$role->id}}</td>
                        <td>{{$role->name}}</td>
                        <td>
                            <a class="btn btn-info" href={{route("admin.roles.edit",['role'=>$role->id])}}>
                                <i class="fas fa-edit"></i>
                              </a>
                              <button class="btn btn-danger delete-btn" type='submit' element='{{$role->name}}' data-toggle="modal" data-target='#delete-modal' url={{route('admin.roles.destroy',['role'=>$role->id])}}>
                                <i class="fas fa-trash"></i>
                              </button>
                        </td>
                      </tr>
                      @endforeach   
                      @endif
                    </tbody>
                  <tfoot>
                  <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Control</th>
                  </tr>
                  </tfoot>
                </table>
                <br>
                <a class="btn btn-primary" href={{route('admin.roles.create')}}>
                    <i class="fas fa-plus"></i>
                    <span>Add New Role</span>
                  </a>
                @if ($roles instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="row" style="float:right;margin-right: 0%;">{{ $roles->links('pagination::bootstrap-4') }}</div> 
                @endif
              </div>
            
            </div>
          </div>
    </section>

@endsection