@extends('admin.layouts.app')
@section('title', 'Order List')

@section('style')

@endsection
@section('content')
    <div class="card mb-3">
      <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url(../../../assets/img/icons/spot-illustrations/corner-4.png);opacity: 0.7;"></div><!--/.bg-holder-->
      <div class="card-body position-relative">
        <h5>Order Details: #{{ $order->order_no }}</h5>
        <p class="fs-10">{{ $order->created_at->format('M d, Y, h:i A') }}</p>
        <div><strong class="me-2">Status: </strong>
          @switch($order->order_status)
              @case('pending')
                  <div class="badge rounded-pill badge-subtle-warning fs-11">{{ ucfirst($order->order_status) }}<span class="fas fa-stream ms-1" data-fa-transform="shrink-2"></span></div>
                  @break
              @case('processing')
                  <div class="badge rounded-pill badge-subtle-primary fs-11">{{ ucfirst($order->order_status) }}<span class="fas fa-redo ms-1" data-fa-transform="shrink-2"></span></div>
                  @break
              @case('completed')
                  <div class="badge rounded-pill badge-subtle-success fs-11">{{ ucfirst($order->order_status) }}<span class="fas fa-check ms-1" data-fa-transform="shrink-2"></span></div>
                  @break
              @default
                  <div class="badge rounded-pill badge-subtle-danger fs-11">{{ ucfirst($order->order_status) }}<span class="fas fa-ban ms-1" data-fa-transform="shrink-2"></span></div>
          @endswitch 
        </div>
      </div>
    </div>
    <div class="card mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
            <h5 class="mb-3 fs-9">Customer Information</h5>
            <div class="avatar avatar-3xl">
              @if ($order->user->image)
                <img class="rounded-circle" src="{{ asset('images/users/' . $order->user->image) }}" alt="" />
              @else 
                <div class="avatar-name rounded-circle"><span>{{ substr($order->user->name, 0, 1) }}</span></div>
              @endif
            </div>
            <p class="mb-0 fs-10"> <strong>Name: </strong> {{ $order->user->name }}</p>
            <p class="mb-0 fs-10"> <strong>Email: </strong><a href="javascript:void(0);">{{ $order->user->email }}</a></p>
            @if ($order->user->fhone)
              <p class="mb-0 fs-10"> <strong>Phone: </strong><a href="javascript:void(0);">{{ $order->user->phone }}</a></p>
            @endif
          </div>
          <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
            <h5 class="mb-3 fs-9">Shipping Address</h5>
            <h6 class="mb-2">{{ $order->orderAddresses->name }}</h6>
            <p class="mb-0 fs-10">{{ $order->orderAddresses->address }}<br />{{ $order->orderAddresses->state->name . ', ' . $order->orderAddresses->city->name . ' ' . $order->orderAddresses->pincode }}</p>
            <div class="text-500 fs-10">(Free Shipping)</div>
          </div>
          <div class="col-md-6 col-lg-4">
            <h5 class="mb-3 fs-9">Payment Method</h5>
              <div class="flex-1">
                <h6 class="mb-0">{{ $order->payment_method == 'cod' ? 'Cash on delivery' : 'Razorpay' }}</h6>
                <p class="mb-0 fs-10"> <strong>Payment Status: </strong> 
                  @switch($order->payment_status)
                      @case('panding')
                          <span class="text-warning">{{ ucfirst($order->payment_status) }}</span>
                          @break
                      @case('failed')
                          <span class="text-danger">{{ ucfirst($order->payment_status) }}</span>
                          @break
                      @default
                          <span class="text-success">{{ ucfirst($order->payment_status) }}</span>
                  @endswitch
                </p>
              </div>
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
                <th class="text-900 border-0 text-center">Quantity</th>
                <th class="text-900 border-0 text-end">Rate</th>
                <th class="text-900 border-0 text-end">Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($order->orderItems as $item) 
                <tr class="border-200">
                  <td class="align-middle">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/products/medium/' . ($item->product->primaryImage->image ?? 'no-image.png')) }}" alt="{{ $item->product->name }}" class="img-fluid" width="60">
                        <div class="ps-2">
                          <h6 class="mb-0 text-nowrap">{{ $item->product->name }}</h6>
                          <p class="mb-0">{{ $item->product->brand->name }}</p>
                        </div>
                    </div>
                  </td>
                  <td class="align-middle text-center">{{ $item->quantity }}</td>
                  <td class="align-middle text-end">{{ Number::currency($item->product->final_price, 'INR') }}</td>
                  <td class="align-middle text-end">{{ Number::currency($item->product->final_price * $item->quantity, 'INR') }}</td>
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
                <td class="fw-semi-bold">{{ Number::currency($order->subtotal, 'INR') }}</td>
              </tr>
              <tr>
                <th class="text-900">Tax 18%:</th>
                <td class="fw-semi-bold">{{ Number::currency($order->tax_amount, 'INR') }}</td>
              </tr>
              <tr class="border-top">
                <th class="text-900">Total:</th>
                <td class="fw-semi-bold">{{ Number::currency($order->grand_total, 'INR') }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    {{-- <div class="card mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col-6 d-flex">
            <div class="d-flex align-items-end g-3">
              <select class="form-select me-2" name="order_status" id="order_status" aria-label="Default select example">
                <option value="panding" {{ $order->order_status == 'panding' ? 'selected' : '' }} >Panding</option>
                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
              </select>
              <select class="form-select" name="paymant_status" id="paymant_status" aria-label="Default select example">
                <option value="panding" {{ $order->payment_status == 'panding' ? 'selected' : '' }}>Panding</option>
                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div> --}}
@endsection
@section('script')
{{-- <script>
  $(document).ready(function () {
    $('#order_status').on('change', function () {
      let status = $(this).val();
      let orderId = "{{ $order->id }}";
      $.ajax({
        url: "{{ route('admin.order.update', $order->id) }}",
        type: "PUT",
        data: {
          'order_status': status
        },
        success: function (response) {
          if (response.status === 'success') {
            window.location.reload();
          }
        },
        error: function (xhr, status, error) {
          console.log(error);
        }
      });
    });
    $('#paymant_status').on('change', function () {
      let status = $(this).val();
      let orderId = "{{ $order->id }}";
      $.ajax({
        url: "{{ route('admin.order.update', $order->id) }}",
        type: "PUT",
        data: {
          'paymant_status': status
        },
        success: function (response) {
          if (response.status === 'success') {
            window.location.reload();
          }
        },
        error: function (xhr, status, error) {
          console.log(error);
        }
      });
    });
  });
</script> --}}
@endsection