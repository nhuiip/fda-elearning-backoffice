@extends('layouts.app')
@section('title', $title)
@section('breadcrumb')
    @include('layouts.components._breadcrumb', ['breadcrumbs' => $breadcrumbs, 'title' => $title])
@endsection
@section('content')
    @if (empty($data))
        {{ Form::open(['novalidate', 'route' => 'questions.store', 'class' => 'form-horizontal', 'id' => 'data-form', 'method' => 'post', 'files' => true]) }}
    @else
        {{ Form::model($data, ['novalidate', 'route' => ['questions.update', $data->id], 'class' => 'form-horizontal', 'id' => 'data-form', 'method' => 'put', 'files' => true]) }}
        {{ Form::hidden('questionId', $data->id, ['id' => 'questionId']) }}
    @endif
    {{ Form::hidden('lessonId', $lesson->id) }}
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                            {{ Form::checkbox('status', true, null, ['class' => 'form-check-input', 'id' => 'inline-1']) }}
                            <label class="form-check-label" for="inline-1">Check for Active</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-3">
                        <label class="form-label"><span class="text-danger">*</span> Sort</label>
                        {{ Form::number('sort', old('sort'), ['class' => 'form-control', 'required', 'placeholder' => 'Enter sort']) }}
                        @error('sort')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-3">
                        <label class="form-label"><span class="text-danger">*</span> Score</label>
                        {{ Form::number('score', old('score'), ['class' => 'form-control', 'required', 'placeholder' => 'Enter score']) }}
                        @error('score')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label">
                            Image
                            @if (!empty($data) && $data->hasImage)
                                <a href="{{ $data->imageUrl }}" target="_blank" rel="noopener noreferrer"><i
                                        class="text-danger"><u>View image</u></i></a> |
                                <a href="javascript:;" data-text="Delete!" data-form="delete-form-{{ $data->id }}"
                                    onclick="fncDelete(this)" rel="noopener noreferrer"><i class="text-danger"><u>Delete
                                            image</u></i></a>
                            @endif
                        </label>
                        {{ Form::file('image', ['class' => 'form-control', 'accept' => 'image/*']) }}
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label"><span class="text-danger">*</span> Question</label>
                        {{ Form::text('name', old('name'), ['class' => 'form-control', 'required', 'placeholder' => 'Enter question']) }}
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-6">
                    @include('layouts.components.buttons._back', [
                        'url' => $breadcrumbs[count($breadcrumbs) - 2]['route'],
                    ])
                    @include('layouts.components.buttons._reset')
                </div>
                <div class="col-6">
                    @include('layouts.components.buttons._save', ['value' => 'save'])
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    @if (!empty($data))
        {{-- form for delete image --}}
        {{ Form::model($data, ['novalidate', 'route' => ['questions.update', $data->id], 'class' => 'form-horizontal', 'id' => 'delete-form-' . $data->id, 'method' => 'put', 'files' => true]) }}
        {{ Form::hidden('imageUrl', '') }}
        {{ Form::hidden('hasImage', 0) }}
        {{ Form::hidden('action', 'deleteImage') }}
        {!! Form::close() !!}
        {{-- choice list --}}
        <div class="page-title pt-0 pb-4">
            <div class="row">
                <div class="col-12 col-sm-12">
                    <h3>Choice Management</h3>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header pb-0">
                <div class="row">
                    <div class="col-10">
                        @include('layouts.components.buttons._create', [
                            'url' => route('choices.create', $data->id),
                        ])
                    </div>
                    <div class="col-2">
                        @include('layouts.components._input-query')
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="dataTable" class="table-hover table-border-vertical table-border-horizontal"
                    data-url="{{ route('choices.jsontable') }}">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Choice</th>
                            <th>Correct</th>
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
    @endif
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
                type: "GET",
                data: function(d) {
                    d.questionId = $('#questionId').val();
                },
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
                    data: 'isRight'
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
