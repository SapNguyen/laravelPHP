$(document).ready(function () {
    $('#btn_login').click(function (e) { 
        $('#btn_login').prop('disabled',true);
        var formData = new FormData($("#form_login")[0]);
        $.ajax({
            type: "post",
            url: "/login",
            data: formData,
            dataType: "json",
            success: function (response) {
                if(response.error){
                    $(".alert-danger").text(response.error);
                    $(".alert-danger").show();
                    $('#btn_login').prop('disabled',false);
                }else{
                    $(".alert-danger").hide();
                    window.location.href= response.dir
                }
            },
            error:function(response){
                alert("error");
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });
    $('.form-control').keyup(function (e) { 
        validate('#btn_login',2);
    });

    $('.form-control').blur(function (e) { 
        validate('#btn_login',2);
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