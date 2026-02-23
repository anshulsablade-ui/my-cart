$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function ajaxCall(url, method, data, successCallback, errorCallback) {
    $.ajax({
        url: url,
        type: method,
        data: data,
        processData: false,
        contentType: false,
        // global: false,
        success: successCallback,
        error: errorCallback
    });
}

$('body').on('click', '.delete', function (e) {
    e.preventDefault();

    var url = $(this).attr("href");

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "delete",
                url: url,
                success: function (response) {
                    table.ajax.reload(null, false);
                }
            });
        }
    })
});

function messageAlert(message, type) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: type,
        title: message
    });
}


$("body").on("click", ".addToCart", function () {
    var productId = $(this).data('product-id');
    $.ajax({
        type: "post",
        url: "/home/cart/add",
        data: { product_id: productId, quantity: 1 },
        global: false,
        success: function (response) {
            if (response.status == 'success') {
                messageAlert(response.message, 'success');
                $('#cartItemCount').html(response.cart_count);
            } else {
                messageAlert(response.message, 'error');
            }
        }
    });
});

$("body").on("click", ".addToWishlist", function () {
    var productId = $(this).data('product-id');
    $.ajax({
        type: "post",
        url: "/home/wishlist/add",
        data: { product_id: productId },
        global: false,
        success: function (response) {
            if (response.status == 'success') {
                messageAlert(response.message, 'success');
            } else if (response.status == 'info') {
                messageAlert(response.message, 'info');
            } else {
                window.location.href = "/auth/login";
            }
        }
    });
});