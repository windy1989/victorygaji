        <!--**********************************
            Content body start
        ***********************************-->
        
		<style>
			.modal-body{
				height: 75vh;
				overflow-y: auto;
			}
            @media (min-width: 500px){
                #leave-datatable td:nth-of-type(1), #leave-datatable td:nth-of-type(2), #leave-datatable td:nth-last-of-type(1) {
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
                                    <button type="button" class="btn btn-primary mb-2" onclick="loadDataTableLeave();">Refresh</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="leave-datatable" class="display nowrap" style="min-width: 100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Kode Dokumen</th>
                                                <th>Pengguna</th>
                                                <th>Karyawan</th>
                                                <th>Tgl.Post</th>
                                                <th>Keterangan</th>
                                                <th>Status</th>
                                                <th>Jumlah Hari</th>
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
                                    <label class="form-label">Tgl.Post</label>
                                    <input type="date" class="form-control" id="post_date" name="post_date" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Keterangan Cuti</label>
                                    <input type="text" class="form-control" placeholder="Keterangan" id="note" name="note">
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-responsive-md">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" class="text-center"><strong>Detail Tanggal</strong></th>
                                                </tr>
                                                <tr>
                                                    <th><strong>#</strong></th>
                                                    <th><strong>Tanggal</strong></th>
                                                    <th><strong>Hapus</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody id="body-leave">
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-center">
                                                        <button type="button" class="btn btn-rounded btn-info" onclick="addLeave();"><span class="btn-icon-start text-info"><i class="fa fa-plus color-info"></i>
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
            function addLeave(){
                let no = $('.row_leave').length + 1;
                $("#body-leave").append(`
                    <tr class="row_leave">
                        <td class="text-center">` + no + `</td>
                        <td><input type="date" class="form-control" name="arr_date[]" value="` + {{ date('Y-m-d') }} + `"></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger" onclick="deleteLeave(this);">Hapus</button>
                        </td>
                    </tr>
                `);
            }

            function deleteLeave(element){
                $(element).closest('tr').remove();
            }
        </script>