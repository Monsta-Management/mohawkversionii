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
    
    function sidebarMenuSticky() {
        var sidebar = document.querySelector( '#with-sidebar .sidebar' );
        var catlist = document.querySelector( '#with-sidebar .cat_list' );
        var footer = document.querySelector( 'footer.site-footer' );
        var header = document.querySelector( 'header.header-desktop' );
        var headerSpacer = 10;
        var testSpacer = 272;
        
        var lastScrollTop = 0;
        var sidebarInitialTop = 0;
        var sidebarInitialWidth = 0;
    
        if ( sidebar && footer && header ) {
            sidebarInitialTop = sidebar.getBoundingClientRect().top + window.scrollY;
            sidebarInitialWidth = sidebar.offsetWidth;
    
            var stickySidebar = function() {
                var scrollTop = window.scrollY;
                var sidebarHeight = sidebar.offsetHeight;
                var headerHeight = header.offsetHeight + headerSpacer;
                var footerTop = footer.getBoundingClientRect().top + window.scrollY;
                var sidebarTop = sidebarInitialTop;
                var sidebarAxis = sidebarInitialTop + headerHeight;
                var catlistHeight = footerTop - scrollTop;
                
                var viewportHeight = $( window ).height();
                var availableViewHeight = viewportHeight - headerHeight;

                // Adjust position if sticky and near the footer.
                if ( scrollTop + sidebarHeight + headerHeight > footerTop ) {
                    sidebar.style.top = 'auto';
                    catlist.style.height = catlistHeight - 185 + 'px';
                    
                    if ( $( '.s2 #with-sidebar .sidebar' ).length ) {
                        sidebar.style.top = '0';
                    }
                } else if (scrollTop >= sidebarTop - headerHeight) {
                    sidebar.classList.add( 'sticky' );
                    sidebar.style.top = headerHeight + 'px';
                    sidebar.style.width = sidebarInitialWidth + 'px';
                } else {
                    sidebar.classList.remove( 'sticky' );
                    sidebar.style.top = '';
                    catlist.style.height = '';
                    sidebar.style.width = '';
                }
    
                lastScrollTop = scrollTop;
            };
    
            window.addEventListener( 'scroll', stickySidebar );
            window.addEventListener( 'resize', stickySidebar );
        }
    }
    
    function sidebarMenuInit() {
        function showSidebarContent() {
            $( '#with-sidebar' ).css( 'opacity', 1 );
            $( '#loader-dom' ).css( 'display', 'none' );
        }
    
        $( window ).on( 'load', function() {
            showSidebarContent();
            sidebarMenuSticky();
            sidebarAutoContentHeight();
        } );
        
        $( window ).on( 'resize', function() {
            sidebarMenuSticky();
            sidebarAutoContentHeight();
        } );
    
        setTimeout( function() {
            if ( $( '#with-sidebar' ).css( 'opacity' ) == 0 ) {
                showSidebarContent();
            }
        }, 500 );
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
        var target = '.sidebar .cat_list ul';
        if ($(target).length) {
            $(target).each(function (index, element) {
                if ($(element).hasClass('active')) {
                    $(element).prependTo($(element).parent());
                }
            });
        }
    }
    
    function sidebarAutoContentHeight() {
        var sidebar = document.querySelector( '#with-sidebar .sidebar' );
        var content = document.querySelector( '#with-sidebar .content' );
        var productItems = document.querySelectorAll( '#with-sidebar .row-products > .product-item-wrap' );
        var threshold = 12;
    
        if ( sidebar && content ) {
            var sidebarHeight = sidebar.offsetHeight;
            var contentHeight = content.offsetHeight;
    
            if ( productItems.length < threshold ) {
                sidebar.classList.add( 'default-style' );
                content.classList.add( 'default-style' );
            } else {
                sidebar.classList.remove( 'default-style' );
                content.classList.remove( 'default-style' );
            }
        }
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
    
    function convertTableToMobile() {
        setTimeout(function () {
            if ($(window).width() <= 768) {
                $('.monstaprice').hide();
    
                $('#mobilePriceTable').remove(); // Remove old table before creating a new one.
    
                var mobileTable = '<div id="mobilePriceTable"><table class="mobile-price-table">';
                mobileTable += '<tbody>';
    
                var quantities = [];
                var prices = [];
    
                $('.monstaprice tr').each(function (rowIndex) {
                    var rowData = $(this).find('td, th');
    
                    // First row (headers) - ignore.
                    if (rowIndex === 0) return;
    
                    // Capture quantity row (2nd row).
                    if (rowIndex === 1) {
                        rowData.each(function (colIndex) {
                            quantities[colIndex] = $(this).text().trim();
                        });
                    }
    
                    // Find the active variant row (tr with class "tr-show").
                    if ($(this).hasClass('tr-show')) {
                        rowData.each(function (colIndex) {
                            prices[colIndex] = $(this).text().trim();
                        });
                    }
                });
    
                // Generate the table using active variant's price.
                if (quantities.length > 0 && prices.length > 0) {
                    quantities.forEach(function (qty, index) {
                        var price = prices[index] || ''; // Ensure there's a matching price.
                        mobileTable += `<tr><td>${qty}</td><td>${price}</td></tr>`;
                    });
                }
    
                mobileTable += '</tbody></table></div>';
    
                $('.variations').before(mobileTable);
            } else {
                $('.monstaprice').show();
                $('#mobilePriceTable').remove();
            }
        }, 200);
    }

    function observeTableChanges() {
        var targetNode = document.querySelector('.monstaprice');
    
        if (!targetNode) return; // Exit if table is not found
    
        var config = { childList: true, subtree: true, characterData: true };
    
        var observer = new MutationObserver(function () {
            convertTableToMobile();
        });
    
        observer.observe( targetNode, config );
    }function convertTableToMobile() {
        setTimeout(function () {
            if ($(window).width() <= 768) {
                $('.monstaprice').hide();
    
                $('#mobilePriceTable').remove(); // Remove old table before creating a new one.
    
                var mobileTable = '<div id="mobilePriceTable"><table class="mobile-price-table">';
                mobileTable += '<tbody>';
    
                var quantities = [];
                var prices = [];
    
                $('.monstaprice tr').each(function (rowIndex) {
                    var rowData = $(this).find('td, th');
    
                    // First row (headers) - ignore.
                    if (rowIndex === 0) return;
    
                    // Capture quantity row (2nd row).
                    if (rowIndex === 1) {
                        rowData.each(function (colIndex) {
                            quantities[colIndex] = $(this).text().trim();
                        });
                    }
    
                    // Find the active variant row (tr with class "tr-show").
                    if ($(this).hasClass('tr-show')) {
                        rowData.each(function (colIndex) {
                            prices[colIndex] = $(this).text().trim();
                        });
                    }
                });
    
                // Generate the table using active variant's price.
                if (quantities.length > 0 && prices.length > 0) {
                    quantities.forEach(function (qty, index) {
                        var price = prices[index] || ''; // Ensure there's a matching price.
                        mobileTable += `<tr><td>${qty}</td><td>${price}</td></tr>`;
                    });
                }
    
                mobileTable += '</tbody></table></div>';
    
                $('.variations').before(mobileTable);
            } else {
                $('.monstaprice').show();
                $('#mobilePriceTable').remove();
            }
        }, 200);
    }

    function observeTableChanges() {
        var targetNode = document.querySelector('.monstaprice');
    
        if (!targetNode) return; // Exit if table is not found
    
        var config = { childList: true, subtree: true, characterData: true };
    
        var observer = new MutationObserver(function () {
            convertTableToMobile();
        });
    
        observer.observe( targetNode, config );
    }
    
    $( document ).ready( function() {
        scrollClass();
        headerSubmenu();
        headerMobileMenu();
        headerMobileTopMenuSubmenu();
        initializeCollapsibleSubmenu();
        sidebarMenuButtonToggle();
        sidebarActiveListSort();
        sidebarMenuSticky();
        sidebarMenuInit();
        sidebarAutoContentHeight();
        searchInputPlaceholderCount();
        checkoutColMerged();
        changeFilterText();
        testimonialSliderInit();
        
        convertTableToMobile();
        observeTableChanges();
        
        console.log('TS to the new server!');
        
        $( window ).resize( convertTableToMobile );
    } );
}( window.jQuery || window.$ ) );