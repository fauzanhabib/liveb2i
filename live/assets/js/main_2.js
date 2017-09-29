function confirmation(url, type, title, class_group_name, class_name){
        if(type === 'group'){
            $('.'+class_group_name).each(function () {
                var dropdown = $(this);
                $('.'+class_name, dropdown).click(function (event) {
                    event.preventDefault();
                    var href = url;
                    alertify.dialog('confirm').set({
                        'title': title
                    });
                    alertify.confirm("Are you sure?", function (e) {
                        if (e) {
                            window.location.href = href;
                        }
                    });
                });
            });
        }else if(type==='single'){
            $('.'+class_name).click(function (event) {
                event.preventDefault();
                var href = url;
                alertify.dialog('confirm').set({
                    'title': title
                });
                alertify.confirm("Are you sure?", function (e) {
                    if (e) {
                        window.location.href = href;
                    }
                });
            });
        }
    }

function animationClick(element, animation){
    element = $(element);
    element.addClass('animated ' + animation);          
    //wait for animation to finish before removing classes
    window.setTimeout( function(){
        element.removeClass('animated ' + animation);
    }, 1000);
}    

function parsley_float(){
    $('.parsley-errors-list').css({'position':'absolute','bottom':'0','right':'0','color':'#E9322D'});
    $('input.parsley-error').css({'border':'none'});
    $('select.parsley-error').css({'border':'none'});
    $('textarea.parsley-error').css({'border':'none'});
}

$(document).ready(function(){


    var fulls = $('.content-center');
    var win = $(document);
    fulls.height(win.height());
    win.resize(function() { 
        fulls.height(win.height());
    });

    $('.view-schedule').addClass('animated fadeIn');

    //link to menu
    $('.tab-list li').each(function(){
        windowsize = $(window).width();
        if(windowsize < 720){
           var dropdown = $(this);
            $('a',dropdown).click(function(event){
                if($('a', dropdown).hasClass('active')) {
                    $('.tab-list li').attr('style', 'display: block !important');
                    return false;
                }
            })
        }
    });

    //show filter date
    $('a.link-filter').click(function(e){
        $('.filter-form').toggleClass( "show" );

        if ($('.filter-form').hasClass('show')) {
            $('.link-filter').children('i').removeClass('icon icon-arrow-down').addClass('icon icon-close');

        }
        else {
            $('.link-filter').children('i').removeClass('icon icon-close').addClass('icon icon-arrow-down');
        }

        e.preventDefault();
    });

    //small menu
    function toggleNav() {
        if ($('#site-wrapper').hasClass('show-nav')) {
            $('#site-wrapper').removeClass('show-nav');
             $('.menu-mobile .icon.icon-close').removeClass('icon icon-close').addClass('icon icon-menu');

        } else {
            $('#site-wrapper').addClass('show-nav');
            $('.menu-mobile .icon.icon-menu').removeClass('icon icon-menu').addClass('icon icon-close');
        }
    }

    //small menu click
    $('.toggle-nav').click(function () {
    	toggleNav();
    });
    
    
    //thumbnail caption for profile
    if($(document).width() > 1024) {
        $('.thumb-small').hover(function(){
            $(this).find('.caption').css('opacity','1');
        }, function(){
            $(this).find('.caption').css('opacity','0');
        });

        $('.thumb-large').hover(function(){
            $(this).find('.caption').css('opacity','1');
        }, function(){
            $(this).find('.caption').css('opacity','0');
        });
    }
    else {
        $('.thumb-large .caption').css('opacity','1');
        $('.thumb-small .caption').css('opacity','1');
    }
    

    //narrow setting
    var $narrow = $('.narrow');
    $narrow.on('click',function(e){
        
        if($('.dropdown-menu-box').hasClass("selected")) {
            $('.dropdown-menu-box').removeClass('selected');
            $('.dropdown-menu-box').hide();
        }
        else{
            animationClick('.dropdown-menu-box','fadeIn');
            $('.dropdown-menu-box').addClass('selected').show();
            $('.dropdown-notif-box').hide();
            $('.icon-notification').removeClass('text-cl-secondary');
            $('.dropdown-notif-box').removeClass('selected');
        }

        e.stopPropagation();
    });


    //notification
    var $notification = $('.notification');
    $notification.on('click',function(e){
        
        
        if($('.dropdown-notif-box').hasClass("selected")) {
            
            $('.dropdown-notif-box').removeClass('selected');
            $('.icon-notification').removeClass('text-cl-secondary');
            $('.dropdown-notif-box').hide();
        }
        else{
            animationClick('.dropdown-notif-box','fadeIn');
            $('.dropdown-notif-box').addClass('selected').show();
            $('.icon-notification').addClass('text-cl-secondary');
            $('.label-notif').hide();
            $('.dropdown-menu-box').hide().removeClass('selected');
        }

        e.stopPropagation(); 
    });

    $(document).click(function(e) {
        var target = e.target;

        if (!$(target).is('.notification') && !$(target).parents().is('.notification')) {
            $('.dropdown-notif-box').removeClass('selected');
            $('.dropdown-notif-box').hide();
            $('.label-notif').show();
            $('.icon-notification').removeClass('text-cl-secondary');
        }
        
        if (!$(target).is('.narrow') && !$(target).parents().is('.narrow')) {
            $('.dropdown-menu-box').removeClass('selected');
            $('.dropdown-menu-box').hide();
        }


        if (!$(target).is('.tab-list li.current')) {
            windowsize = $(window).width();
            if(windowsize < 720){
                $('.tab-list li').attr('style', 'display: none !important');
                $('.tab-list li.current').attr('style', 'display: block !important');
            }
        }

        
    });

    //cart
    $('.cart').click(function(){
        $( ".dropdown-cart-box" ).fadeToggle(300);
    });


    // menu dropdown
    /**
    var dropdown = document.querySelectorAll('.dropdown');
    var dropdownArray = Array.prototype.slice.call(dropdown, 0);
    dropdownArray.forEach(function (el) {
        var button = el.querySelector('a[data-toggle="dropdown"]'), 
            menu = el.querySelector('.menu-dropdown');
        
        $(button).click(function(e){
     
            if($('.menu-dropdown').hasClass("show")) {
                menu.classList.add('hide');
                menu.classList.remove('show');
                $('#dropdown').removeClass('pure-menu-selected');
            }
            else{
                menu.classList.add('show');
                menu.classList.remove('hide');
                $('#dropdown').addClass('pure-menu-selected');
            }
        });
    });**/
    $("li.dropdown > a").add("<span>");

    $(".dropdown").each(function(){

        var $dropdown = $(this);
        var $button = $('a[data-toggle="dropdown"]',$dropdown);
        var $menu = $('.menu-dropdown',$dropdown);

        $button.click(function(e){
            if($menu.hasClass('show')) {
                $menu.addClass('hide');
                $menu.removeClass('show');
                $('#dropdown').removeClass('pure-menu-selected');
                e.preventDefault();
            }
            else {
                $menu.removeClass('hide');
                $menu.addClass('show');
                $('#dropdown').addClass('pure-menu-selected');
                e.preventDefault();
            }
        });


    });
    
    
            
            
    /**
     * Message
     * */

    

    $('.btn-close').click(function(){
        $(".alert").remove();
    });

    if($('div').is('.alert.success')) {

        $(".alert.success").delay(5000).fadeOut(1000, function() {
            $(".alert").remove();
        });
    }

});