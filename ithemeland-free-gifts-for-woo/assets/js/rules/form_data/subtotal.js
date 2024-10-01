"use strict";

function wgblSubTotalMethod(id) {
    wgblRuleSubTotalQuantities(id);
    wgblHideProductBuy(id);
    wgblShowRuleGet(id);
    wgblRuleConditions(id);
    wgblHideQuantitiesBasedOn(id);
}