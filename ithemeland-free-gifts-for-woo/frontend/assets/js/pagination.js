(function ($) {
  function initializePagination() {
    $(".wgb-gift-cart-pagination-items").hide();

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

      var savedPage = localStorage.getItem("gift_current_page_" + ruleUid) || 1;
      loadPage(parseInt(savedPage));

      $pagination.off("click", ".page-link").on("click", ".page-link", function (e) {
        e.preventDefault();
        var page = $(this).data("page");
        loadPage(page);
      });
    });
  }

  $(document).ready(function () {
    initializePagination();
  });

  // Re-initialize pagination after AJAX cart updates
  $(document.body).on("updated_cart_totals", function () {
    initializePagination();
  });
})(jQuery);
