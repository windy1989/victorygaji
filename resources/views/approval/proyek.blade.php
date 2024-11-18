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
                                Nomor Proyek : {{ $data->lookable->code }}<br>
                                Dibuat oleh : {{ $data->lookable->user->nama }}<br>
                                Customer : {{ $data->lookable->customer->name }}<br>
                                Nama Proyek : {{ $data->lookable->name }}<br>
                                No. Proyek : {{ $data->lookable->project_no }}<br>
                                Tgl. Proyek : {{ date('d/m/Y',strtotime($data->lookable->post_date)) }}<br>
                                Lokasi : {{ $data->lookable->location }}<br>
                                Kota/Kab : {{ $data->lookable->region->name }}<br>
                                Tipe Proyek : {{ $data->lookable->projectType->name }}<br>
                                Peruntukan : {{ $data->lookable->purpose->name }}<br>
                                Keterangan Peruntukan : {{ $data->lookable->purpose_note }}<br>
                                Pengerjaan : {{ $data->lookable->working_days }}<br>
                                Tgl. Mulai pengerjaan : {{ date('d/m/Y',strtotime($data->lookable->start_date)) }}<br>
                                Tgl. Selesai pengerjaan : {{ date('d/m/Y',strtotime($data->lookable->end_date)) }}<br>
                                No. Dokumen Andalalin : {{ $data->lookable->andalalin_document_no }}<br>
                                No. Surat Kuasa : {{ $data->lookable->power_letter_no }}<br>
                                PIC : {{ $data->lookable->pic_name }}<br>
                                No.Kontak PIC : {{ $data->lookable->pic_no }}<br>
                                Nilai Proyek : {{ number_format($data->lookable->cost,2,',','.') }}<br>
                                Termin : {{ $data->lookable->termin }}<br>
                                Keterangan : {{ $data->lookable->note }}<br>
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