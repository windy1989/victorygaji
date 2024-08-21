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
                                <h4 class="card-title">Daftar {{ $title }}</h4>
                                <div>	
                                    <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalCreate">Tambah Baru</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="customer-datatable" class="display nowrap" style="min-width: 100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Kode</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Pemilik</th>
                                                <th>PIC</th>
                                                <th>NIK Pemilik</th>
                                                <th>Perusahaan</th>
                                                <th>No.Akta Pendirian</th>
                                                <th>Alamat</th>
                                                <th>Kota</th>
                                                <th>Gender</th>
                                                <th>Telepon</th>
                                                <th>Tipe</th>
                                                <th>Keterangan</th>
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
                        <form>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Kode Pelanggan</label>
                                    <input type="hidden" id="temp" name="temp">
                                    <input type="text" class="form-control" placeholder="Kode Pelanggan" id="code" name="code">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Nama</label>
                                    <input type="text" class="form-control" placeholder="Nama" id="name" name="name">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" placeholder="Email" id="email" name="name">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Nama Pemilik</label>
                                    <input type="text" class="form-control" placeholder="Nama Pemilik" id="owner_name" name="owner_name">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Nama PIC</label>
                                    <input type="text" class="form-control" placeholder="Nama PIC" id="pic" name="pic">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Perusahaan</label>
                                    <input type="text" class="form-control" placeholder="Perusahaan" id="company_name" name="company_name">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">No. Akta Pendirian</label>
                                    <input type="text" class="form-control" placeholder="No. Akta Pendirian" id="document_no" name="document_no">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Alamat</label>
                                    <input type="text" class="form-control" placeholder="Alamat" id="address" name="address">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Kota</label>
                                    <input type="text" class="form-control" placeholder="Alamat" id="city" name="city">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Gender</label>
                                    <input type="text" class="form-control" placeholder="Alamat" id="city" name="city">
                                </div>
                                <div class="mb-3 col-md-6 mb-0">
                                    <label class="radio-inline me-3"><input type="radio" name="optradio" class="form-check-input"> Option 1</label>
                                    <label class="radio-inline me-3"><input type="radio" name="optradio" class="form-check-input"> Option 2</label>
                                    <label class="radio-inline me-3"><input type="radio" name="optradio" class="form-check-input"> Option 3</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">State</label>
                                    <select id="inputState" class="default-select form-control wide">
                                        <option selected>Choose...</option>
                                        <option>Option 1</option>
                                        <option>Option 2</option>
                                        <option>Option 3</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Zip</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label">
                                        Check me out
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Sign in</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>