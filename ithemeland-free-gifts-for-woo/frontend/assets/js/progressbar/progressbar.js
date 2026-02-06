(function ($) {
  $(document).ready(function () {
    let ajaxInProgress = false;
    let lastCartTotal = "";
    let lastMilestoneReached = {}; // Track last milestone for each rule
    let hideTimeouts = {}; // Track timeout IDs for each rule

    // Update all progress bars
    function updateProgressBar(data) {
      if (typeof data === "object" && !data.hasOwnProperty("current_amount")) {
        Object.keys(data).forEach(function (ruleUid) {
          const ruleData = data[ruleUid];
          updateSingleProgressBar(ruleUid, ruleData);
        });
      } else {
        updateSingleProgressBar("default", data);
      }
    }

    // Update a single progress bar
    function updateSingleProgressBar(ruleUid, data) {
      const $progressContainer = $(".gift-progress-container[data-rule-uid='" + ruleUid + "']");
      if (!$progressContainer.length) return;

      // Handle hide signal
      if (data && data.should_hide) {
        $progressContainer.fadeOut(500);
        return;
      }

      if (!data) return;

      // Always ensure progress bar is visible initially
      if (!$progressContainer.is(":visible") && !$progressContainer.hasClass("gift-unlocked")) {
        $progressContainer.show();
      }

      const currentAmount = parseFloat(data.current_amount || 0);
      const targetAmount = parseFloat(data.target_amount || 0);
      const remainingAmount = parseFloat(data.remaining_amount || 0);
      const ruleMethod = data.rule_method || "subtotal";

      // Clear any existing timeout for this rule
      if (hideTimeouts[ruleUid]) {
        clearTimeout(hideTimeouts[ruleUid]);
        delete hideTimeouts[ruleUid];
      }

      // Initialize last milestone if not exists
      if (!lastMilestoneReached[ruleUid]) lastMilestoneReached[ruleUid] = 0;

      const $progressMessage = $progressContainer.find(".progress-message");

      // Logic for subtotal_repeat: show congrats only when new milestone reached
      if (ruleMethod === "subtotal_repeat") {
        const completedCycles = Math.floor(currentAmount / (targetAmount / Math.max(1, Math.floor(targetAmount / currentAmount))));
        if (completedCycles > lastMilestoneReached[ruleUid]) {
          // New milestone reached
          if ($progressMessage.length) {
            $progressMessage.text("Congratulations! You've unlocked your Free Gift!");
            $progressMessage.addClass("success");
            $progressContainer.addClass("gift-unlocked");

            hideTimeouts[ruleUid] = setTimeout(function () {
              $progressContainer.removeClass("gift-unlocked");
              $progressMessage.removeClass("success");
              delete hideTimeouts[ruleUid];
            }, 3000);
          }
          lastMilestoneReached[ruleUid] = completedCycles;
        }
      } else {
        // Regular subtotal: show congrats once
        if (remainingAmount <= 0 && !$progressContainer.hasClass("gift-unlocked")) {
          if ($progressMessage.length) {
            $progressMessage.text("Congratulations! You've unlocked your Free Gift!");
            $progressMessage.addClass("success");
            $progressContainer.addClass("gift-unlocked");

            hideTimeouts[ruleUid] = setTimeout(function () {
              $progressContainer.fadeOut(500);
              $progressContainer.removeClass("gift-unlocked");
              $progressMessage.removeClass("success");
              delete hideTimeouts[ruleUid];
            }, 3000);
          }
        }
        // Show progress bar again if subtotal decreased
        else if (remainingAmount > 0) {
          $progressContainer.removeClass("gift-unlocked");
          $progressMessage.removeClass("success");
          // Force show the progress bar when there's remaining amount
          if (!$progressContainer.is(":visible")) {
            $progressContainer.fadeIn(500);
          } else {
            $progressContainer.show();
          }
        }
      }

      // Update progress bar width and amounts
      const progressPercentage = targetAmount > 0 ? (currentAmount / targetAmount) * 100 : 0;
      $progressContainer.find(".progress-fill").css("width", Math.min(100, progressPercentage) + "%");
      $progressContainer.find(".current-amount").html(data.currency_symbol + currentAmount.toFixed(2));
      $progressContainer.find(".target-amount").html(data.currency_symbol + targetAmount.toFixed(2));

      // Update normal progress message if not milestone
      if ($progressMessage.length && remainingAmount > 0 && ruleMethod !== "subtotal_repeat") {
        const tempDiv = $("<div>").html(data.message);
        $progressMessage.text(tempDiv.text());
      }

      // Ensure progress bar is always visible when there's remaining amount
      if (remainingAmount > 0 && !$progressContainer.hasClass("gift-unlocked")) {
        if (!$progressContainer.is(":visible")) {
          $progressContainer.fadeIn(500);
        }
      }
    }

    // Refresh progress bar via AJAX
    function refreshProgressBar() {
      if (ajaxInProgress) return;
      ajaxInProgress = true;

      if (typeof wgbProgressBarData === "undefined") {
        console.error("wgbProgressBarData is not defined");
        ajaxInProgress = false;
        return;
      }

      $.ajax({
        url: wgbProgressBarData.ajaxurl,
        type: "POST",
        data: {
          action: "wgb_update_progress_bar",
          nonce: wgbProgressBarData.nonce,
          current_url: window.location.href,
        },
        success: function (response) {
          if (response.success && response.data) {
            updateProgressBar(response.data);
          }
        },
        error: function (xhr, status, error) {
          console.error("Progress Bar AJAX Error:", error);
        },
        complete: function () {
          ajaxInProgress = false;
        },
      });
    }

    // Monitor cart changes for WooCommerce Blocks
    function monitorCartChanges() {
      const cartBlock = document.querySelector(".wp-block-woocommerce-cart");
      if (!cartBlock) return;

      const observer = new MutationObserver(function () {
        const cartTotals = cartBlock.querySelector(".wc-block-components-totals-footer-item");
        if (cartTotals) {
          const currentTotal = cartTotals.textContent.trim();
          if (currentTotal !== lastCartTotal && !ajaxInProgress) {
            lastCartTotal = currentTotal;
            setTimeout(refreshProgressBar, 500);
          }
        }
      });

      observer.observe(cartBlock, { childList: true, subtree: true, characterData: true, attributes: true });
    }

    // WooCommerce event listeners
    $(document.body).on("updated_cart_totals wc_fragments_refreshed added_to_cart updated_wc_div", function () {
      setTimeout(refreshProgressBar, 500);
    });
    $(document).on("change", "input.qty", function () {
      setTimeout(refreshProgressBar, 1000);
    });
    $(document).on("click", ".remove", function () {
      setTimeout(refreshProgressBar, 1000);
    });
    // Additional cart update events
    $(document).on("click", "[name='update_cart']", function () {
      setTimeout(refreshProgressBar, 1500);
    });
    $(document).on("wc_cart_updated", function () {
      setTimeout(refreshProgressBar, 500);
    });

    // Initialize
    monitorCartChanges();
    setTimeout(refreshProgressBar, 100);
  });
})(jQuery);
