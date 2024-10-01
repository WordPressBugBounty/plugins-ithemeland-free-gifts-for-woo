<?php

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

class iThemeland_front_shortcodes extends check_rule_condition
{
    private $gift_item_key;
    private $settings;
    public function __construct()
    {
        $this->gift_item_key = array();
        $this->settings = itg_get_settings();
		
		add_shortcode( 'itgp' , array( __CLASS__ , 'process_shortcode' ) ) ;
		
	}
	
	public static function process_shortcode( $atts, $content, $tag ) {	
	
	
	
		
	}
}

new iThemeland_front_shortcodes();
