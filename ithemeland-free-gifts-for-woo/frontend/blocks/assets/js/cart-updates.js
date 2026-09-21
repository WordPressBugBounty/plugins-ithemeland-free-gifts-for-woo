jQuery(document).ready(function ($) {
    let lastCartTotal = "";
    let refreshTimer = null;
    let pendingRequest = null;
    let requestSequence = 0;
    let lastRequestedFingerprint = "";

    function getCartFingerprint() {
        const cartBlock = document.querySelector(".wp-block-woocommerce-cart");
        if (!cartBlock) return "";

        const cartState = cartBlock.cloneNode(true);
        cartState.querySelectorAll(".wp-block-wgb-wc-gift").forEach(function (giftBlock) {
            giftBlock.remove();
        });
        return cartState.textContent.replace(/\s+/g, " ").trim();
    }

    function refreshGiftBlocks() {
        // Get the main gift block container
        var $block = $(".wp-block-wgb-wc-gift").first();

        if ($block.length === 0) {
            return;
        }

        // Get the type from the inner span
        var $typeSpan = $block.find(".itg-shortoces-products");
        var blockType = $typeSpan.data("type");

        const fingerprint = getCartFingerprint();
        if (fingerprint && fingerprint === lastRequestedFingerprint) {
            return;
        }
        lastRequestedFingerprint = fingerprint;

        if (pendingRequest && pendingRequest.readyState !== 4) {
            pendingRequest.abort();
        }

        const currentSequence = ++requestSequence;
        let requestSucceeded = false;

        pendingRequest = $.ajax({
            url: wgb_vars.ajaxurl,
            data: {
                action: "update_block_cart_content",
                type: blockType,
                security: wgb_vars.nonce,
            },
            success: function (response) {
                if (currentSequence !== requestSequence) return;
                if (response && response.success && response.data && response.data.html) {
                    requestSucceeded = true;
                    // Clean up duplicate blocks
                    var $newContent = $(response.data.html);
                    var $innerBlock = $newContent.find(".wp-block-wgb-wc-gift").first();

                    if ($innerBlock.length) {
                        $block.html($innerBlock.html());
                    } else {
                        $block.html(response.data.html);
                    }

                    // Initialize carousel after content update
                    $(document.body).trigger("it-enhanced-carousel");
                }
            },
            error: function (xhr, status, error) {
                if (status === "abort") return;
                console.error("AJAX Error:", {
                    status: status,
                    error: error,
                    response: xhr.responseText,
                });
            },
            complete: function () {
                if (currentSequence === requestSequence) {
                    if (!requestSucceeded) {
                        lastRequestedFingerprint = "";
                    }
                    pendingRequest = null;
                }
            },
        });
    }

    function refreshGiftBlock() {
        if (refreshTimer) {
            clearTimeout(refreshTimer);
        }
        // Debounce all WooCommerce and DOM events into one refresh.
        refreshTimer = setTimeout(function () {
            refreshTimer = null;
            refreshGiftBlocks();
        }, 350);
    }

    // Monitor cart changes
    function monitorCartChanges() {
        // Find the cart block
        const cartBlock = document.querySelector(".wp-block-woocommerce-cart");

        if (cartBlock) {
            // Set up mutation observer for cart changes
            const observer = new MutationObserver(function (mutations) {
                // Check if cart totals changed
                const cartTotals = cartBlock.querySelector(".wc-block-components-totals-footer-item");
                if (cartTotals) {
                    const currentTotal = cartTotals.textContent.trim();

                    // Only proceed if the cart total has actually changed.
                    if (currentTotal !== lastCartTotal) {
                        lastCartTotal = currentTotal;
                        refreshGiftBlock();
                    }
                }
            });

            // Observe the entire cart block for changes
            observer.observe(cartBlock, {
                childList: true,
                subtree: true,
                characterData: true,
                attributes: true,
            });
        }
    }

    // Initial setup
    monitorCartChanges();

    // Handle cart updates
    $(document.body).on("updated_cart_totals", function () {
        refreshGiftBlock();
    });

    // Additional event listener for cart updates
    $(document.body).on("wc_fragments_refreshed", function () {
        refreshGiftBlock();
    });

    // Handle remove button click for WooCommerce Blocks
    var removeBtn = document.getElementsByClassName("wc-block-cart-item__remove-link");

    $(document).on("click", removeBtn, function (e) {
        // Check if the clicked element is actually the remove button
        if (e.target === this || e.target.closest(".wc-block-cart-item__remove-link")) {
            setTimeout(function () {
                refreshGiftBlock();
            }, 500);
        }
    });

    //Handle both classic and block cart remove buttons
    $(document.body).on("click", ".wgb-add-gift-btn", function (e) {
        // Let WooCommerce handle the action first

        setTimeout(function () {
            refreshGiftBlock();
        }, 500);
    });
});
