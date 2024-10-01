"use strict";

jQuery(document).ready(function ($) {
    $(document).on('click', '.wgbl-bulk-quantity-quantities-add-item', function () {
        let rule = $(this).closest('.wgbl-rule-item');
        let ruleID = rule.attr('data-id');
        let rowCount = parseInt($(this).closest('.wgbl-rule-section-content[data-method-type="bulk_quantity"]').find('.wgbl-rule-quantities-bulk-quantity-repeatable-item').length);

        if (WGBL_RULES_DATA.quantities.bulk_quantity.row) {
            rule.find('.wgbl-rule-quantities-bulk-quantity-repeatable-items').append((WGBL_RULES_DATA.quantities.bulk_quantity.row).replaceAll('set_rule_id_here', ruleID).replaceAll('set_row_counter_here', rowCount)).ready(function () {
                wgblBulkQuantityFixPriority(rule);
            });
        }
    });
    $(document).on('click', '.wgbl-bulk-pricing-quantities-add-item', function () {
        let rule = $(this).closest('.wgbl-rule-item');
        let ruleID = rule.attr('data-id');
        let rowCount = parseInt($(this).closest('.wgbl-rule-section-content[data-method-type="bulk_pricing"]').find('.wgbl-rule-quantities-bulk-pricing-repeatable-item').length);

        if (WGBL_RULES_DATA.quantities.bulk_pricing.row) {
            rule.find('.wgbl-rule-quantities-bulk-pricing-repeatable-items').append((WGBL_RULES_DATA.quantities.bulk_pricing.row).replaceAll('set_rule_id_here', ruleID).replaceAll('set_row_counter_here', rowCount)).ready(function () {
                wgblBulkQuantityFixPriority(rule);
            });
        }
    });

    $(document).on('click', '.wgbl-tiered-quantities-add-item', function () {
        let rule = $(this).closest('.wgbl-rule-item');
        let ruleID = rule.attr('data-id');
        let rowCount = parseInt($(this).closest('.wgbl-rule-section-content[data-method-type="tiered_quantity"]').find('.wgbl-rule-quantities-tiered-quantity-repeatable-item').length);

        if (WGBL_RULES_DATA.quantities.tiered_quantity.row) {
            rule.find('.wgbl-rule-quantities-tiered-quantity-repeatable-items').append((WGBL_RULES_DATA.quantities.tiered_quantity.row).replaceAll('set_rule_id_here', ruleID).replaceAll('set_row_counter_here', rowCount)).ready(function () {
                wgblBulkQuantityFixPriority(rule);
            });
        }
    });

    $(document).on('click', '.wgbl-rules-quantities-bulk-quantity-delete-row-item', function () {
        let rule = $(this).closest('.wgbl-rule-item');
        $(this).closest('.wgbl-rule-quantities-bulk-quantity-repeatable-item').remove().ready(function () {
            wgblBulkQuantityFixPriority(rule);
        });
    })

    $(document).on('click', '.wgbl-rules-quantities-bulk-pricing-delete-row-item', function () {
        let rule = $(this).closest('.wgbl-rule-item');
        $(this).closest('.wgbl-rule-quantities-bulk-pricing-repeatable-item').remove().ready(function () {
            wgblBulkQuantityFixPriority(rule);
        });
    })

    $(document).on('click', '.wgbl-rules-quantities-tiered-quantity-delete-row-item', function () {
        let rule = $(this).closest('.wgbl-rule-item');
        $(this).closest('.wgbl-rule-quantities-tiered-quantity-repeatable-item').remove().ready(function () {
            wgblBulkQuantityFixPriority(rule);
        });
    })
});

function wgblRuleSimpleQuantities(ruleID) {
    wgblRuleEnableQuantities(ruleID);
    wgblDisableGeneralQuantitiesItem(ruleID, [
        'quantities-subtotal-amount',
        'quantities-apply-on-cart-item',
        'quantities-buy',
    ]);
}

function wgblRuleCheapestItemInCartQuantities(ruleID) {
    wgblRuleEnableQuantities(ruleID);
    wgblDisableGeneralQuantitiesItem(ruleID, [
        'quantities-subtotal-amount',
        'quantities-auto-add-gift-to-cart',
        'quantities-same-gift',
        'quantities-get',
    ]);
}

function wgblRuleBuyXGetXQuantities(ruleID) {
    wgblRuleEnableQuantities(ruleID);
    wgblDisableGeneralQuantitiesItem(ruleID, [
        'quantities-subtotal-amount',
        'quantities-apply-on-cart-item',
    ]);
}

function wgblRuleBuyXGetYQuantities(ruleID) {
    wgblRuleEnableQuantities(ruleID);
    wgblDisableGeneralQuantitiesItem(ruleID, [
        'quantities-subtotal-amount',
        'quantities-apply-on-cart-item',
    ]);
}

function wgblRuleBulkQuantityQuantities(ruleID) {
    wgblRuleEnableQuantities(ruleID);
}

function wgblRuleBulkPricingQuantities(ruleID) {
    wgblRuleEnableQuantities(ruleID);
}

function wgblRuleTieredQuantities(ruleID) {
    wgblRuleEnableQuantities(ruleID);
}

function wgblRuleSubTotalQuantities(ruleID) {
    wgblRuleEnableQuantities(ruleID);
    wgblDisableGeneralQuantitiesItem(ruleID, [
        'quantities-buy',
        'quantities-apply-on-cart-item',
    ]);
}

function wgblDisableGeneralQuantitiesItem(ruleID, types) {
    wgblShowAllQuantitiesItem(ruleID);
    if (types) {
        let quantities = jQuery('.wgbl-rule-item[data-id=' + ruleID + '] div[data-type=quantities] div[data-method-type="general"] .wgbl-quantity-item');
        quantities.each(function () {
            if (jQuery.inArray(jQuery(this).attr('data-type'), types) !== -1) {
                jQuery(this).find('select').prop("disabled", true);
                jQuery(this).find('input').prop("disabled", true);
                jQuery(this).hide();
            }
        })
    }
}

function wgblRuleEnableQuantities(ruleID) {
    let method = jQuery('.wgbl-rule-item[data-id=' + ruleID + ']').find('.wgbl-rule-method').val();
    switch (method) {
        case 'bulk_quantity':
            wgblRuleDisableGeneralQuantities(ruleID);
            wgblRuleDisableBulkPricingQuantities(ruleID);
            wgblRuleEnableBulkQuantityQuantities(ruleID);
            wgblRuleDisableTieredQuantities(ruleID);
            wgblRuleDisableCheapestItemInCartQuantities(ruleID)
            break;
        case 'bulk_pricing':
            wgblRuleDisableGeneralQuantities(ruleID);
            wgblRuleDisableBulkQuantityQuantities(ruleID);
            wgblRuleDisableTieredQuantities(ruleID);
            wgblRuleEnableBulkPricingQuantities(ruleID);
            wgblRuleDisableCheapestItemInCartQuantities(ruleID)
            break;
        case 'tiered_quantity':
            wgblRuleDisableBulkPricingQuantities(ruleID);
            wgblRuleDisableBulkQuantityQuantities(ruleID);
            wgblRuleDisableGeneralQuantities(ruleID);
            wgblRuleEnableTieredQuantities(ruleID);
            wgblRuleDisableCheapestItemInCartQuantities(ruleID)
            break;
        case 'cheapest_item_in_cart':
            wgblRuleDisableBulkPricingQuantities(ruleID);
            wgblRuleDisableTieredQuantities(ruleID);
            wgblRuleDisableBulkQuantityQuantities(ruleID);
            wgblRuleEnableGeneralQuantities(ruleID);
            wgblRuleEnableCheapestItemInCartQuantities(ruleID);
            break;
        default:
            wgblRuleDisableBulkPricingQuantities(ruleID);
            wgblRuleDisableTieredQuantities(ruleID);
            wgblRuleDisableBulkQuantityQuantities(ruleID);
            wgblRuleDisableCheapestItemInCartQuantities(ruleID)
            wgblRuleEnableGeneralQuantities(ruleID);
    }
}

function wgblRuleEnableBulkQuantityQuantities(ruleID) {
    let bulkQuantityQuantities = jQuery('.wgbl-rule-item[data-id=' + ruleID + '] div[data-method-type="bulk_quantity"]');
    bulkQuantityQuantities.find('select').prop("disabled", false);
    bulkQuantityQuantities.find('input').prop("disabled", false);
    bulkQuantityQuantities.show();
}

function wgblRuleEnableBulkPricingQuantities(ruleID) {
    let bulkQuantityQuantities = jQuery('.wgbl-rule-item[data-id=' + ruleID + '] div[data-method-type="bulk_pricing"]');
    bulkQuantityQuantities.find('select').prop("disabled", false);
    bulkQuantityQuantities.find('input').prop("disabled", false);
    bulkQuantityQuantities.show();
}

function wgblRuleDisableBulkQuantityQuantities(ruleID) {
    let bulkQuantityQuantities = jQuery('.wgbl-rule-item[data-id=' + ruleID + '] div[data-method-type="bulk_quantity"]');
    bulkQuantityQuantities.find('select').prop("disabled", true);
    bulkQuantityQuantities.find('input').prop("disabled", true);
    bulkQuantityQuantities.hide();
}

function wgblRuleDisableBulkPricingQuantities(ruleID) {
    let bulkQuantityQuantities = jQuery('.wgbl-rule-item[data-id=' + ruleID + '] div[data-method-type="bulk_pricing"]');
    bulkQuantityQuantities.find('select').prop("disabled", true);
    bulkQuantityQuantities.find('input').prop("disabled", true);
    bulkQuantityQuantities.hide();
}

function wgblRuleEnableTieredQuantities(ruleID) {
    let bulkQuantityQuantities = jQuery('.wgbl-rule-item[data-id=' + ruleID + '] div[data-method-type="tiered_quantity"]');
    bulkQuantityQuantities.find('select').prop("disabled", false);
    bulkQuantityQuantities.find('input').prop("disabled", false);
    bulkQuantityQuantities.show();
}

function wgblRuleDisableTieredQuantities(ruleID) {
    let bulkQuantityQuantities = jQuery('.wgbl-rule-item[data-id=' + ruleID + '] div[data-method-type="tiered_quantity"]');
    bulkQuantityQuantities.find('select').prop("disabled", true);
    bulkQuantityQuantities.find('input').prop("disabled", true);
    bulkQuantityQuantities.hide();
}

function wgblRuleEnableGeneralQuantities(ruleID) {
    let generalQuantities = jQuery('.wgbl-rule-item[data-id=' + ruleID + '] div[data-method-type="general"]');
    generalQuantities.find('select').prop("disabled", false);
    generalQuantities.find('input').prop("disabled", false);
    generalQuantities.show();
}

function wgblRuleDisableGeneralQuantities(ruleID) {
    let generalQuantities = jQuery('.wgbl-rule-item[data-id=' + ruleID + '] div[data-method-type="general"]');
    generalQuantities.find('select').prop("disabled", true);
    generalQuantities.find('input').prop("disabled", true);
    generalQuantities.hide();
}

function wgblRuleEnableCheapestItemInCartQuantities(ruleID) {
    let generalQuantities = jQuery('.wgbl-rule-item[data-id=' + ruleID + '] div[data-method-type="cheapest_item_in_cart"]');
    generalQuantities.find('select').prop("disabled", false);
    generalQuantities.find('input').prop("disabled", false);
    generalQuantities.show();
}

function wgblRuleDisableCheapestItemInCartQuantities(ruleID) {
    let generalQuantities = jQuery('.wgbl-rule-item[data-id=' + ruleID + '] div[data-method-type="cheapest_item_in_cart"]');
    generalQuantities.find('select').prop("disabled", true);
    generalQuantities.find('input').prop("disabled", true);
    generalQuantities.hide();
}

function wgblShowAllQuantitiesItem(ruleID) {
    wgblRuleDisableBulkQuantityQuantities(ruleID);
    let quantities = jQuery('.wgbl-rule-item[data-id=' + ruleID + '] div[data-type=quantities] div[data-method-type="general"]');
    quantities.find('.wgbl-quantity-item').show();
    quantities.find('input').prop('disabled', false);
    quantities.find('select').prop('disabled', false);
}