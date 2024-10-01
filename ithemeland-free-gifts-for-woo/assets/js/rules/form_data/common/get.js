"use strict"

function wgblShowRuleGet(id) {
    let get = jQuery('.wgbl-rule-item[data-id=' + id + '] div[data-type=get]');
    get.find('select').prop('disabled', false);
    get.find('input').prop('disabled', false);
    get.show();
}

function wgblHideRuleGet(id) {
    let get = jQuery('.wgbl-rule-item[data-id=' + id + '] div[data-type=get]');
    get.find('select').prop('disabled', true);
    get.find('input').prop('disabled', true);
    get.hide();
}