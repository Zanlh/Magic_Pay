@extends('backend.layouts.app')
@section('admin-user-active', 'mm-active')
@section('title', 'Admin Users')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>Admin User
                </div>
            </div>
        </div>
    </div>
    <div class="pt-3">
        <a href="{{ route('admin.admin-user.create') }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Create
            admin user</a>
    </div>

    <div class="content py-3">

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered Datatable">
                    <thead>
                        <tr class="bg-light">
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>IP</th>
                            <th class="no-sort">User Agent</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th class="no-sort" >Action</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
           var table =  $('.Datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/admin/admin-user/datatable/ssd",
                columns: [{
                        data: "name",
                        name: "name"
                    },
                    {
                        data: "email",
                        name: "email"
                    },
                    {
                        data: "phone",
                        name: "phone"
                    },
                    {
                        data: "ip",
                        name: "ip"
                    },
                    {
                        data: "user_agent",
                        name: "user_agent",
                    },
                    {
                        data: "created_at",
                        name: "created_at",
                    },
                    {
                        data: "updated_at",
                        name: "updated_at",
                    },
                    {
                        data: "action",
                        name: "action",
                    },
                ],
                order: [[ 6, "desc" ]],
                columnDefs: [{
                    targets: 'no-sort',
                    sortable: false
                }]
            });

            $(document).on('click', '.delete', function(e) {
                e.preventDefault();

                var id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure, you want to delete?',
                    showCancelButton: true,
                    confirmButtonText: 'confirm', 
                }).then((result) => {
                    if (result.isConfirmed) {
                       $.ajax({
                           url : '/admin/admin-user/' + id,
                           type : 'DELETE', 
                           success: function() {
                               table.ajax.reload();
                           }
                       });
                    }
                })
            });
        });
    </script>
@endsection
