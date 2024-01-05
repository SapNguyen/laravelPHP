$(document).ready(function () {
    ValidateForm($('#i_phone'));
    $('#i_phone').blur(function (e) { 
        const arr = ValidateForm($(e.delegateTarget));
        if(arr[0] == "true"){
            $('#i_phone').val('(+84)' + ' ' + arr[1]);
        }
    });
    $('.input').keyup(function (e) { 
        const arr = ValidateForm($(e.delegateTarget));
        if ($(e.delegateTarget).attr('id') == 'i_phone') {
            if(arr[0] == "true"){
                $('#i_phone').removeClass('input-error');
                $('#i_phone').siblings('p').text("");
            } 
        }
    });
    function ValidateForm(target){
        var validate = 'true';
        var parent = target.parents('.payment-product-section');
        var inputs = parent.find('.input');
        var val;
        for (let i = 0;; i++) {
            var input = inputs.eq(i);
            id = input.attr('id');
            if (!input.val()) {
                validate = 'false';
                errorInput('Vui lòng nhập thông tin','#' + id);
                break;
            }
            else{
                input.siblings('.error-msg').text("");
                input.removeClass('input-error');
                if (id == 'i_phone') {
                    var phone_valid = 'true';
                    var val = $('#i_phone').val().replace(/\s/g, "");

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
                        phone_valid  = 'false';
                        errorInput('Số điện thoại không hợp lệ','#i_phone');
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
                            phone_valid  = 'false';
                            errorInput('Số điện thoại không hợp lệ','#i_phone');
                            break;
                        }
                    }
                    else{
                        validate = 'false';
                        phone_valid  = 'false';
                        errorInput('Số điện thoại không hợp lệ','#i_phone');
                        break;
                    }
                }
                if(id == 'email'){
                    $pattern = /\S+@\S+\.\S+/g;
                    if (input.val().replace($pattern,"") != "") {
                        validate = 'false';
                        errorInput('Email không đúng định dạng','#' + id);
                        break;
                    }
                    else{
                        validate = 'true';
                        input.siblings('.error-msg').text("");
                        input.removeClass('input-error');
                        break;
                    }
                }
            }
            if(inputs.last().parent().html() == inputs.eq(i).parent().html()){
                break;
            }
        }
        if (validate == 'true') {
            $('#btn_submit').removeClass('btn_disabled');
            $('#btn_submit').attr('disabled', false);
        }
        else{
            $('#btn_submit').addClass('btn_disabled');
            $('#btn_submit').attr('disabled', true);
        }
        return [phone_valid,val,validate];
    }
    function errorInput(msg,target) {
        $(target).siblings('.error-msg').text(msg);
        $(target).addClass('input-error');
    }         

    $('#btn_submit').click(function (e) { 
        var arr = ValidateForm();
        if(arr[2] == 'true'){
            var button = document.createElement('button');
            $('#form_payment').append(button);
            $('#form_payment').find(button).click();
        }
    });
});