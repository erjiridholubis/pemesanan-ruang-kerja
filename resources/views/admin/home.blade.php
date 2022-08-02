@extends('admin.layouts.app')
@push('css')
  <!-- Page plugins -->
  <link rel="stylesheet" href="/assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
@endpush

@section('cardheader')
  <div class="row">
    <div class="col-xl-3 col-md-6">
      <div class="card card-stats">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">Total Orderan</h5>
              <span class="h2 font-weight-bold mb-0">{{ $cPost }}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                <i class="fas fa-newspaper"></i>
              </div>
            </div>
          </div>
          <p class="mt-3 mb-0 text-sm">
          </p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card card-stats">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">Total Ruangan</h5>
              <span class="h2 font-weight-bold mb-0">{{ $cPage }}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                <i class="far fa-file-alt"></i>
              </div>
            </div>
          </div>
          <p class="mt-3 mb-0 text-sm">
          </p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card card-stats">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">Total Fasilitas</h5>
              <span class="h2 font-weight-bold mb-0">{{ $cStaff }}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                <i class="fas fa-users"></i>
              </div>
            </div>
          </div>
          <p class="mt-3 mb-0 text-sm">
          </p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card card-stats">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">Total Customer</h5>
              <span class="h2 font-weight-bold mb-0">{{ $cOrganization }}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                <i class="far fa-building"></i>
              </div>
            </div>
          </div>
          <p class="mt--1 mb-0 text-sm">
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('content')
  <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header">
              <h3 class="mb-0">{{ $title }}</h3>
              <p class="text-sm mb-0">
                {{ $description }}
              </p>
            </div>
            <div class="card-body">
              <div class="container">
                <div class="row">
                  
                </div>
              </div>
            </div>

          </div>

        </div>
      </div>
@endsection

@push('script')
  <!-- Optional JS -->
  <script src="/assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="/assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="/assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
@endpush
