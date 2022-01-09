@extends('layouts.app')
<!-- Content Wrapper. Contains page content -->
@section('content')
    <!-- Main content -->
        <div class="widget-user-image ml-1 mb-3 d-flex justify-content-center">
          <img class="img-circle elevation-2" style="width: 128px" height="128px" src={{asset($restaurant->restaurant_image)}} >
        </div>
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
                    <tr>
                        <td>{{$restaurant->id}}</td>
                        <td>{{$restaurant->name}}</td>
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
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">{{$restaurant->name}}’s Meals </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>name</th>
                    <th>content</th>
                    <th>price</th>
                    <th>price In Offer</th>
                    <th>Sales</th>
                    <th>Control</th>
                  </tr>
                  </thead>
                  <tbody>
                      @if (isset($mealMessage))
                        <td colspan="7" class="text-center">{{$mealMessage}}</td>   
                      @else
                      @foreach ($meals as $meal)
                      <tr>
                        <td>{{$meal->id}}</td>
                        <td>{{$meal->name}}</td>
                        <td>{{$meal->content}}</td>
                        <td>{{$meal->price}}</td>
                        <td>{{$meal->price_in_offer}}</td>
                        <td>
                          @if ($item=$meal->orders->first())
                              @if ($item=$item->meals()->whereHas('orders',function($query){
                                $query->where('status','accepted')->orWhere('status','delivered');
                            })->find($meal->id))
                                  {{$item->pivot->where('meal_id',$meal->id)->sum('quantity')}}  
                              @endif
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
                    <th>name</th>
                    <th>content</th>
                    <th>price</th>
                    <th>price In Offer</th>
                    <th>Sales</th>
                    <th>Control</th>
                  </tr>
                  </tfoot>
                </table>
                <br>
                @if ($meals instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="row" style="float:right;margin-right: 0%;">{{ $meals->links('pagination::bootstrap-4') }}</div> 
                @endif
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">{{$restaurant->name}}’s Orders </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="form-inline my-2 mb-4">
                <select class="custom-select status mr-1" id="inputGroupSelect01">
                  <option selected value='0'>status</option>
                  <option value="pending">pending</option>
                  <option value="accepted">accepted</option>
                  <option value="rejected">rejected</option>
                  <option value="canceled">canceled</option>
                  <option value="delivered">delivered</option>
                </select>
              </div>
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Client</th>
                    <th>Total Price</th>
                    <th>price</th>
                    <th>Commission</th>
                    <th>Address</th>
                    <th>status</th>
                  </tr>
                  </thead>
                  <tbody>
                      @if (isset($orderMessage))
                        <td colspan="7" class="text-center">{{$orderMessage}}</td>   
                      @else
                      @foreach ($orders as $order)
                      <tr>
                        <td>{{$order->id}}</td>
                        <td>{{$order->client->name}}</td>
                        <td>{{$order->total_price}}</td>
                        <td>{{$order->price}}</td>
                        <td>{{$order->commission}}</td>
                        <td>{{$order->address}}</td>
                        <td @if ($order->status=='delivered')
                          class='alert alert-success'
                        @elseif ($order->status=='pending')
                          class='alert alert-warning'
                        @endif>{{$order->status}}</td>
                      </tr>
                      @endforeach
                      @endif
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Id</th>
                    <th>Client</th>
                    <th>Total Price</th>
                    <th>price</th>
                    <th>Commission</th>
                    <th>Address</th>
                    <th>status</th>
                  </tr>
                  </tfoot>
                </table>
                <br>
                @if ($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="row" style="float:right;margin-right: 0%;">{{ $orders->links('pagination::bootstrap-4') }}</div> 
                @endif
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">{{$restaurant->name}}’s Offers </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>name</th>
                    <th>Description</th>
                    <th>start Date</th>
                    <th>End Date</th>
                    <th>Expired</th>
                  </tr>
                  </thead>
                  <tbody>
                      @if (isset($offerMessage))
                        <td colspan="7" class="text-center">{{$offerMessage}}</td>   
                      @else
                      @foreach ($offers as $offer)
                      <tr>
                        <td>{{$offer->id}}</td>
                        <td>{{$offer->name}}</td>
                        <td>{{$offer->description}}</td>
                        <td>{{$offer->start_date}}</td>
                        <td>{{$offer->end_date}}</td>
                        <td>@if ($offer->end_date<now()->toDateString())
                            Expired
                        @else
                            Not Expired
                        @endif</td>
                      </tr>
                      @endforeach
                      @endif
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Id</th>
                    <th>name</th>
                    <th>Description</th>
                    <th>start Date</th>
                    <th>End Date</th>
                    <th>Expired</th>
                  </tr>
                  </tfoot>
                </table>
                <br>
                @if ($offers instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="row" style="float:right;margin-right: 0%;">{{ $offers->links('pagination::bootstrap-4') }}</div> 
                @endif
              </div>
            </div>
          </div>
          
    </section>
    @push('custom-scripts')
    <script>
    $('.status').change(function(event) {
      let=url=window.location.href.split('?')[0];
      url+='?status='+$(this).val();
      window.location=url;
    });
    </script>
    @endpush 
  @endsection

  