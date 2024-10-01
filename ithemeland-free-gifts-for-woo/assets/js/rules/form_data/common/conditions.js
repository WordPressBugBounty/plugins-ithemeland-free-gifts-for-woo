"use strict"

jQuery(document).ready(function ($) {
    $(document).on('click', '.wgbl-add-condition', function () {
        let rule = $(this).closest('.wgbl-rule-item');
        let ruleID = rule.attr('data-id');
        let conditionID = parseInt($(this).closest('.wgbl-rule-item').find('.wgbl-condition-items .wgbl-rule-item-sortable-item').length);

        if (WGBL_RULES_DATA.condition.row) {
            rule.find('.wgbl-condition-items').append((WGBL_RULES_DATA.condition.row).replaceAll('set_condition_id_here', conditionID).replaceAll('set_rule_id_here', ruleID)).ready(function () {
                let newItem = rule.find('.wgbl-condition-items .wgbl-rule-item-sortable-item').last();
                newItem.find('.wgbl-condition-extra-fields').attr('data-id', conditionID);
                wgblSelect2Set();
                wgblDatepickerSet();
                wgblConditionsFixPriority(rule);
            });
        }
    });

    $(document).on('change', '.wgbl-rule-condition-item', function () {
        let ruleID = $(this).closest('.wgbl-rule-item').attr('data-id');
        let conditionID = $(this).closest('.wgbl-rule-item-sortable-item').attr('data-id');
        let item = $(this).closest('.wgbl-rule-item-sortable-item').find('.wgbl-condition-extra-fields');
        item.attr('data-id', conditionID);

        if (WGBL_RULES_DATA.condition.extra_fields[$(this).val()]) {
            item.html(WGBL_RULES_DATA.condition.extra_fields[$(this).val()].replaceAll('set_condition_id_here', conditionID).replaceAll('set_rule_id_here', ruleID)).ready(function () {
                wgblResetData();
            });
        }
    });

    $(document).on('click', '.wgbl-condition-delete', function () {
        let ruleItem = $(this).closest('.wgbl-rule-item');
        $(this).closest('.wgbl-rule-item-sortable-item').remove();
        wgblConditionsFixPriority(ruleItem);
    });

    $(document).on('change', '.wgbl-condition-user-meta-field-type', function () {
        let ruleID = $(this).closest('.wgbl-rule-item').attr('data-id');
        let conditionID = $(this).closest('.wgbl-rule-item-sortable-item').attr('data-id');
        $(this).closest('.wgbl-condition-extra-fields').find('.wgbl-condition-extra-fields-col-4').html(wgblGetConditionUserMetaFieldTypeFields($(this).val(), ruleID, conditionID))
    });

    $(document).on('change', '.wgbl-condition-coupons-applied-type', function () {
        let ruleID = $(this).closest('.wgbl-rule-item').attr('data-id');
        let conditionID = $(this).closest('.wgbl-rule-item-sortable-item').attr('data-id');
        $(this).closest('.wgbl-condition-extra-fields').find('.wgbl-condition-extra-fields-col-4').html(wgblGetConditionCouponsAppliedTypeFields($(this).val(), ruleID, conditionID));
        wgblResetData();
    });
})

function wgblGetConditionCouponsAppliedTypeFields(type, ruleID, conditionID) {
    let output;
    switch (type) {
        case 'at_least_one':
        case 'all':
        case 'only':
        case 'none':
            output = '<select name="rule[' + ruleID + '][condition][' + conditionID + '][coupons][]" class="wgbl-select2-coupons wgbl-select2-option-values" data-option-name="coupons" data-type="select2" data-placeholder="Select ..." required multiple></select>';
            break;
        default:
            output = '';
    }
    return output;
}

function wgblGetConditionUserMetaFieldTypeFields(type, ruleID, conditionID) {
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

function wgblRuleConditions(ruleID) {
    //
}