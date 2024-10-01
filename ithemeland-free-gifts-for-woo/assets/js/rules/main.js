jQuery(document).ready(function ($) {
    "use strict";

    let currentTab = (window.location.hash.split('#')[1]) ? window.location.hash.split('#')[1] : 'rules';
    window.location.hash = currentTab;
    wgblOpenTab($('.wgbl-tabs-list li a[data-content="' + currentTab + '"]'));

    $(document).on('click', '.wgbl-rule-header', function (e) {
        let item = $(this).closest('.wgbl-rule-item');
        item.find('.wgbl-rule-body').slideToggle(250);
        $('.wgbl-rule-item').each(function () {
            if ($(this).attr('data-id') !== item.attr('data-id')) {
                $(this).find('.wgbl-rule-body').slideUp(250);
            }
        });
    });

    $(document).on('click', '.wgbl-rule-delete', function () {
        let ruleItem = $(this).closest('.wgbl-rule-item');
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wgbl-button wgbl-button-lg wgbl-button-white",
            confirmButtonClass: "wgbl-button wgbl-button-lg wgbl-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                ruleItem.remove();
                if ($('.wgbl-rule-item').length < 1) {
                    $('.wgbl-empty-rules-box').show();
                }
                wgblRulesFixPriority();
            }
        });
        return false;
    });

    $(document).on('click', '.wgbl-rule-duplicate', function () {
        wgblRuleDuplicate($(this).closest('.wgbl-rule-item'));
        return false;
    });

    $(document).on('click', '.wgbl-rule-item-status', function () {
        return false;
    });

    $(document).on('click', '.wgbl-rule-item-language', function () {
        return false;
    });

    $(document).on('change', '.wgbl-rule-item-status', function () {
        $(this).closest('.wgbl-rule-item').removeClass('wgbl-rule-enable').removeClass('wgbl-rule-disable').addClass('wgbl-rule-' + $(this).val());
    });

    $(document).on('change', '.wgbl-rule-method', function () {
        let id = $(this).closest('.wgbl-rule-item').attr('data-id');
        let item = $(this).closest('.wgbl-rule-item');
        item.find(".wgbl-rule-method-name").html($(this).find('option:selected').html());
        if (!item.find('select[name="rule[' + id + '][quantities_based_on]"]').val()) {
            item.find('select[name="rule[' + id + '][quantities_based_on]"]').prop("selectedIndex", 0).val();
        }

        let basedOn = item.find('div[data-type="quantities-based-on"]');
        basedOn.find('label').hide();

        if ($(this).val() == 'bulk_pricing') {
            basedOn.find('label[data-label="price-based-on"]').show();
        } else {
            basedOn.find('label[data-label="quantities-based-on"]').show();

        }

        switch ($(this).val()) {
            case 'simple':
                if ($.isFunction(window.wgblSimpleMethod)) { wgblSimpleMethod(id) };
                break;
            case 'tiered_quantity':
                if ($.isFunction(window.wgblTieredQuantityMethod)) { wgblTieredQuantityMethod(id) };
                break;
            case 'bulk_quantity':
                if ($.isFunction(window.wgblBulkQuantityMethod)) { wgblBulkQuantityMethod(id) };
                break;
            case 'bulk_pricing':
                if ($.isFunction(window.wgblBulkPricingMethod)) { wgblBulkPricingMethod(id) };
                break;
            case 'subtotal':
                if ($.isFunction(window.wgblSubTotalMethod)) { wgblSubTotalMethod(id) };
                break;
            case 'subtotal_repeat':
                if ($.isFunction(window.wgblSubTotalMethod)) { wgblSubTotalMethod(id) };
                break;
            case 'buy_x_get_x':
                if ($.isFunction(window.wgblBuyXGetXMethod)) { wgblBuyXGetXMethod(id) };
                break;
            case 'buy_x_get_x_repeat':
                if ($.isFunction(window.wgblBuyXGetXMethod)) { wgblBuyXGetXMethod(id) };
                break;
            case 'buy_x_get_y':
                if ($.isFunction(window.wgblBuyXGetYMethod)) { wgblBuyXGetYMethod(id) };
                break;
            case 'buy_x_get_y_repeat':
                if ($.isFunction(window.wgblBuyXGetYMethod)) { wgblBuyXGetYMethod(id) };
                break;
            case 'cheapest_item_in_cart':
                if ($.isFunction(window.wgblCheapestItemInCart)) { wgblCheapestItemInCart(id) };
                break;
            default:
                return false;
        }
    });

    $(document).on('click', '.wgbl-add-rule', function () {
        wgblAddRule(parseInt($('.wgbl-rule-item').length));
        $('.wgbl-empty-rules-box').hide();
    });

    $(document).on('keyup', '.wgbl-rule-name', function () {
        $(this).closest('.wgbl-rule-item').find('.wgbl-rule-title').html(($(this).val()) ? $(this).val() : 'New Rule');
    });

    $(document).on('change', '.wgbl-rule-quantities-checkbox', function () {
        let elementItem = $(this).closest('div');
        if ($(this).prop('checked') === true) {
            elementItem.find('input[type=hidden]').val('yes');
        } else {
            elementItem.find('input[type=hidden]').val('no');
        }
    });

    $(document).on('change', '#wgbl-settings-layout', function () {
        wgblSettingsDependenciesController($(this).val())
    });

    $(document).on('change', '#wgbl-settings-position-in-cart', function () {
        let $this = $(this);

        switch ($this.val()) {
            case 'none':
            case 'popup':
                $('div[data-type="layout-select-box"]').hide();
                $('#wgbl-settings-layout').val('').change().prop('disabled', true);
                $('.position-dependency').hide().find('input').prop('disabled', true);
                break;
            default:
                $('div[data-type="layout-select-box"]').show();
                $('#wgbl-settings-layout').prop('disabled', false);
                $('#wgbl-settings-layout option').hide();
                $('#wgbl-settings-layout option[data-type="' + $this.val() + '"]').show();
                setTimeout(function () {
                    if ($('#wgbl-settings-layout option:selected').attr('data-type') !== $this.val()) {
                        $('#wgbl-settings-layout').val($('#wgbl-settings-layout option[data-type="' + $this.val() + '"]').first().attr('value')).trigger('change');
                    }
                }, 50);
                $('.position-dependency').hide().find('input').prop('disabled', true);
                $('.position-dependency[data-type="' + $this.val() + '"]').show().find('input').prop('disabled', false);
                break;
        }
    });

    $(document).on('change', '#wgbl-settings-position-gift-in-cart', function () {
        wgblSettingsViewGiftInCartDependenciesController($(this).val())
    });

    $(document).on('click', '#wgbl-rules-save-changes', function () {
        let message = 'Red fields is required !';
        let getSectionItems = jQuery('div[data-type=get]:visible').find('.wgbl-rule-section');
        let getSectionCounter;
        let getSectionValidated = true;

        let optionValues = {
            'categories': {},
            'coupons': {},
            'products': {},
            'variations': {},
            'tags': {},
            'taxonomies': {},
            'attributes': {},
            'shipping_classes': {},
            'shipping_country': {},
            'payment_methods': {},
            'customers': {},
        };

        if ($('.wgbl-select2-option-values').length) {
            $('.wgbl-select2-option-values option:selected').each(function () {
                if ($(this).attr('value') != '') {
                    let optionName = $(this).closest('.wgbl-select2-option-values').attr('data-option-name');
                    if (optionValues[optionName]) {
                        optionValues[optionName][$(this).attr('value')] = $(this).text();
                    }
                }
            });
        }

        $('#wgbl-option-values').val(JSON.stringify(optionValues));

        jQuery("#wgbl-rules input:required").filter(function () {
            if (!this.disabled && !this.value) {
                jQuery(this).closest('.wgbl-rule-item').addClass('wgbl-rule-error');
                return true;
            }
        }).addClass("wgbl-validation-error-field");

        let select2Items = [];
        jQuery("#wgbl-rules select:required").filter(function () {
            if ($(this).attr('data-type') === 'select2' && !this.value && !this.disabled) {
                select2Items.push($(this).attr('data-select2-id'))
            }
            return !this.disabled && !this.value;
        }).addClass("wgbl-validation-error-field");

        if (select2Items.length > 0) {
            jQuery.each(select2Items, function (key, val) {
                let select2Id = parseInt(val) + 1;
                jQuery('.select2-container[data-select2-id="' + select2Id + '"]').addClass('wgbl-validation-error-field');
                jQuery('.select2-container[data-select2-id="' + select2Id + '"]').closest('.wgbl-rule-item').addClass('wgbl-rule-error');
            });
        }

        // get section validation
        getSectionItems.each(function () {
            getSectionCounter = 0;
            jQuery(this).find('select').each(function () {
                if (!jQuery(this).val()) {
                    getSectionCounter++;
                    if (getSectionCounter == 4) {
                        getSectionValidated = false;
                        message = "Get Section is empty !";
                    }
                }
            });
        });

        if (document.getElementById('wgbl-rules-form').checkValidity() && getSectionValidated === true) {
            $('#wgbl-rules-form').submit();
        } else {

            swal({
                title: message,
                type: "warning"
            });
        }
    });

    $(document).on('click', '.select2-results__group', function () {
        $(this).closest('ul').find('ul').slideUp(200);
        $(this).closest('li').find('ul').slideDown(200);
    });

    $(document).on('select2:open', '.wgbl-select2-grouped', function () {
        setTimeout(() => {
            let item = $('.select2-container--open').last().find('li[aria-selected="true"]');
            item.closest('ul').slideDown(200);
            if (item.offset() && item.closest('ul[role=listbox]').offset()) {
                let top = item.offset().top - (item.closest('ul[role=listbox]').offset().top + 30);
                item.closest('ul[role=listbox]').animate({
                    scrollTop: top
                });
            }
        }, 250);
    });

    $(document).on('click', '#wgbl-license-renew-button', function () {
        $(this).closest('#wgbl-license').find('.wgbl-license-form').slideDown();
    });

    $(document).on('click', '#wgbl-license-form-cancel', function () {
        $(this).closest('#wgbl-license').find('.wgbl-license-form').slideUp();
    });
    $(document).on('click', '#wgbl-license-deactivate-button', function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wgbl-button wgbl-button-lg wgbl-button-white",
            confirmButtonClass: "wgbl-button wgbl-button-lg wgbl-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $('#wgbl-license-deactivation-form').submit();
            }
        });
    });

    $(document).on('click', '#wgbl-check-for-update', function () {
        wgblCheckForUpdate();
    });

    $(document).on('change', '#wgbl-settings-show-notice-checkout', function () {
        if ($(this).prop('checked') === true) {
            $('#wgbl-settings-notification-show-notice-checkout-dependencies').show();
        } else {
            $('#wgbl-settings-notification-show-notice-checkout-dependencies').hide();
        }
    });

    $('#wgbl-settings-position-in-cart').trigger('change');

    wgblSettingsDependenciesController($('#wgbl-settings-layout').val());

    setTimeout(function () {
        wgblResetData();
    }, 50)
});