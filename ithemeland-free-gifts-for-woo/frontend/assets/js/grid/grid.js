jQuery(document).ready(function($) {
        function pagination_gifts(selectedPage) {
            $('#wgb-cart-pagination-current-page').text(selectedPage);
            var selected_page = "page_" + selectedPage;
            $(".wgb-frontend-gifts").find(".0").addClass("pw-gift-active");

            $(".wgb-frontend-gifts").find(".pw_gift_pagination_num").click(function(e) {
                e.preventDefault();
                $(".wgb-frontend-gifts").find("." + selected_page).addClass("pw-gift-deactive");
                var page = $(this).attr("data-page-id");
                $(".wgb-frontend-gifts").find("." + page).siblings(".pw_gift_pagination_div").removeClass("pw-gift-active");
                $(".wgb-frontend-gifts").find("." + page).addClass("pw-gift-active");
                $(".wgb-frontend-gifts").find("." + page).removeClass("pw-gift-deactive");
                $(".wgb-frontend-gifts").find(this).parents(".wgb-paging-item").find(".pw_gift_pagination_num").removeClass("wgb-active-page");
                $(".wgb-frontend-gifts").find(this).addClass("wgb-active-page");
                selected_page = page;
                pagination_gifts($(this).attr('data-page-id').replace('page_', ''));
            });
        }



        $(".woocommerce-cart-form").find(".button").click(function() {
            $(document).ajaxStop(function() {
                pagination_gifts(1);
            });
        });

        $(".product-remove").on("click", function() {
            $(document).ajaxStop(function() {
                pagination_gifts(1);
            });
        });
		
		
		x=5;
		$('#items_list div').slice(0, 5).show();		
		$('#loadMoregifts').on('click', function (e) {
			e.preventDefault();
			x = x+3;
			$('#items_list div').slice(0, x).slideDown();
		});
		
	function loadmore_gifts() {
		$("#wgb-count-item").val();

	}
	
	pagination_gifts(1);	
	//loadmore_gifts();	
	
});