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
            <form id="checkoutForm">
            <div class="accordion d-flex flex-column gap-5 pe-lg-4 pe-xl-0" id="checkout">

                <!-- Shipping address form -->
                <div class="d-flex align-items-start">
                  <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle fs-sm fw-semibold lh-1 flex-shrink-0" style="width: 2rem; height: 2rem; margin-top: -.125rem">1</div>
                  <div class="w-100 ps-3 ps-md-4">
                    <h1 class="h5 mb-md-4">Shipping address</h1>
                      <div class="row">
                          <div class="col">
                            @foreach ($addresses as $address)
                              <div class="form-check border-bottom">
                                <input type="radio" class="form-check-input" name="address_id" value="{{ $address->id }}" id="address_{{ $address->id }}" {{ $address->is_primary == 1 ? 'checked' : '' }}>
                                <label class="form-check-label w-100">
                                  
                                    <div class="">
                                      <div class="nav flex-nowrap align-items-center justify-content-between pb-1 mb-3">
                                        <div class="d-flex align-items-center gap-3 me-4">
                                          <h2 class="h6 mb-0">{{ $address->name }}</h2>
                                          @if ($address->is_primary == 1)
                                              <span class="badge text-bg-info rounded-pill">Primary</span>
                                          @endif
                                        </div>
                                        
                                      </div>
                                      <div class="collapse primary-address show" id="primaryAddressPreview">
                                        <ul class="list-unstyled fs-sm m-0">
                                          <li>{{ $address->state->name . ' ' . $address->pincode . ', ' . $address->country->name }}</li>
                                          <li>{{ $address->address }}</li>
                                        </ul>
                                      </div>
                                    </div>
                                </label>
                                <a class="nav-link hiding-collapse-toggle text-decoration-underline p-0 collapsed address-edit" href="{{ route('address.edit', $address->id) }}">Edit</a>
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
  
                      <!-- Credit card -->
                      <div class="mt-4">
                        <div class="form-check mb-0" role="listitem" data-bs-toggle="collapse" data-bs-target="#card" aria-expanded="true" aria-controls="card">
                          <label class="form-check-label d-flex align-items-center text-dark-emphasis fw-semibold">
                            <input type="radio" class="form-check-input fs-base me-2 me-sm-3" name="payment_method" checked="">
                            Credit or debit card
                          </label>
                        </div>
                        <div class="ps-4 pt-4 collapse show" id="card" data-bs-parent="#paymentMethod">
                            {{-- <div id="card-element" class="form-control p-2"></div>
                            <small id="card-errors" class="text-danger"></small> --}}
                          {{-- <form class="needs-validation pt-4 pb-2 ps-3 ms-2 ms-sm-3" novalidate="">
                            <div class="position-relative mb-3 mb-sm-4" data-input-format="{&quot;creditCard&quot;: true}">
                              <input type="text" class="form-control form-control-lg form-icon-end" placeholder="Card number" required="">
                              <span class="position-absolute d-flex top-50 end-0 translate-middle-y fs-5 text-body-tertiary me-3" data-card-icon=""></span>
                            </div>
                            <div class="row row-cols-1 row-cols-sm-2 g-3 g-sm-4">
                              <div class="col">
                                <input type="text" class="form-control form-control-lg" data-input-format="{&quot;date&quot;: true, &quot;datePattern&quot;: [&quot;m&quot;, &quot;y&quot;]}" placeholder="MM/YY">
                              </div>
                              <div class="col">
                                <input type="text" class="form-control form-control-lg" maxlength="4" data-input-format="{&quot;numeral&quot;: true, &quot;numeralPositiveOnly&quot;: true, &quot;numeralThousandsGroupStyle&quot;: &quot;none&quot;}" placeholder="CVC">
                              </div>
                            </div>
                          </form> --}}
                          <form id="payment-form" class="pt-4 pb-2 ps-3 ms-2 ms-sm-3">
                              @csrf
                              <div class="mb-3">
                                  <label class="form-label">Card Number</label>
                                  <div id="card-number" class="form-control form-control-lg"></div>
                              </div>
                              <div class="row g-3">
                                  <div class="col">
                                      <label class="form-label">Expiry</label>
                                      <div id="card-expiry" class="form-control form-control-lg"></div>
                                  </div>
                                  <div class="col">
                                      <label class="form-label">CVC</label>
                                      <div id="card-cvc" class="form-control form-control-lg"></div>
                                  </div>
                              </div>
                              <div id="card-errors" class="text-danger mt-2"></div>
                          </form>

                        </div>
                      </div>
  
                      <!-- Razorpay -->
                      <div class="mt-4">
                        <div class="form-check mb-0" role="listitem" data-bs-toggle="collapse" data-bs-target="#razorpay" aria-expanded="false" aria-controls="razorpay">
                          <label class="form-check-label d-flex align-items-center text-dark-emphasis fw-semibold">
                            <input type="radio" class="form-check-input fs-base me-2 me-sm-3" name="payment_method">
                            Razorpay
                            <img src="{{ asset('web/assets/img/payment-methods/razorpay.png') }}" class="ms-3" width="20" alt="razorpay">
                          </label>
                        </div>
                        <div class="collapse" id="razorpay" data-bs-parent="#paymentMethod"></div>
                      </div>

                    </div>
  
                    <!-- Additional comments -->
                    <textarea class="form-control form-control-lg mb-4" name="notes" rows="3" placeholder="Additional comments"></textarea>
  
                  </div>
                </div>
                
              </div>
              <!-- Pay button visible on screens > 991px wide (lg breakpoint) -->
              <div class="">
                <button class="btn btn-lg btn-primary w-100 d-lg-flex payment-button" id="payBtn">Pay {{ Number::currency($grandTotal, 'INR') }}</button>
              </div>
              <div class="d-none">
                <button class="btn btn-lg btn-primary w-100 d-lg-flex order-button" >Order Place</button>
              </div>
            </form>
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
                              <img src="{{ asset('images/products/thumb/' . $item->product->primaryImage->image) }}" class="d-block p-1" alt="iPhone">
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
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>'
    <script src="https://js.stripe.com/v3/"></script>

<script>
    $(document).ready(function () {

      // Order Place
      $(".order-button").click(function (e) { 
        e.preventDefault();
        var address_id = $('input[name="address_id"]:checked').val();
        var payment_method = $('input[name="payment_method"]:checked').val();
        var notes = $('textarea[name="notes"]').val();
        $.ajax({
          type: "post",
          url: "{{ route('checkout.placeOrder') }}",
          data: {
            address_id: address_id,
            payment_method: payment_method,
            notes: notes
          },
          success: function (response) {
            if (response.status == 'success') {
              window.location.href = `/checkout/success/${response.order_id}`;
            }
          }
        });        
      });

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


      $(".order-button").hide();
      $('input[name="payment_method"]').on('change', function () { 
        let paymentMethod = $(this).val();
        if (paymentMethod == 'cod') {
          console.log(paymentMethod);
          $(".order-button > div").removeClass('d-none');
          $(".payment-button > div").addClass('d-none');
        } else {
          $(".payment-button > div").removeClass('d-none');
          $(".order-button > div").addClass('d-none');
        }
      });

      $('input[name="payment_method"]').on('change', function () {
          let paymentMethod = $(this).val();

          if (paymentMethod === 'cod') {
              $(".order-button").removeClass("d-none");
              $(".payment-button").addClass("d-none");
          } else {
              $(".payment-button").removeClass("d-none");
              $(".order-button").addClass("d-none");
          }
      });




    // Peyment script --------------

      // stripe payment
      // const stripe = Stripe("{{ config('services.stripe.key') }}");
      // const elements = stripe.elements();
      // const card = elements.create('card');

      // card.mount('#card-element');


    });
</script>


<script>
$(document).ready(function () {

    const stripe = Stripe("{{ config('services.stripe.key') }}");
    const elements = stripe.elements();

    const style = {
        base: {
            fontSize: '16px',
            color: '#32325d',
        }
    };

    const cardNumber = elements.create('cardNumber', { style });
    const cardExpiry = elements.create('cardExpiry', { style });
    const cardCvc = elements.create('cardCvc', { style });

    cardNumber.mount('#card-number');
    cardExpiry.mount('#card-expiry');
    cardCvc.mount('#card-cvc');

    // PAY BUTTON CLICK (Stripe)
    $('#payNow').on('click', async function () {

        let paymentMethod = $('input[name="payment_method"]:checked').val();
        if (paymentMethod !== 'stripe') return;

        let address_id = $('input[name="address_id"]:checked').val();
        let notes = $('textarea[name="notes"]').val();

        if (!address_id) {
            alert('Please select shipping address');
            return;
        }

        $('#payNow').addClass('disabled').text('Processing...');

        const { paymentMethod: pm, error } =
            await stripe.createPaymentMethod({
                type: 'card',
                card: cardNumber,
            });

        if (error) {
            $('#card-errors').text(error.message);
            $('#payNow').removeClass('disabled')
                        .text('Pay {{ Number::currency($grandTotal, 'INR') }}');
            return;
        }

        // STEP 1: Place Order + Stripe charge
        $.ajax({
            url: "{{ route('stripe.payment') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                payment_method: pm.id,
                address_id: address_id,
                notes: notes
            },
            success: function (res) {
                if (res.success) {
                    window.location.href =
                        `/checkout/success/${res.order_id}`;
                }
            },
            error: function (xhr) {
                $('#card-errors').text(xhr.responseJSON.message);
                $('#payNow').removeClass('disabled')
                            .text('Pay {{ Number::currency($grandTotal, 'INR') }}');
            }
        });
    });

});
</script>


@endsection
