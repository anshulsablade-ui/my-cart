@extends('mycart.layouts.app')

@section('title', 'Order Success')

@section('content')
<main class="content-wrapper">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7 d-flex flex-column justify-content-center py-5 px-xl-4 px-xxl-5">

      <div class="px-sm-4 px-md-5">

        {{-- Header --}}
        <div class="d-flex align-items-sm-center border-bottom pb-4 pb-md-5 mb-4">
          <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle flex-shrink-0"
               style="width: 3rem; height: 3rem;">
            <i class="ci-check fs-4"></i>
          </div>

          <div class="w-100 ps-3">
            <div class="fs-sm text-muted mb-1">
              Order #{{ $order->order_no }}
            </div>
            <h1 class="h4 mb-0">Thank you for your order!</h1>
          </div>
        </div>

        {{-- Order Info --}}
        <div class="d-flex flex-column gap-4">

          {{-- Delivery --}}
          <div>
            <h3 class="h6 mb-2">Delivery Address</h3>
            <p class="fs-sm mb-0">
              Address name: {{ $order->orderAddresses->name }} <br>
              {{ $order->orderAddresses->address }} <br>
              {{ optional($order->orderAddresses->city)->name }},
              {{ optional($order->orderAddresses->state)->name }} -
              {{ $order->orderAddresses->pincode }} <br>
              Phone: {{ $order->orderAddresses->phone }}
            </p>
          </div>

          {{-- Payment --}}
          <div>
            <h3 class="h6 mb-2">Payment</h3>
            <p class="fs-sm mb-0 text-capitalize">
              Method: {{ $order->payment_method == 'cod' ? 'Cash on delivery' : 'Razorpay' }} <br>
              Status:
              <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                {{ $order->payment_status }}
              </span>
            </p>
          </div>

          {{-- Order Summary --}}
          <div>
            <h3 class="h6 mb-2">Order Summary</h3>
            <ul class="list-unstyled fs-sm mb-0">
              <li class="d-flex justify-content-between">
                <span>Subtotal</span>
                <span>₹{{ number_format($order->subtotal, 2) }}</span>
              </li>
              <li class="d-flex justify-content-between">
                <span>Discount</span>
                <span>- ₹{{ number_format($order->discounted_price, 2) }}</span>
              </li>
              <li class="d-flex justify-content-between">
                <span>GST (18%)</span>
                <span>₹{{ number_format($order->tax_amount, 2) }}</span>
              </li>
              <li class="d-flex justify-content-between fw-semibold border-top pt-2 mt-2">
                <span>Total</span>
                <span>₹{{ number_format($order->grand_total, 2) }}</span>
              </li>
            </ul>
          </div>

          {{-- Actions --}}
          <div class="d-flex gap-3 mt-3">
            <a href="{{ route('order.show', $order->id) }}" class="btn btn-primary">
              View Order
            </a>
            <a href="{{ route('products.list') }}" class="btn btn-outline-secondary">
              Continue Shopping
            </a>
          </div>

        </div>
      </div>
    </div>
  </div>
</main>
@endsection
