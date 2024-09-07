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
                                <h4 class="card-title">Daftar Pengguna</h4>
                                <div>	
                                    <button type="button" class="btn btn-secondary mb-2" style="margin-right:10px;" data-bs-toggle="modal" data-bs-target="#modalCreate">Tambah Baru</button>
                                    <button type="button" class="btn btn-primary mb-2" onclick="loadDataTableBank();">Refresh</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="bank-datatable" class="display nowrap" style="min-width: 100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Kode</th>
                                                <th>Atas Nama</th>
                                                <th>Nomor Rekening</th>
                                                <th>Nama Bank</th>
                                                <th>Cabang</th>
                                                <th>Status</th>
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

        <div class="modal fade" id="modalCreate">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Tambah / Edit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="validation_alert" style="display:none;margin-top:25px;"></div>
                        <form id="formData">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Kode</label>
                                    <input type="hidden" id="temp" name="temp">
                                    <input type="text" class="form-control" placeholder="Kode" id="code" name="code">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Atas Nama</label>
                                    <input type="text" class="form-control" placeholder="Atas Nama" id="name" name="name">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">No. Rekening</label>
                                    <input type="text" class="form-control" placeholder="No. Rekening" id="no" name="no">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Nama Bank</label>
                                    <input type="text" class="form-control" placeholder="Nama Bank" id="bank" name="bank">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Cabang</label>
                                    <input type="text" class="form-control" placeholder="Cabang" id="branch" name="branch">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Status</label>
                                    <div class="form-check custom-checkbox mb-3 checkbox-success">
                                        <input type="checkbox" class="form-check-input" checked="" id="status" name="status" required="" value="1">
                                        <label class="form-check-label" for="status">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="save();">Simpan</button>
                    </div>
                </div>
            </div>
        </div>