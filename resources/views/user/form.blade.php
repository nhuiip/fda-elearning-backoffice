@extends('layouts.app')
@section('title', $title)
@section('breadcrumb')
    @include('layouts.components._breadcrumb', ['breadcrumbs' => $breadcrumbs, 'title' => $title])
@endsection
@section('content')
    @if (empty($data))
        {{ Form::open(['novalidate', 'route' => 'users.store', 'class' => 'form-horizontal', 'id' => 'data-form', 'method' => 'post', 'files' => true]) }}
    @else
        {{ Form::model($data, ['novalidate', 'route' => ['users.update', $data->id], 'class' => 'form-horizontal', 'id' => 'data-form', 'method' => 'put', 'files' => true]) }}
    @endif
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                @if (Route::Is('users.create') || Route::Is('users.edit'))
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="m-checkbox-inline custom-radio-ml">
                                @foreach ($role as $key => $value)
                                    <div class="form-check form-check-inline radio radio-primary">
                                        <input class="form-check-input" id="radioinline-{{ $key }}" type="radio"
                                            name="role" value="{{ $value }}"
                                            @if (!empty($data) && $data->roles->first()->name == $value) checked @endif>
                                        <label class="form-check-label mb-0"
                                            for="radioinline-{{ $key }}">{{ $value }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('role')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label"><span class="text-danger">*</span> Name</label>
                            {{ Form::text('name', old('name'), ['class' => 'form-control', 'required', 'placeholder' => 'Enter name']) }}
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label"><span class="text-danger">*</span> Email</label>
                            {{ Form::text('email', old('email'), ['class' => 'form-control', 'required', 'placeholder' => 'Enter email']) }}
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                @endif
                @if (Route::Is('users.create') || Route::Is('users.resetpassword'))
                    <div class="row">
                        <div class="col-3">
                            <label class="form-label"><span class="text-danger">*</span> Password</label>
                            {{ Form::password('password', ['class' => 'form-control password', 'required', 'placeholder' => 'Enter password']) }}
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-3">
                            <label class="form-label"><span class="text-danger">*</span> Confirm Password</label>
                            {{ Form::password('password_confirmation', ['class' => 'form-control password', 'required', 'placeholder' => 'Enter confirm password']) }}
                            @error('password_confirmation')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-3">
                            <label class="form-label">.</label>
                            <div class="input-group">
                                <input class="form-control password" type="text" placeholder="Random password">
                                <button type="button" class="btn btn-light" onclick="rondomPassword(8)">Random</button>
                            </div>
                        </div>
                    </div>
                @endif
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
                    @if (Route::Is('users.resetpassword'))
                        @include('layouts.components.buttons._save', ['value' => 'resetpassword'])
                    @else
                        @include('layouts.components.buttons._save', ['value' => 'save'])
                    @endif
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
@section('script')
    <script>
        function rondomPassword(e) {
            let password = Math.random().toString(36).slice(-e);
            $('.password').val(password)
        }
    </script>
@endsection
