"use strict";

jQuery(document).ready(function ($) {

    // set date range picker options
    let dateRangePickerOptions;
    dateRangePickerOptions = {
        opens: 'left',
        locale: {
            format: 'YYYY/MM/DD'
        },
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }
    // init date range picker
    if ($('.wgb-reports-daterangepicker').length > 0) {
        $('.wgb-reports-daterangepicker').each(function () {
            if ($(this).val() === '') {
                dateRangePickerOptions.startDate = ($(this).attr('data-from')) ? $(this).attr('data-from') : moment().startOf('month');
                dateRangePickerOptions.endDate = ($(this).attr('data-to')) ? $(this).attr('data-to') : moment().endOf('month');
            }
            dateRangePickerOptions.opens = ($(this).attr('data-position')) ? $(this).attr('data-position') : 'left';
            dateRangePickerOptions.locale.format = ($(this).attr('data-format')) ? $(this).attr('data-format') : 'YYYY/MM/DD';
            $(this).daterangepicker(dateRangePickerOptions);

        });
    }

    // chart1 filter buttons event
    $(document).on('click', '.chart1-filter-item', function () {
        $(this).closest('.wgb-chart-filter-buttons').find('button').removeClass('active');
        $(this).addClass('active');
    });

    // chart2 filter buttons event
    $(document).on('click', '.chart2-filter-item', function () {
        $(this).closest('.wgb-chart-filter-buttons').find('button').removeClass('active');
        $(this).addClass('active');
    });

    // change main date
    $(document).on('change', '#wgb-main-date-filter', function () {
        wgbGetReports();
    });

    $(document).on('click', '.chart1-filter-item', function () {
        if (wgbReportData) {
            wgbReInitChart1(wgbReportData.chart1);
        }
    });

    $(document).on('click', '.chart2-filter-item', function () {
        if (wgbReportData) {
            wgbReInitChart2(wgbReportData.chart2);
        }
    });

    // rules page filter
    $(document).on('click', '#wgb-reports-rules-filter-button', function () {
        let element = $(this).closest('.wgb-reports-filter-section');
        if (history.pushState) {
            let ruleMethod = element.find('#wgb-usage-rule-filter-method').val();
            let rulesName = element.find('#wgb-usage-rule-filter-rules-name').val();
            let ruleId = element.find('#wgb-usage-rule-filter-rule-id').val();
            let displayJustUse = element.find('#wgb-usage-rule-filter-display-just-use').prop('checked') === true ? 'yes' : 'no';
            let newUrl = wgbSetFilterParamsForRulesPage({
                ruleMethod: ruleMethod,
                rulesName: rulesName,
                ruleId: ruleId,
                displayJustUse: displayJustUse,
            });
            window.history.pushState({ path: newUrl }, '', newUrl);
            wgbGetReports();
        }
    });

    // rules page reset filter
    $(document).on('click', '#wgb-reports-rules-reset-filter-button', function () {
        let element = $(this).closest('.wgb-reports-filter-section');
        if (history.pushState) {
            element.find('#wgb-usage-rule-filter-method').val('').change();
            element.find('#wgb-usage-rule-filter-rule-id').val('');
            element.find('#wgb-usage-rule-filter-rules-name').val('').change();
            element.find('#wgb-usage-rule-filter-display-just-use').prop('checked', false);
            let newUrl = WGBL_REPORTS_DATA.mainUrl + '&sub-page=rules';
            window.history.pushState({ path: newUrl }, '', newUrl);
            wgbGetReports();
        }
    });

    // orders page filter
    $(document).on('click', '#wgb-reports-orders-filter-button', function () {
        let element = $(this).closest('.wgb-reports-filter-section');
        if (history.pushState) {
            let orderId = element.find('#wgb-orders-filter-order-id').val();
            let rulesName = element.find('#wgb-orders-filter-rules-name').val();
            let gifts = element.find('#wgb-orders-filter-gifts').val();
            let orderDate = element.find('#wgb-orders-filter-order-date').val().replace(/ /g, '');
            let orderStatus = element.find('#wgb-orders-filter-order-status').val();
            let usernames = element.find('#wgb-orders-filter-usernames').val();
            let customerEmail = element.find('#wgb-orders-filter-customer-email').val();
            let newUrl = wgbSetFilterParamsForOrdersPage({
                orderId: orderId,
                rulesName: rulesName,
                gifts: gifts,
                orderDate: orderDate,
                orderStatus: orderStatus,
                usernames: usernames,
                customerEmail: customerEmail,
            });
            window.history.pushState({ path: newUrl }, '', newUrl);
            wgbGetReports();
        }
    });

    // orders page reset filter
    $(document).on('click', '#wgb-reports-orders-reset-filter-button', function () {
        let element = $(this).closest('.wgb-reports-filter-section');
        if (history.pushState) {
            element.find('#wgb-orders-filter-order-id').val('');
            element.find('#wgb-orders-filter-rules-name').val('').change();
            element.find('#wgb-orders-filter-gifts').val('').change();
            let dateFrom = element.find('#wgb-orders-filter-order-date').attr('data-from');
            let dateTo = element.find('#wgb-orders-filter-order-date').attr('data-to');
            element.find('#wgb-orders-filter-order-date').val(dateFrom + ' - ' + dateTo).change();
            element.find('#wgb-orders-filter-order-status').val('').change();
            element.find('#wgb-orders-filter-usernames').val('').change();
            element.find('#wgb-orders-filter-customer-email').val('').change();
            let newUrl = WGBL_REPORTS_DATA.mainUrl + '&sub-page=orders';
            window.history.pushState({ path: newUrl }, '', newUrl);
            wgbGetReports();
        }
    });

    // all customers page filter
    $(document).on('click', '#wgb-reports-all-customers-filter-button', function () {
        let element = $(this).closest('.wgb-reports-filter-section');
        if (history.pushState) {
            let email = element.find('#wgb-all-customers-filter-email').val();
            let username = element.find('#wgb-all-customers-filter-username').val();
            let count = element.find('#wgb-all-customers-filter-count').val();
            let newUrl = wgbSetFilterParamsForAllCustomersPage({
                email: email,
                username: username,
                count: count
            });
            window.history.pushState({ path: newUrl }, '', newUrl);
            wgbGetReports();
        }
    });

    // all customers page reset filter
    $(document).on('click', '#wgb-reports-all-customers-reset-filter-button', function () {
        let element = $(this).closest('.wgb-reports-filter-section');
        if (history.pushState) {
            element.find('#wgb-all-customers-filter-email').val('');
            element.find('#wgb-all-customers-filter-username').val('').change();
            element.find('#wgb-all-customers-filter-count').val('').change();

            let newUrl = WGBL_REPORTS_DATA.mainUrl + '&sub-page=customers&sub-menu=all-customers';
            window.history.pushState({ path: newUrl }, '', newUrl);
            wgbGetReports();
        }
    });

    // used rules by customer page filter
    $(document).on('click', '#wgb-reports-used-rules-by-customer-filter-button', function () {
        let element = $(this).closest('.wgb-reports-filter-section');
        if (history.pushState) {
            let date = element.find('#wgb-used-rules-by-customer-filter-date').val().replace(/ /g, '');
            let email = element.find('#wgb-used-rules-by-customer-filter-email').val();
            let username = element.find('#wgb-used-rules-by-customer-filter-username').val();
            let rulesName = element.find('#wgb-used-rules-by-customer-filter-rules-name').val();
            let orderId = element.find('#wgb-used-rules-by-customer-filter-order-id').val();
            let rulesMethod = element.find('#wgb-used-rules-by-customer-filter-rules-method').val();
            let newUrl = wgbSetFilterParamsForUsedRulesByCustomerPage({
                date: date,
                email: email,
                username: username,
                rulesName: rulesName,
                orderId: orderId,
                rulesMethod: rulesMethod
            });
            window.history.pushState({ path: newUrl }, '', newUrl);
            wgbGetReports();
        }
    });

    // used rules by customer page reset filter
    $(document).on('click', '#wgb-reports-used-rules-by-customer-reset-filter-button', function () {
        let element = $(this).closest('.wgb-reports-filter-section');
        if (history.pushState) {
            let dateFrom = element.find('#wgb-used-rules-by-customer-filter-date').attr('data-from');
            let dateTo = element.find('#wgb-used-rules-by-customer-filter-date').attr('data-to');
            element.find('#wgb-used-rules-by-customer-filter-date').val(dateFrom + ' - ' + dateTo).change();
            element.find('#wgb-used-rules-by-customer-filter-email').val('');
            element.find('#wgb-used-rules-by-customer-filter-username').val('').change();
            element.find('#wgb-used-rules-by-customer-filter-rules-name').val('').change();
            element.find('#wgb-used-rules-by-customer-filter-order-id').val('');
            element.find('#wgb-used-rules-by-customer-filter-rules-method').val('').change();

            let newUrl = WGBL_REPORTS_DATA.mainUrl + '&sub-page=customers&sub-menu=used-rules-by-customer';
            window.history.pushState({ path: newUrl }, '', newUrl);
            wgbGetReports();
        }
    });

    // products page filter
    $(document).on('click', '#wgb-reports-products-filter-button', function () {
        let element = $(this).closest('.wgb-reports-filter-section');
        if (history.pushState) {
            let brand = element.find('#wgb-products-filter-brand').val();
            let category = element.find('#wgb-products-filter-category').val();
            let productsName = element.find('#wgb-products-filter-products-name').val();
            let newUrl = wgbSetFilterParamsForProductsPage({
                brand: brand,
                category: category,
                productsName: productsName,
            });
            window.history.pushState({ path: newUrl }, '', newUrl);
            wgbGetReports();
        }
    });

    // products page reset filter
    $(document).on('click', '#wgb-reports-products-reset-filter-button', function () {
        let element = $(this).closest('.wgb-reports-filter-section');
        if (history.pushState) {
            element.find('#wgb-products-filter-brand').val('').change();
            element.find('#wgb-products-filter-category').val('').change();
            element.find('#wgb-products-filter-products-name').val('').change();

            let newUrl = WGBL_REPORTS_DATA.mainUrl + '&sub-page=products&sub-menu=products';
            window.history.pushState({ path: newUrl }, '', newUrl);
            wgbGetReports();
        }
    });

    // gotten gifts by customer page filter
    $(document).on('click', '#wgb-reports-gotten-gifts-by-customer-filter-button', function () {
        let element = $(this).closest('.wgb-reports-filter-section');
        if (history.pushState) {
            let date = element.find('#wgb-gotten-gifts-by-customer-filter-date').val().replace(/ /g, '');
            let usernames = element.find('#wgb-gotten-gifts-by-customer-filter-usernames').val();
            let products = element.find('#wgb-gotten-gifts-by-customer-filter-products').val();
            let rulesName = element.find('#wgb-gotten-gifts-by-customer-filter-rules-name').val();
            let ruleId = element.find('#wgb-gotten-gifts-by-customer-filter-rule-id').val();
            let rulesMethod = element.find('#wgb-gotten-gifts-by-customer-filter-rules-method').val();
            let customerEmail = element.find('#wgb-gotten-gifts-by-customer-filter-customer-email').val();

            let newUrl = wgbSetFilterParamsForGottenGiftsByCustomerPage({
                date: date,
                usernames: usernames,
                products: products,
                rulesName: rulesName,
                ruleId: ruleId,
                rulesMethod: rulesMethod,
                customerEmail: customerEmail,
            });
            window.history.pushState({ path: newUrl }, '', newUrl);
            wgbGetReports();
        }
    });

    // gotten gifts by customer page reset filter
    $(document).on('click', '#wgb-reports-gotten-gifts-by-customer-reset-filter-button', function () {
        let element = $(this).closest('.wgb-reports-filter-section');
        if (history.pushState) {
            let dateFrom = element.find('#wgb-gotten-gifts-by-customer-filter-date').attr('data-from');
            let dateTo = element.find('#wgb-gotten-gifts-by-customer-filter-date').attr('data-to');
            element.find('#wgb-gotten-gifts-by-customer-filter-date').val(dateFrom + ' - ' + dateTo).change();
            element.find('#wgb-gotten-gifts-by-customer-filter-usernames').val('').change();
            element.find('#wgb-gotten-gifts-by-customer-filter-products').val('').change();
            element.find('#wgb-gotten-gifts-by-customer-filter-rules-name').val('').change();
            element.find('#wgb-gotten-gifts-by-customer-filter-rule-id').val('');
            element.find('#wgb-gotten-gifts-by-customer-filter-rules-method').val('').change();
            element.find('#wgb-gotten-gifts-by-customer-filter-customer-email').val('').change();

            let newUrl = WGBL_REPORTS_DATA.mainUrl + '&sub-page=products&sub-menu=gotten-gifts-by-customer';
            window.history.pushState({ path: newUrl }, '', newUrl);
            wgbGetReports();
        }
    });

    if ($.fn.select2) {
        $('.wgb-reports-select2').select2({
            width: '100%'
        });
    }

    wgbGetReports();
})

