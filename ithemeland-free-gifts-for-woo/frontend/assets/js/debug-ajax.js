jQuery(document).ready(function ($) {
  console.log("=== Gift Plugin Debug ===");

  // Check if pw_wc_gift_adv_ajax is available
  if (typeof pw_wc_gift_adv_ajax !== "undefined") {
    console.log("AJAX settings found:", pw_wc_gift_adv_ajax);

    // Test basic AJAX functionality first
    $.ajax({
      url: pw_wc_gift_adv_ajax.ajaxurl,
      type: "POST",
      dataType: "json",
      data: {
        action: "itg_test_ajax",
        itg_security: pw_wc_gift_adv_ajax.security,
      },
      success: function (response) {
        console.log("Basic AJAX test success:", response);

        // If basic test works, test gift AJAX
        if (response && response.success) {
          console.log("Basic AJAX works, testing gift AJAX...");

          // Test gift AJAX with a real gift ID if available
          var testGiftId = $(".btn-click-add-gift-button").first().data("gift_id");
          if (testGiftId) {
            $.ajax({
              url: pw_wc_gift_adv_ajax.ajaxurl,
              type: "POST",
              dataType: "json",
              data: {
                action: "ajax_add_free_gifts",
                itg_security: pw_wc_gift_adv_ajax.security,
                gift_product_id: testGiftId,
                add_qty: 1,
              },
              success: function (giftResponse) {
                console.log("Gift AJAX test success:", giftResponse);
              },
              error: function (xhr, status, error) {
                console.log("Gift AJAX test error:", {
                  status: status,
                  error: error,
                  responseText: xhr.responseText,
                  statusCode: xhr.status,
                });
              },
            });
          }
        }
      },
      error: function (xhr, status, error) {
        console.log("Basic AJAX test error:", {
          status: status,
          error: error,
          responseText: xhr.responseText,
          statusCode: xhr.status,
        });
      },
    });
  } else {
    console.error("pw_wc_gift_adv_ajax is not defined!");
  }

  // Monitor all AJAX requests
  $(document).ajaxSend(function (event, xhr, settings) {
    if (settings.url && settings.url.includes("admin-ajax.php")) {
      console.log("AJAX request sent:", {
        url: settings.url,
        data: settings.data,
        type: settings.type,
      });
    }
  });

  $(document).ajaxComplete(function (event, xhr, settings) {
    if (settings.url && settings.url.includes("admin-ajax.php")) {
      console.log("AJAX request completed:", {
        url: settings.url,
        status: xhr.status,
        responseText: xhr.responseText,
      });
    }
  });
});
