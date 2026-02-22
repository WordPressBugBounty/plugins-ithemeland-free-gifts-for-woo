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
        placeholder: "Term Name ...",
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
            selector: ".wgb-condition-items",
            handle: ".wgb-offer-rule-item-condition-sortable-btn",
            callback: function (event, ui) {
                wgbConditionsFixPriority(ui.item.closest(".wgb-offer-rule-item"));
            },
        },
        {
            selector: "#wgb-offer-rules",
            handle: ".wgb-offer-rule-sortable-btn",
            callback: function (event, ui) {
                wgbOfferRulesFixPriority();
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

function wgbConditionsFixPriority(ruleItem) {
    let id;
    ruleItem.find(".wgb-condition-items .wgb-offer-rule-item-sortable-item").each(function (i) {
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

function wgbDuplicateGetSectionItems(sourceId, destinationId) {
    wgbDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][include_products][]"]', 'select[name="rule[' + destinationId + '][include_products][]"]');
    wgbDuplicateMultipleSelect2Item('select[name="rule[' + sourceId + '][include_taxonomy][]"]', 'select[name="rule[' + destinationId + '][include_taxonomy][]"]');
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
        'select[name="rule[' + sourceId + "][condition][" + iteration + '][brands][]"]',
        'select[name="rule[' + destinationId + "][condition][" + iteration + '][brands][]"]'
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
    let quantitiesBasedOn = jQuery(".wgb-offer-rule-item[data-id=" + id + "]").find("div[data-type=quantities-based-on]");
    quantitiesBasedOn.find("select").prop("disabled", false);
    quantitiesBasedOn.show();
}

function wgbHideQuantitiesBasedOn(id) {
    let quantitiesBasedOn = jQuery(".wgb-offer-rule-item[data-id=" + id + "]").find("div[data-type=quantities-based-on]");
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


function wgbAddOfferRule(id, rule = [], callback = "") {
    let uid = rule.uid ? rule.uid : uidGenerator();

    jQuery("#wgb-offer-rules").append(WGBL_OFFER_RULES_DATA.new_rule.replaceAll("set_rule_id_here", id).replaceAll("set_uid_here", uid)).ready(function () {
        let item = jQuery("#wgb-offer-rules .wgb-offer-rule-item[data-id=" + id + "]");
        item
            .find(".wgb-offer-rule-method")
            .val(rule.method ? rule.method : "simple")
            .trigger("change");
        item.find(".wgb-offer-rule-body").slideDown();
        if (!callback) {
            wgbOfferRuleResetData();
        }
        if (callback) {
            callback(item, rule);
        }
    });
}

function wgbOfferRuleDuplicate(ruleItem) {
    jQuery("#wgb-offer-rules .wgb-offer-rule-body").slideUp(250);

    ruleItem.clone().appendTo("#wgb-offer-rules").ready(function () {
        let duplicated = jQuery("#wgb-offer-rules .wgb-offer-rule-item").last();
        duplicated.attr("data-id", parseInt(jQuery(".wgb-offer-rule-item").length) - 1);
        wgbOfferRulesFixPriority();
        let sID = ruleItem.attr("data-id");
        let dID = duplicated.attr("data-id");
        duplicated.find(".wgb-offer-rule-body").slideDown(250).css({
            height: "auto",
        });

        let newUid = uidGenerator();

        duplicated.find(".wgb-offer-rule-type").val(ruleItem.find(".wgb-offer-rule-type").val()).change();
        duplicated.find('input[name="rule[' + dID + '][uid]"]').val(newUid).change();
        duplicated.find(".wgb-offer-rule-id").text("ID: " + newUid);

        wgbDuplicateGetSectionItems(sID, dID);

        if (ruleItem.find(".wgb-condition-items .wgb-offer-rule-item-sortable-item").length > 0) {
            jQuery.each(ruleItem.find(".wgb-condition-items .wgb-offer-rule-item-sortable-item"), function (i, item) {
                let itemType = duplicated.find('select[name="rule[' + dID + "][condition][" + i + '][type]"]');
                itemType.closest(".wgb-form-group").find(".select2-container").remove();
                itemType.val(ruleItem.find('select[name="rule[' + sID + "][condition][" + i + '][type]"]').val());
                wgbDuplicateConditionItemFields(sID, dID, i);
            });
        }

        setTimeout(function () {
            wgbOfferRuleResetData();
        }, 50);
    });
}

function wgbOfferRuleResetData() {
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

function wgbOfferRulesFixPriority() {
    let id;
    jQuery(".wgb-offer-rule-item").each(function (i) {
        id = i;
        jQuery(this).attr("data-id", id);
        jQuery(this)
            .find("select, input, textarea")
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
    ruleItem.find(".wgb-condition-items .wgb-offer-rule-item-sortable-item").each(function (i) {
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