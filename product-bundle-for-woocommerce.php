<?php
/**
* Plugin Name: Product Bundles for WooCommerce
* Description: This plugin allows create Product Bundles for WooCommerce plugin.
* Version: 1.0
* Copyright: 2020
* Text Domain: product-bundles-for-woocommerce
* Domain Path: /languages 
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
  die('-1');
}

if (!defined('OCPBW_PLUGIN_NAME')) {
  define('OCPBW_PLUGIN_NAME', 'Product Bundles for WooCommerce');
}
if (!defined('OCPBW_PLUGIN_VERSION')) {
  define('OCPBW_PLUGIN_VERSION', '1.0');
}
if (!defined('OCPBW_PLUGIN_FILE')) {
  define('OCPBW_PLUGIN_FILE', __FILE__);
}
if (!defined('OCPBW_PLUGIN_DIR')) {
  define('OCPBW_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('OCPBW_BASE_NAME')) {
    define('OCPBW_BASE_NAME', plugin_basename(OCPBW_PLUGIN_FILE));
}
if (!defined('OCPBW_DOMAIN')) {
  define('OCPBW_DOMAIN', 'product-bundles-for-woocommerce');
}


if (!class_exists('OCPBW')) {

    class OCPBW {

        protected static $OCPBW_instance;
        function __construct() {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            add_action('admin_init', array($this, 'OCPBW_check_plugin_state'));
        }


        function OCPBW_check_plugin_state(){
            if ( ! ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
                set_transient( get_current_user_id() . 'wqrerror', 'message' );
            }
        }


        function init() {
            add_action( 'admin_notices', array($this, 'OCPBW_show_notice'));
            add_action( 'admin_enqueue_scripts', array($this, 'OCPBW_load_admin'));
            add_action( 'wp_enqueue_scripts',  array($this, 'OCPBW_load_front'));
            add_filter( 'plugin_row_meta', array( $this, 'OCPBW_plugin_row_meta' ), 10, 2 );
        }


        function OCPBW_show_notice() {
            if ( get_transient( get_current_user_id() . 'wqrerror' ) ) {
                deactivate_plugins( plugin_basename( __FILE__ ) );
                delete_transient( get_current_user_id() . 'wqrerror' );
                echo '<div class="error"><p> This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=woocommerce">WooCommerce</a> plugin installed and activated.</p></div>';
            }
        }


        function OCPBW_plugin_row_meta( $links, $file ) {
            if ( OCPBW_BASE_NAME === $file ) {
                $row_meta = array(
                    'rating'    =>  ' <a href="https://www.xeeshop.com/product-bundles-for-woocommerce/" target="_blank">Documentation</a> | <a href="https://www.xeeshop.com/support-us/?utm_source=aj_plugin&utm_medium=plugin_support&utm_campaign=aj_support&utm_content=aj_wordpress" target="_blank">Support</a> | <a href="https://wordpress.org/support/plugin/product-bundles-for-woocommerce/reviews/?filter=5" target="_blank"><img src="'.OCPBW_PLUGIN_DIR.'/includes/images/star.png" class="ocpbw_rating_div"></a>',
                );
                return array_merge( $links, $row_meta );
            }
            return (array) $links;
        }


        function OCPBW_load_admin() {
            wp_enqueue_style( 'OCPBW_admin_style', OCPBW_PLUGIN_DIR . '/includes/css/ocpbw_back_style.css', false, '1.0.0' );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker-alpha', OCPBW_PLUGIN_DIR . '/includes/js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), '1.0.0', true );
            $ocscreen = get_current_screen();
            if($ocscreen->id == 'product') {
                wp_enqueue_script('OCPBW_back_script', OCPBW_PLUGIN_DIR .'/includes/js/ocpbw_back_script.js', array( 'jquery', 'select2'));
                wp_localize_script( 'ajaxloadpost', 'ajax_postajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
            }

        }


        function OCPBW_load_front() {
            wp_enqueue_style( 'OCPBW_front_style', OCPBW_PLUGIN_DIR . '/includes/css/ocpbw_front_style.css', false, '1.0.0' );
        }
 
        
        function includes() {
            include_once('admin/ocpbw-comman.php');
            include_once('admin/ocpbw-admin.php');
            include_once('admin/ocpbw-kit.php');
            include_once('front/ocpbw-front.php');
            
        }


        public static function OCPBW_instance() {
            if (!isset(self::$OCPBW_instance)) {
                self::$OCPBW_instance = new self();
                self::$OCPBW_instance->includes();
                self::$OCPBW_instance->init();
            }
            return self::$OCPBW_instance;
        }

    }

   
    add_action('plugins_loaded', array('OCPBW', 'OCPBW_instance'));

}


add_action( 'plugins_loaded', 'OCPBW_load_textdomain' );
 
function OCPBW_load_textdomain() {
    load_plugin_textdomain( 'product-bundles-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

function OCPBW_load_my_own_textdomain( $mofile, $domain ) {
    if ( 'product-bundles-for-woocommerce' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
        $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
        $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
    }
    return $mofile;
}
add_filter( 'load_textdomain_mofile', 'OCPBW_load_my_own_textdomain', 10, 2 );