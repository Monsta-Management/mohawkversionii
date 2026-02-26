(function($) {
    /**
     * Utility : Update Base Price
     */
    function updateBasePrice() {
        var unitprice = ".cost_per_unit .price";

        if (unitprice.length){
            var displayPrice = parseFloat($(unitprice).attr('orig-price'));
            var currency = woocommerce_currency_symbol
                ? woocommerce_currency_symbol
                : "$";
    
            //process attribute pricing - additional
            $(".single_variation_wrap .monsta-attribute:visible select").each(function (i, e) {
                var defaultPriceCC1 = parseFloat(
                    $(e).find("option:selected").attr("default-price-cc1")
                );
                var defaultPriceCC2 = parseFloat(
                    $(e).find("option:selected").attr("default-price-cc2")
                );
                var additional = parseFloat(
                    $(e).find("option:selected").attr("price-attr")
                );
    
                if (!additional || isNaN(additional)) {
                    additional = parseFloat(
                        $(e).find("option:selected").attr("price-attr")
                    );
                }
    
                if (additional && !isNaN(additional)) {
                    if (!isNaN(defaultPriceCC1)) {
                        displayPrice = displayPrice - defaultPriceCC1;
                    } else if (!isNaN(defaultPriceCC2)) {
                        displayPrice = displayPrice - defaultPriceCC2;
                    }
                    displayPrice = displayPrice + additional;
                }
            });
    
            if (displayPrice && !isNaN(displayPrice)) {
                $(unitprice).html(
                    '<span class="woocommerce-Price-currencySymbol">' +
                    currency +
                    "</span>" +
                    parseFloat(displayPrice).toFixed(2)
                );
    
                //singleProductQuantity(); 
                $(unitprice).next().attr("content", parseFloat(displayPrice).toFixed(2));
                var curr_qty = $('.single-product .cost_quantity .quantity .amount p').text();
                var total_cost = parseFloat(curr_qty) * parseFloat(displayPrice);
                $('.cost_total .cost').text('$' + total_cost.toFixed(2));
            }
        }
    }

    function updateBasePriceNew_alt() {
        var unitprice = ".variations";

        if (unitprice.length){
             var displayPrice = $(unitprice).find('.unit_price').text().split('$');
             var unit_displayPrice = parseFloat($(unitprice).find('.unit_price').attr('orig-price'));
                console.log(unit_displayPrice + " ===  unit_displayPrice")
                displayPrice = ( displayPrice[0] != "" ) ? parseFloat(displayPrice[0]) : parseFloat(displayPrice[1]);
                
             var quantity = parseFloat($(unitprice).find('.quantity .qty').val());
                displayPrice = displayPrice * quantity;
            
            var currency = woocommerce_currency_symbol
                ? woocommerce_currency_symbol
                : "$";
    
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
                    
                    console.log(defaultPriceCC1 + " ===  defaultPriceCC1")
                    console.log(additional + " ===  additional")

                    if (!isNaN(defaultPriceCC1)) {
                        unit_displayPrice = unit_displayPrice - defaultPriceCC1;
                    } else if (!isNaN(defaultPriceCC2)) {
                        unit_displayPrice = unit_displayPrice - defaultPriceCC2;
                    } else if (!isNaN(additional) && additional) {
                        unit_displayPrice = unit_displayPrice + additional;
                    }
                    total_price = unit_displayPrice * quantity;
                    console.log(total_price + " ===  total_price")
                }
            });
    
            if (displayPrice && !isNaN(displayPrice)) {
                $(unitprice).find('.tr-price .price .woocommerce-Price-amount').html(
                    '<span class="woocommerce-Price-currencySymbol">' +
                    currency +
                    "</span>" +
                    parseFloat(total_price).toFixed(2)
                );
                
                //singleProductQuantity(); 
                $(unitprice).next().attr("content", parseFloat(displayPrice).toFixed(2));
                var curr_qty = $('.single-product .cost_quantity .quantity .amount p').text();
                var total_cost = parseFloat(curr_qty) * parseFloat(displayPrice);
                $('.cost_total .cost').text('$' + total_cost.toFixed(2));
            }
            
            if (unit_displayPrice && !isNaN(unit_displayPrice)) {
                $(unitprice).find('.unit_price').html(currency + parseFloat(unit_displayPrice).toFixed(2))
            }
            
            $('.reset_variations').css('visibility','visible');
        }
    }

    function updateBasePriceNew() {
        var unitprice = ".variations";

        if (unitprice.length){
             var displayPrice = $(unitprice).find('.unit_price').text().split('$');
             var unit_displayPrice = parseFloat($(unitprice).find('.unit_price').attr('orig-price'));
                
                displayPrice = ( displayPrice[0] != "" ) ? parseFloat(displayPrice[0]) : parseFloat(displayPrice[1]);
                
             var quantity = parseFloat($(unitprice).find('.quantity .qty').val());
                displayPrice = displayPrice * quantity;
            
            var currency = (woocommerce_currency_symbol != undefined )
                ? woocommerce_currency_symbol
                : "$";
    
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
                        unit_displayPrice = unit_displayPrice - defaultPriceCC1;
                    } else if (!isNaN(defaultPriceCC2)) {
                        unit_displayPrice = unit_displayPrice - defaultPriceCC2;
                    } else if (!isNaN(additional) && additional) {
                        unit_displayPrice = unit_displayPrice + additional;
                    }
                    total_price = unit_displayPrice * quantity;
                    // console.log(total_price + " ===  total_price")
                }
            });
    
            if (displayPrice && !isNaN(displayPrice)) {
                
                $(unitprice).find('.tr-price .price .woocommerce-Price-amount').html(
                    '<span class="woocommerce-Price-currencySymbol">' +
                    currency +
                    "</span>" +
                    parseFloat(total_price).toFixed(2)
                );
                
                //singleProductQuantity(); 
                $(unitprice).next().attr("content", parseFloat(displayPrice).toFixed(2));
                var curr_qty = $('.single-product .cost_quantity .quantity .amount p').text();
                var total_cost = parseFloat(curr_qty) * parseFloat(displayPrice);
                $('.cost_total .cost').text('$' + total_cost.toFixed(2));
            }
            
            if (unit_displayPrice && !isNaN(unit_displayPrice)) {
                $(unitprice).find('.unit_price').html(currency + parseFloat(unit_displayPrice).toFixed(2))
            }
            
            $(unitprice).find('.quantity .qty').trigger('change'); // trigger quantity change
            $('.reset_variations').css('visibility','visible');
        }
    }

    /**
     * Utility: Inject original price as a new property on a single page
     */
    function getOrigPrice() {
        var unitprice = ".cost_per_unit .price";
        if ($(unitprice).length) {
            var original_price = parseFloat($(unitprice).next().attr("content")).toFixed(2);
            if (isNaN(original_price)) {
                var selectedVariant = variationprice[$('#pa_monstasize').val()];

                $(unitprice).attr('orig-price', selectedVariant);
                $(unitprice).next().attr('content', selectedVariant);
            } else {
                $(unitprice).attr('orig-price', original_price);
            }
        }
    }

    /**
       * Centre
       */
    function variantRenderModal(monstacc, centre_modal, button_text, global_var) {
        monstacc = typeof monstacc !== 'undefined' ? monstacc : 'monstacc1';
        centre_modal = typeof centre_modal !== 'undefined' ? centre_modal : 'centre_modal';
        button_text = typeof button_text !== 'undefined' ? button_text : 'Centre';
        global_var = typeof global_var !== 'undefined' ? global_var : 'global_centres';

        if ($(".monsta_pa_" + monstacc).length) {
            $(".monsta_pa_" + monstacc).addClass("centre-hidden");
            var centreDropdown = $(".monsta_pa_" + monstacc);
            var centreDropdownWrapper = $(".single-product .monsta_attribute_pa_" + monstacc);

            var centreModal = $("." + centre_modal);
            centreDropdown.each(function (index, element) {
                var centreButton = $(
                    '<button class="centre-button" data-modal="' + centre_modal + '">Select ' + button_text + '</button>'
                );
                var centresHTML = "";
                if (element) {
                    centreButton.insertAfter($(element));
                    centreButton.on("click", function (e) {
                        var refButton = this;
                        e.preventDefault();

                        //get selected centre
                        var modal = $(this).data('modal');
                        var selectedCentre = $(".selected-centre-" + centre_modal)
                            ? $(".selected-centre-" + centre_modal).attr("data-selected")
                            : null;

                        centreModal.find(".centre_modal-body").html("");
                        if (centreModal) {
                            centreModal.fadeIn();
                            //initialize centres
                            var options = $(".monsta_attribute_pa_" + monstacc + ":visible select.monsta_pa_" + monstacc + " option.enabled");
                            options.each(function (i, e) {
                                if ($(e).val()) {
                                    var woocommerce_currency_symbol = $('.product-summary-wrap').data('currency');
                                    var comp_price = woocommerce_currency_symbol + $(e).attr('price-attr');
                                    var comp_label = $(e).text() + ' ('+comp_price+')';

                                    var global = eval(global_var);

                                    var comp_image = global[$(e).val()]["components_image"],
                                        comp_code = global[$(e).val()]["components_code"];

                                    var selectedCentreClass = "";
                                    if ($(e).val() === selectedCentre) {
                                        selectedCentreClass = "selected";
                                    }
                                    var centreBadge = $(
                                        '<div class="centre-badge ' +
                                        selectedCentreClass +
                                        '" data-slug="' +
                                        $(e).val() +
                                        '" data-label="' +
                                        comp_label +
                                        '"><div class="img-wrap"><img width="150" height="150" src="' +
                                        comp_image +
                                        '" alt="Center" /></div></div>'
                                    );
                                    centreBadge.on("click", function (e) {
                                        e.preventDefault();
                                        $(this)
                                            .closest(".centre_modal-body")
                                            .find(".centre-badge")
                                            .removeClass("selected");
                                        $(this).addClass("selected");
                                        $(this)
                                            .closest("." + centre_modal)
                                            .attr("data-selected", $(this).attr("data-slug"));
                                        $(this)
                                            .closest("." + centre_modal)
                                            .find(".centre_modal-title span")
                                            .text("Selected: " + $(this).attr("data-slug"));
                                        $(refButton).next().remove();
                                        var selectedSLug = $(this).attr("data-slug");
                                        $(refButton)
                                            .closest(".value")
                                            .find("select option").removeAttr('selected');
                                        $(refButton)
                                            .closest(".value")
                                            .find("select option")
                                            .each(function (index, element) {
                                                if ($(element).val() === selectedSLug) {

                                                    $(element).attr("selected", "");

                                                    $(refButton)
                                                        .closest(".value")
                                                        .find("select").val(selectedSLug);
                                                    setTimeout(function () {
                                                        updateBasePriceNew();
                                                        //$(element).removeAttr("selected");
                                                    }, 100)

                                                } else {
                                                    //$(element).removeAttr("selected");
                                                    // console.log('remove')
                                                }
                                            });

                                        var selectedLabel = $(
                                            '<div data-selected="' +
                                            $(this).attr("data-slug") +
                                            '" class="selected-centre selected-centre-' + centre_modal + '">' +
                                            $(this).attr("data-label") +
                                            "</div>"
                                        );
                                        selectedLabel.insertAfter($(refButton));
                                    });
                                    centreBadge.append("<label>" + comp_label + "</label>");
                                    centreModal.find(".centre_modal-body").append(centreBadge);
                                }
                            });
                        }
                    });
                }
            });

            // remove hidden element
            centreDropdownWrapper.each(function (index, element) {
                if (element) {
                    cls = 'mosta_attribute_pa_variation';
                    selectName = $(element).find('.value select').attr('name');
                    
                    $(element).attr('data-select-name', selectName);
                    SelectedName();

                    if( ! $('.' + cls).length){
                        $(element).addClass(cls)
                    }else {
                        setTimeout(function () {
                            if( ! $(element).is(":hidden") ) {
                               selectName = $(element).attr('data-select-name');
                               console.log('onload2 === ' + selectName);
                               SelectedName();
                            }
                         }, 900);
                    }

                    if( $(element).is(":hidden") ) {
                        $(element).addClass(cls)
                    }

                    function SelectedName() {
                        $(element).find('.value select').removeAttr('name');
                        setTimeout(function () {
                           if( ! $(element).is(":hidden") ) {
                              selectedName = $(element).attr('data-select-name');
                             $(element).find('.value select').attr('name', selectedName);
                             console.log('onload3 === ' + selectedName);
                           }
                        }, 900)
                    }
                }
            });

            if (centreModal) {
                centreModal
                    .find('[data-action="centreModalClose"]')
                    .on("click", function (e) {
                        e.preventDefault();
                        $(this).closest("." + centre_modal).fadeOut();
                    });
            }
        }
    }

    function productModalAttribute(){
        var target = $('.single-product #all_accessories');

        if(target.length){
            var cnt = 0;

            target.find('option').each(function(){
                var attrKey = $(this).attr('value');
                var attrName = $(this).text();
                cnt++;

                if(attrName.includes("Ribbon")){
                    variantRenderModal(attrKey, 'ribbon_modal'+cnt, 'Ribbon', 'global_ribbons2');
                }else if(attrName.includes("Medal Box")){
                    variantRenderModal(attrKey, 'monsta_medal_box_modal'+cnt, 'Medal Box', 'global_monsta_medal_boxes');
                } else {
                    variantRenderModal(attrKey, 'centre_modal'+cnt);
                }
            });
        }
    }

    function productModalAttributeDisplay(){
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
    
    $(document).ready(function() {
        productModalAttributeDisplay();
    });
}(window.jQuery || window.$));