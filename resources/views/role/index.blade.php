@extends('layouts.app')

@section('pagecss')
@endsection
@section('content')
<index_role-component :roles = "{{ $roles }}" ></index_role-component>
@endsection

@section('pagejs')

<script src="{{env('APP_URL')}}/assets/lib/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
<script src="{{env('APP_URL')}}/assets/lib/parsleyjs/parsley.min.js"></script>
<script src="{{env('APP_URL')}}/assets/lib/jquery-steps/build/jquery.steps.min.js"></script>
<script src="{{env('APP_URL')}}/assets/lib/jqueryui/jquery-ui.min.js"></script>
<script src="{{env('APP_URL')}}/assets/js/listing.js"></script>
@endsection