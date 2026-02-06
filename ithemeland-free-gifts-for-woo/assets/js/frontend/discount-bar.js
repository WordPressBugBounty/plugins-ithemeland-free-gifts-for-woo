jQuery(document).ready(function ($) {
  // Function to update discount bar content
  function updateDiscountBar() {
    var quantity = $("input.qty").val() || 1;
    var price =
      parseFloat(
        $(".product .price .amount")
          .last()
          .text()
          .replace(/[^0-9.-]+/g, "")
      ) || 0;

    $.ajax({
      url: wgbDiscountBar.ajaxurl,
      type: "POST",
      data: {
        action: "wgb_update_offer_bar",
        nonce: wgbDiscountBar.nonce,
        quantity: quantity,
        price: price,
      },
      success: function (response) {
        if (response.success && response.data) {
          $(".wgb-discount-bar").replaceWith(response.data);
        }
      },
    });
  }

  // Update discount bar when quantity changes
  $(document).on("change", "input.qty", function () {
    updateDiscountBar();
  });

  // Update discount bar when variation changes
  $(document).on("show_variation", ".variations_form", function () {
    updateDiscountBar();
  });

  // Update discount bar when variation is reset
  $(document).on("reset_image", ".variations_form", function () {
    updateDiscountBar();
  });

  // Add smooth scroll to add to cart button when discount bar is clicked
  $(document).on("click", ".wgb-discount-bar", function () {
    $("html, body").animate(
      {
        scrollTop: $(".cart").offset().top - 100,
      },
      500
    );
  });
});
