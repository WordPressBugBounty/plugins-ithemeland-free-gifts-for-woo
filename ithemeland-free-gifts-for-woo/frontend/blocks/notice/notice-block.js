var reactElement = window.wp.element,
    blocks = wp.blocks;

blocks.registerBlockType('wgb/gift-notice', {
    title: 'Gift Notice',
    icon: 'media-text',
    category: 'woocommerce',
    attributes: {
        notice_message: {
            type: 'string',
            default: 'Based on your Current Cart Contents, you are eligible for Free Gift(s). Choose your gift [popup_link] [cart_link]',
        },
        button_label: {
            type: 'string',
            default: 'Here',
        },
    },
    edit: function (props) {
        function updateNoticeMessage(event) {
            props.setAttributes({
                notice_message: event.target.value
            });
        }

        function updateButtonLabel(event) {
            props.setAttributes({
                button_label: event.target.value
            });
        }

        return reactElement.createElement("div", props, reactElement.createElement("div", {
            class: "wgb-blocks-notice-half-container"
        }, reactElement.createElement("label", null, "Free Gift Notice"), reactElement.createElement("input", {
            type: "text",
            value: props.attributes.notice_message,
            placeholder: "Message ...",
            onChange: updateNoticeMessage
        }), reactElement.createElement("span", null, "[popup_link] : Popup in notice"), reactElement.createElement("span", null, "[cart_link] : link for Redirect to the cart page")), reactElement.createElement("div", {
            class: "wgb-blocks-notice-half-container"
        }, reactElement.createElement("label", null, "[popup_link]|[cart_link] Shortcode Label"), reactElement.createElement("input", {
            type: "text",
            value: props.attributes.button_label,
            placeholder: "Here",
            onChange: updateButtonLabel
        })));
    },

    // save: function (props) {
    //     return '<div>iThemeland carousel front</div>';
    // }
});