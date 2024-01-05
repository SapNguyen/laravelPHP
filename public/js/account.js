$(document).ready(function () {
    $('#btn_pass').click(function (e) { 
        e.preventDefault();
        var formData = new FormData($("#form_password")[0]);
        $.ajax({
            type: "POST",
            url: "/account/password",
            data: formData,
            dataType: "json",
            success: function (response) {
                if(response.redirect){
                    window.location.href = response.redirect;
                }
                else{
                    if (response.success == 'true') {
                        $('#form_password')[0].reset();
                        $('#form_password').find('input').removeClass('input-error');
                        $('.tr_error').hide();
                        $('.success-screen').show();
                        setTimeout(() => {
                            $('.success-screen').fadeOut();
                        }, 800);
                    }
                    else{
                        if (response.error == 'confirm') {
                            errorInput('Mật khẩu mới và mật khẩu xác nhận không giống nhau','#tr_confirm');
                        }
                        if(response.error == 'pass'){
                            errorInput('Mật khẩu hiện tại không chính xác, vui lòng thử lại','#tr_pass');
                        }
                    }
                }
                
            },
            error: function (response) {
                alert('error');
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });

    $('#btn_profile').click(function (e) { 
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "/account/profile",
            data: {
                name: $("#name").val(),
                phone: $('#phone').val().replace("(+84)","0").replace(/\s/g, ""),
                address: $("#address").val()
            },
            dataType: "json",
            success: function (response) {
                if(response.redirect){
                    window.location.href = response.redirect;
                }
                else{
                    $('.success-screen').show();
                    setTimeout(() => {
                        $('.success-screen').fadeOut();
                    }, 800);
                    $('#name_user').text(response.name);
                }
            },
            error: function (response) {
                alert('error');
            }
        });
    });

    $('.success-overlay').click(function (e) { 
        $('.success-screen').hide();
        
    });
    
    $('.form-control').keyup(function (e) { 
        ValidateForm(e);
    });

    $('#phone').blur(function(e){
        const arr = ValidateForm(e);
        if(arr[0] == "true"){
            $('#phone').val('(+84)' + ' ' + arr[1]);
        }
    });
    function ValidateForm(event){
        var table = $(event.delegateTarget).parent().parent().parent();
        var validate = 'true';
        for (let i = 0;; i++) {
            var tr = table.children('tr.tr_input').eq(i);
            if (!tr.find('input').val()) {
                validate = 'false';
                id = tr.attr('id');
                errorInput('Vui lòng nhập thông tin','#' + id);
                break;
            }
            if(tr.html() == $('#tr_phone').html()){
                var val = $('#phone').val().replace(/\s/g, "");
                if(val.indexOf("(+84)") == '0' || val.indexOf("+84") == '0'){
                    
                    if(val.indexOf("(+84)") == '0'){
                        val = val.replace("(+84)","");
                    }
                    if(val.indexOf("+84") == '0'){
                        val = val.replace("+84","");
                    }
                    
                }
                var index = val.search(/[^0-9\.]+/g);
                if(index != -1){
                    validate = 'false';
                    errorInput('Số điện thoại không hợp lệ','#tr_phone');
                    break;
                }
                if(val.indexOf("0") == '0'){
                    val = val.replace("0","");
                }
                if(val.indexOf("3") == '0' || val.indexOf("5") == '0' || 
                val.indexOf("7") == '0' || val.indexOf("8") == '0' || 
                val.indexOf("9") == '0'){
                    if(val.length != 9){
                        validate = 'false';
                        errorInput('Số điện thoại không hợp lệ','#tr_phone');
                        break;
                    }
                }
                else{
                    validate = 'false';
                    errorInput('Số điện thoại không hợp lệ','#tr_phone');
                    break;
                }    
            }
            if(table.children('tr.tr_input').last().html() == tr.html()){
                break;
            }
            
        }
        if(validate == 'true'){
            $('input').removeClass('input-error');
            $('.tr_error').hide();
            table.find('button').attr('class','button');
            table.find('button').prop('disabled',false);
        }
        else{
            table.find('button').attr('class','btn-disabled');
            table.find('button').prop('disabled',true);
        }
        return [validate,val];
    }

    function errorInput(msg, target) { 
        $(target).siblings().find('input').removeClass('input-error');
        $('.tr_error').hide();
        var tr = document.createElement('tr');
            tr.setAttribute('class','tr_error');
        var td1 = document.createElement('td');
        var td2 = document.createElement('td');
            td2.setAttribute('style','padding:0 0 0 15px');
        var p = document.createElement('p');
            p.setAttribute('style','color: #ff424f;font-size:14px');
        p.innerHTML = msg;
        td2.append(p);
        tr.append(td1);
        tr.append(td2);
        $(target).after(tr);
        $(target).find('input').addClass('input-error');
     }
});