/**
 * Theme JS File
 *
 * Developer Note:
 * - Handles UI interactions (navigation, back to top, accessibility, forms)
 * - Uses jQuery IIFE to avoid global scope pollution
 * - Optimized with cached selectors + better event handling
 */

;(function ($) {
'use strict';

/* -----------------------------------------------------------
   Cache DOM Elements
----------------------------------------------------------- */
var $window = $(window);
var $body   = $('body');
var $navbar = $('#navbar');
var $aside  = $('#aside-nav-wrapper');

/* -----------------------------------------------------------
   Back To Top Scroll
----------------------------------------------------------- */
var back_to_top_scroll = function () {

    var $btn = $('#backToTop');

    $btn.on('click', function () {
        $('html, body').animate({ scrollTop: 0 }, 500);
        return false;
    });

    $window.on('scroll', function () {
        $btn.toggleClass('active', $window.scrollTop() > 500);
    });
};

/* -----------------------------------------------------------
   Trap Focus (Accessibility)
----------------------------------------------------------- */
var trapFocusInsiders = function (elem) {

    var $elem = $(elem);

    var $tabbable = $elem.find('select, input, textarea, button, a')
        .filter(':visible');

    var $first = $tabbable.first();
    var $last  = $tabbable.last();

    $first.focus();

    $last.on('keydown', function (e) {
        if (e.which === 9 && !e.shiftKey) {
            e.preventDefault();
            $first.focus();
        }
    });

    $first.on('keydown', function (e) {
        if (e.which === 9 && e.shiftKey) {
            e.preventDefault();
            $last.focus();
        }
    });

    $elem.on('keyup', function (e) {
        if (e.keyCode === 27) {
            $elem.removeClass('active').hide();
        }
    });
};

/* -----------------------------------------------------------
   Keyboard Focus Helper
----------------------------------------------------------- */
var focus_to = function (trigger, target) {

    $(trigger).on('keyup', function (e) {
        if (e.which === 13) {
            $(target).focus();
        }
    });
};

/* -----------------------------------------------------------
   Menu Height Sync (≤ 869px)
----------------------------------------------------------- */
function setMenuHeight() {

    if (window.innerWidth <= 896) {

        $('.menu-item-has-children').each(function () {

            var $item = $(this);

            $item.find('.prime-submenu-toggle')
                .css('max-height', $item.outerHeight());

        });

    } else {

        $('.prime-submenu-toggle').css('height', '');
    }
}

/* -----------------------------------------------------------
   Debounce Helper (for resize)
----------------------------------------------------------- */
function debounce(func, wait) {
    var timeout;
    return function () {
        clearTimeout(timeout);
        timeout = setTimeout(func, wait);
    };
}



/* -----------------------------------------------------------
   DOM Ready
----------------------------------------------------------- */
$(function () {

    /* Back to top */
    back_to_top_scroll();

    /* Menu height */
    setMenuHeight();
    $window.on('resize', debounce(setMenuHeight, 100));

    /* Main menu keyboard focus */
    $navbar.find('.navigation-menu li > a').on('keyup', function (e) {

        if (matchMedia('only screen and (min-width: 992px)').matches) {
            $navbar.find('li').removeClass('focus');
            $(this).parents('li.menu-item-has-children').addClass('focus');
            $navbar.addClass('keyfocus');
        }
    });

    $(document).on('mouseenter mouseleave click', '#navbar, #navbar a, body', function () {
        $navbar.removeClass('keyfocus');
    });

    /* Submenu toggle */
    $('.prime-submenu-toggle').on('click', function (e) {
        e.preventDefault();
        $(this).next('ul').toggleClass('focus-active');
        $(this).toggleClass('bi-caret-up-fill');
    });

    /* Responsive menu open */
    $('.prime-shop-responsive-navbar').on('click', function (e) {
        e.preventDefault();

        if ($aside.length) {
            $aside.toggleClass('active');
        }

        $(this).find('i').toggleClass('bi-x-lg');

        trapFocusInsiders($aside);
    });

    /* Responsive menu close */
    $('.prime-shop-navbar-close').on('click', function () {

        if ($aside.length) {
            $aside.removeClass('active');
        }

        $('.prime-shop-responsive-navbar i').removeClass('bi-x-lg');
        $('.prime-shop-responsive-navbar').focus();
    });

    /* Footer widget spacing fix */
   if( $('#colophon .widget').length ){ 
        var titleHeight = $('#colophon .widget-title, #colophon .wp-block-heading')
            .first()
            .outerHeight() || 0;
        $('#colophon .widget').each(function () {

            var $widget = $(this);

            $widget.css('--widget-title-height', titleHeight + 'px');

            if ( $widget.find('> .widget-title-space').length ) {

                // Spacer already exists, just update its height
                $widget.find('> .widget-title-space').css('height', titleHeight + 'px');

            } else if ( !$widget.find('> .widget-title, > .wp-block-heading').length ) {

                // No title and no spacer, so create one
                $widget.prepend(
                    '<div class="widget-title-space" style="height:' + titleHeight + 'px"></div>'
                );
            }

        });
    }   

    /* Comment form validation */
    if ($('#commentform').length) {

        $('#commentform').attr('novalidate', true);

        $('#commentform').on('submit', function (e) {

            var error = false;

            $('#commentform input[required], #commentform textarea[required]').each(function () {

                if ($.trim($(this).val()) === '') {
                    $(this).addClass('is-invalid');
                    error = true;
                } else {
                    $(this).removeClass('is-invalid');
                }

            });

            if (error) {
                e.preventDefault();
                $('#commentform .is-invalid:first').focus();
            }
        });

        $('#commentform input[required], #commentform textarea[required]').on('keyup change', function () {
            if ($.trim($(this).val()) !== '') {
                $(this).removeClass('is-invalid');
            }
        });
        
    }
    AOS.init();
});

})(jQuery);