        <!--**********************************
            Content body start
        ***********************************-->
        
		<style>
			.modal-body{
				height: 75vh;
				overflow-y: auto;
			}
            @media (min-width: 500px){
                #letter-agreement-datatable td:nth-of-type(1), #letter-agreement-datatable td:nth-of-type(2), #letter-agreement-datatable td:nth-last-of-type(1) {
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
                                    <button type="button" class="btn btn-primary mb-2" onclick="loadDataTableLetterAgreement();">Refresh</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="letter-agreement-datatable" class="display nowrap" style="min-width: 100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Kode Dokumen</th>
                                                <th>Pengguna</th>
                                                <th>No.Proyek</th>
                                                <th>Tgl.Post</th>
                                                <th>Nama TTD</th>
                                                <th>Alamat TTD</th>
                                                <th>Posisi/Jabatan</th>
                                                <th>Telepon</th>
                                                <th>Atas Nama</th>
                                                <th>Jenis Pembangunan</th>
                                                <th>Nama Pembangun</th>
                                                <th>Lokasi Persil</th>
                                                <th>Luas Lahan</th>
                                                <th>Luas Bangunan</th>
                                                <th>Desa/Kelurahan</th>
                                                <th>Kecamatan</th>
                                                <th>Kabupaten/Kota</th>
                                                <th>Provinsi</th>
                                                <th>Status Jalan</th>
                                                <th>Nominal Termin 1</th>
                                                <th>Nominal Termin 2</th>
                                                <th>Nominal Termin 3</th>
                                                <th>Est.Tgl. Mulai Pekerjaan</th>
                                                <th>Est.Tgl. Selesai Pekerjaan</th>
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
                                    <select id="project_id" name="project_id" onchange="getProjectInfoSpk()"></select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Tgl.Post</label>
                                    <input type="date" class="form-control" id="post_date" name="post_date" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Nama Pihak 1</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="name" name="name">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Alamat Pihak 1</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="address" name="address">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Jabatan Pihak 1</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="position" name="position">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Telepon</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="phone" name="phone">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Mewakili Atas Nama</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="name_ref" name="name_ref">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Jenis Pembangunan</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="type_building" name="type_building">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Nama Pembangun</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="name_builder" name="name_builder">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Lokasi Persil</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="persil_location" name="persil_location">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Luas Lahan</label>
                                    <input type="text" class="form-control" placeholder="Luas Lahan" id="land_area" name="land_area" onkeyup="formatRupiahNoMinus(this);" value="0,00">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Luas Bangunan</label>
                                    <input type="text" class="form-control" placeholder="Luas Bangunan" id="building_area" name="building_area" onkeyup="formatRupiahNoMinus(this);" value="0,00">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Desa/Kelurahan</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="subdistrict" name="subdistrict">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Kecamatan</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="district" name="district">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Kabupaten/Kota</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="city" name="city">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Provinsi</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="province" name="province">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Status Jalan</label>
                                    <input type="text" class="form-control" placeholder="Muncul saat cetak" id="road_status" name="road_status">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Tgl.Mulai Pengerjaan</label>
                                    <input type="date" class="form-control" id="estimate_date_start" name="estimate_date_start" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Tgl.Selesai Pengerjaan</label>
                                    <input type="date" class="form-control" id="estimate_date_finish" name="estimate_date_finish" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Keterangan (Internal)</label>
                                    <input type="text" class="form-control" placeholder="Keterangan" id="note" name="note">
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
                                                    <th><strong>Tipe</strong></th>
                                                    <th><strong>Hapus</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody id="body-payment">
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
                        <td><input type="number" class="form-control" name="arr_termin[]" value="` + no + `"></td>
                        <td><input type="text" class="form-control" name="arr_percentage[]" value="0,00" onkeyup="formatRupiahNoMinus(this);"></td>
                        <td>
                            <select name="arr_type[]" class="form-control wide">
                                <option value="1">Nilai kontrak dibayarkan pada saat penandatanganan kontrak dan setelah diterimanya invoice.</option>
                                <option value="2">Nilai kontrak dibayarkan pada saat PIHAK KEDUA menyerahkan laporan Analisis Dampak Lalu Lintas yang kondisinya siap disidangkan ke instansi terkait dengan menyertakan Tanda Terima Berkas oleh Dinas terkait.</option>
                                <option value="3">Nilai kontrak dibayarkan saat pekerjaan sudah selesai dan surat rekomendasi Analisis Dampak Lalu Lintas yang diterbitkan instansi terkait sudah terbit.</option>
                            </select>    
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger" onclick="deletePayment(this);">Hapus</button>
                        </td>
                    </tr>
                `);
            }

            function deletePayment(element){
                $(element).closest('tr').remove();
            }
        </script>