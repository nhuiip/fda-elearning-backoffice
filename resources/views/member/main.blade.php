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
                    @include('layouts.components.buttons._create', [
                        'url' => route('members.create', 'formData'),
                    ])
                    <a href="{{route('members.create', 'import')}}" class="btn btn-outline-success"><i class="fa fa-file-excel-o"></i>&nbsp;
                        Import</a>
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
                    targets: [4, 5,6,7],
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
    </script>
@endsection
