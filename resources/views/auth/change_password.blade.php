@extends('layouts.login')

@section('pagecss')
@endsection
@section('content')
<change_password-component></change_password-component>
@endsection

@section('pagejs')

<script src="{{env('APP_URL')}}/assets/lib/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
<script src="{{env('APP_URL')}}/assets/lib/parsleyjs/parsley.min.js"></script>
<script src="{{env('APP_URL')}}/assets/lib/jquery-steps/build/jquery.steps.min.js"></script>
<script src="{{env('APP_URL')}}/assets/lib/jqueryui/jquery-ui.min.js"></script>
<script src="{{env('APP_URL')}}/assets/js/listing.js"></script>

<!-- Scripts (Global) -->
<script src="{{env('APP_URL')}}/assets/lib/jquery/jquery.min.js"></script>
<script src="{{env('APP_URL')}}/assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{env('APP_URL')}}/assets/lib/feather-icons/feather.min.js"></script>
<!-- <script src="{{env('APP_URL')}}/assets/lib/perfect-scrollbar/perfect-scrollbar.min.js"></script> -->
<script src="{{env('APP_URL')}}/assets/js/dashforge.js"></script>

@endsection