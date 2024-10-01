"use strict";

jQuery(document).ready(function ($) {
    $(document).on('change', '.wgbl-rule-product-buy-product-item', function () {
        let rule = $(this).closest('.wgbl-rule-item');
        let ruleID = rule.attr('data-id');
        let productBuyID = $(this).closest('.wgbl-rule-item-sortable-item').attr('data-id');
        let item = $(this).closest('.wgbl-rule-item-sortable-item').find('.wgbl-product-buy-extra-fields');
        item.attr('data-id', productBuyID);

        if (WGBL_RULES_DATA.product_buy.extra_fields[$(this).val()]) {
            item.html(WGBL_RULES_DATA.product_buy.extra_fields[$(this).val()].replaceAll('set_product_buy_id_here', productBuyID).replaceAll('set_rule_id_here', ruleID)).ready(function () {
                wgblResetData();
            });
        }
    });

    $(document).on('click', '.wgbl-product-buy-add-product', function () {
        let rule = $(this).closest('.wgbl-rule-item');
        let ruleID = rule.attr('data-id');
        let productBuyID = parseInt($(this).closest('.wgbl-rule-item').find('.wgbl-product-buy-items .wgbl-rule-item-sortable-item').length);

        if (WGBL_RULES_DATA.product_buy.row) {
            rule.find('.wgbl-product-buy-items').append((WGBL_RULES_DATA.product_buy.row).replaceAll('set_product_buy_id_here', productBuyID).replaceAll('set_rule_id_here', ruleID)).ready(function () {
                let newItem = rule.find('.wgbl-product-buy-items .wgbl-rule-item-sortable-item').last();
                newItem.attr('data-id', productBuyID);
                wgblSelect2Set();
                wgblGetProducts();
                wgblProductBuyFixPriority(rule);
            });
        }
    });

    $(document).on('click', '.wgbl-product-item-delete', function () {
        let ruleItem = $(this).closest('.wgbl-rule-item');
        $(this).closest('.wgbl-rule-item-sortable-item').remove();
        wgblProductBuyFixPriority(ruleItem);
    });

    $(document).on('change', '.wgbl-product-buy-product-meta-field-type', function () {
        let ruleID = $(this).closest('.wgbl-rule-item').attr('data-id');
        let productBuyID = $(this).closest('.wgbl-rule-item-sortable-item').attr('data-id');
        $(this).closest('.wgbl-product-buy-extra-fields').find('.wgbl-product-buy-extra-fields-col-4').html(wgblGetProductBuyProductMetaFieldTypeFields($(this).val(), ruleID, productBuyID))
    });

    $(document).on('change', '.wgbl-product-buy-coupons-applied-type', function () {
        let ruleID = $(this).closest('.wgbl-rule-item').attr('data-id');
        let productBuyID = $(this).closest('.wgbl-rule-item-sortable-item').attr('data-id');
        $(this).closest('.wgbl-product-buy-extra-fields').find('.wgbl-product-buy-extra-fields-col-4').html(wgblGetProductBuyCouponsAppliedTypeFields($(this).val(), ruleID, productBuyID));
        wgblResetData();
    });
})

function wgblGetProductBuyProductMetaFieldTypeFields(type, ruleID, productBuyID) {
    let output;
    switch (type) {
        case 'contains':
        case 'does_not_contain':
        case 'does_not_contain':
        case 'begins_with':
        case 'ends_with':
        case 'equals':
        case 'does_not_equal':
        case 'less_than':
        case 'less_or_equal_to':
        case 'more_than':
        case 'more_or_equal':
            output = '<input type="text" name="rule[' + ruleID + '][product_buy][' + productBuyID + '][value]" placeholder="Value ..." required>';
            break;
        default:
            output = '';
    }
    return output;
}

function wgblGetProductBuyCouponsAppliedTypeFields(type, ruleID, productBuyID) {
    let output;
    switch (type) {
        case 'at_least_one':
        case 'all':
        case 'only':
        case 'none':
            output = '<select name="rule[' + ruleID + '][product_buy][' + productBuyID + '][coupons][]" class="wgbl-select2-coupons wgbl-select2-option-values" data-option-name="coupons" data-type="select2" data-placeholder="Select ..." multiple required></select>';
            break;
        default:
            output = '';
    }
    return output;
}

function wgblShowProductBuy(id) {
    let productBuy = jQuery('.wgbl-rule-item[data-id=' + id + '] div[data-type=product-buy]');
    productBuy.find('select').prop('disabled', false);
    productBuy.find('input').prop('disabled', false);
    productBuy.show();
}

function wgblHideProductBuy(id) {
    let productBuy = jQuery('.wgbl-rule-item[data-id=' + id + '] div[data-type=product-buy]');
    productBuy.find('select').prop('disabled', true);
    productBuy.find('input').prop('disabled', true);
    productBuy.hide();
}
