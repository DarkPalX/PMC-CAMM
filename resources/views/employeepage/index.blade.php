@extends('layouts.employee')
@section('content')
<emp_dashboard-component  :applications = "{{ $applications }}" :user="{{ Auth::user() }}" :expday = "{{ $expDay }}"></emp_dashboard-component>
@endsection