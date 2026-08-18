jQuery(document).ready(function ($) {
    "use strict";

    // Modal
    $(document).on("click", '[data-toggle="modal"]', function () {
        $($(this).attr("data-target")).fadeIn();
        $($(this).attr("data-target") + " .wgbl-modal-box").fadeIn();
        $("#wgbl-last-modal-opened").val($(this).attr("data-target"));

        // set height for modal body
        let titleHeight = $($(this).attr("data-target") + " .wgbl-modal-box .wgbl-modal-title").height();
        let footerHeight = $($(this).attr("data-target") + " .wgbl-modal-box .wgbl-modal-footer").height();
        $($(this).attr("data-target") + " .wgbl-modal-box .wgbl-modal-body").css({
            "max-height": parseInt($($(this).attr("data-target") + " .wgb-modal-box").height()) - parseInt(titleHeight + footerHeight + 150) + "px",
        });

        $($(this).attr("data-target") + " .wgbl-modal-box-lg .wgbl-modal-body").css({
            "max-height": parseInt($($(this).attr("data-target") + " .wgbl-modal-box").height()) - parseInt(titleHeight + footerHeight + 120) + "px",
        });
    });

    $(document).on("click", '[data-toggle="modal-close"]', function () {
        wgbCloseModal();
    });

    $(document).on("click", ".wgbl-modal", function (e) {
        if ($(e.target).hasClass("wgbl-modal") || $(e.target).hasClass("wgbl-popup-body") || $(e.target).hasClass("wgbl-modal-box")) {
            wgbCloseModal();
        }
    });

    $(document).on("keyup", function (e) {
        if (e.keyCode === 27) {
            wgbCloseModal();
            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });
        }
    });

    $(document).on("click", "#wgbl-full-screen", function () {
        if ($("#adminmenuback").css("display") === "block") {
            $("#adminmenuback, #adminmenuwrap").hide();
            $("#wpcontent, #wpfooter").css({ "margin-left": 0 });
        } else {
            $("#adminmenuback, #adminmenuwrap").show();
            $("#wpcontent, #wpfooter").css({ "margin-left": "160px" });
        }
    });

    // show sub menu
    $(document).on("mouseover", ".wgbl-menu-list li", function () {
        if ($(this).find(".wgbl-sub-menu").length > 0) {
            $(this).find(".wgbl-sub-menu").show();
        }
    });

    // hide sub menu
    $(document).on("mouseout", ".wgbl-menu-list li", function () {
        if ($(this).find(".wgbl-sub-menu").length > 0) {
            $(this).find(".wgbl-sub-menu").hide();
        }
    });

    if ($('#wgb-rules-save-changes').length) {
        $('#wgb-rules-save-changes').attr('id', 'wgb-rules-save-change');
    }

    if ($('.btn-click-add-gift-button').length) {
        $('.btn-click-add-gift-button').addClass('btn--click-add-gift-button').removeClass('btn-click-add-gift-button');
    }

    if ($('.itg-select-variation-gift-dropdown-with-button').length) {
        $('.itg-select-variation-gift-dropdown-with-button').each(function () {
            $(this).find('option').prop('value', '0');
        });
    }
});

function wgblOpenTab(item) {
    let wgbTabItem = item;
    let wgbParentContent = wgbTabItem.closest(".wgb-tabs-list");
    let wgbParentContentID = wgbParentContent.attr("data-content-id");
    let wgbDataBox = wgbTabItem.attr("data-content");
    wgbParentContent.find("li a.selected").removeClass("selected");
    wgbTabItem.addClass("selected");
    jQuery("#" + wgbParentContentID).children("div.selected").removeClass("selected");
    jQuery("#" + wgbParentContentID + " div[data-content=" + wgbDataBox + "]").addClass("selected");
    if (jQuery(this).attr("data-type") === "main-tab") {
        wgbFilterFormClose();
    }
}

function wgblShowButtonLoading(target) {
    jQuery(target).find('.wgb-button-text').hide();
    jQuery(target).find('.wgb-button-loading').show();
}

function wgblHideButtonLoading() {
    jQuery('.wgb-button-loadingable').find('.wgb-button-text').show();
    jQuery('.wgb-button-loadingable').find('.wgb-button-loading').hide();
}

function wgblCloseModal() {
    let lastModalOpened = jQuery('#wgb-last-modal-opened');
    if (lastModalOpened.val() !== '') {
        jQuery(lastModalOpened.val() + ' .wgb-modal-box').fadeOut();
        jQuery(lastModalOpened.val()).fadeOut();
        lastModalOpened.val('');
    } else {
        jQuery('.wgb-modal-box').fadeOut();
        jQuery('.wgb-modal').fadeOut();
    }
}

function wgblLoadingStart() {
    jQuery('#wgb-loading').removeClass('wgb-loading-error').removeClass('wgb-loading-success').text('Loading ...').slideDown(300);
}

function wgblLoadingSuccess(message = 'Success !') {
    jQuery('#wgb-loading').removeClass('wgb-loading-error').addClass('wgb-loading-success').text(message).delay(1500).slideUp(200);
}

function wgblLoadingError(message = 'Error !') {
    jQuery('#wgb-loading').removeClass('wgb-loading-success').addClass('wgb-loading-error').text(message).delay(1500).slideUp(200);
}