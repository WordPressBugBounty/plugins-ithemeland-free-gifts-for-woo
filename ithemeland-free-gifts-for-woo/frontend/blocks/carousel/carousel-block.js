var reactElement = window.wp.element,
    blocks = wp.blocks;

blocks.registerBlockType('wgb/gift-carousel', {
    title: 'Gift Carousel',
    icon: 'slides',
    category: 'woocommerce',
    // attributes: {
    //     speed: {
    //         type: 'number',
    //         default: 5000,
    //     },
    //     mobile: {
    //         type: 'number',
    //         default: 1,
    //     },
    //     tablet: {
    //         type: 'number',
    //         default: 3,
    //     },
    //     desktop: {
    //         type: 'number',
    //         default: 5,
    //     },
    //     loop: {
    //         type: 'boolean',
    //         default: false,
    //     },
    //     show_dots: {
    //         type: 'boolean',
    //         default: false,
    //     },
    //     show_nav: {
    //         type: 'boolean',
    //         default: false,
    //     },
    //     right_to_left: {
    //         type: 'boolean',
    //         default: false,
    //     },
    // },
    edit: function (props) {
        // function updateSpeed(event) {
        //     props.setAttributes({
        //         speed: parseInt(event.target.value)
        //     });
        // }

        // function updateMobile(event) {
        //     props.setAttributes({
        //         mobile: parseInt(event.target.value)
        //     });
        // }

        // function updateTablet(event) {
        //     props.setAttributes({
        //         tablet: parseInt(event.target.value)
        //     });
        // }

        // function updateDesktop(event) {
        //     props.setAttributes({
        //         desktop: parseInt(event.target.value)
        //     });
        // }

        // function updateLoop(event) {
        //     props.setAttributes({
        //         loop: event.target.checked
        //     });
        // }

        // function updateShowDots(event) {
        //     props.setAttributes({
        //         show_dots: event.target.checked
        //     });
        // }

        // function updateShowNav(event) {
        //     props.setAttributes({
        //         show_nav: event.target.checked
        //     });
        // }

        // function updateRightToLeft(event) {
        //     props.setAttributes({
        //         right_to_left: event.target.checked
        //     });
        // }

        // return reactElement.createElement("div", null, reactElement.createElement("div", null, reactElement.createElement("h2", null, "Gift Carousel")), reactElement.createElement("div", {
        //     class: "wgb-blocks-notice-half-container"
        // }, reactElement.createElement("div", null, reactElement.createElement("label", null, "Slide Speed in Milliseconds"), reactElement.createElement("input", {
        //     type: "number",
        //     name: "wgb_speed",
        //     value: props.attributes.speed,
        //     placeholder: "Slide Speed ...",
        //     onChange: updateSpeed
        // })), reactElement.createElement("div", null, reactElement.createElement("label", null, "Number Items in Mobile"), reactElement.createElement("input", {
        //     type: "number",
        //     name: "mobile",
        //     value: props.attributes.mobile,
        //     placeholder: "Number Items ...",
        //     onChange: updateMobile
        // })), reactElement.createElement("div", null, reactElement.createElement("label", null, "Number Items in Tablet"), reactElement.createElement("input", {
        //     type: "number",
        //     name: "tablet",
        //     value: props.attributes.tablet,
        //     placeholder: "Number Items ...",
        //     onChange: updateTablet
        // })), reactElement.createElement("div", null, reactElement.createElement("label", null, "Number Items in Desktop"), reactElement.createElement("input", {
        //     type: "number",
        //     name: "desktop",
        //     value: props.attributes.desktop,
        //     placeholder: "Number Items ...",
        //     onChange: updateDesktop
        // }))), reactElement.createElement("div", {
        //     class: "wgb-blocks-notice-half-container"
        // }, reactElement.createElement("div", null, reactElement.createElement("label", null, "Loop"), reactElement.createElement("input", {
        //     type: "checkbox",
        //     name: "loop",
        //     value: true,
        //     checked: (props.attributes.loop === true),
        //     onChange: updateLoop
        // })), reactElement.createElement("div", null, reactElement.createElement("label", null, "Show Dots"), reactElement.createElement("input", {
        //     type: "checkbox",
        //     name: "show_dots",
        //     value: true,
        //     checked: (props.attributes.show_dots === true),
        //     onChange: updateShowDots
        // })), reactElement.createElement("div", null, reactElement.createElement("label", null, "Show Nav"), reactElement.createElement("input", {
        //     type: "checkbox",
        //     name: "show_nav",
        //     value: true,
        //     checked: (props.attributes.show_nav === true),
        //     onChange: updateShowNav
        // })), reactElement.createElement("div", null, reactElement.createElement("label", null, "Right to left"), reactElement.createElement("input", {
        //     type: "checkbox",
        //     name: "right_to_left",
        //     value: true,
        //     checked: (props.attributes.right_to_left === true),
        //     onChange: updateRightToLeft
        // }))));


        return reactElement.createElement("div", {
            class: "adv-gift-section wgb-product-cnt wgb-frontend-gifts wgb-item-layout2"
        }, reactElement.createElement("div", {
            class: "wgb-header-cnt"
        }, reactElement.createElement("h2", {
            class: "wgb-title text-capitalize font-weight-bold"
        }, "Our Gift")), reactElement.createElement("div", {
            class: "wgb-owl-carousel"
        }, reactElement.createElement("div", {
            class: "owl-item"
        }, reactElement.createElement("div", {
            class: "wgb-product-item-cnt"
        }, reactElement.createElement("div", {
            class: "wgb-item-thumb"
        }, reactElement.createElement("img", {
            width: "300",
            height: "300",
            src: WGBL_CAROUSEL_DATA.images.wc_placeholder,
            class: "attachment-woocommerce_thumbnail size-woocommerce_thumbnail",
            alt: ""
        }), reactElement.createElement("div", {
            class: "wgb-item-overlay"
        }), reactElement.createElement("div", {
            class: "wgb-stock"
        }, reactElement.createElement("div", {
            class: "gift-product-stock"
        }, "Available Gift : 1"))), reactElement.createElement("div", {
            class: "wgb-item-content"
        }, reactElement.createElement("h2", {
            class: "wgb-item-title font-weight-bold"
        }, "Gift 1"), reactElement.createElement("div", {
            class: "gift-price"
        }, reactElement.createElement("del", null, reactElement.createElement("span", {
            class: "woocommerce-Price-amount amount"
        }, reactElement.createElement("span", {
            class: "woocommerce-Price-currencySymbol"
        }, "$"), "3,00")), reactElement.createElement("ins", null, "Free"))), reactElement.createElement("a", {
            class: "wgb-add-gift-btn btn-click-add-gift-button",
            href: "#"
        }, "Add Gift"))), reactElement.createElement("div", {
            class: "owl-item"
        }, reactElement.createElement("div", {
            class: "wgb-product-item-cnt"
        }, reactElement.createElement("div", {
            class: "wgb-item-thumb"
        }, reactElement.createElement("img", {
            width: "300",
            height: "300",
            src: WGBL_CAROUSEL_DATA.images.wc_placeholder,
            class: "attachment-woocommerce_thumbnail size-woocommerce_thumbnail",
            alt: ""
        }), reactElement.createElement("div", {
            class: "wgb-item-overlay"
        }), reactElement.createElement("div", {
            class: "wgb-stock"
        }, reactElement.createElement("div", {
            class: "gift-product-stock"
        }, "Available Gift : 1"))), reactElement.createElement("div", {
            class: "wgb-item-content"
        }, reactElement.createElement("h2", {
            class: "wgb-item-title font-weight-bold"
        }, "Gift 2"), reactElement.createElement("div", {
            class: "gift-price"
        }, reactElement.createElement("del", null, reactElement.createElement("span", {
            class: "woocommerce-Price-amount amount"
        }, reactElement.createElement("span", {
            class: "woocommerce-Price-currencySymbol"
        }, "$"), "3,00")), reactElement.createElement("ins", null, "Free"))), reactElement.createElement("a", {
            class: "wgb-add-gift-btn btn-click-add-gift-button",
            href: "#"
        }, "Add Gift"))), reactElement.createElement("div", {
            class: "owl-item"
        }, reactElement.createElement("div", {
            class: "wgb-product-item-cnt"
        }, reactElement.createElement("div", {
            class: "wgb-item-thumb"
        }, reactElement.createElement("img", {
            width: "300",
            height: "300",
            src: WGBL_CAROUSEL_DATA.images.wc_placeholder,
            class: "attachment-woocommerce_thumbnail size-woocommerce_thumbnail",
            alt: ""
        }), reactElement.createElement("div", {
            class: "wgb-item-overlay"
        }), reactElement.createElement("div", {
            class: "wgb-stock"
        }, reactElement.createElement("div", {
            class: "gift-product-stock"
        }, "Available Gift : 1"))), reactElement.createElement("div", {
            class: "wgb-item-content"
        }, reactElement.createElement("h2", {
            class: "wgb-item-title font-weight-bold"
        }, "Gift 3"), reactElement.createElement("div", {
            class: "gift-price"
        }, reactElement.createElement("del", null, reactElement.createElement("span", {
            class: "woocommerce-Price-amount amount"
        }, reactElement.createElement("span", {
            class: "woocommerce-Price-currencySymbol"
        }, "$"), "3,00")), reactElement.createElement("ins", null, "Free"))), reactElement.createElement("a", {
            class: "wgb-add-gift-btn btn-click-add-gift-button",
            href: "#"
        }, "Add Gift"))), reactElement.createElement("div", {
            class: "owl-item"
        }, reactElement.createElement("div", {
            class: "wgb-product-item-cnt"
        }, reactElement.createElement("div", {
            class: "wgb-item-thumb"
        }, reactElement.createElement("img", {
            width: "300",
            height: "300",
            src: WGBL_CAROUSEL_DATA.images.wc_placeholder,
            class: "attachment-woocommerce_thumbnail size-woocommerce_thumbnail",
            alt: ""
        }), reactElement.createElement("div", {
            class: "wgb-item-overlay"
        }), reactElement.createElement("div", {
            class: "wgb-stock"
        }, reactElement.createElement("div", {
            class: "gift-product-stock"
        }, "Available Gift : 1"))), reactElement.createElement("div", {
            class: "wgb-item-content"
        }, reactElement.createElement("h2", {
            class: "wgb-item-title font-weight-bold"
        }, "Gift 4"), reactElement.createElement("div", {
            class: "gift-price"
        }, reactElement.createElement("del", null, reactElement.createElement("span", {
            class: "woocommerce-Price-amount amount"
        }, reactElement.createElement("span", {
            class: "woocommerce-Price-currencySymbol"
        }, "$"), "3,00")), reactElement.createElement("ins", null, "Free"))), reactElement.createElement("a", {
            class: "wgb-add-gift-btn btn-click-add-gift-button",
            href: "#"
        }, "Add Gift"))), reactElement.createElement("div", {
            class: "owl-item"
        }, reactElement.createElement("div", {
            class: "wgb-product-item-cnt"
        }, reactElement.createElement("div", {
            class: "wgb-item-thumb"
        }, reactElement.createElement("img", {
            width: "300",
            height: "300",
            src: WGBL_CAROUSEL_DATA.images.wc_placeholder,
            class: "attachment-woocommerce_thumbnail size-woocommerce_thumbnail",
            alt: ""
        }), reactElement.createElement("div", {
            class: "wgb-item-overlay"
        }), reactElement.createElement("div", {
            class: "wgb-stock"
        }, reactElement.createElement("div", {
            class: "gift-product-stock"
        }, "Available Gift : 1"))), reactElement.createElement("div", {
            class: "wgb-item-content"
        }, reactElement.createElement("h2", {
            class: "wgb-item-title font-weight-bold"
        }, "Gift 5"), reactElement.createElement("div", {
            class: "gift-price"
        }, reactElement.createElement("del", null, reactElement.createElement("span", {
            class: "woocommerce-Price-amount amount"
        }, reactElement.createElement("span", {
            class: "woocommerce-Price-currencySymbol"
        }, "$"), "3,00")), reactElement.createElement("ins", null, "Free"))), reactElement.createElement("a", {
            class: "wgb-add-gift-btn btn-click-add-gift-button",
            href: "#"
        }, "Add Gift")))));
    },
});