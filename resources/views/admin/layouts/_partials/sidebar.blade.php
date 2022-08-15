<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
  <div class="scrollbar-inner">
    <!-- Brand -->
    <div class="sidenav-header d-flex align-items-center">
      <a class="navbar-brand" href="{{ url('admin') }}">
        <h2 class="text-primary mb-0">Administrator</h2>
      </a>
      <div class="ml-auto">
        <!-- Sidenav toggler -->
        <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
          <div class="sidenav-toggler-inner">
            <i class="sidenav-toggler-line"></i>
            <i class="sidenav-toggler-line"></i>
            <i class="sidenav-toggler-line"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="navbar-inner">
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Nav items -->
        <ul class="navbar-nav">

          <li class="nav-item">
            <a class="nav-link" href="{{ url('admin') }}">
              <i class="ni ni-shop text-primary"></i>
              <span class="nav-link-text">Dashboard</span>
            </a>
          </li>

        </ul>

        <!-- Divider -->
        <hr class="my-3">
        <!-- Heading -->
        <h6 class="navbar-heading p-0 text-muted">Ruangan Menu</h6>
        <!-- Navigation -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#navbar-rooms" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-forms">
              <i class="ni ni-ruler-pencil"></i>
              <span class="nav-link-text">Ruangan </span>
            </a>
            <div class="collapse" id="navbar-rooms">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                  <a href="{{ url('admin/rooms') }}" class="nav-link"><i class="fa fa-rss"></i> Semua Ruangan</a>
                </li>
                <li class="nav-item">
                  <a href="{{ url('admin/rooms/trash') }}" class="nav-link"><i class="fa fa-trash"></i> Ruangan Delete</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#navbar-types" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-forms">
              <i class="ni ni-tag"></i>
              <span class="nav-link-text">Tipe Ruangan</span>
            </a>
            <div class="collapse" id="navbar-types">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                  <a href="{{ url('admin/types') }}" class="nav-link"><i class="fa fa-tags"></i> Tipe</a>
                </li>
                <li class="nav-item">
                  <a href="{{ url('admin/types/trash') }}" class="nav-link"><i class="fa fa-trash"></i> Tipe Delete</a>
                </li>
              </ul>
            </div>
          </li>

        </ul>

        <!-- Divider -->
        <hr class="my-3">
        <!-- Heading -->
        <h6 class="navbar-heading p-0 text-muted">Fasilitas Menu</h6>
        <!-- Navigation -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#navbar-facilities" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-forms">
              <i class="ni ni-world-2"></i>
              <span class="nav-link-text">Fasilitas</span>
            </a>
            <div class="collapse" id="navbar-facilities">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                  <a href="{{ url('admin/facilities') }}" class="nav-link"><i class="fa fa-building"></i> Semua Fasilitas</a>
                </li>
                <li class="nav-item">
                  <a href="{{ url('admin/facilities/trash') }}" class="nav-link"><i class="fa fa-trash"></i> Fasilitas Delete</a>
                </li>
              </ul>
            </div>
          </li>

        </ul>

        @if (Auth::user()->level->level == "admin")
        <!-- Divider -->
        <hr class="my-3">
        <!-- Heading -->
        <h6 class="navbar-heading p-0 text-muted">Customer Menu</h6>
        <!-- Navigation -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#navbar-customers" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-forms">
              <i class="ni ni-single-02"></i>
              <span class="nav-link-text">Customer</span>
            </a>
            <div class="collapse" id="navbar-customers">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                  <a href="{{ url('admin/customers') }}" class="nav-link"><i class="fa fa-users"></i> Semua Customer</a>
                </li>
                <li class="nav-item">
                  <a href="{{ url('admin/customers/trash') }}" class="nav-link"><i class="fa fa-trash"></i> Customer Delete</a>
                </li>
              </ul>
            </div>
          </li>
          
        </ul>
        @endif

        <!-- Divider -->
        <hr class="my-3">
        <!-- Heading -->
        <h6 class="navbar-heading p-0 text-muted">Order Menu</h6>
        <!-- Navigation -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#navbar-orders" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-forms">
              <i class="ni ni-single-copy-04"></i>
              <span class="nav-link-text">Order </span>
            </a>
            <div class="collapse" id="navbar-orders">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                  <a href="{{ url('admin/orders') }}" class="nav-link"><i class="fa fa-rss"></i> Semua Orderan</a>
                </li>
                <li class="nav-item">
                  <a href="{{ url('admin/orders/trash') }}" class="nav-link"><i class="fa fa-trash"></i> Halaman Delete</a>
                </li>
              </ul>
            </div>
          </li>

        </ul>

        <!-- Divider -->
        <hr class="my-3">
        <!-- Heading -->
        <h6 class="navbar-heading p-0 text-muted">Pembayaran Menu</h6>
        <!-- Navigation -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#navbar-payments" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-forms">
              <i class="ni ni-single-copy-04"></i>
              <span class="nav-link-text">Pembayaran </span>
            </a>
            <div class="collapse" id="navbar-payments">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                  <a href="{{ url('admin/payments') }}" class="nav-link"><i class="fa fa-rss"></i> Semua Pembayaran</a>
                </li>
                <li class="nav-item">
                  <a href="{{ url('admin/payments/paid') }}" class="nav-link"><i class="fa fa-check"></i> Pembayaran Lunas</a>
                </li>
                <li class="nav-item">
                  <a href="{{ url('admin/payments/cancelled') }}" class="nav-link"><i class="fa fa-clock"></i> Pembayaran Tertunda</a>
                </li>
                <li class="nav-item">
                  <a href="{{ url('admin/payments/trash') }}" class="nav-link"><i class="fa fa-trash"></i> Pembayaran Delete</a>
                </li>
              </ul>
            </div>
          </li>

       @if (Auth::user()->level->level == "admin")
        </ul>
        <!-- Divider -->
        <hr class="my-3">
        <!-- Heading -->
        <h6 class="navbar-heading p-0 text-muted">Pegawai Menu</h6>

        <!-- Navigation -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="{{ url('admin/users') }}" class="nav-link"><i class="fa fa-user"></i> Pegawai</a>
          </li>
          
        </ul>
        @endif

      </div>
    </div>
  </div>
</nav>
