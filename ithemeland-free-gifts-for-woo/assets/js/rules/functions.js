"use strict";

var optionValues = [];
var productsList = false;
var variationsList = false;
var categoriesList = false;
var attributesList = false;
var tagsList = false;
var taxonomiesList = false;
var shippingClassesList = false;
var customersList = false;
var userRolesList = false;
var userCapabilitiesList = false;

function wgblGetCustomers() {
    let query;
    jQuery(".wgbl-select2-customers").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgbl_get_customers",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "Customer Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: '100%'
    });
}

function wgblGetPaymentMethods() {
    let query;
    jQuery(".wgbl-select2-payment-methods").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgbl_get_payment_methods",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "Select ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: '100%'
    });
}

function wgblGetShippingCountry() {
    let query;
    jQuery(".wgbl-select2-shipping-country").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgbl_get_shipping_country",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "Select ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: '100%'
    });
}

function wgblGetUserRoles() {
    let query;
    jQuery(".wgbl-select2-user-roles").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgbl_get_user_roles",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "Role Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: '100%'
    });
}

function wgblGetUserCapabilities() {
    let query;
    jQuery(".wgbl-select2-user-capabilities").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgbl_get_user_capabilities",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: '100%'
    });
}

function wgblGetProducts() {
    let query;
    jQuery(".wgbl-select2-products").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgbl_get_products",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Product Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: '100%'
    });
}

function wgblGetProductsVariations() {
    let query;
    jQuery(".wgbl-select2-products-variations").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgbl_get_products_variations",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "Product Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: '100%'
    });
}

function wgblGetVariations() {
    let query;
    jQuery(".wgbl-select2-variations").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgbl_get_variations",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "variation Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: '100%'
    });
}

function wgblGetTags() {
    let query;
    jQuery(".wgbl-select2-tags").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgbl_get_tags",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "Tag Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: '100%'
    });
}

function wgblGetCategories() {
    let query;
    jQuery(".wgbl-select2-categories").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgbl_get_categories",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "Category Name, ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: '100%'
    });
}

function wgblGetAttributes() {
    let query;
    jQuery(".wgbl-select2-attributes").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgbl_get_attributes",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "Attribute Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: '100%'
    });
}

function wgblGetTaxonomies() {
    let query;
    jQuery(".wgbl-select2-taxonomies").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgbl_get_taxonomies",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "Taxonomy Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: '100%'
    });
}

function wgblGetShippingClass() {
    let query;
    jQuery(".wgbl-select2-shipping_classes").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgbl_get_shipping_class",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "Product Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: '100%'
    });
}

function wgblGetCoupons() {
    let query;
    jQuery(".wgbl-select2-coupons").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgbl_get_coupons",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "Coupon Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: '100%'
    });
}

function wgblResetData() {
    wgblGetCustomers();
    wgblGetPaymentMethods();
    wgblGetShippingCountry();
    wgblGetUserRoles();
    wgblGetUserCapabilities();
    wgblGetProducts();
    wgblGetTaxonomies();
    wgblGetProductsVariations();
    wgblGetVariations();
    wgblGetTags();
    wgblGetCategories();
    wgblGetAttributes();
    wgblGetShippingClass();
    wgblGetCoupons();
    wgblSetSortableItems();
    wgblDatepickerSet();
    wgblSelect2Set();
}

function wgblSetOptionValues(values) {
    optionValues = values;
}

function uidGenerator() {
    return Date.now() + Math.floor((Math.random() * 999) + 100);
}

function wgblAddRule(id, rule = [], callback = '') {
    let uid = (rule.uid) ? rule.uid : uidGenerator();

    jQuery('#wgbl-rules').append((WGBL_RULES_DATA.new_rule).replaceAll('set_rule_id_here', id).replaceAll('set_uid_here', uid)).ready(function () {
        let item = jQuery('#wgbl-rules .wgbl-rule-item[data-id=' + id + ']');
        item.find('.wgbl-rule-method').val((rule.method) ? rule.method : 'simple').trigger('change');
        item.find('.wgbl-rule-body').slideDown();
        if (!callback) {
            wgblResetData();
        }
        if (callback) {
            callback(item, rule)
        }
    });
}

function wgblSetTipsyTooltip() {
    jQuery('[title]').tipsy({
        html: true,
        arrowWidth: 10, //arrow css border-width * 2, default is 5 * 2
        attr: 'data-tipsy',
        cls: null,
        duration: 150,
        offset: 7,
        position: 'top-center',
        trigger: 'hover',
        onShow: null,
        onHide: null
    });
}

function wgblSelect2Set() {
    if (jQuery.fn.select2) {
        jQuery('.wgbl-select2').select2({
            dropdownAutoWidth: true,
            width: '100%',
            placeholder: {
                id: '-1', // the value of the option
                text: 'Select ...'
            }
        });
        jQuery('.wgbl-select2-grouped').select2({
            dropdownAutoWidth: true,
            width: '100%',
            placeholder: {
                id: '-1', // the value of the option
                text: 'Select ...'
            }
        });
    }
}

function wgblDatepickerSet() {
    if (jQuery.fn.datetimepicker) {
        jQuery('.wgbl-datepicker').datetimepicker({
            timepicker: false,
            format: 'Y-m-d'
        });

        jQuery('.wgbl-timepicker').datetimepicker({
            datepicker: false,
            format: 'H:i'
        });

        jQuery('.wgbl-datetimepicker').datetimepicker({
            format: 'Y-m-d H:i'
        });
    }
}

function wgblSetSortableItems() {
    if (jQuery.fn.sortable) {
        if (jQuery(".wgbl-product-buy-items")) {
            let wgblProductGet = jQuery(".wgbl-product-buy-items");
            wgblProductGet.sortable({
                handle: ".wgbl-rule-item-product-buy-sortable-btn",
                cancel: "",
                stop: function (event, ui) {
                    wgblProductBuyFixPriority(ui.item.closest('.wgbl-rule-item'))
                }
            });
            wgblProductGet.disableSelection();
        }

        if (jQuery(".wgbl-rule-quantities-bulk-quantity-repeatable-items")) {
            let wgblBulkQuantityQuantities = jQuery(".wgbl-rule-quantities-bulk-quantity-repeatable-items");
            wgblBulkQuantityQuantities.sortable({
                handle: ".wgbl-rule-item-quantities-bulk-quantity-row-sortable-btn",
                cancel: "",
                stop: function (event, ui) {
                    wgblBulkQuantityFixPriority(ui.item.closest('.wgbl-rule-item'))
                }
            });
            wgblBulkQuantityQuantities.disableSelection();
        }
        if (jQuery(".wgbl-rule-quantities-bulk-pricing-repeatable-items")) {
            let wgblBulkQuantityQuantities = jQuery(".wgbl-rule-quantities-bulk-pricing-repeatable-items");
            wgblBulkQuantityQuantities.sortable({
                handle: ".wgbl-rule-item-quantities-bulk-pricing-row-sortable-btn",
                cancel: "",
                stop: function (event, ui) {
                    wgblBulkPricingFixPriority(ui.item.closest('.wgbl-rule-item'))
                }
            });
            wgblBulkQuantityQuantities.disableSelection();
        }

        if (jQuery(".wgbl-rule-quantities-tiered-quantity-repeatable-items")) {
            let wgblBulkQuantityQuantities = jQuery(".wgbl-rule-quantities-tiered-quantity-repeatable-items");
            wgblBulkQuantityQuantities.sortable({
                handle: ".wgbl-rule-item-quantities-tiered-quantity-row-sortable-btn",
                cancel: "",
                stop: function (event, ui) {
                    wgblBulkQuantityFixPriority(ui.item.closest('.wgbl-rule-item'))
                }
            });
            wgblBulkQuantityQuantities.disableSelection();
        }

        if (jQuery(".wgbl-condition-items")) {
            let wgblConditions = jQuery(".wgbl-condition-items");
            wgblConditions.sortable({
                handle: ".wgbl-rule-item-condition-sortable-btn",
                cancel: "",
                stop: function (event, ui) {
                    wgblConditionsFixPriority(ui.item.closest('.wgbl-rule-item'))
                }
            });
            wgblConditions.disableSelection();
        }

        if (jQuery("#wgbl-rules")) {
            let wgblRules = jQuery("#wgbl-rules");
            wgblRules.sortable({
                handle: ".wgbl-rule-sortable-btn",
                cancel: "",
                stop: function (event, ui) {
                    wgblRulesFixPriority()
                }
            });
            wgblRules.disableSelection();
        }
    }
}

function wgblRulesFixPriority() {
    let id;
    jQuery('.wgbl-rule-item').each(function (i) {
        id = i;
        jQuery(this).attr('data-id', id);
        jQuery(this).find('select, input').each(function (i) {
            if (typeof jQuery(this).prop('name') !== 'undefined') {
                let newName = jQuery(this).prop('name').replace(new RegExp('rule\\[(\\{i\\}|\\d+)\\]?'), 'rule[' + id + ']');
                jQuery(this).prop('name', newName);
            }
        });
    });
}

function wgblConditionsFixPriority(ruleItem) {
    let id;
    ruleItem.find('.wgbl-condition-items .wgbl-rule-item-sortable-item').each(function (i) {
        id = i;
        jQuery(this).find('select, input').each(function (i) {
            if (typeof jQuery(this).prop('name') !== 'undefined') {
                let newName = jQuery(this).prop('name').replace(new RegExp('\\[condition\\]\\[(\\{i\\}|\\d+)\\]?'), '[condition][' + id + ']');
                jQuery(this).prop('name', newName);
            }
        });
    });
}

function wgblProductBuyFixPriority(ruleItem) {
    let id;
    ruleItem.find('.wgbl-product-buy-items .wgbl-rule-item-sortable-item').each(function (i) {
        id = i;
        jQuery(this).find('select, input').each(function (i) {
            if (typeof jQuery(this).prop('name') !== 'undefined') {
                let newName = jQuery(this).prop('name').replace(new RegExp('\\[product_buy\\]\\[(\\{i\\}|\\d+)\\]?'), '[product_buy][' + id + ']');
                jQuery(this).prop('name', newName);
            }
        });
    });
}
function wgblBulkQuantityFixPriority(ruleItem) {
    let id;
    ruleItem.find('.wgbl-rule-quantities-bulk-quantity-repeatable-items .wgbl-rule-quantities-bulk-quantity-repeatable-item').each(function (i) {
        id = i;
        jQuery(this).find('input').each(function (i) {
            if (typeof jQuery(this).prop('name') !== 'undefined') {
                let newName = jQuery(this).prop('name').replace(new RegExp('\\[quantity\\]\\[items\\]\\[(\\{i\\}|\\d+)\\]?'), '[quantity][items][' + id + ']');
                jQuery(this).prop('name', newName);
            }
        });
    });
}
function wgblBulkPricingFixPriority(ruleItem) {
    let id;
    ruleItem.find('.wgbl-rule-quantities-bulk-pricing-repeatable-items .wgbl-rule-quantities-bulk-pricing-repeatable-item').each(function (i) {
        id = i;
        jQuery(this).find('input').each(function (i) {
            if (typeof jQuery(this).prop('name') !== 'undefined') {
                let newName = jQuery(this).prop('name').replace(new RegExp('\\[pricing\\]\\[items\\]\\[(\\{i\\}|\\d+)\\]?'), '[pricing][items][' + id + ']');
                jQuery(this).prop('name', newName);
            }
        });
    });
}

function wgblRuleDuplicate(ruleItem) {
    jQuery('#wgbl-rules .wgbl-rule-body').slideUp(250);

    ruleItem.clone().appendTo('#wgbl-rules').ready(function () {
        let duplicated = jQuery('#wgbl-rules .wgbl-rule-item').last();
        duplicated.attr('data-id', parseInt(jQuery('.wgbl-rule-item').length) - 1);
        wgblRulesFixPriority();
        let sID = ruleItem.attr('data-id');
        let dID = duplicated.attr('data-id');
        duplicated.find('.wgbl-rule-body').slideDown(250).css({
            height: 'auto'
        });

        let newUid = uidGenerator();

        duplicated.find('.wgbl-rule-method').val(ruleItem.find('.wgbl-rule-method').val()).change();
        duplicated.find('.wgbl-rule-item-status').val(ruleItem.find('.wgbl-rule-item-status').val()).change();
        duplicated.find('input[name="rule[' + dID + '][uid]"]').val(newUid).change();
        duplicated.find('.wgbl-rule-method-id').text('ID: ' + newUid);
        duplicated.find('select[name="rule[' + dID + '][quantities_based_on]"]').val(ruleItem.find('select[name="rule[' + sID + '][quantities_based_on]"]').val()).change();

        if (ruleItem.find('.wgbl-product-buy-items .wgbl-rule-item-sortable-item').length > 0) {
            jQuery.each(ruleItem.find('.wgbl-product-buy-items .wgbl-rule-item-sortable-item'), function (i, item) {
                wgblDuplicateProductBuyItemFields(sID, dID, i);
            });
        }

        wgblDuplicateGetSectionItems(sID, dID);

        if (ruleItem.find('.wgbl-condition-items .wgbl-rule-item-sortable-item').length > 0) {
            jQuery.each(ruleItem.find('.wgbl-condition-items .wgbl-rule-item-sortable-item'), function (i, item) {
                let itemType = duplicated.find('select[name="rule[' + dID + '][condition][' + i + '][type]"]');
                itemType.closest('.wgbl-form-group').find('.select2-container').remove();
                itemType.val(ruleItem.find('select[name="rule[' + sID + '][condition][' + i + '][type]"]').val());
                wgblDuplicateConditionItemFields(sID, dID, i);
            });
        }

        setTimeout(function () {
            wgblResetData();
        }, 50)
    });
}

function wgblDuplicateProductBuyItemFields(sourceId, destinationId, iteration) {
    let itemType = jQuery('select[name="rule[' + destinationId + '][product_buy][' + iteration + '][type]"]');
    itemType.closest('.wgbl-form-group').find('.select2-container').remove();
    itemType.val(jQuery('select[name="rule[' + sourceId + '][product_buy][' + iteration + '][type]"]').val());
    jQuery('select[name="rule[' + destinationId + '][product_buy][' + iteration + '][method_option]"]').val(jQuery('select[name="rule[' + sourceId + '][product_buy][' + iteration + '][method_option]"]').val());
    jQuery('input[name="rule[' + destinationId + '][product_buy][' + iteration + '][product_meta_field]"]').val(jQuery('input[name="rule[' + sourceId + '][product_buy][' + iteration + '][product_meta_field]"]').val());
    jQuery('input[name="rule[' + destinationId + '][product_buy][' + iteration + '][value]"]').val(jQuery('input[name="rule[' + sourceId + '][product_buy][' + iteration + '][value]"]').val());
    jQuery('select[name="rule[' + destinationId + '][product_buy][' + iteration + '][value][]"]').val(jQuery('select[name="rule[' + sourceId + '][product_buy][' + iteration + '][value][]"]').val());

    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][product_buy][' + iteration + '][products][]"]', 'select[name="rule[' + destinationId + '][product_buy][' + iteration + '][products][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][product_buy][' + iteration + '][variations][]"]', 'select[name="rule[' + destinationId + '][product_buy][' + iteration + '][variations][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][product_buy][' + iteration + '][categories][]"]', 'select[name="rule[' + destinationId + '][product_buy][' + iteration + '][categories][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][product_buy][' + iteration + '][attributes][]"]', 'select[name="rule[' + destinationId + '][product_buy][' + iteration + '][attributes][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][product_buy][' + iteration + '][tags][]"]', 'select[name="rule[' + destinationId + '][product_buy][' + iteration + '][tags][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][product_buy][' + iteration + '][coupons][]"]', 'select[name="rule[' + destinationId + '][product_buy][' + iteration + '][coupons][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][product_buy][' + iteration + '][shipping_classes][]"]', 'select[name="rule[' + destinationId + '][product_buy][' + iteration + '][shipping_classes][]"]');
}

function wgblDuplicateGetSectionItems(sourceId, destinationId) {
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][include_products][]"]', 'select[name="rule[' + destinationId + '][include_products][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][exclude_products][]"]', 'select[name="rule[' + destinationId + '][exclude_products][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][include_taxonomy][]"]', 'select[name="rule[' + destinationId + '][include_taxonomy][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][exclude_taxonomy][]"]', 'select[name="rule[' + destinationId + '][exclude_taxonomy][]"]');
}

function wgblDuplicateConditionItemFields(sourceId, destinationId, iteration) {
    jQuery('select[name="rule[' + destinationId + '][condition][' + iteration + '][method_option]"]').val(jQuery('select[name="rule[' + sourceId + '][condition][' + iteration + '][method_option]"]').val()).trigger('change');
    jQuery('input[name="rule[' + destinationId + '][condition][' + iteration + '][value]"]').val(jQuery('input[name="rule[' + sourceId + '][condition][' + iteration + '][value]"]').val()).trigger('change');
    jQuery('input[name="rule[' + destinationId + '][condition][' + iteration + '][meta_field_key]"]').val(jQuery('input[name="rule[' + sourceId + '][condition][' + iteration + '][meta_field_key]"]').val()).trigger('change');
    jQuery('select[name="rule[' + destinationId + '][condition][' + iteration + '][time]"]').val(jQuery('select[name="rule[' + sourceId + '][condition][' + iteration + '][time]"]').val()).trigger('change');
    jQuery('select[name="rule[' + destinationId + '][condition][' + iteration + '][value][]"]').val(jQuery('select[name="rule[' + sourceId + '][condition][' + iteration + '][value][]"]').val()).trigger('change');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][condition][' + iteration + '][products][]"]', 'select[name="rule[' + destinationId + '][condition][' + iteration + '][products][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][condition][' + iteration + '][variations][]"]', 'select[name="rule[' + destinationId + '][condition][' + iteration + '][variations][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][condition][' + iteration + '][categories][]"]', 'select[name="rule[' + destinationId + '][condition][' + iteration + '][categories][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][condition][' + iteration + '][attributes][]"]', 'select[name="rule[' + destinationId + '][condition][' + iteration + '][attributes][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][condition][' + iteration + '][tags][]"]', 'select[name="rule[' + destinationId + '][condition][' + iteration + '][tags][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][condition][' + iteration + '][shipping_classes][]"]', 'select[name="rule[' + destinationId + '][condition][' + iteration + '][shipping_classes][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][condition][' + iteration + '][customers][]"]', 'select[name="rule[' + destinationId + '][condition][' + iteration + '][customers][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][condition][' + iteration + '][user_roles][]"]', 'select[name="rule[' + destinationId + '][condition][' + iteration + '][user_roles][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][condition][' + iteration + '][user_capabilities][]"]', 'select[name="rule[' + destinationId + '][condition][' + iteration + '][user_capabilities][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][condition][' + iteration + '][coupons][]"]', 'select[name="rule[' + destinationId + '][condition][' + iteration + '][coupons][]"]');
    wgblDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][condition][' + iteration + '][payment_methods][]"]', 'select[name="rule[' + destinationId + '][condition][' + iteration + '][payment_methods][]"]');
}

function wgblDuplicateMultipleSelect2Item(sourceRuleName, destinationRuleName) {
    jQuery(destinationRuleName).closest('.wgbl-form-group').find('.select2-container').remove();
    if (jQuery(sourceRuleName).val() && jQuery(destinationRuleName).find('option:selected').length < 1) {
        jQuery(sourceRuleName).find('option:selected').each(function () {
            jQuery(destinationRuleName).append("<option value='" + jQuery(this).attr('value') + "' selected='selected'>" + jQuery(this).text() + "</option>")
        });
    }
}

function wgblShowQuantitiesBasedOn(id) {
    let quantitiesBasedOn = jQuery('.wgbl-rule-item[data-id=' + id + ']').find('div[data-type=quantities-based-on]');
    quantitiesBasedOn.find('select').prop('disabled', false);
    quantitiesBasedOn.show();
}

function wgblHideQuantitiesBasedOn(id) {
    let quantitiesBasedOn = jQuery('.wgbl-rule-item[data-id=' + id + ']').find('div[data-type=quantities-based-on]');
    quantitiesBasedOn.find('select').prop('disabled', true);
    quantitiesBasedOn.hide();
}

function wgblSettingsDependenciesController(type) {
    let allDependencies = jQuery('#wgbl-settings-view-gift-in-cart-dependencies');
    // hide all dependencies
    allDependencies.find('.wgbl-settings-dependency-item').hide();

    if (type) {
        let dependencies = allDependencies.find('div[data-type="' + type + '"]');
        // show selected dependencies
        dependencies.show();
    }
}

function wgblCheckForUpdate() {
    jQuery('.wgbl-check-for-update-loading').css({ display: 'inline-block' });
    jQuery.ajax({
        url: WGBL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wgbl_check_for_update',
            nonce: WGBL_DATA.ajax_nonce,
        },
        success: function (response) {
            jQuery('.wgbl-check-for-update-loading').hide();
            if (response.has_update == 'yes') {
                if (response.license_is_valid == 'yes') {
                    jQuery('.wgbl-check-for-update').html('<a href="' + response.update_link + '" class="wgbl-button-blue wgbl-button-blue wgbl-update-button">Update Now</a><strong class="wgbl-new-version">New version: ' + response.new_version + '</strong>')
                } else {
                    swal({
                        title: ' Please purchase Pro version and then activate your plugin.',
                        type: "warning"
                    });
                }
            } else {
                swal({
                    title: 'Your plugin is up to date',
                    type: "success"
                });
            }
        },
        error: function () {
            jQuery('.wgbl-check-for-update-loading').hide();
            swal({
                title: 'Error! Try again',
                type: "warning"
            });
        }
    });
}