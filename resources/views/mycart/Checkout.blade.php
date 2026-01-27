@extends('mycart.layouts.app')
@section('title', 'Checkout')
@section('style')

@endsection
@section('content')
    <!-- Page content -->
    <main class="content-wrapper">
      <div class="container py-5">
        <div class="row pt-1 pt-sm-3 pt-lg-4 pb-2 pb-md-3 pb-lg-4 pb-xl-5">
          <div class="col-lg-8 col-xl-7 mb-5 mb-lg-0">
            {{-- <form id="checkoutForm"> --}}
            <div class="accordion d-flex flex-column gap-5 pe-lg-4 pe-xl-0" id="checkout">

                <!-- Shipping address form -->
                <div class="d-flex align-items-start">
                  <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle fs-sm fw-semibold lh-1 flex-shrink-0" style="width: 2rem; height: 2rem; margin-top: -.125rem">1</div>
                  <div class="w-100 ps-3 ps-md-4">
                    <h1 class="h5 mb-md-4">Shipping address</h1>
                      <div class="row">
                          <div class="col">
                            @foreach ($addresses as $address)
                              <div class="form-check border-bottom pb-2">
                                <input type="radio" class="form-check-input" name="shipping_address" value="{{ $address->id }}" id="address_{{ $address->id }}">
                                <label class="form-check-label w-100">
                                    <div>
                                      <div class="nav flex-nowrap align-items-center justify-content-between pb-1 mb-3">
                                        <div class="d-flex align-items-center gap-3 me-4">
                                          <h2 class="h6 mb-0">{{ $address->name }}</h2>
                                            @if ($address->is_primary == 1)
                                                <span class="badge text-bg-info rounded-pill">Primary</span>
                                            @endif
                                        </div>
                                        <a class="nav-link text-decoration-underline p-0 address-edit" href="{{ route('address.edit', $address->id) }}">Edit</a>
                                      </div>
                                      <div class="primary-address">
                                        <ul class="list-unstyled fs-sm m-0">
                                          <li>{{ $address->state->name . ' ' . $address->pincode . ', ' . $address->country->name }}</li>
                                          <li>{{ $address->address }}</li>
                                        </ul>
                                      </div>
                                    </div>
                                </label>

                              </div>
                            @endforeach                
                          </div>
                      </div>
                      <div class="nav">
                        <a class="nav-link px-0" href="#addressModal" data-bs-toggle="modal">
                          Add address line
                          <i class="ci-plus fs-base ms-1"></i>
                        </a>
                      </div>
                  </div>
                </div>
  
                <!-- Payment method -->
                <div class="d-flex align-items-start">
                  <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle fs-sm fw-semibold lh-1 flex-shrink-0" style="width: 2rem; height: 2rem; margin-top: -.125rem">2</div>
                  <div class="w-100 ps-3 ps-md-4">
                    <h2 class="h5 mb-0">Payment</h2>
                    <div class="mb-4" id="paymentMethod" role="list">
  
                      <!-- Cash on delivery -->
                      <div class="mt-4">
                        <div class="form-check mb-0" role="listitem" data-bs-toggle="collapse" data-bs-target="#cash" aria-expanded="false" aria-controls="cash">
                          <label class="form-check-label w-100 text-dark-emphasis fw-semibold">
                            <input type="radio" class="form-check-input fs-base me-2 me-sm-3" value="cod" name="payment_method">
                            Cash on delivery
                          </label>
                        </div>
                        <div class="collapse" id="cash" data-bs-parent="#paymentMethod"></div>
                      </div>
  
                      <!-- Razorpay -->
                      <div class="mt-4">
                        <div class="form-check mb-0" role="listitem" data-bs-toggle="collapse" data-bs-target="#razorpay" aria-expanded="false" aria-controls="razorpay">
                          <label class="form-check-label d-flex align-items-center text-dark-emphasis fw-semibold">
                            <input type="radio" class="form-check-input fs-base me-2 me-sm-3" name="payment_method" value="razorpay">
                            Razorpay
                            <img src="{{ asset('web/assets/img/payment-methods/razorpay.png') }}" class="ms-3" width="20" alt="razorpay">
                          </label>
                        </div>
                        <div class="collapse" id="razorpay" data-bs-parent="#paymentMethod"></div>
                      </div>

                    </div>
  
                    <!-- Additional comments -->
                    <textarea class="form-control form-control-lg mb-4" id="notes" name="notes" rows="3" placeholder="Additional comments"></textarea>
                    <button class="btn btn-lg btn-primary w-100 d-lg-flex payment-button" id="place-order-btn">Pay {{ Number::currency($grandTotal, 'INR') }}</button>
                  </div>
                </div>
                
              </div>
          </div>

          <!-- Order summary (sticky sidebar) -->
          <aside class="col-lg-4 offset-xl-1" style="margin-top: -100px">
            <div class="position-sticky top-0" style="padding-top: 100px">
              <div class="bg-body-tertiary rounded-5 p-4 mb-3">
                <div class="p-sm-2 p-lg-0 p-xl-2">
                  <div class="border-bottom pb-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                      <h5 class="mb-0">Order summary</h5>
                      <div class="nav">
                        <a class="nav-link text-decoration-underline p-0" href="{{ route('cart.index') }}">Edit</a>
                      </div>
                    </div>
                    <a class="d-flex align-items-center gap-2 text-decoration-none" href="#orderPreview" data-bs-toggle="offcanvas">
                        @foreach ($cartItems as $item)
                            <div class="ratio ratio-1x1" style="max-width: 64px">
                              <img src="{{ asset('images/products/thumb/' . ($item->product->primaryImage->image ?? 'no-image.png')) }}" class="d-block p-1" alt="iPhone">
                            </div>
                        @endforeach
                      
                      <i class="ci-chevron-right text-body fs-xl p-0 ms-auto"></i>
                    </a>
                  </div>
                  <ul class="list-unstyled fs-sm gap-3 mb-0">
                    <li class="d-flex justify-content-between">
                      Subtotal (3 items):
                      <span class="text-dark-emphasis fw-medium">{{ Number::currency($subtotal, 'INR') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                      Discount:
                      <span class="text-danger fw-medium">{{ Number::currency($discounted_price, 'INR') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                      Gst tax (18%):
                      <span class="text-dark-emphasis fw-medium">{{ Number::currency($gstAmount, 'INR') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                      Shipping:
                      <span class="text-dark-emphasis fw-medium">Free</span>
                    </li>
                  </ul>
                  <div class="border-top pt-4 mt-4">
                    <div class="d-flex justify-content-between mb-3">
                      <span class="fs-sm">Grand total:</span>
                      <span class="h5 mb-0">{{ Number::currency($grandTotal, 'INR') }}</span>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </aside>
        </div>
      </div>
    </main>

    <!-- Add new address modal -->
    <div class="modal fade" id="addressModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addressModalLabel">Add new address</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="addressForm" class="row g-3 g-lg-4">
              <input type="hidden" id="address_id" name="address_id" value="">
              <div class="col-lg-6">
                <div class="position-relative">
                  <label for="address_name" class="form-label">Address name</label>
                  <input type="text" class="form-control" id="address_name" name="address_name" >
                </div>
              </div>
              <div class="col-lg-6">
                <div class="position-relative">
                  <label for="address_type" class="form-label">Address type</label>
                  <select class="form-select" id="address_type" name="address_type" aria-label="Select address type">
                    <option value="">Select address type...</option>
                    <option value="home">Home</option>
                    <option value="office">office</option>
                    <option value="shipping">Shipping</option>
                    <option value="billing">Billing</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="position-relative">
                  <label for="address" class="form-label">Address</label>
                  <input type="text" class="form-control" id="address" name="address" >
                </div>
              </div>
              <div class="col-lg-6">
                <div class="position-relative">
                  <label for="mobile_number" class="form-label">Mobile number</label>
                  <input type="text" class="form-control" id="mobile_number" name="mobile_number" >
                </div>
              </div>
              <div class="col-lg-6">
                <div class="position-relative">
                  <label class="form-label">Country</label>
                  <select class="form-select" id="country" name="country" aria-label="Select country" >
                    <option value="">Select country...</option>
                    @foreach ($countries as $country)
                      <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="position-relative">
                  <label class="form-label">State</label>
                  <select class="form-select" id="state" name="state" aria-label="Select state" >
                    <option value="">Select state...</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="position-relative">
                  <label class="form-label">City</label>
                  <select class="form-select" id="city" name="city" aria-label="Select city" >
                    <option value="">Select city...</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="position-relative">
                  <label for="pincode" class="form-label">Pin code</label>
                  <input type="text" class="form-control" id="pincode" name="pincode" >
                </div>
              </div>
              <div class="col-12">
                <div class="form-check mb-0">
                  <input type="checkbox" class="form-check-input" id="is_primary" name="is_primary" value="1">
                  <label for="is_primary" class="form-check-label">Set as primary address</label>
                </div>
              </div>
              <div class="col-12">
                <div class="d-flex gap-3 pt-2 pt-sm-0">
                  <button type="submit" class="btn btn-primary" id="address-submit">Add address</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

@endsection
@section('script')
      <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

  <script>
      $(document).ready(function () {

        // Reset form on modal close
        $('#addressModal').on('hidden.bs.modal', function () {
          $('#addressForm')[0].reset();
          $('.is-invalid').removeClass('is-invalid');
          $('.invalid-feedback').remove();
          $('#address_id').val('');
          $("#address-submit").text("Add address");
        });

        // Submit address form
        $("#addressForm").submit(function (e) {
          e.preventDefault();

          let addressId = $('#address_id').val();
          let url = addressId ? `/home/address/${addressId}` : "{{ route('address.store') }}";

          let formData = new FormData(this);
          if (addressId) {
              formData.append('_method', 'PUT'); 
          }

          ajaxCall(url, 'POST', formData, function (response) {
            if (response.status === 'success') {
              window.location.reload();
            }
          }, function (response) {
            let res = JSON.parse(response.responseText);
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $.each(res.message, function (key, value) {
              $('#' + key).addClass('is-invalid')
                .after(`<div class="invalid-feedback">${value}</div>`);
            });
          });
        });

        // Edit address
        $('body').on('click', '.address-edit', function (e) {
          e.preventDefault();

          let url = $(this).attr('href');

          $.get(url, function (response) {
            if (response.status === 'success') {
              let data = response.data;

              $('#address_id').val(data.id);
              $('#address_name').val(data.name);
              $('#address_type').val(data.address_type);
              $('#address').val(data.address);
              $('#mobile_number').val(data.phone);
              $('#pincode').val(data.pincode);
              $('#is_primary').prop('checked', data.is_primary);

              // Country
              $('#country').val(data.country_id);

              // Load states → then set state
              getStates(data.country_id, function () {
                $('#state').val(data.state_id);

                // Load cities → then set city
                getCities(data.state_id, function () {
                  $('#city').val(data.city_id);
                });
              });
              $("#address-submit").text("Update Address");

              $('#addressModal').modal('show');
            } else {
              messageAlert(response.message, "info");
            }
          });
        });

        // Change handlers
        $('#country').on('change', function () {
          getStates($(this).val());
        });

        $('#state').on('change', function () {
          getCities($(this).val());
        });

        // Load states
        function getStates(country_id, callback = null) {
          if (!country_id) {
            $('#state').html('<option value="">Select State...</option>');
            return;
          }

          ajaxCall('/getStates/' + country_id, 'GET', null, function (response) {
            let html = '<option value="">Select State...</option>';
            $.each(response, function (_, value) {
              html += `<option value="${value.id}">${value.name}</option>`;
            });
            $('#state').html(html);

            if (callback) callback();
          });
        }

        // Load cities
        function getCities(state_id, callback = null) {
          if (!state_id) {
            $('#city').html('<option value="">Select City...</option>');
            return;
          }

          ajaxCall('/getCities/' + state_id, 'GET', null, function (response) {
            let html = '<option value="">Select City...</option>';
            $.each(response, function (_, value) {
              html += `<option value="${value.id}">${value.name}</option>`;
            });
            $('#city').html(html);

            if (callback) callback();
          });
        }


        $('input[name="payment_method"]').on('change', function () {
            let paymentMethod = $(this).val();

            if (paymentMethod === 'cod') {
                $("#place-order-btn").text("Place Order");
            } else {
                $("#place-order-btn").text("Pay {{ Number::currency($grandTotal, 'INR') }}");
            }
        });

      });
  </script>
  <script>
    // Place Order
    $('#place-order-btn').on('click', function () {
      const shippingAddress = $('input[name="shipping_address"]:checked').val();
      const paymentMethod = $('input[name="payment_method"]:checked').val();

      if (!shippingAddress) {
        alert('Please select a shipping address');
        return;
      }

      $.ajax({
        url: '{{ route("order.process") }}',
        method: 'POST',
        data: {
          shipping_address_id: shippingAddress,
          payment_method: paymentMethod,
        },
        success: function (response) {
          if (response.success) {
            if (paymentMethod === 'cod') {
              window.location.href = response.redirect;
            } else {
              // Razorpay payment
              initiateRazorpay(response);
            }
          }
        },
        error: function (xhr) {
          alert('Error: ' + (xhr.responseJSON?.error || 'Something went wrong'));
          $('#place-order-btn').prop('disabled', false).text('Place Order');
        }
      });
    });

    // Razorpay Integration
    function initiateRazorpay(data) {
      const options = {
        key: data.key,
        amount: data.amount * 100,
        currency: data.currency,
        name: 'Your Store Name',
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
            $('#place-order-btn').prop('disabled', false).text('Place Order');
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
            window.location.href = res.redirect;
          }
        },
        error: function (xhr) {
          alert('Payment verification failed: ' + (xhr.responseJSON?.error || 'Unknown error'));
          $('#place-order-btn').prop('disabled', false).text('Place Order');
        }
      });
    }
  </script>
@endsection
