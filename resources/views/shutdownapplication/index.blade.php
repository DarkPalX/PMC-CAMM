@extends('layouts.app')

@section('pagecss')
@endsection
@section('content')
<index_shutdownapplication-component :shutdownapplications = "{{ $shutdownapplications }}" ></index_shutdownapplication-component>

@endsection
