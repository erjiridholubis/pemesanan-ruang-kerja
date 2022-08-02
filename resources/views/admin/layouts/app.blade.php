<!DOCTYPE html>
<html>
<head>
  @include('admin.layouts._partials.header')
</head>

<body>

  <!-- Sidenav -->
  @include('admin.layouts._partials.sidebar')

  <!-- Main content -->
  <div class="main-content" id="panel">
    <!-- Topnav -->
    @include('admin.layouts._partials.navbar')
    @include('admin.layouts._partials.logout')

    <!-- Header -->
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">

          @include('admin.layouts._partials.breadcrumb')
          @yield('cardheader')

        </div>
      </div>
    </div>

    <!-- Page content -->
    <div class="container-fluid mt--6">
      @yield('content')

      <!-- Footer -->
      @include('admin.layouts._partials.footer')

    </div>
  </div>
  <!-- Argon Scripts -->
  @include('admin.layouts._partials.js')
  @include('vendor.sweet.alert')
</body>
</html>
