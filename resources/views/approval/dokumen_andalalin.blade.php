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
                    <div class="col-xl-6">
                        <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="card-title">Daftar {{ $title }}</h4>
                            </div>
                            <div class="card-body">
                                @foreach ($data->lookable->andalalinDetail as $row)
                                    <div class="profile-uoloaded-post border-bottom-1 pb-5">
                                        <div>
                                            <iframe src="https://docs.google.com/gview?url={{ $row->attachment() }}&embedded=true" style="width:100%; height:500px;" frameborder="0"></iframe>
                                        </div>
                                        <h3 class="text-black">Nama File : {{ $row->name }}</h3>
                                    </div>
                                @endforeach
                                
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
                                    @if($data->approve_status == '1')
                                        <div class="col-md-12">
                                            <textarea class="form-control" placeholder="Keterangan Setuju/Tidak" id="note" name="note" rows="5"></textarea>
                                        </div>
                                        <div class="col-md-12 text-center mt-2">
                                            <button class="btn btn-success me-2" onclick="approve('{{ $data->code }}','agree');"><span class="me-2"><i class="fa fa-check"></i></span>Setuju</button>
                                            <button class="btn btn-primary" onclick="approve('{{ $data->code }}','reject');"><span class="me-2"><i class="fa fa-times"></i></span>Tidak</button>
                                        </div>
                                    @else
                                    <div class="col-md-12">
                                        Dokumen ini telah <b>{{ $data->approveStatus() }}</b> oleh anda, pada tanggal <b>{{ date('d/m/Y H:i:s',strtotime($data->approve_date)) }}</b> dengan catatan : <b>{{ $data->approve_note }}</b>.
                                    </div>
                                    @endif
                                    @php
                                        $anotherApprover = $data->approvalExceptMe(session('bo_id'));
                                    @endphp
                                    @if(count($anotherApprover) > 0)
                                        <div class="col-md-12">
                                            List approver lainnya : 
                                            <ol>
                                            @foreach ($anotherApprover as $row)
                                                Dokumen ini telah <b>{{ $row->approveStatus() }}</b> oleh <b>{{ $row->toUser->nama }}</b>, pada tanggal <b>{{ date('d/m/Y H:i:s',strtotime($row->approve_date)) }}</b> dengan catatan : <b>{{ $row->approve_note }}</b>.
                                            @endforeach
                                            </ol>
                                        </div>
                                    @endif
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