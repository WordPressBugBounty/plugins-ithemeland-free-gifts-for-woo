"use strict";

function wgbOpenTab(item) {
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

function wgbShowButtonLoading(target) {
    jQuery(target).find('.wgb-button-text').hide();
    jQuery(target).find('.wgb-button-loading').show();
}

function wgbHideButtonLoading() {
    jQuery('.wgb-button-loadingable').find('.wgb-button-text').show();
    jQuery('.wgb-button-loadingable').find('.wgb-button-loading').hide();
}

function wgbCloseModal() {
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

function wgbLoadingStart() {
    jQuery('#wgb-loading').removeClass('wgb-loading-error').removeClass('wgb-loading-success').text('Loading ...').slideDown(300);
}

function wgbLoadingSuccess(message = 'Success !') {
    jQuery('#wgb-loading').removeClass('wgb-loading-error').addClass('wgb-loading-success').text(message).delay(1500).slideUp(200);
}

function wgbLoadingError(message = 'Error !') {
    jQuery('#wgb-loading').removeClass('wgb-loading-success').addClass('wgb-loading-error').text(message).delay(1500).slideUp(200);
}