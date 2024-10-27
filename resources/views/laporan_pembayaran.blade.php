        <!--**********************************
            Content body start
        ***********************************-->
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
                                <h4 class="card-title">{{ $title }} <i>(Hanya status PROSES & SELESAI yang akan muncul disini)</i></h4>
                                <div>
                                    <button type="button" class="btn btn-primary mb-2" onclick="process();">Proses</button>
                                </div>
                            </div>
                            <div class="card-body" id="report-result" style="width:100%;overflow:auto;">
                                <div class="alert alert-info alert-dismissible fade show">
									<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
									<strong>Info!</strong> Silahkan tekan tombol Proses untuk melihat tunggakan pembayaran proyek.
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                    </button>
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