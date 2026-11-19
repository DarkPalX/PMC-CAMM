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
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>CAMM</title>
	<link rel="shortcut icon" type="image/x-icon"  href="{{env('APP_URL')}}/assets/images/favicon.png" />

	<!-- vendor css -->
	<link href="{{env('APP_URL')}}/assets/lib/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
	<link href="{{env('APP_URL')}}/assets/lib/ionicons/css/ionicons.min.css" rel="stylesheet" />
	<link href="{{env('APP_URL')}}/assets/lib/jqvmap/jqvmap.min.css" rel="stylesheet" />

	<!-- DashForge CSS -->
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/css/dashforge.css" />
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/css/dashforge.dashboard.css" />
	
    <link href="{{env('APP_URL')}}/assets/css/dashforge.auth.css" rel="stylesheet">
    <link href="{{env('APP_URL')}}/assets/css/custom-admin.css" rel="stylesheet">
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vue-file-agent@latest/dist/vue-file-agent.css" /> -->
	<link rel="stylesheet" href="{{env('APP_URL')}}/assets/primeicons-master/primeicons.css" />

	@yield('pagecss')
</head>

<body id="scroll1">

	
<div id="app">
			@yield('content')
		</div>



		<script src="{{ asset('/js/app.js') }}"></script>
		<script src="{{env('APP_URL')}}/assets/lib/jquery/jquery.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/feather-icons/feather.min.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>

		<!--Put your external scripts here -->

		<!-- <script src="{{env('APP_URL')}}/assets/js/dashforge.js"></script>
		<script src="{{env('APP_URL')}}/assets/js/dashforge.aside.js"></script>
		<script src="{{env('APP_URL')}}/assets/js/dashforge.sampledata.js"></script> -->

		<script src="{{env('APP_URL')}}/assets/lib/bselect/dist/js/bootstrap-select.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/bselect/dist/js/i18n/defaults-en_US.js"></script>
		<script src="{{env('APP_URL')}}/assets/lib/owl.carousel/owl.carousel.js"></script>
		<script src="{{env('APP_URL')}}/assets/js/file-upload-validation.js"></script>
		<!-- <script src="{{env('APP_URL')}}/assets/js/FileSaver.min.js"></script> -->
		<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/mocha/1.0.2/package/lib/browser/fs.js"></script> -->

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
<!-- <script src="https://cdn.jsdelivr.net/npm/vue-file-agent@latest/dist/vue-file-agent.umd.js"></script> -->
		
		<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js"></script> -->
		<!-- <script src="{{env('APP_URL')}}/assets/script.js"></script> -->
		
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