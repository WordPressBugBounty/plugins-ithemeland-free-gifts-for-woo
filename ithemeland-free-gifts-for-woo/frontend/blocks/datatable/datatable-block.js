var reactElement = window.wp.element,
    blocks = wp.blocks;

blocks.registerBlockType('wgb/gift-datatable', {
    title: 'Gift Datatable',
    icon: 'list-view',
    category: 'woocommerce',
    attributes: {},
    edit: function (props) {

        return reactElement.createElement("div", {
            class: "itg_shortcode_gift_products_wrapper"
        }, reactElement.createElement("div", {
            class: "wgb-mt30 wgb-mb30"
        }, reactElement.createElement("h3", null, "Our Gift"), reactElement.createElement("div", {
            class: "dataTables_wrapper no-footer"
        }, reactElement.createElement("table", {
            class: "it-gift-products-table display dataTable no-footer"
        }, reactElement.createElement("thead", null, reactElement.createElement("tr", null, reactElement.createElement("th", {
            class: "sorting_disabled"
        }, "Thumb"), reactElement.createElement("th", {
            class: "sorting_disabled"
        }, "Name"), reactElement.createElement("th", {
            class: "sorting_disabled"
        }, "Gift Available"), reactElement.createElement("th", {
            class: "sorting_disabled"
        }, "Add To Cart"))), reactElement.createElement("tbody", null, reactElement.createElement("tr", {
            class: "wgb-product-item-cnt odd"
        }, reactElement.createElement("td", {
            class: "wgb-product-item-td-thumb"
        }, reactElement.createElement("img", {
            src: WGBL_DATATABLE_DATA.images.wc_placeholder,
            class: "attachment-woocommerce_thumbnail size-woocommerce_thumbnail",
            alt: ""
        })), reactElement.createElement("td", null, "Gift 1"), reactElement.createElement("td", null, reactElement.createElement("div", {
            class: "it-wgb-item-overlay"
        }, "Available Gift : 1")), reactElement.createElement("td", null, reactElement.createElement("a", {
            class: "wgb-add-gift-btn btn-click-add-gift-button",
            href: "#"
        }, " Add Gift "))), reactElement.createElement("tr", {
            class: "wgb-product-item-cnt even"
        }, reactElement.createElement("td", {
            class: "wgb-product-item-td-thumb"
        }, reactElement.createElement("img", {
            src: WGBL_DATATABLE_DATA.images.wc_placeholder,
            class: "attachment-woocommerce_thumbnail size-woocommerce_thumbnail",
            alt: ""
        })), reactElement.createElement("td", null, "Gift 2"), reactElement.createElement("td", null, reactElement.createElement("div", {
            class: "it-wgb-item-overlay"
        }, "Available Gift : 1 ")), reactElement.createElement("td", null, reactElement.createElement("a", {
            class: "wgb-add-gift-btn btn-click-add-gift-button",
            href: "#"
        }, " Add Gift "))))), reactElement.createElement("div", {
            class: "dataTables_info"
        }, "Showing page 1 of 1"), reactElement.createElement("div", {
            class: "dataTables_paginate paging_simple_numbers"
        }, reactElement.createElement("a", {
            class: "paginate_button previous disabled"
        }, "previous"), reactElement.createElement("span", null, reactElement.createElement("a", {
            class: "paginate_button current"
        }, "1")), reactElement.createElement("a", {
            class: "paginate_button next disabled",
        }, "next")))));
    },
});