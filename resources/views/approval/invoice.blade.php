        <!--**********************************
            Content body start
        ***********************************-->
        
		<style>
			.modal-body{
				height: 75vh;
				overflow-y: auto;
			}
            #invoice-datatable td:nth-of-type(1), #invoice-datatable td:nth-of-type(2), #invoice-datatable td:nth-last-of-type(1) {
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
                    <div class="col-xl-6">
                        <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="card-title">Daftar {{ $title }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="profile-uoloaded-post border-bottom-1 pb-5">
                                    <img src="{{ $data->lookable->attachment() }}" alt="" class="img-fluid w-100 rounded">
                                    <h3 class="text-black">Bukti Bayar Kwitansi No. {{ $data->lookable->receipt_code }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="card-title">Setuju/Tidak</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <textarea class="form-control" placeholder="Keterangan Setuju/Tidak" id="note" name="note"></textarea>
                                    </div>
                                    <div class="col-md-12 text-center mt-2">
                                        <button class="btn btn-success me-2"><span class="me-2"><i class="fa fa-check"></i></span>Setuju</button>
                                        <button class="btn btn-primary"><span class="me-2"><i class="fa fa-times"></i></span>Tidak</button>
                                    </div>
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