@extends('layouts.app')
@section('title', $title)
@section('breadcrumb')
    @include('layouts.components._breadcrumb', ['breadcrumbs' => $breadcrumbs, 'title' => $title])
@endsection
@section('content')
    @if ($type == 'import')
        @include('member.form._importData')
    @else
        @include('member.form._formData')
    @endif
@endsection
