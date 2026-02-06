<div id="wgb-modal" class="wgb-popup wgb-popup-list">
    <div class="wgb-popup-loading">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="40px" height="40px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <rect x="17.5" y="30" width="15" height="40" fill="#ffffff">
                <animate attributeName="y" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="18;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.16s"></animate>
                <animate attributeName="height" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="64;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.16s"></animate>
            </rect>
            <rect x="42.5" y="30" width="15" height="40" fill="#ffffff">
                <animate attributeName="y" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="20.999999999999996;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.08s"></animate>
                <animate attributeName="height" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="58.00000000000001;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.08s"></animate>
            </rect>
            <rect x="67.5" y="30" width="15" height="40" fill="#ffffff">
                <animate attributeName="y" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="20.999999999999996;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1"></animate>
                <animate attributeName="height" repeatCount="indefinite" dur="0.8s" calcMode="spline" keyTimes="0;0.5;1" values="58.00000000000001;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1"></animate>
            </rect>
        </svg>
    </div>
    <div class="wgb-page wgb-popup-box">
        <div class="wgb-popup-header">
            <h3 class="wgb-popup-title"><?php echo esc_html(get_option('itg_localization_select_gift', 'Select Gift')); ?></h3>
            <div class="wgb-popup-close itg-popup-close">×</div>
        </div>
        <div class="wgb-popup-body">
            <div class="wgb-popup-content">
                <div class="wgb-popup-posts">

                </div>
            </div>
        </div>
        <div class="wgb-popup-footer">
            <button type="button" class="wgb-popup-list-no-thanks-button itg-popup-close"><?php echo esc_html(get_option('itg_localization_no_thanks', 'No Thanks')); ?></button>
        </div>
        <div class="popup-inner-loader wgb-d-none">
            <div class="loader-item"></div>
        </div>
    </div>
</div>