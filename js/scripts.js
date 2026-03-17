( function( $ ) {
    function scrollClass() {
        function checkScroll() {
            var scroll = $( window ).scrollTop();
            if ( scroll >= 10 ) {
                $( 'body' ).addClass( 'sticky' );
            } else {
                $( 'body' ).removeClass( 'sticky' );
            }
        }
        
        checkScroll();
        
        $( window ).scroll( function () {
            checkScroll();
        } );
    }
    
    function headerSubmenu() {
        var target = $( 'header .menu' );

        if ( target.length ) {
            var submenu = $( 'header .submenu-item' );

            target.find( '.menu-item' ).each( function() {
                var this_menu = $( this );
                
                this_menu.mouseenter( function() {
                    var cat = this_menu.data( 'cat' );

                    // Hide all submenu first.
                    submenu.removeClass( 'show-submenu' );

                    // Show the matched one.
                    submenu.each( function() {
                        if ( $(this).data( 'cat' ) == cat ){
                            $( this ).addClass( 'show-submenu' );
                        }
                    } );
                } );
            } );

            // Hide submenu when hover out.
            $( 'header .submenu-wrap' ).mouseleave( function () {
                submenu.removeClass( 'show-submenu' );
            } );
        }

        // Adjust max-height sub-menu.
        var target_hs = 'header .submenu-wrap .submenu-inner';
        
        if ( $( target_hs ).length ) {
            $( target_hs ).each( function() { 
                innerContent = $( this ).find( ".container .submenu-container" ).outerHeight();
                winHeight = $( window ).height();
                
                cls = (winHeight < 650) ? 'fixHeight-alt' : 'fixHeight';
                
                if ( innerContent > winHeight ) {
                    $( this ).addClass( cls );
                }
            } );
        }

        // INFO submenu.
        var targetSubMenuInfo = $( '.menu-info' );
        
        if ( targetSubMenuInfo.length ) {
            customSubmenu = $( ".sub-menu.custom-sub-menu" );
            var customSubmenuContent = customSubmenu.html();
            customSubmenuContent = customSubmenuContent.trim();

            if ( customSubmenuContent ) {
                // Remove exisint submenu.
                targetSubMenuInfo.find( ".sub-menu" ).remove();
                targetSubMenuInfo.append( customSubmenu );
                customSubmenu.removeClass( 'hidden' );
            }
        }
    }
    
    function headerMobileMenu() {
        var target = $( '.mobile-header-nav .menu_button a' );
        var targetClose = $( '#mobile-navigation .close' );

        if ( target.length ) {

            target.click( function( e ) {
                e.preventDefault();
                $( 'body' ).toggleClass( 'mobile_menu_open' );
            } );
        }
        
        if ( targetClose.length ) {

            targetClose.click( function( e ) {
                e.preventDefault();
                $( 'body' ).removeClass( 'mobile_menu_open' );
            } );
        }
    }
    
    function headerMobileTopMenuSubmenu() {
        $( '#mobile-navigation .menu-wrap .menu-item-has-children > a' ).each( function() {
            $( this ).append( '<span></span>' );
            
            $( this ).click( function( event ) {
                event.preventDefault();
                $( '#mobile-navigation .menu-wrap .menu-item-has-children' ).toggleClass( 'active' );
            } );
        } );
    }
    
    function initializeCollapsibleSubmenu() {
        jQuery(document).ready(function () {
    
            jQuery(".collapsible > span").on("click", function (e) {
                e.stopPropagation();
    
                var parent = this.closest(".collapsible");
                var content = parent.nextElementSibling;
    
                parent.classList.toggle("active");
    
                if (content.style.maxHeight) {
                    content.style.maxHeight = null;
                } else {
                    content.style.maxHeight = content.scrollHeight + "px";
                }
            });
    
            jQuery(".collapsible .view-category, .collapsible .category-name").on("click", function (e) {
                e.stopPropagation();
            });
    
            window.addEventListener("click", function (e) {
                if (
                    jQuery(e.target).closest(".collapsible").length === 0 &&
                    jQuery(e.target).closest(".content").length === 0
                ) {
                    jQuery(".collapsible.active").each(function () {
                        this.classList.remove("active");
    
                        var content = this.nextElementSibling;
                        if (content) {
                            content.style.maxHeight = null;
                        }
                    });
                }
            });
        });
    }
    
    function sidebarMenuButtonToggle() {
        var button = '.toggle_cats';
        if ( $( button ).length ) {
            var list = '.cat_list';
            
            $( button ).on( 'click', function () {
                if ( $( button ).text() === "SHOW CATEGORIES" ) {
                    $( button ).text('HIDE CATEGORIES');
                } else {
                    $( button ).text( 'SHOW CATEGORIES' );
                }
                $( list ).slideToggle();
            } );
        }
    }
    
    function sidebarActiveListSort() {
        var $target = $('.sidebar-fix .cat_list ul');
    
        // Move active <ul> to top
        $target.each(function() {
            if ($(this).hasClass('active')) {
                $(this).prependTo($(this).parent());
            }
        });
    
        // Hide all children initially
        $('.cat_list ul.children').hide();
    
        // Show children of active <ul> on page load
        $('.cat_list ul.active').each(function() {
            $(this).children('li').children('ul.children').stop(true, true).slideDown(250);
            $(this).addClass('open');
        });
    
        // Handle chevron click
        $('.cat_list li > a > .fa-chevron-down').on('click', function(e) {
            e.preventDefault(); // Prevent navigation
    
            var $parentLi = $(this).closest('li');
    
            // Stop any ongoing animation and toggle smoothly
            $parentLi.children('ul.children').stop(true, true).slideToggle(250);
    
            $parentLi.toggleClass('open');
        });
    }
    
    function searchInputPlaceholderCount() {
        var target = $( '.search-widget' );
    
        if ( target.length ) {
            var total = target.data( 'total' );
            var the_total = Math.ceil( total / 100 ) * 100;
            var placeholder = 'Search over ' + the_total.toLocaleString() + '+ products';
            target.find( '.aws-search-field' ).attr( 'placeholder', placeholder );
            target.find( '.aws-search-label' ).attr( 'placeholder', placeholder );
        }
    }
    
    function checkoutColMerged() {
        $('.woocommerce-shipping-fields, .woocommerce-additional-fields')
    .wrapAll('<div class="column-merge"></div>');
    }
    
    function changeFilterText() {
        $('select[name="orderby"] option[value="menu_order"]').text('FILTER PRODUCTS');
    }
    
    function testimonialSliderInit() {
        const swiper = new Swiper('#testimonial-slider.swiper', {
            slidesPerView: 1,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            speed: 1500,
        });
    }
    
    $( document ).ready( function() {
        scrollClass();
        headerSubmenu();
        headerMobileMenu();
        headerMobileTopMenuSubmenu();
        initializeCollapsibleSubmenu();
        sidebarMenuButtonToggle();
        sidebarActiveListSort();
        searchInputPlaceholderCount();
        checkoutColMerged();
        changeFilterText();
        testimonialSliderInit();
    } );
}( window.jQuery || window.$ ) );