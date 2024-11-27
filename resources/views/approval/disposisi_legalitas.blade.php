        <!--**********************************
            Content body start
        ***********************************-->
        <style>
            #dropZone {
                border: 2px dashed #ccc;
            }
            #imagePreview {
                max-width: 20em;
                max-height: 20em;
                min-height: 5em;
                margin: 2px auto;
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
                                @foreach ($data->lookable->documentationDetail as $row)
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
                                            <label class="">Upload Gambar/Sketsa Revisi</label>
                                            <br>
                                            <input type="file" name="file" id="fileInput" accept="image/*" style="display: none;">
                                            <div  class="col m8 s12 " id="dropZone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" style="margin-top: 0.5em;height: 5em;">
                                                Drop image here or <a href="javascript:void(0);" id="uploadLink">upload</a>
                                                <br>
                                            </div>
                                            <a class="waves-effect waves-light cyan btn-small" style="margin-top: 0.5em;margin-left:0.2em" id="clearButton" href="javascript:void(0);">
                                            Clear Image
                                            </a>
                                        </div>
                                        <div class="col-md-12">
                                            <div id="fileName"></div>
                                            <img src="" alt="Preview" id="imagePreview" style="display: none;">
                                        </div>
                                        <div class="col-md-12">
                                            <textarea class="form-control" placeholder="Keterangan Setuju/Tidak" id="note" name="note" rows="5"></textarea>
                                        </div>
                                        <div class="col-md-12 text-center mt-2">
                                            <button class="btn btn-success me-2" onclick="approve('{{ $data->code }}','agree');"><span class="me-2"><i class="fa fa-check"></i></span>Setuju</button>
                                            <button class="btn btn-primary" onclick="approve('{{ $data->code }}','reject');"><span class="me-2"><i class="fa fa-times"></i></span>Tidak</button>
                                        </div>
                                    @else
                                    <div class="col-md-12">
                                        Dokumen ini telah <b>{{ $data->approveStatus() }}</b> oleh anda, pada tanggal <b>{{ date('d/m/Y H:i:s',strtotime($data->approve_date)) }}</b> dengan catatan : <b>{{ $data->approve_note }}</b> dengan bukti / lampiran {!! $data->getFile() !!}.
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
                                                Dokumen ini telah <b>{{ $row->approveStatus() }}</b> oleh <b>{{ $row->toUser->nama }}</b>, pada tanggal <b>{{ date('d/m/Y H:i:s',strtotime($row->approve_date)) }}</b> dengan catatan : <b>{{ $row->approve_note }}</b> dengan bukti / lampiran {!! $row->getFile() !!}.
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
        <script>
            const dropZone = document.getElementById('dropZone');
            const uploadLink = document.getElementById('uploadLink');
            const fileInput = document.getElementById('fileInput');
            const imagePreview = document.getElementById('imagePreview');
            const clearButton = document.getElementById('clearButton');
            const fileNameDiv = document.getElementById('fileName');
            if (typeof(fileInput) != 'undefined' && fileInput != null){
                dropZone.addEventListener('click', () => {
                    fileInput.click();
                });

                fileInput.addEventListener('change', (e) => {
                    handleFile(e.target.files[0]);
                });

                function dragOverHandler(event) {
                    event.preventDefault();
                    dropZone.style.backgroundColor = '#f0f0f0';
                }

                function dropHandler(event) {
                    event.preventDefault();
                    dropZone.style.backgroundColor = '#fff';

                    handleFile(event.dataTransfer.files[0]);
                }

                function handleFile(file) {
                    if (file) {
                    const reader = new FileReader();
                    const fileType = file.type.split('/')[0];
                    const maxSize = 10 * 1024 * 1024;
                    if (file.size > maxSize) {
                        alert('File size exceeds the maximum limit of 10 MB.');
                        return;
                    }

                    reader.onload = () => {

                        fileNameDiv.textContent = 'File uploaded: ' + file.name;

                        if (fileType === 'image') {

                            imagePreview.src = reader.result;
                            imagePreview.style.display = 'inline-block';
                            clearButton.style.display = 'inline-block';
                        } else {

                            imagePreview.style.display = 'none';

                        }
                    };

                    reader.readAsDataURL(file);
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);


                    fileInput.files = dataTransfer.files;

                    }
                }

                clearButton.addEventListener('click', () => {
                    imagePreview.src = '';
                    imagePreview.style.display = 'none';
                    fileInput.value = '';
                    fileNameDiv.textContent = '';
                });

                document.addEventListener('paste', (event) => {
                    const items = event.clipboardData.items;
                    if (items) {
                        for (let i = 0; i < items.length; i++) {
                            if (items[i].type.indexOf('image') !== -1) {
                                const file = items[i].getAsFile();
                                handleFile(file);
                                break;
                            }
                        }
                    }
                });

                function displayFile(fileLink) {
                    const fileType = getFileType(fileLink);

                    fileNameDiv.textContent = 'File uploaded: ' + getFileName(fileLink);

                    if (fileType === 'image') {

                        imagePreview.src = fileLink;
                        imagePreview.style.display = 'inline-block';

                    } else {

                        imagePreview.style.display = 'none';


                        const fileExtension = getFileExtension(fileLink);
                        if (fileExtension === 'pdf' || fileExtension === 'xlsx' || fileExtension === 'docx') {

                            const downloadLink = document.createElement('a');
                            downloadLink.href = fileLink;
                            downloadLink.download = getFileName(fileLink);
                            downloadLink.textContent = 'Download ' + fileExtension.toUpperCase();
                            fileNameDiv.appendChild(downloadLink);
                        }
                    }
                }

                function getFileType(fileLink) {
                    const fileExtension = getFileExtension(fileLink);
                    if (fileExtension === 'jpg' || fileExtension === 'jpeg' || fileExtension === 'png' || fileExtension === 'gif') {
                        return 'image';
                    } else {
                        return 'other';
                    }
                }

                function getFileExtension(fileLink) {
                    return fileLink.split('.').pop().toLowerCase();
                }

                function getFileName(fileLink) {
                    return fileLink.split('/').pop();
                }
            }
        </script>