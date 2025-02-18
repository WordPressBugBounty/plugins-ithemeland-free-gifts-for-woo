"use strict";

function wgblFreeShippingMethod(id) {
  wgblRuleFreeShippingQuantities(id);
  wgblShowQuantitiesBasedOn(id);
  wgblHideRuleGet(id);
  wgblShowProductBuy(id);
  wgblRuleConditions(id);
}
