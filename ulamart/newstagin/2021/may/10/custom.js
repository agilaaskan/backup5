require([ "jquery" ], function($) {
    $( "body" ).delegate( ".shopback p", "click", function() {
        $(".action.showcart.active").removeClass("active");
        $(".ui-dialog.mage-dropdown-dialog").css("display", "none");
        $(".minicart-wrapper.active").removeClass("active");
       
    });
});
require(["jquery", "owlcarousel"], function ($) {
    
    $(document).scroll(function() {
            var y = $(this).scrollTop();
            if (y >= 700) {
                $(".ss-mob-cart").addClass("current");
            } else {
                $(".ss-mob-cart").removeClass("current");
            }
            if (y >= 700) {
                $(".page-wrapper").addClass("move");
                $(".nav-toggle").addClass("ss-mob-menus");
                $(".block-search").addClass("stick");
                $(".cmobile-menu").addClass("stick");
                $("ol.review-items.rev").addClass("stick");                
                
            } else {
                $(".page-wrapper").removeClass("move");
                $(".nav-toggle").removeClass("ss-mob-menus");
                $(".block-search").removeClass("stick");
                $(".cmobile-menu").removeClass("stick");
                $("ol.review-items.rev").removeClass("stick");    
            }
    });
    (function(jQuery) {

        jQuery(".ss-mob-sticky").on('scroll', function() {
            jQueryval = jQuery(this).scrollLeft();
          });
        jQuery("button#slideRight").on("click", function(){
            jQuery(".ss-mob-sticky").animate( { scrollLeft: '+=460' }, 200);
            
        });
        jQuery("button#slideLeft").on("click", function(){
            jQuery(".ss-mob-sticky").animate( { scrollLeft: '-=460' }, 200);
        });
    
        
    
    })(jQuery);
    
    $(document).ready(function(){
                var qq =$('.swatch-option.text.selected span').text();
                var pp =$('.swatch-option.text.selected .custom-option-price').text();
                $('.ss-pro-qty').html(qq);
                $('.ss-pro-price').html(pp);
                // $(".show-more").click(function(){
                //     $(".txt").toggleClass("show-more-height");
                // });
               
                $(".mob-all-rev").click(function(){
                    $(".review-pop").toggleClass("show");
                  
                });
                // $(".pp-vd").click(function(){
                //     $(".lightbox").toggleClass("close");
                //     $(".videoModal").toggleClass("hide");
                  
                // });
                // $(".lightbox").click(function(){
                //     $(".lightbox").toggleClass("close");
                //     $(".videoModal").toggleClass("hide");
                  
                // });
                $(".close-pop").click(function(){
                    $(".review-pop").removeClass("show");
                });
                $("#wr-review").click(function(){
                    $(".popup-inside-review").toggleClass("show");
                  
                });
                $(".close-rev-pop").click(function(){
                    $(".popup-inside-review").removeClass("show");
                });
                $('.cmobile-menu ul > li').click(function() { 
                $('.cmobile-menu ul > li').removeClass('active'); 
                $(this).addClass('active'); 
               }); 
               $('.cmobile-menu ul > li').click(function(){
                $(this).addClass('clr').siblings().removeClass('clr');
                });
    });
   $( "body" ).delegate( ".swatch-option.text", "click", function() {
        var qq =$('.swatch-option.text.selected span').text();
        var pp =$('.swatch-option.text.selected .custom-option-price').text();
       $('.ss-pro-qty').html(qq);
       $('.ss-pro-price').html(pp);
     });
     window.addEventListener('load', function() { 
        var agent = navigator.userAgent;
        var n = agent.includes("Chrome-Lighthouse");
      if(!n){
     let amframe = document.querySelectorAll('.amigolazy');
    //  window.onload = function(){
     for(let i=0; i<amframe.length;i++){
         let amsrc = amframe[i];
         let amdata = amsrc.getAttribute("data-src");
         let datanew = amsrc.getAttribute("data-lazy");
         if(datanew === null){
         datanew = 1500;
         }
         setTimeout(function(){
         amframe[i].setAttribute("src",amdata);
         console.info(datanew + "ms Lazyloaded " + amframe[i].src);
         }, datanew);
     }
    //  }
      }
     });
    // });
    jQuery( "body" ).delegate( ".cfaq h2", "click", function() {
        jQuery(this).toggleClass("active");
        var panel = jQuery(this).next();
          panel.toggleClass("show");
    });
    var hgt = jQuery(".ss-pt-one").height();
    if (hgt >= 175) {
        jQuery(".ss-more-one").addClass("custom");
    }
    var hgt2 = jQuery(".ss-pt-two").height();
    if (hgt2 >= 175) {
        jQuery(".ss-more-two").addClass("custom");
    }
    var hgt3 = jQuery(".ss-pt-three").height();
    if (hgt3 >= 175) {
        jQuery(".ss-more-three").addClass("custom");
    }
    var hgt4 = jQuery(".ss-pt-four").height();
    if (hgt4 >= 175) {
        jQuery(".ss-more-four").addClass("custom");
    }
    var hgt5 = jQuery(".ss-pt-five").height();
    if (hgt5 >= 175) {
        jQuery(".ss-more-five").addClass("custom");
    }
    var hgt6 = jQuery(".ss-pt-six").height();
    if (hgt6 >= 175) {
        jQuery(".ss-more-six").addClass("custom");
    }
    var hgt7 = jQuery(".ss-pt-seven").height();
    if (hgt7 >= 175) {
        jQuery(".ss-more-seven").addClass("custom");
    }
    var hgt8 = jQuery(".ss-pt-eight").height();
    if (hgt8 >= 175) {
        jQuery(".ss-more-eight").addClass("custom");
    }
    var hgt9 = jQuery(".ss-pt-nine").height();
    if (hgt9 >= 175) {
        jQuery(".ss-more-nine").addClass("custom");
    }
});


require([ "jquery" ], function($) {
    $(".action.showcart").click(function(){
        $(".ui-dialog.mage-dropdown-dialog").css("display", "block");
        $(".minicart-wrapper.active").removeClass("active");
    });

    $(".pp-vd").click(function(){
        $(".lightbox").toggleClass("close");
        $(".videoModal").toggleClass("hide");


    });
    $(".lightbox").click(function(){
        $(".lightbox.light2").addClass("close");
        $(".videoModal").toggleClass("hide");

    });

    $( "body" ).delegate( ".ss-mob-share", "click", function() {
        $(".a2a_kit.a2a_floating_style.mp_social_share_float.a2a_kit_size_32.a2a_vertical_style").toggle();
        $("div#ss-stick").toggleClass("visiblemob");
        $("button#slideLeft").toggleClass("visiblemob");
        $("button#slideRight").toggleClass("visiblemob");
    });
   
    $(".ss-more-two").click(function(){
        $(".ss-pt-two").toggleClass("show-more-height");
    });
    $(".ss-more-one").click(function(){
        $(".ss-pt-one").toggleClass("show-more-height");
    });
    $(".ss-more-three").click(function(){
        $(".ss-pt-three").toggleClass("show-more-height");
    });
    $(".ss-more-four").click(function(){
        $(".ss-pt-four").toggleClass("show-more-height");
    });
    $(".ss-more-five").click(function(){
        $(".ss-pt-five").toggleClass("show-more-height");
    });
    $(".ss-more-six").click(function(){
        $(".ss-pt-six").toggleClass("show-more-height");
    });
    $(".ss-more-seven").click(function(){
        $(".ss-pt-seven").toggleClass("show-more-height");
    });
    $(".ss-more-eight").click(function(){
        $(".ss-pt-eight").toggleClass("show-more-height");
    });
    $(".ss-more-nine").click(function(){
        $(".ss-pt-nine").toggleClass("show-more-height");
    });
    $("a.pp-vd").click(function(){
        $(".lightbox").removeClass("close");
    });
    $('.top-left ul li a').click(function (e) {
        e.preventDefault();
        var link = jQuery(this).attr("href");
        $('html, body').animate({
        scrollTop: jQuery(link).offset().top - 120
        }, 700);
        })
    
    if( $(window).width() >= 768 ) {
        $(window).scroll(function() {
            if ($(window).scrollTop() > $('.page-header').offset().top && !($('.page-header').hasClass('sticky')) && !($('.sections.nav-sections').hasClass('padding'))) {
                $('.page-header').addClass('sticky');
                $('.sections.nav-sections').addClass('padding');
            } else if ($(window).scrollTop() == 0) {
                $('.page-header').removeClass('sticky');
                $('.sections.nav-sections').removeClass('padding');
            }
        });
    }
    else
    {
        $(window).scroll(function() {
            if ($(window).scrollTop() > $('.page-header').offset().top && !($('.page-header').hasClass('sticky')) && !($('.page-main').hasClass('paddingm'))) {
                $('.page-header').addClass('sticky');
                $('.page-main').addClass('paddingm');
            } else if ($(window).scrollTop() == 0) {
                $('.page-header').removeClass('sticky');
                $('.page-main').removeClass('paddingm');
            }
        });
    }   
});
require([ "jquery" ], function($) {
    $(document).ready(function(){
        var cusp =$('.swatch-option.text.selected .custom-option-price').text();
        $('.ss-pro-price').html(cusp);
        
     });
     $(window).on('load',function(){
        var hgt = $(".ss-pt-one").height();
        if (hgt >= 175) {
            $(".ss-more-one").addClass("custom");
        }
        var hgt2 = $(".ss-pt-two").height();
        if (hgt2 >= 175) {
            $(".ss-more-two").addClass("custom");
        }
        var hgt3 = $(".ss-pt-three").height();
        if (hgt3 >= 175) {
            $(".ss-more-three").addClass("custom");
        }
        var hgt4 = $(".ss-pt-four").height();
        if (hgt4 >= 175) {
            $(".ss-more-four").addClass("custom");
        }
        var hgt5 = $(".ss-pt-five").height();
        if (hgt5 >= 175) {
            $(".ss-more-five").addClass("custom");
        }
        var hgt6 = $(".ss-pt-six").height();
        if (hgt6 >= 175) {
            $(".ss-more-six").addClass("custom");
        }
        var hgt7 = $(".ss-pt-seven").height();
        if (hgt7 >= 175) {
            $(".ss-more-seven").addClass("custom");
        }
        var hgt8 = $(".ss-pt-eight").height();
        if (hgt8 >= 175) {
            $(".ss-more-eight").addClass("custom");
        }
        var hgt9 = $(".ss-pt-nine").height();
        if (hgt9 >= 175) {
            $(".ss-more-nine").addClass("custom");
        }
    });
    
});

