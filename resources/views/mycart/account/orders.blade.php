@extends('mycart.layouts.app')
@section('title', 'My Orders')
@section('style')
    
@endsection
@section('content')
    <!-- Page content -->
    <main class="content-wrapper">
      <div class="container py-5 mt-n2 mt-sm-0">
        <div class="row pt-md-2 pt-lg-3 pb-sm-2 pb-md-3 pb-lg-4 pb-xl-5">
          @include('mycart.account.sidebar')

          <!-- Orders content -->
          <div class="col-lg-9">
            <div class="ps-lg-3 ps-xl-0">

              <!-- Page title + Sorting selects -->
              <div class="row align-items-center pb-3 pb-md-4 mb-md-1 mb-lg-2">
                <div class="col-md-4 col-xl-6 mb-3 mb-md-0">
                  <h1 class="h2 me-3 mb-0">Orders</h1>
                </div>
              </div>


              <!-- Sortable orders table -->
              <div class="table-responsive">
                @if (count($orders) > 0)
                <table class="table align-middle fs-sm text-nowrap">
                  <thead>
                    <tr>
                      <th scope="col" class="py-3 ps-0">
                        <span class="text-body fw-normal">Order <span class="d-none d-md-inline">#</span></span>
                      </th>
                      <th scope="col" class="py-3 d-none d-md-table-cell">
                        <span class="text-body fw-normal">Order date</span>
                      </th>
                      <th scope="col" class="py-3 d-none d-md-table-cell">
                        <span class="text-body fw-normal">Order status</span>
                      </th>
                      <th scope="col" class="py-3 d-none d-md-table-cell">
                        <span class="text-body fw-normal">Paymant status</span>
                      </th>
                      <th scope="col" class="py-3 d-none d-md-table-cell">
                        <span class="text-body fw-norma">Total</span>
                      </th>
                      <th scope="col" class="py-3">&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody class="text-body-emphasis orders-list">

                    @foreach ($orders as $order)  
                        <!-- Item -->
                        <tr>
                          <td class="fw-medium pt-2 pb-3 py-md-2 ps-0">
                            <a class="d-inline-block animate-underline text-body-emphasis text-decoration-none py-2" href="{{ route('order.show', $order->id) }}">
                              <span class="animate-target">{{ $order->order_no }}</span>
                            </a>
                            <ul class="list-unstyled fw-normal text-body m-0 d-md-none">
                              <li>{{ $order->created_at->format('M d, Y') }}</li>
                              <li class="d-flex align-items-center">
                                <span class="bg-info rounded-circle p-1 me-2"></span>
                                {{ ucfirst($order->order_status) }}
                              </li>
                              <li class="fw-medium text-body-emphasis">{{ Number::currency($order->subtotal, 'INR') }}</li>
                            </ul>
                          </td>
                          <td class="fw-medium py-3 d-none d-md-table-cell">
                            {{ $order->created_at->format('M d, Y') }}
                            <span class="date d-none">{{ $order->created_at->format('y-m-d') }}</span>
                          </td>
                          <td class="fw-medium py-3 d-none d-md-table-cell">
                            <span class="d-flex align-items-center">
                              {{ ucfirst($order->order_status) }}
                            </span>
                          </td>
                          <td class="fw-medium py-3 d-none d-md-table-cell">
                            <span class="d-flex align-items-center">
                              {{ ucfirst($order->payment_status) }}
                            </span>
                          </td>
                          <td class="fw-medium py-3 d-none d-md-table-cell">
                            {{ Number::currency($order->grand_total, 'INR') }}
                          </td>
                          <td class="py-3 pe-0">
                            <span class="d-flex align-items-center justify-content-end position-relative gap-1 gap-sm-2 ms-n2 ms-sm-0">
                              @foreach ($order->orderItems->take(3) as $item)
                                <span><img src="{{ asset('images/products/thumb/' . ($item->product->primaryImage->image ?? 'no-image.png')) }}" width="64" alt="Thumbnail"></span>
                              @endforeach
                              @if ($order->orderItems->count() > 3)
                                  <span class="fw-medium me-1">+{{ $order->orderItems->count() - 3 }}</span>
                              @endif
                            </span>
                          </td>
                        </tr>
                    @endforeach
                    
                  </tbody>
                </table>
                @else
                  <div class="text-center py-5">
                    <h2 class="h4 mb-4">You have no orders</h2>
                    <p class="mb-4">Explore our products and add them to your cart.</p>
                    <a class="btn btn-primary" href="{{ route('products.list') }}">Continue Shopping</a>
                  </div>
                @endif
              </div>

              <!-- Pagination -->
              <nav class="pt-3 pb-2 pb-sm-0 mt-2 mt-md-3" aria-label="Page navigation">
                  {{ $orders->links('mycart.pagination.custom') }}
              </nav>
            </div>
          </div>
        </div>
      </div>
    </main>
@endsection

@section('script')

@endsection
