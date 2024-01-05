$(document).ready(function () {

    $('#logo').owlCarousel({
        loop: true,
        margin:10,
        nav:false,
        dots: false,
        autoplay: true,
        responsive:{
            0:{
                items:3
            },
            600:{
                items: 4
            },
            900:{
                items:5
            },
            1250:{
                items:6
            }
        }
        
    });
    $('#hot-sale').owlCarousel({
        loop: true,
        margin: 20,
        nav: false,
        dots: false,
        autoplay: true,
        responsive:{
            0:{
                items:2
            },
            800:{
                items: 3
            },
            1000:{
                items:4
            },
            1250:{
                items:5
            }
        }
    });

    $('.product').owlCarousel({
        loop: false,
        margin: 20,
        nav: false,
        dots: false,
        autoplay: true,
        responsive:{
            0:{
                items:2
            },
            800:{
                items: 3
            },
            1000:{
                items:4
            },
            1250:{
                items:5
            }
        }
    });
    setInterval(()=>{
        $(".carousel-control-next-icon").click();
    }, 3000);
        
    var owl = $('.owl-carousel');
    owl.owlCarousel();
    $('.owl-next').click(function() {
         owl.trigger('next.owl.carousel');
    })
    $('.owl-prev').click(function() {
        owl.trigger('prev.owl.carousel', [300]);
    })
    
    $(".small-img").mouseenter(function (e) { 
        var link = $(e.delegateTarget).attr("src");
        $(e.delegateTarget).parent().siblings().attr("src", link);
    });
    
});
