@extends('vendor.layouts.app')
@section('title', 'Order Details')

@section('content')

<div class="card mb-3">
    <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url({{ asset('assets/img/icons/spot-illustrations/corner-4.png') }});opacity:0.7;"></div>

    <div class="card-body position-relative">
        <h5>Order Details: #{{ $order->order_no }}</h5>
        <p class="fs-10">{{ $order->created_at->format('M d, Y, h:i A') }}</p>

        <div>
            <strong class="me-2">Status:</strong>
            @switch($order->order_status)
                @case('pending')
                  <div class="badge rounded-pill badge-subtle-warning fs-11">Pending<span class="fas fa-stream ms-1" data-fa-transform="shrink-2"></span></div>
                  @break

                @case('processing')
                  <div class="badge rounded-pill badge-subtle-primary fs-11">Processing<span class="fas fa-redo ms-1" data-fa-transform="shrink-2"></span></div>
                  @break

                @case('completed')
                  <div class="badge rounded-pill badge-subtle-success fs-11">Completed<span class="fas fa-check ms-1" data-fa-transform="shrink-2"></span></div>
                  @break

                @default
                  <div class="badge rounded-pill badge-subtle-danger fs-11">Cancelled<span class="fas fa-ban ms-1" data-fa-transform="shrink-2"></span></div>
            @endswitch
        </div>
    </div>
</div>


<div class="card mb-3">
    <div class="card-body">
        <div class="row">

            <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                <h5 class="mb-3 fs-9">Customer Information</h5>

                <p class="mb-0 fs-10">
                    <strong>Name:</strong> {{ $order->user->name }}
                </p>

                <p class="mb-0 fs-10">
                    <strong>Email:</strong>
                    <a href="javascript:void(0);">{{ $order->user->email }}</a>
                </p>

                @if ($order->user->phone)
                  <p class="mb-0 fs-10"> <strong>Phone: </strong>{{ $order->user->phone }}</p>
                @endif
            </div>

            <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                <h5 class="mb-3 fs-9">Shipping Address</h5>
                <h6 class="mb-2">{{ $order->orderAddresses->name }}</h6>

                <p class="mb-0 fs-10">
                    {{ $order->orderAddresses->address }}<br>
                    {{ $order->orderAddresses->state->name }},
                    {{ $order->orderAddresses->city->name }}
                    {{ $order->orderAddresses->pincode }}
                </p>

                <div class="text-500 fs-10">(Free Shipping)</div>
            </div>

            <div class="col-md-6 col-lg-4">
                <h5 class="mb-3 fs-9">Payment Method</h5>

                <h6 class="mb-0">
                    {{ $order->payment_method == 'cod' ? 'Cash on delivery' : 'Razorpay' }}
                </h6>

                <p class="mb-0 fs-10">
                    <strong>Payment Status:</strong>

                    @switch($order->payment_status)
                        @case('pending')
                            <span class="text-warning">Pending</span>
                            @break
                        @case('failed')
                            <span class="text-danger">Failed</span>
                            @break
                        @default
                            <span class="text-success">Paid</span>
                    @endswitch
                </p>
            </div>

        </div>
    </div>
</div>


<div class="card mb-3">
    <div class="card-body">

        <div class="table-responsive fs-10">
            <table class="table table-striped border-bottom">
                <thead class="bg-200">
                <tr>
                    <th class="text-900 border-0">Products</th>
                    <th class="text-900 border-0 text-end">Price</th>
                    <th class="text-900 border-0 text-end">Discount</th>
                    <th class="text-900 border-0 text-center">Quantity</th>
                    <th class="text-900 border-0 text-end">Amount</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($items as $item)
                    <tr class="border-200">
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('images/products/medium/' . ($item->product->primaryImage->image ?? 'no-image.png')) }}" class="img-fluid" width="60">

                                <div class="ps-2">
                                    <h6 class="mb-0 text-nowrap">
                                        {{ $item->product->name }}
                                    </h6>
                                    <p class="mb-0">
                                        {{ $item->product->brand->name }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="align-middle text-end">
                            {{ Number::currency($item->product->base_price, 'INR') }}
                        </td>

                        <td class="align-middle text-end">
                            {{ $item->product->discount_percentage ? $item->product->discount_percentage . '%' : '-' }}
                        </td>

                        <td class="align-middle text-center">
                            {{ $item->quantity }}
                        </td>

                        <td class="align-middle text-end">
                            {{ Number::currency($item->product->base_price * $item->quantity, 'INR') }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="row g-0 justify-content-end">
            <div class="col-auto">
                <table class="table table-sm table-borderless fs-10 text-end">

                    <tr>
                        <th class="text-900">Subtotal:</th>
                        <td class="fw-semi-bold">
                            {{ Number::currency($subtotal, 'INR') }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-900">Discount price:</th>
                        <td class="fw-semi-bold">
                            {{ Number::currency($discounted_price, 'INR') }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-900">Tax 18%:</th>
                        <td class="fw-semi-bold">
                            {{ Number::currency($tax, 'INR') }}
                        </td>
                    </tr>

                    <tr class="border-top">
                        <th class="text-900">Total:</th>
                        <td class="fw-semi-bold">
                            {{ Number::currency($grandTotal, 'INR') }}
                        </td>
                    </tr>

                </table>
            </div>
        </div>

    </div>
</div>

@endsection