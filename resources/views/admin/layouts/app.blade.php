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
      $(document).ready(function () {
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

        let searchXHR = null;
        let searchTimer = null;

        $("#searchInput").on('input', function () {

          let query = $(this).val().trim();

          // clear previous debounce
          clearTimeout(searchTimer);

          searchTimer = setTimeout(function () {

            // if empty input
            if (query === '') {
              $("#previewData").html('');
              return;
            }

            // abort previous request
            if (searchXHR !== null) {
              searchXHR.abort();
            }

            searchXHR = $.ajax({
              type: "POST",
              url: "{{ route('admin.dashboard.search') }}",
              data: { query: query },
              dataType: "json",
              beforeSend: function () {
                $("#previewData").html(`<div class="text-center py-3"><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div></div>`);
              },
              success: function (response) {

                let html = '';
                let products = '<h6 class="dropdown-header fw-medium text-uppercase px-x1 fs-11 pt-0 pb-2">Product</h6>';
                let users = '<h6 class="dropdown-header fw-medium text-uppercase px-x1 fs-11 pt-0 pb-2">User</h6>';

                if (response.products && response.products.length) {

                  response.products.forEach(function (item) {

                    products += `<a class="dropdown-item px-x1 py-2" href="/home/product/${item.url}" target="_blank">
                                    <div class="d-flex align-items-center">
                                        <div class="file-thumbnail me-2">
                                            <img class="border h-100 w-100 object-fit-cover rounded-3" src="${item.image}">
                                        </div>
                                        <div class="flex-1">
                                            <h6 class="mb-0 title text-truncate">${item.name}</h6>
                                            <p class="fs-11 mb-0 d-flex">
                                                <span class="fw-semi-bold">${item.final_price}</span>
                                                ${item.discount_percentage == 0 ? '' : `<del class="ms-2">${item.base_price}</del><span class="fw-medium text-success ms-2">${item.discount_percentage}% off</span>`}
                                            </p>
                                        </div>
                                    </div>
                                </a>`;
                  });

                  html += products;
                }
                if (response.products.length && response.users.length) {
                  html += '<hr class="text-200 dark__text-900" />';
                }

                if (response.users && response.users.length) {

                  response.users.forEach(function (item) {

                    let avatar = item.image
                      ? `<img class="rounded-circle" src="${item.image}" alt="${item.name}">`
                      : `<div class="avatar-name rounded-circle"><span>${item.name.charAt(0)}</span></div>`;

                    users += `<a class="dropdown-item px-x1 py-2" href="/admin/customers/${item.id}">
                                  <div class="d-flex align-items-center">
                                      <div class="avatar avatar-l me-2">${avatar}</div>
                                      <div class="flex-1">
                                          <h6 class="mb-0 title text-truncate">${item.name}</h6>
                                          <p class="fs-11 mb-0 d-flex">${item.email}</p>
                                      </div>
                                  </div>
                              </a>`;
                  });

                  html += users;
                }

                if (!html) {
                  $("#previewData").html(`<div class="text-center py-3"><p class="fw-bold fs-8">No Result Found.</p></div>`);
                  return;
                }

                $("#previewData").html(`<div class="scrollbar list py-3" style="max-height:24rem;">${html}</div>`);
              }
            });

          }, 500);

        });


      });
    </script>
  </body>

</html>