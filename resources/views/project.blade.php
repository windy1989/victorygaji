        <!--**********************************
            Content body start
        ***********************************-->
        
		<style>
			.modal-body{
				height: 75vh;
				overflow-y: auto;
			}
            #customer-datatable td:nth-of-type(1) {
                background-color:rgb(255, 233, 173) !important;
            }
            #customer-datatable td:nth-of-type(2) {
                background-color:rgb(255, 233, 173) !important;
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
                                    <button type="button" class="btn btn-secondary mb-2" style="margin-right:10px;" data-bs-toggle="modal" data-bs-target="#modalCreate">Tambah Baru</button>
                                    <button type="button" class="btn btn-primary mb-2" onclick="loadDataTableProject();">Refresh</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="project-datatable" class="display nowrap" style="min-width: 100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Kode</th>
                                                <th>Pengguna</th>
                                                <th>Customer</th>
                                                <th>Nama</th>
                                                <th>No.Proyek</th>
                                                <th>Tgl.Pengajuan</th>
                                                <th>Lokasi</th>
                                                <th>Kota</th>
                                                <th>Tipe Proyek</th>
                                                <th>Peruntukan</th>
                                                <th>Catatan Peruntukan</th>
                                                <th>Lama Pengerjaan (Hari)</th>
                                                <th>Tgl.Mulai Pengerjaan</th>
                                                <th>Tgl.Selesai Pengerjaan</th>
                                                <th>No.Surat Andalalin</th>
                                                <th>No.Surat Kuasa</th>
                                                <th>Biaya</th>
                                                <th>Termin</th>
                                                <th>Keterangan/Catatan</th>
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
                                    <label class="form-label">Kode Pelanggan</label>
                                    <input type="hidden" id="temp" name="temp">
                                    <input type="text" class="form-control" placeholder="Auto generate" id="code" name="code" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Nama</label>
                                    <input type="text" class="form-control" placeholder="Nama" id="name" name="name">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" placeholder="Email" id="email" name="email">
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
                                    <label class="form-label">KTP Pemilik</label>
                                    <input type="text" class="form-control" placeholder="NIK/Identitas Pemilik" id="owner_id_card" name="owner_id_card">
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
                                    <input type="text" class="form-control" placeholder="Kota" id="city" name="city">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Gender</label>
                                    <div class="mb-3 mb-0">
                                        <label class="radio-inline me-3"><input type="radio" name="gender" class="form-check-input" value="1" checked> Laki-laki</label>
                                        <label class="radio-inline me-3"><input type="radio" name="gender" class="form-check-input" value="2"> Perempuan</label>
                                        <label class="radio-inline me-3"><input type="radio" name="gender" class="form-check-input" value="3"> Lain-lain</label>
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Telepon</label>
                                    <input type="text" class="form-control" placeholder="6281XXXXX" id="phone" name="phone">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Tipe Perusahaan</label>
                                    <select id="type_body" name="type_body" class="form-control wide">
                                        <option value="1">PT</option>
                                        <option value="2">CV</option>
                                        <option value="3">Perorangan</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" placeholder="Keterangan" id="note" name="note">
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