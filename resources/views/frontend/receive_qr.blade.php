@extends('frontend.layouts.app')
@section('title', 'Receive QR')
@section('content')

    <div class="receive_qr">
        <div class="card my-card">
            <div class="card-body">
                <p class="text-center mb-0">Qr Scan</p>
                <div class="text-center">
                    <img src="data:image/png;base64, {!! base64_encode(
    QrCode::format('png')->size(240)->generate($authUser->phone),
) !!} ">
                </div>
                <p class="text-center"><strong>{{$authUser->name}}</strong></p>
                <p class="text-center">{{$authUser->phone}}</p>
            </div>
        </div>
    </div>

@endsection
