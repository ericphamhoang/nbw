<?php
/*
    Plugin Name: Nothing But Web
    Plugin URI: http://nothingbutweb.com.au
    Description: Plugin to add additional features to the website
    Version: 1.1
    Author: Hoang Nguyen
    License: Proprietary
*/

define( 'NBW_VERSION', '1.1.0' );
define( 'NBW__MINIMUM_WP_VERSION', '3.0' );
define( 'NBW__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'NBW__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'NBW__NO_FOOTER', 6 );
define( 'NBW__ASSET_VERSION', 2);
define( 'NBW__ENABLE_MVC', false);

require_once( NBW__PLUGIN_DIR . '/classes/class.Admin.php' );
require_once( NBW__PLUGIN_DIR . '/classes/class.FrontFacing.php' );
require_once( NBW__PLUGIN_DIR . '/classes/class.HooksAndFilters.php' );
require_once( NBW__PLUGIN_DIR . '/classes/class.ShortCodes.php' );
require_once( NBW__PLUGIN_DIR . '/classes/class.TwigExtension.php' );
require_once( NBW__PLUGIN_DIR . '/classes/class.Twig.php' );
require_once( NBW__PLUGIN_DIR . '/classes/class.Utils.php' );
require_once( NBW__PLUGIN_DIR . '/models/class.ModelManagementBase.php' );

register_activation_hook( __FILE__, array ('NBWAdmin', 'activate'));
register_deactivation_hook( __FILE__, array( 'NBWAdmin', 'deactivate' ));

add_action( 'init', array('NBWShortCodes', 'nbw_shortcode_initialize'));
add_action( 'init', array('NBWHooksAndFilters', 'register_hooks_and_filters'));

add_action( 'admin_menu', array( 'NBWAdmin', 'admin_menu' ));
add_action( 'admin_enqueue_scripts', array( 'NBWAdmin', 'admin_enqueue_assets' ));
add_action( 'wp_ajax_NBW_option_update', array( 'NBWAdmin', 'option_save_ajax_callback' ) );
add_action( 'wp_enqueue_scripts', array ('NBWFrontFacing', 'site_enqueue_assets') );

if ('NBW__ENABLE_MVC') {
    require_once( NBW__PLUGIN_DIR . '/classes/class.MVCRouter.php' );
    require_once( NBW__PLUGIN_DIR . '/controllers/BaseController.php' );
    add_action('parse_request', array( 'NBWRouter', 'processRequest'));
}