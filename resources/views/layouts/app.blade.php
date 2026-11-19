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
	<title>CAMM</title>
	<link rel="shortcut icon" type="image/x-icon" href="{{env('APP_URL')}}/assets/images/favicon.png" />

	<!-- vendor css -->
	<link href="{{env('APP_URL')}}/assets/lib/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
	<link href="{{env('APP_URL')}}/assets/lib/ionicons/css/ionicons.min.css" rel="stylesheet" />
	<link href="{{env('APP_URL')}}/assets/lib/jqvmap/jqvmap.min.css" rel="stylesheet" />

	<!-- DashForge CSS -->
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/css/dashforge.css" />
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/css/dashforge.dashboard.css" />
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/css/skin.deepblue.css" />
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/css/custom-admin.css" />
	<!-- <link rel="stylesheet" href="{{env('APP_URL')}}/assets/css/style.css" /> -->

	<link href="{{env('APP_URL')}}/assets/lib/bselect/dist/css/bootstrap-select.css" rel="stylesheet" />
	<link href="{{env('APP_URL')}}/assets/lib/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet" />
	<link href="{{env('APP_URL')}}/assets/lib/owl.carousel/assets/owl.theme.default.min.css" rel="stylesheet" />
	<!-- Add your own custom style and css-->
	<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" /> -->
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/lib/grapesjs/toastr.min.css" />
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/lib/grapesjs/grapes.min.css" />
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/lib/grapesjs/grapick.min.css" />
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-preset-webpage.min.css" />
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/lib/grapesjs/tooltip.css" />
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-plugin-filestack.css" />

	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/lib/grapesjs/tui-color-picker.min.css" />
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/lib/grapesjs/tui-image-editor.min.css" />
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/primeicons-master/primeicons.css" />

	@yield('pagecss')
</head>

<body>
	<aside class="aside aside-fixed">
		<div class="aside-header">
			<a href="#" class="aside-logo">Admin <span>Portal</span></a>
			<a href="" class="aside-menu-link">
				<i data-feather="menu"></i>
				<i data-feather="x"></i>
			</a>
		</div>
		<div class="aside-body">
			<div class="aside-loggedin">
				<div class="d-flex justify-content-center">
					<a href="#" class="avatar wd-100"><img src="{{env('APP_URL')}}/assets/images/user.png" class="rounded-circle" alt="" /></a>
				</div>
				<div class="aside-loggedin-user tx-center">
					<h6 class="tx-semibold mg-b-0">{{ Auth::user()->name }}</h6>
					<p class="tx-color-03 tx-11 mg-b-0 tx-uppercase">{{ Auth::user()->role }}</p>
				</div>
			</div>
			<!-- aside-loggedin -->
			<ul class="nav nav-aside">

				<li class="nav-item {{ (request()->is('dashboard')) ? 'active' : '' }}">
					<a href="{{ route('dashboard') }}" class="nav-link"><i data-feather="home"></i> <span>Dashboard</span></a>
				</li>

				<li class="nav-label mg-t-25">Maintenance</li>

				<li class="nav-item with-sub {{ (request()->is('users/*')) ? 'active show' : '' }}">
					<a href="" class="nav-link"><i data-feather="users"></i> <span>Users</span></a>
					<ul>
						<li class="{{ (request()->is('users/list*')) ? 'active' : '' }}"><a href="{{ route('users.index') }}">Manage</a></li>
						<li class="{{ (request()->is('users/create')) ? 'active' : '' }}"><a href="{{ route('users.create') }}">Add New</a></li>
					</ul>
				</li>

				<li class="nav-item with-sub {{ (request()->is('announcements/*')) ? 'active show' : '' }}">
					<a href="" class="nav-link"><i data-feather="calendar"></i> <span>Announcements</span></a>
					<ul>
						<li class="{{ (request()->is('announcements/list*')) ? 'active' : '' }}"><a href="{{ route('announcements.index') }}">Manage</a></li>
						<li class="{{ (request()->is('announcements/create')) ? 'active' : '' }}"><a href="{{ route('announcements.create') }}">Add New</a></li>
					</ul>
				</li>

				<li class="nav-item with-sub {{ (request()->is('shutdownapplications/*')) ? 'active show' : '' }}">
					<a href="" class="nav-link"><i data-feather="power"></i> <span>Shutdown Applications</span></a>
					<ul>
						<li class="{{ (request()->is('shutdownapplications/list*')) ? 'active' : '' }}"><a href="{{ route('shutdownapplications.index') }}">Manage</a></li>
						<li class="{{ (request()->is('shutdownapplications/create')) ? 'active' : '' }}"><a href="{{ route('shutdownapplications.create') }}">Add New</a></li>
					</ul>
				</li>

				<li class="nav-item with-sub {{ (request()->is('applications/*')) ? 'active show' : '' }}">
					<a href="" class="nav-link"><i data-feather="layout"></i> <span>Applications</span></a>
					<ul> 
						<li class="{{ (request()->is('applications/list*')) ? 'active' : '' }}"><a href="{{ route('applications.index') }}">Manage</a></li>
						<li class="{{ (request()->is('applications/create')) ? 'active' : '' }}"><a href="{{ route('applications.create') }}">Add New</a></li>
					</ul>
				</li>
			
				<li class="nav-item {{ (request()->is('databases/index*')) ? 'active' : '' }}">
					<a href="{{ route('databases.index') }}" class="nav-link"><i data-feather="database"></i><span>Database</span></a>
				</li>

				<li class="nav-label mg-t-25">Account Management</li>

				<li class="nav-item with-sub {{ (request()->is('roles/*')) ? 'active show' : '' }}">
					<a href="" class="nav-link"><i data-feather="calendar"></i> <span>Roles</span></a>
					<ul>
						<li class="{{ (request()->is('roles/list*')) ? 'active' : '' }}"><a href="{{ route('roles.index') }}">Manage</a></li>
						<li class="{{ (request()->is('roles/create')) ? 'active' : '' }}"><a href="{{ route('roles.create') }}">Add New</a></li>
					</ul>
				</li>

				<li class="nav-item with-sub {{ (request()->is('modules/*')) ? 'active show' : '' }}">
					<a href="" class="nav-link"><i data-feather="calendar"></i> <span>Modules</span></a>
					<ul>
						<li class="{{ (request()->is('modules/list*')) ? 'active' : '' }}"><a href="{{ route('modules.index') }}">Manage</a></li>
						<li class="{{ (request()->is('modules/create')) ? 'active' : '' }}"><a href="{{ route('modules.create') }}">Add New</a></li>
					</ul>
				</li>

				<li class="nav-item with-sub {{ (request()->is('permissions/*')) ? 'active show' : '' }}">
					<a href="" class="nav-link"><i data-feather="calendar"></i> <span>Permissions</span></a>
					<ul>
						<li class="{{ (request()->is('permissions/list*')) ? 'active' : '' }}"><a href="{{ route('permissions.index') }}">Manage</a></li>
						<li class="{{ (request()->is('permissions/create')) ? 'active' : '' }}"><a href="{{ route('permissions.create') }}">Add New</a></li>
					</ul>
				</li>


				<li class="nav-item {{ (request()->is('roleaccessrights/index*')) ? 'active' : '' }}">
					<a href="{{ route('roleaccessrights.index') }}" class="nav-link"><i data-feather="calendar"></i><span>Role Access Rights</span></a>
				</li>


				<li class="nav-label mg-t-25">Reports</li>
				<!-- <li class="nav-item">
					<a href="#" class="nav-link"><i data-feather="users"></i> <span>Users List</span></a>
				</li> -->
				<li class="nav-item {{ (request()->is('report/logs*')) ? 'active' : '' }}">
					<a href="{{ route('report.logs') }}" class="nav-link"><i data-feather="book"></i> <span>User Logs</span></a>
				</li>
				<li class="nav-item {{ (request()->is('report/announcement*')) ? 'active' : '' }}">
					<a href="{{ route('report.announcement') }}" class="nav-link"><i data-feather="book"></i> <span>Announcement Report</span></a>
				</li>

			</ul>
		</div>
	</aside>

	<div class="content ht-100v pd-0">
		<div class="content-header">
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
					<a href="#" class="dropdown-item"><i data-feather="help-circle"></i> Help</a>
					<a href="{{ route('logout') }}" class="dropdown-item"><i data-feather="log-out"></i>Log Out</a>
				</div>
				<!-- dropdown-menu -->
			</div>
			<!-- dropdown -->
		</div>

		<notifications />
		<div class="content-body" id="app">
			@yield('content')
		</div>


		<!-- Success -->
		<div class="pos-fixed b-10 r-10">
			<div id="toast_success" class="toast bg-success bd-0 wd-350" data-delay="3000" role="alert" aria-live="assertive" aria-atomic="true">
				<div class="toast-header bg-success">
					<h6 class="tx-white tx-14 mg-b-0 mg-r-auto tx-semibold">
						<i data-feather="check-circle" width="14"></i> Success
					</h6>
					<button type="button" class="ml-2 mb-1 close tx-normal" data-dismiss="toast" aria-label="Close">
						<i data-feather="x" aria-hidden="true" class="tx-white wd-15"></i>
					</button>
				</div>
				@if (Session::has('success'))
				<div class="toast-body bg-success tx-white">
					{{ Session::get('success') }}
				</div>
				@endif
				remove
			</div>
		</div>

		<!-- Error -->
		<div class="pos-fixed b-10 r-10">
			<div id="toast_error" class="toast bg-danger bd-0 wd-350" data-delay="3000" role="alert" aria-live="assertive" aria-atomic="true">
				<div class="toast-body pd-6 tx-white">
					<button type="button" class="ml-2 mb-1 close tx-normal tx-shadow-none" data-dismiss="toast" aria-label="Close">
						<span class="tx-white" aria-hidden="true">&times;</span>
					</button>
					<h6 class="mg-b-15 mg-t-15 tx-white">
						<i data-feather="alert-circle"></i> ERROR!
					</h6>
					<p>{{ Session::get('error') }}</p>
				</div>
			</div>
		</div>

		<!-- Error -->
		<div class="pos-fixed b-10 r-10">
			<div id="toastDynamicError" class="toast bg-danger bd-0 wd-350" data-delay="60000" role="alert" aria-live="assertive" aria-atomic="true">
				<div class="toast-body pd-6 tx-white">
					<button type="button" class="ml-2 mb-1 close tx-normal tx-shadow-none" data-dismiss="toast" aria-label="Close">
						<span class="tx-white" aria-hidden="true">&times;</span>
					</button>
					<h6 class="mg-b-15 mg-t-15 tx-white">
						<i data-feather="alert-circle"></i> ERROR!
					</h6>
					<p id="errorMessage"></p>
				</div>
			</div>
		</div>

		<div class="pos-absolute b-10 r-20 z-index-200">
			<div id="toastErrorMessage" class="toast bg-danger bd-0 wd-350" data-delay="10000" role="alert" aria-live="assertive" aria-atomic="true">
				<div class="toast-header bg-danger">
					<h6 class="tx-white tx-14 mg-b-0 mg-r-auto tx-semibold">
						<i data-feather="x-circle" width="14"></i> Failed
					</h6>
					<button type="button" class="ml-2 mb-1 close tx-normal" data-dismiss="toast" aria-label="Close">
						<i data-feather="x" aria-hidden="true" class="tx-white wd-15"></i>
					</button>
				</div>
				<div class="toast-body bg-danger tx-white">
					<ul id="errorMessage" style="list-style-type: none; padding-inline-start: 0px">
						@if ($errors->any()) @foreach ($errors->all() as $error)
						<li><i class="mg-r-10"></i> {{ $error }}</li>
						@endforeach @endif
					</ul>
				</div>
			</div>
		</div>

		<div class="modal effect-scale" id="prompt-banner-error" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalCenterTitle">Failed</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p id="bannerErrorMessage"></p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
							Close
						</button>
					</div>
				</div>
			</div>
		</div>
		<script src="{{ asset('/js/app.js') }}"></script>
		<script src="{{env('APP_URL')}}/assets/lib/jquery/jquery.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/feather-icons/feather.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>

		<!--Put your external scripts here -->

		<script src="{{env('APP_URL')}}/assets/js/dashforge.js"></script>
		<script src="{{env('APP_URL')}}/assets/js/dashforge.aside.js"></script>
		<script src="{{env('APP_URL')}}/assets/js/dashforge.sampledata.js"></script>

		<script src="{{env('APP_URL')}}/assets/lib/bselect/dist/js/bootstrap-select.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/bselect/dist/js/i18n/defaults-en_US.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/owl.carousel/owl.carousel.js"></script>
		<script src="{{env('APP_URL')}}/assets/js/file-upload-validation.js"></script>
		<!-- <script src="{{env('APP_URL')}}/assets/js/FileSaver.min.js"></script> -->

		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/toastr.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapes.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-pkurg-bootstrap4-plugin.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-preset-webpage.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-lory-slider.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-tabs.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-custom-code.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-touch.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-parser-postcss.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-tooltip.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-tui-image-editor.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-typed.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-style-bg.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/fabric.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/tui-code-snippet.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/tui-color-picker.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/tui-image-editor.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-style-gradient.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-plugin-ckeditor.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-plugin-export.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/grapesjs-blocks-bootstrap4.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/grapesjs/b4bulder-custom-blocks.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/ckeditor/ckeditor.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/ckeditor/adapters/jquery.js"></script>


		@yield('pagejs')
		<script>
			let saveFile = () => {
				// Get the data from each element on the form.
				const cssCode = editor.getCss();
				const htmlCode = editor.getHtml();

				// This variable stores all the data.
				let data = cssCode + " \r\n " + htmlCode;

				// Write data in 'Output.txt' .
				fs.writeFile("Output.txt", data, (err) => {
					// In case of a error throw err.
					if (err) throw err;
				});
			};
		</script>
</body>

</html>