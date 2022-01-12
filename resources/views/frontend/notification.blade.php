@extends('frontend.layouts.app')
@section('title', 'Notification')
@section('content')
    <div class="notification">
        <h6>Notifications</h6>
        <div class="infinite-scroll">
            @foreach ($notifications as $notification)
                {{-- @php
                $data = json_decode($notification->data,true);
            @endphp --}}
                <a href="{{ url('notification/' . $notification->id) }}">
                    <div class="card mb-2" style="@if(is_null($notification->read_at)) background: lightgray @endif ">
                        <div class="card-body p-2">
                            <h6> {{ Illuminate\Support\Str::limit($notification->data['title'],40) }}</h6>
                            <p class="mb-1">{{ Illuminate\Support\Str::limit($notification->data['message'],100) }}</p>
                            {{-- <small class="text-muted mb-1">{{Carbon\Carbon::parse($notification->created_at)->format('Y-m-d h:i:s A')}}</small> --}}
                            <small class="text-muted mb-1">{{$notification->created_at->diffForHumans()}}</small>
                        </div>
                    </div>

                </a>
            @endforeach
            {{ $notifications->links() }}
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $('ul.pagination').hide();
        $(function() {
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '<div class="text-center"><img src="/images/loading.gif" alt="Loading..." /></div>',
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });
        });
    </script>
@endsection
