$("#btnSignIn").click(function() {
    $("#btnSignIn").attr("disabled",true);
    $("#btnSignIn").html('Loading...<i class="fa fa-spinner fa-spin"></i>');

    $.ajax({
        url: url_ori+'/process-auth-admin',
        type: 'POST',
        data: $("#formData").serialize(),
        dataType: 'JSON',
        headers: {'X-CSRF-TOKEN': tokenCsrf},
        success: function(resp) {
            if (resp.status == true) {
                $("#btnSignIn").attr("disabled",true);
                $("#btnSignIn").html('Harap Menunggu...<i class="fa fa-spinner fa-spin"></i>');

                setTimeout(function() {
                    $("#btnSignIn").attr("disabled",false);
                    $("#btnSignIn").html('<i class="fas fa-sign-in-alt"></i>&nbsp; SIGN IN');
                    window.location.href = resp.data.redirect;
                },2000);
            } else {
                Swal.fire({
                    icon: 'error',
                    html: resp.message,
                });

                $("#btnSignIn").attr("disabled",false);
                $("#btnSignIn").html('<i class="fas fa-sign-in-alt"></i>&nbsp; SIGN IN');
            }
        },
        error: function(xhr,error_text,statusText) {
            Swal.fire({
                icon: 'error',
                html: error_text+"<br>"+statusText,
            });
        }
    });
})

$(document).keypress(function(e) {
    if (e.which == 13) {
        $("#btnSignIn").click();
    }
})