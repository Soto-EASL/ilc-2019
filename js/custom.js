(function($){
    function ilcMegaMenuPosition(){
        var hw = $("#site-header").outerWidth(), hcl = $("#site-navigation-wrap").offset().left;
        $(".megamenu > ul").each(function(){
            var t = $(this), mml = t.closest(".megamenu").offset().left, tw = t.width(), mmltw = mml + tw;
            if(mmltw <=  hw){
                t.css({"left": (mml-hcl) + "px", "right": "auto"});
            }else{
                t.css({"left": (hw-tw-hcl) + "px", "right": "auto"});
            }
        });
    }
    function ilcStickyFooterMsg(){
        var $sf = $('#ilc-sticky-footer-message-wrap');
        if($sf.size() < 1){
            return false;
        }
        $('#outer-wrap').css('padding-bottom', $sf.outerHeight() + 'px');
        $sf.addClass('ilc-fms-sticky-enabled');
    }
    $(window).load(function(){
        ilcMegaMenuPosition();
        setTimeout(function(){
            ilcMegaMenuPosition();
        }, 1000);
        setTimeout(function(){
            ilcMegaMenuPosition();
        }, 2000);
        setTimeout(function(){
            ilcMegaMenuPosition();
        }, 3000);
    });
    $(document).ready(function(){
        ilcMegaMenuPosition();
        ilcStickyFooterMsg();
        $('.ilc-countdown').each(function(){
            var t = $(this), d = t.data('until'), z = t.data('zoneoffset'), data, d;
            
            if(typeof d === "undefined"){
                return false;
            }
            d = d.replace( /^\s+/g, '').replace( /\s+$/g, '');
            d = d.split(" ");
            if(d.length !== 2){
                t.closest('.top-bar-col-countdown').remove();
                return false;
            }
            date = d[0].split("-");
            time = d[1].split(":");
            if( (date.length !== 3) || (time.length !== 3)){
                t.closest('.top-bar-col-countdown').remove();
                return false;
            }
            if(typeof z === "undefined"){
                z = 0;
            }
            t.closest('.top-bar-col-countdown').removeClass('countdown-hide');
            t.countdown({
                until: new Date(date[0], date[1] - 1, date[2], time[0], time[1], time[2]),
                timezone: z,
                format: 'd',
                layout: '<span class="countdown-dn">{dn}</span><br/><span class="countdown-dl">{dl} left</span>',
                tickInterval: 60*60*24
            });
        });
        
        $('#ilc-close-stikcy-message').on('click', function(e){
            e.preventDefault();
            console.log('closeing...');
            $.ajax({
                type: "POST",
                url: ILC.ajaxUrl,
                data: {
                    action: 'ilc_save_closed_footer_message'
                },
                dataType: 'json',
                success: function(data){
                    console.log(data)
                },
                fail: function(){
                    console.log('Failed');
                }
            });
            $(this).closest('#ilc-sticky-footer-message-wrap').slideUp(250, function(){
                $('#outer-wrap').css('padding-bottom', '0px');
                $(this).remove();
            });
            return false;
        });
        if($('body').hasClass('page-template-sponsorship-exhibition')){
            var curMenuText = '';
            if($('.ilc-spex-menu-container .current-menu-item').length > 0){
                curMenuText = $('.ilc-spex-menu .current-menu-item > a').html();
            }else {
                curMenuText = $('.ilc-spex-menu li:first-child > a').html();
            }

            $('.spex-menu-mobile-text').html(curMenuText);
            $('.spex-menu-mobile-tray').on('click', function(e){
                var $menuCon = $('.ilc-spex-menu-container');
                if($menuCon.hasClass('spex-mobile-menu-active')){
                    $('.ilc-spex-menu').slideUp(300);
                    $menuCon.removeClass('spex-mobile-menu-active');
                }else{
                    $menuCon.addClass('spex-mobile-menu-active');
                    $('.ilc-spex-menu').slideDown(300);
                }
//                $('.ilc-spex-menu').slideDown();
//                $menuCon.hasClass('spex-mobile-menu-active')?$menuCon.removeClass('spex-mobile-menu-active'):$menuCon.addClass('spex-mobile-menu-active');
            });
            //.msr-page-menu-mobile-text 
            $('.ilc-spex-menu a').on('click', function(e){
                $('.spex-menu-mobile-text').html($(this).html());
            });
        }
        if($('body').hasClass('page-template-sponsorship-exhibition-horizontal')){
            var curMenuText = '';
            if($('.ilc-spex-menu-container-hz .current-menu-item').length > 0){
                curMenuText = $('.ilc-spex-menu-hz .current-menu-item > a').html();
            }else {
                curMenuText = $('.ilc-spex-menu-hz li:first-child > a').html();
            }

            $('.spex-menu-mobile-text').html(curMenuText);
            $('.spex-menu-mobile-tray').on('click', function(e){
                var $menuCon = $('.ilc-spex-menu-container-hz');
                if($menuCon.hasClass('spex-mobile-menu-active')){
                    $('.ilc-spex-menu-hz').slideUp(300);
                    $menuCon.removeClass('spex-mobile-menu-active');
                }else{
                    $menuCon.addClass('spex-mobile-menu-active');
                    $('.ilc-spex-menu-hz').slideDown(300);
                }
//                $('.ilc-spex-menu').slideDown();
//                $menuCon.hasClass('spex-mobile-menu-active')?$menuCon.removeClass('spex-mobile-menu-active'):$menuCon.addClass('spex-mobile-menu-active');
            });
            //.msr-page-menu-mobile-text 
            $('.ilc-spex-menu-hz a').on('click', function(e){
                $('.spex-menu-mobile-text').html($(this).html());
            });
        }
        // Stuff to be done when windows resize
        $(window).resize(function(){
            ilcMegaMenuPosition();
            ilcStickyFooterMsg();
        });
    });
})(jQuery);