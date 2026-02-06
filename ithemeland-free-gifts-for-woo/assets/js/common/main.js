jQuery(document).ready(function ($) {
  "use strict";

  $(document).on("click", ".wgb-button-loadingable", function () {
    wgbShowButtonLoading("#" + $(this).attr("id"));
  });

  if ($(".wgb-button-loadingable").length > 0) {
    $(".wgb-button-loadingable").each(function () {
      $(this).prepend(WGBL_DATA.loadingImage);
    });
  }

  // Modal
  $(document).on("click", '[data-toggle="modal"]', function () {
    $($(this).attr("data-target")).fadeIn();
    $($(this).attr("data-target") + " .wgb-modal-box").fadeIn();
    $("#wgb-last-modal-opened").val($(this).attr("data-target"));

    // set height for modal body
    let titleHeight = $($(this).attr("data-target") + " .wgb-modal-box .wgb-modal-title").height();
    let footerHeight = $($(this).attr("data-target") + " .wgb-modal-box .wgb-modal-footer").height();
    $($(this).attr("data-target") + " .wgb-modal-box .wgb-modal-body").css({
      "max-height": parseInt($($(this).attr("data-target") + " .wgb-modal-box").height()) - parseInt(titleHeight + footerHeight + 150) + "px",
    });

    $($(this).attr("data-target") + " .wgb-modal-box-lg .wgb-modal-body").css({
      "max-height": parseInt($($(this).attr("data-target") + " .wgb-modal-box").height()) - parseInt(titleHeight + footerHeight + 120) + "px",
    });
  });

  $(document).on("click", '[data-toggle="modal-close"]', function () {
    wgbCloseModal();
  });

  $(document).on("click", ".wgb-modal", function (e) {
    if ($(e.target).hasClass("wgb-modal") || $(e.target).hasClass("wgb-popup-body") || $(e.target).hasClass("wgb-modal-box")) {
      wgbCloseModal();
    }
  });

  $(document).on("keyup", function (e) {
    if (e.keyCode === 27) {
      wgbCloseModal();
      $("[data-type=edit-mode]").each(function () {
        $(this).closest("span").html($(this).attr("data-val"));
      });
    }
  });

  $(document).on("click", "#wgb-full-screen", function () {
    if ($("#adminmenuback").css("display") === "block") {
      $("#adminmenuback, #adminmenuwrap").hide();
      $("#wpcontent, #wpfooter").css({ "margin-left": 0 });
    } else {
      $("#adminmenuback, #adminmenuwrap").show();
      $("#wpcontent, #wpfooter").css({ "margin-left": "160px" });
    }
  });

  // show sub menu
  $(document).on("mouseover", ".wgb-menu-list li", function () {
    if ($(this).find(".wgb-sub-menu").length > 0) {
      $(this).find(".wgb-sub-menu").show();
    }
  });

  // hide sub menu
  $(document).on("mouseout", ".wgb-menu-list li", function () {
    if ($(this).find(".wgb-sub-menu").length > 0) {
      $(this).find(".wgb-sub-menu").hide();
    }
  });
});
