<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- container starts -->
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ env('APP_NAME') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ Str::title(str_replace('_',' ',Request::segment(1))) }}</a></li>
            </ol>
        </div>
        <!-- row -->
        <!-- Row starts -->
        <div class="row">
            <!-- Column starts -->
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-block">
                        <h4 class="card-title">Selamat Datang!</h4>
                        <p class="m-0 subtitle">Aplikasi Manajemen Proyek V.24.01</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 m-b30">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" value="" name="nama" id="nama" disabled>
                            </div>
                            <div class="col-sm-6 m-b30">
                                <label class="form-label">NIK</label>
                                <input type="text" class="form-control" value="" name="nik" id="nik" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column ends -->
        </div>
        <!-- Row ends -->
    </div>
    <!-- container ends -->
</div>
<!--**********************************
        Content body end
    ***********************************-->