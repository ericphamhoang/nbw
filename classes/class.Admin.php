<?php

// This class is mainly for Admin option page as well as for getting saved options from Database

class NBWAdmin {
    const OPTION_PHONE = "NBW_PHONE";
	const OPTION_ALT_PHONE = "NBW_ALT_PHONE";
	const OPTION_FAX = "NBW_FAX";
	const OPTION_EMAIL = "NBW_EMAIL";

	const OPTION_SOCIAL_FACEBOOK_URL = "NBW_FB_URL";
	const OPTION_SOCIAL_TWITTER_URL = "NBW_TW_URL";
	const OPTION_SOCIAL_LINKEDIN_URL = "NBW_LI_URL";
	const OPTION_SOCIAL_GOOGLEPLUS_URL = "NBW_GP_URL";
	const OPTION_SOCIAL_PINTEREST_URL = "NBW_PI_URL";
	const OPTION_SOCIAL_INSTAGRAM_URL = "NBW_IG_URL";
	const OPTION_SOCIAL_YOUTUBE_URL = "NBW_YT_URL";

    const ENUM_LINK_TYPE_NONE = "none";
    const ENUM_LINK_TYPE_PAGE = "page";
    const ENUM_LINK_TYPE_POST = "post";
    const ENUM_LINK_TYPE_URL = "url";

	static $_registered_options = array(
		self::OPTION_PHONE => array(
			'model_tag' => 'Phone',
			'default' => '00 0000 0000'
		),self::OPTION_ALT_PHONE => array(
			'model_tag' => 'AltPhone',
			'default' => '00 0000 0000'
		),
		self::OPTION_FAX => array(
			'model_tag' => 'Fax',
			'default' => '00 0000 0000'
		),
		self::OPTION_EMAIL => array(
			'model_tag' => 'Email',
			'default' => 'email@domain.com'
		),
		self::OPTION_SOCIAL_FACEBOOK_URL => array(
			'model_tag' => 'FbUrl',
			'default' => 'https://facebook.com'
		),
		self::OPTION_SOCIAL_TWITTER_URL => array(
			'model_tag' => 'TwUrl',
			'default' => 'https://twitter.com'
		),
		self::OPTION_SOCIAL_LINKEDIN_URL => array(
			'model_tag' => 'LiUrl',
			'default' => 'https://linkedin.com'
		),
		self::OPTION_SOCIAL_GOOGLEPLUS_URL => array(
			'model_tag' => 'GpUrl',
			'default' => 'https://plus.google.com'
		),
		self::OPTION_SOCIAL_PINTEREST_URL => array(
			'model_tag' => 'PiUrl',
			'default' => 'https://pinterest.com'
		),
		self::OPTION_SOCIAL_INSTAGRAM_URL => array(
			'model_tag' => 'IgUrl',
			'default' => 'https://instagram.com'
		),
		self::OPTION_SOCIAL_YOUTUBE_URL => array(
			'model_tag' => 'YtUrl',
			'default' => 'https://youtube.com'
		),
	);

    /**
     * Called when the plugin is activate
     */
    static function activate() {
        // Plugin activate
    }

    /**
     * Called when the plugin is deactivated
     */
    static function deactivate() {
        // Plugin deactivate
    }

    /**
     * Called to add NBW Page into the admin menu
     */
    static function admin_menu() {
	    add_menu_page('NBW', 'Nothing But Web', 'manage_options', 'NBWGlucose', array('NBWAdmin', 'option_page'), 'none');
//        add_options_page("NBW Custom", "NBW", "manage_options", "NBW-custom", array("NBWAdmin", "option_page"));
    }

    /**
     * Rendering the option page
     * New options should go here
     */
    static function option_page() {
        $model = array();

	    // Uncomment the following two lines if it is required to retrieve all posts and pages
	    /*
	    $model["Posts"] = NBWUtils::get_all_published_posts();
	    $model["Pages"] = NBWUtils::get_all_published_pages();
	     */

		$options = array();
		foreach(self::$_registered_options as $option_key => $option) {
			$options[$option['model_tag']] = get_option($option_key, $option['default']);
		}

		$model["Options"] = $options;

        include NBW__PLUGIN_DIR . "/pages/admin-options.php";
    }

    /**
     * Option page ajax save
     */
    static function option_save_ajax_callback() {
	    $result = array(
		    'success' => true
	    );

	    try {
			$options = $_POST["options"];

			foreach(self::$_registered_options as $option_key => $option) {
				if (isset($options[$option['model_tag']])) {
					update_option($option_key, stripslashes($options[$option['model_tag']]));
				}
			}
	    }
		catch(\Exception $e) {
			$result["success"] = false;
			$result["message"] = $e->getMessage();
		}

        header('Content-type: application/json', true);
        echo json_encode($result);
        die(); // this is required to return a proper result
    }

    /**
     * Enqueuing javascripts and hooks for admin option page
     * @param $hook
     */
    static function admin_enqueue_assets($hook) {
	    // SCRIPTS
	    // Kendo UIS script
        wp_enqueue_script( 'kendo_ui', NBW__PLUGIN_URL . 'js/Kendo/kendo.web.min.js', array('jquery'), "2014.3.1119" );
	    // Admin javascript
        wp_enqueue_script( 'NBW_main', NBW__PLUGIN_URL . 'js/admin_option.js', array('kendo_ui'), NBW__ASSET_VERSION );
	    // Helper object for ajax call
        wp_localize_script( 'NBW_main', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );

	    // STYLESHEETS
	    // Bootstrap
        wp_enqueue_style( 'bootstrap' , NBW__PLUGIN_URL . 'styles/Bootstrap/bootstrap.custom.css', array(), "3.3.1");
	    // Font awesome
        wp_enqueue_style( 'font_awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', array('bootstrap'), "4.2.0");
	    // Kendo UIS
        wp_enqueue_style( 'kendo_ui_core', NBW__PLUGIN_URL . 'styles/Kendo/kendo.common-bootstrap.min.css', array(), "2014.3.1119" );
        wp_enqueue_style( 'kendo_ui_flat', NBW__PLUGIN_URL . 'styles/Kendo/kendo.bootstrap.min.css', array(), "2014.3.1119" );

	    // Admin Option
        wp_enqueue_style( 'NBW_main', NBW__PLUGIN_URL . 'styles/admin_option.css', array(), NBW__ASSET_VERSION );
    }

	// Get an option from the configurations
	static function get_option($option_name) {
		if (!isset(self::$_cached[$option_name])) {
			self::$_cached[$option_name] = get_option($option_name);
		}
		return self::$_cached[$option_name];
	}

    static $_cached = array();
}