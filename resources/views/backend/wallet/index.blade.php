@extends('backend.layouts.app')
@section('wallet-active', 'mm-active')
@section('title', 'Wallet')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-wallet icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>Wallets
                </div>
            </div>
        </div>
    </div>
    <div class="pt-3">
        <a href="{{ route('admin.user.create') }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Create
            User</a>
    </div>

    <div class="content py-3">

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered Datatable">
                    <thead>
                        <tr class="bg-light">
                            <th>Account Number</th>
                            <th>Account Person</th>
                            <th>Amount(MMK)</th>
                            <th>Created</th>
                            <th>Updated</th>
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
                ajax: "/admin/wallet/datatable/ssd",
                columns: [{
                        data: "account_number",
                        name: "account_number"
                    },
                    {
                        data: "account_person",
                        name: "account_person"
                    },
                    {
                        data: "amount",
                        name: "amount"
                    },
                    {
                        data: "created_at",
                        name: "created_at",
                    },
                    {
                        data: "updated_at",
                        name: "updated_at",
                    },
                ],
                order: [[ 4, "desc" ]],
                columnDefs: [{
                    targets: 'no-sort',
                    sortable: false
                }]
            });
        });
    </script>
@endsection
