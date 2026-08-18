jQuery(document).ready(function ($) {
    "use strict";

    $(document).on('click', '.wgb-renew-license-alert-dismiss-button', function () {
        $('#wgb-renew-license-alert').slideUp(250);

        $.ajax({
            url: WGBL_PRO_VERSION_ALERT.ajax_url,
            type: 'post',
            dataType: 'json',
            data: {
                action: 'wgb_renew_license_alert_dismiss',
                nonce: WGBL_PRO_VERSION_ALERT.ajax_nonce,
            },
            success: function (response) { },
            error: function () { }
        });
    });
});