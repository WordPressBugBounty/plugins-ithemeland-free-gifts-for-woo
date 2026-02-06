/**
 * Editor Block
 *
 * @since 11.0.0
 */

(() => {
  "use strict";

  var reactElement = window.wp.element,
    blocks = window.wp.blocks,
    blockEditor = window.wp.blockEditor,
    wp_components = window.wp.components;

  console.log("blocks: ", wp_components);

  const FreeGiftsBlock = {
    cartSchema: JSON.parse(
      '{"name":"woocommerce/wgb-wc-cart-free-gifts-block","icon":"cart","keywords":["free","gifts"],"version":"1.0.0","title":"iT Free Gifts","description":"Shows the free gifts layout in the cart block.","category":"woocommerce","supports":{"align":false,"html":false,"multiple":false,"reusable":false},"attributes":{"className":{"type":"string","default":""},"lock":{"type":"object","default":{"remove":true,"move":false}}},"parent":["woocommerce/cart-items-block"],"textdomain":"ithemeland-free-gifts-for-woo","apiVersion":2}'
    ),
    checkoutSchema: JSON.parse(
      '{"name":"woocommerce/wgb-wc-checkout-free-gifts-block","icon":"cart","keywords":["free","gifts"],"version":"1.0.0","title":"iT Free Gifts","description":"Shows the free gifts layout in the checkout block.","category":"woocommerce","supports":{"align":false,"html":false,"multiple":false,"reusable":false},"attributes":{"className":{"type":"string","default":""},"lock":{"type":"object","default":{"remove":true,"move":false}}},"parent":["woocommerce/checkout-fields-block"],"textdomain":"ithemeland-free-gifts-for-woo","apiVersion":2}'
    ),
    getElement: function (e) {
      return reactElement.createElement(wp_components.Disabled, {}, reactElement.createElement(reactElement.Fragment, {}, FreeGiftsBlock.getFormField()));
    },
    getFormField: function () {
      return reactElement.createElement(reactElement.RawHTML, { className: "wgb-free-gifts-block" }, free_gifts_preview_html);
    },
    edit: function (attributes) {
      return reactElement.createElement("div", blockEditor.useBlockProps(), FreeGiftsBlock.getElement());
    },
    save: function (e) {
      return reactElement.createElement("div", blockEditor.useBlockProps.save());
    },
  };

  // Register inner block of free gifts in the cart block.
  blocks.registerBlockType(FreeGiftsBlock.cartSchema.name, {
    ...FreeGiftsBlock.cartSchema,
    edit: FreeGiftsBlock.edit,
    save: FreeGiftsBlock.save,
  });
  // Register inner block of free gifts in the checkout block.s
  blocks.registerBlockType(FreeGiftsBlock.checkoutSchema.name, {
    ...FreeGiftsBlock.checkoutSchema,
    edit: FreeGiftsBlock.edit,
    save: FreeGiftsBlock.save,
  });
})();
