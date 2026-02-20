@extends('admin.layouts.app')
@section('title', 'Product Scraper')

@section('style')

@endsection
@section('content')

    <div class="card mb-3">
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Product Scraper</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label" for="url">URL:</label>
                    <input type="url" class="form-control" name="url" id="url" placeholder="Url" value="https://webscraper.io/test-sites/e-commerce/allinone/computers/laptops">
                </div>
                <div class="col-auto justify-content-end mb-3 d-flex">
                    <button class="btn btn-success" id="submitBtn" type="submit">Fetch Product</button>
                </div>
                <div class="col-12" id="result">
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('#submitBtn').click(function () {
                var url = $('#url').val();
                if (url) {
                    $.ajax({
                        url: "{{ route('admin.products.scrape') }}",
                        method: "POST",
                        data: { url: url },
                        beforeSend: function () {
                            $('#result').html('<p>Loading...</p>');
                        },
                        success: function (response) {
                            $('#result').empty();
                            response.forEach(function (item) {
                                $('#result').append(`<div class="d-flex align-items-center mb-3 p-2 border shadow rounded"><img src="${item.image}" alt="${item.name}"><div class="ps-2"><h5>${item.title}</h5><p>${item.price}</p><p>${item.description}</p></div></div>`);
                            });
                        },
                        error: function (xhr, status, error) {
                            $('#result').html('<p class="text-danger">An error occurred while fetching the products.</p>');
                        }
                    });
                } else {
                    $("#url").removeClass('is-invalid');
                    $('.invalid-feedback').remove();
                    $("#url").addClass('is-invalid').after('<div class="invalid-feedback">Url is required.</div>');
                }
            });
        });
    </script>
@endsection