jQuery(document).ready(function ($) {
    "use strict";

    // Tabs
    $(document).on("click", ".wgbl-tabs-list li a", function (event) {
        if ($(this).attr('data-disabled') !== 'true') {
            event.preventDefault();
            window.location.hash = $(this).attr('data-content');
            wgblOpenTab($(this));
        }
    });

    $(document).on('click', '.wgbl-button-loadingable', function () {
        wgblShowButtonLoading('#' + $(this).attr('id'));
    });

    if ($('.wgbl-button-loadingable').length > 0) {
        $('.wgbl-button-loadingable').each(function () {
            $(this).prepend(WGBL_DATA.loadingImage)
        });
    }

    // Modal
    $(document).on("click", "[data-toggle=modal]", function () {
        $($(this).attr("data-target")).fadeIn();
        $($(this).attr("data-target") + " .wgbl-modal-box").fadeIn();
        $("#wgbl-last-modal-opened").val($(this).attr("data-target"));

        // set height for modal body
        let titleHeight = $($(this).attr("data-target") + " .wgbl-modal-box .wgbl-modal-title").height();
        let footerHeight = $($(this).attr("data-target") + " .wgbl-modal-box .wgbl-modal-footer").height();
        $($(this).attr("data-target") + " .wgbl-modal-box .wgbl-modal-body").css({
            "max-height": parseInt($($(this).attr("data-target") + " .wgbl-modal-box").height()) - parseInt(titleHeight + footerHeight + 150) + "px"
        });

        $($(this).attr("data-target") + " .wgbl-modal-box-lg .wgbl-modal-body").css({
            "max-height": parseInt($($(this).attr("data-target") + " .wgbl-modal-box").height()) - parseInt(titleHeight + footerHeight + 120) + "px"
        });
    });

    $(document).on("click", "[data-toggle=modal-close]", function () {
        wgblCloseModal();
    });

    $(document).on('click', '.wgbl-modal', function (e) {
        if ($(e.target).hasClass('wgbl-modal') || $(e.target).hasClass('wgbl-popup-body') || $(e.target).hasClass('wgbl-modal-box')) {
            wgblCloseModal();
        }
    });

    $(document).on("keyup", function (e) {
        if (e.keyCode === 27) {
            wgblCloseModal();
            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });
        }
    });

    $(document).on('click', '#wgbl-full-screen', function () {
        if ($('#adminmenuback').css('display') === 'block') {
            $('#adminmenuback, #adminmenuwrap').hide();
            $('#wpcontent, #wpfooter').css({ "margin-left": 0 });
        } else {
            $('#adminmenuback, #adminmenuwrap').show();
            $('#wpcontent, #wpfooter').css({ "margin-left": "160px" });
        }
    });

    // show sub menu
    $(document).on('mouseover', '.wgbl-menu-list li', function () {
        if ($(this).find('.wgbl-sub-menu').length > 0) {
            $(this).find('.wgbl-sub-menu').show();
        }
    });

    // hide sub menu
    $(document).on('mouseout', '.wgbl-menu-list li', function () {
        if ($(this).find('.wgbl-sub-menu').length > 0) {
            $(this).find('.wgbl-sub-menu').hide();
        }
    })
})