@extends('mycart.layouts.app')
@section('title', 'Chatbot')
@section('style')

@endsection
@section('content')
    <main class="content-wrapper">

        <section class="container pt-5">

            <div class="row">

                <div class="col-12 order-summary" style="margin-top: -100px">
                    <div class="position-sticky top-0" style="padding-top: 100px">
                        <div class="bg-body-tertiary rounded-5 p-4 mb-3">
                            <div class="p-sm-2 p-lg-0 p-xl-2">
                                <h5 class="border-bottom pb-2 mb-4">Chatbot</h5>
                                <div class="overflow-auto" style="height:500px;" id="message-content">
                                    {{-- <div class="message sent">
                                        <p class="card-text">Hello</p>
                                    </div>
                                    <div class="message received">
                                        <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla
                                            nam commodi in vel rerum laboriosam aliquam magnam libero. Nostrum, sint?</p>
                                    </div> --}}
                                </div>
                                <div class="">
                                    <form id="chatForm" class="d-flex align-items-center">
                                        <input type="text" class="form-control" id="chatInput" name="message"
                                            placeholder="Type your message">
                                        <button type="submit" id="messageSubmit" class="ms-2 btn btn-icon btn-secondary fs-lg rounded-circle">
                                            <i class="ci-send"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection
@section('script')
    <script>
        $(document).ready(function () {

            $("#chatForm").submit(function (e) {
                e.preventDefault();
                let con = $("#message-content");
                con.append(`<div class="message sent"><pre class="card-text">${$("#chatInput").val()}</pre></div>`);
                scrollToBottom();

                $.ajax({
                    type: "post",
                    url: "{{ route('ai.generate') }}",
                    data: {
                        message: $("#chatInput").val(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        console.log(response);
                        con.append(`<div class="message received"><pre class="card-text">${response}</pre></div>`);
                        scrollToBottom();
                        $("#chatInput").val('');
                    }
                });
            });

            // disable submit button
            var $submitBtn = $('#messageSubmit');
            $submitBtn.prop('disabled', true);

            $('#chatInput').on('input change keyup', function() {
                var textareaValue = $(this).val().trim();
                $submitBtn.prop('disabled', (textareaValue.length === 0));
            });

            function scrollToBottom(force = false, smooth = true) {
                const el = document.getElementById('messages-content');
                if (!el) return;
            
                const nearBottom = el.scrollHeight - el.scrollTop - el.clientHeight < 120;
            
                if (force || nearBottom) {
                    el.scrollTo({
                        top: el.scrollHeight,
                        behavior: smooth ? 'smooth' : 'auto'
                    });
                }
            }
        });
    </script>
@endsection