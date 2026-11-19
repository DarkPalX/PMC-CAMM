@extends('layouts.app')
@section('content')
<dashboard-component :loggedinusers = "{{ $loggedInUsers }}" :applications = "{{ $applications }}" :user="{{ Auth::user() }}"></dashboard-component>
@endsection