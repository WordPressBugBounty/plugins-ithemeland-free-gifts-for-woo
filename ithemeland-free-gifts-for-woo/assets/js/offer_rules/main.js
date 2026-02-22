jQuery(document).ready(function ($) {
    "use strict";

    $(document).on('click', '.wgb-add-offer-rule', function () {
        wgbAddOfferRule(parseInt($(".wgb-offer-rule-item").length));
        $(".wgb-empty-rules-box").hide();
    });

    $(document).on("click", ".wgb-offer-rule-header", function (e) {
        let item = $(this).closest(".wgb-offer-rule-item");
        item.find(".wgb-offer-rule-body").slideToggle(250);
        $(".wgb-offer-rule-item").each(function () {
            if ($(this).attr("data-id") !== item.attr("data-id")) {
                $(this).find(".wgb-offer-rule-body").slideUp(250);
            }
        });
    });

    $(document).on("click", ".wgb-offer-rule-delete", function () {
        let ruleItem = $(this).closest(".wgb-offer-rule-item");
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
                    if ($(".wgb-offer-rule-item").length < 1) {
                        $(".wgb-empty-rules-box").show();
                    }
                    wgbOfferRulesFixPriority();
                }
            }
        );
        return false;
    });

    $(document).on("click", ".wgb-offer-rule-duplicate", function () {
        wgbOfferRuleDuplicate($(this).closest(".wgb-offer-rule-item"));
        return false;
    });

    $(document).on("change", ".wgb-offer-rule-type", function () {
        $(this).closest('.wgb-offer-rule-item').find('.wgb-offer-rule-type-name').html($(this).find('option[value="' + $(this).val() + '"]').text());
        $(this).closest('.wgb-offer-rule-item').find('.wgb-offer-rules-type-dependency-item').hide().find('input, textarea, select').prop('disabled', true);
        $(this).closest('.wgb-offer-rule-item').find('.wgb-offer-rules-type-dependency-item[data-type="' + $(this).val() + '"]').show().find('input, textarea, select').prop('disabled', false);
    });

    $(document).on("click", "#wgb-offer-rules-save-changes", function () {
        let message = "Red fields is required !";
        let getSectionItems = jQuery('div[data-type="get"]:visible').find(".wgb-offer-rule-section");
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
            brands: {},
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

        jQuery("#wgb-offer-rules input:required").filter(function () {
            if (!this.disabled && !this.value) {
                jQuery(this).closest(".wgb-offer-rule-item").addClass("wgb-offer-rule-error");
                return true;
            }
        }).addClass("wgb-validation-error-field");

        let select2Items = [];
        jQuery("#wgb-offer-rules select:required").filter(function () {
            if ($(this).attr("data-type") === "select2" && !this.value && !this.disabled) {
                select2Items.push($(this).attr("data-select2-id"));
            }
            return !this.disabled && !this.value;
        }).addClass("wgb-validation-error-field");

        if (select2Items.length > 0) {
            jQuery.each(select2Items, function (key, val) {
                let select2Id = parseInt(val) + 1;
                jQuery('.select2-container[data-select2-id="' + select2Id + '"]').addClass("wgb-validation-error-field");
                jQuery('.select2-container[data-select2-id="' + select2Id + '"]').closest(".wgb-offer-rule-item").addClass("wgb-offer-rule-error");
            });
        }

        // get section validation
        getSectionItems.each(function () {
            getSectionCounter = 0;
            jQuery(this).find("select").each(function () {
                if (!jQuery(this).val()) {
                    getSectionCounter++;
                    if (getSectionCounter == 4) {
                        getSectionValidated = false;
                        message = "Get Section is empty !";
                    }
                }
            });
        });

        $(".wgb-offer-rule-item").each(function () {
            let ruleItem = $(this);
            let method = ruleItem.find(".wgb-offer-rule-method").val();
            let comparisonOperator = ruleItem.find(".wgb-comparison-operator").val();

            if (method === "subtotal" || method === "buy_x_get_x" || method === "buy_x_get_y") {
                if (comparisonOperator === "") {
                    ruleItem.addClass("wgb-offer-rule-error");
                    ruleItem.find(".wgb-comparison-operator").addClass("wgb-validation-error-field");
                    getSectionValidated = false;
                    message = "Red fields is required !";
                }
            }
        });

        if (document.getElementById("wgb-offer-rules-form").checkValidity() && getSectionValidated === true) {
            $("#wgb-offer-rules-form").submit();
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
    $(document).on("click", ".wgb-offer-rule-text-align-button", function () {
        $(this).closest('.wgb-form-group').find('.wgb-offer-rule-text-align-button').removeClass('active').find('input').prop('checked', false);
        $(this).addClass('active').find('input').prop('checked', true);
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

    wgbOfferRuleResetData();
});
