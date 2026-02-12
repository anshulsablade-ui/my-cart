<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-pwa="true">

<head>
  <meta charset="utf-8">

  <!-- Viewport -->
  <meta name="viewport"
    content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title') - MyCart</title>

  <!-- Webmanifest + Favicon / App icons -->
  <link rel="icon" type="image/png" href="{{ asset('/assets/img/favicons/mycart_icon.png') }}" sizes="32x32">

  <!-- Theme switcher (color modes) -->
  <script src="{{ asset('web/assets/js/theme-switcher.js') }}"></script>

  <!-- Preloaded local web font (Inter) -->
  <link rel="preload" href="{{ asset('web/assets/fonts/inter-variable-latin.woff2') }}" as="font" type="font/woff2"
    crossorigin="">

  <!-- Font icons -->
  <link rel="preload" href="{{ asset('web/assets/icons/cartzilla-icons.woff2') }}" as="font" type="font/woff2"
    crossorigin="">
  <link rel="stylesheet" href="{{ asset('web/assets/icons/cartzilla-icons.min.css') }}">
  <!-- Vendor styles -->
  <link rel="stylesheet" href="{{ asset('web/assets/vendor/swiper/swiper-bundle.min.css') }}">
  <link rel="stylesheet" href="{{ asset('web/assets/vendor/drift-zoom/drift-basic.min.css') }}">
  <link rel="stylesheet" href="{{ asset('web/assets/vendor/simplebar/simplebar.min.css') }}">
  <link rel="stylesheet" href="{{ asset('web/assets/vendor/choices.js/choices.min.css') }}">

  <!-- Bootstrap + Theme styles -->
  <link rel="stylesheet" href="{{ asset('web/assets/css/theme.min.css') }}">

  <style>
    .message {
      margin: 10px 0;
      padding: 12px 16px;
      border-radius: 20px;
      min-width: 40%;
      max-width: 78%;
      line-height: 1.4;
      word-wrap: break-word;
    }

    .sent {
      background: #2563eb;
      color: white;
      margin-left: auto;
    }

    .received {
      background: black;
      color: white;
      margin-right: auto;
    }
  </style>
  @yield('style')
</head>

<!-- Body -->

<body>
  @include('mycart.layouts.header')
  @yield('content')
  @include('mycart.layouts.footer')

  @if (!request()->routeIs('ai.index'))
    <!-- Chat Bot -->
    <div class="position-fixed bottom-0 end-0 z-sticky me-3 me-xl-4 pb-4">
      <!-- Card with header and footer -->
      <div class="card" id="chat-content" style="display: none; width: 500px;">
        <div class="card-header d-flex justify-content-between">
          <h6 class="m-0">MyCart</h6>
          <a href="{{ route('ai.index') }}"><i class="ci-maximize"></i></a>
        </div>
        <div class="card-body" id="chatMessages" style="height:320px;overflow-y:auto;"></div>

        <div class="card-footer fs-sm text-body-secondary px-2">
          <form id="chatForm" class="d-flex align-items-center">
            <input type="text" class="form-control" id="chatInput" name="message" placeholder="Type your message">
            <button type="submit" id="messageSubmit" class="ms-2 btn btn-icon btn-secondary fs-lg rounded-circle">
              <i class="ci-send"></i>
            </button>
          </form>
        </div>
      </div>

      <div class="d-flex justify-content-end mt-2">
        <button class="btn btn-xl bg-body border border-3 border-info rounded-pill shadow" id="chatBtn">
          Chat <i class="ci-arrow-right fs-base ms-1 me-n1"></i>
        </button>
      </div>
    </div>
  @endif

  <!-- Back to top button -->
  <div class="floating-buttons position-fixed top-50 end-0 z-sticky me-3 me-xl-4 pb-4">
    <a class="btn-scroll-top btn btn-sm bg-body border-0 rounded-pill shadow animate-slide-end" href="#top">
      Top
      <i class="ci-arrow-right fs-base ms-1 me-n1 animate-target"></i>
      <span class="position-absolute top-0 start-0 w-100 h-100 border rounded-pill z-0"></span>
      <svg class="position-absolute top-0 start-0 w-100 h-100 z-1" viewBox="0 0 62 32" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <rect x=".75" y=".75" width="60.5" height="30.5" rx="15.25" stroke="currentColor" stroke-width="1.5"
          stroke-miterlimit="10"></rect>
      </svg>
    </a>
  </div>

  <!-- Vendor scripts -->
  <script src="{{ asset('web/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('web/assets/vendor/drift-zoom/Drift.min.js') }}"></script>
  <script src="{{ asset('web/assets/vendor/simplebar/simplebar.min.js') }}"></script>
  <script src="{{ asset('web/assets/vendor/choices.js/choices.min.js') }}"></script>
  <script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('ajax.js') }}"></script>

  <!-- Bootstrap + Theme scripts -->
  <script src="{{ asset('web/assets/js/theme.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

      @if (!request()->routeIs('ai.index'))
      $("#chatBtn").click(function () {
        $("#chat-content").slideToggle();
        const icon = $(this).find("i");
        icon.hasClass("ci-arrow-right") ? icon.removeClass("ci-arrow-right").addClass("ci-arrow-up") : icon.removeClass("ci-arrow-up").addClass("ci-arrow-right");
      });

      $("#chatForm").submit(function (e) { 
        e.preventDefault();
        let con = $("#chatMessages");
        con.append(`<div class="message sent"><p class="card-text">${ $("#chatInput").val() }</p></div>`);

        $.ajax({
          type: "post",
          url: "{{ route('ai.generate') }}",
          data: {
            message: $("#chatInput").val(),
            _token: "{{ csrf_token() }}"
          },
          success: function (response) {
            console.log(response);
            con.append(`<div class="message received"><p class="card-text">${ response }</p></div>`);
            $("#chatInput").val('');
          }
        });
      });
      @endif

    });

  </script>
  @yield('script')

</body>

</html>