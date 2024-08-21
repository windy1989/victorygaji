        <!--**********************************
            Content body start
        ***********************************-->
        
		<style>
			.modal-body{
				height: 75vh;
				overflow-y: auto;
			}
		</style>
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
                        <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="card-title">Daftar Notifikasi Gaji</h4>
                                <div>	
                                    <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#basicModal">Upload Baru</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="payroll-datatable" class="display nowrap" style="min-width: 100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Rekening</th>
                                                <th>Bulan</th>
                                                <th>Jabatan</th>
												<th>Gaji Pokok</th>
                                                <th>Transfer</th>
                                                <th>Tgl.Proses</th>
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
        <div class="modal fade" id="basicModal">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Payroll</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Silahkan download template <b style="font-weight:800;"><a href="{{ url('assets/format/format_import_gaji.xlsx') }}">disini</a></b> dan pastikan tidak ada format accounting pada nilai nominal. Lihat contoh yang ada.
                        </p>
                        <form action="{{ Request::url() }}/create" class="dropzone mt-3" id="dropzone-multiple">
                            @csrf
                        </form>
                        <div id="validation_alert" style="display:none;margin-top:25px;">
													
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="historyModal">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Histori Email Payroll</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body" id="show-history">
					
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>