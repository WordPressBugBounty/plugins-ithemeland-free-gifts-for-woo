(function ($) {
  $(document).ready(function () {
    let ajaxInProgress = false;
    let lastCartTotal = "";

    // const existingNotice = document.getElementsByClassName(".wgb-promotion-notice");
    // if (existingNotice) {
    //   existingNotice.remove();
    // }

    //Function to initialize pagination
    function initializePagination() {
      $(".wgb-pagination").each(function () {
        var $pagination = $(this);
        var ruleUid = $pagination.data("rule-uid");
        var $container = $pagination.closest(".wgb-promotion-template-wrapper");

        var $items = $container.find('.wgb-gift-cart-pagination-items[data-rule-uid="' + ruleUid + '"]');

        function getPerPage() {
          if (typeof cart_ajax !== "undefined" && cart_ajax.per_page) {
            return parseInt(cart_ajax.per_page);
          }
          return 5;
        }

        function loadPage(page, per_page) {
          if (typeof per_page === "undefined" || per_page === null) {
            per_page = getPerPage();
          }
          var start = (page - 1) * per_page;
          var end = start + per_page;
          $items.removeClass("wgb-visible");
          $items.slice(start, end).addClass("wgb-visible");
          var totalItems = $items.length;
          var totalPages = Math.ceil(totalItems / per_page);
          var paginationHtml = "";
          for (var i = 1; i <= totalPages; i++) {
            var activeClass = i === page ? "active" : "";
            paginationHtml += '<a href="#" class="page-link ' + activeClass + '" data-page="' + i + '" data-rule-uid="' + ruleUid + '">' + i + "</a>";
          }
          $pagination.html(paginationHtml);

          localStorage.setItem("gift_current_page_" + ruleUid, page);
        }

        // Only hide items for this rule/template, not globally!
        $items.removeClass("wgb-visible");

        var savedPage = localStorage.getItem("gift_current_page_" + ruleUid) || 1;
        loadPage(parseInt(savedPage));

        $pagination.on("click", ".page-link", function (e) {
          e.preventDefault();
          var page = $(this).data("page");
          loadPage(page);
        });

        $(document.body).on("updated_cart_totals", function () {
          var currentPage = localStorage.getItem("gift_current_page_" + ruleUid) || 1;
          loadPage(parseInt(currentPage));
        });
      });
    }

    // Function to check and display promotion message
    function checkAndDisplayPromotion(message) {
      if (!wgbPromotionData.cartPage) return;

      const cartCheckoutBlock = document.querySelector(".wp-block-woocommerce-cart, .wp-block-woocommerce-checkout");
      if (!cartCheckoutBlock) return;

      if (!message || !Array.isArray(message)) return;

      let messageTemplatePairs = message.reverse();

      const noticesElement = cartCheckoutBlock.querySelector(".wc-block-components-notices");

      const existingNotices = cartCheckoutBlock.querySelectorAll(".wgb-promotion-notice");
      existingNotices.forEach((n) => n.remove());

      messageTemplatePairs.forEach(({ msg, tmpl }) => {
        if (!msg && !tmpl) return;

        const isProgressBar = tmpl && tmpl.includes("gift-progress-container");

        if (isProgressBar) {
          const tempDiv = document.createElement("div");
          tempDiv.innerHTML = tmpl;
          const newProgress = tempDiv.querySelector(".gift-progress-container");

          const ruleUid = newProgress ? newProgress.getAttribute("data-rule-uid") : null;

          if (!ruleUid) {
            const progressContainer = document.createElement("div");
            progressContainer.className = "wgb-progress-bar-container";
            progressContainer.innerHTML = tmpl;
            cartCheckoutBlock.insertBefore(progressContainer, cartCheckoutBlock.firstChild);
            return;
          }

          let existing = cartCheckoutBlock.querySelector(`.wgb-progress-bar-container[data-rule-uid="${ruleUid}"]`);

          if (existing) {
            existing.innerHTML = tmpl;
          } else {
            const progressContainer = document.createElement("div");
            progressContainer.className = "wgb-progress-bar-container";
            progressContainer.setAttribute("data-rule-uid", ruleUid);
            progressContainer.innerHTML = tmpl;
            cartCheckoutBlock.insertBefore(progressContainer, cartCheckoutBlock.firstChild);
          }
        } else {
          const notice = document.createElement("div");
          notice.className = "woocommerce-info wgb-promotion-notice";
          notice.innerHTML = (msg ? msg : "") + (tmpl ? tmpl : "");
          if (noticesElement) {
            noticesElement.appendChild(notice);
          } else {
            cartCheckoutBlock.insertBefore(notice, cartCheckoutBlock.firstChild);
          }
        }
      });

      if (messageTemplatePairs.some((pair) => pair.tmpl && pair.tmpl.includes("wgb-gift-cart-pagination-items"))) {
        setTimeout(function () {
          initializePagination();
        }, 100);
      }
    }

    // Monitor cart changes
    function monitorCartChanges() {
      const cartBlock = document.querySelector(".wp-block-woocommerce-cart");

      if (cartBlock) {
        const observer = new MutationObserver(function (mutations) {
          const cartTotals = cartBlock.querySelector(".wc-block-components-totals-footer-item");
          if (cartTotals) {
            const currentTotal = cartTotals.textContent.trim();

            if (currentTotal !== lastCartTotal && !ajaxInProgress) {
              lastCartTotal = currentTotal;
              ajaxInProgress = true;
              $.ajax({
                url: wgbPromotionData.ajaxurl,
                type: "POST",
                data: {
                  action: "wgb_check_promotion_message",
                  nonce: wgbPromotionData.nonce,
                  current_url: window.location.href,
                },
                success: function (response) {
                  if (response.success && response.data) {
                    checkAndDisplayPromotion(response.data.messageText);
                  }
                },
                error: function (xhr, status, error) {
                  console.error("AJAX Error:", error);
                  console.error("Status:", status);
                  console.error("Response:", xhr.responseText);
                },
                complete: function () {
                  ajaxInProgress = false;
                },
              });
            }
          }
        });

        observer.observe(cartBlock, {
          childList: true,
          subtree: true,
          characterData: true,
          attributes: true,
        });
      }
    }

    checkAndDisplayPromotion(wgbPromotionData.message);
    monitorCartChanges();
  });
})(jQuery);
