<!DOCTYPE html>
<html data-bs-theme="light" lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - MyCart</title>

    <!-- ===============================================--><!--    Favicons--><!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/assets/img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/assets/img/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/assets/img/favicons/favicon-16x16.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/assets/img/favicons/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('/assets/img/favicons/manifest.json') }}">
    <meta name="msapplication-TileImage" content="{{ asset('/assets/img/favicons/mstile-150x150.png') }}">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('/vendors/simplebar/simplebar.min.js') }}"></script>

    <!-- ===============================================--><!--    Stylesheets--><!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('/vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/theme.min.css') }}" rel="stylesheet" id="style-default">
    <link href="{{ asset('/assets/css/user.min.css') }}" rel="stylesheet" id="user-style-default">
</head>

<body>
    <!-- ===============================================--><!--    Main Content--><!-- ===============================================-->
    <main class="main" id="top">
      <div class="container" data-layout="container">
        <div class="row flex-center min-vh-100 py-6">
          <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
            <a class="d-flex flex-center mb-4" href="{{ route('admin.dashboard') }}">
              <img class="me-2" src="{{ asset('/assets/img/favicons/mycart.png') }}" alt="" width="200" />
              {{-- <span class="font-sans-serif text-primary fw-bolder fs-4 d-inline-block">MyCart</span> --}}
            </a>
            <div class="card">
              <div class="card-body p-4 p-sm-5">
                <div class="row flex-between-center mb-2">
                  <div class="col-auto">
                    <h5>Log in</h5>
                  </div>
                </div>
                <form id="loginForm">
                  <div class="mb-3">
                    <label class="form-label ps-1" for="email">Email</label>
                    <input class="form-control" type="email" name="email" id="email" placeholder="Email address" />
                  </div>
                  <div class="mb-3">
                    <label class="form-label ps-1" for="password">Password</label>
                    <input class="form-control" type="password" name="password" id="password" placeholder="Password" autocomplete />
                  </div>
                  <div class="mb-3"><button class="btn btn-primary d-block w-100 mt-4" type="submit" name="submit">Log in</button></div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main><!-- ===============================================--><!--    End of Main Content--><!-- ===============================================-->

    <!-- ===============================================--><!--    JavaScripts--><!-- ===============================================-->
    <script src="{{ asset('/vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('/vendors/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('/assets/js/theme.js') }}"></script>
    <script src="{{ asset('/ajax.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('#loginForm').submit(function (e) { 
                e.preventDefault();
                let formData = new FormData(this);
                ajaxCall('{{ route('admin.login.post') }}', 'POST', formData, function (res) {
                    if (res.status == 'success') {
                        window.location.href = '{{ route('admin.dashboard') }}';
                    }
                },
                function (response) {
                    var response = JSON.parse(response.responseText);
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();
                    
                    $.each(response.message, function (key, value) { 
                         $(`#${key}`).addClass('is-invalid').after(` <span class="invalid-feedback">${value}</span> `);
                    });
                });
            });
        });
    </script>
</body>

</html>