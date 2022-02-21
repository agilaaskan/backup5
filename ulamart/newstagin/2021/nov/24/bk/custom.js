
require([ "jquery" ], function($) {
    window.addEventListener('load', function() { setTimeout(function(){
        $("body.catalog-product-view .product-info-main").css("margin-top", "0px");
        },1000); }); 
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
                    $(".mob-all-rev").click(function(){
                        $(".review-pop").toggleClass("show");
                      
                    });
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
         
        jQuery( "body" ).delegate( ".cfaq h2", "click", function() {
            jQuery(this).toggleClass("active");
            var panel = jQuery(this).next();
              panel.toggleClass("show");
        });
    });
    
    
    require([ "jquery" ], function($) {
        $(document).ready(function(){
            let amframe = document.querySelectorAll('.amigolazy');
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
            $(this).text(function(i, text){
                return text === "View more..." ? "View less" : "View more...";
            });
        });
        $(".ss-more-one").click(function(){
            $(".ss-pt-one").toggleClass("show-more-height");
            $(this).text(function(i, text){
                return text === "View more..." ? "View less" : "View more...";
            });
        });
        $(".ss-more-three").click(function(){
            $(".ss-pt-three").toggleClass("show-more-height");
            $(this).text(function(i, text){
                return text === "View more..." ? "View less" : "View more...";
            });
        });
        $(".ss-more-four").click(function(){
            $(".ss-pt-four").toggleClass("show-more-height");
            $(this).text(function(i, text){
                return text === "View more..." ? "View less" : "View more...";
            });
        });
        $(".ss-more-five").click(function(){
            $(".ss-pt-five").toggleClass("show-more-height");
            $(this).text(function(i, text){
                return text === "View more..." ? "View less" : "View more...";
            });
        });
        $(".ss-more-six").click(function(){
            $(".ss-pt-six").toggleClass("show-more-height");
            $(this).text(function(i, text){
                return text === "View more..." ? "View less" : "View more...";
            });
        });
        $(".ss-more-seven").click(function(){
            $(".ss-pt-seven").toggleClass("show-more-height");
            $(this).text(function(i, text){
                return text === "View more..." ? "View less" : "View more...";
            });
        });
        $(".ss-more-eight").click(function(){
            $(".ss-pt-eight").toggleClass("show-more-height");
            $(this).text(function(i, text){
                return text === "View more..." ? "View less" : "View more...";
            });
        });
        $(".ss-more-nine").click(function(){
            $(".ss-pt-nine").toggleClass("show-more-height");
            $(this).text(function(i, text){
                return text === "View more..." ? "View less" : "View more...";
            });
        });
        $(".ss-more-ten").click(function(){
            $(".ss-pt-ten").toggleClass("show-more-height");
            $(this).text(function(i, text){
                return text === "View more..." ? "View less" : "View more...";
            });
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
            var hgt10 = $(".ss-pt-ten").height();
            if (hgt10 >= 175) {
                $(".ss-more-ten").addClass("custom");
            }
        });
        
    });
    require([ "jquery" ], function($) {
        $(document).ready(function(){
            if ($(window).width() < 768) {
                $('#custom-header').remove(); 
            }
        });
    }); 
    require([ "jquery" ], function($) {
        $( "body" ).delegate( "#cminiclose span", "click", function() {
                $('#custom-cart').removeClass('active'); 
                $('a.action.showcart').removeClass('active');
                $('.minicart-wrapper .mage-dropdown-dialog').css("display", "none");
                location.reload();
        }); 
    });
require(["jquery"],function($){
    var ulacountry =  $('.ulamart-cid').html();
    var checkExistt = setInterval(function() {
        if ($(".cod-block").length) {
            if(ulacountry == "US"){
                $(".cod-block p").html("Shipping is available for all states across USA.");
                $('#block-discount').remove();
                clearInterval(checkExistt);
            } else {
                clearInterval(checkExistt);
            }
        } 
    }, 1);
    var checkExistt_t = setInterval(function() {
        if ($(".payment-method.cod-ulamart").length) {
                if(ulacountry == "US"){
                    $('.payment-method.cod-ulamart').remove();
                    clearInterval(checkExistt_t);
                    $('.checkout-index-index .opc-block-summary .table-totals tr.totals.charge').css("display", "none");
                    $('#block-discount-heading').remove();        
                } else {
                    $('.payment-method.cod-ulamart').show();
                    clearInterval(checkExistt_t);
                }
        } 
        
    }, 100); 
});