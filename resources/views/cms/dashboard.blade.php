{{-- TEMPORARY DEBUG - remove after testing --}}
{{-- <div style="background:red;color:white;padding:10px;">
  Guard: {{ auth()->getDefaultDriver() }}<br>
  @php
    $debugAdmin = auth('admin')->user();
  @endphp
  Admin guard user: {{ $debugAdmin ? $debugAdmin->full_name : 'NULL' }}<br>
  Can Read-Admins: {{ $debugAdmin && $debugAdmin->can('Read-Admins') ? 'YES' : 'NO' }}<br>
  Has Role Admin: {{ $debugAdmin && $debugAdmin->hasRole('Admin') ? 'YES' : 'NO' }}<br>
  Roles: {{ $debugAdmin ? $debugAdmin->getRoleNames() : 'NULL' }}<br>
  Permissions: {{ $debugAdmin ? $debugAdmin->getAllPermissions()->pluck('name') : 'NULL' }}
</div>
 --}}

@if (session('guest_blocked'))
  <div style="display:none" id="guest-blocked-msg">{{ session('guest_blocked') }}</div>
@endif


@extends('cms.parent')

@section('title', 'Dashboard')
@section('page-name', '')
@section('main-page', '')
@section('sub-page', '')

@section('styles')
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('cms/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('cms/plugins/daterangepicker/daterangepicker.css') }}">



  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('cms/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet"
    href="{{ asset('cms/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('cms/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('cms/plugins/jqvmap/jqvmap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('cms/dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('cms/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('cms/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('cms/plugins/summernote/summernote-bs4.min.css') }}">
@endsection

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Info boxes -->
      @if (Auth::user()->hasRole('User'))
        <div class="row">
          <div class="col-12 col-sm-6 col-md-4">
            <a href="{{ route('users.index') }}" style="text-decoration:none;">
              <div class="info-box" style="cursor:pointer;">
                <span class="info-box-icon bg-info elevation-1">
                  <i class="fas fa-users"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text"><b>Childs</b></span>
                  <span class="info-box-number"><b>{{ $childs }}</b></span>
                </div>
              </div>
            </a>
          </div>

          <div class="col-12 col-sm-6 col-md-4">
            <a href="{{ route('wallets.index') }}" style="text-decoration:none;">
              <div class="info-box mb-3" style="cursor:pointer;">
                <span class="info-box-icon bg-danger elevation-1">
                  <i class="fas fa-wallet"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text"><b>Wallets</b></span>
                  <span class="info-box-number"><b>{{ $wallets }}</b></span>
                </div>
              </div>
            </a>
          </div>

          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-4">
            <a href="{{ route('debits.index') }}" style="text-decoration:none;">
              <div class="info-box mb-3" style="cursor:pointer;">
                <span class="info-box-icon bg-success elevation-1">
                  <i class="fas fa-wallet"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text"><b>Debits</b></span>
                  <span class="info-box-number"><b>{{ $debts }}</b></span>
                </div>
              </div>
            </a>
          </div>
        </div>
        <!-- /.row -->
      @else
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('admins.index') }}" style="text-decoration:none;">
              <div class="info-box" style="cursor:pointer;">
                <span class="info-box-icon bg-info elevation-1">
                  <i class="fas fa-users-cog"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text"><b>Admins</b></span>
                  <span class="info-box-number"><b>{{ $admins }}</b></span>
                </div>
              </div>
            </a>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('users.index') }}" style="text-decoration:none;">
              <div class="info-box mb-3" style="cursor:pointer;">
                <span class="info-box-icon bg-danger elevation-1">
                  <i class="fas fa-users"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text"><b>Users</b></span>
                  <span class="info-box-number"><b>{{ $users }}</b></span>
                </div>
              </div>
            </a>
          </div>

          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('cities.index') }}" style="text-decoration:none;">
              <div class="info-box mb-3" style="cursor:pointer;">
                <span class="info-box-icon bg-success elevation-1">
                  <i class="fas fa-city"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text"><b>Cities</b></span>
                  <span class="info-box-number"><b>{{ $cities }}</b></span>
                </div>
              </div>
            </a>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('currencies.index') }}" style="text-decoration:none;">
              <div class="info-box mb-3" style="cursor:pointer;">
                <span class="info-box-icon bg-warning elevation-1">
                  <i class="fas fa-coins"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text"><b>Currencies</b></span>
                  <span class="info-box-number"><b>{{ $currencies }}</b></span>
                </div>
              </div>
            </a>
          </div>
        </div>
      @endif


      <!-- Main row -->
      <div class="row">

        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-12 connectedSortable">

          <!-- Map card -->
          <div class="card bg-gradient-primary" style="display: none;">
            <div class="card-header border-0">
              <h3 class="card-title">
                <i class="fas fa-map-marker-alt mr-1"></i>
                Visitors
              </h3>
              <!-- card tools -->
              <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm daterange" title="Date range">
                  <i class="far fa-calendar-alt"></i>
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <div class="card-body">
              <div id="world-map" style="height: 250px; width: 100%;"></div>
            </div>
            <!-- /.card-body-->
            <div class="card-footer bg-transparent">
              <div class="row">
                <div class="col-4 text-center">
                  <div id="sparkline-1"></div>
                  <div class="text-white">Visitors</div>
                </div>
                <!-- ./col -->
                <div class="col-4 text-center">
                  <div id="sparkline-2"></div>
                  <div class="text-white">Online</div>
                </div>
                <!-- ./col -->
                <div class="col-4 text-center">
                  <div id="sparkline-3"></div>
                  <div class="text-white">Sales</div>
                </div>
                <!-- ./col -->
              </div>
              <!-- /.row -->
            </div>
          </div>
          <!-- /.card -->

          <!-- Calendar -->
          <div class="card bg-gradient-success">
            <div class="card-header border-0">

              <h3 class="card-title">
                <i class="far fa-calendar-alt"></i>
                Calendar
              </h3>
              <!-- tools card -->
              <div class="card-tools">
                <!-- button with a dropdown -->
                <div class="btn-group">
                  <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown"
                    data-offset="-52">
                    <i class="fas fa-bars"></i>
                  </button>
                  <div class="dropdown-menu" role="menu">
                    <a href="#" class="dropdown-item">Add new event</a>
                    <a href="#" class="dropdown-item">Clear events</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">View calendar</a>
                  </div>
                </div>
                <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
              <!-- /. tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body pt-0">
              <!--The calendar -->
              <div id="calendar" style="width: 100%"></div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </div>
    <!--/. container-fluid -->
  </section>
  <!-- /.content -->
@endsection

@section('scripts')
  <!-- overlayScrollbars -->
  <script src="{{ asset('cms/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

  <!-- PAGE PLUGINS -->
  <!-- jQuery Mapael -->
  <script src="{{ asset('cms/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
  <script src="{{ asset('cms/plugins/raphael/raphael.min.js') }}"></script>
  <script src="{{ asset('cms/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
  <script src="{{ asset('cms/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
  <!-- ChartJS -->
  <script src="{{ asset('cms/plugins/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('cms/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('cms/plugins/daterangepicker/daterangepicker.js') }}"></script>

  <!-- PAGE SCRIPTS -->
  <script src="{{ asset('cms/dist/js/pages/dashboard2.js') }}"></script>


  <!-- jQuery -->
  <script src="{{ asset('cms/plugins/jquery/jquery.min.js') }}"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="{{ asset('cms/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('cms/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- ChartJS -->
  <script src="{{ asset('cms/plugins/chart.js/Chart.min.js') }}"></script>
  <!-- Sparkline -->
  <script src="{{ asset('cms/plugins/sparklines/sparkline.js') }}"></script>
  <!-- JQVMap -->
  <script src="{{ asset('cms/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
  <script src="{{ asset('cms/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
  <!-- jQuery Knob Chart -->
  <script src="{{ asset('cms/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
  <!-- daterangepicker -->
  <script src="{{ asset('cms/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('cms/plugins/daterangepicker/daterangepicker.js') }}"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="{{ asset('cms/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
  <!-- Summernote -->
  <script src="{{ asset('cms/plugins/summernote/summernote-bs4.min.js') }}"></script>
  <!-- overlayScrollbars -->
  <script src="{{ asset('cms/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('cms/dist/js/adminlte.js') }}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{ asset('cms/dist/js/demo.js') }}"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="{{ asset('cms/dist/js/pages/dashboard.js') }}"></script>

  @if (session('guest_blocked'))
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          title: 'Guest Access Only',
          html: '<i class="fas fa-lock" style="font-size:40px;color:#e67e22;"></i>' +
            '<br><br>Guests cannot access <strong>Profile Settings</strong> or <strong>Change Password</strong>.' +
            '<br>Please login with a full account.',
          icon: 'warning',
          confirmButtonText: 'OK',
          confirmButtonColor: '#e67e22',
        });
      });
    </script>
  @endif
@endsection
