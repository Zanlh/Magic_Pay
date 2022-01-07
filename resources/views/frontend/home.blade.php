@extends('frontend.layouts.app')
@section('title', 'Magic Pay')
@section('content')
    <div class="home">
        <div class="row">
            <div class="col-12">
                <div class="profile mb-3">
                    <img src="https://ui-avatars.com/api/?background=5842E3&color=fff&name={{ $user->name }}" alt="">
                    <h6>{{ $user->name }}</h6>
                    <p class="text-muted">{{ $user->wallet ? number_format($user->wallet->amount) : 0 }}</p>
                </div>
                </p>
            </div>
            <div class="col-6">
                <div class="card mb-3">
                    <div class="card-body shortcut-box p-3">
                        <img src="{{ asset('img/qr-code-scan.png') }}" alt="">
                        <span>Scan & Pay</span>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card mb-3">
                    <div class="card-body shortcut-box p-3">
                        <img src="{{ asset('img/qr-code.png') }}" alt="">
                        <span>Receive</span>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-body shortcut-box pr-0">
                        <a href="{{route('transfer')}}" class="d-flex justify-content-between">
                            <span><img src="{{ asset('img/money-transfer.png') }}" alt=""> Transfer</span>
                            <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                        </a>
                        <hr>
                        <a href="#" class="d-flex justify-content-between ">
                            <span><img src="{{ asset('img/wallet.png') }}" alt="">Wallet</span>
                            <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                        </a>
                        <hr>
                        <a href="#" class="d-flex justify-content-between ">
                            <span><img src="{{ asset('img/transaction.png') }}" alt="">Transaction</span>
                            <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
