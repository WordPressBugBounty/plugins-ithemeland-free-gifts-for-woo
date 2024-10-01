"use strict";

var wgblReportData;

function wgblGetMainDate() {
    if (jQuery('#wgbl-main-date-filter').length > 0) {
        let date = jQuery('#wgbl-main-date-filter').val().replace(/ /g, '').split('-');
        return {
            from: (date[0]) ? date[0] : null,
            to: (date[1]) ? date[1] : null
        }
    }
    return {};
}

function wgblRemoveSkeletonLoading() {
    jQuery('.wgbl-skeleton').removeClass('wgbl-skeleton');
}

function wgblGetReports() {
    jQuery.ajax({
        url: WGBL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wgbl_get_reports',
            nonce: WGBL_DATA.ajax_nonce,
            dates: wgblGetMainDate(),
            page_params: window.location.search
        },
        success: function (response) {
            wgblReportData = response;
            wgblSetReportItems(response);
        },
        error: function () {
            wgblHideButtonLoading();
            wgblRemoveSkeletonLoading();
            wgblSetReportItems([]);
        }
    });
}

function wgblSetReportItems(data) {
    let page = (WGBL_REPORTS_DATA.subMenu && WGBL_REPORTS_DATA.subMenu !== '') ? WGBL_REPORTS_DATA.subMenu : WGBL_REPORTS_DATA.subPage;
    switch (page) {
        case 'dashboard':
            wgblSetDashboardPageItems(data);
            break;
        case 'rules':
            wgblSetRulesPageItems(data);
            break;
        case 'orders':
            wgblSetOrdersPageItems(data);
            break;
        case 'all-customers':
            wgblSetAllCustomersPageItems(data);
            break;
        case 'used-rules-by-customer':
            wgblSetUsedRulesByCustomerPageItems(data);
            break;
        case 'products':
            wgblSetProductsPageItems(data);
            break;
        case 'gotten-gifts-by-customer':
            wgblSetGottenGiftsByCustomerPageItems(data);
            break;
    }
}

function wgblSetDashboardPageItems(data) {
    // set boxes 
    jQuery('#wgbl-reports-dashboard-total-gift-count').text(data.total_gift_count);
    jQuery('#wgbl-reports-dashboard-total-customers').text(data.total_customers);
    jQuery('#wgbl-reports-dashboard-number-of-used-rule').text(data.number_of_used_rule);
    jQuery('#wgbl-reports-dashboard-number-of-orders').text(data.number_of_orders);

    // set charts
    wgblReInitChart1(data.chart1);
    wgblReInitChart2(data.chart2);

    // set recent orders used the gift
    wgblSetRecentOrdersUsedTheGift(data.recent_orders_used_the_gift);

    // set top methods
    wgblSetTopMethods(data.top_methods);

    // set top rules
    wgblSetTopRules(data.top_rules);

    // set top gifts
    wgblSetTopGifts(data.top_gifts);

    // set top categories
    wgblSetTopCategories(data.top_categories);

    // set top countries
    wgblSetTopCountries(data.top_countries);

    // set top states
    wgblSetTopStates(data.top_states);

    // set used gifts
    wgblSetUsedGifts(data.used_gifts);

    // set used gifts
    wgblSetRecentCustomersGetGift(data.recent_customers_get_gift);

    // remove skeleton loading
    wgblRemoveSkeletonLoading();
}

function wgblSetRulesPageItems(data) {
    let table = jQuery('#wgbl-reports-rules');
    table.find('tbody').html('<tr><td colspan="7">No item</td></tr>');
    if (data.rules instanceof Object && Object.keys(data.rules).length > 0) {
        // destroy datatable
        if (jQuery.fn.DataTable.isDataTable('#wgbl-reports-rules')) {
            jQuery('#wgbl-reports-rules').DataTable().destroy();
        }
        table.find('tbody').html('');
        let rulesLength = Object.keys(data.rules).length;
        jQuery.each(data.rules, function (key, rule) {
            let methodName = (WGBL_DATA.ruleMethods) ? WGBL_DATA.ruleMethods[rule.method] : rule.method;
            let viewUsedGiftButton = '-';
            let viewUsedOrderButton = '-';
            let viewCustomerButton = '-';

            if (rule.used_count > 0) {
                let viewUsedGiftFilterUrl = wgblSetFilterParamsForGottenGiftsByCustomerPage({ rulesName: rule.uid });
                let viewUsedOrderFilterUrl = wgblSetFilterParamsForOrdersPage({ rulesName: rule.uid });
                let viewCustomerButtonFilterUrl = wgblSetFilterParamsForUsedRulesByCustomerPage({ rulesName: rule.uid });
                viewUsedGiftButton = '<a class="wgbl-reports-view-button" href="' + viewUsedGiftFilterUrl + '">View</a>';
                viewUsedOrderButton = '<a class="wgbl-reports-view-button" href="' + viewUsedOrderFilterUrl + '">View</a>';
                viewCustomerButton = '<a class="wgbl-reports-view-button" href="' + viewCustomerButtonFilterUrl + '">View</a>';
            }

            table.append('<tr>' +
                '<td>' + rule.uid + '</a></td>' +
                '<td>' + rule.rule_name + '</td>' +
                '<td>' + methodName + '</td>' +
                '<td>' + rule.used_count + '</td>' +
                '<td>' + viewUsedGiftButton + '</td>' +
                '<td>' + viewUsedOrderButton + '</td>' +
                '<td>' + viewCustomerButton + '</td>' +
                '</tr>');

            // init DataTable
            if (!--rulesLength) {
                table.DataTable(WGBL_REPORTS_DATA.dataTableOptions);
                wgblHideButtonLoading();

                // remove skeleton loading
                wgblRemoveSkeletonLoading();
            }
        });
    }
}

function wgblSetOrdersPageItems(data) {
    let table = jQuery('#wgbl-reports-orders');
    table.find('tbody').html('<tr><td colspan="7">No item</td></tr>');
    if (data.orders instanceof Object && Object.keys(data.orders).length > 0) {
        // destroy datatable
        if (jQuery.fn.DataTable.isDataTable('#wgbl-reports-orders')) {
            jQuery('#wgbl-reports-orders').DataTable().destroy();
        }
        table.find('tbody').html('');
        let ordersLength = Object.keys(data.orders).length;
        jQuery.each(data.orders, function (key, order) {
            let statusLabel = (WGBL_REPORTS_DATA.orderStatuses[order.order_status]) ? WGBL_REPORTS_DATA.orderStatuses[order.order_status] : order.order_status;

            let rulesName = '';
            if (order.rules_name instanceof Object && Object.keys(order.rules_name).length > 0) {
                let length = Object.keys(order.rules_name).length;
                jQuery.each(order.rules_name, function (ruleId, ruleName) {
                    let ruleFilterUrl = wgblSetFilterParamsForRulesPage({ rulesName: ruleId });
                    rulesName += '<a href="' + ruleFilterUrl + '">' + ruleName + '</a>';
                    if (--length) {
                        rulesName += ', ';
                    }
                })
            }

            table.append('<tr>' +
                '<td><a href="' + order.order_link + '">#' + order.order_id + '<a/></td>' +
                '<td>' + order.customer_email + '</td>' +
                '<td>' + order.order_date + '</td>' +
                '<td><span class="wgbl-order-status ' + order.order_status + '">' + statusLabel + '</span></td>' +
                '<td>' + rulesName + '</td>' +
                '<td>' + order.rules_id + '</td>' +
                '<td>' + order.gifts_name + '</td>' +
                '</tr>');

            // init DataTable
            if (!--ordersLength) {
                table.DataTable(WGBL_REPORTS_DATA.dataTableOptions);
                wgblHideButtonLoading();

                // remove skeleton loading
                wgblRemoveSkeletonLoading();
            }
        });
    }
}

function wgblSetAllCustomersPageItems(data) {
    let table = jQuery('#wgbl-reports-all-customers');
    table.find('tbody').html('<tr><td colspan="7">No item</td></tr>');
    if (data.customers instanceof Object && Object.keys(data.customers).length > 0) {
        // destroy datatable
        if (jQuery.fn.DataTable.isDataTable('#wgbl-reports-all-customers')) {
            jQuery('#wgbl-reports-all-customers').DataTable().destroy();
        }
        table.find('tbody').html('');
        let customersLength = Object.keys(data.customers).length;
        jQuery.each(data.customers, function (key, customer) {
            let viewOrdersFilterUrl = wgblSetFilterParamsForOrdersPage({ customerEmail: customer.customer_email });
            let viewRulesFilterUrl = wgblSetFilterParamsForUsedRulesByCustomerPage({ email: customer.customer_email });
            let viewGiftsFilterUrl = wgblSetFilterParamsForGottenGiftsByCustomerPage({ customerEmail: customer.customer_email });

            table.append('<tr>' +
                '<td>' + customer.customer_email + '</td>' +
                '<td>' + customer.customer_name + '</td>' +
                '<td>' + customer.customer_username + '</td>' +
                '<td>' + customer.order_count + '</td>' +
                '<td><a class="wgbl-reports-view-button" href="' + viewOrdersFilterUrl + '">View</a></td>' +
                '<td><a class="wgbl-reports-view-button" href="' + viewRulesFilterUrl + '">View</a></td>' +
                '<td><a class="wgbl-reports-view-button" href="' + viewGiftsFilterUrl + '">View</a></td>' +
                '</tr>');

            // init DataTable
            if (!--customersLength) {
                table.DataTable(WGBL_REPORTS_DATA.dataTableOptions);
                wgblHideButtonLoading();

                // remove skeleton loading
                wgblRemoveSkeletonLoading();
            }
        });
    }
}

function wgblSetUsedRulesByCustomerPageItems(data) {
    let table = jQuery('#wgbl-reports-used-rules-by-customers');
    table.find('tbody').html('<tr><td colspan="7">No item</td></tr>');
    if (data.used_rules instanceof Object && Object.keys(data.used_rules).length > 0) {
        // destroy datatable
        if (jQuery.fn.DataTable.isDataTable('#wgbl-reports-used-rules-by-customers')) {
            jQuery('#wgbl-reports-used-rules-by-customers').DataTable().destroy();
        }
        table.find('tbody').html('');
        let length = Object.keys(data.used_rules).length;
        jQuery.each(data.used_rules, function (key, used_rule) {
            let ruleMethod = (WGBL_DATA.ruleMethods[used_rule.rule_method]) ? WGBL_DATA.ruleMethods[used_rule.rule_method] : used_rule.rule_method;
            table.append('<tr>' +
                '<td>' + used_rule.customer_username + '</td>' +
                '<td>' + used_rule.customer_email + '</td>' +
                '<td>' + used_rule.rule_id + '</td>' +
                '<td><a href="' + used_rule.order_link + '">#' + used_rule.order_id + '</a></td>' +
                '<td>' + used_rule.rule_name + '</td>' +
                '<td>' + ruleMethod + '</td>' +
                '<td>' + used_rule.order_date + '</td>' +
                '</tr>');

            // init DataTable
            if (!--length) {
                table.DataTable(WGBL_REPORTS_DATA.dataTableOptions);
                wgblHideButtonLoading();

                // remove skeleton loading
                wgblRemoveSkeletonLoading();
            }
        });
    }
}

function wgblSetProductsPageItems(data) {
    let table = jQuery('#wgbl-reports-products');
    table.find('tbody').html('<tr><td colspan="7">No item</td></tr>');
    if (data.products instanceof Object && Object.keys(data.products).length > 0) {
        // destroy datatable
        if (jQuery.fn.DataTable.isDataTable('#wgbl-reports-products')) {
            jQuery('#wgbl-reports-products').DataTable().destroy();
        }
        table.find('tbody').html('');
        let length = Object.keys(data.products).length;
        jQuery.each(data.products, function (key, product) {
            let brands = (table.find('thead th.it-product-brands').length > 0) ? '<td>' + product.brand + '</td>' : '';
            let viewCustomerFilterUrl = wgblSetFilterParamsForGottenGiftsByCustomerPage({ products: product.product_id });
            let viewOrdersFilterUrl = wgblSetFilterParamsForOrdersPage({ gifts: product.product_id });

            table.append('<tr>' +
                '<td><a href="' + product.product_link + '">#' + product.product_id + '</a></td>' +
                '<td>' + product.sku + '</td>' +
                '<td>' + product.product_name + '</td>' +
                '<td>' + product.category + '</td>' +
                brands +
                '<td>' + product.count + '</td>' +
                '<td><a class="wgbl-reports-view-button" href="' + viewCustomerFilterUrl + '">View</a></td>' +
                '<td><a class="wgbl-reports-view-button" href="' + viewOrdersFilterUrl + '">View</a></td>' +
                '</tr>');

            // init DataTable
            if (!--length) {
                table.DataTable(WGBL_REPORTS_DATA.dataTableOptions);
                wgblHideButtonLoading();

                // remove skeleton loading
                wgblRemoveSkeletonLoading();
            }
        });
    }
}

function wgblSetGottenGiftsByCustomerPageItems(data) {
    let table = jQuery('#wgbl-reports-gotten-gifts-by-customer');
    table.find('tbody').html('<tr><td colspan="7">No item</td></tr>');
    if (data.gifts instanceof Object && Object.keys(data.gifts).length > 0) {
        // destroy datatable
        if (jQuery.fn.DataTable.isDataTable('#wgbl-reports-gotten-gifts-by-customer')) {
            jQuery('#wgbl-reports-gotten-gifts-by-customer').DataTable().destroy();
        }
        table.find('tbody').html('');
        let length = Object.keys(data.gifts).length;
        jQuery.each(data.gifts, function (key, gift) {
            let ruleMethod = (WGBL_DATA.ruleMethods[gift.rule_method]) ? WGBL_DATA.ruleMethods[gift.rule_method] : gift.rule_method;
            table.append('<tr>' +
                '<td>' + gift.product_name + '</td>' +
                '<td>' + gift.customer_email + '</td>' +
                '<td><a href="' + gift.order_link + '">#' + gift.order_id + '</a></td>' +
                '<td>' + gift.rule_id + '</td>' +
                '<td>' + gift.rule_name + '</td>' +
                '<td>' + ruleMethod + '</td>' +
                '<td>' + gift.order_date + '</td>' +
                '</tr>');

            // init DataTable
            if (!--length) {
                table.DataTable(WGBL_REPORTS_DATA.dataTableOptions);
                wgblHideButtonLoading();

                // remove skeleton loading
                wgblRemoveSkeletonLoading();
            }
        });
    }
}

function wgblReInitChart1(data) {
    var chart1Data = data;

    am4core.ready(function () {
        // chart1 init
        if (jQuery('#amchart1').length > 0) {
            // Themes begin
            am4core.useTheme(am4themes_animated);
            am4core.options.autoDispose = true;

            // Create chart instance
            var chart = am4core.create("amchart1", am4charts.XYChart);

            // Export
            // chart.exporting.menu = new am4core.ExportMenu();

            // Data for both series
            let period = jQuery('#wgbl-chart1-buttons').find('button.active').val();
            period = (period && period !== '') ? period : 'month';
            var data = chart1Data[period];

            /* Create axes */
            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "category";
            categoryAxis.renderer.minGridDistance = 30;

            /* Create value axis */
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.min = 0;
            valueAxis.renderer.minGridDistance = 30;

            /* Create series */
            var columnSeries = chart.series.push(new am4charts.ColumnSeries());
            columnSeries.name = "Count";
            columnSeries.dataFields.valueY = "count";
            columnSeries.dataFields.categoryX = "category";
            columnSeries.columns.template.stroke = am4core.color("#603ecd");
            columnSeries.columns.template.fill = am4core.color("#603ecd");
            columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
            columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
            columnSeries.columns.template.propertyFields.stroke = "stroke";
            columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
            columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
            columnSeries.tooltip.label.textAlign = "middle";

            chart.data = data;

            // Set cell size in pixels
            var cellSize = 50;
            chart.events.on("datavalidated", function (ev) {

                // Get objects of interest
                var chart = ev.target;
                var categoryAxis = chart.yAxes.getIndex(0);

                // Calculate how we need to adjust chart height
                var adjustHeight = chart.data.length * cellSize - categoryAxis.pixelHeight;

                // get current chart height
                var targetHeight = chart.pixelHeight + adjustHeight;

                // Set it on chart's container
                chart.svgContainer.htmlElement.style.height = targetHeight + "px";
            });
        }
    })
}

function wgblReInitChart2(data) {
    var chart2Data = data;

    am4core.ready(function () {
        // chart2 init
        if (jQuery('#amchart2').length > 0) {
            // Themes begin
            am4core.useTheme(am4themes_animated);

            am4core.options.autoDispose = true;

            // Create chart instance
            var chart2 = am4core.create("amchart2", am4charts.PieChart);

            // Add data
            let chartType = jQuery('#wgbl-chart2-buttons button.active').val();
            chart2.data = (chartType && chartType !== '') ? chart2Data[chartType] : chart2Data.product;

            // Add and configure Series
            var pieSeries = chart2.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "count";
            pieSeries.dataFields.category = "name";
            pieSeries.labels.template.text = "";
            pieSeries.slices.template.stroke = am4core.color("#fff");
            pieSeries.slices.template.strokeWidth = 2;
            pieSeries.slices.template.strokeOpacity = 1;

            // This creates initial animation
            pieSeries.hiddenState.properties.opacity = 1;
            pieSeries.hiddenState.properties.endAngle = -90;
            pieSeries.hiddenState.properties.startAngle = -90;

        }
    });
}

function wgblSetRecentOrdersUsedTheGift(data) {
    let table = jQuery('#wgbl-dashboard-recent-orders-used-gift');
    table.find('tbody').html('<tr><td colspan="5">No item</td></tr>');
    if (data.length > 0) {
        table.find('tbody').html('');
        jQuery.each(data, function (key, val) {
            let statusLabel = (WGBL_REPORTS_DATA.orderStatuses[val.order_status]) ? WGBL_REPORTS_DATA.orderStatuses[val.order_status] : val.order_status;
            let rulesName = '';

            if (val.rules_name instanceof Object && Object.keys(val.rules_name).length > 0) {
                let length = Object.keys(val.rules_name).length;
                jQuery.each(val.rules_name, function (ruleId, ruleName) {
                    let ruleFilterUrl = wgblSetFilterParamsForRulesPage({ rulesName: ruleId });
                    rulesName += '<a href="' + ruleFilterUrl + '">' + ruleName + '</a>';
                    if (--length) {
                        rulesName += ', ';
                    }
                })
            }

            table.append('<tr>' +
                '<td><a href="' + val.order_link + '">#' + val.order_id + '</a></td>' +
                '<td>' + val.order_date + '</td>' +
                '<td><span class="wgbl-order-status ' + val.order_status + '">' + statusLabel + '</span></td>' +
                '<td>' + rulesName + '</td>' +
                '<td>' + val.gifts_name + '</td>' +
                '</tr>');
        })
    }
}

function wgblSetTopMethods(data) {
    let table = jQuery('#wgbl-dashboard-top-methods');
    table.find('tbody').html('<tr><td colspan="2">No item</td></tr>');
    if (data instanceof Object && Object.keys(data).length > 0) {
        table.find('tbody').html('');
        jQuery.each(data, function (key, val) {
            let filterUrl = wgblSetFilterParamsForRulesPage({ ruleMethod: val.method_name });
            table.append('<tr>' +
                '<td><a href="' + filterUrl + '">' + val.method_label + '</a></td>' +
                '<td>' + val.count + '</td>' +
                '</tr>');
        })
    }
}

function wgblSetTopRules(data) {
    let table = jQuery('#wgbl-dashboard-top-rules');
    table.find('tbody').html('<tr><td colspan="2">No item</td></tr>');
    if (data instanceof Object && Object.keys(data).length > 0) {
        table.find('tbody').html('');
        jQuery.each(data, function (key, val) {
            table.append('<tr>' +
                '<td>' + val.rule_name + '</td>' +
                '<td>' + val.count + '</td>' +
                '</tr>');
        })
    }
}

function wgblSetTopGifts(data) {
    let table = jQuery('#wgbl-dashboard-top-gifts');
    table.find('tbody').html('<tr><td colspan="2">No item</td></tr>');
    if (data instanceof Object && Object.keys(data).length > 0) {
        table.find('tbody').html('');
        jQuery.each(data, function (key, val) {
            let filterUrl = wgblSetFilterParamsForProductsPage({ productsName: val.product_id })
            table.append('<tr>' +
                '<td><a href="' + filterUrl + '">' + val.product_name + '</a></td>' +
                '<td>' + val.count + '</td>' +
                '</tr>');
        })
    }
}

function wgblSetTopCategories(data) {
    let table = jQuery('#wgbl-dashboard-top-categories');
    table.find('tbody').html('<tr><td colspan="2">No item</td></tr>');
    if (data instanceof Object && Object.keys(data).length > 0) {
        table.find('tbody').html('');
        jQuery.each(data, function (key, val) {
            table.append('<tr>' +
                '<td><a href="' + val.category_link + '">' + val.category_name + '</a></td>' +
                '<td>' + val.count + '</td>' +
                '</tr>');
        })
    }
}

function wgblSetTopCountries(data) {
    let table = jQuery('#wgbl-dashboard-top-countries');
    table.find('tbody').html('<tr><td colspan="2">No item</td></tr>');
    if (data instanceof Object && Object.keys(data).length > 0) {
        table.find('tbody').html('');
        jQuery.each(data, function (key, val) {
            table.append('<tr>' +
                '<td>' + val.country_name + '</td>' +
                '<td>' + val.count + '</td>' +
                '</tr>');
        })
    }
}

function wgblSetTopStates(data) {
    let table = jQuery('#wgbl-dashboard-top-states');
    table.find('tbody').html('<tr><td colspan="2">No item</td></tr>');
    if (data instanceof Object && Object.keys(data).length > 0) {
        table.find('tbody').html('');
        jQuery.each(data, function (key, val) {
            table.append('<tr>' +
                '<td>' + val.state_name + '</td>' +
                '<td>' + val.count + '</td>' +
                '</tr>');
        })
    }
}

function wgblSetRecentCustomersGetGift(data) {
    let table = jQuery('#wgbl-dashboard-recent-customers-get-gift');
    table.find('tbody').html('<tr><td colspan="6">No item</td></tr>');
    if (data.length > 0) {
        table.find('tbody').html('');
        jQuery.each(data, function (key, val) {
            let usernameFilterUrl = wgblSetFilterParamsForAllCustomersPage({
                username: val.customer_id
            });
            table.append('<tr>' +
                '<td>' + val.customer_email + '</td>' +
                '<td>' + val.customer_name + '</td>' +
                '<td><a href="' + usernameFilterUrl + '">' + val.customer_username + '</a></td>' +
                '<td>' + val.used_gifts + '</td>' +
                '<td><a href="' + val.order_link + '">#' + val.order_id + '</a></td>' +
                '<td>' + val.order_date + '</td>' +
                '</tr>');
        })
    }
}

function wgblSetUsedGifts(data) {
    let table = jQuery('#wgbl-dashboard-used-gifts');
    table.find('tbody').html('<tr><td colspan="4">No item</td></tr>');
    if (data.length > 0) {
        table.find('tbody').html('');
        jQuery.each(data, function (key, val) {
            let brands = (table.find('thead th.it-product-brands').length > 0) ? '<td>' + val.brand + '</td>' : '';
            table.append('<tr>' +
                '<td><a href="' + val.product_link + '">' + val.product_name + '</a></td>' +
                '<td>' + val.sku + '</td>' +
                brands +
                '<td>' + val.category + '</td>' +
                '<td>' + val.count + '</td>' +
                '</tr>');
        })
    }
}


// set filter functions
function wgblSetFilterParamsForRulesPage(filters) {
    let ruleMethod = (filters.ruleMethod) ? filters.ruleMethod : '';
    let ruleId = (filters.ruleId) ? filters.ruleId : '';
    let rulesName = (filters.rulesName) ? filters.rulesName : '';
    let displayJustUse = (filters.displayJustUse) ? filters.displayJustUse : '';

    return WGBL_REPORTS_DATA.mainUrl + '&sub-page=rules&rule-method=' + ruleMethod + '&rules-name=' + rulesName + '&rule-id=' + ruleId + '&display-just-use=' + displayJustUse;
}

function wgblSetFilterParamsForOrdersPage(filters) {
    let orderId = (filters.orderId) ? filters.orderId : '';
    let rulesName = (filters.rulesName) ? filters.rulesName : '';
    let gifts = (filters.gifts) ? filters.gifts : '';
    let orderDate = (filters.orderDate) ? filters.orderDate : '';
    let orderStatus = (filters.orderStatus) ? filters.orderStatus : '';
    let usernames = (filters.usernames) ? filters.usernames : '';
    let customerEmail = (filters.customerEmail) ? filters.customerEmail : '';

    return WGBL_REPORTS_DATA.mainUrl + '&sub-page=orders&order-id=' + orderId + '&rules-name=' + rulesName + '&gifts=' + gifts + '&order-date=' + orderDate + '&order-status=' + orderStatus + '&usernames=' + usernames + '&customer-email=' + customerEmail;
}

function wgblSetFilterParamsForAllCustomersPage(filters) {
    let email = (filters.email) ? filters.email : '';
    let username = (filters.username) ? filters.username : '';
    let count = (filters.count) ? filters.count : '';

    return WGBL_REPORTS_DATA.mainUrl + '&sub-page=customers&sub-menu=all-customers&email=' + email + '&username=' + username + '&count=' + count;
}

function wgblSetFilterParamsForUsedRulesByCustomerPage(filters) {
    let date = (filters.date) ? filters.date : '';
    let email = (filters.email) ? filters.email : '';
    let username = (filters.username) ? filters.username : '';
    let rulesName = (filters.rulesName) ? filters.rulesName : '';
    let orderId = (filters.orderId) ? filters.orderId : '';
    let rulesMethod = (filters.rulesMethod) ? filters.rulesMethod : '';

    return WGBL_REPORTS_DATA.mainUrl + '&sub-page=customers&sub-menu=used-rules-by-customer&date=' + date + '&email=' + email + '&username=' + username + '&rules-name=' + rulesName + '&order-id=' + orderId + '&rules-method=' + rulesMethod;
}

function wgblSetFilterParamsForProductsPage(filters) {
    let brand = (filters.brand) ? filters.brand : '';
    let category = (filters.category) ? filters.category : '';
    let productsName = (filters.productsName) ? filters.productsName : '';

    return WGBL_REPORTS_DATA.mainUrl + '&sub-page=products&sub-menu=products&brand=' + brand + '&category=' + category + '&products-name=' + productsName;
}

function wgblSetFilterParamsForGottenGiftsByCustomerPage(filters) {
    let date = (filters.date) ? filters.date : '';
    let usernames = (filters.usernames) ? filters.usernames : '';
    let products = (filters.products) ? filters.products : '';
    let rulesName = (filters.rulesName) ? filters.rulesName : '';
    let ruleId = (filters.ruleId) ? filters.ruleId : '';
    let rulesMethod = (filters.rulesMethod) ? filters.rulesMethod : '';
    let customerEmail = (filters.customerEmail) ? filters.customerEmail : '';

    return WGBL_REPORTS_DATA.mainUrl + '&sub-page=products&sub-menu=gotten-gifts-by-customer&date=' + date + '&usernames=' + usernames + '&products=' + products + '&rules-name=' + rulesName + '&rule-id=' + ruleId + '&rules-method=' + rulesMethod + '&customer-email=' + customerEmail;
}