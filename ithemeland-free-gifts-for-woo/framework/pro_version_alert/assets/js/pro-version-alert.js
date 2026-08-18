jQuery(document).ready(function ($) {
    "use strict";

    $(document).on('click', '.wgb-pro-version-alert-dismiss-button', function () {
        $('#wgb-pro-version-alert').slideUp(250);

        $.ajax({
            url: WGBL_PRO_VERSION_ALERT.ajax_url,
            type: 'post',
            dataType: 'json',
            data: {
                action: 'wgb_pro_version_alert_dismiss',
                nonce: WGBL_PRO_VERSION_ALERT.ajax_nonce,
            },
            success: function (response) { },
            error: function () { }
        });
    });
});