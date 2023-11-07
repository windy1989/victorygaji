
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
   <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="DexignLab">
	<meta name="robots" content="" >
	
	<!-- Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- PAGE TITLE HERE -->
	<title>Login | {{ env('APP_NAME') }}</title>
	
	<!-- Favicon icon -->
	<link rel="shortcut icon" type="image/ico" href="{{ url('assets/images/favicon.ico') }}">
    <link href="{{ url('assets/vendor/toastr/css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ url('assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ url('assets/css/style.css') }}" rel="stylesheet">

</head>

<body class="vh-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
									<div class="text-center mb-3">
										<a href="{{ url('/') }}" class="brand-logo">
											<img src="{{ url('assets/images/logovictory_2.png') }}" width="70%" style="margin:auto;">
										</a>
									</div>
                                    <h4 class="text-center mb-4">Sign in to your account</h4>
                                    <form class="login-form" id="login_form">
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>NIK</strong></label>
                                            <input type="text" class="form-control" value="" name="nik" id="nik">
                                        </div>
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>Password</strong></label>
                                            <input type="password" class="form-control" value="" name="password" id="password">
                                        </div>
                                        <div class="row d-flex justify-content-between mt-4 mb-2">
                                            <div class="mb-3">
                                               <div class="form-check custom-checkbox ms-1">
													<input type="checkbox" class="form-check-input" id="show_password">
													<label class="form-check-label" for="show_password">Show Password</label>
												</div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
	
    <script>
        var offline = 1;
    </script>

    <script src="{{ url('assets/vendor/global/global.min.js') }}"></script>

    <script src="{{ url('assets/vendor/dropzone/dist/dropzone.js') }}"></script>
    <script src="{{ url('assets/vendor/toastr/js/toastr.min.js') }}"></script>
    
	<script src="{{ url('assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ url('assets/js/custom.min.js') }}"></script>
    <script src="{{ url('assets/js/dlabnav-init.js') }}"></script>
    <script src="{{ url('assets/js/all.js') }}"></script>
</body>
</html>