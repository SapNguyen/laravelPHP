var link,page_active;
const range = [['500000','1000000'],['1000000','1500000'],['1500000','2000000'],['2000001','1000000000']];
$(document).ready(function () {
    page_active = $("#cur_page").val();
    link = $('#link').val();
    $(".small-img").mouseenter(function (e) { 
        var link = $(e.delegateTarget).attr("src");
        $(e.delegateTarget).parent().siblings().attr("src", link);
    });
    $('#sort').change(function (e) { 
        var sort_type = $(e.delegateTarget).val();
        var display_amount = $('#display').val();
        const arr = getRangePrice();
        var min_price = arr[0];
        var max_price = arr[1];
        const checked_box = arr[2];
        submitForm(display_amount,sort_type,min_price,max_price,1,checked_box);
    });
    $('#display').change(function (e) { 
        var display_amount = $(e.delegateTarget).val();
        var sort_type = $('#sort').val();
        const arr = getRangePrice();
        var min_price = arr[0];
        var max_price = arr[1];
        const checked_box = arr[2];
        submitForm(display_amount,sort_type,min_price,max_price,1,checked_box);
        
    });
    $('.cb_price').click(function (e) { 
        var display_amount = $('#display').val();
        var sort_type = $('#sort').val();
        const arr = getRangePrice();
        var min_price = arr[0];
        var max_price = arr[1];
        const checked_box = arr[2];
        submitForm(display_amount,sort_type,min_price,max_price,1,checked_box);
    });
    $('.page-link').click(function (e) { 
        var page = $(e.delegateTarget).text();
        if(page != page_active){
            if(page == '‹'){
                if($(e.delegateTarget).parent('.page-item').attr('class').indexOf('disabled') == -1){
                    page = Number(page_active) - 1;
                }
            }
            else if(page == '›'){
                if($(e.delegateTarget).parent('.page-item').attr('class').indexOf('disabled') == -1){
                    page = Number(page_active) + 1;
                }
            }
            var display_amount = $('#display').val();
            var sort_type = $('#sort').val();
            const arr = getRangePrice();
            var min_price = arr[0];
            var max_price = arr[1];
            const checked_box = arr[2];
            submitForm(display_amount,sort_type,min_price,max_price,page,checked_box);
        }
        
    });
    function submitForm(display_amount,sort_type,min_price,max_price,page,checked_box) {
        var form = document.createElement('form');
            form.setAttribute('action',link);
            var i_sort_type = document.createElement('input');
                i_sort_type.setAttribute('name','sort_type');
                i_sort_type.setAttribute('value',sort_type);
            var i_amount_display = document.createElement('input');
                i_amount_display.setAttribute('name','display_amount');
                i_amount_display.setAttribute('value',display_amount);
            var i_min_price = document.createElement('input');
                i_min_price.setAttribute('name','min_price');
                i_min_price.setAttribute('value',min_price);
            var i_max_price = document.createElement('input');
                i_max_price.setAttribute('name','max_price');
                i_max_price.setAttribute('value',max_price);
            var i_checked_box = document.createElement('input');
                i_checked_box.setAttribute('name','checked_box');
                i_checked_box.setAttribute('value',"! "+checked_box);
            var i_page = document.createElement('input');
                i_page.setAttribute('name','page');
                i_page.setAttribute('value',page);
            var button = document.createElement('button');
                button.setAttribute('id','sort_submit');
        form.append(i_sort_type,i_amount_display,i_min_price,i_max_price,i_page,i_checked_box,button);
        $('body').append(form);
        $('#sort_submit').click();
    }
    function getRangePrice() {
        const arr = [];
        for (let i = 0; i < 4; i++) {
            var cb = $('#list_cb').find('.cb_price').eq(i);
            if(cb.prop('checked') == true){
                arr.push(i);
            }
        }
        if(arr.length > 0){
            var min_price = range[arr[0]][0];
            var max_price = range[arr[arr.length-1]][1];
        }
        else{
            var min_price = 0;
            var max_price = 1000000000;
        }
        return [min_price,max_price,arr];
    }
});
