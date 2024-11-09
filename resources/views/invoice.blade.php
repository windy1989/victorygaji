        <!--**********************************
            Content body start
        ***********************************-->
        
		<style>
			.modal-body{
				height: 75vh;
				overflow-y: auto;
			}
            @media (min-width: 500px){
                #invoice-datatable td:nth-of-type(1), #invoice-datatable td:nth-of-type(2), #invoice-datatable td:nth-last-of-type(1) {
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
                                    <button type="button" class="btn btn-primary mb-2" onclick="loadDataTableInvoice();">Refresh</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-secondary solid alert-square"><a href="#" class="btn btn-info btn-sm content-icon"><i class="fa fa-print"></i></a> : tombol untuk cetak Invoice. <a href="#" class="btn btn-light btn-sm content-icon"><i class="fa fa-print"></i></a> : tombol untuk cetak Kwitansi.</div>
                                <div class="table-responsive">
                                    <table id="invoice-datatable" class="display nowrap" style="min-width: 100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Kode Dokumen</th>
                                                <th>Kode Kwitansi</th>
                                                <th>Pengguna</th>
                                                <th>Terima dari</th>
                                                <th>No.Proyek</th>
                                                <th>Bank</th>
                                                <th>Tgl.Post</th>
                                                <th>Tgl.Bayar</th>
                                                <th>PPN(%)</th>
                                                <th>Termasuk PPN</th>
                                                <th>PPh(%)</th>
                                                <th>Total</th>
                                                <th>PPN(Rp)</th>
                                                <th>Total Setelah PPN</th>
                                                <th>PPh(Rp)</th>
                                                <th>Nominal Bayar</th>
                                                <th>Termin Ke</th>
                                                <th>Keterangan/Catatan</th>
                                                <th>Status</th>
                                                <th>Bukti Bayar</th>
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
                                    <label class="form-label">Ditujukan Kepada / Terima Dari</label>
                                    <input type="text" class="form-control" placeholder="Ditujukan Kepada / Terima Dari" id="receive_from" name="receive_from">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Proyek</label>
                                    <select id="project_id" name="project_id" onchange="getProjectInfo();"></select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Bank</label>
                                    <select id="bank_id" name="bank_id"></select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Tgl.Post</label>
                                    <input type="date" class="form-control" id="post_date" name="post_date" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">PPN (%) / 0 untuk non-PPN</label>
                                    <input type="text" class="form-control" placeholder="Persen PPN" id="percent_tax" name="percent_tax" onkeyup="formatRupiahNoMinus(this);countInvoice();" value="0">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Termasuk PPN / Tidak</label>
                                    <select id="include_tax" name="include_tax" class="form-control wide" onchange="countInvoice();">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">PPh (%) / 0 untuk non-PPh</label>
                                    <input type="text" class="form-control" placeholder="Persen PPN" id="percent_wtax" name="percent_wtax" onkeyup="formatRupiahNoMinus(this);countInvoice();" value="0">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Total</label>
                                    <input type="text" class="form-control" placeholder="Total" id="total" name="total" onkeyup="formatRupiahNoMinus(this);countInvoice();" value="0,00">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">PPN (Non-Editable)</label>
                                    <input type="text" class="form-control" placeholder="Total" id="tax" name="tax" onkeyup="formatRupiahNoMinus(this);" value="0,00" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Total Stlh PPN (Non-Editable)</label>
                                    <input type="text" class="form-control" placeholder="Total" id="total_after_tax" name="total_after_tax" onkeyup="formatRupiahNoMinus(this);" value="0,00" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">PPh (Non-Editable)</label>
                                    <input type="text" class="form-control" placeholder="Total" id="wtax" name="wtax" onkeyup="formatRupiahNoMinus(this);" value="0,00" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Grandtotal (Non-Editable)</label>
                                    <input type="text" class="form-control" placeholder="Nominal" id="nominal" name="nominal" onkeyup="formatRupiahNoMinus(this);" value="0,00" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Pembayaran Ke</label>
                                    <input type="number" class="form-control" id="termin_no" name="termin_no" value="1" min="1" step="1">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" placeholder="Keterangan" id="note" name="note">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Nominal Proyek</label>
                                    <div><b id="nominal_project">0,00</b></div>
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

        <div class="modal fade" id="modalReceipt">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Kwitansi Invoice <b id="modal-receipt-title"></b></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info solid alert-square "><strong>Info!</strong> Hati-hati, informasi akan menimpa data yang sudah ada..</div>
                        <div id="validation_alert_receipt" style="display:none;margin-top:15px;"></div>
                        <form id="formDataReceipt">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Kode Kwitansi</label>
                                    <input type="hidden" id="tempReceipt" name="tempReceipt">
                                    <input type="text" class="form-control" placeholder="Kode Kwitansi" id="code_receipt" name="code_receipt">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Tgl.Bayar</label>
                                    <input type="date" class="form-control" id="pay_date" name="pay_date" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Bukti Bayar</label>
                                    <input class="form-control" type="file" id="fileReceipt" name="fileReceipt" accept="image/png, image/jpeg, image/jpg" onchange="checkFileReceiptMax(this)">
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label class="form-label">Preview File</label>
                                    <div id="previewFileReceipt"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="saveReceipt();">Simpan</button>
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
            function countInvoice(){
                let percentTax = parseFloat($('#percent_tax').val().replaceAll(".", "").replaceAll(",",".")), percentWtax = parseFloat($('#percent_wtax').val().replaceAll(".", "").replaceAll(",",".")), total = parseFloat($('#total').val().replaceAll(".", "").replaceAll(",",".")), tax = 0, wtax = 0, grandtotal = 0, total_after_tax = 0;
                if(percentTax > 0){
                    if($('#include_tax').val() == '1'){
                        total = total / (1 + (percentTax / 100));
                    }
                    total = Math.round(total * 100) / 100;
                    tax =  Math.round((total * (percentTax / 100)) * 100) / 100;
                }
                if(percentWtax > 0){
                    wtax = Math.round((total * (percentWtax / 100)) * 100) / 100;
                }
                total_after_tax = total + tax;
                grandtotal = total_after_tax - wtax;
                if($('#include_tax').val() == '1'){
                    $('#total').val(
                        (total >= 0 ? '' : '-') + formatRupiahIni(total.toFixed(2).toString().replace('.',','))
                    );
                }
                $('#tax').val(
                    (tax >= 0 ? '' : '-') + formatRupiahIni(tax.toFixed(2).toString().replace('.',','))
                );
                $('#total_after_tax').val(
                    (total_after_tax >= 0 ? '' : '-') + formatRupiahIni(total_after_tax.toFixed(2).toString().replace('.',','))
                );
                $('#wtax').val(
                    (wtax >= 0 ? '' : '-') + formatRupiahIni(wtax.toFixed(2).toString().replace('.',','))
                );
                $('#nominal').val(
                    (grandtotal >= 0 ? '' : '-') + formatRupiahIni(grandtotal.toFixed(2).toString().replace('.',','))
                );
            }

            function formatRupiahIni(angka){
                var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
                split   		= number_string.split(','),
                sisa     		= split[0].length % 3,
                rupiah     		= split[0].substr(0, sisa),
                ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

                if(ribuan){
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

                return rupiah;
            }
        </script>