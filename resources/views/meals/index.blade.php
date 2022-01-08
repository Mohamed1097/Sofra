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
                <div class="form-inline my-2 mb-4">
                    <form action={{ url()->current()}}>
                        <input class="form-control mr-sm-2 ml-1" type="search" placeholder="Search" aria-label="Search" name="name">
                        <button class="btn btn-outline-primary my-2 my-sm-0 search-btn" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Restaurant</th>
                    <th>Sales</th>
                    <th>Control</th>
                  </tr>
                  </thead>
                    <tbody>
                      @if (isset($message))
                      <tr>
                        <td colspan="20" class="text-center">{{$message}}</td>   
                      </tr>
                      @else
                      @foreach ($meals as $meal)
                      <tr>
                        <td>{{$meal->id}}</td>
                        <td>{{$meal->name}}</td>
                        <td>{{$meal->restaurant->name}}</td>
                        <td>
                          @if ($meal->orders->first()->meals()->whereHas('orders',
                          function($query){
                              $query->where('status','accepted')->orWhere('status','delivered');
                          })->find($meal->id))
                              {{$meal->orders->first()->meals()->whereHas('orders',
                              function($query){
                                  $query->where('status','accepted')->orWhere('status','delivered');
                              })->find($meal->id)->pivot->where('meal_id',$meal->id)->sum('quantity')}}
                          @else
                              0
                          @endif
                        </td>
                        <td>
                            <button class="btn btn-danger delete-btn" type='submit' element='{{$meal->name}}' data-toggle="modal" data-target='#delete-modal' url={{route('admin.meals.destroy',['meal'=>$meal->id])}}>
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
                    <th>Restaurant</th>
                    <th>Sales</th>
                    <th>Control</th>
                  </tr>
                  </tfoot>
                </table>
                @if ($meals instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="row" style="float:right;margin-right: 0%;">{{ $meals->links('pagination::bootstrap-4') }}</div> 
                @endif
              </div>
            
            </div>
          </div>
    </section>

@endsection
