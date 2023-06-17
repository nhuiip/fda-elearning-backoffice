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
                    @include('layouts.components.buttons._create', ['url' => route('lessons.create')])
                </div>
                <div class="col-2">
                    @include('layouts.components._input-query')
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table-hover table-border-vertical table-border-horizontal"
                data-url="{{ route('lessons.jsontable') }}">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Passed (%)</th>
                        <th>Sort</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Updated</th>
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
                    width: '10%',
                    orderable: true
                },
                {
                    targets: [1],
                    orderable: true
                },
                {
                    targets: [2, 3, 4],
                    width: '10%',
                    className: 'text-center',
                    orderable: true
                },
                {
                    targets: [5, 6],
                    width: '10%',
                    orderable: true
                },
                {
                    targets: [7],
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
                    data: 'passScore'
                },
                {
                    data: 'sort'
                },
                {
                    data: 'status'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'action'
                }
            ]
        });
    </script>
@endsection
