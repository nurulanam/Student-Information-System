<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @yield('extraMeta')
  <title>@yield('title')</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />

</head>

<body>
    <script src="{{ asset('/vendor/sweetalert/sweetalert.all.js') }}"></script>
    @include('sweetalert::alert')

  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    @include('includes.sidebar')
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
        @include('includes.header')
      <!--  Header End -->
      <div class="container-fluid">
        @yield('content')
        <!--  Row 1 -->
        {{-- <div class="py-6 px-6 text-center">
          <p class="mb-0 fs-4">Design and Developed by <a href="https://dev-anam.com/" target="_blank" class="pe-1 text-primary text-decoration-underline">Dev-Anam.com</a></p>
        </div> --}}
      </div>
    </div>
  </div>
  <script src="{{ asset('/assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('/assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('/assets/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('/assets/js/app.min.js') }}"></script>

  @yield('extraJs')
</body>

</html>
