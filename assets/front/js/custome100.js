/*!
 * Simple jQuery Equal Heights
 *
 * Copyright (c) 2013 Matt Banks
 * Dual licensed under the MIT and GPL licenses.
 * Uses the same license as jQuery, see:
 * http://docs.jquery.com/License
 *
 * @version 1.5.1
 */
!function(a){a.fn.equalHeights=function(){var b=0,c=a(this);return c.each(function(){var c=a(this).innerHeight();c>b&&(b=c)}),c.css("height",b)},a("[data-equal]").each(function(){var b=a(this),c=b.data("equal");b.find(c).equalHeights()})}(jQuery);

jQuery(document).ready(function ($) {
    "use strict";

    $.fn.hasAttr = function (name) {
        return this.attr(name) !== undefined;
    };

    /*NAVIGATION ON SMALL DEVICES*/
    function create_nav() {

    }
    // function create_nav() {
    //     var $nav = $('.navigation');
    //     if ($nav.data('enable_sticky') == 'yes' || $('.map-left-content').length > 0) {
    //         var nav_height = $nav.outerHeight();
    //         var $admin = $('#wpadminbar');
    //         var offset = 0;
    //         var position = 'fixed';


    //         if ($admin.length) {
    //             offset += $admin.height();
    //             position = $admin.css('position');
    //         }

    //         $nav.css({
    //             position: position,
    //             top: offset,
    //             left: '0px',
    //             width: '100%',
    //             zIndex: '10'
    //         });

    //         $('body').css('margin-top', nav_height);
    //     }
    // }

    // create_nav();
    // $(document).on('click', '.navigation-toggle', function () {
    //     var $admin = $('#wpadminbar');
    //     var $navbar = $('.navbar');
    //     var offset = $('.navigation').outerHeight(true);

    //     var bar_offset = 0;
    //     if ($admin.length > 0 && ( $admin == 'fixed' || $('.navigation').css('position') == 'absolute' )) {
    //         bar_offset = $admin.height();
    //     }

    //     $navbar.css('top', offset);
    //     var dynamic_max = $(window).height() - offset - bar_offset;
    //     $navbar.css('height', dynamic_max);
    //     $navbar.css('overflow-y', 'auto');


    //     if ($navbar.css('left') == '0px') {
    //         $('body').css('overflow-y', 'visible');
    //         $navbar.animate({
    //             left: '-1000px'
    //         }, 500);
    //     }
    //     else {
    //         $('body').css('overflow-y', 'hidden');
    //         $navbar.animate({
    //             left: '0px'
    //         }, 500);
    //     }
    // });
    var lastScrollTop = 0;
    var is_safari = navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1;
    if ($('.navigation').length > 0 && $('.navigation').data('enable_sticky') == 'yes' && $('.search-page .container-fluid').length == 0) {
        var $nav = $('.navigation');
        var $admin = $('#wpadminbar');
        var position = 'fixed';
        var offset = 0;
        if ($admin.length > 0) {
            offset = $admin.height();
            position = $admin.css('position');
        }

        var lastScrollTop = 0;
        $(window).on('scroll', function () {
            st = $(this).scrollTop();
            if (!is_safari) {
                if (st < lastScrollTop) {
                    if (position == 'absolute') {
                        var st = $(window).scrollTop();
                        if (st <= $admin.height()) {
                            create_nav();
                        }
                    }
                    $nav.show();
                }
                else if (st > lastScrollTop) {
                    $nav.css('position', 'fixed');
                    if (position == 'absolute') {
                        $nav.css('top', '0px');
                    }
                    if ($(window).width() > 769) {
                        $nav.hide();
                    }
                }
            }
            else {
                if (position == 'absolute') {
                    var st = $(window).scrollTop();
                    if (st <= $admin.height()) {
                        create_nav();
                    }
                }
                $nav.show();
            }
            lastScrollTop = st;
        });
    }

    if ($(window).width() >= 768) {
        $('.search-block').imagesLoaded(function () {
            setTimeout(function(){ $('.white-block.ad-box').equalHeights(); }, 100);

        });

    }

    $(window).resize(function () {
        create_nav();
        $(window).trigger('scroll');

    });

    function handle_navigation() {
        if ($(window).width() >= 770) {
            $('.navbar').css('height', 'auto');
            $('.navbar').css('left', 'auto');
            $('.navbar').css('overflow-y', 'visible');
            $(document).on('mouseenter', 'ul.nav li.dropdown, ul.nav li.dropdown-submenu', function () {
                $(this).addClass('open').find(' > .dropdown-menu').show();
            });

            $(document).on('mouseleave', 'ul.nav li.dropdown, ul.nav li.dropdown-submenu', function () {
                $(this).removeClass('open').find(' > .dropdown-menu').hide();

            });
        }
        else {
            $('.navbar').css('left', '-1000px');
            $('ul.nav li.dropdown, ul.nav li.dropdown-submenu').unbind('mouseenter mouseleave');
        }

        if ($(window).width() >= 770) {
            $(document).on('mouseenter', 'ul.nav li.mega_menu_li, ul.mega_menu', function () {
                $(this).addClass('open').find(' > .mega_menu').show();
            });
            $(document).on('mouseleave', 'ul.nav li.mega_menu_li, ul.mega_menu', function () {
                $(this).removeClass('open').find(' > .mega_menu').hide();
            });
        }
        else {
            $('ul.nav li.mega_menu_li, ul.mega_menu').unbind('mouseenter mouseleave');
            $(document).on('click', 'ul.nav li.mega_menu_li', function () {
                if ($(this).find('.mega_menu').is(':visible')) {
                    $(this).find('.mega_menu').hide();
                }
                else {
                    $(this).find('.mega_menu').show();
                }
            });
        }
    }

    //handle_navigation();

    $(document).on('click', 'a[data-toggle="dropdown"]', function () {
        if ($(this).attr('href').indexOf('http') > -1) {
            window.location.href = $(this).attr('href');
        }
    });

    // $(window).resize(function () {
    //     setTimeout(function () {
    //         handle_navigation();
    //     }, 200);
    // });

    /* STRECH MAP TO FULL HEIGHT ON SEARCH PAGE STYLE LEFT */
    function calc_side_map_search() {
        if ($('.search-page .container-fluid').length > 0) {
            var offset = 0;
            var $admin = $('#wpadminbar');
            if ($admin.length > 0 && $admin.css('position') == 'fixed') {
                offset = $admin.height();
            }
            $('.navigation').css({
                position: 'fixed',
                top: offset,
                left: '0px',
                right: '0px',
                zIndex: 11
            });
            $('.header-map').css({
                top: $('.navigation:not(.sticky-nav)').outerHeight(true) + offset + 2,
                left: '0px',
                bottom: '0px',
                width: $('.map-size').outerWidth(true),
                position: 'fixed',
                zIndex: 10
            });

            $('.header-map #map').css({
                height: $('.header-map').height()
            });
        }
    }

    calc_side_map_search();
    $(window).resize(function () {
        calc_side_map_search();
    });

    /* RESET SEARCH */
    $(document).on('click', '.reset-search', function () {
        $('.filters-holder').html('');
        $('.advanced-filters').addClass('disabled');
        $('.search-form input').val('');
        $('.search-form select').select2('val', '');
        $('.search-form input.view').val('grid');
        $('.search-form').submit();
        $('.clear-input').addClass('hidden');
    });

    $('.search-form input[type="text"]').keypress(function(e) {
       if(e.which == 13) {
           $(this).parents('form').submit();
       }
   });

    /* ADD BUTTON CLASS */
    $('input#submit').addClass('btn');

    /* SUBMIT FORMS */
    $(document).on('click', '.submit-form', function () {
        $(this).parents('form').submit();
    });


    /* RESPONSIVE SLIDES */
    function start_slides() {
        $('.post-slider').responsiveSlides({
            speed: 800,
            auto: false,
            pager: false,
            nav: true,
            prevText: '<i class="fa fa-angle-left"></i>',
            nextText: '<i class="fa fa-angle-right"></i>',
        });
    }

    start_slides();


    /* GOOGLE MAPS */
    $('.submit-form-ajax').parents('form').submit(function (e) {
        e.preventDefault();
    });

    $('form:not(.search-form)').append('<input type="hidden" name="captcha" value="1">');

    $(document).on('click', '.submit-form-ajax', function () {
        var $this = $(this);
        var $form = $this.parents('form');
        var text = $this.html();
        $this.addClass('disabled');
        $this.html('<i class="fa fa-pulse fa-spinner"></i>');
        if ($form.find('#ad_description').length > 0) {
            if (typeof tinyMCE !== 'undefined' && tinyMCE.get('ad_description')) {
                var tiny = tinyMCE.get('ad_description').getContent();
                $('#ad_description').val(tiny);
            }
        }
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: $form.serialize(),
            dataType: 'JSON',
            success: function (response) {

                if ($form.find('.g-recaptcha').length > 0) {
                    grecaptcha.reset();
                }

                var ajax_response_div = $form.prev('.ajax-response');
                ajax_response_div.html(response.message);
                $('html, body').animate({ scrollTop: ajax_response_div.offset().top - 50}, 1000);

                // if ($form.find('.ajax-response').length > 0) {
                //     $form.find('.ajax-response').html(response.message);

                //     //$('html, body').animate({ scrollTop: $form.find('.ajax-response').eq(0).offset().top - 50}, 1000);

                // }
                // else {
                //     $('.ajax-response:first').html(response.message);

                //     $('html, body').animate({ scrollTop: $('.ajax-response:first').offset().top - 50}, 1000);

                // }

                if(response.message.indexOf("alert-success") > 0) {
                    $form.hide();
                }

                if (response.url) {
                    //var appendType = ( response.url.indexOf("?") < 0 ) ? "?" : "&";
                    //window.location = response.url + appendType + "_c=" + (new Date()).getTime();
                    window.location = response.url;
                }
                //initiate_stripe();
            },
            complete: function () {
                $this.html(text);
                $this.removeClass('disabled');
            }
        });
    });

    /* EQUAL WIDGET HEIGHT FOR THE MEGAMENU */
    function is_ie() {

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
            return true;
        }
        else {
            return false;
        }
    }

    if (is_ie()) {
        $('.mega_menu').width($('.nav.navbar-nav').width());
    }


    var $mega_menu = $('.mega_menu');
    var mega_menu_height = $mega_menu.outerHeight(true);

    function update_mega_menu() {
        if ($(window).width() > 768) {
            $mega_menu.height(mega_menu_height);
        }
        else {
            $mega_menu.height('auto');
        }
    }

    update_mega_menu();
    $(window).resize(function () {
        update_mega_menu();
    });

    /* CONTACT MAP */
    var $contact_map = $('.contact_map');
    if ($contact_map.length > 0) {
        var markers = [];
        $('.contact_map_marker').each(function () {
            var temp = $(this).val().split(',');
            markers.push({
                longitude: temp[0].trim(),
                latitude: temp[1].trim()
            })
        });
        var markersArray = [];
        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {
            scrollwheel: $('.contact_map_scroll_zoom').length > 0 ? false : true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map"), mapOptions);
        var location;
        if (markers.length > 0) {
            for (var i = 0; i < markers.length; i++) {
                location = new google.maps.LatLng(markers[i].longitude, markers[i].latitude);
                bounds.extend(location);

                var marker = new google.maps.Marker({
                    position: location,
                    map: map,
                });
            }

            map.fitBounds(bounds);
            var listener = google.maps.event.addListener(map, "idle", function () {
                if (classifieds_data.contact_map_zoom != '') {
                    map.setZoom(parseInt(classifieds_data.contact_map_zoom));
                    google.maps.event.removeListener(listener);
                }
            });
        }
    }

    /* CLIENTS SCROLLING */
    $('.clients-owl').owlCarousel({
        rtl: $('.rtl').length > 0 ? true : false,
        items: 4,
        nav: false,
        dots: false,
        responsive: {
            0: {items: 1},
            400: {items: 2},
            600: {items: 3},
            800: {items: 4}
        }
    });

    /* SEARCHABLE DROPDOWNS */
    // $('.select2').select2({allowClear: true, autofocusInputOnOpen: false});
    // $('.select2').on('select2-open', function (e) {
    //     $('.select2-search input').blur();
    // });

    /* ADS */
    function start_product_sliders() {
        $('.ads-slider').each(function () {
            var max_visible = 4;
            var responsive = {
                0: {items: 1},
                600: {items: 2},
                900: {items: 3},
                990: {items: 4},
            };
            if ($(this).hasClass("style2")) {
                max_visible = 2;
                responsive = {
                    0: {items: 1},
                    770: {items: 2},
                };
            }
            var nav = true;
            if ($(this).find(' > div').length <= max_visible) {
                nav = false;
            }
            var $this = $(this);
            if (!$this.hasClass('no-slider')) {
                if ($this.parent().hasClass('active')) {
                    $this.on('initialized.owl.carousel', function (event) {
                        $this.find('.owl-prev').addClass('disabled');
                        if (event.item.count <= event.page.size) {
                            $this.find('.owl-next').addClass('disabled');
                        }
                    });
                    $this.owlCarousel({
                        rtl: $('.rtl').length > 0 ? true : false,
                        margin: 30,
                        dots: false,
                        nav: nav,
                        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                        responsive: responsive,
                    });
                    $this.on('translated.owl.carousel', function (event) {
                        $this.find('.owl-next').removeClass('disabled');

                        var increment = 0;
                        if ($(window).width() >= 770 && max_visible == 2) {
                            increment = 2;
                        }
                        else if ($(window).width() >= 990) {
                            increment = 4;
                        }
                        else if ($(window).width() >= 900) {
                            increment = 3;
                        }
                        else if ($(window).width() >= 600) {
                            increment = 2;
                        }

                        if (event.item.index + increment >= event.item.count) {
                            $this.find('.owl-next').addClass('disabled');
                        }

                        if (event.item.index == 0) {
                            $this.find('.owl-prev').addClass('disabled');
                        }
                        else {
                            $this.find('.owl-prev').removeClass('disabled');
                        }
                    });

                }
            }
        });
    }

    start_product_sliders();
    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        start_product_sliders();
        if (e.target.hash === '#location') {
            google.maps.event.trigger(document.getElementById("map"), 'resize');
        }
    });

    /* CATEGORIES SLIDER */
    $('.categories-slider').each(function () {
        var $this = $(this);
        $this.on('initialized.owl.carousel', function (event) {
            $this.find('.owl-prev').addClass('disabled');
            if (event.item.count <= event.page.size) {
                $this.find('.owl-next').addClass('disabled');
            }
        });
        $this.owlCarousel({
            rtl: $('.rtl').length > 0 ? true : false,
            margin: 30,
            dots: false,
            nav: true,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsive: {
                0: {items: 1},
                770: {items: 2},
            },
        });
        $this.on('changed.owl.carousel', function (event) {
            $this.find('.owl-next').removeClass('disabled');

            var increment = 0;
            if ($(window).width() >= 770) {
                increment = 2;
            }
            if (event.item.index + increment >= event.item.count) {
                $this.find('.owl-next').addClass('disabled');
            }

            if (event.item.index == 0) {
                $this.find('.owl-prev').addClass('disabled');
            }
            else {
                $this.find('.owl-prev').removeClass('disabled');
            }
        });
    });

    /* START HEADER MAP */
    var map, infoWindow;

    function start_cluster_inspection(cluster) {
        var content = '<ul class="list-unstyled info-box-markers-list ' + ( classifieds_data.map_price == 'yes' ? '' : 'no-price-list' ) + '">';
        var markers = cluster.getMarkers();
        var addedMarkers = [];
        for (var i = 0; i < markers.length; i++) {
            if (addedMarkers.indexOf(markers[i].id) == -1) {
                content += '<li>' + htmlspecialchars_decode(markers[i].infoWindow) + '</li>';
                addedMarkers.push(markers[i].id);
            }
        }
        content += '</ul>';
        closeInfoWindow();
        infoWindow.setContent(content);
        infoWindow.open(map, markers[markers.length - 1]);
        infoWindow.setOptions({
            pixelOffset: new google.maps.Size(0, 0),
            maxWidth: 320
        });
        infoWindow.setPosition(cluster.getCenter());

        updateInfoBoxScrollData();
    }

    function updateInfoBoxScrollData() {
        if ($('.infoBox').length > 0) {
            $(document).off('mousewheel', '.infoBox');
            var infoBoxHeight = $('.infoBox').height(),
                infoBoxscrollHeight = $('.infoBox').get(0).scrollHeight;
            $(document).on('mousewheel', '.infoBox', function (event) {
                var blockScrolling = this.scrollTop >= infoBoxscrollHeight - infoBoxHeight && event.originalEvent.wheelDelta < 0 || this.scrollTop === 0 && event.originalEvent.wheelDelta > 0;
                return !blockScrolling;
            });
        }
    }

    function closeInfoWindow() {
        infoWindow.close();
    }

    function big_map_start() {
        if ($('.big_map').length > 0) {
            var isMobile = $(window).width() <= 1024 ? true : false;
            var empty_search_location = classifieds_data.empty_search_location.split(',');
            var options = {
                zoom: 13,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                scrollwheel: $('.map-left-content').length > 0 ? true : false,
                center: new google.maps.LatLng(empty_search_location[0], empty_search_location[1])
            };

            if ($('.markers.hidden .marker').length == 0 && $('.center-latitude').val() !== '') {
                options.center = new google.maps.LatLng($('.center-latitude').val(), $('.center-longitude').val());
            }

            map = new google.maps.Map(document.getElementById("map"), options);
            map.set('styles', [
                {
                    "featureType": "road",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "lightness": 100
                        },
                        {
                            "visibility": "simplified"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "color": "#979897"
                        },
                        {
                            "lightness": 50
                        },
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#C6E2FF"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#C5E3BF"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#D1D1B8"
                        }
                    ]
                }
            ]);
            var bounds = new google.maps.LatLngBounds();
            infoWindow = new InfoBox({
                content: '',
                maxWidth: 310,
                alignBottom: true,
                pixelOffset: new google.maps.Size(-46, -75),
                enableEventPropagation: true
            });
            var markers = [];
            var counter_id = 0;
            $('.markers.hidden .marker').each(function () {
                var $this = $(this);
                var latLng = new google.maps.LatLng($this.data('latitude'), $this.data('longitude'));
                bounds.extend(latLng);

                var marker = new google.maps.Marker({
                    position: latLng,
                    infoWindow: $this.html().trim(),
                    icon: $this.data('marker-icon'),
                    id: counter_id
                });
                counter_id++;

                marker.addListener('click', function () {
                    infoWindow.setContent(htmlspecialchars_decode(marker.infoWindow));
                    infoWindow.open(map, marker);
                    infoWindow.setOptions({
                        maxWidth: 310
                    });
                });

                markers.push(marker);
            });

            if (markers.length > 0) {

                map.fitBounds(bounds);
                var markerCluster = new MarkerClusterer(map, markers, {
                    styles: [
                        {
                            height: 55,
                            url: classifieds_data.url + "/images/m1.png",
                            width: 55,
                            textColor: '#ffffff'
                        },
                        {
                            height: 65,
                            url: classifieds_data.url + "/images/m2.png",
                            width: 65,
                            textColor: '#ffffff'
                        },
                        {
                            height: 75,
                            url: classifieds_data.url + "/images/m3.png",
                            width: 75,
                            textColor: '#ffffff'
                        },
                        {
                            height: 85,
                            url: classifieds_data.url + "/images/m4.png",
                            width: 85,
                            textColor: '#ffffff'
                        },
                        {
                            height: 95,
                            url: classifieds_data.url + "/images/m5.png",
                            width: 95,
                            textColor: '#ffffff'
                        }
                    ],
                    calculator: function (markers, numStyles) {
                        if (markers.length >= 12) return {text: markers.length, index: 5};
                        if (markers.length >= 9) return {text: markers.length, index: 4};
                        if (markers.length >= 6) return {text: markers.length, index: 3};
                        if (markers.length >= 3) return {text: markers.length, index: 2};
                        return {text: markers.length, index: 1};
                    }
                });

                google.maps.event.addListener(infoWindow, "closeclick", function () {
                    markerCluster.setZoomOnClick(true);
                });

                google.maps.event.addListener(markerCluster, 'click', function (cluster) {
                    var markers = cluster.getMarkers();
                    closeInfoWindow();
                    var pos;
                    var allSame = true;
                    for (var i = 0; i < markers.length; i++) {
                        if (!pos) {
                            pos = markers[i].position;
                        }
                        else if (!pos.equals(markers[i].position)) {
                            allSame = false;
                        }
                    }
                    if (allSame) {
                        start_cluster_inspection(cluster);
                        markerCluster.setZoomOnClick(false);
                    }
                });
            }

            var listener = google.maps.event.addListener(map, "idle", function () {
                if (classifieds_data.home_map_geolocation == 'yes') {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(gelocationMap);
                    }
                }
                if (classifieds_data.home_map_zoom !== '') {
                    if (markers.length == 0) {
                        map.setZoom(parseInt(classifieds_data.home_map_zoom));
                    }
                }
                google.maps.event.removeListener(listener);
            });
        }
    }

    big_map_start();

    function gelocationMap(position) {
        var location = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        map.setCenter(location);
    }

    function htmlspecialchars_decode(string, quote_style) {
        //       discuss at: http://phpjs.org/functions/htmlspecialchars_decode/
        //      original by: Mirek Slugen
        //      improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        //      bugfixed by: Mateusz "loonquawl" Zalega
        //      bugfixed by: Onno Marsman
        //      bugfixed by: Brett Zamir (http://brett-zamir.me)
        //      bugfixed by: Brett Zamir (http://brett-zamir.me)
        //         input by: ReverseSyntax
        //         input by: Slawomir Kaniecki
        //         input by: Scott Cariss
        //         input by: Francois
        //         input by: Ratheous
        //         input by: Mailfaker (http://www.weedem.fr/)
        //       revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // reimplemented by: Brett Zamir (http://brett-zamir.me)
        //        example 1: htmlspecialchars_decode("<p>this -&gt; &quot;</p>", 'ENT_NOQUOTES');
        //        returns 1: '<p>this -> &quot;</p>'
        //        example 2: htmlspecialchars_decode("&amp;quot;");
        //        returns 2: '&quot;'

        var optTemp = 0,
            i = 0,
            noquotes = false;
        if (typeof quote_style === 'undefined') {
            quote_style = 2;
        }
        string = string.toString()
            .replace(/&lt;/g, '<')
            .replace(/&gt;/g, '>');
        var OPTS = {
            'ENT_NOQUOTES': 0,
            'ENT_HTML_QUOTE_SINGLE': 1,
            'ENT_HTML_QUOTE_DOUBLE': 2,
            'ENT_COMPAT': 2,
            'ENT_QUOTES': 3,
            'ENT_IGNORE': 4
        };
        if (quote_style === 0) {
            noquotes = true;
        }
        if (typeof quote_style !== 'number') {
            // Allow for a single string or an array of string flags
            quote_style = [].concat(quote_style);
            for (i = 0; i < quote_style.length; i++) {
                // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
                if (OPTS[quote_style[i]] === 0) {
                    noquotes = true;
                } else if (OPTS[quote_style[i]]) {
                    optTemp = optTemp | OPTS[quote_style[i]];
                }
            }
            quote_style = optTemp;
        }
        if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
            string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
            // string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
        }
        if (!noquotes) {
            string = string.replace(/&quot;/g, '"');
        }
        // Put this in last place to avoid escape being double-decoded
        string = string.replace(/&amp;/g, '&');

        return string;
    }

    /* LOAD MORE */
    function do_ajax_pagination($this) {
        var $text = $this.addClass('no-remove').html();
        var next_link = $this.data('next_link');
        $this.html('<i class="fa fa-spinner fa-pulse"></i>');
        $this.addClass('disabled');

        $.ajax({
            url: next_link,
            success: function (response) {
                $this.before($(response).find('.ajax-container').html());

                var $link = $(response).find('.load-more').addClass('to-remove').attr('data-next_link');

                if ($link != "") {
                    $this.data('next_link', $link);
                }
                else {
                    $this.remove();
                }
                $('.load-more:not(.no-remove)').remove();
                start_slides();
            },
            complete: function () {
                $this.html($text);
                $this.removeClass('disabled');
            }
        });
    }

    $('.load-more').click(function (e) {
        e.preventDefault();
        do_ajax_pagination($(this));
    });


    /* START OWL THUMBS ON AD SINGLE */
    if($(window).width() >= 992) {
        $('.ad-single-thumbs').owlCarousel({
            rtl: $('.rtl').length > 0 ? true : false,
            items: 6,
            margin: 0,
            // stagePadding: 25,
            nav: $('.ad-single-thumbs > div').length > 1 ? true : false,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            dots: false,
        });
    } else {
        $('.single-ad-image').removeClass('hidden');
        // $('.images-list').addClass('owl-carousel owl-theme');
        $('.images-list').owlCarousel({
            items: 1,
            margin: 0,
            nav: false,
            // navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        });

        // $(document).keyup(function(i){
        //     if(i.keyCode==37) {
        //         $('.ad-single-thumbs').trigger('prev.owl.carousel');
        //     } else if (i.keyCode==39) {
        //         $('.ad-single-thumbs').trigger('next.owl.carousel');
        //     }
        // });

    }



    var items = [];
    var $imageLinks = $('.single-ad-image');
    // $imageLinks.each(function () {
    //     var $this = $(this);
    //     if ($this.hasClass('video')) {
    //         items.push({
    //             src: $(this).attr('href'),
    //             type: 'iframe',
    //         });
    //     }
    //     else {
    //         items.push({
    //             src: $(this).attr('href'),
    //             type: 'image',
    //         });
    //     }
    // });
    // $imageLinks.magnificPopup({
    //     mainClass: 'mfp-fade',
    //     items: items,
    //     type: 'image',
    //     gallery: {enabled: true},
    //     callbacks: {
    //         beforeOpen: function () {
    //             var index = $imageLinks.index(this.st.el);
    //             if (-1 !== index) {
    //                 this.goTo(index);
    //             }
    //         }
    //     }
    // });

    $(document).on('click', '.single-ad-thumb', function () {
        var $this = $(this);
        var item = $this.data('item');

        $('.single-ad-image').addClass('hidden');
        $('.single-ad-image.item-' + item).removeClass('hidden');

    });

    /* REVEAL NUMBER  */
    $(document).on('click', '.phone-reveal', function () {
        var $this = $(this);
        var last = $this.data('last');
        $this.text($this.text().replace('XXX', last));
    });

    /* SINGLE AD MAP */
    var $single_map = $('#single-map');
    if ($single_map.length > 0) {
        var data = $single_map.find('.hidden').text().trim().split("|");
        var location = new google.maps.LatLng(data[0], data[1]);

        var mapOptions = {
            scrollwheel: false,
            draggable: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: location,
            zoom: classifieds_data.contact_map_zoom ? parseInt(classifieds_data.contact_map_zoom) : 12
        };

        var map = new google.maps.Map(document.getElementById("single-map"), mapOptions);

        var marker = new google.maps.Marker({
            position: location,
            map: map,
            icon: data[2] ? data[2] : ''
        });
    }

    /* CONNECT GOOGLE SEARCH WITH THE LOCATION INPUT */
    if ($('.google-location').length > 0) {
        $(document).on('keyup', '.google-location', function () {
            $('input.longitude').val('');
            $('input.latitude').val('');
        });
        var options = {};
        if (classifieds_data.restrict_country !== '') {
            options['componentRestrictions'] = {
                country: classifieds_data.restrict_country
            };
        }
        var searchBox = new google.maps.places.Autocomplete($('.google-location')[0], options);
        google.maps.event.addListener(searchBox, 'place_changed', function () {
            var place = searchBox.getPlace();

            $('.longitude').val(place.geometry.location.lng());
            $('.latitude').val(place.geometry.location.lat());

            if ($('.page-template-page-tpl_search_page').length > 0) {
                $('.search-form').submit();
            }
        });

        $('.google-location').val($('.google-location').val().replace(/\\/i, ''));
    }

    /* UDPATE TOTAL VALUE */
    $(document).on('change', '#ad_featured', function () {
        if ($(this).prop('checked')) {
            $('.featured-info').removeClass('hidden');
            $('.total-amount').text($('.total-amount').data('featured'));
        }
        else {
            $('.featured-info').addClass('hidden');
            $('.total-amount').text($('.total-amount').data('basic'));
        }
    });

    /* DELETE AD */
    $(document).on('click', '.remove-ad', function () {
        var r = confirm($(this).data('confirm'));
        if (r == true) {
            window.location = $(this).data('delete_link');
        }
    });

    /* pay with stripe */
    function initiate_stripe() {
        if ($('.ad-manage .payments').length > 0 || $('.ad-manage .alert-success').length > 0) {
            $('.submit-form-ajax').remove();

            $('html,body').animate({
                scrollTop: $('.ad-manage .payments').offset().top - ( $('#wpadminbar').length > 0 ? $('#wpadminbar').height() : 0 ) - 10
            }, 1000);
        }
        if ($('.stripe-payment').length > 0) {
            var ad_id;
            var handler = StripeCheckout.configure({
                key: $('.stripe-payment').attr('data-pk'),
                token: function (token) {
                    $('.ajax-response').append('<div class="alert alert-info">' + $('.stripe-payment').data('genearting_string') + '</div>')

                    $.ajax({
                        url: ajaxurl,
                        method: 'POST',
                        data: {
                            action: 'pay_with_stripe',
                            token: token,
                            ad_id: ad_id,
                        },
                        success: function (response) {
                            if (response.indexOf('stripe-complete') > -1) {
                                $('.ajax-response').html(response);
                            }
                            else {
                                $('.ajax-response').append(response);
                            }
                        }
                    });
                }
            });
            $(document).on('click', '.stripe-payment', function (e) {
                e.preventDefault();
                handler.open({
                    name: $(this).attr('data-name'),
                    description: $(this).attr('data-description'),
                    amount: $(this).attr('data-amount'),
                    currency: $(this).attr('data-currency')
                });
                ad_id = $(this).attr('data-ad_id');
            });
            // Close Checkout on page navigation
            $(window).on('popstate', function () {
                handler.close();
            });
        }
    }

    /* payment with skrill */
    $(document).on('click', '.skrill-payment', function () {
        $('.skrill-form').submit();
    });

    if (window.location.hash.indexOf('comment') > -1) {
        $('a[href="#reviews"]').click();
    }

    /* pay with ideal */
    $(document).on('click', '.submit-ideal-payment', function () {
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: $('.ideal-payment').serialize(),
            success: function (response) {
                if (response.indexOf('http') > -1) {
                    window.location.href = response;
                }
                else {
                    $('.ajax-response').append(response);
                }
            }
        })
    });

    /* PAY WITH PAYU */
    $(document).on('click', '.payu-initiate', function () {
        var $this = $(this);
        var $form = $this.next();

        if ($form.hasClass('payu-submit-click')) {
            $form.submit();
        }
        else {
            var $clone = $form.clone();
            $clone.removeClass('hidden');
            $('.payu-content-modal').html('');
            $('.payu-content-modal').append($clone);
            $('#payUAdditional').modal('show');
        }
    });

    $(document).on('click', '.forgot-password', function () {
        $('#login').on('hidden.bs.modal', function (e) {
            $('#recover').modal('show');
            $('#login').off('hidden.bs.modal');
        })
    });

    $(document).on('click', '.resend-activation', function () {
        $('#login').on('hidden.bs.modal', function (e) {
            $('#resend-activation').modal('show');
            $('#login').off('hidden.bs.modal');
        })
    });

    $(document).on('click', '#login .register-close-login', function () {
        $('#login').on('hidden.bs.modal', function (e) {
            $('#register').modal('show');
            $('#login').off('hidden.bs.modal');
        })
    });

    $(document).on('click', '#register .register-close-login', function () {
        $('#register').on('hidden.bs.modal', function (e) {
            $('#login').modal('show');
            $('#register').off('hidden.bs.modal');
        })
    });

    $(document).on('click', '.payu-additional-info', function () {
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: $(this).parents('form').serialize(),
            success: function (response) {
                $('#payUAdditional .payu-content-modal').html(response);
                var $$form = $('#payUAdditional .payu-content-modal .payu-submit');
                if ($$form.length > 0) {
                    $$form.submit();
                }
            }
        });
    });

    /* STAY ON CURRENT PROFILE TAB */
    $(document).on('click', '.profile-tabs a', function () {
        $('.profile-form').attr('action', $(this).attr('href').replace('#', '#!'));
    });

    if (window.location.hash && $('.profile-tabs').length > 0) {
        $('a[href="' + window.location.hash.replace('!', '') + '"]').trigger('click');
    }


    /* CHANGE LIST VIEW */
    $(document).on('click', '.change_view', function () {
        $('.search-form input.view').val($(this).data('value'));
        // $('.search-form').submit();
    });

    /* CHANGE SORT */
    $(document).on('change', '.change_sort', function () {
        $('.search-form input.sortby').val($(this).val());
        $('.search-form').submit();
    });

    $('.search-form ul.categories li a').click(function(e) {
        e.preventDefault();
        var tax_id = '';

        if($(this).attr('href').indexOf('category=') > 0) {
            tax_id = $(this).attr('href').replace('?category=', '')
        }

        $('.search-form input#ad-category').val(tax_id);
        $('.search-form').submit();

    });
    $('.search-form ul.locations li a').click(function(e) {
        e.preventDefault();
        var tax_id = '';

        if($(this).attr('href').indexOf('location=') > 0) {
            tax_id = $(this).attr('href').replace('?location=', '')
        }

        $('.search-form input#ad-location').val(tax_id);
        $('.search-form').submit();

    });

    /* MAGNIFIC POPUP FOR THE GALLERY */
    function start_galleries() {
        $('.gallery').each(function () {
            var $this = $(this);
            $this.magnificPopup({
                type: 'image',
                delegate: 'a',
                gallery: {enabled: true},
                image: {
                    verticalFit: false
                }
            });
        });
    }

    start_galleries();


    /* PRINT AD */
    $(document).on('click', '.print-ad', function () {
        $('.phone-reveal').click();
        window.print();
    });

    /* OPEN CONFIRMATION MODAL */
    if ($('#confirmation').length > 0) {
        $('#confirmation').modal('show');
    }

    /* MASONRY ITEMS */
    var $container = $('.masonry');
    var has_masonry = false;
    // initialize
    function start_masonry() {
        if ($(window).width() < 550 && has_masonry) {
            $container.masonry('destroy');
            has_masonry = false;
        }
        else if ($(window).width() >= 550 && !has_masonry) {
            $container.imagesLoaded(function () {
                $container.masonry({
                    itemSelector: '.masonry-item',
                    columnWidth: '.masonry-item',
                });
                has_masonry = true;
            });
        }
    }

    if ($container.length > 0) {
        start_masonry();
        $(window).resize(function () {
            setTimeout(function () {
                start_masonry();
            }, 500);
        });
    }

    /* BREAK BOOTSTRAP GRID FROM 3 To 2 */
    function bootstrap_3_to_2() {
        if ($('.page .search-results .col-md-4').length > 0) {
            var $html = [];
            var pointer = 0;
            var counter = 0;
            var max_number = 2;
            if ($(window).width() > 1030) {
                max_number = 3;
            }
            $('.page .search-results .col-md-4').each(function () {
                counter++;
                var $this = $(this);
                if (!$html[pointer]) {
                    $html[pointer] = '';
                }
                $html[pointer] += '<div class="' + $this.attr('class') + '">' + $this.html() + '</div>';
                if (max_number == counter) {
                    counter = 0;
                    $html[pointer] = '<div class="row">' + $html[pointer] + '</div>';
                    pointer++;
                }
            });

            if ($html.length > 0) {
                if ($html[$html.length - 1].indexOf('row') == -1) {
                    $html[$html.length - 1] = '<div class="row">' + $html[$html.length - 1] + '</div>';
                }
            }

            $('.page .search-results').html($html.join(''));
        }
    }

    if ($('.page .search-results .col-md-4').length > 0) {
        bootstrap_3_to_2();
        $(window).resize(function () {
            bootstrap_3_to_2();
        });
    }

    /* BREAK ADS IN NO SLIDER */
    function bootstrap_4_to_2() {
        $('.ads-slider.no-slider.style1').each(function () {
            var $$this = $(this);
            if ($$this.find('.col-md-3').length > 0) {
                var $html = [];
                var pointer = 0;
                var counter = 0;
                var max_number = 1;
                if ($(window).width() > 500) {
                    max_number = 2;
                }
                if ($(window).width() > 900) {
                    max_number = 4;
                }
                $$this.find('.col-md-3').each(function () {
                    counter++;
                    var $this = $(this);
                    if (!$html[pointer]) {
                        $html[pointer] = '';
                    }
                    $html[pointer] += '<div class="' + $this.attr('class') + '">' + $this.html() + '</div>';
                    if (max_number == counter) {
                        counter = 0;
                        $html[pointer] = '<div class="row">' + $html[pointer] + '</div>';
                        pointer++;
                    }
                });

                if ($html.length > 0) {
                    if ($html[$html.length - 1].indexOf('row') == -1) {
                        $html[$html.length - 1] = '<div class="row">' + $html[$html.length - 1] + '</div>';
                    }
                }
                $$this.html($html.join(''));
            }
        });
    }

    if ($('.ads-slider.no-slider.style1').length > 0) {
        bootstrap_4_to_2();
        $(window).resize(function () {
            bootstrap_4_to_2();
        });
    }

    /* AJAX ON SEARCH PAGE */
    var submit_text = $('.page-template-page-tpl_search_page .search-form .submit-form').html();

    function handle_ajax_response(response) {
        var $response = $('<div>' + response + '</div>');
        $('.search-results').html($response.find('.search-results').html());
        if (response.indexOf('markers hidden') > -1) {
            $('.markers').html(response.split('<div class="markers hidden">')[1].split('<section')[0]);
        }
        else {
            $('.markers').html('');
        }

        if ($('.center-longitude').length > 0) {
            $('.center-longitude').val($response.find('.center-longitude').val());
            $('.center-latitude').val($response.find('.center-latitude').val());
        }
        else {
            $('body').append($response.find('.center-longitude')).append($response.find('.center-latitude'));
        }

        $('.pagination').remove();
        if ($response.find('.pagination').html()) {
            $('.search-results').after($response.find('.pagination').parent());
        }
        $('.search-organizer').html($response.find('.search-organizer').html());
        $('.search-block').html($response.find('.search-block').html());

        big_map_start();
        bootstrap_3_to_2();
        end_loaders();
    }

    function start_loaders() {
        $('.page-template-page-tpl_search_page .search-form .submit-form').html('<i class="fa fa-pulse fa-spinner"></i>').addClass('disabled');
        $('.search-results').css('opacity', '0.5');
        $('.pagination').css('opacity', '0.5');
        $('.header-map').css('opacity', '0.5');
        $('.advanced-filters').addClass('disabled');
    }

    function end_loaders() {
        $('.page-template-page-tpl_search_page .search-form .submit-form').html(submit_text).removeClass('disabled');
        $('.search-results').css('opacity', '1');
        $('.pagination').css('opacity', '1');
        $('.header-map').css('opacity', '1');
        start_filter();
    }

    // $('.search-form').submit(function () {
    //     var $this = $(this);
    //     if ($this.find('.google-location').val() !== '') {
    //         if ($this.find('.latitude').val() == '') {
    //             $this.find('.google-location').val('');
    //         }
    //     }
    // });

    // $('.page-template-page-tpl_search_page .search-form').submit(function (e) {
    //     var $this = $(this);
    //     e.preventDefault();
    //     start_loaders();
    //     $.ajax({
    //         url: $this.attr('action'),
    //         data: $this.serialize(),
    //         success: function (response) {
    //             handle_ajax_response(response)
    //         }
    //     });
    // });

    // $(document).on('click', '.search-page .pagination a', function (e) {
    //     e.preventDefault();
    //     start_loaders();
    //     $.ajax({
    //         url: $(this).attr('href'),
    //         success: function (response) {
    //             handle_ajax_response(response)
    //         }
    //     });
    // });

    // $(document).on('change', '.page-template-page-tpl_search_page select.category, .page-template-page-tpl_search_page select.radius', function () {
    //     $('.page-template-page-tpl_search_page .search-form').submit();
    // });

    /* SUBMIT FORM ON KEYPRESS ON KEYWORD */
    // $(document).on('keypress', '.keyword', function (e) {
    //     var code = e.keyCode || e.which;
    //     if (code == 13) {
    //         $('.page-template-page-tpl_search_page .search-form').submit();
    //     }
    // });

    /* ADD CLEAR INPUT ION THE SEARCH FIELDS */
    $(document).on('change keyup', '.search-form input', function () {
        $(this).parent().find('.clear-input').addClass('hidden');
        if ($(this).val() !== '') {
            $(this).parent().find('.clear-input').removeClass('hidden');
        }
    });

    $(document).on('click', '.clear-input', function () {
        $(this).parent().find('input').val('');
        // if ($('.page-template-page-tpl_search_page').length > 0) {
        //     $('.search-form').submit();
        // }
        $(this).addClass('hidden');
    });

    /* LOAD AJAX SIDE MENU */
    $(document).on('click', '.page-side-menu a', function (e) {
        e.preventDefault();
        $('.page-side-menu').parents('.white-block').css('opacity', '0.6');
        $.ajax({
            url: $(this).attr('href'),
            success: function (response) {
                $('.page-side-menu').html($(response).find('.page-side-menu').html());
                $('.page-content').html($(response).find('.page-content').html());

                start_galleries();

                var offset = 0;
                var $admin = $('#wpadminbar');
                var st = $(window).scrollTop();
                var position = Math.round($('.page-side-menu').offset().top);

                if ($('.navigation').data('enable_sticky') == 'yes' && st > position) {
                    offset += $('.navigation').outerHeight(true);
                }
                if ($admin.length > 0 && $admin.css('position') == 'fixed') {
                    offset += $admin.height();
                }


                $("html, body").stop().animate(
                    {
                        scrollTop: position - offset
                    },
                    {
                        duration: 500,
                    }
                );
            },
            complete: function () {
                $('.page-side-menu').parents('.white-block').css('opacity', '1');
            }
        })
    });

    /* TOOGLE AD VISIBILITY */
    $(document).on('click', '.ad_visibility', function () {
        var input = $('input[name="ad_visibility"]');
        var val = input.val();
        if (val == 'no') {
            input.val('yes');
            $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
        }
        else {
            input.val('no');
            $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    /* Start select2 on custom filter */
    $(document).on('click', '.filter-results', function () {
        $('.filter-select').select2('destroy');
        $('.filters-holder').html('');
        $('.filter-form select').each(function () {
            var $this = $(this);
            if (!$this.parents('li').hasClass('hidden')) {
                var name = $this.attr('name');
                var val = $this.val();
                if (val) {
                    if (name.indexOf('[]') > -1) {
                        name = name.replace('[]', '');
                        val = val.join(',');
                    }
                    $('.filters-holder').append('<input type="hidden" name="cf_' + name + '" value="' + val + '">');
                }
            }
        });
        $('.search-form').submit();
    });
    $(document).on('change', '.page-template-page-tpl_search_page select.category', function () {
        $.ajax({
            url: ajaxurl + '?t=' + Date.now(),
            method: 'POST',
            cache: false,
            data: {
                action: 'custom_filter',
                category: $(this).val(),

            },
            success: function (response) {
                $('.filters-holder').html('');
                if (response != '') {
                    $('#filters .filters-modal-holder').html(response);
                }
                else {
                    $('#filters .filters-modal-holder').html('');
                }
            }
        })
    });

    // $('.page-template-page-tpl_search_page ul.subitem:has(li.active)').addClass('active');
    // $('.page-template-page-tpl_search_page ul.subitem:has(li.active)').prev('li').addClass('active');


    // $(document).on('click', '.page-template-page-tpl_search_page ul.categories a', function (e) {
    //     e.preventDefault();

    //     $('select[name="category"]').val($(this).attr('href'));

    //     $('.page-template-page-tpl_search_page .search-form').submit();

    // });

    $(document).on('change', '.filter-select:not(.conditional-hide)', function () {
        var $this = $(this);
        var val = $this.val();
        $('select[class$="-' + $this.data('field-id') + '"]').parents('li').addClass('hidden');
        if (val && val.constructor !== Array) {
            var hidden_select = val.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '').split(' ').join('') + '-' + $this.data('field-id');
            var $li = $('.' + hidden_select).parents('li');
            if ($li.length > 0) {
                $li.removeClass('hidden');
            }
        }
        else if (val && val.length > 0) {
            $('#s2id_' + $this.attr('id')).addClass('multiselect-chosen');
        }
        else {
            $('#s2id_' + $this.attr('id')).removeClass('multiselect-chosen');
        }
    });
    function start_filter() {
        var $advanced_filters = $('.advanced-filters');
        if ($advanced_filters.length > 0) {
            if ($('.filters-modal-holder').html().trim() !== '') {
                $advanced_filters.removeClass('disabled');
            }
            if ($('.filters-holder').html().trim() !== '') {
                $advanced_filters.text($advanced_filters.data('filtered'));
                $advanced_filters.addClass('filtered');
            }
            else {
                $advanced_filters.text($advanced_filters.data('default'));
                $advanced_filters.removeClass('filtered');
            }
            $('.filter-select').select2({allowClear: true, autofocusInputOnOpen: false});
        }
    }

    start_filter();

    $(document).on('click', '.tab-disable a', function (e) {
        if (!$(this).attr('data-toggle')) {
            e.preventDefault();
        }
    });

    /* NEXT PREV TABS */
    $('.next-tab, .prev-tab').on('click', function () {
        $("html, body").animate({scrollTop: 200}, 600);
    });
    $(document).on('click', '.next-tab', function () {
        var $this = $(this);
        if (!$this.hasClass('disabled')) {
            var tab = $this.parents('.tab-pane').attr('id');
            var $tab = $this.parents('.tab-pane');
            var $tab_link = $('a[href="#' + tab + '"]');

            var can_next = true;

            $tab.find('input.require, select.require').each(function () {
                var $this = $(this);
                if ($this.attr('type') && $this.attr('type') === 'checkbox') {
                    if (!$this.prop('checked')) {
                        can_next = false;
                    }
                }
                else if (!$this.val()) {
                    can_next = false;
                }
            });

            if ($tab.find('#ad_description').length > 0) {
                if (typeof tinyMCE !== 'undefined' && tinyMCE.get('ad_description')) {
                    var tiny = tinyMCE.get('ad_description').getContent();
                    if (!tiny) {
                        can_next = false;
                    }
                }
            }

            if (can_next) {
                $('.next-prev-error').addClass('hidden');
                $tab_link.parent().next().find('a').attr('data-toggle', 'tab').click();
            }
            else {
                $('.next-prev-error').removeClass('hidden');
            }
        }
    });

    $(document).on('click', '.prev-tab', function () {
        var $this = $(this);
        if (!$this.hasClass('disabled')) {
            var tab = $this.parents('.tab-pane').attr('id');
            var $tab = $this.parents('.tab-pane');
            var $tab_link = $('a[href="#' + tab + '"]');

            $tab_link.parent().next().find('a').attr('data-toggle', '');
            $tab_link.parent().prev().find('a').click();
        }
    });

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        start_product_sliders();
        if (e.target.hash === '#basic') {
            $('.prev-tab').addClass('disabled');
            $('.next-tab').removeClass('disabled');
        }
        else if (e.target.hash == '#final') {
            $('.next-tab').addClass('disabled');
            $('.prev-tab').removeClass('disabled');
        }
        else {
            $('.next-tab').removeClass('disabled');
            $('.prev-tab').removeClass('disabled');
        }
    });

    // $('.modal').on('shown.bs.modal', function () {
    //     if($("#recaptcha").length) {
    //         grecaptcha.render('recaptcha', {'sitekey': '6LdDoykUAAAAAKMPIq3qDMaAIGYMXk0zFT1JUQbN'});
    //     }
    // })

    // $('.modal').on('hide.bs.modal', function () {
    //     $("#recaptcha").empty();
    // })


});