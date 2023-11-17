<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class DD_Elementor {

   const MINIMUM_ELEMENTOR_VERSION = '3.15.0';
   const MINIMUM_PHP_VERSION = '7.3';

   public function __construct() {
      add_action( 'plugins_loaded', array( $this, 'init' ) );
      add_action( 'elementor/elements/categories_registered', array( $this, 'register_widgets_categories' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_frontend_scripts' ) );
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_frontend_styles' ) );
      add_action( 'elementor/widgets/register', array( $this, 'on_widgets_registered' ) );
   }

   public function init() {
      // Check if Elementor installed and activated
      if ( ! did_action( 'elementor/loaded' ) ) {
         add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
         return;
      }

      // Check for required Elementor version
      if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
         add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
         return;
      }

      // Check for required PHP version
      if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
         add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
         return;
      }
   }



   /**
    * Register custom widgets categories
    *
    * @param \Elementor\Elements_Manager $elements_manager
    *
    * @return void
    */
   function register_widgets_categories( \Elementor\Elements_Manager $elements_manager ) {
      $elements_manager->add_category( 'drive-design', [
         'title' => 'Drive Design',
         'icon' => 'fa fa-plug',
      ] );
   }



   /**
    * Admin notice
    *
    * Warning when the site doesn't have Elementor installed or activated.
    *
    * @return void
    */
   public function admin_notice_missing_main_plugin() {
      if ( isset( $_GET['activate'] ) ) {
         unset( $_GET['activate'] );
      }
      $message = sprintf(
         '"%1$s" requires "%2$s" to be installed and activated.',
         '<strong>' . 'Drive Design Custom Functions' . '</strong>',
         '<strong>' . 'Elementor' . '</strong>'
      );
      printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
   }



   /**
    * Admin notice
    *
    * Warning when the site doesn't have a minimum required Elementor version.
    *
    * @return void
    */
   public function admin_notice_minimum_elementor_version() {
      if ( isset( $_GET['activate'] ) ) {
         unset( $_GET['activate'] );
      }
      $message = sprintf(
         /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
         '"%1$s" requires "%2$s" version %3$s or greater.',
         '<strong>' . 'Drive Design Custom Functions' . '</strong>',
         '<strong>' . 'Elementor' . '</strong>',
         self::MINIMUM_ELEMENTOR_VERSION
      );
      printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
   }



   /**
    * Admin notice
    *
    * Warning when the site doesn't have a minimum required PHP version.
    *
    * @return void
    */
   public function admin_notice_minimum_php_version() {
      if ( isset( $_GET['activate'] ) ) {
         unset( $_GET['activate'] );
      }
      $message = sprintf(
         /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
         '"%1$s" requires "%2$s" version %3$s or greater.',
         '<strong>' . 'Drive Design Custom Functions' . '</strong>',
         '<strong>' . 'PHP' . '</strong>',
         self::MINIMUM_PHP_VERSION
      );
      printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
   }



   /**
    * Register DT widgets
    *
    * @return void
    */
	public function on_widgets_registered() {
		require ( DD_PLUGIN_DIR . '/inc/modules/elementor/widgets/dynamic-tabs.php' );
		require ( DD_PLUGIN_DIR . '/inc/modules/elementor/widgets/navigation-menu-tree.php' );

		\Elementor\Plugin::instance()->widgets_manager->register( new DD_Dynamic_Tabs_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new DD_Navigation_Menu_Tree_Widget() );
	}



   /**
    * Register frontend scripts
    *
    * @return void
    */
   public function register_frontend_scripts() {
		wp_register_script( 'dd-dynamic-tabs', DD_PLUGIN_DIR_URL . 'inc/modules/elementor/assets/dynamic-tabs.js', array( 'jquery', 'elementor-frontend' ), DD_PLUGIN_VERSION, true );
		wp_register_script( 'dd-navigation-menu-tree', DD_PLUGIN_DIR_URL . 'inc/modules/elementor/assets/navigation-menu-tree.js', array( 'jquery', 'elementor-frontend' ), DD_PLUGIN_VERSION, true );

   }



   /**
    * Register frontend styles
    *
    * @return void
    */
   public function register_frontend_styles() {
      wp_register_style( 'dd-dynamic-tabs', DD_PLUGIN_DIR_URL . 'inc/modules/elementor/assets/dynamic-tabs.css', array(), DD_PLUGIN_VERSION ); 
      wp_register_style( 'dd-navigation-menu-tree', DD_PLUGIN_DIR_URL . 'inc/modules/elementor/assets/navigation-menu-tree.css', array(), DD_PLUGIN_VERSION ); 
   }
}
new DD_Elementor();