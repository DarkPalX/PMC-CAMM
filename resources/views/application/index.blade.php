@extends('layouts.app')

@section('pagecss')
@endsection
@section('content')
<index_application-component :applications = "{{ $applications }}" ></index_application-component>

@endsection
