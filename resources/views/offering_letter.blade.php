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
                                                    <th colspan="5" class="text-center"><strong>Detail Pembayaran</strong></th>
                                                </tr>
                                                <tr>
                                                    <th><strong>#</strong></th>
                                                    <th><strong>Termin</strong></th>
                                                    <th><strong>Prosentase</strong></th>
                                                    <th><strong>Keterangan</strong></th>
                                                    <th><strong>Hapus</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody id="body-payment">
                                                <tr>
                                                    <td colspan="5" class="text-center" id="empty-termin">Silahkan tambahkan termin.</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        <button type="button" class="btn btn-rounded btn-info" onclick="addTermin();"><span class="btn-icon-start text-info"><i class="fa fa-plus color-info"></i>
                                                        </span>Tambah</button>
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
        <script>
            function addTermin(){
                if($('#empty-termin').length > 0){
                    $('#empty-termin').remove();
                }
                let no = $('.row_payment').length + 1;
                $("#body-payment").append(`
                    <tr class="row_payment">
                        <td class="text-center">` + no + `</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger" onclick="deletePayment(this);">Hapus <span class="btn-icon-end">
								<i class="fas fa-times"></i></span>
                            </button>
                        </td>
                    </tr>
                `);
            }

            function deletePayment(element){
                $(element).closest('tr').remove();
                alert($('#body-payment tr').length);
                if($("#body-payment").children().length == 0){
                    $("#body-payment").append(`
                        <tr>
                            <td colspan="5" class="text-center" id="empty-termin">Silahkan tambahkan termin.</td>
                        </tr>
                    `);
                }
            }
        </script>