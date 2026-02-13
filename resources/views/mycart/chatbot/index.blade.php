@extends('mycart.layouts.app')
@section('title', 'Chatbot')
@section('style')
    <style>
        /* Input area */
        #chatForm {
            background: #f8f9fa;
            padding: 12px;
            border-top: 1px solid #dee2e6;
            border-radius: 0 0 20px 20px;
        }
    </style>
@endsection
@section('content')
    <main class="content-wrapper">
        <section class="container pt-5">
            <div class="row">
                <div class="col-12 mx-auto">
                    <div class="position-sticky top-0">
                        <div class="bg-body-tertiary rounded-5 shadow-sm overflow-hidden">
                            <div class="p-3 p-md-4">
                                <h5 class="border-bottom pb-3 mb-4 fw-semibold">Chatbot</h5>

                                <div id="messages-content"></div>

                                <form id="chatForm" class="d-flex align-items-center gap-2 mt-3 pb-0">
                                    <input type="text" class="form-control" id="chatInput" name="message" placeholder="Type your message..." autocomplete="off">
                                    <button type="submit" id="messageSubmit" class="btn btn-primary btn-icon rounded-circle" disabled>
                                        <i class="ci-send"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('script')

@endsection