<!DOCTYPE html>
<html lang="en">
<head> 
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sofra -{{$title}}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href={{asset('plugins/fontawesome-free/css/all.min.css')}}>
  <link rel="icon" href={{asset('adminlte/img/AdminLTELogo.png')}}>

  <!-- Theme style -->
  <link rel="stylesheet" href={{ asset('adminlte/css/adminlte.min.css') }}>
   <!-- DataTables -->
   <link rel="stylesheet" href={{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}>
   <link rel="stylesheet" href={{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}>
   <link rel="stylesheet" href={{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}>
   <link rel="stylesheet" href={{asset('plugins/summernote/summernote-bs4.min.css')}}>

</head>
@include('layouts.filterModal')
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Danger</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger delete">Delete</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Danger</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger delete">Delete</button>
      </div>
    </div>
  </div>
</div>
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href={{route('admin.home')}} class="nav-link">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      

      <!-- Messages Dropdown Menu -->
  
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
     
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href={{route('admin.home')}} class="brand-link">
      <img src={{asset('adminlte/img/AdminLTELogo.png')}} alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Sofra</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src={{asset('adminlte/img/user2-160x160.jpg')}} class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{auth()->user()->name}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href={{route('admin.clients.index')}} class="nav-link @if (getUrl( url()->current(),'clients'))
              active
            @endif">
              <i class="fas fa-user nav-icon"></i>
              <p> Clients</p>
            </a>
          </li>
          <li class="nav-item">
            <a href={{route('admin.restaurants.index')}} class="nav-link @if (getUrl( url()->current(),'restaurants'))
              active
            @endif">
              <i class="fas fa-pizza-slice nav-icon"></i>
              <p> Restaurants</p>
            </a>
          </li>
          <li class="nav-item">
            <a href={{route('admin.food-categories.index')}} class="nav-link @if (getUrl( url()->current(),'food-categories'))
              active
            @endif">
              <i class="nav-icon fa fa-list-alt"></i>
              <p>Food Categories</p>
            </a>
          </li>
          <li class="nav-item">
            <a href={{route('admin.users.index')}} class="nav-link @if (getUrl( url()->current(),'users'))
              active
            @endif">
              <i class="fas fa-user nav-icon"></i>
              <p> Users</p>
            </a>
          </li>
          <li class="nav-item">
            <a href={{route('admin.roles.index')}} class="nav-link @if (getUrl( url()->current(),'roles'))
              active
            @endif">
            <i class="fab fa-critical-role nav-icon"></i>
              <p>
                Roles
              </p>
            </a>
          </li>
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
          {{-- <li class="nav-item">
            <a href="#" class="nav-link @if (getUrl( url()->current(),'post-categories')||getUrl( url()->current(),'post'))
              active
            @endif">
            <i class="nav-icon fas fa-sticky-note"></i>
              <p>
                Posts
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href={{route('admin.post.index')}} class="nav-link @if (getUrl( url()->current(),'post'))
                  active
                @endif">
                  <i class="nav-icon fas fa-sticky-note"></i>
                  <p>Posts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href={{route('admin.post-categories.index')}} class="nav-link @if (getUrl( url()->current(),'post-categories'))
                active
              @endif">
                  <i class="fa fa-list-alt nav-icon"></i>
                  <p>Post Categories</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href={{route('admin.clients.index')}} class="nav-link @if (getUrl( url()->current(),'clients'))
              active
            @endif">
              <i class="fas fa-user nav-icon"></i>
              <p> Clients</p>
            </a>
          </li>
          <li class="nav-item">
            <a href={{route('admin.users.index')}} class="nav-link @if (getUrl( url()->current(),'users'))
              active
            @endif">
              <i class="fas fa-user nav-icon"></i>
              <p> Users</p>
            </a>
          </li>
          <li class="nav-item">
            <a href={{route('admin.change-password')}} class="nav-link @if (getUrl( url()->current(),'set-new-password'))
              active
            @endif">
            <i class="fas fa-lock nav-icon"></i>
              <p> Set New Password</p>
            </a>
          </li>
          <li class="nav-item">
            <a href={{route('admin.donation-requests.index')}} class="nav-link @if (getUrl( url()->current(),'donation-requests'))
              active
            @endif">
              <i class="fas fa-crutch nav-icon"></i>
              <p>
                Donation Requests
              </p>
            </a>
          </li>
          <li class="nav-item"> 
            <a href={{route('admin.governorate.index')}} class="nav-link  @if (getUrl( url()->current(),'governorate'))
            active
          @endif">
            <i class="fas fa-city nav-icon"></i>
              <p>
                Governorates
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href={{route('admin.cities.index')}} class="nav-link @if (getUrl( url()->current(),'cities'))
            active
          @endif">
            <i class="fas fa-city nav-icon"></i>
              <p>
              Cities
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href={{route('admin.settings.index')}} class="nav-link @if (getUrl( url()->current(),'settings'))
              active
            @endif">
            <i class="fas fa-cogs nav-icon"></i>
              <p>
                Settings
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href={{route('admin.messages.index')}} class="nav-link @if (getUrl( url()->current(),'messages'))
              active
            @endif">
            <i class="fas fa-inbox nav-icon"></i>
              <p>
                Messages
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href={{route('admin.roles.index')}} class="nav-link @if (getUrl( url()->current(),'roles'))
              active
            @endif">
            <i class="fab fa-critical-role nav-icon"></i>
              <p>
                Roles
              </p>
            </a>
          </li> --}}
          
         
          <li class="nav-item">
            <a href="{{ route('admin.logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Sign Out
              </p>
          </a>
          
          <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
          </form>
            
          </li>
         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{$title}}</h1>
          </div>
          <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 20px;z-index:1000">
            <div class="toast" style="position: absolute; top: 50%; right: 50%;">
              <div class="toast-header">
                <strong class="mr-auto">Message</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="toast-body">
                Hello, world! This is a toast message.
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href={{route('admin.home')}}>Home</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
            @yield('content')  
    </section>
  </div>


  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.1.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->

<script src={{asset('plugins/jquery/jquery.min.js')}}></script>
<!-- Bootstrap 4 -->
<script src={{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}></script>
<!-- AdminLTE App -->
<script src={{asset('adminlte/js/adminlte.min.js')}}></script>
<!-- AdminLTE for demo purposes -->
<script src={{asset('adminlte/js/demo.js')}}></script>

 <script src={{asset('plugins/datatables/jquery.dataTables.min.js')}}></script>
<script src={{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}></script>
<script src={{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}></script>
<script src={{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}></script>
<script src={{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}></script>
<script src={{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>


<script src={{asset('plugins/jszip/jszip.min.js')}}></script>
<script src={{asset('plugins/pdfmake/pdfmake.min.js')}}></script>
<script src={{asset('plugins/pdfmake/vfs_fonts.js')}}></script>
<script src={{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}></script>
<script src={{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}></script>
<script src={{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}></script>
<script src={{asset('plugins/summernote/summernote-bs4.min.js')}}></script>
<script src={{asset('adminlte/js/custom.js')}}></script>

@stack('custom-scripts')




<script>
   $(document).ready(function() {
  $('#summernote').summernote();
  $('#about-summernote').summernote();
});
  if(document.querySelectorAll("ul.pagination").length>0)
  {
    document.querySelectorAll("ul.pagination")[-1].style.display='none';
  }
  else
  {
    document.querySelector("ul.pagination").style.display='none';
  }

  
$(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
 
 
  
 
</script>
</body>
</html>
