<?php
/**
 * Plugin Name: Drive Design Elementor Widgets Pack
 * Plugin URI: https://github.com/webdevs-pro/drive-design-widgets
 * Version: 1.0.3
 * Description: Drive Design Elementor Custom Widget Pack
 * Author: Alex Ishchenko
 * Author URI: https://website.cv.ua
 */

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

final class DD_Plugin {

	public function __construct() {
      add_action( 'init', array( $this, 'load_textdomain' ) );

		$this->define_constants();
		$this->include_files();
		$this->init_plugin_update_checker();
	}

	/**
	 * Load plugin textdomain
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'drive-design-widgets', false, dirname( DD_PLUGIN_BASENAME ) . '/languages/' );
	}

	function define_constants() {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		if ( ! function_exists( 'get_home_path' ) ) {
			require_once ( ABSPATH . 'wp-admin/includes/file.php' );
		}
		define( 'DD_PLUGIN_VERSION', get_plugin_data( __FILE__, false, false )['Version'] );
		// define( 'DD_HOME_PATH', get_home_path() );
		define( 'DD_HOME_PATH', ABSPATH );
		define( 'DD_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		define( 'DD_PLUGIN_DIR', dirname( __FILE__ ) );
		define( 'DD_PLUGIN_FILE', __FILE__ );
		define( 'DD_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
	}

	function include_files() {
		require_once ( DD_PLUGIN_DIR . '/inc/vendor/autoload.php' );
		require_once ( DD_PLUGIN_DIR . '/inc/plugin.php' );
	}

	function init_plugin_update_checker() {
		$UpdateChecker = PucFactory::buildUpdateChecker(
			'https://github.com/webdevs-pro/drive-design-widgets',
			__FILE__,
			'drive-design-widgets'
		);
		
		//Set the branch that contains the stable release.
		$UpdateChecker->setBranch( 'main' );
	}

}

new DD_Plugin();