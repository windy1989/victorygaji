Dropzone.options.dropzoneMultiple = {
    paramName: "file",
    maxFilesize: 200000,
    maxFiles: 1,
    /* autoProcessQueue: false, */
    autoQueue: true,
	timeout: 0,
    addRemoveLinks : true,
    acceptedFiles: ".xls,.xlsx",
    init: function() {
        dropzoneMultiple = this;
        this.on("addedfiles", function(files) {
            
        });
        this.on("sending", function(file, xhr, formData){
            
        });
        this.on("success", function(file, responseText) {
            $('#validation_alert').empty().hide();
            if(responseText.status == '200'){
                successMessage(responseText.message);
                loadDataTablePayroll();
            }else if(responseText.status == '422'){
                $('#validation_alert').show();
                $.each(responseText.error, function(i, val) {
                    $.each(val, function(i, val) {
                       $('#validation_alert').append(`
                            <div class="alert alert-danger solid alert-rounded "> ` + val + `</div>
                       `);
                    });
                });
            }else if(responseText.status == '432'){
                $('#validation_alert').show();
                $.each(responseText.error, function(i, val) {
                    $('#validation_alert').append(`
                        <div class="alert alert-danger solid alert-rounded ">
                            <p> Line <b>` + val.row + `</b> in column <b>` + val.attribute + `</b> : ` + val.errors[0] + `</p>
                        </div>
                    `);
                });
            }else{
                errorMessage(responseText.message);
            }
        });
        this.on("complete", function(file) {
            dropzoneMultiple.removeFile(file);
        });
		this.on('error', function(file, response) {
			errorMessage(response.match("<title>(.*?)</title>")[1]);
		});
    },
};

Dropzone.options.dropzoneUploadSmall = {
    paramName: "file",
    maxFilesize: 2,
    maxFiles: 1,
    autoProcessQueue: false,
    autoQueue: true,
	timeout: 0,
    addRemoveLinks : true,
    acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf,.xlsx,.xls,.7z,.rar,.zip",
    init: function() {
        dropzoneMultiple = this;
        this.on("addedfiles", function(files) {
            $.ajax({
                url: window.location.href + '/check',
                type: 'POST',
                dataType: 'JSON',
                data: {
                   name: files[0].name
                },
                headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {

                },
                success: function(response) {
                    if(response.status == '1'){
                        dropzoneMultiple.processQueue();
                    }else{
                        errorMessage('Maaf nama file telah ada pada sistem. Silahkan rename file anda.');
                        /* if(confirm("Ups, file ini telah ada dalam. Do you want to replace the file?")){
                            dropzoneMultiple.processQueue();
                        }else{
                            dropzoneMultiple.removeFile(files[0]);
                        } */
                    }
                }
           });
        });
        this.on("sending", function(file, xhr, formData){
            formData.append('code', $('#tempUpload').val());
        });
        this.on("success", function(file, responseText) {
            $('#validation_alert_upload').empty().hide();
            if(responseText.status == '200'){
                $('#list-files').append(`
                    <div class="col-md-3">
                        ` + responseText.newimage.file + `
                        <p class="mt-3 text-center">
                            <h6>` + responseText.newimage.name + `</h6>
                            <button type="button" class="btn btn-rounded btn-primary" onclick="destroyFile('` + responseText.newimage.code + `');"><i class="fa fa-trash"></i></button>
                        </p>
                    </div>    
                `);
                successMessage(responseText.message);
                loadDataTableSurveyResult();
            }else if(responseText.status == '422'){
                $('#validation_alert_upload').show();
                $.each(responseText.error, function(i, val) {
                    $.each(val, function(i, val) {
                       $('#validation_alert_upload').append(`
                            <div class="alert alert-info solid alert-rounded "> ` + val + `</div>
                       `);
                    });
                });
            }else{
                errorMessage(responseText.message);
            }
        });
        this.on("complete", function(file) {
            dropzoneMultiple.removeFile(file);
        });
		this.on('error', function(file, response) {
			errorMessage(response.match("<title>(.*?)</title>")[1]);
		});
    },
};

$(function() {

    $('#basicModal').on('hidden.bs.modal', function (e) {
        $('#validation_alert').empty().hide();
    });
    
    $('#show_password').click(function(){
        if($(this).is(':checked')){
            $('#password').attr('type', 'text');
        }else{
            $('#password').attr('type', 'password');
        }
    });

    $("#login_form").submit(function(event) { 
        event.preventDefault();
        if($('#username').val() !== '' && $('#password').val() !== ''){
            $.ajax({
             url: location.protocol + '//' + location.host + '/login/auth',
             type: 'POST',
             dataType: 'JSON',
             contentType: false,
             processData: false,
             data: new FormData($('#login_form')[0]),
             cache: true,
             headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             beforeSend: function() {
                
             },
             success: function(response) {
                if(response.status == 200) {						
                    toastr.success(response.message, "Success", {
                        positionClass: "toast-top-right",
                        timeOut: 1e3,
                        closeButton: !0,
                        debug: !1,
                        newestOnTop: !0,
                        progressBar: !0,
                        onclick: null,
                        showDuration: "300",
                        hideDuration: "1000",
                        extendedTimeOut: "1000",
                        showEasing: "swing",
                        hideEasing: "linear",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                        onHidden: function() {
                            location.reload();
                        }
                    });
                } else if(response.status == 422) {
                    errorMessage(response.message);
                }
             },
             error: function() {
                errorConnection();
             }
          });
        }else{
            errorMessage("Check your form!");
        }
    });

    if($('#payroll-datatable').length > 0){
		loadDataTablePayroll();
    }

    if($('#user-datatable').length > 0){
		loadDataTableUser();
    }

    if($('#purpose-datatable').length > 0){
		loadDataTablePurpose();
    }

    if($('#project-type-datatable').length > 0){
		loadDataTableProjectType();
    }

    if($('#project-datatable').length > 0){
		loadDataTableProject();
    }

    if($('#bank-datatable').length > 0){
		loadDataTableBank();
    }

    if($('#invoice-datatable').length > 0){
		loadDataTableInvoice();
    }

    if($('#offering-letter-datatable').length > 0){
		loadDataTableOfferingLetter();
    }

    if($('#letter-agreement-datatable').length > 0){
		loadDataTableLetterAgreement();
    }

    if($('#approval-datatable').length > 0){
        loadDataTableApproval();
    }

    if($('#survey-result-datatable').length > 0){
        loadDataTableSurveyResult();
    }

    $('#payroll-datatable tbody').on('click', '.payroll-email', function() {
        let id = $(this).data('payroll');
        swal.fire({
            title: "Yakin ingin mengirimkan email kembali?",
            text: "Pastikan data yang ingin anda kirimkan tepat. Anda tidak bisa membatalkan gaji yang sudah terkirim.",
            showCancelButton: true,
            type: 'warning',
            cancelButtonColor: '#d33',
            confirmButtonColor: "#DD6B55",
        }).then((res) => {
            if(res.value){
                $.ajax({
                    url: window.location.href + '/send_email',
                    type: 'POST',
                    dataType: 'JSON',
                    data: { id : id },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        loadingOpen();
                    },
                    success: function(response) {
                        loadingClose();
                        if(response.status == 200) {
                            successMessage(response.message);
                        }else{
                            errorMessage(response.message);
                        }
                        loadDataTablePayroll();
                    },
                    error: function(response) {
                        if(response.status == '403'){
                            errorMessage('You have no access.')
                        }else{
                            errorConnection();
                        }
                        loadingClose();
                    }
                });
            }else if(res.dismiss == 'cancel'){
                swal("Cancelled !!", "Oke, mungkin lain waktu ya.", "warning");
            }else if(res.dismiss == 'esc'){
                
            }
        });
    });

    $('#historyModal').on('hidden.bs.modal', function (e) {
        $('#show-history').empty();
    });

    if($('#dashboard-datatable').length > 0){
        window.table = $('#dashboard-datatable').DataTable({
            "scrollCollapse": true,
            "scrollY": '400px',
            "responsive": true,
            "stateSave": true,
            "serverSide": true,
            "deferRender": true,
            "destroy": true,
            "iDisplayInLength": 10,
            "order": [[0, 'asc']],
            ajax: {
                url: window.location.href + '/datatable',
                type: 'GET',
                data: {
                    
                },
                beforeSend: function() {
                    /* loadingOpen(); */
                },
                complete: function() {
                    /* loadingClose(); */
                },
                error: function() {
                    /* loadingClose(); */
                    errorConnection();
                }
            },
            columns: [
                { name: 'id', searchable: false, className: 'text-center' },
                { name: 'rekening', className: '' },
                { name: 'bulan', className: '' },
                { name: 'jabatan', className: '' },
                { name: 'jumlah_transfer', className: 'text-right' },
                { name: 'updated_at', className: '' },
                { name: 'action', searchable: false, orderable: false, className: 'text-center' },
            ],
            createdRow: function ( row, data, index ) {
                $(row).addClass('selected')
            },
            language: {
                paginate: {
                    next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
                }
            }
        });

        $('#dashboard-datatable tbody').on('click', '.download-pdf', function() {
            successMessage('File successfully downloaded!');
            window.open(window.location.href + '/download/' + $(this).data('download'), '_blank');
        });
    }

    setTimeout(function(){
        jQuery("#preloader").hide();
    },800);

    /* $(document).on('keypress',function(e) {
        if($('#modalCreate').hasClass('show')){
            if(e.which == 13) {
                save();
            }
        }
    }); */
    $('#modalCreate').on( 'keypress', function( e ) {
        if( e.keyCode === 13 ) {
            e.preventDefault();
            save();
        }
    } );
    $('#modalCreate').on('shown.bs.modal', function() {
        $(document).off('focusin.modal');
    });
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });
    select2ServerSide('#customer_id','select2/customer');
    select2ServerSide('#region_id','select2/region');
    select2ServerSide('#project_type_id','select2/project_type');
    select2ServerSide('#purpose_id','select2/purpose');
    select2ServerSide('#project_id','select2/project');
    select2ServerSide('#bank_id','select2/bank');

    /*INVOICE*/
    $('#modalReceipt').on('hidden.bs.modal', function (e) {
        $('#tempReceipt').val('');
        $('#formDataReceipt')[0].reset();
        $('#modal-receipt-title').text('');
        $('#previewFileReceipt').html('');
    });
    if($('#fileReceipt').length > 0){
        $("#fileReceipt").on('change', function () {
            if($('#fileReceipt').val()){
                if (typeof (FileReader) != "undefined") {
                    var image_holder = $("#previewFileReceipt");
                    image_holder.empty();

                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $("<img />", {
                            "src": e.target.result,
                            "class": "thumb-image",
                            "width": "100%"
                        }).appendTo(image_holder);
                    };
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                } else {
                    alert("This browser does not support FileReader.");
                }
            }
        });
    }
    /*INVOICE*/

    cekApproval();

    setInterval(function () {
        cekApproval();
    },25000);
});

function addDate(){
    if($('#start_date').val()){
        var result = new Date($('#start_date').val());
        result.setDate(result.getDate() + parseInt($('#working_days').val()));
        $('#end_date').val(result.toISOString().split('T')[0]);
    }else{
        $('#end_date').val('');
    }
}

function formatRupiahNoMinus(angka){
	let val = angka.value ? angka.value : '';
	var number_string = val.replace(/[^,\d]/g, '').toString(),
	split   		= number_string.split(','),
	sisa     		= parseFloat(split[0]).toString().length % 3,
	rupiah     		= parseFloat(split[0]).toString().substring(0, sisa),
	ribuan     		= parseFloat(split[0]).toString().substring(sisa).match(/\d{3}/gi);
 
	if(ribuan){
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}
 
	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	angka.value = rupiah;
}

function select2ServerSide(selector, endpoint) {
	$(selector).select2({
		placeholder: '-- Pilih ya --',
		minimumInputLength: 1,
		cache: true,
		width: '100%',
		dropdownParent: $('#modalCreate'),
		ajax: {
			url: endpoint,
			type: 'GET',
			dataType: 'JSON',
			data: function(params) {
				return {
					search: params.term
				};
			},
			processResults: function(data) {
				return {
					results: data.items
				}
			}
		}
	});
 }

function errorConnection(){
    toastr.error("Check your internet connection!", "Ups...", {
        positionClass: "toast-top-right",
        timeOut: 2e3,
        closeButton: !0,
        debug: !1,
        newestOnTop: !0,
        progressBar: !0,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
    });
}

function successMessage(msg){
    toastr.success(msg, "Success", {
        positionClass: "toast-top-right",
        timeOut: 2e3,
        closeButton: !0,
        debug: !1,
        progressBar: !0,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
    });
}

function infoMessage(msg){
    toastr.info(msg, "Info", {
        positionClass: "toast-bottom-right",
        timeOut: 2e3,
        closeButton: !0,
        debug: !1,
        progressBar: !0,
        preventDuplicates: !0,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
    });
}

function errorMessage(msg){
    toastr.error(msg, "Ups...", {
        positionClass: "toast-top-right",
        timeOut: 2e3,
        closeButton: !0,
        debug: !1,
        progressBar: !0,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
    });
}

function loadDataTablePayroll(){
    window.table = $('#payroll-datatable').DataTable({
        "scrollCollapse": true,
        "scrollY": '400px',
		"scrollX": true,
		"scroller": true,
        "responsive": true,
        "stateSave": true,
        "serverSide": true,
        "deferRender": true,
        "destroy": true,
        "iDisplayInLength": 10,
        "order": [[0, 'asc']],
        ajax: {
            url: window.location.href + '/datatable',
            type: 'GET',
            data: {
                
            },
            beforeSend: function() {
                /* loadingOpen(); */
            },
            complete: function() {
                /* loadingClose(); */
            },
            error: function() {
                /* loadingClose(); */
                errorConnection();
            }
        },
        columns: [
            { name: 'id', searchable: false, className: 'text-center' },
            { name: 'nik', className: '' },
            { name: 'name', className: '', orderable: false },
            { name: 'rekening', className: '' },
            { name: 'bulan', className: '' },
			{ name: 'jabatan', className: '' },
            { name: 'gaji_pokok', className: '' },
            { name: 'jumlah_transfer', className: '' },
            { name: 'updated_at', className: '' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center' },
        ],
        createdRow: function ( row, data, index ) {
            $(row).addClass('selected')
        },
        language: {
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
            }
        }
    });
}

function loadDataTableUser(){
    window.table = $('#user-datatable').DataTable({
        "scrollCollapse": true,
        "scrollY": '400px',
		"scrollX": true,
		"scroller": true,
        "responsive": true,
        "stateSave": true,
        "serverSide": true,
        "deferRender": true,
        "destroy": true,
        "iDisplayInLength": 10,
        "order": [[0, 'asc']],
        ajax: {
            url: window.location.href + '/datatable',
            type: 'GET',
            data: {
                
            },
            beforeSend: function() {
            },
            complete: function() {
            },
            error: function() {
                errorConnection();
            }
        },
        columns: [
            { name: 'id', searchable: false, className: 'text-center' },
            { name: 'name', className: '' },
            { name: 'nik', className: '' },
            { name: 'email', className: '' },
            { name: 'tipe', className: '' },
            { name: 'phone', className: '' },
			{ name: 'status', className: '' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center' },
        ],
        createdRow: function ( row, data, index ) {
            $(row).addClass('selected')
        },
        language: {
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
            }
        }
    });
}

function loadDataTablePurpose(){
    window.table = $('#purpose-datatable').DataTable({
        "scrollCollapse": true,
        "scrollY": '400px',
		"scrollX": true,
		"scroller": true,
        "responsive": true,
        "stateSave": true,
        "serverSide": true,
        "deferRender": true,
        "destroy": true,
        "iDisplayInLength": 10,
        "order": [[0, 'asc']],
        ajax: {
            url: window.location.href + '/datatable',
            type: 'GET',
            data: {
                
            },
            beforeSend: function() {
            },
            complete: function() {
            },
            error: function() {
                errorConnection();
            }
        },
        columns: [
            { name: 'id', searchable: false, className: 'text-center' },
            { name: 'code', className: '' },
            { name: 'name', className: '' },
			{ name: 'status', className: '' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center' },
        ],
        createdRow: function ( row, data, index ) {
            $(row).addClass('selected')
        },
        language: {
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
            }
        }
    });
}

function loadDataTableProjectType(){
    window.table = $('#project-type-datatable').DataTable({
        "scrollCollapse": true,
        "scrollY": '400px',
		"scrollX": true,
		"scroller": true,
        "responsive": true,
        "stateSave": true,
        "serverSide": true,
        "deferRender": true,
        "destroy": true,
        "iDisplayInLength": 10,
        "order": [[0, 'asc']],
        ajax: {
            url: window.location.href + '/datatable',
            type: 'GET',
            data: {
                
            },
            beforeSend: function() {
            },
            complete: function() {
            },
            error: function() {
                errorConnection();
            }
        },
        columns: [
            { name: 'id', searchable: false, className: 'text-center' },
            { name: 'code', className: '' },
            { name: 'name', className: '' },
			{ name: 'status', className: '' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center' },
        ],
        createdRow: function ( row, data, index ) {
            $(row).addClass('selected')
        },
        language: {
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
            }
        }
    });
}

function updatePassword(id){
    swal.fire({
        title: "Yakin ingin mereset password akun?",
        text: "Password baru akan dikirimkan ke email terdaftar.",
        showCancelButton: true,
        type: 'warning',
        cancelButtonColor: '#d33',
        confirmButtonColor: "#DD6B55",
    }).then((res) => {
        if(res.value){
            $.ajax({
                url: window.location.href + '/update_password',
                type: 'POST',
                dataType: 'JSON',
                data: { id : id },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    loadingOpen();
                },
                success: function(response) {
                    loadingClose();
                    if(response.status == 200) {
                        successMessage(response.message);
                    }else{
                        errorMessage(response.message);
                    }
                    loadDataTablePayroll();
                },
                error: function(response) {
                    if(response.status == '403'){
                        errorMessage('You have no access.')
                    }else{
                        errorConnection();
                    }
                    loadingClose();
                }
            });
        }else if(res.dismiss == 'cancel'){
            swal("Cancelled !!", "Oke, mungkin lain waktu ya.", "warning");
        }else if(res.dismiss == 'esc'){
            
        }
    });
}

/* function history(code){
    $.ajax({
        url: window.location.href + '/history',
        type: 'POST',
        dataType: 'JSON',
        data: {
            code: code
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {

        },
        success: function(response) {
            $('#historyModal').modal('toggle');
            $('#show-history').html(response.content);
            $('.modal-content').scrollTop(0);            
        },
        error: function(response) {
            if(response.status == '403'){
				errorMessage('You have no access.')
			}else{
				errorConnection();
			}
        }
    });
} */

function history(id){
    $.ajax({
        url: window.location.href + '/history',
        type: 'POST',
        dataType: 'JSON',
        data: {
            id: id,
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {

        },
        success: function(response) {
            if(response.status == '200'){
                $('#historyModal').modal('toggle');
                $('#show-history').html(response.content);
                successMessage(response.message);
            }else{
                errorMessage(response.message)
            }
            $('.modal-content').scrollTop(0);            
        },
        error: function(response) {
            if(response.status == '403'){
				errorMessage('You have no access.')
			}else{
				errorConnection();
			}
        }
    });
}

function loadingOpen(){
    jQuery('#preloader').show();
}

function loadingClose(){
    jQuery('#preloader').hide();
}

function edit(code){
    $.ajax({
        url: window.location.href + '/show',
        type: 'POST',
        dataType: 'JSON',
        data: {
            code: code
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            loadingOpen();
        },
        success: function(response) {
            if(response.status == 200){
                $('#temp').val(code);
                /* JIKA FORM CUSTOMER */
                if($('#customer-datatable').length > 0){
                    $('#name').val(response.data.name);
                    $('#code').val(response.data.code);
                    $('#email').val(response.data.email);
                    $('#owner_name').val(response.data.owner_name);
                    $('#pic').val(response.data.pic);
                    $('#owner_id_card').val(response.data.owner_id_card);
                    $('#company_name').val(response.data.company_name);
                    $('#document_no').val(response.data.document_no);
                    $('#address').val(response.data.address);
                    $('#city').val(response.data.city);
                    $('input[name=gender][value="' + response.data.gender + '"]').attr('checked', 'checked');
                    $('#phone').val(response.data.phone);
                    $('#type_body').val(response.data.type_body);
                    $('#note').val(response.data.note);
                    if(response.data.status == '1'){
                        $('#status').prop( "checked", true);
                    }else{
                        $('#status').prop( "checked", false);
                    }
                }

                /* JIKA FORM CUSTOMER */
                if($('#user-datatable').length > 0){
                    $('#name').val(response.data.nama);
                    $('#nik').val(response.data.nik);
                    $('#email').val(response.data.email);
                    $('#type').val(response.data.type);
                    $('#phone').val(response.data.phone);
                    if(response.data.status == '1'){
                        $('#status').prop( "checked", true);
                    }else{
                        $('#status').prop( "checked", false);
                    }
                }

                /* JIKA FORM PURPOSE */
                if($('#purpose-datatable').length > 0){
                    $('#code').val(response.data.code);
                    $('#name').val(response.data.name);
                    if(response.data.status == '1'){
                        $('#status').prop( "checked", true);
                    }else{
                        $('#status').prop( "checked", false);
                    }
                }

                /* JIKA FORM JENIS PROYEK */
                if($('#project-type-datatable').length > 0){
                    $('#code').val(response.data.code);
                    $('#name').val(response.data.name);
                    if(response.data.status == '1'){
                        $('#status').prop( "checked", true);
                    }else{
                        $('#status').prop( "checked", false);
                    }
                }

                /* JIKA FORM BANK */
                if($('#bank-datatable').length > 0){
                    $('#code').val(response.data.code);
                    $('#name').val(response.data.name);
                    $('#no').val(response.data.no);
                    $('#bank').val(response.data.bank);
                    $('#branch').val(response.data.branch);
                    if(response.data.status == '1'){
                        $('#status').prop( "checked", true);
                    }else{
                        $('#status').prop( "checked", false);
                    }
                }

                /* JIKA FORM PROJECT */
                if($('#project-datatable').length > 0){
                    $('#code').val(response.data.code);
                    $('#name').val(response.data.name);
                    $('#customer_id').empty().append(`
                        <option value="` + response.data.customer_id + `">` + response.data.customer_info + `</option>
                    `);
                    $('#project_no').val(response.data.project_no);
                    $('#post_date').val(response.data.post_date);
                    $('#location').val(response.data.location);
                    $('#region_id').empty().append(`
                        <option value="` + response.data.region_id + `">` + response.data.region_info + `</option>
                    `);
                    $('#project_type_id').empty().append(`
                        <option value="` + response.data.project_type_id + `">` + response.data.project_type_info + `</option>
                    `);
                    $('#purpose_id').empty().append(`
                        <option value="` + response.data.purpose_id + `">` + response.data.purpose_info + `</option>
                    `);
                    $('#purpose_note').val(response.data.purpose_note);
                    $('#working_days').val(response.data.working_days);
                    $('#start_date').val(response.data.start_date);
                    $('#end_date').val(response.data.end_date);
                    $('#andalalin_document_no').val(response.data.andalalin_document_no);
                    $('#power_letter_no').val(response.data.power_letter_no);
                    $('#cost').val(response.data.cost);
                    $('#termin').val(response.data.termin);
                    $('#note').val(response.data.note);
                }

                /* JIKA FORM INVOICE */
                if($('#invoice-datatable').length > 0){
                    $('#code').val(response.data.code);
                    $('#receive_from').val(response.data.receive_from);
                    $('#project_id').empty().append(`
                        <option value="` + response.data.project_id + `">` + response.data.project_code + `</option>
                    `);
                    $('#bank_id').empty().append(`
                        <option value="` + response.data.bank_id + `">` + response.data.bank_code + `</option>
                    `);
                    $('#post_date').val(response.data.post_date);
                    $('#nominal').val(response.data.nominal);
                    $('#termin_no').val(response.data.termin_no);
                    $('#note').val(response.data.note);
                    $('#nominal_project').text(response.data.nominal_project);
                }

                /* JIKA FORM SURAT PENAWARAN */
                if($('#offering-letter-datatable').length > 0){
                    $('#code').val(response.data.code);
                    $('#to_name').val(response.data.to_name);
                    $('#project_id').empty().append(`
                        <option value="` + response.data.project_id + `">` + response.data.project_code + `</option>
                    `);
                    $('#post_date').val(response.data.post_date);
                    $('#type_building').val(response.data.type_building);
                    $('#location_building').val(response.data.location_building);
                    $('#type_road').val(response.data.type_road);
                    $('#note').val(response.data.note);
                }

                /* JIKA FORM SURAT SPK */
                if($('#letter-agreement-datatable').length > 0){
                    $('#code').val(response.data.code);
                    $('#name').val(response.data.name);
                    $('#project_id').empty().append(`
                        <option value="` + response.data.project_id + `">` + response.data.project_code + `</option>
                    `);
                    $('#post_date').val(response.data.post_date);
                    $('#address').val(response.data.address);
                    $('#position').val(response.data.position);
                    $('#phone').val(response.data.phone);
                    $('#name_ref').val(response.data.name_ref);
                    $('#type_building').val(response.data.type_building);
                    $('#name_builder').val(response.data.name_builder);
                    $('#persil_location').val(response.data.persil_location);
                    $('#land_area').val(response.data.land_area);
                    $('#building_area').val(response.data.building_area);
                    $('#subdistrict').val(response.data.subdistrict);
                    $('#district').val(response.data.district);
                    $('#city').val(response.data.city);
                    $('#province').val(response.data.province);
                    $('#road_status').val(response.data.road_status);
                    $('#nominal_1').val(response.data.nominal_1);
                    $('#nominal_2').val(response.data.nominal_2);
                    $('#nominal_3').val(response.data.nominal_3);
                    $('#estimate_date_start').val(response.data.estimate_date_start);
                    $('#estimate_date_finish').val(response.data.estimate_date_finish);
                    $('#note').val(response.data.note);
                }

                /* JIKA FORM HASIL SURVEI */
                if($('#survey-result-datatable').length > 0){
                    $('#project_id').empty().append(`
                        <option value="` + response.data.project_id + `">` + response.data.project_code + `</option>
                    `);
                    $('#post_date').val(response.data.post_date);
                    $('#note').val(response.data.note);
                }

                $('#modalCreate').modal('toggle');
            }else{
                errorMessage('Data tidak ditemukan.');
            }
            loadingClose();
        },
        error: function(response) {
            if(response.status == '403'){
				errorMessage('You have no access.');
			}else{
				errorConnection();
			}
            loadingClose();
        }
    });
}

function detail(code){
    $.ajax({
        url: window.location.href + '/detail',
        type: 'POST',
        dataType: 'JSON',
        data: {
            code: code
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            loadingOpen();
        },
        success: function(response) {
            if(response.status == 200){
                $('#modal-detail-title').text(response.data.code);
                $('#modal-detail-body').html(response.html);

                $('#modalDetail').modal('toggle');
            }else{
                errorMessage('Data tidak ditemukan.');
            }
            loadingClose();
        },
        error: function(response) {
            if(response.status == '403'){
				errorMessage('You have no access.');
			}else{
				errorConnection();
			}
            loadingClose();
        }
    });
}

function destroyFile(code){
    swal.fire({
        title: "Yakin ingin menghapus data?",
        text: "Anda tidak bisa mengembalikan data yang dihapus.",
        showCancelButton: true,
        type: 'warning',
        cancelButtonColor: '#d33',
        confirmButtonColor: "#DD6B55",
    }).then((res) => {
        if(res.value){
            $.ajax({
                url: window.location.href + '/destroy_file',
                type: 'POST',
                dataType: 'JSON',
                data: { id : code },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    loadingOpen();
                },
                success: function(response) {
                    loadingClose();
                    if(response.status == 200) {
                        $('#picture' + code).remove();
                        successMessage(response.message);
                        loadDataTableSurveyResult();
                    }
                },
                error: function() {
                    if(response.status == '403'){
                        errorMessage('You have no access.');
                    }else{
                        errorConnection();
                    }
                    loadingClose();
                }
            });
        }
    });
}

function showUpload(code){
    $.ajax({
        url: window.location.href + '/show_upload',
        type: 'POST',
        dataType: 'JSON',
        data: {
            code: code
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            loadingOpen();
        },
        success: function(response) {
            if(response.status == 200){
                $('#modal-detail-title-upload').text(response.code);
                $("#list-files").empty();
                $('#tempUpload').val(code);
                if(response.data.length > 0){
                    $.each(response.data, function(i, val) {
                        $('#list-files').append(`
                            <div class="col-md-3" id="picture` + val.code + `">
                                ` + val.file + `
                                <p class="mt-3 text-center">
                                    <h6>` + val.name + `</h6>
                                    <button type="button" class="btn btn-rounded btn-primary" onclick="destroyFile('` + val.code + `');"><i class="fa fa-trash"></i></button>
								</p>
                            </div>    
                        `);
                    });
                }else{
                    $('#list-files').append(`
						<div class="col-md-12 text-center">
							<div class="alert alert-warning solid alert-dismissible fade show">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                <strong>Warning!</strong> Data file lama tidak tersedia.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                </button>
                            </div>
						</div>
					`);
                }                

                $('#modalUpload').modal('toggle');
            }else{
                errorMessage('Data tidak ditemukan.');
            }
            loadingClose();
        },
        error: function(response) {
            if(response.status == '403'){
				errorMessage('You have no access.');
			}else{
				errorConnection();
			}
            loadingClose();
        }
    });
}

function destroy(code){
    swal.fire({
        title: "Yakin ingin menghapus data?",
        text: "Anda tidak bisa mengembalikan data yang dihapus.",
        showCancelButton: true,
        type: 'warning',
        cancelButtonColor: '#d33',
        confirmButtonColor: "#DD6B55",
    }).then((res) => {
        if(res.value){
            $.ajax({
                url: window.location.href + '/destroy',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    code: code
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    loadingOpen();
                },
                success: function(response) {
                    if(response.status == 200){
                        successMessage('Data berhasil dihapus');

                        /* JIKA FORM CUSTOMER */
                        if($('#customer-datatable').length > 0){
                            loadDataTableCustomer();
                        }

                        /* JIKA FORM USER */
                        if($('#user-datatable').length > 0){
                            loadDataTableUser();
                        }

                        /* JIKA FORM TIPE PROYEK */
                        if($('#project-type-datatable').length > 0){
                            loadDataTableProjectType();
                        }

                        /* JIKA FORM PERUNTUKAN */
                        if($('#purpose-datatable').length > 0){
                            loadDataTablePurpose();
                        }

                        /* JIKA FORM BANK */
                        if($('#bank-datatable').length > 0){
                            loadDataTableBank();
                        }

                        /* JIKA FORM INVOICE */
                        if($('#invoice-datatable').length > 0){
                            loadDataTableInvoice();
                        }
                    }else{
                        errorMessage(response.message);
                    }
                    loadingClose();
                },
                error: function(response) {
                    if(response.status == '403'){
                        errorMessage('You have no access.');
                    }else{
                        errorConnection();
                    }
                    loadingClose();
                }
            });
        }
    });
}

function save(){
    swal({
        title: "Apakah yakin ingin simpan?",
        text: "Silahkan cek kembali form anda.",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, simpan!",
        cancelButtonText: "Batal",
        closeOnConfirm: !1,
        closeOnCancel: !1,
        focusCancel: true,
    }).then(function (willDelete) {
        setTimeout(function () {
            $('.swal2-confirm').focus();
        }, 100);
        if (willDelete.value) {
            var formData = new FormData($('#formData')[0]);
            $.ajax({
                url: window.location.href + '/create',
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                processData: false,
                cache: true,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#validation_alert').hide();
                    $('#validation_alert').html('');
                    loadingOpen();
                },
                success: function(response) {
                    loadingClose();
                    if(response.status == 200) {
                        successMessage(response.message);

                        /* JIKA FORM CUSTOMER */
                        if($('#customer-datatable').length > 0){
                            loadDataTableCustomer();
                        }

                        if($('#user-datatable').length > 0){
                            loadDataTableUser();
                        }

                        if($('#purpose-datatable').length > 0){
                            loadDataTablePurpose();
                        }

                        if($('#project-type-datatable').length > 0){
                            loadDataTableProjectType();
                        }

                        if($('#project-datatable').length > 0){
                            loadDataTableProject();
                        }

                        if($('#bank-datatable').length > 0){
                            loadDataTableBank();
                        }

                        if($('#invoice-datatable').length > 0){
                            loadDataTableInvoice();
                        }

                        if($('#offering-letter-datatable').length > 0){
                            loadDataTableOfferingLetter();
                        }

                        if($('#letter-agreement-datatable').length > 0){
                            loadDataTableLetterAgreement();
                        }

                        if($('#survey-result-datatable').length > 0){
                            loadDataTableSurveyResult();
                        }

                        $('#modalCreate').modal('toggle');
                    } else if(response.status == 422) {
                        $('#validation_alert').show();
                        $.each(response.error, function(i, val) {
                            $.each(val, function(j, val) {
                                $('#validation_alert').append(`
                                     <div class="alert alert-danger solid alert-rounded "> ` + val + `</div>
                                `);
                             });
                        });
                        $('.modal-body').scrollTop(0);
                    }else{
                        errorMessage(response.message);
                    }
                },
                error: function() {
                    loadingClose();
                }
            });
        }
    });
}

/* CUSTOMER */

$(function() {
    $('#modalCreate').on('hidden.bs.modal', function (e) {
        $('#formData')[0].reset();
        $('#temp').val('');
        $('#validation_alert').html('');
        $('#validation_alert').hide();
        $('#customer_id,#project_type_id,#purpose_id,#region_id,#project_id,#bank_id').empty();
        $('#nominal_project').text('0,00');
    });

    $('#modalUpload').on('hidden.bs.modal', function (e) {
        $('#tempUpdate').val('');
        $('#validation_alert_upload').html('');
        $('#validation_alert_upload').hide();
        $('#list-files').empty();
    });

    $('#modalDetail').on('hidden.bs.modal', function (e) {
        $('#modal-detail-title,#modal-detail-body').text('');
    });
});

if($('#customer-datatable').length > 0){
    loadDataTableCustomer();
}

function loadDataTableCustomer(){
    window.table = $('#customer-datatable').DataTable({
        "scrollCollapse": true,
        "scrollY": '400px',
		"scrollX": true,
		"scroller": true,
        "responsive": true,
        "stateSave": true,
        "serverSide": true,
        "deferRender": true,
        "destroy": true,
        "fixedColumns": {
            left: 2,
            right: 1
        },
        "iDisplayInLength": 10,
        "order": [[0, 'asc']],
        ajax: {
            url: window.location.href + '/datatable',
            type: 'GET',
            data: {
                
            },
            beforeSend: function() {
                /* loadingOpen(); */
            },
            complete: function() {
                /* loadingClose(); */
            },
            error: function() {
                /* loadingClose(); */
                errorConnection();
            }
        },
        columns: [
            { name: 'id', searchable: false, className: 'text-center' },
            { name: 'code', className: '' },
            { name: 'name', className: '' },
            { name: 'email', className: '' },
			{ name: 'owner', className: '' },
            { name: 'pic', className: '' },
            { name: 'nik', className: '' },
            { name: 'company', className: '' },
            { name: 'document', className: '' },
            { name: 'address', className: '' },
            { name: 'city', className: '' },
            { name: 'gender', className: '' },
            { name: 'phone', className: '' },
            { name: 'type', className: '' },
            { name: 'note', className: '' },
            { name: 'status', className: 'text-center' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center' },
        ],
        createdRow: function ( row, data, index ) {
            $(row).addClass('selected')
        },
        language: {
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
            }
        }
    });
}

/* CUSTOMER */

/* PROJECT */

function loadDataTableProject(){
    window.table = $('#project-datatable').DataTable({
        "scrollCollapse": true,
        "scrollY": '400px',
		"scrollX": true,
		"scroller": true,
        "responsive": true,
        "stateSave": true,
        "serverSide": true,
        "deferRender": true,
        "destroy": true,
        "fixedColumns": {
            left: 2,
            right: 1
        },
        "iDisplayInLength": 10,
        "order": [[0, 'asc']],
        ajax: {
            url: window.location.href + '/datatable',
            type: 'GET',
            data: {
                
            },
            beforeSend: function() {
                /* loadingOpen(); */
            },
            complete: function() {
                /* loadingClose(); */
            },
            error: function() {
                /* loadingClose(); */
                errorConnection();
            }
        },
        columns: [
            { name: 'id', searchable: false, className: 'text-center' },
            { name: 'code', className: '' },
            { name: 'user_id', className: '' },
            { name: 'customer_id', className: '' },
			{ name: 'name', className: '' },
            { name: 'project_no', className: '' },
            { name: 'post_date', className: '' },
            { name: 'location', className: '' },
            { name: 'region_id', className: '' },
            { name: 'project_type_id', className: '' },
            { name: 'purpose_id', className: '' },
            { name: 'purpose_note', className: '' },
            { name: 'working_days', className: '' },
            { name: 'start_date', className: '' },
            { name: 'end_date', className: '' },
            { name: 'andalalin_document_no', className: '' },
            { name: 'power_letter_no', className: '' },
            { name: 'cost', className: 'text-right' },
            { name: 'termin', className: 'text-center' },
            { name: 'note', className: '' },
            { name: 'status', className: 'text-center' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center' },
        ],
        createdRow: function ( row, data, index ) {
            $(row).addClass('selected')
        },
        language: {
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
            }
        }
    });
}

/* PROJECT */

/* BANK */

function loadDataTableBank(){
    window.table = $('#bank-datatable').DataTable({
        "scrollCollapse": true,
        "scrollY": '400px',
		"scrollX": true,
		"scroller": true,
        "responsive": true,
        "stateSave": true,
        "serverSide": true,
        "deferRender": true,
        "destroy": true,
        "fixedColumns": {
            left: 2,
            right: 1
        },
        "iDisplayInLength": 10,
        "order": [[0, 'asc']],
        ajax: {
            url: window.location.href + '/datatable',
            type: 'GET',
            data: {
                
            },
            beforeSend: function() {
                /* loadingOpen(); */
            },
            complete: function() {
                /* loadingClose(); */
            },
            error: function() {
                /* loadingClose(); */
                errorConnection();
            }
        },
        columns: [
            { name: 'id', searchable: false, className: 'text-center' },
            { name: 'code', className: '' },
            { name: 'name', className: '' },
            { name: 'no', className: '' },
			{ name: 'bank', className: '' },
            { name: 'branch', className: '' },
            { name: 'status', className: 'text-center' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center' },
        ],
        createdRow: function ( row, data, index ) {
            $(row).addClass('selected')
        },
        language: {
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
            }
        }
    });
}

/* BANK */

/* APPROVAL */

function loadDataTableApproval(){
    window.table = $('#approval-datatable').DataTable({
        "scrollCollapse": true,
        "scrollY": '400px',
		"scrollX": true,
		"scroller": true,
        "responsive": true,
        "stateSave": true,
        "serverSide": true,
        "deferRender": true,
        "destroy": true,
        "fixedColumns": {
            left: 2,
            right: 1
        },
        "iDisplayInLength": 10,
        "order": [[0, 'asc']],
        ajax: {
            url: window.location.href + '/datatable',
            type: 'GET',
            data: {
                
            },
            beforeSend: function() {
                /* loadingOpen(); */
            },
            complete: function() {
                /* loadingClose(); */
            },
            error: function() {
                /* loadingClose(); */
                errorConnection();
            }
        },
        columns: [
            { name: 'id', searchable: false, className: 'text-center' },
            { name: 'created_at', className: '' },
            { name: 'from_user_id', className: '' },
            { name: 'approve_note', className: '' },
            { name: 'approve_status', className: '' },
            { name: 'approve_level', className: '' },
            { name: 'approve_date', className: '' },
            { name: 'ref_code', searchable: false, orderable: false, className: '' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center' },
        ],
        createdRow: function ( row, data, index ) {
            $(row).addClass('selected')
        },
        language: {
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
            }
        }
    });
}

/* APPROVAL */

/* INVOICE */

function loadDataTableInvoice(){
    window.table = $('#invoice-datatable').DataTable({
        "scrollCollapse": true,
        "scrollY": '400px',
		"scrollX": true,
		"scroller": true,
        "responsive": true,
        "stateSave": true,
        "serverSide": true,
        "deferRender": true,
        "destroy": true,
        "fixedColumns": {
            left: 2,
            right: 1
        },
        "iDisplayInLength": 10,
        "order": [[0, 'asc']],
        ajax: {
            url: window.location.href + '/datatable',
            type: 'GET',
            data: {
                
            },
            beforeSend: function() {
                /* loadingOpen(); */
            },
            complete: function() {
                /* loadingClose(); */
            },
            error: function() {
                /* loadingClose(); */
                errorConnection();
            }
        },
        columns: [
            { name: 'id', searchable: false, className: 'text-center' },
            { name: 'code', className: '' },
            { name: 'receipt_code', className: '' },
            { name: 'user_id', className: '' },
            { name: 'receive_from', className: '' },
            { name: 'project_id', className: '' },
            { name: 'bank_id', className: '' },
            { name: 'post_date', className: '' },
            { name: 'pay_date', className: '' },
            { name: 'nominal', className: 'text-right' },
            { name: 'termin_no', className: 'text-center' },
            { name: 'note', className: '' },
            { name: 'status', className: 'text-center' },
            { name: 'document', searchable: false, orderable: false, className: 'text-center' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center' },
        ],
        createdRow: function ( row, data, index ) {
            $(row).addClass('selected')
        },
        language: {
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
            }
        }
    });
}

function pay(code,code2){
    $('#modal-receipt-title').text(code2);
    $('#tempReceipt').val(code);
    $('#modalReceipt').modal('toggle');
}

function checkFileReceiptMax(element){
    var file = element;
    var size = parseFloat(file.files[0].size);
    var maxSizeKB = 1024;
    var maxSize = maxSizeKB * 1024;
    if (size > maxSize) {
        errorMessage('File upload bukti bayar ukuran maksimal adalah ' + maxSize + 'Kb.');
        file.value = "";
    }
}

function saveReceipt(){
    swal({
        title: "Apakah yakin ingin simpan?",
        text: "Silahkan cek kembali form anda.",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, simpan!",
        cancelButtonText: "Batal",
        closeOnConfirm: !1,
        closeOnCancel: !1,
        focusCancel: true,
    }).then(function (willDelete) {
        setTimeout(function () {
            $('.swal2-confirm').focus();
        }, 100);
        if (willDelete.value) {
            var formData = new FormData($('#formDataReceipt')[0]);
            $.ajax({
                url: window.location.href + '/create_receipt',
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                processData: false,
                cache: true,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#validation_alert_receipt').hide();
                    $('#validation_alert_receipt').html('');
                    loadingOpen();
                },
                success: function(response) {
                    loadingClose();
                    if(response.status == 200) {
                        successMessage(response.message);

                        if($('#invoice-datatable').length > 0){
                            loadDataTableInvoice();
                        }

                        $('#modalReceipt').modal('toggle');
                    } else if(response.status == 422) {
                        $('#validation_alert_receipt').show();
                        $.each(response.error, function(i, val) {
                            $.each(val, function(j, val) {
                                $('#validation_alert_receipt').append(`
                                     <div class="alert alert-danger solid alert-rounded "> ` + val + `</div>
                                `);
                             });
                        });
                        $('.modal-body').scrollTop(0);
                    }else{
                        errorMessage(response.message);
                    }
                },
                error: function() {
                    loadingClose();
                }
            });
        }
    });
}

function getProjectInfo(){
    $('#nominal_project').text('0,00');
    if($('#project_id').val()){
        $('#nominal_project').text($('#project_id').select2('data')[0].total_project);
    }
}

/* INVOICE */

/* APPROVAL */
function cekApproval(){
	$.ajax({
        url: location.protocol + '//' + location.host + '/persetujuan/get_count_approval',
        type: 'POST',
        dataType: 'JSON',
        data: { },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {

        },
        success: function(response) {
            $('#countApproval').text(response);
        }
    });
}

function approve(code,type){
    if($('#note').val()){
        swal({
            title: "Apakah yakin ingin simpan persetujuan?",
            text: "Silahkan cek kembali form anda.",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, simpan!",
            cancelButtonText: "Batal",
            closeOnConfirm: !1,
            closeOnCancel: !1,
            focusCancel: true,
        }).then(function (willDelete) {
            if (willDelete.value) {
                $.ajax({
                    url: location.protocol + '//' + location.host + '/persetujuan/approve',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        code: code,
                        note: $('#note').val(),
                        type: type,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        loadingOpen();
                    },
                    success: function(response) {
                        loadingClose();
                        if(response.status == 200){
                            toastr.success(response.message, "Success", {
                                positionClass: "toast-top-right",
                                timeOut: 1e3,
                                closeButton: !0,
                                debug: !1,
                                newestOnTop: !0,
                                progressBar: !0,
                                onclick: null,
                                showDuration: "300",
                                hideDuration: "1000",
                                extendedTimeOut: "1000",
                                showEasing: "swing",
                                hideEasing: "linear",
                                showMethod: "fadeIn",
                                hideMethod: "fadeOut",
                                onHidden: function() {
                                    location.reload();
                                }
                            });
                        }else{
                            errorMessage(response.message);
                        }
                    }
                });
            }
        });
    }else{
        errorMessage('Keterangan harus diisi.');
    }
}

/* APPROVAL */

/* SURAT PENAWARAN */

function loadDataTableOfferingLetter(){
    window.table = $('#offering-letter-datatable').DataTable({
        "scrollCollapse": true,
        "scrollY": '400px',
		"scrollX": true,
		"scroller": true,
        "responsive": true,
        "stateSave": true,
        "serverSide": true,
        "deferRender": true,
        "destroy": true,
        "fixedColumns": {
            left: 2,
            right: 1
        },
        "iDisplayInLength": 10,
        "order": [[0, 'asc']],
        ajax: {
            url: window.location.href + '/datatable',
            type: 'GET',
            data: {
                
            },
            beforeSend: function() {
                /* loadingOpen(); */
            },
            complete: function() {
                /* loadingClose(); */
            },
            error: function() {
                /* loadingClose(); */
                errorConnection();
            }
        },
        columns: [
            { name: 'id', searchable: false, className: 'text-center' },
            { name: 'code', className: '' },
            { name: 'user_id', className: '' },
            { name: 'project_id', className: '' },
            { name: 'post_date', className: '' },
            { name: 'to_name', className: '' },
            { name: 'type_building', className: '' },
            { name: 'location_building', className: '' },
            { name: 'type_road', className: 'text-right' },
            { name: 'note', className: '' },
            { name: 'status', className: 'text-center' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center' },
        ],
        createdRow: function ( row, data, index ) {
            $(row).addClass('selected')
        },
        language: {
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
            }
        }
    });
}

/* SURAT PENAWARAN */

/* SPK */

function loadDataTableLetterAgreement(){
    window.table = $('#letter-agreement-datatable').DataTable({
        "scrollCollapse": true,
        "scrollY": '400px',
		"scrollX": true,
		"scroller": true,
        "responsive": true,
        "stateSave": true,
        "serverSide": true,
        "deferRender": true,
        "destroy": true,
        "fixedColumns": {
            left: 2,
            right: 1
        },
        "iDisplayInLength": 10,
        "order": [[0, 'asc']],
        ajax: {
            url: window.location.href + '/datatable',
            type: 'GET',
            data: {
                
            },
            beforeSend: function() {
                /* loadingOpen(); */
            },
            complete: function() {
                /* loadingClose(); */
            },
            error: function() {
                /* loadingClose(); */
                errorConnection();
            }
        },
        columns: [
            { name: 'id', searchable: false, className: 'text-center' },
            { name: 'code', className: '' },
            { name: 'user_id', className: '' },
            { name: 'project_id', className: '' },
            { name: 'post_date', className: '' },
            { name: 'name', className: '' },
            { name: 'address', className: '' },
            { name: 'position', className: '' },
            { name: 'phone', className: '' },
            { name: 'name_ref', className: '' },
            { name: 'type_building', className: '' },
            { name: 'name_builder', className: '' },
            { name: 'persil_location', className: '' },
            { name: 'land_area', className: '' },
            { name: 'building_area', className: '' },
            { name: 'subdistrict', className: '' },
            { name: 'district', className: '' },
            { name: 'city', className: '' },
            { name: 'province', className: '' },
            { name: 'road_status', className: '' },
            { name: 'nominal_1', className: 'text-right' },
            { name: 'nominal_2', className: 'text-right' },
            { name: 'nominal_3', className: 'text-right' },
            { name: 'estimate_date_start', className: '' },
            { name: 'estimate_date_end', className: '' },
            { name: 'note', className: '' },
            { name: 'status', className: 'text-center' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center' },
        ],
        createdRow: function ( row, data, index ) {
            $(row).addClass('selected')
        },
        language: {
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
            }
        }
    });
}

function getProjectInfoSpk(){
    if($('#project_id').val()){
        let data = $('#project_id').select2('data')[0];
        $('#name').val(data.customer);
        $('#address').val(data.address);
        $('#phone').val(data.phone);
    }else{
        $('#name,#address,#phone').val('');
    }
}

/* SPK */

/* HASIL SURVEY */

function loadDataTableSurveyResult(){
    window.table = $('#survey-result-datatable').DataTable({
        "scrollCollapse": true,
        "scrollY": '400px',
		"scrollX": true,
		"scroller": true,
        "responsive": true,
        "stateSave": true,
        "serverSide": true,
        "deferRender": true,
        "destroy": true,
        "fixedColumns": {
            left: 2,
            right: 1
        },
        "iDisplayInLength": 10,
        "order": [[0, 'asc']],
        ajax: {
            url: window.location.href + '/datatable',
            type: 'GET',
            data: {
                
            },
            beforeSend: function() {
                /* loadingOpen(); */
            },
            complete: function() {
                /* loadingClose(); */
            },
            error: function() {
                /* loadingClose(); */
                errorConnection();
            }
        },
        columns: [
            { name: 'id', searchable: false, className: 'text-center' },
            { name: 'code', className: '' },
            { name: 'user_id', className: '' },
            { name: 'project_id', className: '' },
            { name: 'post_date', className: '' },
            { name: 'note', className: '' },
            { name: 'attachment', searchable: false, orderable: false, className: 'text-center' },
            { name: 'status', searchable: false, orderable: false, className: 'text-center' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center' },
        ],
        createdRow: function ( row, data, index ) {
            $(row).addClass('selected')
        },
        language: {
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
            }
        }
    });
}

/* HASIL SURVEY */