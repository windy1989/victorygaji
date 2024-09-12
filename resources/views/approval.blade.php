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
                        <h4 class="card-title">Daftar {{ $title }}</h4>
                        <p class="m-0 subtitle">Silahkan tekan tombol download untuk mengunduh dalam bentuk PDF.</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="approval-datatable" class="display nowrap" style="min-width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tgl.Pengajuan</th>
                                        <th>Dari</th>
                                        <th>Catatan</th>
                                        <th>Status</th>
                                        <th>Level</th>
                                        <th>Tgl.Proses</th>
                                        <th>Kode Dokumen</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
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