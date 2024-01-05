var feedback_star, feedback_comment, uid, pid, rid,feedback_count_product,current_obj_feedback,href_status;
$(document).ready(function () {
    uid = $('#uid').text();
    for (let i = 0; i < 5; i++) {
        var href = $('.r-tab').eq(i).attr('href').replace("/account/receipt/","");
        if($('.r-tab').eq(i).attr('class').indexOf('active') != -1){
            href_status = href;
        }
    }
    $('.r-tab').click(function (e) {
        $(e.delegateTarget).parent().find('.active').removeClass('active');
        $(e.delegateTarget).addClass('active');
    });
    $('.btn-drop').click(function (e) { 
        e.preventDefault();
        var drop = confirm("Xác nhận hủy đơn hàng?");
        if(drop){
            var rid = $(e.delegateTarget).attr('id');
            $.ajax({
                type: "get",
                url: "/receipt/drop",
                data: {
                    rid: rid,
                    uid: uid
                },
                success: function (response) {
                    if(response.drop == 'success'){
                        if(href_status == 0){
                            $(e.delegateTarget).parents('.receipt').remove();
                            if(response.count == 0){
                                var div = createAlertEmptyReceipt();
                                $('#receipt').append(div);
                            }
                        }
                        else{
                            $('.success-screen').find('p').text("Đã hủy đơn hàng");
                            $('.success-screen').show();
                            setTimeout(() => {
                                $('.success-screen').fadeOut();
                            }, 700);
                            $(e.delegateTarget).parents('.receipt').find('.status').text("ĐÃ HỦY");
                            $(e.delegateTarget).remove();
                        }
                    }
                },
                error: function (response) {
                    alert('error');
                }
            });
        }
    });
    
    $('.btn-confirm').click(function (e) { 
        e.preventDefault();
        var rid = $(e.delegateTarget).attr('id');
        if(rid){
            $.ajax({
                type: "get",
                url: "/receipt/confirm",
                data: {
                    rid: rid,
                    uid: uid
                },
                success: function (response) {
                    if(response.confirm == 'success'){
                        $('.success-screen').find('p').text("Xác nhận thành công");
                        $('.success-screen').show();
                        setTimeout(() => {
                            $('.success-screen').fadeOut();
                        }, 700);
                        if(href_status == 1){
                            $(e.delegateTarget).parents('.receipt').remove();
                            if(response.count == 0){
                                var div = createAlertEmptyReceipt();
                                $('#receipt').append(div);
                            }
                        }
                        else{
                            $(e.delegateTarget).parents('.receipt').find('.status').text("HOÀN THÀNH");
                            
                            var div_btn = document.createElement('div');
                                div_btn.setAttribute('class','feedback btn-red');
                                div_btn.innerHTML = 'Đánh giá';
                                div_btn.setAttribute('onclick','writeFeedback(this)');
                            $(e.delegateTarget).parent().prepend(div_btn);
                            $(e.delegateTarget).remove();
                        }
                        
                    }
                },
                error: function (response) {
                    alert('error');
                }
            });
        }
    });
    $('.feedback').click(function (e) { 
        current_obj_feedback = $(e.delegateTarget).parents('.receipt-footer');
        rid = $(e.delegateTarget).parents('.receipt').attr('id');
        $('.error-txt').text('');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "/feedback/writeRequest",
            data: {
                rid: rid
            },
            success: function (response) {
                if(response.write == 'true'){
                    $('.feedback-product-box').remove();
                    feedback_count_product = response.pid.length;
                    for (let i = response.pid.length-1; i >= 0; i--) {
                        var prod_id = response.pid[i].product_id;
                        var div = document.createElement('div');
                            div.setAttribute('class','feedback-product-box');
                            div.setAttribute('id',prod_id);
                        for (let j = 0; j < response.products.length; j++) {
                            if (response.products[j].product_id == prod_id) {
                                var img = "/img/product/"+response.products[j].product_id + "/" +response.products[j].product_image;
                                var name = response.products[j].product_name;
                                var size = response.products[j].size
                                var color = response.products[j].color
                                var obj = createFeedbackProduct(img,name,size,color);
                                div.append(obj);
                            }
                        }
                        var arr = createFeedback();
                        div.append(arr[0]);
                        div.append(arr[1]);
                        $('.feedback-box').prepend(div);
                        $('.feedback-screen').show();
                    }
                }
            },
            error: function (response) {
                alert('error');
            }
        });
    });
    $('.view-feedback').click(function (e) { 
        $(e.delegateTarget).parents('.receipt').attr('id');
        rid = $(e.delegateTarget).parents('.receipt').attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "/feedback/readRequest",
            data: {
                rid: rid,
                uid: uid
            },
            success: function (response) {
                if(response.read == 'true'){
                    $('.feedback-product-view').remove();
                    feedback_count_product = response.feedbacks.length;
                    for (let i = response.feedbacks.length-1; i >= 0; i--) {
                        var prod_id = response.feedbacks[i].product_id;
                        var div = document.createElement('div');
                            div.setAttribute('class','feedback-product-view');
                        for (let j = 0; j < response.products.length; j++) {
                            if (response.products[j].product_id == prod_id) {
                                var img = "/img/product/"+response.products[j].product_id + "/" +response.products[j].product_image;
                                var name = response.products[j].product_name;
                                var size = response.products[j].size
                                var color = response.products[j].color
                                var obj = createFeedbackProduct(img,name,size,color);
                                div.append(obj);
                            }
                        }
                        var div1 = createComment(response.feedbacks[i].name,response.feedbacks[i].star,response.feedbacks[i].comment);
                        div.append(div1);
                        $('.feedback-box-view').prepend(div);
                        $('.feedback-screen-view').show();
                    }
                }
            },
            error: function (response) {
                alert('error');
            }
        });
    });
    $('.btn_cancel-feedback').click(function (e) { 
        $('.feedback-screen').hide();
    });
    $('.btn_confirm-feedback').click(function (e) { 
        var star_choosen = false;
        const comment = [];
        const ta = [];
        const star = [];
        const pid = [];
        for (let j = 0; j < feedback_count_product; j++) {
            var choose = false;
            var prod_id = $('.feedback-product-box').eq(j).attr('id');
            var star_id = 0;
            for (let i = 0; i < 5; i++) {
                var obj = $('.starbox').eq(j).find('.div_star').eq(i);
                if(obj.find('.star-fill').attr('class').indexOf('star-active') != -1){
                    star_choosen = true;
                    choose = true;
                    star_id += 1;
                }
            }
            var text_area = $('.feedback-product-box').eq(j).find('textarea');
            if(text_area.val() && !choose){
                $('.feedback-product-box').eq(j).find('.starbox').addClass('error-starbox');
                $('.error-txt').text('Vui lòng chọn số sao đánh giá');
                return 0;
            }
            if(choose){
                pid.push(prod_id);
                star.push(star_id);
                ta.push($('.starbox').eq(j).siblings('textarea'));
                comment.push($('.starbox').eq(j).siblings('textarea').val());
            }
        }
        if(star_choosen){
            var insert = true;
            for (let i = 0; i < comment.length; i++) {
                if (!ta[i].val()) {
                    $('.error-txt').text('Vui lòng nhập đánh giá');
                    ta[i].addClass('error-textarea');
                    insert = false;
                }
            }
            if(insert){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                if(uid, pid, rid, star, comment){
                    var count = feedback_count_product - star.length;
                    var skip = true;
                    if(count > 0){
                        skip = confirm('Còn ' + count + ' Sản phẩm chưa đánh giá. Xác nhận bỏ qua?');
                    } 
                    if(skip){
                        if(star.length == comment.length && comment.length == pid.length){
                            var str_star = star.join("|! ");
                            var str_comment = comment.join("|! ");
                            var str_pid = pid.join("|! ");
                            $.ajax({
                                type: "post",
                                url: "/receipt/feedback",
                                data: {
                                    uid: uid,
                                    pid: str_pid,
                                    rid: rid,
                                    star: str_star,
                                    comment: str_comment
                                },
                                success: function (response) {
                                    current_obj_feedback.find('.feedback').remove();
                                    var div_btn = document.createElement('div');
                                        div_btn.setAttribute('class','btn-gray view-feedback');
                                        div_btn.innerHTML = 'Xem đánh giá';
                                        div_btn.setAttribute('onclick','showFeedback(this)');
                                    current_obj_feedback.prepend(div_btn);
                                    $('.success-screen').show();
                                    setTimeout(() => {
                                        $('.success-screen').hide();
                                        $('.feedback-screen').hide();
                                    }, 500);
                                },
                                error: function (response) {
                                    alert('error');
                                }
                            });
                        }
                        
                    }
                    
                }
            }
        }
        else{
            $('.error-txt').text('Vui lòng chọn số sao đánh giá');
            $('.starbox').addClass('error-starbox');
            
        }

    });
    
    $('.btn_close').click(function (e) { 
        $(e.delegateTarget).parents('.feedback-screen-view').hide();
    });
    
});
function showFeedback(e) {
    $(e).parents('.receipt').attr('id');
    rid = $(e).parents('.receipt').attr('id');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        type: "post",
        url: "/feedback/readRequest",
        data: {
            rid: rid,
            uid: uid
        },
        success: function (response) {
            if(response.read == 'true'){
                $('.feedback-product-view').remove();
                feedback_count_product = response.feedbacks.length;
                for (let i = response.feedbacks.length-1; i >= 0; i--) {
                    var prod_id = response.feedbacks[i].product_id;
                    var div = document.createElement('div');
                        div.setAttribute('class','feedback-product-view');
                    for (let j = 0; j < response.products.length; j++) {
                        if (response.products[j].product_id == prod_id) {
                            var img = "/img/product/"+response.products[j].product_id + "/" +response.products[j].product_image;
                            var name = response.products[j].product_name;
                            var size = response.products[j].size
                            var color = response.products[j].color
                            var obj = createFeedbackProduct(img,name,size,color);
                            div.append(obj);
                        }
                    }
                    var div1 = createComment(response.feedbacks[i].name,response.feedbacks[i].star,response.feedbacks[i].comment);
                    div.append(div1);
                    $('.feedback-box-view').prepend(div);
                    $('.feedback-screen-view').show();
                }
            }
        },
        error: function (response) {
            alert('error');
        }
    });
}
function createFeedback() {
    var div = document.createElement('div');
        div.setAttribute('class','flex starbox');
    for (let i = 1; i < 6; i++) {
        var div1 = document.createElement('div');
            div1.setAttribute('class','div_star');
            div1.setAttribute('id', i);
            div1.setAttribute('onclick','chooseStar(this)');
        var span1 = document.createElement('span');
            span1.setAttribute('class','material-symbols-outlined star');
            span1.setAttribute('style', 'font-size: 30px');
            span1.innerHTML = 'star';
        var span2 = document.createElement('span');
            span2.setAttribute('class','material-symbols-outlined star-fill star-deactive');
            span2.innerHTML = 'star';
        div1.append(span1,span2);
        div.append(div1);
    }
    var ta = document.createElement('textarea');
        ta.setAttribute('class','comment');
        ta.setAttribute('placeholder','Nhập đánh giá của bạn');
        ta.setAttribute('onkeyup','comment(this)');
    return [div,ta];
}
function createFeedbackProduct(img,name,size,color) {
    var div = document.createElement('div');
        div.setAttribute('class','product-feedback');
    var image = document.createElement('img');
        image.setAttribute('class', 'img-product-feedback');
        image.setAttribute('src', img);
    var div1 = document.createElement('div');
        div1.setAttribute('style','margin-left: 10px;text-align: left');
    var p1 = document.createElement('p');
        p1.setAttribute('class','name-product-feedback');
        p1.innerHTML = name;
    var p2 = document.createElement('p');
        p2.setAttribute('class','product-classify');
        p2.innerHTML = "Size:" + size + ", Màu: " + color; 
    div1.append(p1,p2);
    div.append(image,div1);
    return div;
}
function createComment(name,star,comment) {
    var div = document.createElement('div');
        div.setAttribute('class','flex mt-3');
        div.setAttribute('style','justify-content: left');
        var div1 = document.createElement('div');
            div1.setAttribute('class','comment-box');
            var div_avt = document.createElement('div');
                div_avt.setAttribute('class','avata material-symbols-outlined');
                div_avt.innerHTML = 'account_circle';
            var div_comment = document.createElement('div');
                div_comment.setAttribute('class','comment-info');
                var div_name = document.createElement('div');
                    div_name.setAttribute('class','name');
                    div_name.innerHTML = name;
                var div_rate = document.createElement('div');
                    div_rate.setAttribute('class','rate');
                    var div_flex = document.createElement('div');
                        div_flex.setAttribute('class','flex');
                        div_flex.setAttribute('style','justify-content: left');
                        var div_star1 = document.createElement('div');
                            div_star1.setAttribute('style','position: relative;height:20px');
                        for (let i = 0; i < star; i++) {
                            var span1 = document.createElement('span');
                                span1.setAttribute('style','font-size: 20px;cursor: default');   
                                span1.setAttribute('class','material-symbols-outlined star');
                                span1.innerHTML = 'star';
                            var span2 = document.createElement('span');
                                span2.setAttribute('style','width: 100%;font-size: 20px;cursor: default');   
                                span2.setAttribute('class','material-symbols-outlined star-fill');
                                span2.innerHTML = 'star';
                            div_star1.append(span1,span2);
                        }
                        var div_star2 = document.createElement('div');
                            div_star2.setAttribute('style','position: relative;height:20px');
                        for (let i = 0; i < 5 - star; i++) {
                            var span1 = document.createElement('span');
                                span1.setAttribute('style','font-size: 20px;cursor: default');   
                                span1.setAttribute('class','material-symbols-outlined star');
                                span1.innerHTML = 'star';
                            var span2 = document.createElement('span');
                                span2.setAttribute('style','width: 0px;font-size: 20px;cursor: default');   
                                span2.setAttribute('class','material-symbols-outlined star-fill');
                                span2.innerHTML = 'star';
                            div_star2.append(span1,span2);
                        }
                    div_flex.append(div_star1,div_star2);
                div_rate.append(div_flex);
                var div_content = document.createElement('div');
                    div_content.setAttribute('class','content');
                    div_content.innerHTML = comment;
            div_comment.append(div_name,div_rate,div_content);
        div1.append(div_avt,div_comment);
    div.append(div1);
    return div;
}
function chooseStar(e) {
    var star = $(e).attr('id');
    $('.error-txt').text('');
    $('.starbox').removeClass('error-starbox');
    var obj = $(e).parent('.starbox').find('.div_star');
    for (let i = 0; i < star; i++) {
        obj.eq(i).find('.star-fill').removeClass('star-deactive');
        obj.eq(i).find('.star-fill').removeClass('star-active');
        obj.eq(i).find('.star-fill').addClass('star-active');
    }
    for (let i = star; i < 5; i++) {
        obj.eq(i).find('.star-fill').removeClass('star-deactive');
        obj.eq(i).find('.star-fill').removeClass('star-active');
        obj.eq(i).find('.star-fill').addClass('star-deactive');
    }
}
function comment(e){
    $('.error-txt').text('');
    $(e).removeClass('error-textarea');
    feedback_comment = $(e.delegateTarget).val();
}
function writeFeedback(e) {
    current_obj_feedback = $(e).parents('.receipt-footer');
    rid = $(e).parents('.receipt').attr('id');
        $('.error-txt').text('');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "/feedback/writeRequest",
            data: {
                rid: rid
            },
            success: function (response) {
                if(response.write == 'true'){
                    $('.feedback-product-box').remove();
                    feedback_count_product = response.pid.length;
                    for (let i = response.pid.length-1; i >= 0; i--) {
                        var prod_id = response.pid[i].product_id;
                        var div = document.createElement('div');
                            div.setAttribute('class','feedback-product-box');
                            div.setAttribute('id',prod_id);
                        for (let j = 0; j < response.products.length; j++) {
                            if (response.products[j].product_id == prod_id) {
                                var img = "/img/product/"+response.products[j].product_id + "/" +response.products[j].product_image;
                                var name = response.products[j].product_name;
                                var size = response.products[j].size
                                var color = response.products[j].color
                                var obj = createFeedbackProduct(img,name,size,color);
                                div.append(obj);
                            }
                        }
                        var arr = createFeedback();
                        div.append(arr[0]);
                        div.append(arr[1]);
                        $('.feedback-box').prepend(div);
                        $('.feedback-screen').show();
                    }
                }
            },
            error: function (response) {
                alert('error');
            }
        });    
}
function createAlertEmptyReceipt(params) {
    var div = document.createElement('div');
        div.setAttribute('class','empty-alert');
        var span = document.createElement('span');
            span.setAttribute('class','material-symbols-outlined cart-ico');
            span.innerHTML = 'receipt';
        var p = document.createElement('p');
        p.setAttribute('style','text-align: center;font-size:20px');
        p.innerHTML = 'Chưa có đơn hàng';
    div.append(span,p);
    return div;
}
