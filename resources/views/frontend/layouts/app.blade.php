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

    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    @yield('extra_css')

</head>

<body>
    <div id="app">
        <div class="header-menu">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-4 text-center">
                        </div>
                        <div class="col-4 text-center">
                            <a href="" class="">
                                <h3 >Magic Pay</h3>
                            </a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="" class="">
                                <i class="fas fa-bell"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <main class="py-4">
            @yield('content')
        </main> --}}

        <div class="bottom-menu">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-4 text-center">
                            <a href="">
                                <i class="fas fa-home"></i>
                                <p class="mb-0">Home</p>
                            </a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="" class="">
                                <i class="fas fa-qrcode"></i>
                                <p class="mb-0">Scan</p>
                            </a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="" class="">
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous">
    </script>

    @yield('scripts')
</body>

</html>
