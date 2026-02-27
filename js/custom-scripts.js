(function($) {
    function bannerSliderInit() {
        const swiper = new Swiper('#site-slider.swiper', {
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            autoplay: {
               delay: 15000,
            },
            loop: true,
            speed: 1000
        });
    }
    
    function featuredCategories() {
        var target = $( '.row-thumb_categories' );
        var tab_categories = $( '.row-tab_categories' );
        var subcategories = $( '.row-subcategories' );

        if ( target.length ) {
            target.find( '.cat-item-cta' ).each( function() {

                $( this ).click( function( e ) {
                    e.preventDefault();

                    var cat_id = $( this ).data( 'cat' );

                    if ( cat_id ) {
                        target.addClass( 'hide-row' );
                        tab_categories.addClass( 'show-row' );
                        tab_categories.find( '.cat-item-tab[data-cat="' + cat_id +'"]' ).click();
                    }
                } );
            } );

            var submenu = subcategories.find( '.submenu-item' );

            tab_categories.find( '.cat-item-tab' ).each( function() {
                var this_menu = $( this );
                
                this_menu.click( function( e ) {
                    e.preventDefault();

                    var cat = this_menu.data( 'cat' );

                    tab_categories.find( '.cat-item-tab' ).removeClass( 'active' );
                    $( this ).addClass( 'active' );

                    submenu.removeClass( 'show-submenu' );

                    submenu.each( function() {
                        if ( $( this ).data( 'cat' ) == cat ){
                            $( this ).addClass( 'show-submenu' );
                        }
                    } );
                } );
            } );
        }
    }
    
    function featuredCategoriesMobileView() {
        let originalElement = null;
        let slickInitialized = false;
        const targetSelector = '.row-featured_categories';
    
        function initSlick() {
            const target = $(targetSelector);
            
            if (!slickInitialized) {
                if (originalElement === null) {
                    // Save a cloned DOM element (not HTML string!)
                    originalElement = target.clone();
                }
    
                target.slick({
                    arrows: false,
                    dots: true,
                    infinite: false,
                    speed: 300,
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    adaptiveHeight: true,
                    responsive: [
                        {
                            breakpoint: 991,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 3
                            }
                        }
                    ]
                });
    
                slickInitialized = true;
                target.removeClass('no-slick');
                
                featuredCategories();
            }
        }
    
        function destroySlick() {
            const target = $(targetSelector);
            if (slickInitialized) {
                target.slick('unslick');
                slickInitialized = false;
        
                setTimeout(() => {
                    const newElement = originalElement.clone();
                    target.remove();
        
                    // Insert in original position: before .row-tab_categories or .row-subcategories
                    if ($('.row-tab_categories').length) {
                        $('.row-tab_categories').first().before(newElement);
                    } else if ($('.row-subcategories').length) {
                        $('.row-subcategories').first().before(newElement);
                    } else {
                        // Fallback if nothing found
                        $('body').append(newElement);
                    }
        
                    // Re-bind logic
                    featuredCategories();
        
                    // Force reflow
                    newElement[0].offsetHeight;
                }, 50);
            }
        }
    
        function checkViewport() {
            const windowWidth = $(window).width();
            if (windowWidth <= 991) {
                initSlick();
            } else {
                destroySlick();
            }
        }
    
        // On load
        checkViewport();
    
        // On resize
        $(window).on('resize', function () {
            checkViewport();
        });
    }
    
    function featuredProductsMobileView() {
        var target = $( '.row_featured_products .row-products' );

        if ( target.length ) {
            target.addClass( 'featured_products-slider' );

            $( '.featured_products-slider' ).slick( {
                arrows: false,
                dots: true,
                infinite: false,
                speed: 300,
                slidesToShow: 6,
                slidesToScroll: 6,
                adaptiveHeight: true,
                responsive: [
                    {
                        breakpoint: 1100,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4,
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 415,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            } );
        }
    }
    
    function productRelatedSlider() {
        var target = $( '.related-wrap .related-app2' );

        if ( target.length ) {
            $( 'footer.site-footer' ).addClass( 'adapt-bg' );

            target.slick( {
                autoplay: true,
                autoplaySpeed: 6000,
                dots: false,
                infinite: true,
                speed: 600,
                arrows: false,
                vertical: false,
                verticalSwiping: false,
                slidesToShow: 5,
                slidesToScroll: 1,
                focusOnSelect: true,
                draggable: true,
                responsive: [
                    {
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 568,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });

            target.find('.slick-prev').html('');
            target.find('.slick-next').html('');
        }
    }
    
    function productSingleThumbnailSlider() {
        var target = $( '.product-slider-wrap .main-slider');
        var thumbSlider = $( '.product-slider-wrap .thumb-slider');

        if (target.length) {
            target.slick({
                dots: false,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                arrows: false,
                asNavFor: '.thumb-slider'
            });
        }

        if (thumbSlider.length) {
            thumbSlider.slick({
                dots: false,
                infinite: true,
                speed: 300,
                arrows: false,
                vertical: false,
                slidesToShow: 3,
                slidesToScroll: 1,
                asNavFor: '.main-slider',
                focusOnSelect: true,
                draggable: true,
            });
        }
    }
    
    function productSummarySection() {
        var productSummaryEl = $('.product-summary');
        
        if (productSummaryEl.length){
            productSummaryEl.each(function() {
                
                var target = $(this);
                var variations = target.find('table.variations');

                if (variations.length) {

                    var price_table = target.find('.monstaprice');
                    price_table.insertBefore(variations);

                    var qtyEl = target.find('.quantity');
                    var priceEl = target.find('.price');
                    var colorsEl = target.find('.tr-colors');
                    var sizesEl = target.find('.tr-sizes');


                    // show color options
                    if (colorsEl.length) {
                        variations.find('tbody').prepend(
                            colorsEl.clone().get(0).outerHTML
                        );
                    } else {
                        //show size options
                        if (sizesEl.length) {
                            variations.find('tbody').prepend(
                                sizesEl.clone().get(0).outerHTML
                            );
                        }
                    }

                    // move quantity to table
                    if (qtyEl.length) {

                        variations.find('tbody').append(
                            '<tr class="tr-qty">' +
                            '<td class="label"><label for="quantity">Quantity</label></td>' +
                            '<td class="value">' +
                            qtyEl.clone().get(0).outerHTML +
                            '</td>' +
                            '</tr>'
                        );

                    }

                    // move price to table
                    if (priceEl.length) {
                        var origPrice = priceEl.text().split('–');
                        variations.find('tbody').append(
                            '<tr class="tr-price-unit">' +
                            '<td class="label"><label>Unit Price</label></td>' +
                            '<td class="value"><span id="unit_price" class="unit_price">' +
                            priceEl.text() +
                            '</span></td>' +
                            '</tr>' +
                            '<tr class="tr-price">' +
                            '<td class="label"><label>Total Price</label></td>' +
                            '<td class="value">' +
                            priceEl.clone().get(0).outerHTML +
                            '</td>' +
                            '</tr>'
                        );
                    }

                    qtyEl.remove();
                    priceEl.remove();
                }
            });
        }
    }
    
    function productSummaryColorSizesOptions(){

        var productSummary = $('.product-summary');

        if (productSummary.length){

            productSummary.each(function() {
                
                var target = $(this).find('.summary-wrap');
                var sizeEl = $(this).find('select[name="attribute_pa_monstasize"]');
    
                // update size option when select on colors
                var colorsEl_alt = target.find('.tr-colors');
                var sizeEl_alt = target.find('.tr-sizes');
                var option_box = colorsEl_alt.find('.option-item');
                var size_box = sizeEl_alt.find('.size-item');
    
                // color options
                option_box.each(function() {
                    $(this).click(function() {
                        
                        option_box.removeClass('active');
                        $(this).addClass('active');
    
                        // set selected size
                        var varVal = $(this).data('variant');
                        if (varVal){
                            sizeEl
                                .val(varVal)
                                .trigger('change');
                        }
    
    
                    });
                });
    
                // size options
                size_box.each(function() {
                    $(this).click(function() {
                        
                        size_box.removeClass('active');
                        $(this).addClass('active');
    
                        // set selected size
                        var varVal = $(this).data('variant');
                        if (varVal){
                            sizeEl
                                .val(varVal)
                                .trigger('change');
                        }
    
    
                    });
                });
    
                // update color when select on size
                sizeEl.change(function() {
                    setColorOption(sizeEl, option_box);
                    setSizeOption(sizeEl, size_box);
                });


                setDefaultVariant(sizeEl);

                setColorOption(sizeEl, option_box);

                setSizeOption(sizeEl, size_box);

            });
            
        }

        function setColorOption(sizeEl, option_box) {

            var sizeVal = sizeEl.val();

            if (option_box.length){

                option_box.removeClass('active');
                option_box.each(function () {
                    var varVal = $(this).data('variant');

                    if (varVal == sizeVal) {
                        
                        imageEl = $(this).data('image-url');
                        
                        setProductSliderImage(imageEl);

                        $(this).addClass('active');
                    }
                });

            }

        }

        function setSizeOption(sizeEl, size_box) {

            var sizeVal = sizeEl.val();

            if (size_box.length){

                size_box.removeClass('active');
                size_box.each(function () {
                    var varVal = $(this).data('variant');
                    if (varVal == sizeVal) {

                        imageEl = $(this).data('image-url');
                        setProductSliderImage(imageEl);

                        $(this).addClass('active');
                    }
                });

            }
        }

        function setDefaultVariant(sizeEl) {

            var sizeVal_default = '';

            if (sizeEl.length){

                var s = 0;
                var the_default_one = false;
                var the_default = false;

                sizeEl.find('option').each(function() {

                    var opt_val = $(this).attr('value');

                    if ( opt_val ){
                        
                        s++;

                        // get the first val
                        if (s == 1){
                            the_default_one = opt_val;
                        }

                        // get the gold if there is one
                        if(opt_val.toUpperCase().includes('G')){
                            the_default = opt_val;
                        }

                    }
                });

                // set default one if no gold found
                the_default = !the_default ? the_default_one : the_default;

                // set the default value now
                sizeEl.val( the_default );
                sizeVal_default = the_default;

                // always set to first option

                $('.reset_variations').click(function() {
                    setTimeout(function(){
                        sizeEl
                            .val(sizeVal_default)
                            .trigger('change');
                    }, 500);
                });

            }
        }

        function setProductSliderImage(imageEl) {
            var singleProduct = $('.single-product');
            var modalQuickView = '.modal-quickview';
            // var sliderWrap = $(".product-slider-wrap");

            if(singleProduct.length && imageEl) {

                singleProduct.find('.main-item.slick-slide img')
                    .attr('data-src', imageEl)
                    .attr('src', imageEl)
                    .attr('data-zoom', imageEl);
                    
                singleProduct.find('.slick-slide .main-item img')
                    .attr('data-src', imageEl)
                    .attr('src', imageEl)
                    .attr('data-zoom', imageEl);

                singleProduct.find('.thumb-item.slick-slide img')
                    .attr('data-src', imageEl)
                    .attr('src', imageEl)
                    .attr('data-zoom', imageEl);
                    
                singleProduct.find('.slick-slide .thumb-item img')
                    .attr('data-src', imageEl)
                    .attr('src', imageEl)
                    .attr('data-zoom', imageEl);
            }  

            if($(modalQuickView).length) {
                modalQuickViews = $(modalQuickView + '.show');

                modalQuickViews.find('.main-item.slick-slide img')
                    .attr('data-src', imageEl)
                    .attr('src', imageEl)
                    .attr('data-zoom', imageEl);
                    
                modalQuickViews.find('.slick-slide .main-item img')
                    .attr('data-src', imageEl)
                    .attr('src', imageEl)
                    .attr('data-zoom', imageEl);

                modalQuickViews.find('.thumb-item.slick-slide img')
                    .attr('data-src', imageEl)
                    .attr('src', imageEl)
                    .attr('data-zoom', imageEl);
                    
                modalQuickViews.find('.slick-slide .thumb-item img')
                    .attr('data-src', imageEl)
                    .attr('src', imageEl)
                    .attr('data-zoom', imageEl);
            }


            // update none selected modal accessory button
            var varationBtn = $(".single-product .mosta_attribute_pa_variation");
            
            if( varationBtn.length ) {
                varationBtn.each(function(){
                    selectName = $(this).attr('data-select-name');

                    if( $(this).is(":hidden")  ) {
                        // remove select name attribute
                        $(this).find('.value select').removeAttr('name');
                    }else {
                        // re-assign select name attribute
                        $(this).find('.value select').attr('name', selectName);
                    }
                });
            }

        }
        
    }
    
    function productSummaryColorOrderSetGoldFirst() {
        var productSummary = $('.product-summary');

        if (productSummary.length){
            productSummary.each(function() {
                
                var sizeEl = $(this).find('select[name="attribute_pa_monstasize"]');
                var colorsEl = $(this).find('.tr-colors .product-variant-items');
                var gold_opt = false;
                var gold_opt_alt = false;

                if (sizeEl.length){
                    // get gold option
                    sizeEl.find('option').each(function() {
                        var size_val = $(this).attr('value');
                        if(size_val.toUpperCase().includes('G')){
                            gold_opt = $(this);
                        }
                    });

                    // set gold option as first
                    if(gold_opt){
                        sizeEl.find('option:eq(0)').after(gold_opt);
                    }
                }

                // update color boxes
                if (colorsEl.length){
                    // get gold option
                    colorsEl.find('.option-item').each(function() {
                        var color_val = $(this).data('variant');
                        if(color_val.toUpperCase().includes('G')){
                            gold_opt_alt = $(this).detach();
                        }
                    });

                    // set gold option as first
                    if(gold_opt_alt){
                        colorsEl.prepend(gold_opt_alt);
                    }
                }
            });
        }
    }
    
    function productSummaryColorOrderSetGoldFirstNew() {
        var productSummary = $('.product .product-summary');
    
        if (productSummary.length) {
            productSummary.each(function() {
                var sizeEl = $(this).find('select[name="attribute_pa_monstasize"]');
                var colorsEl = $(this).find('.tr-colors .product-variant-items-new');
    
                if (sizeEl.length) {
                    var goldOption = null;
    
                    // Get gold option
                    sizeEl.find('option').each(function() {
                        var size_val = $(this).attr('value');
                        if (size_val.toUpperCase().includes('G')) {
                            goldOption = $(this);
                        }
                    });
    
                    // Set gold option as first
                    if (goldOption) {
                        sizeEl.find('option:eq(0)').after(goldOption);
                    }
                }
    
                if (colorsEl.length) {
                    var goldItems = [];
                    var silverItems = [];
                    var bronzeItems = [];
                    var otherItems = [];
    
                    // Collect color items
                    colorsEl.find('.option-item').each(function() {
                        var color_val = $(this).data('variant');
                        var colorItem = $(this).detach();
    
                        // Push color item to the appropriate array
                        if (color_val.toUpperCase().includes('G')) {
                            goldItems.push(colorItem);
                        } else if (color_val.toUpperCase().includes('S')) {
                            silverItems.push(colorItem);
                        } else if (color_val.toUpperCase().includes('B')) {
                            bronzeItems.push(colorItem);
                        } else {
                            otherItems.push(colorItem);
                        }
                    });
    
                    // Append all items in the desired order: G, S, B, Others
                    colorsEl.empty().append(goldItems).append(silverItems).append(bronzeItems).append(otherItems);
                }
            });
        }
    }
    
    function productSummaryColorOrderOptions() {
        var productSummary = $('.product-summary');

        if (productSummary.length){
            productSummary.each(function() {
                var target = $(this).find('.summary-wrap');
                var sizeEl = $(this).find('select[name="attribute_pa_monstasize"]');
                var colorBox = target.find('.variant-options .option-box');
                var product_variations =  $(this).find('form.variations_form').data('product_variations');

                if (target.length && sizeEl.length){
        
                    var color_list = '';
                    sizeEl.find('option').each(function() {
                        if($(this).val()){
                            var attr_val = $(this).val();
                            colorBox.each(function() {
                                var color_val = $(this).data('variant');
                                if (color_val == attr_val){
                                    
                                    // assigned data image url from product_variations
                                    for (var v = 0; v < product_variations.length; v++) {

                                        var variation = product_variations[v];
                                        var sizeattr = variation.attributes.attribute_pa_monstasize;

                                        if (color_val == sizeattr) {
                                            var imageattr = variation.image.url;
                                            $(this).attr('data-image-url', imageattr);
                                        }
                                    }
                                    color_list += $(this).clone().get(0).outerHTML;
                                }
                            });
                        }
                    });
        
                    if (color_list){
                        target.find('.colourMedals-product').html(color_list);

                        // rearrange color variant BGS - GSB
                        optBronze = target.find('.variations .colourMedals-product .bronze');
                        target.find('.variations .colourMedals-product').append(optBronze);

                    }

                    setTimeout(function () {
                        var colorOpt = $('.product-summary .summary-wrap').find('.value .product-variant-items .option-item.active');
                        /** 
                         * Color swatches force click event
                         * On products with no default variant value
                         * to display variant modal button
                        */
                        if(colorOpt.length) {
                            colorOpt.trigger('click');
                        }
                    }, 200);
                }
            });
        }
    }
    
    function productSummaryPrice() {
        var target = $('.single-product .summary-wrap');
        var sizeEl = $('#pa_monstasize');
        var qtyEl = target.find('.qty');

        if (target.length && sizeEl.length){

            sizeEl.change(function() {
                updateBasePrice();
            });
            
            qtyEl.change(function() {
                updateBasePrice();
            });

            updateBasePrice();
        }
        
        function updateBasePrice() {
            setTimeout(function () {
                var currency =  "$";
                var quantity = parseFloat(qtyEl.val());
                var totalAccessoriesPrice = parseFloat( updateBasePriceNew() );
                var deductAdditional = totalAccessoriesPrice * quantity;
                var origPrice = parseFloat($("#unit_price").attr('orig-price'));
                origPrice = origPrice + totalAccessoriesPrice;
                origPrice = origPrice.toFixed(2);
                /**
                 * base for computation
                 * actual formula 
                 * total = (unit_price * quantity) + (totalAccessories * quantity)
                 * 
                 */

                var total = target.find('.tr-price .monsta_price_value .amount').text();
                    /**
                     * total_orig = total
                     */
                    total_orig = parseFloat(total.replace('$', ''));
                    total = parseFloat(total.replace('$', ''));
                    total = total - deductAdditional;

                var unit_price = (total / quantity) + totalAccessoriesPrice;
                    unit_price = unit_price.toFixed(2);
                   
                    // update Unite Price
                    $('#unit_price').html('$' + unit_price).attr('orig-price', unit_price - totalAccessoriesPrice);
                    // $('#unit_price').html('$' + unit_price).attr('orig-price', origPrice);

                    total = total + totalAccessoriesPrice;
                    total = (quantity > 1) ? total_orig : total;
                    
                    // update Total Price
                    $(".variations").find('.tr-price .price .woocommerce-Price-amount:first-child').html(
                        '<span class="woocommerce-Price-currencySymbol">' +
                        currency +
                        "</span>" +
                        parseFloat(total).toFixed(2)
                    );
            }, 100);

            updateBasePriceNew();
        }

        function updateBasePriceNew() {
            var unitprice = ".variations";
    
            if (unitprice.length){
                var displayPrice = parseFloat(0);
                
                //process attribute pricing - additional
                $(".single_variation_wrap > div:visible").each(function (i, e) {
                    var strs = $(this).attr('class');
                    
                    if(strs != undefined &&  strs.toLowerCase().indexOf("monsta_attribute_pa_") >= 0) {
                        var defaultPriceCC1 = parseFloat(
                            $(e).find("select option:selected").attr("default-price-cc1")
                        );
                        var defaultPriceCC2 = parseFloat(
                            $(e).find("select option:selected").attr("default-price-cc2")
                        );
                        var additional = parseFloat(
                            $(e).find("select option:selected").attr("price-attr")
                        );
            
                        if (!additional || isNaN(additional)) {
                            additional = parseFloat(
                                $(e).find("select option:selected").attr("price-attr")
                            );
                        }

                        if (!isNaN(defaultPriceCC1)) {
                            displayPrice = displayPrice - defaultPriceCC1;
                        } else if (!isNaN(defaultPriceCC2)) {
                            displayPrice = displayPrice - defaultPriceCC2;
                        } else if (!isNaN(additional) && additional) {
                            displayPrice = displayPrice + additional;
                        }
                    }
                });
                
                $('.reset_variations').css('visibility','visible');
                return displayPrice;
            }
        }
    }
    
    function productQuickViewAccesoriesOptions() {
        var target = $('.modal-quickview');

        target.each(function() {
            $(this).on('shown.bs.modal', function () {
                var quickviewModal = $(this);
                var monstasizeSelect = quickviewModal.find('select[name="attribute_pa_monstasize"]');
                var product_variations = quickviewModal.find('form.variations_form').data('product_variations');
                var product_accessories = quickviewModal.find('.single_variation_wrap div');
                var product_attrs = quickviewModal.data('attributes');
                
                quickviewModal.find('.single_variation_wrap .variations_button').before('<div class="acc-slider-wrap"></div>');

                // set the selected variant accesories
                monstasizeSelect.change(function() {
                    showVariantAccesories(monstasizeSelect, product_variations, product_accessories, product_attrs);
                });

                // set the default accesories
                showVariantAccesories(monstasizeSelect, product_variations, product_accessories, product_attrs);
            });

            $(this).on('hidden.bs.modal', function () {
                var quickviewModal = $(this);
                quickviewModal.find('.acc-slider-wrap').remove();
            });
        })

        function showVariantAccesories(monstasizeSelect, product_variations, product_accessories, product_attrs) {
            var selectedSize = monstasizeSelect.find('option:selected').val();

            // clear selected accessories
            product_accessories.find('.selected-centre').remove();
            product_accessories.find('select').val('');

            for (var v = 0; v < product_variations.length; v++) {
                var variation = product_variations[v];
                var sizeattr = variation.attributes.attribute_pa_monstasize;
                
                if (selectedSize == sizeattr) {
                    var the_variation_id = variation.variation_id;

                    product_accessories.each(function () {
                        
                        var attr_item = $(this);
                        var attr_id = attr_item.attr('id');
                        
                        if (typeof attr_id != 'undefined') {
                            if (attr_id.includes(the_variation_id + '_')) {
                                var accSelect = attr_item.find('select');
                                var accButton = attr_item.find('.centre-button');

                                if (attr_item.find('.btn-acc').length == 0) {
                                    accSelect.after('<a href="#" class="btn btn-acc">' + accButton.html()+' <span class="acc-selected"></span></a>');
                                }

                                var accButton_new = attr_item.find('.btn-acc');
                                
                                // show dropdown
                                accSelect.removeAttr('disabled');
                                attr_item.show();
                                accSelect.find('option[value=""]').html(accButton.html());
                                accSelect.addClass('show');
                                
                                // add selected element
                                accSelect.change(function() {
                                    var accSelectval = $(this).val();
                                    attr_item.find('.selected-centre').remove();
                                    if (accSelectval){
                                        var selectedAcc = '<div data-selected="' + accSelectval + '" class="selected-centre"></div>';
                                        accSelect.after(selectedAcc);
                                    }
                                });

                                accessoriesItems(attr_item, accSelect, accButton_new, product_attrs);
                                accessoriesItemsSelect(attr_id, true);
                            } else {
                                $(this).hide();
                                accessoriesItemsSelect(attr_id, false);
                            }
                        }
                    });
                }
            }
        }

        function accessoriesItems(attr_item, accSelect, accButton_new, product_attrs) {
            var the_modal = attr_item.closest('.modal');

            accButton_new.click(function (e) {
                e.preventDefault();

                var accList = '<div class="acc-slider">';

                accSelect.find('option').each(function () {
                    var accID = $(this).attr('value');
                    var optSelected = accSelect.val() == accID ? 'active' : '';
                    if (accID && $(accList).find('img[data-attrval="' + accID + '"]').length == 0) {
                        var product_attr = product_attrs[accID];
                        accList += '<div class="acc-item">';
                        accList += '<div class="acc-item-inner">';
                        accList += '<img class="' + optSelected+'" data-attrval="' + accID + '" src="' + product_attr.components_image[0] + '">';
                        accList += '</div>';
                        accList += '</div>';
                    }
                });

                accList += '</div>';

                // apply slider
                the_modal.find('.single_variation_wrap .acc-slider-wrap').html(accList);
                the_modal.find('.single_variation_wrap .acc-slider').slick({
                    dots: true,
                    infinite: true,
                    speed: 300,
                    arrows: false,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    focusOnSelect: true,
                });

                // update selected item
                the_modal.find('.acc-slider img').click(function() {
                    var imgAccID = $(this).data('attrval');
                    accSelect.val(imgAccID);

                    the_modal.find('.acc-slider img').removeClass('active')
                    $(this).addClass('active');

                    accButton_new.find('.acc-selected').html('<strong>Selected:</strong> ' + imgAccID);
                });
            });
        }

        function accessoriesItemsSelect( elemId, hiddenElem = false ) {
            if( elemId !== '') {
                var elem = $('#' + elemId);
                var selectName = elem.find('.value select').attr('name');
                elem.addClass('mosta_attribute_pa_variation').attr('data-select-name', selectName);
                
                $('.mosta_attribute_pa_variation').find('.value select').removeAttr('name');
                $('.acc-slider-wrap').html('');
                setTimeout(function () {
                    if( hiddenElem === true ) {
                        var selectName = elem.attr('data-select-name');
                        // console.log(elemId +"  ====== "+ selectName)
                        elem.find('.value select').attr('name', selectName);
                    };
                }, 900);
            }
        }
    }
    
    function productSummarySizeTable() {
        var target = $('.product-summary');
        
        if (target.length){
            target.each(function() {
                var productSummaryEl = $(this);
                var sizeEl = productSummaryEl.find('select[name="attribute_pa_monstasize"]');
                
                sizeTable(productSummaryEl, sizeEl);
    
                sizeEl.change(function() {
                    sizeTable(productSummaryEl, sizeEl);
                });

                var monstaprice = productSummaryEl.find('table.monstaprice');
                if (monstaprice.length) {
                    labelSize = monstaprice.find('tr.tr-show')

                    labelSize.each(function(e){
                        th = $(this).find('th');
                        if(th.length && e == 1)  {
                            th.eq(0).text('Quantity:');
                        }
                    });
                }
            });
        }

        // show the price table based on selected size
        function sizeTable(productSummaryEl, sizeEl) {
            productSummaryEl.find('tr').removeClass('tr-show');
            productSummaryEl.find('tr').each(function () {
                var this_tr = $(this);
                var td_label = this_tr.find('td:first-child').text();
                var sizeElVal = sizeEl.find('option:selected').text();
              
                if (td_label.toLowerCase() == sizeElVal.toLowerCase()) {
                    this_tr.addClass('tr-show');
                    productSummaryEl.find('tr:nth-child(1)').addClass('tr-show');
                    productSummaryEl.find('tr:nth-child(2)').addClass('tr-show');
                }
            });
        }
    }
    
    function productSummaryButtons() {
        var target = $('.product-summary');
        
        if (target.length){
            var minicart = $('#minicart');

            target.each(function() {
                var this_productSummary = $(this);
                var this_addtoCart = $(this).find('.woocommerce-variation-add-to-cart');

                // append checkout button in add to cart button
                this_addtoCart.append('<button type="button" class="button alt checkout-btn"><i class="fas fa-cart-plus"></i> & Checkout</button>');
                this_addtoCart.wrapInner('<div class="row"></div>');
                this_addtoCart.find('button').wrap('<div class="col"></div>');

                // add action to checkout button
                this_productSummary.find('.checkout-btn').click(function() {
                    var form = this_productSummary.find('form.cart')
                    var action = form.attr('action')+'?redirect=checkout';
                    form.attr('action', action);
                    minicart.addClass('checkout-redirect');
                    this_productSummary.find('button[type="submit"]').click();
                });
            });
        }
    }
    
    function productSliderZoom() {
        var target = $('.single-product .drift-img');

        if (target.length) {
            var slides = document.getElementsByClassName("drift-img");

            for (var i = 0; i < slides.length; i++) {
                new Drift(slides.item(i), {
                    paneContainer: document.querySelector(".summary-wrap"),
                    inlinePane: 900,
                    inlineOffsetY: -85,
                    containInline: true,
                    hoverBoundingBox: true,
                });
            }
        }
    }
    
    function productsInfiniteResult() {
        var target = $('.infinite-wrap');
        if (!target.length) return;

        // --- Dual-mode setup ---
        // Mode A: mohawkInfinite is available (proper AJAX endpoint).
        // Mode B: fallback — scrape pagination links from the DOM.
        var useAjax = (typeof mohawkInfinite !== 'undefined');

        var currentPage, maxPages;

        if (useAjax) {
            currentPage = parseInt(mohawkInfinite.current_page, 10) || 1;
            maxPages    = parseInt(mohawkInfinite.max_pages, 10) || 1;
        } else {
            // Fallback: determine current page from pagination or default to 1.
            currentPage = 1;
            // Estimate max pages from pagination links in the DOM.
            var paginationLinks = $('.pagination-wrap, .custom-pagination, .pagination-c');
            var lastPageNum = 1;
            paginationLinks.find('a.page-numbers, span.page-numbers').each(function () {
                var num = parseInt($(this).text(), 10);
                if (!isNaN(num) && num > lastPageNum) lastPageNum = num;
            });
            maxPages = lastPageNum;
        }

        var isLoading      = false;
        var prefetchedData = null;
        var isDone         = false;

        if (currentPage >= maxPages) {
            target.addClass('infinite-end');
            return;
        }

        var loaderEl       = target.find('.infinite-loader');
        var loadingSpinner = target.find('.loading-container');

        // Build the URL for a given page in fallback mode.
        function buildFallbackUrl(page) {
            var url;

            // Try to find the "next" pagination link first.
            var nextLink = $('.pagination-wrap a.next, .custom-pagination a.next, .pagination-c a.next');

            if (nextLink.length) {
                url = nextLink.attr('href');
                // Replace the page number in the URL with the target page.
                url = url.replace(/\/page\/\d+\//, '/page/' + page + '/');
                url = url.replace(/[?&]paged=\d+/, function(m) {
                    return m.charAt(0) + 'paged=' + page;
                });
            } else {
                // Construct from current URL — strip any existing page number,
                // then append /page/N/ before the query string.
                var path = window.location.pathname.replace(/\/page\/\d+\/?/, '/');
                if (path.charAt(path.length - 1) !== '/') path += '/';
                url = path + 'page/' + page + '/' + window.location.search;
            }

            // Append infinite_result flag.
            var sep = url.indexOf('?') !== -1 ? '&' : '?';
            return url + sep + 'infinite_result=1';
        }

        // Fetch a page of products.
        function fetchPage(page) {
            if (useAjax) {
                return $.ajax({
                    url: mohawkInfinite.ajaxurl,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        action:   'mohawk_infinite_scroll',
                        nonce:    mohawkInfinite.nonce,
                        paged:    page,
                        per_page: mohawkInfinite.per_page,
                        orderby:  mohawkInfinite.orderby,
                        category: mohawkInfinite.category
                    }
                });
            } else {
                // Fallback: fetch the page URL, parse product HTML from response.
                var url = buildFallbackUrl(page);
                var deferred = $.Deferred();
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'html'
                }).done(function (html) {
                    var tempContainer = $('<div>').html(html);
                    var products = tempContainer.find('.row-products').children();
                    if (products.length) {
                        // Try to get max pages from the response pagination.
                        var respMaxPages = maxPages;
                        tempContainer.find('a.page-numbers, span.page-numbers').each(function () {
                            var num = parseInt($(this).text(), 10);
                            if (!isNaN(num) && num > respMaxPages) respMaxPages = num;
                        });
                        deferred.resolve({
                            success: true,
                            data: {
                                html: products,
                                max_pages: respMaxPages,
                                page: page
                            }
                        });
                    } else {
                        deferred.resolve({ success: false });
                    }
                }).fail(function () {
                    deferred.reject();
                });
                return deferred.promise();
            }
        }

        function prefetchNextPage() {
            var nextPage = currentPage + 1;
            if (nextPage > maxPages || prefetchedData) return;
            fetchPage(nextPage).done(function (response) {
                var data = useAjax ? response.data : (response.success ? response.data : null);
                if (data && data.html) {
                    prefetchedData = data;
                }
            });
        }

        function appendProducts(data) {
            if (data && data.html) {
                if (useAjax) {
                    target.find('.row-products').append($(data.html));
                } else {
                    // Fallback: data.html is already jQuery elements.
                    target.find('.row-products').append(data.html);
                }
                maxPages = parseInt(data.max_pages, 10) || maxPages;
            }
        }

        function markDone() {
            isDone = true;
            target.addClass('infinite-end');
            if (observer) observer.disconnect();
            $(window).off('scroll.infiniteScroll');
        }

        function loadNextPage() {
            if (isLoading || isDone || currentPage >= maxPages) return;

            isLoading = true;
            target.addClass('infinite-process');
            loadingSpinner.removeClass('d-none hide');
            currentPage++;

            if (prefetchedData) {
                var cached = prefetchedData;
                prefetchedData = null;
                appendProducts(cached);
                finishLoad();
                return;
            }

            fetchPage(currentPage)
                .done(function (response) {
                    if (response.success) appendProducts(response.data);
                })
                .fail(function () {
                    currentPage--;
                })
                .always(function () {
                    finishLoad();
                });
        }

        function finishLoad() {
            isLoading = false;
            target.removeClass('infinite-process');
            loadingSpinner.addClass('d-none hide');

            if (currentPage >= maxPages) {
                markDone();
            } else {
                prefetchNextPage();
            }
        }

        // Check if loader is close enough to viewport to trigger loading.
        // Uses getBoundingClientRect for reliability — works regardless
        // of opacity, transforms, or layout shifts.
        function isLoaderNearViewport() {
            if (!loaderEl.length) return false;
            var rect = loaderEl[0].getBoundingClientRect();
            // Trigger 2000px before the loader comes into view.
            return rect.top <= window.innerHeight + 2000;
        }

        // --- Triple detection: IntersectionObserver + scroll + interval ---
        // All run in parallel to guarantee the trigger fires early.

        var observer = null;

        // 1) IntersectionObserver with very large rootMargin.
        if ('IntersectionObserver' in window && loaderEl.length) {
            observer = new IntersectionObserver(function (entries) {
                if (entries[0].isIntersecting && !isDone) {
                    loadNextPage();
                }
            }, {
                rootMargin: '0px 0px 2500px 0px',
                threshold: 0
            });
            observer.observe(loaderEl[0]);
        }

        // 2) Scroll listener as backup (throttled to 100ms).
        var scrollTimer = null;
        $(window).on('scroll.infiniteScroll', function () {
            if (scrollTimer || isDone) return;
            scrollTimer = setTimeout(function () {
                scrollTimer = null;
                if (isLoaderNearViewport()) loadNextPage();
            }, 100);
        });

        // 3) Polling interval — catches edge cases where scroll events
        //    don't fire (e.g. container revealed via opacity transition,
        //    resize, or programmatic scroll).
        var pollInterval = setInterval(function () {
            if (isDone) { clearInterval(pollInterval); return; }
            if (isLoaderNearViewport()) loadNextPage();
        }, 500);

        // 4) Prefetch page 2 immediately on init.
        prefetchNextPage();

        // 5) Run initial checks at multiple delays to catch various
        //    DOM readiness states (especially the opacity:0 sidebar reveal).
        [100, 300, 600, 1000].forEach(function (delay) {
            setTimeout(function () {
                if (isLoaderNearViewport() && !isDone) loadNextPage();
            }, delay);
        });
    }
    
    function productModalAttributeDisplay() {
        var target = $('.single-product .single_variation_wrap');

        if(target.length){
            var cnt = 0;
            var attrSelect = target.find('select');
            
            var attrInt = setInterval(function(){
                if(attrSelect.length){
                    clearInterval(attrInt);

                    attrSelect.each(function(){
                        var attrKey = $(this).attr('name');

                        if(attrKey){
                            attrKey = attrKey.replace('attribute_pa_', '');
                            cnt++;
                            
                            var global_var = 'global_'+attrKey;
                            var modal_title = $('.monsta_attribute_pa_'+attrKey).find('label').first().text();
                            modal_title = modal_title.replace('1 Inch', '');
                            modal_title = modal_title.replace('2 Inch', '');
                            modal_title = modal_title.replace('for your Medals', '');
    
                            variantRenderModal(attrKey, attrKey+'_modal', modal_title, global_var);
                        }
                    });
                }

            }, 250);
        }
    }
    
    function enableCartAndCheckout() {
        $('.woocommerce-variation-add-to-cart').removeClass('btn-disabled');
    }

    function disableCartAndCheckout() {
        var modalId = $('.single_variation_wrap .centre-button:contains("Ribbons"), ' +
                    '.single_variation_wrap .centre-button:contains("Ribbon"), ' +
                    '.single_variation_wrap .centre-button:contains("RIBBONS"), ' +
                    '.single_variation_wrap .centre-button:contains("RIBBON"), ' +
                    '.single_variation_wrap .centre-button:contains("ribbons"), ' +
                    '.single_variation_wrap .centre-button:contains("ribbon")').attr('data-modal'); // retrieve data-modal attribute value.
                    
        var trimmedModalId;
        
        if (modalId) {
            trimmedModalId = modalId.replace('_modal', '');
        }
        
        if ($('.single_variation_wrap .monsta_attribute_pa_' + trimmedModalId).length) {
            $('.woocommerce-variation-add-to-cart').addClass('btn-disabled');
    
            var ribbonSelect = $('<span>', {'class': 'ribbon-select', text: 'Please select a ribbon to enable the buttons.'});
            $('.woocommerce-variation-add-to-cart').append(ribbonSelect);
        }
    }
    
    function setupRibbonEventListeners() {
        var modalId = $('.single_variation_wrap .centre-button:contains("Ribbons"), ' +
                    '.single_variation_wrap .centre-button:contains("Ribbon"), ' +
                    '.single_variation_wrap .centre-button:contains("RIBBONS"), ' +
                    '.single_variation_wrap .centre-button:contains("RIBBON"), ' +
                    '.single_variation_wrap .centre-button:contains("ribbons"), ' +
                    '.single_variation_wrap .centre-button:contains("ribbon")').attr('data-modal'); // retrieve data-modal attribute value.
                    
        $('.' + modalId + '.monsta_modal').on('click', '.centre-badge, .clear-field', function() {
            enableCartAndCheckout();
        });
    }
    
    function removeSelectedRibbon() {
        var removeSelect = $('<a class="clear-field">').attr('href', '#').text('Clear All');
        
        removeSelect.on('click', function(event) {
            event.preventDefault();
            
            var modalId = $('.single_variation_wrap .centre-button:contains("Ribbons"), ' +
                    '.single_variation_wrap .centre-button:contains("Ribbon"), ' +
                    '.single_variation_wrap .centre-button:contains("RIBBONS"), ' +
                    '.single_variation_wrap .centre-button:contains("RIBBON"), ' +
                    '.single_variation_wrap .centre-button:contains("ribbons"), ' +
                    '.single_variation_wrap .centre-button:contains("ribbon")').attr('data-modal'); // retrieve data-modal attribute value.
                    
            var trimmedModalId;
            
            if (modalId) {
                trimmedModalId = modalId.replace('_modal', '');
            }
            
            var selectedBadge = $('.' + modalId + '.monsta_modal .centre-badge.selected');
            selectedBadge.removeClass('selected');
            $('.' + modalId + '.monsta_modal .centre_modal-title span').text('Selected: NO RIBBON REQUIRED');
            $('.selected-centre.selected-centre-' + modalId).hide();
            $('select[name="attribute_pa_' + trimmedModalId + '"] option:selected').prop('selected', false);
            disableCartAndCheckout();
            
            $('#clear-all.centre-badge').addClass('active');
        });
        
        var modalId = $('.single_variation_wrap .centre-button:contains("Ribbons"), ' +
                    '.single_variation_wrap .centre-button:contains("Ribbon"), ' +
                    '.single_variation_wrap .centre-button:contains("RIBBONS"), ' +
                    '.single_variation_wrap .centre-button:contains("RIBBON"), ' +
                    '.single_variation_wrap .centre-button:contains("ribbons"), ' +
                    '.single_variation_wrap .centre-button:contains("ribbon")').attr('data-modal'); // retrieve data-modal attribute value.
                    
        $('.' + modalId + '.monsta_modal > .centre_modal-inner > .centre_modal-title').append(removeSelect);
    
        $('.single_variation_wrap').on('click', '[data-modal="' + modalId + '"]', function() {
            var modal = $('.' + modalId);
            var removeSelectCircle = $('<div>', {
                'class': 'centre-badge',
                'id': 'clear-all'
            }).append(
                $('<div>', { 'class': 'frame' }).append(
                    $('<i>', { 'class': 'fas fa-redo' })
                ),
                $('<span>').text('NO RIBBON REQUIRED')
            );
            
            removeSelectCircle.on('click', function(event) {
                event.preventDefault();
                
                var modalId = $('.single_variation_wrap .centre-button:contains("Ribbons"), ' +
                    '.single_variation_wrap .centre-button:contains("Ribbon"), ' +
                    '.single_variation_wrap .centre-button:contains("RIBBONS"), ' +
                    '.single_variation_wrap .centre-button:contains("RIBBON"), ' +
                    '.single_variation_wrap .centre-button:contains("ribbons"), ' +
                    '.single_variation_wrap .centre-button:contains("ribbon")').attr('data-modal'); // retrieve data-modal attribute value.
                    
                var trimmedModalId;
                
                if (modalId) {
                    trimmedModalId = modalId.replace('_modal', '');
                }
                
                var selectedBadge = $('.' + modalId + '.monsta_modal .centre-badge.selected');
                selectedBadge.removeClass('selected');
                $('.' + modalId + '.monsta_modal .centre_modal-title span').text('Selected: NO RIBBON REQUIRED');
                $('.selected-centre.selected-centre-' + modalId).remove();
                $('select[name="attribute_pa_' + trimmedModalId + '"] option:selected').prop('selected', false);
                removeSelectCircle.addClass('active');
                disableCartAndCheckout();
            });
            
            modal.find('.centre_modal-body').prepend(removeSelectCircle);
            
            $('.centre-badge').not(removeSelectCircle).on('click', function() {
                $('#clear-all').removeClass('active');
            });
        });
    }

    function wrapPaymentOptions() {
        $(window).on('load', function() {
            var paymentOptionSection = $('<div id="payment-option-section"></div>');
            var $paymentMethod = $('.woocommerce-checkout #payment-method');
            var $orderNotes = $('.woocommerce-checkout #order-notes');
            var $termsBlock = $('.woocommerce-checkout .wp-block-woocommerce-checkout-terms-block');
            var $actionsBlock = $('.woocommerce-checkout .wp-block-woocommerce-checkout-actions-block');
    
            paymentOptionSection.append($paymentMethod, $orderNotes, $termsBlock, $actionsBlock);
    
            $('.woocommerce-checkout .wc-block-components-sidebar .wp-block-woocommerce-checkout-order-summary-block').after(paymentOptionSection);
            // hide the original sections.
            $('.woocommerce-checkout .wc-block-components-main #payment-method, .woocommerce-checkout .wc-block-components-main #order-notes, .woocommerce-checkout .wc-block-components-main .wp-block-woocommerce-checkout-terms-block, .woocommerce-checkout .wc-block-components-main .wp-block-woocommerce-checkout-actions-block').hide();
        });
    }
    
    function woocommerceMiniCart() {
        var target = $('.cart-link');

        if (target.length) {
            var modalMiniCart = $('#minicart');

            // show minicart when click on header cart icon
            target.click(function (e) {
                e.preventDefault();
                modalMiniCart.modal('show');
            });

            // close minicart modal
            modalMiniCart.find('.close').click(function (e) {
                e.preventDefault();
                modalMiniCart.modal('hide');
            });
        }
    }
    
    function companyLabelNoteCheckout() {
        var target_cl = $('.woocommerce-billing-fields__field-wrapper');
        if(target_cl.length) {
            target_cl.find('#billing_company_field label').append('<small style="display: block; line-height: 1; color: red">if you are an existing customer, it\'s important that you fill this field</small>')
        }
    }
    
    function contactUsMap() {
        var target = $( '#map-canvas' );

        if ( target.length ){
            function initialize() {
                var myLatlng = new google.maps.LatLng( target.data( 'latitude' ), target.data( 'longitude' ) );
                var imagePath = target.data( 'marker' );
                var mapOptions = {
                    zoom: 11,
                    center: myLatlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }

                var map = new google.maps.Map( document.getElementById( 'map-canvas' ), mapOptions );

                //Callout Content
                var contentString = target.data('title');

                //Set window width + content
                var infowindow = new google.maps.InfoWindow({
                    content: contentString,
                    maxWidth: 500
                });

                //Add Marker
                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    icon: imagePath,
                    title: target.data('title')
                });

                // add popup
                google.maps.event.addListener(marker, 'click', function () {
                    infowindow.open(map, marker);
                });
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        }
    }
    
    function sidebarMiniCartAddToCart() {
        var modalMiniCart = $('#minicart');

        if (modalMiniCart.length) {
            var productSummary = $('.product-summary');
            var modalMiniCartMsgEl = modalMiniCart.find('.minicart-msg');
            
            // show minicart after adding
            productSummary.each(function () {
                var addToCartBtn = $(this).find('button[type="submit"]');
                var productTitleEl = $(this).find('.summary-cover h1');
                
                // show minicart if added
                addToCartBtn.click(function () {
                    var this_btn = $(this);
                    var addCartInt = setInterval(function () {

                        if (this_btn.hasClass('added')) {

                            // show notif with product title
                            modalMiniCartMsgEl.find('.msg-product-title').text(productTitleEl.text());
                            modalMiniCartMsgEl.removeClass('hide');
                            modalMiniCart.modal('show');
                            clearInterval(addCartInt);

                            // redirect to checkout if clicked on checkout btn
                            if (modalMiniCart.hasClass('checkout-redirect')) {
                                window.location.href = modalMiniCart.data('checkout');
                            }

                            // hide notif after 6sec
                            setTimeout(function () {
                                modalMiniCartMsgEl.addClass('hide');
                            }, 6000);
                        }
                    }, 500);
                });
            });
        }
    }
    
    function sortMedalProductSingleSelectOptions() {
        var $select = $('.product_cat-grouping-86103-20 #pa_monstasize'); // Only on Medals (product_cat-grouping-1464-20).
        var customOrder = ['G', 'S', 'B', 'BR'];
    
        var $options = $select.find('option').toArray();
    
        // Find the value for "Gold"
        var goldValue = '';
        $options.forEach(function(option) {
            var text = $(option).text().toUpperCase();
            if (text.includes('G')) {
                goldValue = $(option).val(); // Save value of the Gold option
            }
        });
    
        // Sort the options array based on the custom order
        $options.sort(function(a, b) {
            var aText = $(a).text().toUpperCase();
            var bText = $(b).text().toUpperCase();
    
            var aIndex = customOrder.findIndex(function(prefix) { return aText.includes(prefix); });
            var bIndex = customOrder.findIndex(function(prefix) { return bText.includes(prefix); });
    
            if (aIndex === -1) aIndex = customOrder.length;
            if (bIndex === -1) bIndex = customOrder.length;
    
            return aIndex - bIndex;
        });
    
        // Remove existing options and append sorted ones
        $select.empty().append($options);
    
        // Ensure the "Gold" option is selected
        if (goldValue) {
            // Using setTimeout to ensure the value is selected after options are appended
            setTimeout(function() {
                $select.val(goldValue).trigger('change'); // Trigger change to update any bindings
            }, 0);
        }
    }
    
    function disableBillingShippingAutoComplete() {
        // Rename billing and shipping company fields to prevent browser autofill
        $('#billing_company').attr('id', 'billing_company_clone');
        $('#shipping_company').attr('id', 'shipping_company_clone');
    
        // Restore real name on form submit to ensure WooCommerce processes them
        $('form.checkout').on('submit', function() {
            $('#billing_company').attr('id', 'billing_company');
            $('#shipping_company').attr('id', 'shipping_company');
        });
    }
    
    function woocommerceLegacyStyleOverrides() {
        $( ".woocommerce-cart-form__cart-item .product-remove a.remove" ).each( function () {
            var href = $( this ).attr( "href" );
            var ariaLabel = $( this ).attr( "aria-label" );
            var productId = $( this ).data( "product_id" );
            var productSku = $( this ).data( "product_sku" );
    
            var button = $( "<button>", {
                type: "button",
                text: "Remove Item",
                class: "remove-item-btn",
                "aria-label": ariaLabel,
                "data-product_id": productId,
                "data-product_sku": productSku,
                click: function () {
                    window.location.href = href;
                }
            } );
    
            $( this ).replaceWith( button );
        } );
    }
    
    function productZoomingEffects() {
        if (typeof PhotoSwipeLightbox === 'undefined') {
            console.error('PhotoSwipeLightbox is not loaded');
            return;
        }

        const lightbox = new PhotoSwipeLightbox({
            gallery: '#product-gallery',
            children: '.pswp-gallery__item',
           allowPanToNext: false,
           allowMouseDrag: true,
            wheelToZoom: true,
            //zoom: false,
            bgOpacity: 0.9,
            showHideAnimationType: 'zoom',
            pswpModule: PhotoSwipe,
        });
        
        lightbox.init();
    }
    
    $( document ).ready( function() {
        bannerSliderInit();
        featuredCategories();
        featuredCategoriesMobileView();
        featuredProductsMobileView();
        productRelatedSlider();
        productSingleThumbnailSlider();
        productSummarySection();
        productSummaryColorSizesOptions();
        productSummaryColorOrderSetGoldFirst();
        //productSummaryColorOrderSetGoldFirstNew();
        productSummaryColorOrderOptions();
        productSummaryPrice();
        productQuickViewAccesoriesOptions();
        productSummarySizeTable();
        productSummaryButtons();
        //productSliderZoom();
        productsInfiniteResult();
        productZoomingEffects();
        productModalAttributeDisplay();
        setTimeout(function() {
            removeSelectedRibbon();
            disableCartAndCheckout();
            setupRibbonEventListeners();
        }, 1000);
        wrapPaymentOptions();
        woocommerceMiniCart();
        companyLabelNoteCheckout();
        contactUsMap();
        sidebarMiniCartAddToCart();
        sortMedalProductSingleSelectOptions();
        disableBillingShippingAutoComplete();
        woocommerceLegacyStyleOverrides();
    });
} )( window.jQuery || window.$ );
