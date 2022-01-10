@extends('frontend.layouts.app')
@section('title', 'Transaction Detail')
@section('content')

    <div class="transaction-detail">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="{{ asset('img/checked.png') }}" alt="">
                </div>
                @if (session('transfer-success'))
                    <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
                        {{ session('transfer-success') }}
                    </div>
                @endif

                @if ($transaction->type == 1)
                    <h6 class="text-center text-success mb-4">{{ number_format($transaction->amount) }} MMK</h6>
                @elseif($transaction->type == 2)
                    <h6 class="text-center text-danger mb-4">{{ number_format($transaction->amount) }} MMK</h6>
                @endif

                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Trx ID</p>
                    <p class="mb-0 ">{{ $transaction->trx_id }}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Reference Number</p>
                    <p class="mb-0 ">{{ $transaction->ref_no }}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Type</p>
                    @if ($transaction->type == 1)
                        <span class="badge badge-pill badge-success">Income</span>
                    @elseif($transaction->type == 2)
                        <span class="badge badge-pill badge-danger">Expense</span>
                    @endif
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Amount</p>
                    <p class="mb-0 ">{{ number_format($transaction->amount) }} MMK</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Date & Time</p>
                    <p class="mb-0 ">{{ $transaction->created_at }} MMK</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">
                        @if ($transaction->type == 1)
                            <span>From</span>
                        @elseif($transaction->type == 2)
                            <span>To</span>
                        @endif
                    </p>
                    <p class="mb-0">
                        {{ $transaction->source ? $transaction->source->name : '-' }}
                    </p>

                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Description</p>
                    <p class="mb-0 ">{{ $transaction->description }}</p>
                </div>
                <hr>
            </div>
        </div>
    </div>

@endsection
