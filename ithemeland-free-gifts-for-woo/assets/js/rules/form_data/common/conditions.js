"use strict"

jQuery(document).ready(function ($) {
    $(document).on('click', '.wgb-add-condition', function () {
        let rule = $(this).closest('.wgb-rule-item');
        let ruleID = rule.attr('data-id');
        let conditionID = parseInt($(this).closest('.wgb-rule-item').find('.wgb-condition-items .wgb-rule-item-sortable-item').length);

        if (WGBL_RULES_DATA.condition.row) {
            rule.find('.wgb-condition-items').append((WGBL_RULES_DATA.condition.row).replaceAll('set_condition_id_here', conditionID).replaceAll('set_rule_id_here', ruleID)).ready(function () {
                let newItem = rule.find('.wgb-condition-items .wgb-rule-item-sortable-item').last();
                newItem.find('.wgb-condition-extra-fields').attr('data-id', conditionID);
                wgbSelect2Set();
                wgbDatepickerSet();
                wgbConditionsFixPriority(rule);
            });
        }
    });

    $(document).on('change', '.wgb-rule-condition-item', function () {
        let ruleID = $(this).closest('.wgb-rule-item').attr('data-id');
        let conditionID = $(this).closest('.wgb-rule-item-sortable-item').attr('data-id');
        let item = $(this).closest('.wgb-rule-item-sortable-item').find('.wgb-condition-extra-fields');
        item.attr('data-id', conditionID);

        if (WGBL_RULES_DATA.condition.extra_fields[$(this).val()]) {
            item.html(WGBL_RULES_DATA.condition.extra_fields[$(this).val()].replaceAll('set_condition_id_here', conditionID).replaceAll('set_rule_id_here', ruleID)).ready(function () {
                wgbResetData();
            });
        }
    });

    $(document).on('click', '.wgb-condition-delete', function () {
        let ruleItem = $(this).closest('.wgb-rule-item');
        $(this).closest('.wgb-rule-item-sortable-item').remove();
        wgbConditionsFixPriority(ruleItem);
    });

    $(document).on('change', '.wgb-condition-user-meta-field-type', function () {
        let ruleID = $(this).closest('.wgb-rule-item').attr('data-id');
        let conditionID = $(this).closest('.wgb-rule-item-sortable-item').attr('data-id');
        $(this).closest('.wgb-condition-extra-fields').find('.wgb-condition-extra-fields-col-4').html(wgbGetConditionUserMetaFieldTypeFields($(this).val(), ruleID, conditionID))
    });

    $(document).on('change', '.wgb-condition-coupons-applied-type', function () {
        let ruleID = $(this).closest('.wgb-rule-item').attr('data-id');
        let conditionID = $(this).closest('.wgb-rule-item-sortable-item').attr('data-id');
        $(this).closest('.wgb-condition-extra-fields').find('.wgb-condition-extra-fields-col-4').html(wgbGetConditionCouponsAppliedTypeFields($(this).val(), ruleID, conditionID));
        wgbResetData();
    });
})

function wgbGetConditionCouponsAppliedTypeFields(type, ruleID, conditionID) {
    let output;
    switch (type) {
        case 'at_least_one':
        case 'all':
        case 'only':
        case 'none':
            output = '<select name="rule[' + ruleID + '][condition][' + conditionID + '][coupons][]" class="wgb-select2-coupons wgb-select2-option-values" data-option-name="coupons" data-type="select2" data-placeholder="Select ..." required multiple></select>';
            break;
        default:
            output = '';
    }
    return output;
}

function wgbGetConditionUserMetaFieldTypeFields(type, ruleID, conditionID) {
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
            output = '<input type="text" name="rule[' + ruleID + '][condition][' + conditionID + '][value]" placeholder="Value ..." required>';
            break;
        default:
            output = '';
    }
    return output;
}

function wgbRuleConditions(ruleID) {
    //
}