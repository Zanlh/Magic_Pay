@extends('frontend.layouts.app')
@section('title', 'Scan and Pay Form')
@section('content')

    <div class="transfer">
        <div class="card">
            <div class="card-body">
                @include('frontend.layouts.flash')
                <form action="{{ url('scan-and-pay/confirm') }}" method="GET" autocomplete="off" id="transfer-form">

                    <input type="hidden" name="hash_value" value="" class="hash_value">
                    <input type="hidden" name="to_phone" class="to_phone" value="{{ $to_account->phone }}">
                    
                    <div class="form-group">
                        <label for="">From</label>
                        <p class="mb-1 text-muted">{{ $from_account->name }}</p>
                        <p class="mb-1 text-muted">{{ $from_account->phone }}</p>
                    </div>

                    <div class="form-group">
                        <label for="">To</label>
                        <p class="mb-1 text-muted">{{ $to_account->name }}</p>
                        <p class="mb-1 text-muted">{{ $to_account->phone }}</p>
                    </div>


                    <div class="form-group">
                        <label for="">Amount (MMK)</label>
                        <input type="number" class="form-control amount" name="amount" value="{{ old('amount') }}">
                        @error('amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="description" class="form-control description">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-theme btn-block mt-3 submit-btn">Continue</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $(document).ready(function() {

            $('.submit-btn').on('click', function(e){
                e.preventDefault();
                var to_phone = $('.to_phone').val();
                var amount = $('.amount').val();
                var description = $('.description').val();
                $.ajax({
                    url: `/transfer-hash?to_phone=${to_phone}&amount=${amount}&description=${description}`,
                    type: 'GET',
                    success: function(res) {
                        if (res.status == 'success') {
                            $('.hash_value').val(res.data);
                            $('#transfer-form').submit();
                        }
                    }
                });
            });
        });
    </script>

@endsection
