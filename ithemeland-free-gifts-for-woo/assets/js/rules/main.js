jQuery(document).ready(function ($) {
    "use strict";

    $(document).on("click", ".wgb-rule-header", function (e) {
        let item = $(this).closest(".wgb-rule-item");
        item.find(".wgb-rule-body").slideToggle(250);
        $(".wgb-rule-item").each(function () {
            if ($(this).attr("data-id") !== item.attr("data-id")) {
                $(this).find(".wgb-rule-body").slideUp(250);
            }
        });
    });

    $(document).on("click", ".wgb-rule-delete", function () {
        let ruleItem = $(this).closest(".wgb-rule-item");
        swal(
            {
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wgb-button wgb-button-lg wgb-button-white",
                confirmButtonClass: "wgb-button wgb-button-lg wgb-button-green",
                confirmButtonText: "Yes, I'm sure !",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    ruleItem.remove();
                    if ($(".wgb-rule-item").length < 1) {
                        $(".wgb-empty-rules-box").show();
                    }
                    wgbRulesFixPriority();
                }
            }
        );
        return false;
    });

    $(document).on("click", ".wgb-rule-duplicate", function () {
        wgbRuleDuplicate($(this).closest(".wgb-rule-item"));
        return false;
    });

    $(document).on("click", ".wgb-rule-item-status", function () {
        return false;
    });

    $(document).on("click", ".wgb-rule-item-language", function () {
        return false;
    });

    $(document).on("change", ".wgb-rule-item-status", function () {
        $(this)
            .closest(".wgb-rule-item")
            .removeClass("wgb-rule-enable")
            .removeClass("wgb-rule-disable")
            .addClass("wgb-rule-" + $(this).val());
    });

    $(document).on("change", ".wgb-rule-method", function () {
        let id = $(this).closest(".wgb-rule-item").attr("data-id");
        let item = $(this).closest(".wgb-rule-item");
        item.find(".wgb-rule-method-name").html($(this).find("option:selected").html());
        if (!item.find('select[name="rule[' + id + '][quantities_based_on]"]').val()) {
            item
                .find('select[name="rule[' + id + '][quantities_based_on]"]')
                .prop("selectedIndex", 0)
                .val();
        }

        if ($(this).val() == "free_shipping") {
            item.find('div[data-type="quantities"] .wgb-rule-section h3').html(WGBL_DATA.translate.select_shipping);
        } else {
            item.find('div[data-type="quantities"] .wgb-rule-section h3').html(WGBL_DATA.translate.quantities_and_settings);
        }

        item.find('.wgb-rule-mode').val('free_gift').change();
        if ($.inArray($(this).val(), ['simple', 'subtotal', 'cheapest_item_in_cart']) !== -1) {
            item.find('.wgb-rule-mode-container').show();
            item.find('.wgb-rule-mode option[value="discount"]').prop('disabled', false);
        } else {
            item.find('.wgb-rule-mode-container').hide();
            item.find('.wgb-rule-mode option[value="discount"]').prop('disabled', true);
        }

        let basedOn = item.find('div[data-type="quantities-based-on"]');
        basedOn.find("label").hide();

        if ($(this).val() == "bulk_pricing") {
            basedOn.find('label[data-label="price-based-on"]').show();
        } else {
            basedOn.find('label[data-label="quantities-based-on"]').show();
        }

        if ($(this).val() == "cheapest_item_in_cart") {
            item.find('.wgb-quantity-item[data-type="quantities-auto-add-gift-to-cart"] label').css({
                'margin-top': '26px'
            });
        } else {
            item.find('.wgb-quantity-item[data-type="quantities-auto-add-gift-to-cart"] label').css({
                'margin-top': '5px'
            });
        }

        // Control promotion section visibility based on method
        let promotionSection = item.find('div[data-type="promotion"]');
        if ($(this).val() === "subtotal" || $(this).val() === "subtotal_repeat") {
            promotionSection.show();
        } else {
            promotionSection.hide();
            // Uncheck promotion toggle when hiding
            promotionSection.find(".wgb-promotion-toggle").prop("checked", false).trigger("change");
        }

        let offerBarSection = item.find('div[data-type="offer_bar"]');
        if ($.inArray($(this).val(), ['free_shipping', 'cheapest_item_in_cart', 'buy_x_get_x', 'buy_x_get_x_repeat', 'get_group_of_products']) === -1) {
            offerBarSection.show();
        } else {
            offerBarSection.hide();
            offerBarSection.find(".wgb-discount-bar-toggle").prop("checked", false).trigger("change");
        }

        switch ($(this).val()) {
            case "simple":
                if ($.isFunction(window.wgbSimpleMethod)) {
                    wgbSimpleMethod(id);
                }
                break;
            case "tiered_quantity":
                if ($.isFunction(window.wgbTieredQuantityMethod)) {
                    wgbTieredQuantityMethod(id);
                }
                break;
            case "bulk_quantity":
                if ($.isFunction(window.wgbBulkQuantityMethod)) {
                    wgbBulkQuantityMethod(id);
                }
                break;
            case "bulk_pricing":
                if ($.isFunction(window.wgbBulkPricingMethod)) {
                    wgbBulkPricingMethod(id);
                }
                break;
            case "subtotal":
                if ($.isFunction(window.wgbSubTotalMethod)) {
                    wgbSubTotalMethod(id);
                }
                break;
            case "subtotal_repeat":
                if ($.isFunction(window.wgbSubTotalRepeatMethod)) {
                    wgbSubTotalRepeatMethod(id);
                }
                break;
            case "buy_x_get_x":
                if ($.isFunction(window.wgbBuyXGetXMethod)) {
                    wgbBuyXGetXMethod(id);
                }
                break;
            case "buy_x_get_x_repeat":
                if ($.isFunction(window.wgbBuyXGetXMethod)) {
                    wgbBuyXGetXMethod(id);
                }
                break;
            case "buy_x_get_y":
                if ($.isFunction(window.wgbBuyXGetYMethod)) {
                    wgbBuyXGetYMethod(id);
                }
                break;
            case "buy_x_get_y_repeat":
                if ($.isFunction(window.wgbBuyXGetYMethod)) {
                    wgbBuyXGetYMethod(id);
                }
                break;
            case "cheapest_item_in_cart":
                if ($.isFunction(window.wgbCheapestItemInCart)) {
                    wgbCheapestItemInCart(id);
                }
                break;
            case "free_shipping":
                if ($.isFunction(window.wgbFreeShippingMethod)) {
                    wgbFreeShippingMethod(id);
                }
                break;
            case "get_group_of_products":
                if ($.isFunction(window.wgbGetGroupOfProductsMethod)) {
                    wgbGetGroupOfProductsMethod(id);
                }
                break;
            default:
                return false;
        }
    });

    $(document).on("click", ".wgb-add-rule", function () {
        wgbAddRule(parseInt($(".wgb-rule-item").length));
        $(".wgb-empty-rules-box").hide();
    });

    $(document).on("keyup", ".wgb-rule-name", function () {
        $(this)
            .closest(".wgb-rule-item")
            .find(".wgb-rule-title")
            .html($(this).val() ? $(this).val() : "New Rule");
    });

    $(document).on("change", ".wgb-rule-quantities-checkbox", function () {
        let elementItem = $(this).closest("div");
        if ($(this).prop("checked") === true) {
            elementItem.find("input[type=hidden]").val("yes");
        } else {
            elementItem.find("input[type=hidden]").val("no");
        }
    });

    $(document).on("change", "#wgb-settings-layout", function () {
        wgbSettingsDependenciesController($(this).val());
    });

    $(document).on("change", "#wgb-settings-position-in-cart", function () {
        let $this = $(this);

        switch ($this.val()) {
            case "none":
            case "popup":
                $('div[data-type="layout-select-box"]').hide();
                $("#wgb-settings-layout").val("").change().prop("disabled", true);
                $(".position-dependency").hide().find("input").prop("disabled", true);
                break;
            default:
                $('div[data-type="layout-select-box"]').show();
                $("#wgb-settings-layout").prop("disabled", false);
                $("#wgb-settings-layout option").hide();
                $('#wgb-settings-layout option[data-type="' + $this.val() + '"]').show();
                setTimeout(function () {
                    if ($("#wgb-settings-layout option:selected").attr("data-type") !== $this.val()) {
                        $("#wgb-settings-layout")
                            .val(
                                $('#wgb-settings-layout option[data-type="' + $this.val() + '"]')
                                    .first()
                                    .attr("value")
                            )
                            .trigger("change");
                    }
                }, 50);
                $(".position-dependency").hide().find("input").prop("disabled", true);
                $('.position-dependency[data-type="' + $this.val() + '"]')
                    .show()
                    .find("input")
                    .prop("disabled", false);
                break;
        }
    });

    $(document).on("change", ".wgb-rule-mode", function () {
        let rule = $(this).closest(".wgb-rule-item");
        if ($(this).val() == 'discount') {
            rule.find('div[data-type="discount_items"]').show();
            rule.find('.wgb-quantity-item[data-type="quantities-same-gift"]').hide();
            rule.find('.wgb-quantity-item[data-type="quantities-auto-add-gift-to-cart"]').hide();
        } else {
            rule.find('div[data-type="discount_items"]').hide();
            if (rule.find('.wgb-rule-method').val() !== 'cheapest_item_in_cart') {
                rule.find('.wgb-quantity-item[data-type="quantities-same-gift"]').show();
            }
            rule.find('.wgb-quantity-item[data-type="quantities-auto-add-gift-to-cart"]').show();

        }
    });

    $(document).on("change", "#wgb-settings-position-gift-in-cart", function () {
        wgbSettingsViewGiftInCartDependenciesController($(this).val());
    });

    $(document).on("click", "#wgb-rules-save-changes", function () {
        let message = "Red fields is required !";
        let getSectionItems = jQuery("div[data-type=get]:visible").find(".wgb-rule-section");
        let getSectionCounter;
        let getSectionValidated = true;

        let optionValues = {
            categories: {},
            coupons: {},
            products: {},
            variations: {},
            tags: {},
            taxonomies: {},
            attributes: {},
            shipping_classes: {},
            shipping_country: {},
            payment_methods: {},
            customers: {},
        };

        if ($(".wgb-select2-option-values").length) {
            $(".wgb-select2-option-values option:selected").each(function () {
                if ($(this).attr("value") != "") {
                    let optionName = $(this).closest(".wgb-select2-option-values").attr("data-option-name");
                    if (optionValues[optionName]) {
                        optionValues[optionName][$(this).attr("value")] = $(this).text();
                    }
                }
            });
        }

        $("#wgb-option-values").val(JSON.stringify(optionValues));

        jQuery("#wgb-rules input:required")
            .filter(function () {
                if (!this.disabled && !this.value) {
                    jQuery(this).closest(".wgb-rule-item").addClass("wgb-rule-error");
                    return true;
                }
            })
            .addClass("wgb-validation-error-field");

        let select2Items = [];
        jQuery("#wgb-rules select:required")
            .filter(function () {
                if ($(this).attr("data-type") === "select2" && !this.value && !this.disabled) {
                    select2Items.push($(this).attr("data-select2-id"));
                }
                return !this.disabled && !this.value;
            })
            .addClass("wgb-validation-error-field");

        if (select2Items.length > 0) {
            jQuery.each(select2Items, function (key, val) {
                let select2Id = parseInt(val) + 1;
                jQuery('.select2-container[data-select2-id="' + select2Id + '"]').addClass("wgb-validation-error-field");
                jQuery('.select2-container[data-select2-id="' + select2Id + '"]')
                    .closest(".wgb-rule-item")
                    .addClass("wgb-rule-error");
            });
        }

        // get section validation
        getSectionItems.each(function () {
            getSectionCounter = 0;
            jQuery(this)
                .find("select")
                .each(function () {
                    if (!jQuery(this).val()) {
                        getSectionCounter++;
                        if (getSectionCounter == 4) {
                            getSectionValidated = false;
                            message = "Get Section is empty !";
                        }
                    }
                });
        });

        $(".wgb-rule-item").each(function () {
            let ruleItem = $(this);
            let method = ruleItem.find(".wgb-rule-method").val();
            let comparisonOperator = ruleItem.find(".wgb-comparison-operator").val();

            if (method === "subtotal" || method === "buy_x_get_x" || method === "buy_x_get_y") {
                if (comparisonOperator === "") {
                    ruleItem.addClass("wgb-rule-error");
                    ruleItem.find(".wgb-comparison-operator").addClass("wgb-validation-error-field");
                    getSectionValidated = false;
                    message = "Red fields is required !";
                }
            }
        });

        if (document.getElementById("wgb-rules-form").checkValidity() && getSectionValidated === true) {
            $("#wgb-rules-form").submit();
        } else {
            swal({
                title: message,
                type: "warning",
            });
        }
    });

    $(document).on("click", ".select2-results__group", function () {
        $(this).closest("ul").find("ul").slideUp(200);
        $(this).closest("li").find("ul").slideDown(200);
    });

    $(document).on("select2:open", ".wgb-select2-grouped", function () {
        setTimeout(() => {
            let item = $(".select2-container--open").last().find('li[aria-selected="true"]');
            item.closest("ul").slideDown(200);
            if (item.offset() && item.closest("ul[role=listbox]").offset()) {
                let top = item.offset().top - (item.closest("ul[role=listbox]").offset().top + 30);
                item.closest("ul[role=listbox]").animate({
                    scrollTop: top,
                });
            }
        }, 250);
    });

    $(document).on("change", "#wgb-settings-show-notice-checkout", function () {
        if ($(this).prop("checked") === true) {
            $("#wgb-settings-notification-show-notice-checkout-dependencies").show();
        } else {
            $("#wgb-settings-notification-show-notice-checkout-dependencies").hide();
        }
    });

    $(document).on('click', '.wgb-get-products-group-item-delete', function () {
        let row = $(this).closest('.wgb-rule-products-group-item');
        $(this).closest('.wgb-rule-products-group-item').remove();
        wgbGetProductsGroupFixPriority(row);
    });

    $(document).on('click', '.wgb-add-get-products-group-item', function () {
        let rule = $(this).closest('.wgb-rule-item');
        let ruleID = rule.attr('data-id');
        let rowID = parseInt($(this).closest('.wgb-rule-item').find('.wgb-rule-products-group-items .wgb-rule-products-group-item').length);
        if (WGBL_RULES_DATA.get_products_group.row) {
            rule.find('.wgb-rule-products-group-items').append((WGBL_RULES_DATA.get_products_group.row).replaceAll('set_group_id_here', rowID).replaceAll('set_rule_id_here', ruleID)).ready(function () {
                rule.find('.wgb-rule-products-group-items .wgb-rule-products-group-item').attr('data-id', rowID);
                wgbResetData();
                wgbGetProductsGroupFixPriority(rule);
            });
        }
    });

    $(document).on('change', '.wgb-rule-products-group-item-type', function () {
        let ruleID = $(this).closest('.wgb-rule-item').attr('data-id');
        let groupID = $(this).closest('.wgb-rule-products-group-item').attr('data-id');
        let item = $(this).closest('.wgb-rule-products-group-item').find('.wgb-form-group[data-type="value"]');
        item.attr('data-id', groupID);

        if (WGBL_RULES_DATA.get_products_group.value_field) {
            let className = ($(this).val() == 'attributes') ? 'taxonomies' : $(this).val();
            item.html((WGBL_RULES_DATA.get_products_group.value_field).replaceAll('set_group_id_here', groupID).replaceAll('set_rule_id_here', ruleID).replaceAll('set_type_here', $(this).val()).replaceAll('set_class_here', className)).ready(function () {
                wgbResetData();
            });
        }
    });

    $("#wgb-settings-position-in-cart").trigger("change");

    wgbSettingsDependenciesController($("#wgb-settings-layout").val());

    setTimeout(function () {
        wgbResetData();
    }, 50);

    $(document).ready(function () {
        var $templateSelect = $("#wgb-settings-promotion-temp-get-product");
        var $perPageGroup = $(".wgb-per-page-group");
        var $laptopColumnGroup = $(".wgb-laptop-column-group");
        var $tabletColumnGroup = $(".wgb-tablet-column-group");
        var $phoneColumnGroup = $(".wgb-phone-column-group");
        var $tempBgColor = $(".wgb-promotion-template-bg-color-group");
        var $tempTitleColor = $(".wgb-promotion-template-title-color-group");
        var $temTitleBgColor = $(".wgb-promotion-template-title-bg-color-group");
        var $temTitleHoverColor = $(".wgb-promotion-template-title-hover-color-group");

        function toggleTemplateFields() {
            var val = $templateSelect.val();

            switch (val) {
                case "glass-morphism":
                    glassMorphism();
                    break;
                case "flex-items":
                    flexItems();
                    break;
                case "tooltip-style":
                    tooltipStyle();
                    break;
                case "gift-box":
                    giftBox();
                    break;
                default:
                    $perPageGroup.hide();
                    $laptopColumnGroup.hide();
                    $tabletColumnGroup.hide();
                    $phoneColumnGroup.hide();
                    $tempBgColor.hide();
                    $tempTitleColor.hide();
                    $temTitleBgColor.hide();
                    $temTitleHoverColor.hide();
            }
        }

        function glassMorphism() {
            $perPageGroup.show();
            $laptopColumnGroup.show();
            $tabletColumnGroup.show();
            $phoneColumnGroup.show();
            $tempBgColor.hide();
            $tempTitleColor.show();
            $temTitleBgColor.hide();
            $temTitleHoverColor.show();
        }

        function flexItems() {
            $perPageGroup.show();
            $laptopColumnGroup.show();
            $tabletColumnGroup.show();
            $phoneColumnGroup.show();
            $tempBgColor.hide();
            $tempTitleColor.show();
            $temTitleBgColor.show();
            $temTitleHoverColor.show();
        }
        function tooltipStyle() {
            $perPageGroup.show();
            $laptopColumnGroup.show();
            $tabletColumnGroup.show();
            $phoneColumnGroup.show();
            $tempBgColor.hide();
            $tempTitleColor.show();
            $temTitleBgColor.show();
            $temTitleHoverColor.show();
        }
        function giftBox() {
            $perPageGroup.show();
            $laptopColumnGroup.hide();
            $tabletColumnGroup.hide();
            $phoneColumnGroup.hide();
            $tempBgColor.show();
            $tempTitleColor.show();
            $temTitleBgColor.hide();
            $temTitleHoverColor.show();
        }

        if ($templateSelect.length) {
            toggleTemplateFields(); // Initial state
            $templateSelect.on("change", toggleTemplateFields);
        }

        // Responsive column functionality
        function updateResponsiveColumns() {
            const wrapper = document.querySelector(".wgb-promotion-template-wrapper");
            if (!wrapper) return;

            const width = window.innerWidth;
            let columns;

            if (width >= 1024) {
                columns = wrapper.style.getPropertyValue("--laptop-columns") || 4;
            } else if (width >= 768) {
                columns = wrapper.style.getPropertyValue("--tablet-columns") || 3;
            } else {
                columns = wrapper.style.getPropertyValue("--phone-columns") || 2;
            }
        }

        // Initialize responsive columns on page load
        updateResponsiveColumns();

        // Update on window resize
        window.addEventListener("resize", updateResponsiveColumns);

        var $promotionCheckbox = $("#wgb-settings-promotion-visibility");
        var $popupRadio = $("#wgb-settings-promotion-popup-radio");
        var $noticeRadio = $("#wgb-settings-promotion-notice-radio");
        var $promotionDependent = $(".wgb-promotion-dependent");
        var $popupDependent = $(".wgb-promotion-notice-dependent");
        var $productTempVisibility = $("#wgb-settings-promotion-get-products-template-visibility");
        var $productTempDependent = $(".wgb-promotion-get-products-templates-dependent");

        function togglePromotionTempDependent() {
            if ($productTempVisibility.is(":checked")) {
                $productTempDependent.show();
            } else {
                $productTempDependent.hide();
            }
        }

        function togglePromotionDependent() {
            if ($promotionCheckbox.is(":checked")) {
                $promotionDependent.show();
                if ($popupRadio.is(":checked")) {
                    $popupDependent.hide();
                } else if ($noticeRadio.is(":checked")) {
                    $popupDependent.show();
                }
            } else {
                $promotionDependent.hide();
            }
        }

        // Initial state
        togglePromotionDependent();
        togglePromotionTempDependent();

        // On change
        $promotionCheckbox.on("change", togglePromotionDependent);
        $popupRadio.on("change", togglePromotionDependent);
        $productTempVisibility.on("change", togglePromotionTempDependent);

        // Promotion popup radio logic
        function handlePromotionPopupRadio() {
            var selected = $('input[name="settings[promotion][promotion_layout]"]:checked').val();
            if (selected === "popup" || selected === "progressbar") {
                $(".wgb-promotion-notice-dependent").hide();
            } else {
                $(".wgb-promotion-notice-dependent").show();
            }
        }
        // Initial check on page load
        handlePromotionPopupRadio();
        // Force re-initialize toggles on page load
        setTimeout(function () {
            handlePromotionToggle();
            handleDiscountBarToggle();
        }, 100);
        // Listen for change
        $(document).on("change", 'input[name="settings[promotion][promotion_layout]"]', handlePromotionPopupRadio);

        // Handle promotion toggle functionality
        function handlePromotionToggle() {
            const $WgbPromotionToggle = $(".wgb-promotion-toggle");
            const $content = $(".wgb-promotion-section-dependent");

            // Check if elements exist
            if ($WgbPromotionToggle.length === 0 || $content.length === 0) {
                return;
            }

            // Remove previous event handlers to prevent multiple bindings
            $WgbPromotionToggle.off("change.promotionToggle");

            // Set initial state for each toggle
            $WgbPromotionToggle.each(function () {
                const $toggle = $(this);
                const $relatedContent = $toggle.closest(".wgb-rule-item").find(".wgb-promotion-section-dependent");

                if (!$toggle.is(":checked")) {
                    $relatedContent.hide();
                } else {
                    $relatedContent.show();
                }
            });

            // Handle toggle change with namespaced event
            $WgbPromotionToggle.on("change.promotionToggle", function () {
                const $toggle = $(this);
                const $relatedContent = $toggle.closest(".wgb-rule-item").find(".wgb-promotion-section-dependent");

                if (this.checked) {
                    $relatedContent.slideDown(300);
                } else {
                    $relatedContent.slideUp(300);
                }
            });
        }

        // Initialize promotion toggle
        handlePromotionToggle();

        // Re-initialize when new content is added dynamically using MutationObserver
        const observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    // Check if any added nodes contain rule items
                    for (let i = 0; i < mutation.addedNodes.length; i++) {
                        const node = mutation.addedNodes[i];
                        if (node.nodeType === 1) { // Element node
                            const $node = $(node);
                            if ($node.hasClass('wgb-rule-item') || $node.find('.wgb-rule-item').length > 0) {
                                setTimeout(function () {
                                    handlePromotionToggle();
                                    handleDiscountBarToggle();
                                }, 100);
                                break;
                            }
                        }
                    }
                }
            });
        });

        // Start observing
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        // Handle discount bar toggle functionality
        function handleDiscountBarToggle() {
            const $WgbDiscountBarToggle = $(".wgb-discount-bar-toggle");
            const $content = $(".wgb-discount-bar-section-dependent");

            // Check if elements exist
            if ($WgbDiscountBarToggle.length === 0 || $content.length === 0) {
                return;
            }

            // Remove previous event handlers to prevent multiple bindings
            $WgbDiscountBarToggle.off("change.discountBarToggle");

            // Set initial state for each toggle
            $WgbDiscountBarToggle.each(function () {
                const $toggle = $(this);
                const $relatedContent = $toggle.closest(".wgb-rule-item").find(".wgb-discount-bar-section-dependent");

                if (!$toggle.is(":checked")) {
                    $relatedContent.hide();
                } else {
                    $relatedContent.show();
                }
            });

            // Handle toggle change with namespaced event
            $WgbDiscountBarToggle.on("change.discountBarToggle", function () {
                const $toggle = $(this);
                const $relatedContent = $toggle.closest(".wgb-rule-item").find(".wgb-discount-bar-section-dependent");

                if (this.checked) {
                    $relatedContent.slideDown(300);
                } else {
                    $relatedContent.slideUp(300);
                }
            });
        }

        // Initialize discount bar toggle
        handleDiscountBarToggle();
    });
});
