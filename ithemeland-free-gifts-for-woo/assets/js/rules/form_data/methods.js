"use strict";

function wgbSubTotalMethod(id) {
    wgbHidePriceType(id);
    wgbRuleSubTotalQuantities(id);
    wgbHideProductBuy(id);
    wgbShowRuleGet(id);
    wgbRuleConditions(id);
    wgbHideQuantitiesBasedOn(id);
    wgbHideGetGroupOfProductsItems(id);
}

function wgbSubTotalRepeatMethod(id) {
    wgbHidePriceType(id);
    wgbRuleSubTotalQuantities(id);
    wgbHideProductBuy(id);
    wgbShowRuleGet(id);
    wgbRuleConditions(id);
    wgbHideQuantitiesBasedOn(id);
    wgbHideGetGroupOfProductsItems(id);
}

function wgbSimpleMethod(id) {
    wgbHidePriceType(id);
    wgbRuleSimpleQuantities(id);
    wgbHideProductBuy(id);
    wgbShowRuleGet(id);
    wgbRuleConditions(id);
    wgbHideQuantitiesBasedOn(id);
    wgbHideGetGroupOfProductsItems(id);
}

function wgbBuyXGetYMethod(id) {
    wgbHidePriceType(id);
    wgbRuleBuyXGetYQuantities(id);
    wgbShowProductBuy(id);
    wgbShowRuleGet(id);
    wgbRuleConditions(id);
    wgbShowQuantitiesBasedOn(id);
    wgbHideGetGroupOfProductsItems(id);
}

function wgbBuyXGetXMethod(id) {
    wgbHidePriceType(id);
    wgbRuleBuyXGetXQuantities(id);
    wgbShowProductBuy(id);
    wgbHideRuleGet(id);
    wgbRuleConditions(id);
    wgbShowQuantitiesBasedOn(id);
    wgbHideGetGroupOfProductsItems(id);
}

function wgbBulkQuantityMethod(id) {
    wgbHidePriceType(id);
    wgbRuleBulkQuantityQuantities(id);
    wgbShowProductBuy(id);
    wgbShowRuleGet(id);
    wgbRuleConditions(id);
    wgbShowQuantitiesBasedOn(id);
    wgbHideGetGroupOfProductsItems(id);
}
function wgbBulkPricingMethod(id) {
    wgbHidePriceType(id);
    wgbRuleBulkPricingQuantities(id);
    wgbShowProductBuy(id);
    wgbShowRuleGet(id);
    wgbRuleConditions(id);
    wgbShowQuantitiesBasedOn(id);
    wgbHideGetGroupOfProductsItems(id);
}

function wgbTieredQuantityMethod(id) {
    wgbHidePriceType(id);
    wgbRuleTieredQuantities(id);
    wgbShowProductBuy(id);
    wgbShowRuleGet(id);
    wgbRuleConditions(id);
    wgbShowQuantitiesBasedOn(id);
    wgbHideGetGroupOfProductsItems(id);
}

function wgbCheapestItemInCart(id) {
    wgbRuleCheapestItemInCartQuantities(id);
    wgbHideQuantitiesBasedOn(id);
    wgbShowPriceType(id);
    wgbHideRuleGet(id);
    wgbHideProductBuy(id);
    wgbRuleConditions(id);
    wgbHideGetGroupOfProductsItems(id);
}

function wgbFreeShippingMethod(id) {
    wgbHidePriceType(id);
    wgbRuleFreeShippingQuantities(id);
    wgbHideQuantitiesBasedOn(id);
    wgbHideRuleGet(id);
    wgbHideProductBuy(id);
    wgbRuleConditions(id);
    wgbHideGetGroupOfProductsItems(id);
}

function wgbGetGroupOfProductsMethod(id) {
    wgbShowGetGroupOfProductsItems(id);
    wgbHidePriceType(id);
    wgbHideQuantitiesBasedOn(id);
    wgbHideRuleGet(id);
    wgbHideProductBuy(id);
    wgbRuleConditions(id);
    wgbRuleEnableQuantities(id);
}