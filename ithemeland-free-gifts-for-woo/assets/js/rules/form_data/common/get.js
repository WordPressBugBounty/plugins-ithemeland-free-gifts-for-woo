"use strict"

function wgbShowRuleGet(id) {
    let get = jQuery('.wgb-rule-item[data-id=' + id + '] div[data-type=get]');
    get.find('select').prop('disabled', false);
    get.find('input').prop('disabled', false);
    get.show();
}

function wgbHideRuleGet(id) {
    let get = jQuery('.wgb-rule-item[data-id=' + id + '] div[data-type=get]');
    get.find('select').prop('disabled', true);
    get.find('input').prop('disabled', true);
    get.hide();
}