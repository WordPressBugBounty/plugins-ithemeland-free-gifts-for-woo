/**
 * Editor Block
 *
 * @since 11.0.0
 */
(() => {
    "use strict";

    function registerGiftBlock() {
        var reactElement = window.wp.element,
            blocks = window.wp.blocks,
            blockEditor = window.wp.blockEditor,
            wp_components = window.wp.components,
            wp_data = window.wp.data;

        const layout = WGBL_APPEND_DATA.layout;
        //const blockName = WGBL_APPEND_DATA.blockName;
        const blockHtmlLayout = WGBL_APPEND_DATA.blockHtmlLayout;

        // Function to update blocks in cart/checkout
        function updateBlocksInCartCheckout() {
            const store = wp_data.select("core/block-editor");
            if (!store) {
                return;
            }
        }

        // Initial update
        if (document.readyState === "complete") {
            updateBlocksInCartCheckout();
        } else {
            window.addEventListener("load", function () {
                updateBlocksInCartCheckout();
            });
        }

        const FreeGiftsBlock = {
            cartSchema: {
                name: "wgb/wc-gift",
                icon: "cart",
                keywords: ["free", "gifts"],
                version: "1.0.0",
                title: layout,
                description: "Shows the free gifts layout in the cart block.",
                category: "woocommerce",
                supports: {
                    align: false,
                    html: true,
                    multiple: false,
                    reusable: false,
                },
                attributes: {
                    className: {
                        type: "string",
                        default: "",
                    },
                    content: {
                        type: "string",
                        default: `[itg_gift_products type="${WGBL_APPEND_DATA.layout}"]`,
                    },
                },
                parent: ["woocommerce/cart-items-block"],
                textdomain: "free-gifts-for-woocommerce",
                apiVersion: 2,
            },
            checkoutSchema: {
                name: "wgb/wc-gift",
                icon: "cart",
                keywords: ["free", "gifts"],
                version: "1.1.0",
                title: "Free Gifts",
                description: "Shows the free gifts layout in the checkout block.",
                category: "woocommerce",
                supports: {
                    align: false,
                    html: true,
                    multiple: false,
                    reusable: false,
                },
                attributes: {
                    className: {
                        type: "string",
                        default: "",
                    },
                    content: {
                        type: "string",
                        default: `[itg_gift_products type="${WGBL_APPEND_DATA.layout}"]`,
                    },
                },
                parent: ["woocommerce/checkout-fields-block"],
                textdomain: "free-gifts-for-woocommerce",
                apiVersion: 2,
            },
            getElement: function (e) {
                return reactElement.createElement(wp_components.Disabled, {}, reactElement.createElement(reactElement.Fragment, {}, FreeGiftsBlock.getFormField()));
            },
            getFormField: function () {
                return reactElement.createElement(reactElement.RawHTML, { className: "wgb-free-gifts-block" }, blockHtmlLayout);
            },
            edit: function (props) {
                // Check if the saved block name matches the current setting
                if (props.name !== WGBL_APPEND_DATA.blockName) {
                    // If not, replace the old block with the new one
                    wp_data.dispatch("core/block-editor").replaceBlocks(
                        props.clientId,
                        blocks.createBlock(WGBL_APPEND_DATA.blockName, {
                            className: `wp-block-wgb-wc-gift`,
                            content: `[itg_gift_products type="${WGBL_APPEND_DATA.layout}"]`,
                        })
                    );
                    // Return null or an empty div while replacement happens
                    return null;
                }

                // Ensure content is set if it's the correct block type
                if (!props.attributes.content) {
                    props.setAttributes({ content: `[itg_gift_products type="${WGBL_APPEND_DATA.layout}"]` });
                }

                // Add effect to monitor layout changes
                reactElement.useEffect(() => {
                    updateBlocksInCartCheckout();
                }, [WGBL_APPEND_DATA.layout]);

                return reactElement.createElement("div", blockEditor.useBlockProps(), reactElement.createElement(reactElement.RawHTML, {}, blockHtmlLayout));
            },
            save: function (props) {
                const blockProps = blockEditor.useBlockProps.save({
                    className: `wp-block-wgb-wc-gift`,
                });

                // Return empty div to prevent premature rendering on the frontend
                return reactElement.createElement("div", blockProps);
            },
        };

        // Register inner block of free gifts in the cart block.
        blocks.registerBlockType(FreeGiftsBlock.cartSchema.name, {
            ...FreeGiftsBlock.cartSchema,
            edit: FreeGiftsBlock.edit,
            save: FreeGiftsBlock.save,
        });

        // Register inner block of free gifts in the checkout block.
        blocks.registerBlockType(FreeGiftsBlock.checkoutSchema.name, {
            ...FreeGiftsBlock.checkoutSchema,
            edit: FreeGiftsBlock.edit,
            save: FreeGiftsBlock.save,
        });
    }

    // Try to register immediately if dependencies are available
    if (window.wp && window.wp.blocks && window.wp.element && window.wp.blockEditor && window.wp.components && window.wp.data) {
        registerGiftBlock();
    }
})();
