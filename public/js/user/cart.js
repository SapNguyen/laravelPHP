$(document).ready(function () {
    if ($('#count_deleted').text().trim() != '0') {
        alert('Đã xóa ' + $('#count_deleted').text() + ' sản phẩm khỏi giỏ hàng do không còn tồn tại trong cửa hàng')
    }
    isChoiceAll();
    const cart = [];
    const psc = [];
    for (let i = 0;; i++) {
        cart.push($('.data').eq(i).text().trim());
        if ($('.data').last().html() == $('.data').eq(i).html()) {
            break;
        }
    }
    for (let i = 0;; i++) {
        psc.push($('.psc').eq(i).text().trim());
        if ($('.psc').last().html() == $('.psc').eq(i).html()) {
            break;
        }
    }
    $('.btn-change').click(function (e) { 
        var act = $(e.delegateTarget).children().text().trim();
        var i_quantity = $(e.delegateTarget).siblings('div').children('input');
        var parent = $(e.delegateTarget).parents(".item-box");
        var pid = parent.find(".checkbox").attr('id');
        var size = parent.find('.size').text().trim();
        var color = parent.find('.color').text().trim();
        var selected = parent.find('.check-one').prop('checked');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        if($(e.delegateTarget).attr('class').indexOf('btn-disabled') == -1){
            $.ajax({
                type: "post",
                url: "/cart/quantity",
                data: {
                    action: act,
                    quantity: i_quantity.val(),
                    id: pid,
                    size: size,
                    color: color
                },
                dataType: "json",
                success: function (response) {
                    if(response.redirect){
                        window.location.href = response.redirect;
                    }
                    else{
                        var max = i_quantity.attr('max');
                        if(parseInt(response.quantity) == 1){
                            parent.find('.btn_dec').addClass('btn-disabled');
                            parent.find('.btn_inc').removeClass('btn-disabled');
                        }
                        else if(parseInt(response.quantity) >= parseInt(max)){
                            parent.find('.btn_inc').addClass('btn-disabled');
                            parent.find('.btn_dec').removeClass('btn-disabled');
                        }
                        else if(parseInt(response.quantity) < parseInt(max) && parseInt(response.quantity) > 1){
                            parent.find('.btn_inc').removeClass('btn-disabled');
                            parent.find('.btn_dec').removeClass('btn-disabled');
                            
                        }
                        if(selected){
                            var cur_amount = $('#s_amount').text().replace(/[^0-9\.]+/g, "");
                            var cur_price = $('#s_price').text().replace(/[^0-9\.]+/g, "");
                            if(act == 'remove'){
                                cur_amount = parseInt(cur_amount) + Number(response.diff_amount) ;
                                cur_price = Number(cur_price) + Number(response.diff_price) ;
                                cur_price = addCommas(cur_price);
                            }
                            else if (act == 'add'){
                                cur_amount = parseInt(cur_amount) + Number(response.diff_amount) ;
                                cur_price = Number(cur_price) + Number(response.diff_price) ;
                                cur_price = addCommas(cur_price);
                            }
                            $('#s_amount').text('Tổng thanh toán (' + cur_amount + ' Sản phẩm): ');
                            $('#s_price').text(cur_price + '₫');
                        }
                        i_quantity.val(response.quantity);
                        $(e.delegateTarget).parents(".item-box").find('.div_price_all').text(addCommas(response.s_price) + '₫');
                    }
                },
                error: function (response) {
                    alert('error');
                }
            });
        }else{
            var max = i_quantity.attr('max');
            if(parseInt(i_quantity.val()) == parseInt(max)){
                alertShow('Rất tiếc, mặt hàng này hiện tại chỉ có sẵn ' + max + ' sản phẩm');
            }
        }
    });
    $('.quantity').blur(function (e) { 
        var i_quantity = $(e.delegateTarget);
        var parent = $(e.delegateTarget).parents(".item-box");
        var quantity = i_quantity.val();
        var max = i_quantity.attr('max');
        var pid = parent.find(".checkbox").attr('id');
        var size = parent.find('.size').text().trim();
        var color = parent.find('.color').text().trim();
        var selected = parent.find('.check-one').prop('checked');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        if(parseInt(quantity) >= parseInt(max)){
            if(parseInt(quantity) > parseInt(max)){
                quantity = max;
                alertShow('Rất tiếc, mặt hàng này hiện tại chỉ có sẵn ' + max + ' sản phẩm');
            }
            parent.find('.btn_inc').addClass('btn-disabled');
            parent.find('.btn_dec').removeClass('btn-disabled');
        }
        else if(parseInt(quantity) == 1){
            parent.find('.btn_inc').removeClass('btn-disabled');
            parent.find('.btn_dec').addClass('btn-disabled');
        }
        else if (parseInt(quantity) > 1 && parseInt(quantity) < parseInt(max)){
            parent.find('.btn_inc').removeClass('btn-disabled');
            parent.find('.btn_dec').removeClass('btn-disabled');
        }
        $.ajax({
            type: "post",
            url: "/cart/quantity",
            data: {
                quantity: quantity,
                id: pid,
                size: size,
                color: color
            },
            dataType: "json",
            success: function (response) {
                if(response.redirect){
                    window.location.href = response.redirect;
                }else{
                    i_quantity.val(parseInt(response.quantity));
                    $(e.delegateTarget).parents(".item-box").find('.div_price_all').text(addCommas(response.s_price) + '₫');
                    if(selected){
                        var cur_amount = $('#s_amount').text().replace(/[^0-9\.]+/g, "");
                        var cur_price = $('#s_price').text().replace(/[^0-9\.]+/g, "");
                        cur_price = Number(cur_price) + Number(response.diff_price);
                        cur_price = addCommas(cur_price);
                        cur_amount= Number(cur_amount) + Number(response.diff_amount);
                        $('#s_amount').text('Tổng thanh toán (' + cur_amount + ' Sản phẩm): ');
                        $('#s_price').text(cur_price + '₫');
                    }
                }
            },
            error: function (response) {
                alert('error');
            }
        });

        
    });

    $('#btn_confirm').click(function (e) { 
        $('.alert-screen').hide();
    });

    $('#btn_payment').click(function (e) { 
        $('#btn_payment').attr('disable',true);
        const arr = [];
        for (let i = 0;; i++) {
            if($('.check-one').eq(i).prop('checked') == true){
                var val = $('#choice').val();
                id = $('.check-one').eq(i).attr('id');
                size = $('.check-one').eq(i).parents('.item-box').find('.size').text().trim();
                color = $('.check-one').eq(i).parents('.item-box').find('.color').text().trim();
                arr.push(val + " " + id + ',' + size + ',' +color);
            }
            if($('.check-one').eq(i).parents('.item-box').html() == $('.product').last().html()){
                break;
            }
        }
        $('#choice').val(arr.join(';'));
        if($('#choice').val() == ""){
            alertShow('Bạn chưa chọn sản phẩm để mua');
        }else{
            $('#submit').click();
        }
    });

    $('.check-all').click(function (e) { 
        if ($(e.delegateTarget).prop('checked') == true) {
            ajaxSelect('all',null,null, 'add');
            for (let i = 0; i < 2; i++) {
                $('.check-all').eq(i).prop('checked',true);
            }
            for (let i = 0;; i++) {
                $('.check-one').eq(i).prop('checked',true);
                if($('.check-one').eq(i).parents('.item-box').html() == $('.product').last().html()){
                    break;
                }
            }
        }
        else{
            ajaxSelect('all',null,null,'remove');
            for (let i = 0; i < 2; i++) {
                $('.check-all').eq(i).prop('checked',false);
            }
            for (let i = 0;; i++) {
                $('.check-one').eq(i).prop('checked',false);
                if($('.check-one').eq(i).parents('.item-box').html() == $('.product').last().html()){
                    break;
                }
            }
        }
    });

    $('.check-one').change(function (e) { 
        const obj = $(e.delegateTarget);
        var size = obj.parents('.item-box').find('.size').text().trim();
        var color = obj.parents('.item-box').find('.color').text().trim();
        var pid = obj.attr('id');
        if (obj.prop('checked') == true) {
            ajaxSelect(pid,size,color,'add');
            var all = 'true'
            for (let i = 0;; i++) {
                if($('.check-one').eq(i).prop('checked') == false){
                    all = 'false';
                    break;
                }
                if($('.check-one').eq(i).parents('.item-box').html() == $('.product').last().html()){
                    break;
                }
            }
            if(all == 'true'){
                for (let i = 0; i < 2; i++) {
                    $('.check-all').eq(i).prop('checked',true);
                }
            }
        }
        else{
            ajaxSelect(pid,size,color,'remove');
            for (let i = 0; i < 2; i++) {
                $('.check-all').eq(i).prop('checked',false);
            }
        }
        
    });

    function ajaxSelect(pid,size,color,status) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "/cart/selectProduct",
            data: { 
                pid: pid,
                size: size,
                color: color    
            },
            dataType: "json",
            success: function (response) {
                if(response.redirect){
                    window.location.href = response.redirect;
                }
                else{
                    var cur_amount = $('#s_amount').text().replace(/[^0-9\.]+/g, "");
                    var cur_price = $('#s_price').text().replace(/[^0-9\.]+/g, "");
                    if(status == 'add'){
                        cur_amount = parseInt(response.s_amount) + parseInt(cur_amount);
                        cur_price = Number(response.s_price) + Number(cur_price);
                        cur_price = addCommas(cur_price);
                        if(pid == 'all'){
                            $('#s_price').text(addCommas(response.s_price) + '₫');
                            $('#s_amount').text('Tổng thanh toán (' + response.s_amount + ' Sản phẩm): ');
                        }
                        else{
                            $('#s_amount').text('Tổng thanh toán (' + cur_amount + ' Sản phẩm): ');
                            $('#s_price').text(cur_price + '₫');
                        }
                    }
                    else if (status == 'remove'){
                        cur_amount = parseInt(cur_amount) - parseInt(response.s_amount) ;
                        cur_price = Number(cur_price) - Number(response.s_price) ;
                        cur_price = addCommas(cur_price);
                        $('#s_amount').text('Tổng thanh toán (' + cur_amount + ' Sản phẩm): ');
                        $('#s_price').text(cur_price + '₫');
                    }
                    
                    
                    
                }
            },
            error: function (response) {
                alert('error');
            }
        });
    }
    function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    $('.p_remove').click(function (e) { 
        e.preventDefault();
        var parent = $(e.delegateTarget).parents(".item-box");
        var pid = parent.find(".checkbox").attr('id');
        var size = parent.find('.size').text().trim();
        var color = parent.find('.color').text().trim();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "/cart/remove",
            data: {
                id: pid,
                size: size,
                color: color
            },
            success: function (response) {
                if(response.redirect){
                    window.location.href = response.redirect;
                }
                else{
                    $(e.delegateTarget).parents('.item-box').remove();
                    isChoiceAll();
                }
            },
            error: function (response) {
                alert('error');
            }
        });
        
    });

    $('.div_size').click(function (e) { 
        $(e.delegateTarget).siblings('.div_choose-size').attr('class','');
        $('.item-box').parent().find('.div_choose-size').fadeOut(200);
        $(e.delegateTarget).siblings('div').attr('class','div_choose-size');
        $(e.delegateTarget).siblings('.div_choose-size').fadeToggle(200);
        var size = $(e.delegateTarget).parents('.item-box').find('.size').text();
        var color = $(e.delegateTarget).parents('.item-box').find('.color').text();
        var parent = $(e.delegateTarget).siblings('.div_choose-size');
        for (let i = 0;; i++) {
            var obj = parent.find('.size-box').eq(i);
            if (obj.text().trim() == size) {
                if ($(e.delegateTarget).siblings('.div_choose-size').attr('style') == 'display: block; opacity: 1;') {
                    setTimeout(() => {
                        obj.siblings('.size-box').removeClass('selected');
                        obj.addClass('selected');
                        
                    }, 200);
                }else{
                    obj.siblings('.size-box').removeClass('selected');
                    obj.addClass('selected');
                }
            }
            else if(obj.text().trim() == color){
                if ($(e.delegateTarget).siblings('.div_choose-size').attr('style') == 'display: block; opacity: 1;') {
                    setTimeout(() => {
                        obj.siblings('.size-box').removeClass('selected');
                        obj.addClass('selected');
                        
                    }, 200);
                }else{
                    obj.siblings('.size-box').removeClass('selected');
                    obj.addClass('selected');
                }
                break;
            }
            if(obj.html() == parent.find('.size-box').last().html()){
                break;
            }
            
        }
        check(e);
    });
    $(document).click(function (e) { 
        var $trigger = $(".div_size");
        var $trigger2 = $(".div_choose-size");
        if($trigger !== e.target && !$trigger.has(e.target).length && $trigger2 !== e.target && !$trigger2.has(e.target).length){
            $(".div_choose-size").fadeOut(200);
        } 
    });

    $('.size-box').click(function (e) {
        if($(e.delegateTarget).attr('class').indexOf('size-box-disabled') == -1){
            $(e.delegateTarget).siblings('div').removeClass('selected');
            $(e.delegateTarget).addClass('selected');
        }
        check(e);
        if($('.selected').eq(1).attr('class').indexOf('sold-out') != -1){
            $('.btn_confirm-size').addClass('btn-disabled');
        }
        else{
            $('.btn_confirm-size').removeClass('btn-disabled');
        } 
    });
    function check(e) {
        var parent = $(e.delegateTarget).parents('.item-box');
        var size = parent.find('.selected').eq(0).text().trim();
        var color = parent.find('.selected').eq(1).text().trim();
        var cid = parent.find('.cid').text().trim();
        var pid = parent.find('.checkbox').attr('id').trim();
        const size_disabled = [];
        const color_disabled = [];
        const color_sold = [];
        var obj = parent.find('.selected').eq(0).parent().find('.size-box');
        for (let i = 0;; i++) {
            
            for (let j = 0; j < cart.length; j++) {
                var arr = cart[j].split(";");
                if(arr[1] == pid && arr[2] == color && arr[3] == obj.eq(i).text().trim()){
                    if(arr[0] != cid){
                        size_disabled.push(obj.eq(i).text().trim());
                    }
                }
            }
            // var search = false;
            // var quantity;
            // for (let j = 0; j < psc.length; j++) {
            //     var arr = psc[j].split(";");
            //     if(obj.eq(i).text().trim() != size){
                    
            //     }
            //     if (arr[0] == pid && arr[1] == obj.eq(i).text().trim() && arr[2] == color) {
            //         search = true;
            //         quantity = arr[3];
            //     }
            // }
            // if (search) {
            //     if (Number(quantity) <= 0) {
            //         size_disabled.push(obj.eq(i).text().trim());
            //     }
            // }
            // else{
            //     size_disabled.push(obj.eq(i).text().trim());
            // }
            
            if (obj.last().html() == obj.eq(i).html()) {
                break;
            }
        }
        for (let i = 0;; i++) {
            obj.eq(i).removeClass('size-box-disabled');
            for (let j = 0; j < size_disabled.length; j++) {
                if (obj.eq(i).text().trim() == size_disabled[j]) {
                    obj.eq(i).addClass('size-box-disabled');
                }
            }
            
            if (obj.last().html() == obj.eq(i).html()) {
                break;
            }
        }
        var obj = parent.find('.selected').eq(1).parent().find('.size-box');
        for (let i = 0;; i++) {
            for (let j = 0; j < cart.length; j++) {
                var arr = cart[j].split(";");
                if(arr[1] == pid && arr[3] == size && arr[2] == obj.eq(i).text().trim()){
                    if(arr[0] != cid){
                        color_disabled.push(obj.eq(i).text().trim());
                    }
                }
            }
            var search = false;
            var quantity;
            for (let j = 0; j < psc.length; j++) {
                var arr = psc[j].split(";");
                if (arr[0] == pid && arr[1] == size && arr[2] == obj.eq(i).text().trim()) {
                    search = true;
                    quantity = arr[3];
                    break;
                } 
            }
            if (search) {
                if (Number(quantity) <= 0) {
                    color_sold.push(obj.eq(i).text().trim());
                }
            }
            else{
                color_sold.push(obj.eq(i).text().trim());
            }
            if (obj.last().html() == obj.eq(i).html()) {
                break;
            }
        }
        for (let i = 0;; i++) {
            obj.eq(i).removeClass('sold-out');
            for (let j = 0; j < color_sold.length; j++) {
                if (obj.eq(i).text().trim() == color_sold[j]) {
                    obj.eq(i).addClass('sold-out');
                }
            }
            
            if (obj.last().html() == obj.eq(i).html()) {
                break;
            }
        }
        for (let i = 0;; i++) {
            obj.eq(i).removeClass('size-box-disabled');
            for (let j = 0; j < color_disabled.length; j++) {
                if (obj.eq(i).text().trim() == color_disabled[j]) {
                    obj.eq(i).addClass('size-box-disabled');
                }
            }
            
            if (obj.last().html() == obj.eq(i).html()) {
                break;
            }
        }
    }
    $('.btn_cancel-size').click(function (e) { 
        $(e.delegateTarget).parents('.div_choose-size').fadeOut(200);
    });
    $('.btn_confirm-size').click(function (e) {
        if ($(e.delegateTarget).attr('class').indexOf('btn-disabled') == -1) {
            var parent = $(e.delegateTarget).parents('.item-box');
            var size = parent.find('.selected').eq(0).text();
            var color = parent.find('.selected').eq(1).text();
            var pid = parent.find('.checkbox').attr('id');
            var cid = parent.find('.cid').text();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                url: "/cart/updateSize",
                data: {
                    pid: pid,
                    size: size,
                    color: color,
                    cid: cid
                },
                dataType: "json",
                success: function (response) {
                    if(response.soldOut){
                        alert(response.soldOut);
                    }
                    else if(response.quantity){
                        parent.find('.size').text(size);
                        parent.find('.color').text(color);
                        parent.find('.quantity').attr('max',response.quantity);
                        parent.find('.product-img').attr('src','/img/product/'+ pid +'/'+response.img);
                        if(parseInt(parent.find('.quantity').val()) >= parseInt(response.quantity)){
                            parent.find('.quantity').val(response.quantity);
                            parent.find('.btn_inc').addClass('btn-disabled');
                        }else{
                            parent.find('.btn_inc').removeClass('btn-disabled');
                        }
                        parent.find('.div_choose-size').fadeOut(200);
                    }
                    else{
                        parent.find('.div_choose-size').fadeOut(200);
                    }
                },
                error: function (response) {
                    alert('error');
                }
            });
        }
    });

    function alertShow(msg) {
        $('.alert-screen').find('#msg').text(msg);
        $('.alert-screen').show();
    }
    function isChoiceAll() {
        var all = true;
        for (let i = 0;; i++) {
            var obj = $('.check-one').parents('.item-box').parent().find('.item-box');
            if(obj.eq(i).find('.check-one').prop('checked') == false){
                all = false;
                break;
            }
            if(obj.last().html() == obj.eq(i).html()){
                break;
            }
        }
        if(all){
            $('.check-all').prop('checked',true);
        }
    }
});