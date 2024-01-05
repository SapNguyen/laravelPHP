$('.changebtn').click(function(){
    var trs = $('tr');
    for(var i = 1; i < trs.length; i++){
        if(trs[i].childNodes[1].value != $(this).closest('tr')[0].childNodes[1].value){
            trs[i].style.visibility = 'hidden';
        }
    }
    var btn = $(this).closest('td')[0].childNodes;
    btn[1].style.display = 'none';
    btn[3].style.display = 'none';
    btn[5].style.display = 'block';
    btn[7].style.display = 'block';
    btn[9].style.display = 'block';

    var tr = $(this).closest('tr')[0].childNodes;
    tr[3].childNodes[1].disabled = false;
    tr[5].childNodes[1].disabled = false;
    tr[7].childNodes[1].disabled = false;
    tr[9].childNodes[1].disabled = false;
    tr[11].childNodes[1].disabled = false;
})

$('.cancelbtn').click(function(){
    $(this).closest('form')[0].reset();
    var trs = $('tr');
    for(var i = 1; i < trs.length; i++){
        if(trs[i].childNodes[1].value != $(this).closest('tr')[0].childNodes[1].value){
            trs[i].style.visibility = 'visible';
        }
    }
    var btn = $(this).closest('td')[0].childNodes;
    btn[1].style.display = 'block';
    btn[3].style.display = 'block';
    btn[5].style.display = 'none';
    btn[7].style.display = 'none';
    btn[9].style.display = 'none';
    
    var tr = $(this).closest('tr')[0].childNodes;
    tr[3].childNodes[1].disabled = true;
    tr[5].childNodes[1].disabled = true;
    tr[7].childNodes[1].disabled = true;
    tr[9].childNodes[1].disabled = true;
    tr[11].childNodes[1].disabled = true;
})

$('.deletebtn').click(function(){
    if(!confirm('Are you sure want to delete this account?')){
        alert('You should change the account info instead of deleting it.');
        return;
    }
    var formData = new FormData();
    formData.append('sId', $(this).attr('data-sid'));
    $.ajax({
        type: 'POST',
        url: '/deletestaff',
        data: formData,
        success: function (response) {
            alert(response.message);
            window.location.replace('/admin/accounts/staff');
        },
        error: function (response) {
            alert('An error orcured when deleting account!');
        },
        cache: false,
        contentType: false,
        processData: false,
    });
})