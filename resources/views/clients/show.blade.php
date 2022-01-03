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
                      <tr>
                      <td>{{$client->id}}</td>
                      <td>{{$client->name}}</td>
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
                            @endif</label>
                          </div>
                        </td>
                      </tr>
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
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">{{$client->name}}’s Orders </h3>
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
                    <th>Restaurant</th>
                    <th>Total Price</th>
                    <th>price</th>
                    <th>Commission</th>
                    <th>Address</th>
                    <th>status</th>
                  </tr>
                  </thead>
                  <tbody>
                      @if (isset($message))
                        <td colspan="7" class="text-center">{{$message}}</td>   
                      @else
                      @foreach ($orders as $order)
                      <tr>
                        <td>{{$order->id}}</td>
                        <td>{{$order->restaurant->name}}</td>
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
                    <th>Restaurant</th>
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

  