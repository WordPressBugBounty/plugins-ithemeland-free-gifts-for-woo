jQuery(document).ready(function ($) {
    var wc_meta_boxes_order_downloads = {
        init: function () {
            $('.add-gift-to-order')
                .on('click', 'button.add_gift_order', this.add_gift_order);
        },

        add_gift_order: function () {
            var products = $('#gift_products_id').val();

            if (!products) {
                return;
            }

            $('.add-gift-to-order').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });

            var data = {
                action: 'it_wc_gift_to_order',
                product_ids: products,
                order_id: it_wc_gift_add_order_ajax.order_id,
                security: it_wc_gift_add_order_ajax.security
            };
            $.post(it_wc_gift_add_order_ajax.ajax_url, data, function (response) {

                if (response) {
                    // window.alert('The gift is added to order');
                    location.reload();
                } else {
                    window.alert('Fail');
                }
                // $('.add-gift-to-order').unblock();
            });

            return false;
        }
    };
    wc_meta_boxes_order_downloads.init();
});