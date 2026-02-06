"use strict";
jQuery(document).ready(function ($) {
    // Debug AJAX settings
    //   console.log("Gift plugin AJAX settings:", {
    //     ajax_url: pw_wc_gift_adv_ajax.ajaxurl,
    //     security: pw_wc_gift_adv_ajax.security,
    //     add_gift_ajax_manual: pw_wc_gift_adv_ajax.add_gift_ajax_manual,
    //     is_block_cart: pw_wc_gift_adv_ajax.is_block_cart,
    //     is_block_checkout: pw_wc_gift_adv_ajax.is_block_checkout,
    //   });

    //DataTable
    if (jQuery("html").find(".it-gift-products-table").length > 0 && jQuery.fn.DataTable) {
        jQuery(".it-gift-products-table").DataTable({
            ordering: false,
            bDestroy: true,
            language: {
                search: pw_wc_gift_adv_ajax.language_search,
                info: pw_wc_gift_adv_ajax.language_info,
                paginate: {
                    first: pw_wc_gift_adv_ajax.language_first,
                    previous: pw_wc_gift_adv_ajax.language_previous,
                    next: pw_wc_gift_adv_ajax.language_next,
                    last: pw_wc_gift_adv_ajax.language_last,
                },
            },
        });
        jQuery(".scrollbar-macosx").scrollbar();
    }

    jQuery(document.body).on("updated_cart_totals", function () {
        if (jQuery("html").find(".it-gift-products-table").length > 0 && jQuery.fn.DataTable) {
            jQuery(".it-gift-products-table").DataTable({
                ordering: false,
                bDestroy: true,
            });
        }

        //DropDown
        if (jQuery("html").find(".wgb-gift-products-dropdown").length > 0) {
            jQuery(".wgb-gift-products-dropdown").ddslick("destroy");
            jQuery(".wgb-gift-products-dropdown").ddslick({
                imagePosition: "left",
                selectText: pw_wc_gift_adv_ajax.language_select_your_gift,
                onSelected: function (data) {
                    if (data.selectedData && data.selectedData != "") {
                        if (pw_wc_gift_adv_ajax.add_gift_ajax_manual == "true") {
                            AjaxCall_addToCart(data.selectedData.value);
                        } else {
                            ReloadCall_addToCart(data.selectedData.value);
                        }
                    }
                },
            });
        }
    });

    $(document.body).on("it-enhanced-carousel", function () {
        if (jQuery("html").find(".wgb-gift-products-dropdown").length > 0) {
            jQuery(".wgb-gift-products-dropdown").ddslick({
                selectText: pw_wc_gift_adv_ajax.language_select_your_gift,
                imagePosition: "left",
                onSelected: function (data) {
                    if (data.selectedData && data.selectedData != "") {
                        if (pw_wc_gift_adv_ajax.add_gift_ajax_manual == "true") {
                            AjaxCall_addToCart(data.selectedData.value);
                        } else {
                            ReloadCall_addToCart(data.selectedData.value);
                        }
                    }
                },
            });
        }
    });

    //DropDown
    if (jQuery("html").find(".wgb-gift-products-dropdown").length > 0) {
        jQuery(".wgb-gift-products-dropdown").ddslick({
            selectText: pw_wc_gift_adv_ajax.language_select_your_gift,
            imagePosition: "left",
            onSelected: function (data) {
                if (data.selectedData && data.selectedData != "") {
                    if (pw_wc_gift_adv_ajax.add_gift_ajax_manual == "true") {
                        AjaxCall_addToCart(data.selectedData.value);
                    } else {
                        ReloadCall_addToCart(data.selectedData.value);
                    }
                }
            },
        });
    }

    //Add Gift Button
    jQuery(document).on("click", ".btn-click-add-gift-button", function (e) {
        e.preventDefault();

        jQuery(this).css({ color: "transparent" });
        jQuery(".gift_cart_ajax").show();
        jQuery(this).find(".wgb-loading-icon").removeClass("wgb-d-none");
        jQuery(".popup-inner-loader").removeClass("wgb-d-none");

        var gift_id = jQuery(this).data("gift_id");

        var qty = 1;
        var qunatity_field = 1;
        qunatity_field = jQuery(this).closest(".itg-gift-product-add-to-cart-actions").find(".itg-gift-product-qty");
        if (qunatity_field.length && qunatity_field.val()) {
            qty = qunatity_field.val();
        }

        var mode = jQuery(this).data("itg_mode");

        // Check if AJAX is enabled and working
        if (pw_wc_gift_adv_ajax.add_gift_ajax_manual == "true" && pw_wc_gift_adv_ajax.ajaxurl) {

            // Add a fallback for status 204 issues
            var ajaxTimeout = setTimeout(function () {
                $(".wgb-loading-icon").addClass("wgb-d-none");
                $(".popup-inner-loader").addClass("wgb-d-none");
                ReloadCall_addToCart(gift_id, qty);
            }, 10000);

            AjaxCall_addToCart(gift_id, qty, mode);

            // Clear timeout on success
            $(document).one("ajaxSuccess", function () {
                clearTimeout(ajaxTimeout);
            });
        } else {
            ReloadCall_addToCart(gift_id, qty);
        }
    });

    //Select Gift Button
    jQuery(document).on("click", ".btn-select-gift-button", function (e) {
        e.preventDefault();
        $(".wgb-popup-box").removeClass("wgb-page-scaleDownUp");
        $(".wgb-popup-box").addClass("wgb-page-scaleUp");
        $(".wgb-popup-box .popup-inner-loader").removeClass("wgb-d-none");
        $(".wgb-popup-posts").html("");

        if ($("#wgb-modal").length) {
            $(".wgb-popup-loading").show();
            $("#wgb-modal").css("display", "block");
            $.ajax({
                url: pw_wc_gift_adv_ajax.ajaxurl,
                type: "POST",
                data: {
                    action: pw_wc_gift_adv_ajax.action_show_variation,
                    pw_gift_variable: jQuery(this).data("id"),
                    pw_gift_uid: jQuery(this).data("rule-id"),
                    security: pw_wc_gift_adv_ajax.security,
                },
                success: function (resp) {
                    $(".wgb-popup-loading").hide();
                    $(".wgb-popup-box .popup-inner-loader").addClass("wgb-d-none");
                    $(".wgb-popup-posts")
                        .html(resp)
                        .ready(function () {
                            let bodyHeight = parseInt($(".wgb-list-items").height()) + parseInt($(".wgb-popup-footer").height());
                            if (bodyHeight > 100) {
                                $(".wgb-popup-box").css({ height: parseInt(bodyHeight) + "px" });
                            }
                            $("body").addClass("modal-opened");
                            $(".wgb-popup-box").addClass("wgb-page-current");
                            $(".wgb-popup").addClass("wgb-active-modal");
                            jQuery(".scrollbar-macosx").scrollbar();
                        });
                },
                error: function () {
                    $(".wgb-popup-box .popup-inner-loader").addClass("wgb-d-none");
                },
            });
        }

        //Close on Click out of modal
        // $("#wgb-modal").on('click', function (e) {
        //     $("#wgb-modal").remove();
        // });
    });

    //Close on ESC
    $(document).keyup(function (e) {
        if (e.key === "Escape") {
            // escape key maps to keycode `27`
            $(".wgb-popup-close").trigger("click");
        }
    });

    jQuery(document).on("click", ".btn-select-gift-popup-button", function (e) {
        e.preventDefault();

        $(".wgb-popup-box").removeClass("wgb-page-scaleDownUp");
        $(".wgb-popup-box").addClass("wgb-page-scaleUp");

        show_popup_in_checkout();

        //Close on Click out of modal
        // $("#wgb-modal").on('click', function (e) {
        //     $("#wgb-modal").remove();
        // });
    });

    jQuery(document).on("click", ".itg-popup-close", function (e) {
        let modal = $(this).closest(".wgb-popup");
        $(".wgb-loading-icon").addClass("wgb-d-none");
        $(".wgb-popup-box").removeClass("wgb-page-scaleUp");
        $(".wgb-popup-box").addClass("wgb-page-scaleDownUp");
        setTimeout(function () {
            $("body").removeClass("modal-opened");
            modal.hide();
        }, 700);

        // jQuery('.scrollbar-macosx').scrollbar('destroy');
    });

    if (jQuery("html").find(".itg-auto-show-popup").length > 0) {
        show_popup_in_checkout();
    }

    $(document).on("click", ".wgb-qty-decrease-btn", function () {
        let input = $(this).closest(".qty").find('input[type="number"]');
        if (input.attr("min") && parseInt(input.attr("min")) < parseInt(input.val())) {
            input.val(parseInt(input.val()) - 1);
        }
    });

    $(document).on("click", ".wgb-qty-increase-btn", function () {
        let input = $(this).closest(".qty").find('input[type="number"]');
        if (input.attr("max") && parseInt(input.attr("max")) > parseInt(input.val())) {
            input.val(parseInt(input.val()) + 1);
        }
    });

    /**
     * Update on payment method change
     */

    jQuery("body").on("updated_checkout", set_up_payment_method_checkout_update);
    set_up_payment_method_checkout_update();

    /**
     * Update on payment method change
     */
    function set_up_payment_method_checkout_update() {
        jQuery('form.checkout input[name="payment_method"]').each(function () {
            if (typeof jQuery(this).data("itg_payment_method_checkout_update") === "undefined") {
                jQuery(this).on("change", function () {
                    $.ajax({
                        url: pw_wc_gift_adv_ajax.ajaxurl,
                        type: "POST",
                        dataType: "json",
                        data: {
                            action: "wgb_check_rule_after_update_checkout",
                        },
                        success: function (response) {
                            if (response.data.gift_avilible == "yes") {
                                if (pw_wc_gift_adv_ajax.checkout_auto_load == "true") {
                                    show_popup_in_checkout();
                                }
                                //if($(".itg-checkout-notice").length>0){
                                //	$(".itg-checkout-notice").parent().show();
                                //}
                            } else {
                                if ($(".itg-checkout-notice").length > 0) {
                                    $(".itg-checkout-notice").parent().hide();
                                }
                            }
                        },
                        error: function () { },
                    });
                    jQuery("body").trigger("update_checkout");
                });
                jQuery(this).data("itg_payment_method_checkout_update", true);
            }
        });
    }

    function show_popup_in_checkout() {
        $(".popup-inner-loader").removeClass("wgb-d-none");
        $(".wgb-popup-posts").html("");
        $(".wgb-popup-loading").show();

        if ($("#wgb-modal").length) {
            $("#wgb-modal").css("display", "block");

            $.ajax({
                url: pw_wc_gift_adv_ajax.ajaxurl,
                type: "POST",
                data: {
                    action: pw_wc_gift_adv_ajax.action_gift_show_popup_checkout,
                    security: pw_wc_gift_adv_ajax.security,
                },
                success: function (response) {
                    $(".wgb-popup-posts")
                        .html(response.data.result)
                        .ready(function () {
                            let bodyHeight = parseInt($(".wgb-list-items").height()) + parseInt($(".wgb-popup-footer").height());
                            if (bodyHeight > 100) {
                                $(".wgb-popup-box").css({ height: parseInt(bodyHeight) + "px" });
                            }

                            $(".wgb-popup-loading").hide();
                            $(".popup-inner-loader").addClass("wgb-d-none");
                            $("body").addClass("modal-opened");
                            $(".wgb-popup-box").removeClass("wgb-page-scaleDownUp");
                            $(".wgb-popup-box").addClass("wgb-page-scaleUp");
                            $(".wgb-popup-box").addClass("wgb-page-current");
                            $(".wgb-popup").addClass("wgb-active-modal");
                            jQuery(".scrollbar-macosx").scrollbar();
                            if (response.data.layout == "carousel") {
                                $(document.body).trigger("it-enhanced-carousel");
                            }
                            setTimeout(function () {
                                $(window).trigger("resize");
                            }, 150);
                        });
                },
                error: function () {
                    $(".wgb-popup-box .popup-inner-loader").addClass("wgb-d-none");
                },
            });

            //Close on ESC
            $(document).keyup(function (e) {
                if (e.key === "Escape") {
                    // escape key maps to keycode `27`
                    $(".itg-popup-close").trigger("click");
                }
            });
        }
    }

    function run_ajax_owl_carousels() {
        var owl_carousels = $(".it-owl-carousel-items");
        if (!owl_carousels.length) {
            return;
        }
        owl_carousels.each(function (e) {
            $(this).owlCarousel({
                margin: 10,
                responsiveClass: true,
                rtl: "true" == pw_wc_gift_adv_ajax.rtl,
                autoplayHoverPause: true,
                autoplayTimeout: parseInt(pw_wc_gift_adv_ajax.speed),
                loop: "true" == pw_wc_gift_adv_ajax.loop,
                dots: "true" == pw_wc_gift_adv_ajax.dots,
                nav: "true" == pw_wc_gift_adv_ajax.nav,
                responsive: {
                    0: {
                        items: parseInt(pw_wc_gift_adv_ajax.mobile),
                    },
                    600: {
                        items: parseInt(pw_wc_gift_adv_ajax.tablet),
                    },
                    1000: {
                        items: parseInt(pw_wc_gift_adv_ajax.desktop),
                    },
                },
            });
        });
    }

    function pagination_gifts(selectedPage) {
        $("#wgb-cart-pagination-current-page").text(selectedPage);
        var selected_page = "page_" + selectedPage;
        $(".wgb-frontend-gifts").find(".0").addClass("pw-gift-active");

        $(".wgb-frontend-gifts")
            .find(".pw_gift_pagination_num")
            .click(function (e) {
                e.preventDefault();
                $(".wgb-frontend-gifts")
                    .find("." + selected_page)
                    .addClass("pw-gift-deactive");
                var page = $(this).attr("data-page-id");
                $(".wgb-frontend-gifts")
                    .find("." + page)
                    .siblings(".pw_gift_pagination_div")
                    .removeClass("pw-gift-active");
                $(".wgb-frontend-gifts")
                    .find("." + page)
                    .addClass("pw-gift-active");
                $(".wgb-frontend-gifts")
                    .find("." + page)
                    .removeClass("pw-gift-deactive");
                $(".wgb-frontend-gifts").find(this).parents(".wgb-paging-item").find(".pw_gift_pagination_num").removeClass("wgb-active-page");
                $(".wgb-frontend-gifts").find(this).addClass("wgb-active-page");
                selected_page = page;
                pagination_gifts($(this).attr("data-page-id").replace("page_", ""));
            });
    }

    function Reloaditempopup() {
        $.ajax({
            url: pw_wc_gift_adv_ajax.ajaxurl,
            type: "POST",
            dataType: "json",
            data: {
                action: "itg_reloaditempopup",
                itg_security: pw_wc_gift_adv_ajax.security,
            },
            success: function (response) {
                if (response.data.result == "unselectable") {
                    $(".itg-popup-close").trigger("click");
                    //$('.loader-item').addClass('wgb-d-none');
                } else {
                    $(".wgb-popup-posts").html(response.data.result);
                    $(".wgb-popup-box").addClass("wgb-page-scaleUp");
                    $(".wgb-popup-box").addClass("wgb-page-current");
                    jQuery(".scrollbar-macosx").scrollbar();
                    $(document.body).trigger("it-enhanced-carousel");
                    $(".popup-inner-loader").addClass("wgb-d-none");
                }
            },
            error: function (response) { },
        });
        /**/
    }

    $(document.body).on("updated_cart_totals", function () {
        pagination_gifts(1);
    });

    // A more robust and modern AjaxCall_addToCart function
    async function AjaxCall_addToCart(gift_id, qty = 1, mode = "") {
        // Show loading state immediately
        $(".wgb-loading-icon").removeClass("wgb-d-none");
        $(".popup-inner-loader").removeClass("wgb-d-none");

        try {
            // First AJAX call to add the gift to the cart
            const response = await $.ajax({
                url: pw_wc_gift_adv_ajax.ajaxurl,
                type: "POST",
                dataType: "json",
                data: {
                    action: "ajax_add_free_gifts",
                    itg_security: pw_wc_gift_adv_ajax.security,
                    gift_product_id: gift_id,
                    add_qty: qty,
                },
            });

            // Hide loading state on success
            $(".wgb-loading-icon").addClass("wgb-d-none");
            $(".popup-inner-loader").addClass("wgb-d-none");

            // Check for success from the server
            if (response && response.success) {
                updateCart(response.data.notice ? response.data.notice : "");

                // Handle the Woodmart update with a separate async function for clarity
                if (typeof woodmartThemeModule !== "undefined") {
                    await woodmartUpdateGiftsTable();
                }

                // Handle popup mode
                if (mode == "popup") {
                    Reloaditempopup();
                } else {
                    $(".itg-popup-close").trigger("click");
                }
            } else {
                // Handle error response from server
                const errorMessage = response && response.data && response.data.error ? "Error: " + response.data.error : "An unknown error occurred while adding the gift to cart.";
                alert(errorMessage);
            }
        } catch (xhr) {
            // Hide loading state on error
            $(".wgb-loading-icon").addClass("wgb-d-none");
            $(".popup-inner-loader").addClass("wgb-d-none");

            // const errorResponse = parseErrorResponse(xhr);
            // alert(errorResponse);
        }
    }

    // A separate async function for the Woodmart update call
    async function woodmartUpdateGiftsTable() {
        try {
            await $.ajax({
                url: pw_wc_gift_adv_ajax.ajaxurl,
                type: "POST",
                data: {
                    action: "woodmart_update_gifts_table",
                    security: pw_wc_gift_adv_ajax.security,
                },
            });
            jQuery(document.body).trigger("wc_update_cart");
        } catch (xhr) {
            // You can add a silent failure here, as it's a secondary function
        }
    }

    // A helper function to parse error responses
    function parseErrorResponse(xhr) {
        if (xhr.status === 204) {
            return "Server returned no content. This might be a configuration issue. Please contact support.";
        }
        if (xhr.status === 0) {
            return "Request was blocked or a network error occurred. Please check your connection.";
        }
        try {
            const errorData = JSON.parse(xhr.responseText);
            if (errorData && errorData.data && errorData.data.error) {
                return "Error: " + errorData.data.error;
            }
        } catch (e) {
            // Fallback for non-JSON or unparsable responses
        }
        return "Network error occurred. Please try again.";
    }
    function ReloadCall_addToCart(gift_id, qty = 1) {
        if ("" == gift_id) {
            return false;
        }

        var url = pw_wc_gift_adv_ajax.add_to_cart_link;
        url = url.replace("%s", gift_id);
        url = url.replace("%q", qty);

        window.location.href = url;
    }

    /**
     * Update the cart after any action done.
     * @since 2.0.0
     * @returns {undefined}
     */
    function updateCart(notice) {
        if (pw_wc_gift_adv_ajax.is_block_cart || pw_wc_gift_adv_ajax.is_block_checkout) {
            if (window.wc && window.wc.blocksCheckout) {
                window.wc.blocksCheckout
                    .extensionCartUpdate({
                        namespace: "wgb-free-gifts",
                        data: {
                            action: "refresh_cart",
                        },
                    })
                    .then(() => {
                        jQuery(document.body).trigger("it-enhanced-carousel");
                    })
                    .finally(() => {
                        // show result
                        if (notice && $(".wc-block-store-notices .woocommerce-notices-wrapper").length) {
                            $(".wc-block-store-notices .woocommerce-notices-wrapper").prepend(notice);
                        }
                    });
            } else {
                // Fallback for block cart/checkout without proper WC blocks
                $(document.body).trigger("wc_update_cart");
                $(document.body).trigger("update_checkout");
            }
        } else {
            // For classic cart/checkout
            $(document.body).trigger("wc_update_cart");
            $(document.body).trigger("update_checkout");

            // Add notice if available
            if (notice && $(".woocommerce-notices-wrapper").length) {
                $(".woocommerce-notices-wrapper").prepend(notice);
            } else if (notice && $(".woocommerce-message").length) {
                $(".woocommerce-message").html(notice);
            }
        }
    }
});

jQuery(function ($) {
    "use strict";

    try {
        $(document.body).on("it-enhanced-carousel", function () {
            var owl_carousels = $(".it-owl-carousel-items");
            if (!owl_carousels.length) {
                return;
            }
            owl_carousels.each(function (e) {
                $(this).owlCarousel({
                    margin: 10,
                    responsiveClass: true,
                    rtl: "true" == pw_wc_gift_adv_ajax.rtl,
                    autoplayHoverPause: true,
                    autoplayTimeout: parseInt(pw_wc_gift_adv_ajax.speed),
                    loop: "true" == pw_wc_gift_adv_ajax.loop,
                    dots: "true" == pw_wc_gift_adv_ajax.dots,
                    nav: "true" == pw_wc_gift_adv_ajax.nav,
                    responsive: {
                        0: {
                            items: parseInt(pw_wc_gift_adv_ajax.mobile),
                        },
                        600: {
                            items: parseInt(pw_wc_gift_adv_ajax.tablet),
                        },
                        1000: {
                            items: parseInt(pw_wc_gift_adv_ajax.desktop),
                        },
                    },
                });
            });
        });

        $(document.body).on("updated_wc_div", function () {
            $(document.body).trigger("it-enhanced-carousel");
        });

        $(document.body).trigger("it-enhanced-carousel");
    } catch (err) {
        // window.console.log(err);
    }
});

//Promotion popup
jQuery(document).ready(function ($) {
    // Show the popup on page load
    $("#wgb-promotion-popup").removeClass("hidden");

    // Handle closing the popup
    $("#wgb-promotion-popup .wgb-promotion-close").on("click", function (e) {
        e.preventDefault();
        $("#wgb-promotion-popup").addClass("hidden");
    });
});
