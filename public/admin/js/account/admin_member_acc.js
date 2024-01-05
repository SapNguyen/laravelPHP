$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('.acbtn').click(function(){
    if(confirm('Are you sure want to unban this account?')){
        var formData = new FormData();
        formData.append('mid', $(this)[0].getAttribute('data-mid'));
        $.ajax({
            type: "POST",
            url: "/unbanmember",
            data: formData,
            success: function (response) {
                alert('Account has been unbanned.')
                window.location.reload();
            },
            error: function (response) {
                alert('An error orcured when unbanning account!')
            },
            cache: false,
            contentType: false,
            processData: false,
        })
    }
})

$('.debtn').click(function(){
    if(confirm('Are you sure want to ban this account?')){
        var formData = new FormData();
        formData.append('mid', $(this)[0].getAttribute('data-mid'));
        $.ajax({
            type: "POST",
            url: "/banmember",
            data: formData,
            success: function (response) {
                alert('Account has been banned.')
                window.location.reload();
            },
            error: function (response) {
                alert('An error orcured when banning account!')
            },
            cache: false,
            contentType: false,
            processData: false,
        })
    }
})