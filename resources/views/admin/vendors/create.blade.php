@extends('admin.layouts.app')
@section('title', 'Vendor Create')

@section('style')

@endsection
@section('content')

    <div class="card mb-3">
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Vendor Create</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="row" id="vendorForm">
                <div class="col-12 mb-3">
                    <label class="form-label" for="name">Vendor Name:</label>
                    <input class="form-control" id="name" type="text" name="name" />
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label" for="email">Email:</label>
                    <input class="form-control" id="email" type="text" name="email" />
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label" for="password">Password:</label>
                    <input class="form-control" id="password" type="text" name="password" />
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function () {

            // Form submit
            $('#vendorForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                ajaxCall("{{ route('admin.vendors.store') }}", 'POST', formData, function (response) {
                    if (response.status === 'success') {
                        window.location.href = '{{ route('admin.vendors.index') }}';
                    }
                }, function (error) {
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();
                    var error = JSON.parse(error.responseText);
                    // console.log(error);
                    $.each(error.message, function (key, value) {
                        $(`#${key}`).addClass('is-invalid').after(` <span class="invalid-feedback">${value}</span> `);
                    });
                });
            });

        });
    </script>
@endsection