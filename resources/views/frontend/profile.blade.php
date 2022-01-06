@extends('frontend.layouts.app')
@section('title', 'Profile')
@section('content')

    <div class="account">
        <div class="profile mb-3">
            <img src="https://ui-avatars.com/api/?background=5842E3&color=fff&name={{ $user->name }}" alt="">
        </div>

        <div class="card mb-3">
            <div class="card-body pr-0">
                <div class="d-flex justify-content-between">
                    <span>Username</span>
                    <span class="mr-3">{{ $user->name }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Phone</span>
                    <span class="mr-3">{{ $user->phone }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Email</span>
                    <span class="mr-3">{{ $user->email }}</span>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body pr-0">

                <a href="{{ route('update-password') }}" class="d-flex justify-content-between">
                    <span>Update Password</span>
                    <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                </a>

                <hr>
                <a href="#" class="d-flex justify-content-between logout">
                    <span>Logout</span>
                    <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                </a>
            </div>
        </div>


    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.logout', function(e) {
                e.preventDefault();

                var id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure, you want to logout?',
                    showCancelButton: true,
                    confirmButtonText: 'confirm',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('logout') }}",
                            type: 'POST',
                            success: function() {
                                window.location.replace("{{ route('profile') }}");
                            }
                        });

                    }
                })
            });
        });
    </script>

@endsection
