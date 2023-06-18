@extends('layouts.app')
@section('title', $title)
@section('breadcrumb')
    @include('layouts.components._breadcrumb', ['breadcrumbs' => $breadcrumbs, 'title' => $title])
@endsection
@section('content')
    @if (empty($data))
        {{ Form::open(['novalidate', 'route' => 'choices.store', 'class' => 'form-horizontal', 'id' => 'data-form', 'method' => 'post', 'files' => true]) }}
    @else
        {{ Form::model($data, ['novalidate', 'route' => ['choices.update', $data->id], 'class' => 'form-horizontal', 'id' => 'data-form', 'method' => 'put', 'files' => true]) }}
    @endif
    {{ Form::hidden('questionId', $question->id) }}
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                            {{ Form::checkbox('status', true, null, ['class' => 'form-check-input', 'id' => 'inline-1']) }}
                            <label class="form-check-label" for="inline-1">Check for Active</label>
                        </div>
                        <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                            {{ Form::checkbox('isRight', true, null, ['class' => 'form-check-input', 'id' => 'inline-2']) }}
                            <label class="form-check-label" for="inline-2">Check for Correct</label>
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
                    <div class="col-9">
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
                        <label class="form-label">Choice</label>
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
        {{ Form::model($data, ['novalidate', 'route' => ['choices.update', $data->id], 'class' => 'form-horizontal', 'id' => 'delete-form-' . $data->id, 'method' => 'put', 'files' => true]) }}
        {{ Form::hidden('imageUrl', '') }}
        {{ Form::hidden('hasImage', 0) }}
        {{ Form::hidden('action', 'deleteImage') }}
        {!! Form::close() !!}
    @endif
@endsection
