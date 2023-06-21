@extends('layouts.app')
@section('title', $title)
@section('breadcrumb')
    @include('layouts.components._breadcrumb', ['breadcrumbs' => $breadcrumbs, 'title' => $title])
@endsection
@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="row">
                <div class="col-10">
                    <a href="{{ asset('temp-header-import.xlsx') }}" target="_blank" class="btn btn-outline-light" download><i
                            class="fa fa-file-excel-o"></i>&nbsp;
                        Download template for Import</a>
                    <a href="{{ route('members.create', 'import') }}" class="btn btn-outline-success"><i
                            class="fa fa-file-excel-o"></i>&nbsp;
                        Import</a>
                    <a href="javascript:;" onclick="sendMailAll(this)" data-url="{{ route('sendMailAll') }}"
                        class="btn btn-outline-info"><i class="fa fa-envelope"></i>&nbsp;
                        Send Password</a>
                    <a href="javascript:;" onclick="sendMailNewMember(this)" data-url="{{ route('sendMailNewMember') }}"
                        class="btn btn-outline-primary"><i class="fa fa-envelope"></i>&nbsp;
                        Send Password (New)</a>
                </div>
                <div class="col-2">
                    @include('layouts.components._input-query')
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table-hover table-border-vertical table-border-horizontal"
                data-url="{{ route('members.jsontable') }}">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Business type</th>
                        <th>Passed</th>
                        <th>Import Date</th>
                        <th>Register Date</th>
                        <th>Last visit</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr></tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script>
        let dataTable = $('#dataTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 10,
            dom: 'rtip',
            ajax: {
                url: $('#dataTable').attr('data-url'),
                type: "GET"
            },
            columnDefs: [{
                    targets: [0],
                    width: '10%'
                },
                {
                    targets: [1, 2, 3],
                    orderable: false
                },
                {
                    targets: [4, 5, 6, 7],
                    width: '10%',
                    orderable: false
                },
                {
                    targets: [8],
                    width: '5%',
                    className: 'text-center',
                    orderable: false
                }
            ],
            columns: [{
                    data: 'id'
                },
                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'businessType'
                },
                {
                    data: 'passed'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'registerDate'
                },
                {
                    data: 'lastVisitDate'
                },
                {
                    data: 'action'
                }
            ]
        });

        function sendMail(e) {
            let url = $(e).attr('data-url');
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                }
            });
        }

        function sendMailAll(e) {
            let url = $(e).attr('data-url');
            new swal({
                title: "Are you sure?",
                text: "Once deleted, You won't be able to revert this!",
                icon: "warning",
                buttons: {
                    cancel: true,
                    confirm: {
                        text: "Send Email",
                        className: 'btn-info',
                    },
                },
            }).then((data) => {
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(resp) {
                        // new swal({
                        //     title: "Good job!",
                        //     text: "Send password success!",
                        //     icon: "success",
                        // })
                    }
                });
                // setTimeout(function() {
                //     swal("Ajax request finished!");
                // }, 2000);
            });
        }

        function sendMailNewMember(e) {
            let url = $(e).attr('data-url');
            new swal({
                title: "Are you sure?",
                text: "Once deleted, You won't be able to revert this!",
                icon: "warning",
                buttons: {
                    cancel: true,
                    confirm: {
                        text: "Send Email",
                        className: 'btn-info',
                    },
                },
            }).then((data) => {
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(resp) {
                        // new swal({
                        //     title: "Good job!",
                        //     text: "Send password success!",
                        //     icon: "success",
                        // })
                    }
                });
                // setTimeout(function() {
                //     swal("Ajax request finished!");
                // }, 2000);
            });
        }
    </script>
@endsection
