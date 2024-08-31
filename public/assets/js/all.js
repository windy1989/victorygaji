Dropzone.options.dropzoneMultiple = {
    paramName: "file",
    maxFilesize: 200000,
    maxFiles: 1,
    /* autoProcessQueue: false, */
    autoQueue: true,
	timeout: 0,
    addRemoveLinks : true,
    acceptedFiles: ".xlsx,.xls",
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
});

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

function history(code){
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
}

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