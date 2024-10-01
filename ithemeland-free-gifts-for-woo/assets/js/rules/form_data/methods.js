"use strict";

function wgblSubTotalMethod(id) {
    wgblRuleSubTotalQuantities(id);
    wgblHideProductBuy(id);
    wgblShowRuleGet(id);
    wgblRuleConditions(id);
    wgblHideQuantitiesBasedOn(id);
}

function wgblSimpleMethod(id) {
    wgblRuleSimpleQuantities(id);
    wgblHideProductBuy(id);
    wgblShowRuleGet(id);
    wgblRuleConditions(id);
    wgblHideQuantitiesBasedOn(id);
}

function wgblBuyXGetYMethod(id) {
    wgblRuleBuyXGetYQuantities(id);
    wgblShowProductBuy(id);
    wgblShowRuleGet(id);
    wgblRuleConditions(id);
    wgblShowQuantitiesBasedOn(id);
}

function wgblBuyXGetXMethod(id) {
    wgblRuleBuyXGetXQuantities(id);
    wgblShowProductBuy(id);
    wgblHideRuleGet(id);
    wgblRuleConditions(id);
    wgblShowQuantitiesBasedOn(id);
}

function wgblBulkQuantityMethod(id) {
    wgblRuleBulkQuantityQuantities(id);
    wgblShowProductBuy(id);
    wgblShowRuleGet(id);
    wgblRuleConditions(id);
    wgblShowQuantitiesBasedOn(id);
}
function wgblBulkPricingMethod(id) {
    wgblRuleBulkPricingQuantities(id);
    wgblShowProductBuy(id);
    wgblShowRuleGet(id);
    wgblRuleConditions(id);
    wgblShowQuantitiesBasedOn(id);
}

function wgblTieredQuantityMethod(id) {
    wgblRuleTieredQuantities(id);
    wgblShowProductBuy(id);
    wgblShowRuleGet(id);
    wgblRuleConditions(id);
    wgblShowQuantitiesBasedOn(id);
}

function wgblCheapestItemInCart(id) {
    wgblRuleCheapestItemInCartQuantities(id);
    wgblShowQuantitiesBasedOn(id);
    wgblHideRuleGet(id);
    wgblShowProductBuy(id);
    wgblRuleConditions(id);
}