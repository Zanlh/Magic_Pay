@extends('frontend.layouts.app')
@section('title', 'Transfer')
@section('content')

    <div class="transfer">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('transferConfirm') }}" method="GET" autocomplete="off">
                    <div class="form-group">
                        <label for="">From</label>
                        <p class="mb-1 text-muted">{{ $user->name }}</p>
                        <p class="mb-1 text-muted">{{ $user->phone }}</p>
                    </div>

                    <div class="form-group">
                        <label for="">To <span class="text-success to_account_info"></span></label>
                        <div class="input-group">
                            <input type="text" name="to_phone" class="form-control to_phone "
                                value="{{ old('to_phone') }}">
                            <div class="input-group-append">
                                <span class="input-group-text btn verify-btn" id="basic-addon2"><i
                                        class="fas fa-check"></i></span>
                            </div>
                        </div>
                        @error('to_phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Amount (MMK)</label>
                        <input type="number" class="form-control " name="amount" value="{{ old('amount') }}">
                        @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-theme btn-block mt-3">Continue</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $(document).ready(function() {
            $('.verify-btn').on('click', function() {
                var phone = $('.to_phone').val();
                $.ajax({
                    url: '/to-account-verify?phone=' + phone,
                    type: 'GET',
                    success: function(res) {
                        if (res.status == 'success') {
                            $('.to_account_info').text('(' + res.data['name'] + ')');
                        } else {
                            $('.to_account_info').text('(' + res.message + ')');
                        }
                    }
                });
            });
        });
    </script>

@endsection
