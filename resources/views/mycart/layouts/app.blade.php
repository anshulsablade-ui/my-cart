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
  #messages-content {
    height: 400px;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .message {
    max-width: 80%;
    margin-bottom: 4px;
    display: flex;
    flex-direction: column;
  }

  .message.sent {
    align-self: flex-end;
    align-items: flex-end;
  }

  .message.received {
    align-self: flex-start;
    align-items: flex-start;
  }

  .message .card-text {
    margin: 0;
    padding: 12px 16px;
    border-radius: 18px;
    font-size: 15px;
    line-height: 1.4;
    white-space: pre-wrap; /* important for line breaks */
    word-wrap: break-word;
  }

  .message.sent .card-text {
    background: #007bff;
    color: white;
    border-bottom-right-radius: 4px;
  }

  .message.received .card-text {
    background: #e9ecef;
    color: #212529;
    border-bottom-left-radius: 4px;
  }

  .received p {
    margin: 0;
  }

  #chatInput {
    border-radius: 24px !important;
    padding: 12px 20px !important;
    border: 1px solid #ced4da !important;
  }

  #messageSubmit {
    width: 48px;
    height: 48px;
    border-radius: 50% !important;
    background: #007bff !important;
    border: none;
  }

  #messageSubmit:disabled {
    background: #adb5bd !important;
    cursor: not-allowed;
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
    <div class="position-fixed bottom-0 end-0 z-sticky me-3 me-xl-5 pb-4">
      <!-- Card with header and footer -->
      <div class="card" id="chat-content" style="display: none; width: 500px;">
        <div class="card-header d-flex justify-content-between">
          <h6 class="m-0">MyCart</h6>
          <a href="{{ route('ai.index') }}"><i class="ci-maximize"></i></a>
        </div>
        <div class="card-body" id="messages-content" style="height:320px;overflow-y:auto;"></div>

        <div class="card-footer fs-sm text-body-secondary px-2">
          <form id="chatForm" class="d-flex align-items-center gap-2">
              <input type="text" class="form-control" id="chatInput" name="message" placeholder="Type your message..." autocomplete="off">
              <button type="submit" id="messageSubmit" class="btn btn-primary btn-icon rounded-circle" disabled>
                  <i class="ci-send fs-5"></i>
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
  <div class="floating-buttons position-fixed top-50 end-0 z-sticky me-3 me-xl-4">
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
  <script src="https://cdn.jsdelivr.net/npm/marked/lib/marked.umd.min.js"></script>
  <script>
    $(document).ready(function () {

      const $messages = $("#messages-content");
      const $input = $("#chatInput");
      const $form = $("#chatForm");
      const $submit = $("#messageSubmit");

      // Auto-disable button
      $input.on('input change keyup', function () {
        $submit.prop('disabled', this.value.trim().length === 0);
      });

      // Submit
      $form.submit(function (e) {
        e.preventDefault();

        const message = $input.val().trim();
        if (!message) return;

        // Add user message
        appendMessage('sent', message);
        $input.val('').focus();

        $.ajax({
          type: "POST",
          url: "{{ route('ai.generate') }}",
          data: {
            message: message,
            _token: "{{ csrf_token() }}"
          },
          beforeSend: function () {
            $submit.prop('disabled', true);
            appendMessage('received', '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...');
            scrollToBottom(true);
          },
          success: function (response) {
            let html = marked.parse(response);
            $('.spinner-border').parent('div').parent('div').remove();
            appendMessage('received', html);
            scrollToBottom();
          },
          error: function (xhr) {
            $('.spinner-border').parent('div').parent('div').remove();
            appendMessage('received', 'Sorry, something went wrong...');
            console.error(xhr);
          }
        });
      });

      function appendMessage(type, text) {
        const $msg = $(`<div class="message ${type}"><div class="card-text">${text}</div></div>`);
        $messages.append($msg);
        scrollToBottom();
      }

      function scrollToBottom(force = false) {
        const el = $messages[0];
        if (!el) return;

        const distanceToBottom = el.scrollHeight - el.scrollTop - el.clientHeight;

        if (force || distanceToBottom < 150) {
          el.scrollTo({
            top: el.scrollHeight,
            behavior: 'smooth'
          });
        }
      }

      // Initial scroll
      setTimeout(() => scrollToBottom(true), 100);

      // Optional: auto-scroll when window resizes (mobile keyboard)
      $(window).on('resize', () => scrollToBottom());
    });
  </script>
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

      @endif

    });

  </script>
  @yield('script')

</body>

</html>