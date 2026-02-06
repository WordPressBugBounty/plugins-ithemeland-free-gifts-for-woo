/**
 * Auto Update Blocks
 *
 * @since 11.0.0
 */
(() => {
  "use strict";

  function autoUpdateBlocks() {
    // Check if we're in the editor
    if (window.wp && window.wp.data) {
      const wp_data = window.wp.data;

      // Function to update blocks
      function updateBlocks() {
        const store = wp_data.select("core/block-editor");
        if (!store) return;

        const blocks = store.getBlocks();
        const cartBlock = blocks.find((block) => block.name === "woocommerce/cart-items-block");
        const checkoutBlock = blocks.find((block) => block.name === "woocommerce/checkout-fields-block");

        if (cartBlock) {
          const hasGiftBlock = cartBlock.innerBlocks.some((block) => block.name === WGBL_APPEND_DATA.blockName);
          if (!hasGiftBlock) {
            wp_data.dispatch("core/block-editor").insertBlock(
              window.wp.blocks.createBlock(WGBL_APPEND_DATA.blockName, {
                className: `wp-block-wgb-wc-gift-${WGBL_APPEND_DATA.layout}`,
                content: `[itg_gift_products type="${WGBL_APPEND_DATA.layout}"]`,
              }),
              cartBlock.innerBlocks.length,
              cartBlock.clientId
            );
          }
        }

        if (checkoutBlock) {
          const hasGiftBlock = checkoutBlock.innerBlocks.some((block) => block.name === WGBL_APPEND_DATA.blockName);
          if (!hasGiftBlock) {
            wp_data.dispatch("core/block-editor").insertBlock(
              window.wp.blocks.createBlock(WGBL_APPEND_DATA.blockName, {
                className: `wp-block-wgb-wc-gift-${WGBL_APPEND_DATA.layout}`,
                content: `[itg_gift_products type="${WGBL_APPEND_DATA.layout}"]`,
              }),
              checkoutBlock.innerBlocks.length,
              checkoutBlock.clientId
            );
          }
        }

        // Save changes
        if (wp_data.select("core/editor")) {
          wp_data.dispatch("core/editor").savePost();
        }
      }

      // Listen for template changes
      wp_data.subscribe(() => {
        const currentLayout = wp_data.select("core/editor")?.getEditedPostAttribute("meta")?.wgb_layout;
        if (currentLayout && currentLayout !== WGBL_APPEND_DATA.layout) {
          updateBlocks();
        }
      });

      // Initial update
      if (document.readyState === "complete") {
        updateBlocks();
      } else {
        window.addEventListener("load", updateBlocks);
      }
    }
  }

  // Run on page load
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", autoUpdateBlocks);
  } else {
    autoUpdateBlocks();
  }
})();
