<?php
/**
 * This class is mainly for front facing assets
 * User: nkahoang
 * Date: 8/07/2014
 * Time: 8:38 PM
 */

class NBWFrontFacing {
	/**
	 * Enqueuing assets for the site
	 * @param $hook
	 */
	static function site_enqueue_assets($hook) {
		wp_enqueue_script( 'nbw_custom', NBW__PLUGIN_URL . 'js/main.js', array('jquery'), NBW__ASSET_VERSION );
		wp_enqueue_style( 'nbw_custom', NBW__PLUGIN_URL . 'styles/main.css', array(), NBW__ASSET_VERSION );
	}
} 