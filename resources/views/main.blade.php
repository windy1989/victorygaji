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
                        
                    </div>
                    {{-- <div class="card-header d-block">
                        <h4 class="card-title">Daftar Payroll Anda</h4>
                        <p class="m-0 subtitle">Silahkan tekan tombol download untuk mengunduh dalam bentuk PDF.</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dashboard-datatable" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Rekening</th>
                                        <th>Bulan</th>
                                        <th>Jabatan</th>
                                        <th>Transfer</th>
                                        <th>Tgl.Proses</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div> --}}
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