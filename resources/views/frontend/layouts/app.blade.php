<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    {{-- font awesome --}}
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.css') }}">
    {{-- google font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    @yield('extra_css')

</head>

<body>
    <div id="app">
        <div class="header-menu">
            <div class="d-flex justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-2 text-center">
                            @if (!request()->is('/'))
                                <a href="#" class="back-btn">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            @endif
                        </div>
                        <div class="col-8 text-center">

                            <h3>@yield('title')</h3>

                        </div>
                        <div class="col-2 text-center">
                            <a href="{{url('notification')}}" class="">
                                <i class="fas fa-bell"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="d-flex justify-content-center">
                <div class="col-md-8">
                    @yield('content')
                </div>
            </div>

        </div>

        <div class="bottom-menu">
            <a href="{{ url('scan-and-pay') }}" class="scan-tab">
                <div class="inside">
                    <i class="fas fa-qrcode"></i>
                </div>
            </a>
            <div class="d-flex justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-3 text-center">
                            <a href="{{ route('home') }}">
                                <i class="fas fa-home"></i>
                                <p class="mb-0">Home</p>
                            </a>
                        </div>
                        <div class="col-3 text-center">
                            <a href="{{ route('wallet') }}" class="">
                                <i class="fas fa-wallet"></i>
                                <p class="mb-0">Wallets</p>
                            </a>
                        </div>
                        <div class="col-3 text-center">
                            <a href="{{route('transaction')}}" class="">
                                <i class="fas fa-exchange-alt"></i>
                                <p class="mb-0">Transactions</p>
                            </a>
                        </div>
                        <div class="col-3 text-center">
                            <a href="{{ route('profile') }}" class="">
                                <i class="fas fa-user"></i>
                                <p class="mb-0">Account</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous">
    </script>

    {{-- sweet-alert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{asset('frontend/js/jscroll.min.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            let token = document.head.querySelector('meta[name = "csrf-token"]');
            if (token) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF_TOKEN': token.content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                });
            }

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            @if (session('create'))
                Toast.fire({
                icon: 'success',
                title: '{{ session('create') }}'
                });
            @endif

            @if (session('update'))
                Toast.fire({
                icon: 'success',
                title: '{{ session('update') }}'
                });
            @endif

            $('.back-btn').on('click', function(e) {
                e.preventDefault();
                window.history.go(-1);
                return false;
            })
        });
    </script>

    @yield('scripts')
</body>

</html>
