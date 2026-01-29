@extends('mycart.layouts.app')
@section('title', 'Order Details')
@section('style')
    
@endsection
@section('content')
    <main class="content-wrapper">

      <!-- Breadcrumb -->
      <nav class="container pt-3 my-3 my-md-4" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Order</a></li>
          <li class="breadcrumb-item active" aria-current="page">Order # {{ $order->order_no }}</li>
        </ol>
      </nav>


      <!-- Items in the cart + Order summary -->
      <section class="container pb-5 mb-2 mb-md-3 mb-lg-4 mb-xl-5">
        <div>
          <h4 class="offcanvas-title mb-1" id="orderDetailsLabel">Order # {{ $order->order_no }}</h4>
          <span class="d-flex align-items-center fs-sm fw-medium text-body-emphasis">
            <span class="bg-info rounded-circle p-1 me-2"></span>
            {{ $order->order_status }}
          </span>
        </div>
        @if ($order->payment_status == 'pending' && $order->payment_method != 'cod')     
          <div class="pt-3">
            <button class="btn btn-lg btn-primary payment-button" id="paymant-button">Pay {{ Number::currency($order->grand_total, 'INR') }}</button>
          </div>
        @endif

        <div class="row">

          <!-- Items list -->
          <div class="col-lg-8">
            <div class="pe-lg-2 pe-xl-3 me-xl-3">

              <!-- Table of items -->
              <table class="table position-relative z-2 mb-4">
                <thead>
                  <tr>
                    <th scope="col" class="fs-sm fw-normal py-3 ps-0"><span class="text-body">Product</span></th>
                    <th scope="col" class="text-body fs-sm fw-normal py-3 d-none d-md-table-cell"><span class="text-body">Total</span></th>
                  </tr>
                </thead>
                <tbody class="align-middle">

                    @foreach ($order->orderItems as $item)
                        <tr data-cart-id="{{ $item->id }}">
                          <td class="py-3 ps-0">
                            <div class="d-flex align-items-center">
                              <a class="flex-shrink-0" href="{{ route('product.show', $item->product->slug) }}">
                                @if ($item->product->discount_percentage)
                                    <span class="badge text-bg-danger position-absolute start-0">-{{ $item->product->discount_percentage }}%</span>
                                @endif
                                <img src="{{ asset('images/products/thumb/' . ($item->product->primaryImage->image ?? 'no-image.png')) }}" width="110" alt="{{ $item->product->name }}">
                              </a>
                              <div class="w-100 min-w-0 ps-2 ps-xl-3">
                                <h5 class="d-flex animate-underline mb-2">
                                  <a class="d-block fs-sm fw-medium text-truncate animate-target" href="{{ route('product.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                </h5>
                                <div class="h6 mb-2">{{ Number::currency($item->product->final_price, 'INR') }}</div>
                                <div class="fs-xs">Qty: {{ $item->quantity }}</div>
                              </div>
                            </div>
                          </td>
                          <td class="h6 py-3 d-none d-md-table-cell total-price">{{ Number::currency($item->product->final_price * $item->quantity, 'INR') }}</td>
                        </tr>
                    @endforeach

                </tbody>
              </table>

            </div>
          </div>

          <!-- Order summary (sticky sidebar) -->
          <aside class="col-lg-4 order-summary" style="margin-top: -100px">
            <div class="position-sticky top-0" style="padding-top: 100px">
              <div class="bg-body-tertiary rounded-5 p-4 mb-3">
                <div class="p-sm-2 p-lg-0 p-xl-2">
                  <h5 class="border-bottom pb-4 mb-4">Order summary</h5>
                  <ul class="list-unstyled fs-sm gap-3 mb-0">
                    <li class="d-flex justify-content-between">
                      Payment method:
                      <span class="text-dark-emphasis fw-medium subtotal">{{ $order->payment_method == 'cod' ? 'Cash on delivery' : 'Razorpay' }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                      Payment status:
                      <span class="text-dark-emphasis fw-medium subtotal">{{ $order->payment_status }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                      Order status:
                      <span class="text-dark-emphasis fw-medium discount">{{ $order->order_status }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                      Subtotal ({{ count($order->orderItems) }} items):
                      <span class="text-dark-emphasis fw-medium subtotal">{{ Number::currency($order->subtotal, 'INR') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                      Discount:
                      <span class="text-danger fw-medium discount">{{ Number::currency($order->discounted_price, 'INR') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                      Gst tax (18%):
                      <span class="text-dark-emphasis fw-medium gst-amount">{{ Number::currency($order->tax_amount, 'INR') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                      Shipping:
                      <span class="text-dark-emphasis fw-medium">{{ Number::currency($order->shipping_amount, 'INR') }}</span>
                    </li>
                  </ul>
                  <div class="border-top pt-4 mt-4">
                    <div class="d-flex justify-content-between mb-3">
                      <span class="fs-sm">Grand total:</span>
                      <span class="h5 mb-0 grand-total">{{ Number::currency($order->grand_total, 'INR') }}</span>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </aside>
        </div>

      </section>

    </main>
@endsection
@section('script')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <script>
    $(document).ready(function () {
      // razorpay payment
      $('#paymant-button').on('click', function () {

        $.ajax({
          url: '{{ route("razorpay.order") }}',
          method: 'POST',
          data: {
            amount: '{{ $order->grand_total }}',
            order_id: '{{ $order->id }}'
          },
          success: function (response) {
            if (response.success) {
                initiateRazorpay(response);
            }
          },
          error: function (xhr) {
            alert('Error: ' + (xhr.responseJSON?.error || 'Something went wrong'));
          }
        });

      });
    });

    function initiateRazorpay(data) {
      const options = {
        key: data.key,
        amount: data.amount * 100,
        currency: data.currency,
        name: 'MyCart',
        description: 'Order #' + data.order_no,
        order_id: data.razorpay_order_id,
        handler: function (response) {
          verifyPayment(response, data.order_id);
        },
        prefill: {
          name: data.user.name,
          email: data.user.email,
          contact: data.user.contact
        },
        theme: {
          color: '#0d6efd'
        },
        modal: {
          ondismiss: function () {
            messageAlert('Payment cancelled', 'error');
            window.location.href = response.redirect;
          }
        }
      };

      const rzp = new Razorpay(options);
      rzp.open();
    }

    // Verify Payment
    function verifyPayment(response, orderId) {
      $.ajax({
        url: '{{ route("order.verify-payment") }}',
        method: 'POST',
        data: {
          razorpay_payment_id: response.razorpay_payment_id,
          razorpay_order_id: response.razorpay_order_id,
          razorpay_signature: response.razorpay_signature,
          order_id: orderId
        },
        success: function (res) {
          if (res.success) {
            messageAlert(res.message, 'success');
            window.location.reload();
          }
        },
        error: function (xhr) {
          alert('Payment verification failed: ' + (xhr.responseJSON?.error || 'Unknown error'));
        }
      });
    }
  </script>
@endsection