@extends('frontend.layouts.app')
@section('title', 'Transfer Confirm')
@section('content')

    <div class="confirm ">
        <div class="card">
            <div class="card-body">
                @include('frontend.layouts.flash')
                <form action="{{ route('transferComplete') }}" method="POST" id="form">
                    @csrf
                    <input type="hidden" name="hash_value" value="{{ $hash_value }}">
                    <input type="hidden" name="to_phone" value="{{ $to_account->phone }}">
                    <input type="hidden" name="amount" value="{{ $amount }}">
                    <input type="hidden" name="description" value="{{ $description }}">
                    <div class="form-group">
                        <label for="" class="mb-0"> <strong>From</strong></label>
                        <p class="mb-1 text-muted">{{ $from_account->name }}</p>
                        <p class="mb-1 text-muted">{{ $from_account->phone }}</p>
                    </div>

                    <div class="form-group">
                        <label for="" class="mb-0">To</label>
                        <p class="mb-1 text-muted">{{ $to_account->name }}</p>
                        <p class="mb-1 text-muted">{{ $to_account->phone }}</p>
                    </div>

                    <div class="form-group">
                        <label for="" class="mb-0">Amount (MMK)</label>
                        <p class="mb-1 text-muted">{{ number_format($amount) }}</p>
                    </div>

                    <div class="form-group">
                        <label for="" class="mb-0">Description</label>
                        <p class="mb-1 text-muted">{{ $description }}</p>
                    </div>
                    <button type="submit" class="btn btn-theme btn-block mt-3 confirm-btn">Confirm</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $(document).ready(function() {
            $('.confirm-btn').on('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Please Fill Your Password.',
                    icon: 'info',
                    html: '<input type="password" class="form-control text-center password" />',
                    showCancelButton: true,
                    reverseButtons: true,
                    confirmButtonText: 'Comfirm',
                    cancleButtonText: 'Cancle',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var password = $('.password').val();
                        $.ajax({
                            url: '/password-check?password=' + password,
                            type: 'GET',
                            success: function(res) {
                                if (res.status == 'success') {
                                    $('#form').submit();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Something went wrong!',
                                        footer: res.message
                                    })
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>

@endsection
