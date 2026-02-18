@extends('admin.layouts.app')
@section('title', 'Customer Details')

@section('style')

@endsection
@section('content')
    <div class="card mb-3">
      <div class="card-header">
        <div class="row">
          <div class="col">
            <h5 class="mb-2">{{ $user->name }} (<a href="mailto:{{ $user->email }}">{{ $user->email }}</a>)</h5>
          </div>
          <div class="col-auto d-none d-sm-block">
            <h6 class="text-uppercase text-600">{{ $user->role }}<span class="fas fa-user ms-2"></span></h6>
          </div>
        </div>
      </div>
      <div class="card-body border-top">
        <div class="d-flex"><span class="fas fa-user text-success me-2" data-fa-transform="down-5"></span>
          <div class="flex-1">
            <p class="mb-0">Customer was created</p>
            <p class="fs-10 mb-0 text-600">{{ $user->created_at->format('M d, h:i A') }}</p>
          </div>
        </div>
      </div>
    </div>
    <div class="card mb-3">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col">
            <h5 class="mb-0">User Wishlist</h5>
          </div>
        </div>
      </div>
      <div class="card-body bg-body-tertiary border-top">
        <div class="row">

          @foreach ($wishlists as $item)
            <div class="col-4 col-xxl-5">
              <div class="row">
                <div class="col-sm-5 col-md-4">
                  <div class="position-relative h-sm-100">
                      <img src="{{ asset('images/products/medium/' . ($item->product->primaryImage->image ?? 'no-image.png')) }}" alt="{{ $item->product->name }}" class="img-fluid" width="120">
                  </div>
                </div>
                <div class="col-sm-7 col-md-8">
                      <h5 class="mt-3 mt-sm-0">{{ $item->product->name }}</h5>
                      <div>
                        <h4 class="fs-9 text-warning mb-0">{{ Number::currency($item->product->final_price, 'INR') }}</h4>
                        @if ($item->product->base_price != $item->product->final_price)
                          <h5 class="fs-10 text-500 mb-0 mt-1"><del>{{ Number::currency($item->product->base_price, 'INR') }} </del><span class="ms-1 text-success">-{{ $item->product->discount_percentage }}%</span></h5>
                        @endif
                        <div class="d-none d-lg-block">
                          <p class="fs-10 mb-1">Stock: <strong class="text-success">{{ $item->product->stock }}</strong></p>
                        </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
          @if (empty($wishlists))
          <div class="d-flex justify-content-center">
            <p class="mb-3">wishlist is empty.</p>
          </div>
          @endif

        </div>
      </div>
    </div>
    <div class="card mb-3">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col">
            <h5 class="mb-0">User Cart</h5>
          </div>
        </div>
      </div>
      <div class="card-body bg-body-tertiary border-top">
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
              @foreach ($cartItems as $item) 
                <tr class="border-200">
                  <td class="align-middle">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/products/medium/' . ($item->product->primaryImage->image ?? 'no-image.png')) }}" alt="{{ $item->product->name }}" class="img-fluid" width="60">
                        <div class="ps-2">
                          <h6 class="mb-1 text-nowrap">{{ $item->product->name }}</h6>
                          <p class="mb-0">{{ $item->product->final_price }}</p>
                          @if ($item->product->base_price != $item->product->final_price)
                            <h5 class="fs-10 text-500 mb-0 mt-1"><del>{{ Number::currency($item->product->base_price, 'INR') }} </del><span class="ms-1 text-success">-{{ $item->product->discount_percentage }}%</span></h5>
                          @endif
                        </div>
                    </div>
                  </td>
                  <td class="align-middle text-center">{{ $item->quantity }}</td>
                  <td class="align-middle text-end">{{ Number::currency($item->product->final_price, 'INR') }}</td>
                  <td class="align-middle text-end">
                    {{ Number::currency($item->product->final_price * $item->quantity, 'INR') }}
                    @if ($item->product->base_price != $item->product->final_price)
                      <h5 class="fs-10 text-500 mb-0 mt-1"><del>{{ Number::currency($item->product->base_price * $item->quantity, 'INR') }} </del></h5>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          @if ($cartItems->isEmpty())
            <div class="d-flex justify-content-center">
              <p class="mb-3">cart is empty.</p>
            </div>
          @endif
        </div>
      </div>
    </div>
    <div class="card mb-3">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col">
            <h5 class="mb-0">User Orders</h5>
          </div>
        </div>
      </div>
      <div class="card-body bg-body-tertiary border-top">
        <div class="table-responsive fs-10">
          <table class="table table-striped border-bottom">
            <thead class="bg-200">
              <tr>
                <th class="text-900 border-0">Order number</th>
                <th class="text-900 border-0 text-center">Date</th>
                <th class="text-900 border-0 text-end">Items</th>
                <th class="text-900 border-0 text-center">Payment</th>
                <th class="text-900 border-0 text-center">Status</th>
                <th class="text-900 border-0 text-end">Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($orders as $item) 
                <tr class="border-200">
                  <td class="align-middle"><a href="{{ route('admin.order.show', $item->id) }}">#{{ $item->order_no }}</a></td>
                  <td class="align-middle text-center">{{ $item->created_at->format('d-m-Y') }}</td>
                  <td class="align-middle text-end">{{ $item->orderItems->count() }}</td>
                  <td class="align-middle text-center">
                    @switch($item->payment_status)
                        @case('pending')
                            <span class="badge badge rounded-pill d-block badge-subtle-warning">Pending<span class="ms-1 fas fa-stream" data-fa-transform="shrink-2"></span></span>
                            @break
                        @case('paid')
                            <span class="badge badge rounded-pill d-block badge-subtle-success">Paid<span class="ms-1 fas fa-check" data-fa-transform="shrink-2"></span></span>
                            @break
                        @case('failed')
                            <span class="badge badge rounded-pill d-block badge-subtle-danger">Failed<span class="ms-1 fas fa-ban" data-fa-transform="shrink-2"></span></span>
                            @break
                    @endswitch
                  </td>
                  <td class="align-middle text-center">
                    @switch($item->order_status)
                        @case('pending')
                            <span class="badge badge rounded-pill d-block badge-subtle-warning">Pending<span class="ms-1 fas fa-stream" data-fa-transform="shrink-2"></span></span>
                            @break
                        @case('processing')
                            <span class="badge badge rounded-pill d-block badge-subtle-primary">Processing<span class="ms-1 fas fa-redo" data-fa-transform="shrink-2"></span></span>
                            @break
                        @case('completed')
                            <span class="badge badge rounded-pill d-block badge-subtle-success">Completed<span class="ms-1 fas fa-check" data-fa-transform="shrink-2"></span></span>
                            @break
                        @case('cancelled')
                            <span class="badge badge rounded-pill d-block badge-subtle-danger">Cancelled<span class="ms-1 fas fa-ban" data-fa-transform="shrink-2"></span></span>
                            @break
                    @endswitch
                  </td>
                  <td class="align-middle text-end">
                    {{ Number::currency($item->grand_total, 'INR') }}
                  </td>
                </tr>
              @endforeach
              @if (empty($orders))
                <div class="d-flex justify-content-center">
                  <p class="mb-3">Orders is empty.</p>
                </div>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
@endsection
@section('script')
<script>
  $(document).ready(function () {

  });
</script>
@endsection