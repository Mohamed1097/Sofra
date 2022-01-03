@extends('layouts.app')
@inject('Neighborhoods','App\Models\Neighborhood')
@inject('Cities','App\Models\city')
<!-- Content Wrapper. Contains page content -->
@section('content')
    <!-- Main content -->
        <div class="card">
            <div class="card-header">
              <h3 class="card-title">{{$title}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="form-inline my-2 mb-4">
                <select class="custom-select search-by mr-1" id="inputGroupSelect01">
                  <option selected value='0'>Search By</option>
                  <option value="1">Phone</option>
                  <option value="2">Email</option>
                </select>
                <input class="form-control mr-sm-2 search ml-1" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-primary my-2 my-sm-0 search-btn" type="submit"><i class="fas fa-search"></i></button>
              </div>
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Is Active</th> 
                  </tr>
                  </thead>
                    <tbody>
                      @if (isset($message))
                      <tr>
                        <td colspan="7" class="text-center">{{$message}}</td>   
                      </tr>
                      @else
                      @foreach ($clients as $client)
                      <tr>
                        <td>{{$client->id}}</td>
                        <td><a href={{route('admin.clients.show',['client'=>$client->id])}}>{{$client->name}}</a></td>
                        <td>{{$client->email}}</td>
                        <td>{{$client->phone}}</td>
                        <td>{{$client->city->name}}</td>
                        <td>
                          <div class="custom-control custom-switch">
                            <input type="checkbox" name='is_active' class="custom-control-input" id="is_active" url={{route('admin.clients.update',['client'=>$client->id])}} value={{$client->is_active}} @if ($client->is_active)
                              checked
                            @endif>
                            <label class="custom-control-label" for="is_active">@if ($client->is_active)
                              Active
                              @else
                              De-active                        
                              @endif
                            </label>
                          </div>
                        </td>
                      </tr>
                      @endforeach   
                      @endif
                    </tbody>
                  <tfoot>
                  <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Is Active</th> 
                  </tr>
                  </tfoot>
                </table>
                <br>
                @if ($clients instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="row" style="float:right;margin-right: 0%;">{{ $clients->links('pagination::bootstrap-4') }}</div> 
                @endif
              </div>
            
            </div>
          </div>
    </section>
    
  @endsection