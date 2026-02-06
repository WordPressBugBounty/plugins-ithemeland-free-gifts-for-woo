"use strict";

jQuery(document).ready(function ($) {
    $(document).on("click", ".wgb-bulk-quantity-quantities-add-item", function () {
        let rule = $(this).closest(".wgb-rule-item");
        let ruleID = rule.attr("data-id");
        let rowCount = parseInt($(this).closest('.wgb-rule-section-content[data-method-type="bulk_quantity"]').find(".wgb-rule-quantities-bulk-quantity-repeatable-item").length);

        if (WGBL_RULES_DATA.quantities.bulk_quantity.row) {
            rule
                .find(".wgb-rule-quantities-bulk-quantity-repeatable-items")
                .append(WGBL_RULES_DATA.quantities.bulk_quantity.row.replaceAll("set_rule_id_here", ruleID).replaceAll("set_row_counter_here", rowCount))
                .ready(function () {
                    wgbBulkQuantityFixPriority(rule);
                });
        }
    });
    $(document).on("click", ".wgb-bulk-pricing-quantities-add-item", function () {
        let rule = $(this).closest(".wgb-rule-item");
        let ruleID = rule.attr("data-id");
        let rowCount = parseInt($(this).closest('.wgb-rule-section-content[data-method-type="bulk_pricing"]').find(".wgb-rule-quantities-bulk-pricing-repeatable-item").length);

        if (WGBL_RULES_DATA.quantities.bulk_pricing.row) {
            rule
                .find(".wgb-rule-quantities-bulk-pricing-repeatable-items")
                .append(WGBL_RULES_DATA.quantities.bulk_pricing.row.replaceAll("set_rule_id_here", ruleID).replaceAll("set_row_counter_here", rowCount))
                .ready(function () {
                    wgbBulkQuantityFixPriority(rule);
                });
        }
    });

    $(document).on("click", ".wgb-tiered-quantities-add-item", function () {
        let rule = $(this).closest(".wgb-rule-item");
        let ruleID = rule.attr("data-id");
        let rowCount = parseInt($(this).closest('.wgb-rule-section-content[data-method-type="tiered_quantity"]').find(".wgb-rule-quantities-tiered-quantity-repeatable-item").length);

        if (WGBL_RULES_DATA.quantities.tiered_quantity.row) {
            rule
                .find(".wgb-rule-quantities-tiered-quantity-repeatable-items")
                .append(WGBL_RULES_DATA.quantities.tiered_quantity.row.replaceAll("set_rule_id_here", ruleID).replaceAll("set_row_counter_here", rowCount))
                .ready(function () {
                    wgbBulkQuantityFixPriority(rule);
                });
        }
    });

    $(document).on("click", ".wgb-rules-quantities-bulk-quantity-delete-row-item", function () {
        let rule = $(this).closest(".wgb-rule-item");
        $(this)
            .closest(".wgb-rule-quantities-bulk-quantity-repeatable-item")
            .remove()
            .ready(function () {
                wgbBulkQuantityFixPriority(rule);
            });
    });

    $(document).on("click", ".wgb-rules-quantities-bulk-pricing-delete-row-item", function () {
        let rule = $(this).closest(".wgb-rule-item");
        $(this)
            .closest(".wgb-rule-quantities-bulk-pricing-repeatable-item")
            .remove()
            .ready(function () {
                wgbBulkQuantityFixPriority(rule);
            });
    });

    $(document).on("click", ".wgb-rules-quantities-tiered-quantity-delete-row-item", function () {
        let rule = $(this).closest(".wgb-rule-item");
        $(this)
            .closest(".wgb-rule-quantities-tiered-quantity-repeatable-item")
            .remove()
            .ready(function () {
                wgbBulkQuantityFixPriority(rule);
            });
    });

    // Initial setup for all rules
    $(".wgb-rule-item").each(function () {
        let ruleItem = $(this);

        wgbRuleComparisonOperator(ruleItem);
        checkPromotionVisibility(ruleItem);
    });
    // Handle method changes
    $(document).on("change", ".wgb-rule-method", function () {
        let ruleItem = $(this).closest(".wgb-rule-item");

        wgbRuleComparisonOperator(ruleItem);
        checkPromotionVisibility(ruleItem);
    });

    $(document).on("change", ".wgb-comparison-operator", function () {
        let currentOperator = $(this).val();
        let ruleItem = $(this).closest(".wgb-rule-item");
        let method = ruleItem.find(".wgb-rule-method").val();
        let promotionSection = ruleItem.find('[data-type="promotion"]');
        let subtotalFields = ruleItem.find('[data-type="promotion-subtotal"]');
        let quantityFields = ruleItem.find('[data-type="promotion-quantity"]');

        // First check if promotion visibility is enabled in settings
        let promotionVisibility = WGBL_RULES_DATA.settings && WGBL_RULES_DATA.settings.promotion_visibility === "true";

        if (!promotionVisibility) {
            return;
        }

        if (method === "subtotal" || method === "subtotal_repeat") {
            if (currentOperator === "greater_than" || currentOperator === "greater_than_or_equal") {
                promotionSection.show();
                subtotalFields.show();
                quantityFields.hide();
            } else {
                promotionSection.hide();
                subtotalFields.hide();
                quantityFields.hide();
            }
        } else {
            promotionSection.hide();
            subtotalFields.hide();
            quantityFields.hide();
        }
    });
});

function wgbRuleSimpleQuantities(ruleID) {
    wgbRuleEnableQuantities(ruleID);
    wgbDisableGeneralQuantitiesItem(ruleID, ["quantities-subtotal-amount", "quantities-apply-on-cart-item", "quantities-buy"]);
}

function wgbRuleCheapestItemInCartQuantities(ruleID) {
    wgbRuleEnableQuantities(ruleID);
    wgbDisableGeneralQuantitiesItem(ruleID, ["quantities-subtotal-amount", "quantities-same-gift", "quantities-buy"]);
}

function wgbRuleFreeShippingQuantities(ruleID) {
    wgbRuleEnableQuantities(ruleID);
    wgbDisableGeneralQuantitiesItem(ruleID, "all");
}

function wgbRuleBuyXGetXQuantities(ruleID) {
    wgbRuleEnableQuantities(ruleID);
    wgbDisableGeneralQuantitiesItem(ruleID, ["quantities-subtotal-amount", "quantities-apply-on-cart-item"]);
}

function wgbRuleBuyXGetYQuantities(ruleID) {
    wgbRuleEnableQuantities(ruleID);
    wgbDisableGeneralQuantitiesItem(ruleID, ["quantities-subtotal-amount", "quantities-apply-on-cart-item"]);
}

function wgbRuleBulkQuantityQuantities(ruleID) {
    wgbRuleEnableQuantities(ruleID);
}

function wgbRuleBulkPricingQuantities(ruleID) {
    wgbRuleEnableQuantities(ruleID);
}

function wgbRuleTieredQuantities(ruleID) {
    wgbRuleEnableQuantities(ruleID);
}

function wgbRuleSubTotalQuantities(ruleID) {
    wgbRuleEnableQuantities(ruleID);
    wgbDisableGeneralQuantitiesItem(ruleID, ["quantities-buy", "quantities-apply-on-cart-item"]);
}

function wgbDisableGeneralQuantitiesItem(ruleID, types) {
    wgbShowAllQuantitiesItem(ruleID);
    if (types) {
        let quantities = jQuery(".wgb-rule-item[data-id=" + ruleID + '] div[data-type=quantities] div[data-method-type="general"] .wgb-quantity-item');
        if (types == "all") {
            quantities
                .each(function () {
                    jQuery(this).find("select").prop("disabled", true);
                    jQuery(this).find("input").prop("disabled", true);
                    jQuery(this).hide();
                })
                .promise()
                .done(function () {
                    quantities.closest('div[data-method-type="general"]').hide();
                });
        } else {
            quantities.each(function () {
                if (jQuery.inArray(jQuery(this).attr("data-type"), types) !== -1) {
                    jQuery(this).find("select").prop("disabled", true);
                    jQuery(this).find("input").prop("disabled", true);
                    jQuery(this).hide();
                }
            });
        }
    }
}

function wgbRuleEnableQuantities(ruleID) {
    let method = jQuery(".wgb-rule-item[data-id=" + ruleID + "]")
        .find(".wgb-rule-method")
        .val();
    switch (method) {
        case "bulk_quantity":
            wgbRuleDisableGeneralQuantities(ruleID);
            wgbRuleDisableBulkPricingQuantities(ruleID);
            wgbRuleEnableBulkQuantityQuantities(ruleID);
            wgbRuleDisableTieredQuantities(ruleID);
            wgbRuleDisableCheapestItemInCartQuantities(ruleID);
            wgbRuleDisableFreeShippingQuantities(ruleID);

            break;
        case "bulk_pricing":
            wgbRuleDisableGeneralQuantities(ruleID);
            wgbRuleDisableBulkQuantityQuantities(ruleID);
            wgbRuleDisableTieredQuantities(ruleID);
            wgbRuleEnableBulkPricingQuantities(ruleID);
            wgbRuleDisableCheapestItemInCartQuantities(ruleID);
            wgbRuleDisableFreeShippingQuantities(ruleID);

            break;
        case "tiered_quantity":
            wgbRuleDisableBulkPricingQuantities(ruleID);
            wgbRuleDisableBulkQuantityQuantities(ruleID);
            wgbRuleDisableGeneralQuantities(ruleID);
            wgbRuleEnableTieredQuantities(ruleID);
            wgbRuleDisableCheapestItemInCartQuantities(ruleID);
            wgbRuleDisableFreeShippingQuantities(ruleID);
            break;
        case "cheapest_item_in_cart":
            wgbRuleDisableBulkPricingQuantities(ruleID);
            wgbRuleDisableTieredQuantities(ruleID);
            wgbRuleDisableBulkQuantityQuantities(ruleID);
            wgbRuleEnableGeneralQuantities(ruleID);
            wgbRuleEnableCheapestItemInCartQuantities(ruleID);
            wgbRuleDisableFreeShippingQuantities(ruleID);

            break;
        case "free_shipping":
            wgbRuleDisableBulkPricingQuantities(ruleID);
            wgbRuleDisableTieredQuantities(ruleID);
            wgbRuleDisableBulkQuantityQuantities(ruleID);
            wgbRuleEnableGeneralQuantities(ruleID);
            wgbRuleEnableFreeShippingQuantities(ruleID);

            break;
        case "get_group_of_products":
            wgbRuleDisableBulkPricingQuantities(ruleID);
            wgbRuleDisableTieredQuantities(ruleID);
            wgbRuleDisableBulkQuantityQuantities(ruleID);
            wgbRuleDisableCheapestItemInCartQuantities(ruleID);
            wgbRuleDisableGeneralQuantities(ruleID);
            wgbRuleDisableFreeShippingQuantities(ruleID);

            break;
        default:
            wgbRuleDisableBulkPricingQuantities(ruleID);
            wgbRuleDisableTieredQuantities(ruleID);
            wgbRuleDisableBulkQuantityQuantities(ruleID);
            wgbRuleDisableCheapestItemInCartQuantities(ruleID);
            wgbRuleEnableGeneralQuantities(ruleID);
            wgbRuleDisableFreeShippingQuantities(ruleID);
    }
}

function wgbRuleEnableBulkQuantityQuantities(ruleID) {
    let bulkQuantityQuantities = jQuery(".wgb-rule-item[data-id=" + ruleID + '] div[data-method-type="bulk_quantity"]');
    bulkQuantityQuantities.find("select").prop("disabled", false);
    bulkQuantityQuantities.find("input").prop("disabled", false);
    bulkQuantityQuantities.show();
}

function wgbRuleEnableBulkPricingQuantities(ruleID) {
    let bulkQuantityQuantities = jQuery(".wgb-rule-item[data-id=" + ruleID + '] div[data-method-type="bulk_pricing"]');
    bulkQuantityQuantities.find("select").prop("disabled", false);
    bulkQuantityQuantities.find("input").prop("disabled", false);
    bulkQuantityQuantities.show();
}

function wgbRuleDisableBulkQuantityQuantities(ruleID) {
    let bulkQuantityQuantities = jQuery(".wgb-rule-item[data-id=" + ruleID + '] div[data-method-type="bulk_quantity"]');
    bulkQuantityQuantities.find("select").prop("disabled", true);
    bulkQuantityQuantities.find("input").prop("disabled", true);
    bulkQuantityQuantities.hide();
}

function wgbRuleDisableBulkPricingQuantities(ruleID) {
    let bulkQuantityQuantities = jQuery(".wgb-rule-item[data-id=" + ruleID + '] div[data-method-type="bulk_pricing"]');
    bulkQuantityQuantities.find("select").prop("disabled", true);
    bulkQuantityQuantities.find("input").prop("disabled", true);
    bulkQuantityQuantities.hide();
}

function wgbRuleEnableTieredQuantities(ruleID) {
    let bulkQuantityQuantities = jQuery(".wgb-rule-item[data-id=" + ruleID + '] div[data-method-type="tiered_quantity"]');
    bulkQuantityQuantities.find("select").prop("disabled", false);
    bulkQuantityQuantities.find("input").prop("disabled", false);
    bulkQuantityQuantities.show();
}

function wgbRuleDisableTieredQuantities(ruleID) {
    let bulkQuantityQuantities = jQuery(".wgb-rule-item[data-id=" + ruleID + '] div[data-method-type="tiered_quantity"]');
    bulkQuantityQuantities.find("select").prop("disabled", true);
    bulkQuantityQuantities.find("input").prop("disabled", true);
    bulkQuantityQuantities.hide();
}

function wgbRuleEnableGeneralQuantities(ruleID) {
    let generalQuantities = jQuery(".wgb-rule-item[data-id=" + ruleID + '] div[data-method-type="general"]');
    generalQuantities.find("select").prop("disabled", false);
    generalQuantities.find("input").prop("disabled", false);
    generalQuantities.show();
}

function wgbRuleDisableGeneralQuantities(ruleID) {
    let generalQuantities = jQuery(".wgb-rule-item[data-id=" + ruleID + '] div[data-method-type="general"]');
    generalQuantities.find("select").prop("disabled", true);
    generalQuantities.find("input").prop("disabled", true);
    generalQuantities.hide();
}

function wgbRuleEnableCheapestItemInCartQuantities(ruleID) {
    let generalQuantities = jQuery(".wgb-rule-item[data-id=" + ruleID + '] div[data-method-type="cheapest_item_in_cart"]');
    generalQuantities.find("select").prop("disabled", false);
    generalQuantities.find("input").prop("disabled", false);
    generalQuantities.show();
}

function wgbRuleDisableCheapestItemInCartQuantities(ruleID) {
    let generalQuantities = jQuery(".wgb-rule-item[data-id=" + ruleID + '] div[data-method-type="cheapest_item_in_cart"]');
    generalQuantities.find("select").prop("disabled", true);
    generalQuantities.find("input").prop("disabled", true);
    generalQuantities.hide();
}

function wgbRuleEnableFreeShippingQuantities(ruleID) {
    let generalQuantities = jQuery(".wgb-rule-item[data-id=" + ruleID + '] div[data-method-type="free_shipping"]');
    generalQuantities.find("select").prop("disabled", false);
    generalQuantities.find("input").prop("disabled", false);
    generalQuantities.show();
}

function wgbRuleDisableFreeShippingQuantities(ruleID) {
    let generalQuantities = jQuery(".wgb-rule-item[data-id=" + ruleID + '] div[data-method-type="free_shipping"]');
    generalQuantities.find("select").prop("disabled", true);
    generalQuantities.find("input").prop("disabled", true);
    generalQuantities.hide();
}

function wgbShowAllQuantitiesItem(ruleID) {
    wgbRuleDisableBulkQuantityQuantities(ruleID);
    let quantities = jQuery(".wgb-rule-item[data-id=" + ruleID + '] div[data-type=quantities] div[data-method-type="general"]');

    // First hide the operator field by default
    quantities.find('[data-type="quantities-subtotal-operator"]').hide();

    // Show and enable other quantity items
    quantities.find(".wgb-quantity-item:not([data-type='quantities-subtotal-operator'])").show();
    quantities.find("input").prop("disabled", false);
    quantities.find("select").prop("disabled", false);

    // Re-hide operator if not subtotal method
    let method = jQuery(".wgb-rule-item[data-id=" + ruleID + "]")
        .find(".wgb-rule-method")
        .val();
    if (method === "subtotal" || method === "subtotal_repeat") {
        quantities.find('[data-type="quantities-subtotal-operator"]').show().find("select").prop("disabled", false);
    }
}

function wgbRuleComparisonOperator(ruleItem) {
    let method = ruleItem.find(".wgb-rule-method").val();
    let comparisonOperator = ruleItem.find('[data-type="quantities-comparison-operator"]');
    // Hide by default
    comparisonOperator.hide().find("select").prop("disabled", true);

    switch (method) {
        case "subtotal":
            return comparisonOperator.show().find("select").prop("disabled", false);
        case "buy_x_get_x":
            return comparisonOperator.show().find("select").prop("disabled", false);
        case "buy_x_get_y":
            return comparisonOperator.show().find("select").prop("disabled", false);
        default:
            return comparisonOperator.hide().find("select").prop("disabled", true);
    }
}

function checkPromotionVisibility(ruleItem) {
    let method = ruleItem.find(".wgb-rule-method").val();
    let promotionSection = ruleItem.find('[data-type="promotion"]');
    let subtotalFields = ruleItem.find('[data-type="promotion-subtotal"]');
    let quantityFields = ruleItem.find('[data-type="promotion-quantity"]');

    // First check if promotion visibility is enabled in settings
    let promotionVisibility = WGBL_RULES_DATA.settings && WGBL_RULES_DATA.settings.promotion_visibility === "true";

    if (!promotionVisibility) {
        return;
    }
    // Hide by default
    promotionSection.hide();

    // Handle operator change event
    let visibility = method === "subtotal" || method === "subtotal_repeat";

    if (visibility) {
        switch (method) {
            case "subtotal":
                return promotionSection.show(), subtotalFields.show(), quantityFields.hide();
            case "subtotal_repeat":
                return promotionSection.show(), subtotalFields.show(), quantityFields.hide();
            default:
                return promotionSection.hide(), subtotalFields.hide(), quantityFields.hide();
        }
    }
}

function wgbShowPriceType(id) {
    let priceType = jQuery('.wgb-rule-item[data-id=' + id + '] div[data-type="quantities-apply-on-cart-item"]');
    priceType.find('select').prop('disabled', false);
    priceType.find('input').prop('disabled', false);
    priceType.show();
}

function wgbHidePriceType(id) {
    let priceType = jQuery('.wgb-rule-item[data-id=' + id + '] div[data-type="quantities-apply-on-cart-item"]');
    priceType.find('select').prop('disabled', true);
    priceType.find('input').prop('disabled', true);
    priceType.hide();
}