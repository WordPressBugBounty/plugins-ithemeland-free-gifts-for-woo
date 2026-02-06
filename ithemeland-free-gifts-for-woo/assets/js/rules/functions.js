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

function wgbGetCustomers() {
    let query;
    jQuery(".wgb-select2-customers").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgb_get_customers",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Customer Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wgbGetPaymentMethods() {
    let query;
    jQuery(".wgb-select2-payment-methods").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgb_get_payment_methods",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Select ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wgbGetShippingCountry() {
    let query;
    jQuery(".wgb-select2-shipping-country").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgb_get_shipping_country",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Select ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wgbGetUserRoles() {
    let query;
    jQuery(".wgb-select2-user-roles").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgb_get_user_roles",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Role Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wgbGetUserCapabilities() {
    let query;
    jQuery(".wgb-select2-user-capabilities").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgb_get_user_capabilities",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wgbGetProducts() {
    let query;
    jQuery(".wgb-select2-products").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgb_get_products",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Product Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wgbGetProductsVariations() {
    let query;
    jQuery(".wgb-select2-products-variations").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgb_get_products_variations",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Product Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wgbGetVariations() {
    let query;
    jQuery(".wgb-select2-variations").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgb_get_variations",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Variation Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wgbGetTags() {
    let query;
    jQuery(".wgb-select2-tags").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgb_get_tags",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Tag Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wgbGetCategories() {
    let query;
    jQuery(".wgb-select2-categories").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgb_get_categories",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Category Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wgbGetAttributes() {
    let query;
    jQuery(".wgb-select2-attributes").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgb_get_attributes",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Term Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wgbGetTaxonomies() {
    let query;
    jQuery(".wgb-select2-taxonomies").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgb_get_taxonomies",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Taxonomy Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wgbGetShippingClass() {
    let query;
    jQuery(".wgb-select2-shipping_classes").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgb_get_shipping_class",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Product Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wgbGetCoupons() {
    let query;
    jQuery(".wgb-select2-coupons").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgb_get_coupons",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Coupon Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wgbGetBrands() {
    let query;
    jQuery(".wgb-select2-brands").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WGBL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wgb_get_brands",
                    nonce: WGBL_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Brand Name ...",
        minimumInputLength: 1,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wgbResetData() {
    wgbGetCustomers();
    wgbGetPaymentMethods();
    wgbGetShippingCountry();
    wgbGetUserRoles();
    wgbGetUserCapabilities();
    wgbGetProducts();
    wgbGetTaxonomies();
    wgbGetProductsVariations();
    wgbGetVariations();
    wgbGetTags();
    wgbGetCategories();
    wgbGetAttributes();
    wgbGetShippingClass();
    wgbGetCoupons();
    wgbGetBrands();
    wgbSetSortableItems();
    wgbDatepickerSet();
    wgbSelect2Set();
}

function wgbSetOptionValues(values) {
    optionValues = values;
}

function uidGenerator() {
    return Date.now() + Math.floor(Math.random() * 999 + 100);
}

function wgbAddRule(id, rule = [], callback = "") {
    let uid = rule.uid ? rule.uid : uidGenerator();

    jQuery("#wgb-rules")
        .append(WGBL_RULES_DATA.new_rule.replaceAll("set_rule_id_here", id).replaceAll("set_uid_here", uid))
        .ready(function () {
            let item = jQuery("#wgb-rules .wgb-rule-item[data-id=" + id + "]");
            item
                .find(".wgb-rule-method")
                .val(rule.method ? rule.method : "simple")
                .trigger("change");
            item.find(".wgb-rule-body").slideDown();
            if (!callback) {
                wgbResetData();
            }
            if (callback) {
                callback(item, rule);
            }
        });
}

function wgbSetTipsyTooltip() {
    jQuery("[title]").tipsy({
        html: true,
        arrowWidth: 10, //arrow css border-width * 2, default is 5 * 2
        attr: "data-tipsy",
        cls: null,
        duration: 150,
        offset: 7,
        position: "top-center",
        trigger: "hover",
        onShow: null,
        onHide: null,
    });
}

function wgbSelect2Set() {
    if (jQuery.fn.select2) {
        jQuery(".wgb-select2").select2({
            dropdownAutoWidth: true,
            width: "100%",
            placeholder: {
                id: "-1", // the value of the option
                text: "Select ...",
            },
        });
        jQuery(".wgb-select2-grouped").select2({
            dropdownAutoWidth: true,
            width: "100%",
            placeholder: {
                id: "-1", // the value of the option
                text: "Select ...",
            },
        });
    }
}

function wgbDatepickerSet() {
    if (jQuery.fn.datetimepicker) {
        jQuery(".wgb-datepicker").datetimepicker({
            timepicker: false,
            format: "Y-m-d",
        });

        jQuery(".wgb-timepicker").datetimepicker({
            datepicker: false,
            format: "H:i",
        });

        jQuery(".wgb-datetimepicker").datetimepicker({
            format: "Y-m-d H:i",
        });
    }
}

function wgbSetSortableItems() {
    if (!jQuery.fn.sortable) return;

    const dragSelectionControl = {
        start: function () {
            jQuery(this).css("user-select", "none");
        },
        stop: function () {
            jQuery(this).css("user-select", "");
        },
    };

    const sortableSections = [
        {
            selector: ".wgb-product-buy-items",
            handle: ".wgb-rule-item-product-buy-sortable-btn",
            callback: function (event, ui) {
                wgbProductBuyFixPriority(ui.item.closest(".wgb-rule-item"));
            },
        },
        {
            selector: ".wgb-rule-quantities-bulk-quantity-repeatable-items",
            handle: ".wgb-rule-item-quantities-bulk-quantity-row-sortable-btn",
            callback: function (event, ui) {
                wgbBulkQuantityFixPriority(ui.item.closest(".wgb-rule-item"));
            },
        },
        {
            selector: ".wgb-rule-quantities-bulk-pricing-repeatable-items",
            handle: ".wgb-rule-item-quantities-bulk-pricing-row-sortable-btn",
            callback: function (event, ui) {
                wgbBulkPricingFixPriority(ui.item.closest(".wgb-rule-item"));
            },
        },
        {
            selector: ".wgb-rule-quantities-tiered-quantity-repeatable-items",
            handle: ".wgb-rule-item-quantities-tiered-quantity-row-sortable-btn",
            callback: function (event, ui) {
                wgbBulkQuantityFixPriority(ui.item.closest(".wgb-rule-item"));
            },
        },
        {
            selector: ".wgb-condition-items",
            handle: ".wgb-rule-item-condition-sortable-btn",
            callback: function (event, ui) {
                wgbConditionsFixPriority(ui.item.closest(".wgb-rule-item"));
            },
        },
        {
            selector: ".wgb-rule-products-group-items",
            handle: ".wgb-rule-item-get-products-group-item-sortable-btn",
            callback: function (event, ui) {
                wgbGetProductsGroupFixPriority(ui.item.closest(".wgb-rule-item"));
            },
        },
        {
            selector: "#wgb-rules",
            handle: ".wgb-rule-sortable-btn",
            callback: function (event, ui) {
                wgbRulesFixPriority();
            },
        },
    ];

    sortableSections.forEach((section) => {
        const element = jQuery(section.selector);
        if (element.length) {
            element.sortable({
                handle: section.handle,
                cancel: "",
                ...dragSelectionControl,
                stop: function (event, ui) {
                    dragSelectionControl.stop.call(this);
                    section.callback(event, ui);
                },
            });
        }
    });
}

function wgbRulesFixPriority() {
    let id;
    jQuery(".wgb-rule-item").each(function (i) {
        id = i;
        jQuery(this).attr("data-id", id);
        jQuery(this)
            .find("select, input")
            .each(function (i) {
                if (typeof jQuery(this).prop("name") !== "undefined") {
                    let newName = jQuery(this)
                        .prop("name")
                        .replace(new RegExp("rule\\[(\\{i\\}|\\d+)\\]?"), "rule[" + id + "]");
                    jQuery(this).prop("name", newName);
                }
            });
    });
}

function wgbConditionsFixPriority(ruleItem) {
    let id;
    ruleItem.find(".wgb-condition-items .wgb-rule-item-sortable-item").each(function (i) {
        id = i;
        jQuery(this)
            .find("select, input")
            .each(function (i) {
                if (typeof jQuery(this).prop("name") !== "undefined") {
                    let newName = jQuery(this)
                        .prop("name")
                        .replace(new RegExp("\\[condition\\]\\[(\\{i\\}|\\d+)\\]?"), "[condition][" + id + "]");
                    jQuery(this).prop("name", newName);
                }
            });
    });
}

function wgbProductBuyFixPriority(ruleItem) {
    let id;
    ruleItem.find(".wgb-product-buy-items .wgb-rule-item-sortable-item").each(function (i) {
        id = i;
        jQuery(this)
            .find("select, input")
            .each(function (i) {
                if (typeof jQuery(this).prop("name") !== "undefined") {
                    let newName = jQuery(this)
                        .prop("name")
                        .replace(new RegExp("\\[product_buy\\]\\[(\\{i\\}|\\d+)\\]?"), "[product_buy][" + id + "]");
                    jQuery(this).prop("name", newName);
                }
            });
    });
}
function wgbBulkQuantityFixPriority(ruleItem) {
    let id;
    ruleItem.find(".wgb-rule-quantities-bulk-quantity-repeatable-items .wgb-rule-quantities-bulk-quantity-repeatable-item").each(function (i) {
        id = i;
        jQuery(this)
            .find("input")
            .each(function (i) {
                if (typeof jQuery(this).prop("name") !== "undefined") {
                    let newName = jQuery(this)
                        .prop("name")
                        .replace(new RegExp("\\[quantity\\]\\[items\\]\\[(\\{i\\}|\\d+)\\]?"), "[quantity][items][" + id + "]");
                    jQuery(this).prop("name", newName);
                }
            });
    });
}
function wgbBulkPricingFixPriority(ruleItem) {
    let id;
    ruleItem.find(".wgb-rule-quantities-bulk-pricing-repeatable-items .wgb-rule-quantities-bulk-pricing-repeatable-item").each(function (i) {
        id = i;
        jQuery(this)
            .find("input")
            .each(function (i) {
                if (typeof jQuery(this).prop("name") !== "undefined") {
                    let newName = jQuery(this)
                        .prop("name")
                        .replace(new RegExp("\\[pricing\\]\\[items\\]\\[(\\{i\\}|\\d+)\\]?"), "[pricing][items][" + id + "]");
                    jQuery(this).prop("name", newName);
                }
            });
    });
}

function wgbRuleDuplicate(ruleItem) {
    jQuery("#wgb-rules .wgb-rule-body").slideUp(250);

    ruleItem.clone().appendTo("#wgb-rules").ready(function () {
        let duplicated = jQuery("#wgb-rules .wgb-rule-item").last();
        duplicated.attr("data-id", parseInt(jQuery(".wgb-rule-item").length) - 1);
        wgbRulesFixPriority();
        let sID = ruleItem.attr("data-id");
        let dID = duplicated.attr("data-id");
        duplicated.find(".wgb-rule-body").slideDown(250).css({
            height: "auto",
        });

        let newUid = uidGenerator();

        duplicated.find(".wgb-rule-method").val(ruleItem.find(".wgb-rule-method").val()).change();
        duplicated.find(".wgb-rule-item-status").val(ruleItem.find(".wgb-rule-item-status").val()).change();
        duplicated.find('input[name="rule[' + dID + '][uid]"]').val(newUid).change();
        duplicated.find(".wgb-rule-method-id").text("ID: " + newUid);
        duplicated.find('select[name="rule[' + dID + '][quantities_based_on]"]').val(ruleItem.find('select[name="rule[' + sID + '][quantities_based_on]"]').val()).change();

        if (duplicated.find('.wgb-product-group-operator').length) {
            duplicated.find('select[name="rule[' + dID + '][quantity][operator]"]').val(ruleItem.find('select[name="rule[' + sID + '][quantity][operator]"]').val()).change();
        }

        if (ruleItem.find(".wgb-product-buy-items .wgb-rule-item-sortable-item").length > 0) {
            jQuery.each(ruleItem.find(".wgb-product-buy-items .wgb-rule-item-sortable-item"), function (i, item) {
                wgbDuplicateProductBuyItemFields(sID, dID, i);
            });
        }

        wgbDuplicateGetSectionItems(sID, dID);

        if (ruleItem.find(".wgb-condition-items .wgb-rule-item-sortable-item").length > 0) {
            jQuery.each(ruleItem.find(".wgb-condition-items .wgb-rule-item-sortable-item"), function (i, item) {
                let itemType = duplicated.find('select[name="rule[' + dID + "][condition][" + i + '][type]"]');
                itemType.closest(".wgb-form-group").find(".select2-container").remove();
                itemType.val(ruleItem.find('select[name="rule[' + sID + "][condition][" + i + '][type]"]').val());
                wgbDuplicateConditionItemFields(sID, dID, i);
            });
        }

        if (ruleItem.find(".wgb-rule-products-group-items .wgb-rule-products-group-item").length > 0) {
            jQuery.each(ruleItem.find(".wgb-rule-products-group-items .wgb-rule-products-group-item"), function (i, item) {
                let itemType = duplicated.find('select[name="rule[' + dID + "][get_products_group][" + i + '][type]"]');
                itemType.closest(".wgb-form-group").find(".select2-container").remove();
                wgbDuplicateGetProductsGroupItemFields(sID, dID, i);
            });
        }

        if (ruleItem.find('.wgb-rule-section-content[data-method-type="free_shipping"]').length > 0) {
            let itemType = duplicated.find('select[name="rule[' + dID + '][quantity][free_shipping_methods][]"]');
            itemType.closest(".wgb-form-group").find(".select2-container").remove();
        }

        setTimeout(function () {
            wgbResetData();
        }, 50);
    });
}

function wgbDuplicateGetProductsGroupItemFields(sourceId, destinationId, iteration) {
    jQuery('select[name="rule[' + destinationId + "][get_products_group][" + iteration + '][quantity]"]').val(jQuery('select[name="rule[' + sourceId + "][get_products_group][" + iteration + '][quantity]"]').val()).trigger("change");
    jQuery('input[name="rule[' + destinationId + "][get_products_group][" + iteration + '][type]"]').val(jQuery('input[name="rule[' + sourceId + "][get_products_group][" + iteration + '][type]"]').val()).trigger("change");

    wgbDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + "][get_products_group][" + iteration + '][value][]"]', 'select[name="rule[' + destinationId + "][get_products_group][" + iteration + '][value][]"]');
}

function wgbDuplicateProductBuyItemFields(sourceId, destinationId, iteration) {
    let itemType = jQuery('select[name="rule[' + destinationId + "][product_buy][" + iteration + '][type]"]');
    itemType.closest(".wgb-form-group").find(".select2-container").remove();
    itemType.val(jQuery('select[name="rule[' + sourceId + "][product_buy][" + iteration + '][type]"]').val());
    jQuery('select[name="rule[' + destinationId + "][product_buy][" + iteration + '][method_option]"]').val(
        jQuery('select[name="rule[' + sourceId + "][product_buy][" + iteration + '][method_option]"]').val()
    );
    jQuery('input[name="rule[' + destinationId + "][product_buy][" + iteration + '][product_meta_field]"]').val(
        jQuery('input[name="rule[' + sourceId + "][product_buy][" + iteration + '][product_meta_field]"]').val()
    );
    jQuery('input[name="rule[' + destinationId + "][product_buy][" + iteration + '][value]"]').val(jQuery('input[name="rule[' + sourceId + "][product_buy][" + iteration + '][value]"]').val());
    jQuery('select[name="rule[' + destinationId + "][product_buy][" + iteration + '][value][]"]').val(jQuery('select[name="rule[' + sourceId + "][product_buy][" + iteration + '][value][]"]').val());

    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][product_buy][" + iteration + '][products][]"]',
        'select[name="rule[' + destinationId + "][product_buy][" + iteration + '][products][]"]'
    );
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][product_buy][" + iteration + '][variations][]"]',
        'select[name="rule[' + destinationId + "][product_buy][" + iteration + '][variations][]"]'
    );
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][product_buy][" + iteration + '][categories][]"]',
        'select[name="rule[' + destinationId + "][product_buy][" + iteration + '][categories][]"]'
    );
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][product_buy][" + iteration + '][attributes][]"]',
        'select[name="rule[' + destinationId + "][product_buy][" + iteration + '][attributes][]"]'
    );
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][product_buy][" + iteration + '][tags][]"]',
        'select[name="rule[' + destinationId + "][product_buy][" + iteration + '][tags][]"]'
    );
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][product_buy][" + iteration + '][coupons][]"]',
        'select[name="rule[' + destinationId + "][product_buy][" + iteration + '][coupons][]"]'
    );
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][product_buy][" + iteration + '][shipping_classes][]"]',
        'select[name="rule[' + destinationId + "][product_buy][" + iteration + '][shipping_classes][]"]'
    );
}

function wgbDuplicateGetSectionItems(sourceId, destinationId) {
    wgbDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][include_products][]"]', 'select[name="rule[' + destinationId + '][include_products][]"]');
    wgbDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][exclude_products][]"]', 'select[name="rule[' + destinationId + '][exclude_products][]"]');
    wgbDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][include_taxonomy][]"]', 'select[name="rule[' + destinationId + '][include_taxonomy][]"]');
    wgbDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][exclude_taxonomy][]"]', 'select[name="rule[' + destinationId + '][exclude_taxonomy][]"]');
}

function wgbDuplicateConditionItemFields(sourceId, destinationId, iteration) {
    jQuery('select[name="rule[' + destinationId + "][condition][" + iteration + '][method_option]"]')
        .val(jQuery('select[name="rule[' + sourceId + "][condition][" + iteration + '][method_option]"]').val())
        .trigger("change");
    jQuery('input[name="rule[' + destinationId + "][condition][" + iteration + '][value]"]')
        .val(jQuery('input[name="rule[' + sourceId + "][condition][" + iteration + '][value]"]').val())
        .trigger("change");
    jQuery('input[name="rule[' + destinationId + "][condition][" + iteration + '][meta_field_key]"]')
        .val(jQuery('input[name="rule[' + sourceId + "][condition][" + iteration + '][meta_field_key]"]').val())
        .trigger("change");
    jQuery('select[name="rule[' + destinationId + "][condition][" + iteration + '][time]"]')
        .val(jQuery('select[name="rule[' + sourceId + "][condition][" + iteration + '][time]"]').val())
        .trigger("change");
    jQuery('select[name="rule[' + destinationId + "][condition][" + iteration + '][value][]"]')
        .val(jQuery('select[name="rule[' + sourceId + "][condition][" + iteration + '][value][]"]').val())
        .trigger("change");
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][condition][" + iteration + '][products][]"]',
        'select[name="rule[' + destinationId + "][condition][" + iteration + '][products][]"]'
    );
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][condition][" + iteration + '][variations][]"]',
        'select[name="rule[' + destinationId + "][condition][" + iteration + '][variations][]"]'
    );
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][condition][" + iteration + '][categories][]"]',
        'select[name="rule[' + destinationId + "][condition][" + iteration + '][categories][]"]'
    );
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][condition][" + iteration + '][attributes][]"]',
        'select[name="rule[' + destinationId + "][condition][" + iteration + '][attributes][]"]'
    );
    wgbDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + "][condition][" + iteration + '][tags][]"]', 'select[name="rule[' + destinationId + "][condition][" + iteration + '][tags][]"]');
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][condition][" + iteration + '][shipping_classes][]"]',
        'select[name="rule[' + destinationId + "][condition][" + iteration + '][shipping_classes][]"]'
    );
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][condition][" + iteration + '][customers][]"]',
        'select[name="rule[' + destinationId + "][condition][" + iteration + '][customers][]"]'
    );
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][condition][" + iteration + '][user_roles][]"]',
        'select[name="rule[' + destinationId + "][condition][" + iteration + '][user_roles][]"]'
    );
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][condition][" + iteration + '][user_capabilities][]"]',
        'select[name="rule[' + destinationId + "][condition][" + iteration + '][user_capabilities][]"]'
    );
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][condition][" + iteration + '][coupons][]"]',
        'select[name="rule[' + destinationId + "][condition][" + iteration + '][coupons][]"]'
    );
    wgbDuplicateMultipleSelect2Item(
        'select[name="rule[' + sourceId + "][condition][" + iteration + '][payment_methods][]"]',
        'select[name="rule[' + destinationId + "][condition][" + iteration + '][payment_methods][]"]'
    );
}

function wgbDuplicateMultipleSelect2Item(sourceRuleName, destinationRuleName) {
    jQuery(destinationRuleName).closest(".wgb-form-group").find(".select2-container").remove();
    if (jQuery(sourceRuleName).val() && jQuery(destinationRuleName).find("option:selected").length < 1) {
        jQuery(sourceRuleName)
            .find("option:selected")
            .each(function () {
                jQuery(destinationRuleName).append("<option value='" + jQuery(this).attr("value") + "' selected='selected'>" + jQuery(this).text() + "</option>");
            });
    }
}

function wgbShowQuantitiesBasedOn(id) {
    let quantitiesBasedOn = jQuery(".wgb-rule-item[data-id=" + id + "]").find("div[data-type=quantities-based-on]");
    quantitiesBasedOn.find("select").prop("disabled", false);
    quantitiesBasedOn.show();
}

function wgbHideQuantitiesBasedOn(id) {
    let quantitiesBasedOn = jQuery(".wgb-rule-item[data-id=" + id + "]").find("div[data-type=quantities-based-on]");
    quantitiesBasedOn.find("select").prop("disabled", true);
    quantitiesBasedOn.hide();
}

function wgbSettingsDependenciesController(type) {
    let allDependencies = jQuery("#wgb-settings-view-gift-in-cart-dependencies");
    // hide all dependencies
    allDependencies.find(".wgb-settings-dependency-item").hide();

    if (type) {
        let dependencies = allDependencies.find('div[data-type="' + type + '"]');
        // show selected dependencies
        dependencies.show();
    }
}

function wgbCheckForUpdate() {
    jQuery(".wgb-check-for-update-loading").css({ display: "inline-block" });
    jQuery.ajax({
        url: WGBL_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wgb_check_for_update",
            nonce: WGBL_DATA.ajax_nonce,
        },
        success: function (response) {
            jQuery(".wgb-check-for-update-loading").hide();
            if (response.has_update == "yes") {
                if (response.license_is_valid == "yes") {
                    jQuery(".wgb-check-for-update").html(
                        '<a href="' +
                        response.update_link +
                        '" class="wgb-button-blue wgb-button-blue wgb-update-button">Update Now</a><strong class="wgb-new-version">New version: ' +
                        response.new_version +
                        "</strong>"
                    );
                } else {
                    swal({
                        title: " Please purchase Pro version and then activate your plugin.",
                        type: "warning",
                    });
                }
            } else {
                swal({
                    title: "Your plugin is up to date",
                    type: "success",
                });
            }
        },
        error: function () {
            jQuery(".wgb-check-for-update-loading").hide();
            swal({
                title: "Error! Try again",
                type: "warning",
            });
        },
    });
}

function wgbShowGetGroupOfProductsItems(id) {
    let quantitiesAndSettings = jQuery(".wgb-rule-item[data-id=" + id + "]").find('.wgb-rule-section-content[data-method-type="get_group_of_products"]');
    let getProductsGroupItems = jQuery(".wgb-rule-item[data-id=" + id + "]").find('.wgb-col-12[data-type="get_products_group"]');
    quantitiesAndSettings.find("select").prop("disabled", false);
    quantitiesAndSettings.find("input").prop("disabled", false);
    getProductsGroupItems.find("select").prop("disabled", false);
    getProductsGroupItems.find("input").prop("disabled", false);
    getProductsGroupItems.show();
    quantitiesAndSettings.show();
}

function wgbHideGetGroupOfProductsItems(id) {
    let quantitiesAndSettings = jQuery(".wgb-rule-item[data-id=" + id + "]").find('.wgb-rule-section-content[data-method-type="get_group_of_products"]');
    let getProductsGroupItems = jQuery(".wgb-rule-item[data-id=" + id + "]").find('.wgb-col-12[data-type="get_products_group"]');
    quantitiesAndSettings.find("select").prop("disabled", true);
    quantitiesAndSettings.find("input").prop("disabled", true);
    getProductsGroupItems.find("select").prop("disabled", true);
    getProductsGroupItems.find("input").prop("disabled", true);
    getProductsGroupItems.hide();
    quantitiesAndSettings.hide();
}