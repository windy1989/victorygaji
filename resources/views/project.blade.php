        <!--**********************************
            Content body start
        ***********************************-->
        
		<style>
			.modal-body{
				height: 75vh;
				overflow-y: auto;
			}
            #project-datatable td:nth-of-type(1) {
                background-color:rgb(255, 233, 173) !important;
            }
            #project-datatable td:nth-of-type(2) {
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
                                    <label class="form-label">Kode Dokumen</label>
                                    <input type="hidden" id="temp" name="temp">
                                    <input type="text" class="form-control" placeholder="Auto generate" id="code" name="code" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Nama Proyek</label>
                                    <input type="text" class="form-control" placeholder="Nama Proyek" id="name" name="name">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Customer</label>
                                    <select id="customer_id" name="customer_id"></select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Nomor Proyek (Cetak)</label>
                                    <input type="text" class="form-control" placeholder="Nomor Proyek" id="project_no" name="project_no">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Tgl.Pengajuan</label>
                                    <input type="date" class="form-control" id="post_date" name="post_date" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Lokasi/Alamat</label>
                                    <input type="text" class="form-control" placeholder="Lokasi / Alamat" id="location" name="location">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Kota</label>
                                    <select id="region_id" name="region_id"></select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Jenis Proyek</label>
                                    <select id="project_type_id" name="project_type_id"></select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Peruntukan</label>
                                    <select id="purpose_id" name="purpose_id"></select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Keterangan Peruntukan</label>
                                    <input type="text" class="form-control" placeholder="Keterangan Peruntukan" id="purpose_note" name="purpose_note">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Lama Pengerjaan (Hari)</label>
                                    <input type="number" class="form-control" id="working_days" name="working_days" value="0" min="0" step="1" onchange="addDate();">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Tgl.Mulai Pengerjaan</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ date('Y-m-d') }}" onchange="addDate();">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Tgl.Selesai Pengerjaan</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">No.Dokumen Andalalin</label>
                                    <input type="text" class="form-control" placeholder="No.Dokumen Andalalin" id="andalalin_document_no" name="andalalin_document_no">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">No.Surat Kuasa</label>
                                    <input type="text" class="form-control" placeholder="No.Surat Kuasa" id="power_letter_no" name="power_letter_no">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Harga</label>
                                    <input type="text" class="form-control" placeholder="Harga" id="cost" name="cost" onkeyup="formatRupiahNoMinus(this);" value="0,00">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Termin Pembayaran</label>
                                    <input type="number" class="form-control" id="termin" name="termin" value="1" min="1" step="1">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" placeholder="Keterangan" id="note" name="note">
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