<!DOCTYPE html>
<html data-bs-theme="light" lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - MyCart</title>

    <!-- ===============================================--><!--    Favicons--><!-- ===============================================-->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/assets/img/favicons/mycart_icon.png') }}">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('/vendors/simplebar/simplebar.min.js') }}"></script>

    <!-- ===============================================--><!--    Stylesheets--><!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('/vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/theme.min.css') }}" rel="stylesheet" id="style-default">
    <link href="{{ asset('/assets/css/user.min.css') }}" rel="stylesheet" id="user-style-default">
    @yield('style')
</head>

  <body>
    <!-- ===============================================--><!--    Main Content--><!-- ===============================================-->
    <main class="main" id="top">
      <div class="container-fluid" data-layout="container">
        @include('admin.layouts.sidebar')
        <div class="content">
          @include('admin.layouts.header')
          @yield('content')
        </div>
      </div>
    </main>
    <!-- ===============================================--><!--    End of Main Content--><!-- ===============================================-->


    <!-- ===============================================--><!--    JavaScripts--><!-- ===============================================-->
    <script src="{{ asset('/vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('/vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('/vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('/vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('/vendors/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('/vendors/list.js/list.min.js') }}"></script>
    <script src="{{ asset('/assets/js/theme.js') }}"></script>
    <script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('/ajax.js') }}"></script>

    @yield('script')

    <script>
      $(document).ready(function() {
        @if (session('success'))
          const Toast = Swal.mixin({
            toast: true,
            position: "top",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.onmouseenter = Swal.stopTimer;
              toast.onmouseleave = Swal.resumeTimer;
            }
          });
          Toast.fire({
            icon: "success",
            title: "{{ session('success') }}"
          });
        @endif
      });
    </script>
  </body>

</html>