<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Meta -->
    <meta name="description" content="" />
    <meta name="author" />

    <!-- CSRF Token -->

    <meta id="csrf" name="csrf-token" content="{{ csrf_token() }}">
    <title>CAMM</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{env('APP_URL')}}/assets/images/favicon.png" />

    <!-- vendor css -->
    <link href="{{env('APP_URL')}}/assets/lib/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="{{env('APP_URL')}}/assets/lib/ionicons/css/ionicons.min.css" rel="stylesheet" />
    <link href="{{env('APP_URL')}}/assets/lib/jqvmap/jqvmap.min.css" rel="stylesheet" />
    <link href="{{env('APP_URL')}}/assets/lib/bselect/dist/css/bootstrap-select.css" rel="stylesheet" />

    <!-- DashForge CSS -->
    <link rel="stylesheet" href="{{env('APP_URL')}}/assets/css/dashforge.css" />
    <link rel="stylesheet" href="{{env('APP_URL')}}/assets/css/dashforge.dashboard.css" />
    <link rel="stylesheet" href="{{env('APP_URL')}}/assets/css/skin.deepblue.css" />
    <link rel="stylesheet" href="{{env('APP_URL')}}/assets/css/custom-admin.css" />
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/primeicons-master/primeicons.css" />
    @yield('pagecss')
</head>

<body>
    <div class="content ht-100v pd-0">
        <div class="content-header w-100 mg-l-0 pl-3 pl-lg-4">
            <div class="content-search content-company">
                <h3 class="tx-15 mg-b-0">CAMM</h3>
            </div>

            <div class="dropdown dropdown-profile">
                <a href="" class="dropdown-link" data-toggle="dropdown" data-display="static">
                    <div class="avatar avatar-sm">
                        <img src="{{env('APP_URL')}}/assets/images/user.png" class="rounded-circle" alt="" />
                    </div>
                </a>
                <!-- dropdown-link -->
                <div class="dropdown-menu dropdown-menu-right tx-13">
                    <h6 class="tx-semibold mg-b-5">{{ Auth::user()->name }}</h6>
                    <p class="tx-12 tx-color-03">{{ Auth::user()->role }}</p>
                    <div class="dropdown-divider"></div>

                    <a href="#" class="dropdown-item"><i data-feather="edit-3"></i> Account Settings</a>
					<a href="{{ route('auth.change_password') }}" class="dropdown-item"><i data-feather="edit-3"></i>Change Password</a>
                    <a href="{{ route('logout') }}" class="dropdown-item"><i data-feather="log-out"></i>Log Out</a>
                </div>
                <!-- dropdown -->
            </div>
        </div>


        <div class="content-body">
            <div class="container pd-x-0">
                <div class="media d-block d-lg-flex">
                    <div class="profile-sidebar profile-sidebar-three pd-lg-r-15" style="width:auto;min-width:200px">
                        <div class="row">
                            <div class="col-sm-3 col-md-2 col-lg-12">
                                <div class="avatar avatar-xxl avatar-online"><img src="http://projects.wsiph2.com/webdirectory/cerebro/editorv2/public/images/user.png" class="rounded-circle" alt=""></div>
                            </div>
                            <div class="col-sm-9 col-md-10 col-lg-12 mg-t-20 mg-sm-t-0 mg-lg-t-25">
                                <input type="hidden" id="user_id" value="1">
                                <h5 class="mg-b-2 tx-spacing--1">{{ Auth::user()->name }}</h5>
                                <p class="tx-color-03 mg-b-25">{{ Auth::user()->role }}</p>

                                <label class="tx-sans tx-10 tx-semibold tx-uppercase tx-color-01 tx-spacing-1 mg-b-5">Department</label>
                                <p class="tx-13 tx-color-02 mg-b-25">{{ Auth::user()->dept }}</p>

                                <label class="tx-sans tx-10 tx-semibold tx-uppercase tx-color-01 tx-spacing-1 mg-b-5">Date Last Access</label>
                                <p class="tx-13 tx-color-02 mg-b-50">Dec 6, 2021 9:24 AM</p>

                            </div>
                        </div>
                    </div>
                    <div class="row row-sm" id="app">
                        @yield('content')
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('/js/app.js') }}"></script>
    <script src="{{env('APP_URL')}}/assets/lib/jquery/jquery.min.js"></script>
    <script src="{{env('APP_URL')}}/assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{env('APP_URL')}}/assets/lib/feather-icons/feather.min.js"></script>
    <!-- <script src="{{env('APP_URL')}}/assets/lib/perfect-scrollbar/perfect-scrollbar.min.js"></script> -->

    <!--Put your external scripts here -->

    <script src="{{env('APP_URL')}}/assets/js/dashforge.js"></script>
    <script src="{{env('APP_URL')}}/assets/js/dashforge.sampledata.js"></script>

    <script src="{{env('APP_URL')}}/assets/lib/bselect/dist/js/bootstrap-select.js"></script>
    <script src="{{env('APP_URL')}}/assets/lib/bselect/dist/js/i18n/defaults-en_US.js"></script>

    @yield('pagejs')

</body>

</html>