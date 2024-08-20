<!DOCTYPE html>

<html lang="en">

<head>
   <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="DexignLab">
	<meta name="robots" content="" >
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	
	<!-- Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- PAGE TITLE HERE -->
	<title>{{ env('APP_NAME') }} | 401</title>
	
	<!-- Favicon icon -->
	<link rel="shortcut icon" type="image/ico" href="{{ url('assets/images/favicon.ico') }}">
    <link href="{{ url('assets/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ url('assets/vendor/toastr/css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ url('assets/vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('assets/vendor/select2/css/select2.min.css') }}">
    <link href="{{ url('assets/vendor/dropzone/dist/dropzone.css') }}" rel="stylesheet">
    <link href="{{ url('assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ url('assets/css/style.css?v=9') }}" rel="stylesheet">

</head>

<body class="vh-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-5">
                    <div class="form-input-content text-center error-page">
                        <h1 class="error-text fw-bold">401</h1>
                        <h4><i class="fa fa-thumbs-down text-danger"></i> Anda tidak memiliki akses</h4>
                        <p>Silahkan hubungi owner untuk dibukakan akses</p>
						<div>
                            <a class="btn btn-primary" href="../dashboard">Kembali ke Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Required vendors -->
    
    <script src="{{ url('assets/vendor/global/global.min.js') }}"></script>

    <!-- Datatable -->
    <script src="{{ url('assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/vendor/toastr/js/toastr.min.js') }}"></script>
    <script src="{{ url('assets/vendor/dropzone/dist/dropzone.js?v=0') }}"></script>
    <script src="{{ url('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>

	<script src="{{ url('assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ url('assets/vendor/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ url('assets/vendor/jquery-nice-select/js/jquery.nice-select.js') }}"></script>

    <script src="{{ url('assets/js/custom.min.js') }}"></script>
	<script src="{{ url('assets/js/dlabnav-init.js') }}"></script>
	<script src="{{ url('assets/js/all.js?v=21') }}"></script>
  
</body>
</html>