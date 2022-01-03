@extends('layouts.app')
@inject('client','App\Models\Client')
@inject('restaurant','App\Models\Restaurant')
@inject('contact','App\Models\Contact')
@inject('offer','App\Models\Offer')
<!-- Content Wrapper. Contains page content -->
@section('content')
  
    <!-- Content Header (Page header) -->

    <!-- Main content -->
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                  <span class="info-box-icon bg-info"><i class="far fa-user"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Clients</span>
                    <span class="info-box-number">{{$client->count()}}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                  <span class="info-box-icon bg-info"><i class="fas fa-pizza-slice"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Restaurants</span>
                    <span class="info-box-number">{{$restaurant->count()}}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                  <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Messages</span>
                    <span class="info-box-number">{{$contact->count()}}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                  <span class="info-box-icon bg-info"><i class="fas fa-gift"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Offers</span>
                    <span class="info-box-number">{{$offer->count()}}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              
        </div>
      <!-- /.card -->

  <!-- /.content-wrapper -->
  @endsection