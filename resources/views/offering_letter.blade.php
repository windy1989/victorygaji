        <!--**********************************
            Content body start
        ***********************************-->
        
		<style>
			.modal-body{
				height: 75vh;
				overflow-y: auto;
			}
            @media (min-width: 500px){
                #offering-letter-datatable td:nth-of-type(1), #offering-letter-datatable td:nth-of-type(2), #offering-letter-datatable td:nth-last-of-type(1) {
                    background-color:rgb(255, 233, 173) !important;
                }
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
                                    <button type="button" class="btn btn-primary mb-2" onclick="loadDataTableOfferingLetter();">Refresh</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="offering-letter-datatable" class="display nowrap" style="min-width: 100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Kode Dokumen</th>
                                                <th>Pengguna</th>
                                                <th>No.Proyek</th>
                                                <th>Tgl.Post</th>
                                                <th>Ditujukan Kepada</th>
                                                <th>Jenis Bangunan</th>
                                                <th>Lokasi Bangunan</th>
                                                <th>Jalan</th>
                                                <th>Include PNBP</th>
                                                <th>Include Pajak</th>
                                                <th>Catatan Internal</th>
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
                                    <input type="text" class="form-control" placeholder="Kode Dokumen" id="code" name="code">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Proyek</label>
                                    <select id="project_id" name="project_id"></select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Ditujukan Kepada</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="to_name" name="to_name">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Tgl.Post</label>
                                    <input type="date" class="form-control" id="post_date" name="post_date" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Tipe Bangunan</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="type_building" name="type_building">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Lokasi Bangunan</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="location_building" name="location_building">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Tipe Jalan</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="type_road" name="type_road">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Keterangan (Internal)</label>
                                    <input type="text" class="form-control" placeholder="Keterangan" id="note" name="note">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Include PNBP?</label>
                                    <select id="is_pnbp" name="is_pnbp" class="form-control wide">
                                        <option value="1">Ya</option>
                                        <option value="2">Tidak</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Include Pajak?</label>
                                    <select id="is_include_tax" name="is_include_tax" class="form-control wide">
                                        <option value="1">Ya</option>
                                        <option value="2">Tidak</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-responsive-md">
                                            <thead>
                                                <tr>
                                                    <th style="width:80px;"><strong>#</strong></th>
                                                    <th><strong>PATIENT</strong></th>
                                                    <th><strong>DR NAME</strong></th>
                                                    <th><strong>DATE</strong></th>
                                                    <th><strong>STATUS</strong></th>
                                                    <th><strong>PRICE</strong></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>01</strong></td>
                                                    <td>Mr. Bobby</td>
                                                    <td>Dr. Jackson</td>
                                                    <td>01 August 2020</td>
                                                    <td><span class="badge light badge-success">Successful</span></td>
                                                    <td>$21.56</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-success light sharp" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                            </button>
                                                            <div class="dropdown-menu" style="">
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>02</strong></td>
                                                    <td>Mr. Bobby</td>
                                                    <td>Dr. Jackson</td>
                                                    <td>01 August 2020</td>
                                                    <td><span class="badge light badge-danger">Canceled</span></td>
                                                    <td>$21.56</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-danger light sharp" data-bs-toggle="dropdown">
                                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>03</strong></td>
                                                    <td>Mr. Bobby</td>
                                                    <td>Dr. Jackson</td>
                                                    <td>01 August 2020</td>
                                                    <td><span class="badge light badge-warning">Pending</span></td>
                                                    <td>$21.56</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-warning light sharp" data-bs-toggle="dropdown">
                                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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

        <div class="modal fade" id="modalDetail">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Informasi <b id="modal-detail-title"></b></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body" id="modal-detail-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>