@extends('frontend.layouts.app')
@section('title', 'Notification Detail')
@section('content')
    <div class="notification_detail">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-center">
                    <img src="{{ asset('img/notification.png') }}" alt="">
                </div>
                <h6 class="text-center">{{ $notification->data['title'] }}</h6>
                <p class="text-center mb-1">{{ $notification->data['message'], 100 }}</p>
                <div class="text-center"><small class=" mb-1">{{Carbon\Carbon::parse($notification->created_at)->format('Y-m-d h:i:s A')}}</small></div>
                <a href="{{$notification->data['web_link']}}" class="btn btn-theme">Continue</a>
            </div>
        </div>
    </div>
@endsection
