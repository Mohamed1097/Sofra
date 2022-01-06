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
                    <th>No Of Restaurants</th>
                    <th>Control</th>
                  </tr>
                  </thead>
                    <tbody>
                      @if (isset($message))
                      <tr>
                        <td colspan="20" class="text-center">{{$message}}</td>   
                      </tr>
                      @else
                      @foreach ($foodCategories as $foodCategory)
                      <tr>
                        <td>{{$foodCategory->id}}</td>
                        <td>{{$foodCategory->name}}</td>
                        <td>{{$foodCategory->restaurants->count()}}</td>
                        <td>
                            <a class="btn btn-info" href={{route("admin.food-categories.edit",['food_category'=>$foodCategory->id])}}>
                                <i class="fas fa-edit"></i>
                              </a>
                              <button class="btn btn-danger delete-btn" type='submit' element='{{$foodCategory->name}}' data-toggle="modal" data-target='#delete-modal' url={{route('admin.food-categories.destroy',['food_category'=>$foodCategory->id])}}>
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
                    <th>No Of Restaurants</th>
                    <th>Control</th>
                  </tr>
                  </tfoot>
                </table>
                <br>
                <a class="btn btn-primary" href={{route('admin.food-categories.create')}}>
                    <i class="fas fa-plus"></i>
                    <span>Add New Food Category</span>
                  </a>
                @if ($foodCategories instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="row" style="float:right;margin-right: 0%;">{{ $foodCategories->links('pagination::bootstrap-4') }}</div> 
                @endif
              </div>
            
            </div>
          </div>
    </section>

@endsection