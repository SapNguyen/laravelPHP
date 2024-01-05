$(document).ready(function () {
    $('#btn_register').click(function (e) { 
        $('#btn_register').prop('disabled',true);
        var formData = new FormData($("#form_register")[0]);
        $.ajax({
            type: "post",
            url: "/register",
            data: formData,
            dataType: "json",
            success: function (response) {
                if(response.error){
                    $(".alert-danger").text(response.error);
                    $(".alert-danger").show();
                    $('#btn_register').prop('disabled',false);
                }else{
                    $(".alert-danger").hide();
                    $('.success-screen').show();
                    setTimeout(() => {
                        window.location.href= response.dir
                    }, 500);
                }
            },
            error:function(response){
                alert("Tài khoản phải là định dạng email");
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });
    $('.form-control').keyup(function (e) { 
        validate('#btn_register',3);
    });

    $('.form-control').blur(function (e) { 
        validate('#btn_register',3);
    });

    function validate(btn,num) { 
        var validate = 'true';
        for (let i = 0; i < num; i++) {
            var val = $('.form-control').eq(i).val();
            if(!val){
                validate = 'false';
                break;
            }
        }
        if(validate == 'true'){
            $(btn).removeClass('btn_disabled');
            $(btn).prop('disabled',false);
        }
        else{
            $(btn).addClass('btn_disabled');
            $(btn).prop('disabled',true);
        }
    }
});