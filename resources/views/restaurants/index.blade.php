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
              <button type="button" class="btn btn-primary mr-3 mb-3" data-toggle="modal" data-target="#filter" style="float: right">
                Filter
              </button>
              
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Commission</th>
                    <th>Active</th>
                    <th>Open</th>
                    <th>Busy</th>
                    <th>Warned</th>
                  </tr>
                  </thead>
                    <tbody>
                      @if (isset($message))
                      <tr>
                        <td colspan="20" class="text-center">{{$message}}</td>   
                      </tr>
                      @else
                      @foreach ($restaurants as $restaurant)
                      <tr>
                        <td>{{$restaurant->id}}</td>
                        <td><a href={{route('admin.restaurants.show',['restaurant'=>$restaurant->id])}}>{{$restaurant->name}}</a></td>
                        <td>{{$restaurant->email}}</td>
                        <td>{{$restaurant->phone}}</td>
                        <td>{{$restaurant->city->name}}</td>
                        <td>{{$restaurant->payment->commission-$restaurant->payment->amount}} L.E</td>
                        <td>
                          <div class="custom-control custom-switch">
                            <input v1='Active' v2='De-Active' type="checkbox" name='is_active' class="custom-control-input switch" id="is_active" url={{route('admin.restaurants.update',['restaurant'=>$restaurant->id])}} 
                            value={{$restaurant->is_active}} @if ($restaurant->is_active)
                              checked
                            @endif>
                            <label class="custom-control-label" for="is_active">@if ($restaurant->is_active)
                              Active
                              @else
                              De-active                        
                              @endif
                            </label>
                          </div>
                        </td>
                        <td>
                          <div class="custom-control custom-switch">
                            <input v1='Opened' v2='Closed' type="checkbox" name='is_opened' class="custom-control-input switch" id="is_opened" url={{route('admin.restaurants.update',['restaurant'=>$restaurant->id])}} 
                            value={{$restaurant->is_opened}} @if ($restaurant->is_opened)
                              checked
                            @endif>
                            <label class="custom-control-label" for="is_opened">@if ($restaurant->is_opened)
                              Opened
                              @else
                              Closed                        
                              @endif
                            </label>
                          </div>
                        </td>
                        <td>
                          <div class="custom-control custom-switch">
                            <input v1='Busy' v2='Not Busy' type="checkbox" name='is_busy' class="custom-control-input switch" id="is_busy" url={{route('admin.restaurants.update',['restaurant'=>$restaurant->id])}} 
                            value={{$restaurant->is_busy}} @if ($restaurant->is_busy)
                              checked
                            @endif>
                            <label class="custom-control-label" for="is_busy">@if ($restaurant->is_busy)
                              Busy
                              @else
                              Not Busy                        
                              @endif
                            </label>
                          </div>
                        </td>
                        <td>
                          <div class="custom-control custom-switch">
                            <input v1='Warned' v2='Not Warned' type="checkbox" name='is_warned' class="custom-control-input switch" id="is_warned" url={{route('admin.restaurants.update',['restaurant'=>$restaurant->id])}} 
                            value={{$restaurant->is_warned}} @if ($restaurant->is_warned)
                              checked
                            @endif>
                            <label class="custom-control-label" for="is_warned">@if ($restaurant->is_warned)
                              Warned
                              @else
                              Not Warned                        
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
                    <th>Commission</th>
                    <th>Active</th> 
                    <th>Open</th>
                    <th>Busy</th>
                    <th>Warned</th>
                  </tr>
                  </tfoot>
                </table>
                <br>
                @if ($restaurants instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="row" style="float:right;margin-right: 0%;">{{ $restaurants->links('pagination::bootstrap-4') }}</div> 
                @endif
              </div>
            
            </div>
          </div>
    </section>
    
    @push('custom-scripts')
    <script>
      $('.check').click(function(){
        let input=this.parentElement.parentElement.querySelectorAll('.input');
        if(input.length>1)
        {
          input=input[1]
        }
        else
        {
          input=input[0];
        }
        if(this.checked==true)
        {
          console.log(input);
          input.disabled=false;
        }
        else {
        input.disabled=true;
        }
      })
    </script>
      
    @endpush
  @endsection
