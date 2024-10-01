'use strict';
jQuery(document).ready(function ($) {
    //DataTable
    if (jQuery("html").find('.it-gift-products-table').length > 0 && jQuery.fn.DataTable) {
        jQuery('.it-gift-products-table').DataTable({
            "ordering": false
        });
        jQuery('.scrollbar-macosx').scrollbar();
    }

    jQuery(document.body).on('updated_cart_totals', function () {
        if (jQuery("html").find('.it-gift-products-table').length > 0 && jQuery.fn.DataTable) {
            jQuery('.it-gift-products-table').DataTable({
                "ordering": false,
				"bDestroy": true
            });
        }
		//DropDown
        if (jQuery("html").find('#wgb-gift-products-dropdown').length > 0) {
            jQuery("#wgb-gift-products-dropdown").ddslick("destroy");
            jQuery("#wgb-gift-products-dropdown").ddslick({
                imagePosition: "left",
                selectText: pw_wc_gift_adv_ajax.select_your_gift,
                onSelected: function (data) {
                    if (data.selectedData && data.selectedData != '') {
                        AjaxCall_addToCart(data.selectedData.value);
                    }
                }
            });
        }		
    });

    //Add Gift Button
    jQuery(document).on("click", ".btn-click-add-gift-button", function (e) {
        e.preventDefault();
        jQuery(this).css({ color: 'transparent' });
        jQuery(".gift_cart_ajax").show();
        jQuery(this).find(".wgb-loading-icon").removeClass("wgb-d-none");
		var qty = 1;
		var gift_id = jQuery(this).data("gift_id");
        AjaxCall_addToCart(gift_id, qty);
    });
	
    //DropDown
    if (jQuery("html").find('#wgb-gift-products-dropdown').length > 0) {
        jQuery("#wgb-gift-products-dropdown").ddslick({
            selectText: pw_wc_gift_adv_ajax.select_your_gift,
            imagePosition: "left",
            onSelected: function (data) {
                if (data.selectedData && data.selectedData != '') {
                    AjaxCall_addToCart(data.selectedData.value);
                }
            }
        });

    }

    //Select Gift Button
    jQuery(document).on("click", ".btn-select-gift-button", function (e) {
        e.preventDefault();

        jQuery(this).find(".wgb-loading-icon").removeClass("wgb-d-none");
        var show_html =
            '<div id="wgb-modal" class="wgb-popup">' +
            '<div class="wgb-page wgb-popup-box">' +
            '<div class="wgb-popup-header">' +
            '<h3 class="wgb-popup-title">Select Gift</h3>' +
            '<button type="button" class="wgb-popup-close"><i class="dashicons dashicons-no-alt"></i></button>' +
            '</div>' +
            '<div class="wgb-popup-body">' +
            '<div class="wgb-popup-content">' +
            '<div class="wgb-popup-posts"></div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';

        $('body').append(show_html);
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
                $(".wgb-popup-posts").html(resp);
                $('body').addClass('modal-opened');
                $('.wgb-popup-box').addClass('wgb-page-scaleUp');
                $('.wgb-popup-box').addClass('wgb-page-current');
                $('.wgb-popup').addClass('wgb-active-modal');
                jQuery('.scrollbar-macosx').scrollbar();
            },
            error: function () { }
        });
    });
	
    //Select Gift Button in Btn Coupon
    jQuery(document).on("click", ".btn_select_gift_in_coupon", function (e) {

        e.preventDefault();

        jQuery(this).find(".wgb-loading-icon").removeClass("wgb-d-none");
        var show_html =
            '<div id="wgb-modal" class="wgb-popup">' +
            '<div class="wgb-page wgb-popup-box">' +
            '<div class="wgb-popup-header">' +
            '<h3 class="wgb-popup-title">Select Gift</h3>' +
            '<button type="button" class="wgb-popup-close"><i class="dashicons dashicons-no-alt"></i></button>' +
            '</div>' +
            '<div class="wgb-popup-body">' +
            '<div class="wgb-popup-content">' +
            '<div class="wgb-popup-posts"></div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';

        $('body').append(show_html);
        $("#wgb-modal").css("display", "block");

        var pdata = {
            action: pw_wc_gift_adv_ajax.action_display_gifts_in_popup,
            security: pw_wc_gift_adv_ajax.security,
        }

        $.ajax({
            type: "POST",
            url: pw_wc_gift_adv_ajax.ajaxurl,
            data: pdata,
            success: function (resp) {
                $(".wgb-popup-posts").html(resp);
                $('body').addClass('modal-opened');
                $('.wgb-popup-box').addClass('wgb-page-scaleUp');
                $('.wgb-popup-box').addClass('wgb-page-current');
                $('.wgb-popup').addClass('wgb-active-modal');
                jQuery('.scrollbar-macosx').scrollbar();
            }
        });
    });
	
    jQuery(document).on("click", ".btn-click-add-gift-button", function(e) {
        jQuery(this).css({ color: 'transparent' });
        jQuery(".gift_cart_ajax").show();
        jQuery(this).find(".wgb-loading-icon").removeClass("wgb-d-none");
        e.preventDefault();

        var gift_id = jQuery(this).data("gift_id");
        var qty = 1;
        if (pw_wc_gift_adv_ajax.show_quantity == 'true') {
            //qty = jQuery("#" + gift_id).val();
			qty = jQuery(this).parent().find(".qty").find(".input-text").val();
        }
		if(pw_wc_gift_adv_ajax.add_gift_ajax_manual =='true')
		{
			AjaxCall_addToCart(gift_id, qty);
		}
		//else{
		//	ReloadCall_addToCart(gift_id, qty);
		//}
    });	

    jQuery(document).on('click', '.wgb-popup-close', function (e) {
        let modal = $(this).closest('.wgb-popup');
        $('.wgb-loading-icon').addClass('wgb-d-none');
        $('.wgb-popup-box').removeClass('wgb-page-scaleUp');
        $('.wgb-popup-box').addClass('wgb-page-scaleDownUp');
        setTimeout(function () {
            $('body').removeClass('modal-opened');
            modal.remove();
        }, 700)

        jQuery('.scrollbar-macosx').scrollbar('destroy');
    });



	
	//Close on ESC
	$(document).keyup(function (e) {
		if (e.key === "Escape") { // escape key maps to keycode `27`
			$('.wgb-popup-close').trigger("click");
		}
	});
	
	function pagination_gifts(selectedPage) {
		$('#wgb-cart-pagination-current-page').text(selectedPage);
		var selected_page = "page_" + selectedPage;
		$(".wgb-frontend-gifts").find(".0").addClass("pw-gift-active");

		$(".wgb-frontend-gifts").find(".pw_gift_pagination_num").click(function(e) {
			e.preventDefault();
			$(".wgb-frontend-gifts").find("." + selected_page).addClass("pw-gift-deactive");
			var page = $(this).attr("data-page-id");
			$(".wgb-frontend-gifts").find("." + page).siblings(".pw_gift_pagination_div").removeClass("pw-gift-active");
			$(".wgb-frontend-gifts").find("." + page).addClass("pw-gift-active");
			$(".wgb-frontend-gifts").find("." + page).removeClass("pw-gift-deactive");
			$(".wgb-frontend-gifts").find(this).parents(".wgb-paging-item").find(".pw_gift_pagination_num").removeClass("wgb-active-page");
			$(".wgb-frontend-gifts").find(this).addClass("wgb-active-page");
			selected_page = page;
			pagination_gifts($(this).attr('data-page-id').replace('page_', ''));
		});
	}

    function AjaxCall_addToCart(gift_id , qty = 1) {

		$.ajax({
			url: pw_wc_gift_adv_ajax.ajaxurl,
			type: "POST",
			dataType: 'json',
			data: {
				action: 'ajax_add_free_gifts',
				itg_security: pw_wc_gift_adv_ajax.security,
				gift_product_id : gift_id,
				add_qty : qty,
			},
			success: function (response) {
				updateCart(1);
				$('.wgb-popup-close').trigger("click");
				$('.wgb-loading-icon').addClass('wgb-d-none');
				
			},
			error: function (response) {
				//console.log(response);
			}
		});
    }

	/**
	 * Update the cart after any action done.
	 * @since 2.0.0
	 * @returns {undefined}
	 */
	function updateCart(action) {
		$(document.body).trigger('wc_update_cart');
		$(document.body).trigger('update_checkout');
	}

	function paginationInit(targetElement, currentPage, maxNumPages) {
		let maxDisplay = 3;
		targetElement.html('');
		if (currentPage < maxDisplay) {
			for (let i = 1; i <= Math.min(parseInt(maxDisplay), parseInt(maxNumPages)); i++) {
				let active = (parseInt(currentPage) == i) ? 'wgb-active-page' : '';
				targetElement.append("<a href='javascript:;' class='pw_gift_pagination_num " + active + "' data-page-id='page_" + i + "'> " + i + " </a>");
			}
			if (maxNumPages > (parseInt(maxDisplay) + 1)) {
				targetElement.append("<span> ... </span>");
			}
			if (parseInt(currentPage) + 2 <= parseInt(maxNumPages) && parseInt(maxNumPages) > parseInt(maxDisplay)) {
				targetElement.append("<a href='javascript:;' class='pw_gift_pagination_num' data-page-id='page_" + maxNumPages + "'> " + maxNumPages + " </a>");
			}
		} else if (currentPage == maxDisplay) {
			let maxNum = (maxDisplay < maxNumPages) ? parseInt(maxDisplay) + 1 : maxDisplay;
			for (let i = 1; i <= maxNum; i++) {
				let active = (currentPage == i) ? 'wgb-active-page' : '';
				targetElement.append("<a href='javascript:;' class='pw_gift_pagination_num " + active + "' data-page-id='page_" + i + "'> " + i + " </a>");
			}
			if (maxNumPages > (parseInt(currentPage) + 1)) {
				let active = (currentPage == maxNumPages) ? 'wgb-active-page' : '';
				targetElement.append("<span> ... </span>");
				targetElement.append("<a href='javascript:;' class='pw_gift_pagination_num " + active + "' data-page-id='page_" + maxNumPages + "'> " + maxNumPages + " </a>");
			}
		} else {
			let active = (parseInt(currentPage) === 1) ? 'wgb-active-page' : '';
			targetElement.append("<a href='javascript:;' class='pw_gift_pagination_num " + active + "' data-page-id='page_1'>1</a>");
			if (parseInt(currentPage) > (parseInt(maxDisplay) + 1)) {
				targetElement.append("<span> ... </span>");
			}
			for (let i = parseInt(currentPage) - 2; i <= Math.min(parseInt(currentPage) + 2, parseInt(maxNumPages)); i++) {
				let active = (currentPage == i) ? 'wgb-active-page' : '';
				targetElement.append("<a href='javascript:;' class='pw_gift_pagination_num " + active + "' data-page-id='page_" + i + "'> " + i + " </a>");
			}
			if (parseInt(currentPage) + 2 < parseInt(maxNumPages)) {
				let active = (currentPage == maxNumPages) ? 'wgb-active-page' : '';
				targetElement.append("<span> ... </span>");
				targetElement.append("<a href='javascript:;' class='pw_gift_pagination_num " + active + "' data-page-id='page_" + maxNumPages + "'> " + maxNumPages + " </a>");
			}
		}
	}	
});



