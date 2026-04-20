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

	function handleMainMenuCarousel() {
		const menuListParent = document.querySelector('#menu-carousel > .menu-main-menu-container');
		const menuList = document.querySelector('#menu-carousel ul.menu');
		const menuItems = document.querySelectorAll('#menu-carousel ul.menu li');
		let currentIndex = 0;
	
		function createCarousel() {
			$('#menu-carousel').addClass('active');
	
			if (!document.querySelector('#prev-button') && !document.querySelector('#next-button')) {
				$(menuListParent).parent().append('<button id="prev-button" class="prev">&laquo;</button>');
				$(menuListParent).parent().append('<button id="next-button" class="next">&raquo;</button>');
		
				$('#next-button').on('click', function() {
					const maxIndex = getMaxIndex();
					if (currentIndex < maxIndex) {
						currentIndex++;
						updateCarousel();
					}
				});
		
				$('#prev-button').on('click', function() {
					if (currentIndex > 0) {
						currentIndex--;
						updateCarousel();
					}
				});
			}
	
			function updateCarousel() {
				const newPosition = -currentIndex * getMenuItemWidth();
				menuList.style.transform = `translateX(${newPosition}px)`;
	
				$('#prev-button').prop('disabled', currentIndex === 0);
				$('#next-button').prop('disabled', currentIndex >= getMaxIndex());
			}
	
			function getMenuItemWidth() {
				const viewportWidth = menuListParent.offsetWidth;
				let itemWidth = 150; // Default width for fallback.
	
				// Determine the number of items to display based on viewport width.
				if (viewportWidth <= 1024) {
					itemWidth = viewportWidth / 6;
				} else if (viewportWidth <= 1200) {
					itemWidth = viewportWidth / 7;
				} else if (viewportWidth <= 1600) {
					itemWidth = viewportWidth / 8;
				} else {
					itemWidth = 150;
				}
	
				// Apply the calculated width to each menu item.
				menuItems.forEach(item => {
					item.style.width = `${itemWidth}px`;
				});
	
				return itemWidth;
			}
	
			function getMaxIndex() {
				const viewportWidth = menuListParent.offsetWidth;
				const itemWidth = getMenuItemWidth();
				const maxItemsVisible = Math.floor(viewportWidth / itemWidth);
	
				return Math.max(0, menuItems.length - maxItemsVisible);
			}
	
			function adjustMenuVisibility() {
				const viewportWidth = menuListParent.offsetWidth;
				const itemWidth = getMenuItemWidth();
				const maxItemsVisible = Math.floor(viewportWidth / itemWidth);
	
				// Adjust the width of the menu to show only full items.
				menuList.style.width = `${itemWidth * maxItemsVisible}px`;
			}
	
			adjustMenuVisibility();
			updateCarousel();
		}
	
		function deleteCarousel() {
			$('#menu-carousel').removeClass('active');
			$('#prev-button').remove();
			$('#next-button').remove();
			menuList.style.transform = 'translateX(0)';
			menuList.style.width = 'auto';
			menuItems.forEach(item => {
				item.style.width = '';
			});
		}
	
		function checkScreenWidth() {
			if (window.innerWidth <= 1600) {
				createCarousel();
			} else {
				deleteCarousel();
			}
		}
	
		// Initial check on page load.
		checkScreenWidth();
	
		// Event listener for window resize.
		window.addEventListener('resize', function() {
			checkScreenWidth();
	
			// Additional logic to update carousel on resize.
			if (document.querySelector('#prev-button') && document.querySelector('#next-button')) {
				adjustMenuVisibility();
				currentIndex = 0;
				updateCarousel();
			}
		});
	}

	function headerSubmenu() {
		var target = $('header .menu');
		if (!target.length) return;
	
		var submenu = $('header .submenu-item');
		var disableHover = mohawkSubmenuSettings.disableHoverSubmenu;
	
		// Hover mode - default
		if (!disableHover) {
	
			target.find('.menu-item').each(function () {
				var this_menu = $(this);
	
				this_menu.on('mouseenter', function () {
					var cat = this_menu.data('cat');
	
					submenu.removeClass('show-submenu');
	
					submenu.each(function () {
						if ($(this).data('cat') == cat) {
							$(this).addClass('show-submenu');
						}
					});
				});
			});
	
			$('header .submenu-wrap').on('mouseleave', function () {
				submenu.removeClass('show-submenu');
			});
		}
	
		// toggle mode on caret
		if (disableHover) {
			// Remove hover behavior completely
			target.find('.menu-item').off('mouseenter mouseleave');
	
			target.on('click', '.subcat-caret', function (e) {
				e.preventDefault();
				e.stopPropagation();
	
				var thisMenu = $(this).closest('.menu-item');
				var cat = thisMenu.data('cat');
	
				// Toggle off if already active
				if (thisMenu.hasClass('active-submenu')) {
					thisMenu.removeClass('active-submenu');
					submenu.removeClass('show-submenu');
					return;
				}
	
				// Close others
				target.find('.menu-item').removeClass('active-submenu');
				submenu.removeClass('show-submenu');
	
				// Activate current
				thisMenu.addClass('active-submenu');
	
				submenu.each(function () {
					if ($(this).data('cat') == cat) {
						$(this).addClass('show-submenu');
					}
				});
			});
	
			// Click outside closes menu
			$(document).on('click', function (e) {
				if (!$(e.target).closest('header .menu, header .submenu-wrap').length) {
					submenu.removeClass('show-submenu');
					target.find('.menu-item').removeClass('active-submenu');
				}
			});
		}

		// Height adjustment
		var target_hs = 'header .submenu-wrap .submenu-inner';
	
		if ($(target_hs).length) {
			$(target_hs).each(function () {
				let innerContent = $(this).find(".container .submenu-container").outerHeight();
				let winHeight = $(window).height();
				let cls = (winHeight < 650) ? 'fixHeight-alt' : 'fixHeight';
	
				if (innerContent > winHeight) {
					$(this).addClass(cls);
				}
			});
		}
	
		// INFO submenu.
		var targetSubMenuInfo = $('.menu-info');
	
		if (targetSubMenuInfo.length) {
			let customSubmenu = $(".sub-menu.custom-sub-menu");
			let customSubmenuContent = customSubmenu.html()?.trim();
	
			if (customSubmenuContent) {
				targetSubMenuInfo.find(".sub-menu").remove();
				targetSubMenuInfo.append(customSubmenu);
				customSubmenu.removeClass('hidden');
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
		$('#mobile-navigation .menu-wrap .menu-item-has-children > a').each(function() {
			// Append the caret if not already appended.
			if (!$(this).find('> span').length) {
				$(this).append('<span class="submenu-caret"></span>');
			}
	
			$(this).off('click').on('click', function(event) {
				event.preventDefault();
	
				var parentLi = $(this).closest('.menu-item-has-children');
	
				// Toggle ONLY this menu item
				parentLi.toggleClass('active');
				parentLi.siblings('.menu-item-has-children').removeClass('active');
			});
		});
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

	function makeGalleryLightbox() {
		$( '#monsta-gallery .monsta-gallery--item > a' ).each( function() {
			$( this ).attr( 'data-lightbox', 'gallery' );
			$( this ).removeAttr( 'target' );
		} );
	}

	// Spinning image on HOVER start //
	function shouldInitHoverVideo() {
		return document.querySelector('.product-item-wrap .product-card[data-video-url]');
	}

	function initHoverVideo() {
		if (!shouldInitHoverVideo()) return;
	
		const cards = document.querySelectorAll('.product-item-wrap > .product-card:not([data-hover-bound])');
	
		cards.forEach(card => {
			const videoUrl = card.dataset.videoUrl;
			if (!videoUrl) return;
	
			const imageWrapper = card.querySelector('.product-inner-img > a');
			if (!imageWrapper) return;
	
			let loaderTimeout;
			let loader = null;
			let video = null;
			let isLoading = false;
	
			card.setAttribute('data-hover-bound', 'true');
	
			card.addEventListener('mouseenter', () => {
				if (video) return;
				isLoading = true;
	
				video = document.createElement('video');
				video.src = videoUrl;
				video.autoplay = true;
				video.muted = true;
				video.loop = true;
				video.playsInline = true;
				video.preload = 'metadata';
	
				Object.assign(video.style, {
					position: 'absolute',
					top: '0',
					left: '0',
					width: '100%',
					height: '100%',
					objectFit: 'cover',
					zIndex: '1',
					opacity: '0',
					transition: 'opacity 0.3s ease'
				});
	
				const img = imageWrapper.querySelector('img');
				if (img) img.style.transition = 'opacity 0.3s ease';
	
				imageWrapper.appendChild(video);
	
				loaderTimeout = setTimeout(() => {
					if (!isLoading) return;
	
					loader = document.createElement('div');
					loader.className = 'video-loading-spinner';
	
					Object.assign(loader.style, {
						position: 'absolute',
						top: '50%',
						left: '50%',
						transform: 'translate(-50%, -50%)',
						width: '30px',
						height: '30px',
						border: '3px solid rgba(0,0,0,0.25)',
						borderTop: '3px solid var(--secondary-color)',
						borderRadius: '50%',
						animation: 'video-spin 1s linear infinite',
						zIndex: '2'
					});
	
					imageWrapper.appendChild(loader);
				}, 120);
	
				video.addEventListener('canplaythrough', () => {
					isLoading = false;
					clearTimeout(loaderTimeout);
	
					if (img) img.style.opacity = '0';
					video.style.opacity = '1';
	
					if (loader) {
						loader.remove();
						loader = null;
					}
				});
			});
	
			card.addEventListener('mouseleave', () => {
				isLoading = false;
				clearTimeout(loaderTimeout);
	
				if (video) {
					video.pause();
					video.remove();
					video = null;
				}
	
				if (loader) {
					loader.remove();
					loader = null;
				}
	
				const img = imageWrapper.querySelector('img');
				if (img) img.style.opacity = '1';
			});
		});
	}
	
	function initHoverVideoObserver() {
		if (!shouldInitHoverVideo()) return;
	
		const wrap = document.querySelector('.infinite-wrap');
		if (!wrap) return; // making sure that it will not observe body unnecessarily
	
		const observer = new MutationObserver(() => {
			initHoverVideo();
		});
	
		observer.observe(wrap, {
			childList: true,
			subtree: true
		});
	}
	// Spinning image on HOVER end //
	
	$( document ).ready( function() {
		scrollClass();
		handleMainMenuCarousel();
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
		makeGalleryLightbox();

		// Spinning image on HOVER
		if (shouldInitHoverVideo()) {
			initHoverVideo();
			initHoverVideoObserver();
		}
	} );
}( window.jQuery || window.$ ) );