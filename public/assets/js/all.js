$(function() {
    
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

});

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