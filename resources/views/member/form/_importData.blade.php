{{ Form::open(['novalidate', 'route' => 'members.import', 'class' => 'form-horizontal', 'id' => 'data-form', 'method' => 'post', 'files' => true]) }}
<div class="card">
    <div class="card-body">
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-12">
                    <label class="form-label">
                        File
                    </label>
                    {{ Form::file('file', ['class' => 'form-control', 'accept' => '.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel']) }}
                    @error('file')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            @if (!empty($errors->all()) && !$errors->has('file'))
                <div class="row mb-3">
                    <div class="col-12">
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">
                                <p>{{ $error }}</p>
                            </div>
                        @endforeach
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
                @include('layouts.components.buttons._save', ['value' => 'save'])
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
