$('#resetbtn').click(function(){
    $(this).closest('form')[0].reset();
})

$('#cancelbtn').click(function(){
    $(this).closest('form')[0].reset();
    window.location.replace('/admin/accounts/staff');
})