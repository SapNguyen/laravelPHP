var max_amount,pid,size,color;
$(document).ready(function () {
    pid = $('#pid').text();
    if ($('#alert-deleted').text() != '') {
        var dir = $('#alert-deleted').text();
        alert('Sản phẩm đã bị xóa khỏi cửa hàng');
        window.location.href= dir;
    }
    $('.owl-carousel').owlCarousel({
        loop:false,
        margin:10,
        nav:false,
        dots: false,
        responsive:{
            0:{
                items:4
            },
            600:{
                items: 2
            },
            900:{
                items:3
            },
            1250:{
                items:4
            }
        }
        
    });
    var owl = $('.owl-carousel');
    owl.owlCarousel();
    $('.owl-next').click(function() {
         owl.trigger('next.owl.carousel');
    })
    $('.owl-prev').click(function() {
        owl.trigger('prev.owl.carousel', [300]);
    })
    $(".small-img").click(function (e) { 
        const link = $(e.delegateTarget).attr("src");
        $(".large-img").attr("src", link);
        
    });
    $(".rectangle").click(function (e) { 
        const parent = $(e.delegateTarget).parent()
        parent.siblings(".promotion-info").slideToggle();
        
    });
    $(".size-box").click(function (e) { 
        var txt = $(e.delegateTarget).children("p").attr("class");
        if (txt == "color") {
            var src = $(e.delegateTarget).children("img").attr("src");
            $('.large-img').attr("src", src);
        }
        $(e.delegateTarget).toggleClass("size-active");
        $(e.delegateTarget).siblings("div").attr("class","size-box");
        checkAmount();
    });
    $('.color-box').mouseenter(function (e) { 
        var src = $(e.delegateTarget).children("img").attr("src");
        $('.large-img').attr("src", src);
    });
    $('.color-box').mouseleave(function (e) { 
        var obj = $(e.delegateTarget).parent().find('.size-active').children("img").attr("src");
        if(obj){
            $('.large-img').attr("src", obj);
        }
    });
    $(".nav-info").click(function (e) { 
        const li = $(e.delegateTarget).parent();
        li.siblings().children("a").attr("class","nav-link nav-info");
        $(e.delegateTarget).attr("class","nav-link nav-info-active");
    });
    $(".button").click(function (e) { 
        var disabled = $("#buy-amount").attr('disabled');
        if (!disabled) {
            var sign = $(e.delegateTarget).children('span').text().trim();
            var buy_value = Number($("#buy-amount").val());
            if(sign == "add"){
                if(max_amount){
                    if(buy_value < max_amount){
                        $('#div_alert').text('');
                        $("#buy-amount").val(buy_value + 1);
                    }
                    if(buy_value == max_amount){
                        $('#div_alert').text('Số lượng bạn chọn đạt mức tối đa của sản phẩm');
                    }
                }
                else{
                    $('#div_alert').text('');
                    $("#buy-amount").val(buy_value + 1);
                }
                
            }
            else{
                if(buy_value > 1){
                    $('#div_alert').text('');
                    $("#buy-amount").val(buy_value - 1);
                }
            }
        }
    });
    $('#buy-amount').keyup(function(e){
        var max = max_amount;
        var val = $('#buy-amount').val();
        if(Number(val) > Number(max)){
            $('#buy-amount').val(max);
            $('#div_alert').text('Số lượng bạn chọn đạt mức tối đa của sản phẩm');
        }
    });
    $('#buy-amount').blur(function (e) { 
        if(!$(e.delegateTarget).val() || $(e.delegateTarget).val() < 1){
            $('#div_alert').text('');
            $(e.delegateTarget).val(1);
        }
    });
    $('.buy-btn').click(function (e) { 
       var check = checkAmount();
       if(!check){
        $('#div_alert').text('Vui lòng chọn Màu và Size');
       }
       else{
        $('#div_alert').text('');
        var form = document.createElement('form');
            form.setAttribute('action','/cart/'+pid);
        var input_size = document.createElement('input');
            input_size.setAttribute('name','size');
            input_size.value = size;
        var input_color = document.createElement('input');
            input_color.setAttribute('name','color');
            input_color.value = color;
        var input_amount = document.createElement('input');
            input_amount.setAttribute('name','amount');
            input_amount.value = $('#buy-amount').val();
        var btn = document.createElement('button');
            btn.setAttribute('id','form_btn');
        form.append(btn);
        form.append(input_color);
        form.append(input_size);
        form.append(input_amount);
        $('body').append(form);
        $('#form_btn').click();
        $('.buy-btn').attr("disabled",true);
       }
        
    });
    var count_feedback = 0;
    for (let i = 0; i < 5; i++) {
        count_feedback = Number(count_feedback) + Number($('.count-feedback').eq(i).text().trim());
    }
    $('.star-box').click(function (e) { 
        e.preventDefault();
        $(e.delegateTarget).siblings('.star-box').removeClass('star-active');
        $(e.delegateTarget).addClass('star-active');
        var star = $(e.delegateTarget).attr('id');
        var empty = true;
        for (let i = 0; i < count_feedback; i++) {
            if (star == 'all') {
                $('.comment-box').eq(i).show();
            }
            else{
                if($('.comment-box').eq(i).attr('class').indexOf(star) == -1){
                    $('.comment-box').eq(i).hide();
                }
                else{
                    $('.comment-box').eq(i).show();
                }
            }
        }
        for (let i = 0; i < count_feedback; i++) {
            if($('.comment-box').eq(i).attr('class').indexOf(star) != -1 || star == 'all'){
                empty = false;
                break;
            }
        }
        if(empty){
            $('.star-feedback-empty').show();
        }
        else{
            $('.star-feedback-empty').hide();
        }
    });
    //ImageControll

    // $("#img").change(function (e) { 
    //     e.preventDefault();
    //     var formData = new FormData($("#image_upload")[0]);
    //     $.ajax({
    //         type: "POST",
    //         url: "/upload",
    //         data: formData,
    //         dataType: "json",
    //         success: function (response) {
    //             for(var i = 0 ; i < response.path.length; i++){
    //                 var ul = document.createElement('ul');
    //                     ul.setAttribute("class","preview");
    //                 var img = document.createElement("img");
    //                     img.setAttribute("class","img-fluid");
    //                     img.setAttribute("src",response.path[i]);
    //                 var div = document.createElement("div");
    //                     div.setAttribute("class","delBtn");
    //                     div.setAttribute("onclick","deleteImg(this)");
    //                 var span = document.createElement("span");
    //                     span.setAttribute("class","material-symbols-outlined");
    //                     span.innerHTML = "close";
    //                 div.append(span);
    //                 ul.append(img);
    //                 ul.append(div);
    //                 $("#preview").append(ul);
    //             }
                
    //         },
    //         error:function(response){
    //             alert("error");
    //         },
    //         cache: false,
    //         contentType: false,
    //         processData: false,
    //     });
    // });
    // const input = document.querySelector('.input');
    // input.style.opacity = 0;    
});

function checkAmount(){
    var this_color = $('#div_color').find('.size-active').children("p").text();
    if(this_color){
        var amount = 0;
        var check = $("#div_size").find('.size-active').html();
        var obj = $("#div_size").find('.size-active').find('.div_quantity');
        if(check){
            var this_size = $("#div_size").find('.size-active').children('.size-id').text();
            for (let i = 0;; i++) {
                var color_hidden = obj.eq(i).children('.color-hidden').text();
                if(color_hidden == this_color){
                    amount = obj.eq(i).children('.amount-hidden').text();
                }
                if(obj.last().html() == obj.eq(i).html()){
                    break;
                }
            }
            
            if(amount == 0){
                $('.buy-btn').html('Hết hàng');
                $('.buy-btn').attr("class","buy-btn btn-disable");
                $('.buy-btn').prop('disabled', true);
                $('#buy-amount').prop('disabled', true);
                $('#buy-amount').val(1);
                $('#div_alert').text('');
                $('#has-amount').text('');
                return false;
            }else{
                color = this_color;
                size = this_size;
                max_amount = amount;
                $('#has-amount').text(amount + ' sản phẩm có sẵn');
                $('.buy-btn').html('Mua ngay');
                $('.buy-btn').attr("class","buy-btn btn-allow");
                $('.buy-btn').prop('disabled', false);
                $('#buy-amount').val(1);
                $('#div_alert').text('');
                $('#buy-amount').attr('disabled', false);
                return true;
            }
        }
        else{
            return false;
        }
        
    }
    else{
        $('#has-amount').text('');
        return false;
    }
    
}

function openTab(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
  }
  

  