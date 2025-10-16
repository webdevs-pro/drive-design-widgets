<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ( DD_PLUGIN_DIR . '/inc/modules/elementor/parallax-background.php' );
require_once ( DD_PLUGIN_DIR . '/inc/modules/elementor/elementor.php' );



add_filter( 'show_admin_bar', 'cc_hide_admin_bar_based_on_url_param' );
function cc_hide_admin_bar_based_on_url_param( $show ) {
	if ( isset( $_GET['admin-bar'] ) && 'false' === $_GET['admin-bar'] ) {
		 return false;
	}
	return $show;
}