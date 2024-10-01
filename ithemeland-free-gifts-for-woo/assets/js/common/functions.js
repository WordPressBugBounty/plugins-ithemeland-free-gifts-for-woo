"use strict";

function wgblOpenTab(item) {
    let wgblTabItem = item;
    let wgblParentContent = wgblTabItem.closest(".wgbl-tabs-list");
    let wgblParentContentID = wgblParentContent.attr("data-content-id");
    let wgblDataBox = wgblTabItem.attr("data-content");
    wgblParentContent.find("li a.selected").removeClass("selected");
    wgblTabItem.addClass("selected");
    jQuery("#" + wgblParentContentID).children("div.selected").removeClass("selected");
    jQuery("#" + wgblParentContentID + " div[data-content=" + wgblDataBox + "]").addClass("selected");
    if (jQuery(this).attr("data-type") === "main-tab") {
        wgblFilterFormClose();
    }
}

function wgblShowButtonLoading(target) {
    jQuery(target).find('.wgbl-button-text').hide();
    jQuery(target).find('.wgbl-button-loading').show();
}

function wgblHideButtonLoading() {
    jQuery('.wgbl-button-loadingable').find('.wgbl-button-text').show();
    jQuery('.wgbl-button-loadingable').find('.wgbl-button-loading').hide();
}

function wgblCloseModal() {
    let lastModalOpened = jQuery('#wgbl-last-modal-opened');
    if (lastModalOpened.val() !== '') {
        jQuery(lastModalOpened.val() + ' .wgbl-modal-box').fadeOut();
        jQuery(lastModalOpened.val()).fadeOut();
        lastModalOpened.val('');
    } else {
        jQuery('.wgbl-modal-box').fadeOut();
        jQuery('.wgbl-modal').fadeOut();
    }
}

function wgblLoadingStart() {
    jQuery('#wgbl-loading').removeClass('wgbl-loading-error').removeClass('wgbl-loading-success').text('Loading ...').slideDown(300);
}

function wgblLoadingSuccess(message = 'Success !') {
    jQuery('#wgbl-loading').removeClass('wgbl-loading-error').addClass('wgbl-loading-success').text(message).delay(1500).slideUp(200);
}

function wgblLoadingError(message = 'Error !') {
    jQuery('#wgbl-loading').removeClass('wgbl-loading-success').addClass('wgbl-loading-error').text(message).delay(1500).slideUp(200);
}