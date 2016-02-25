<?php

class NBWShortCodes {
	static function nbw_shortcode_initialize() {
		$shortcode_list = array(
			'nbw_site_url','nbw_theme_url','nbw_phone','nbw_alt_phone','nbw_fax','nbw_email',
			'nbw_facebook_url','nbw_twitter_url','nbw_linkedin_url','nbw_google_plus_url','nbw_pinterest_url','nbw_instagram_url','nbw_youtube_url');
		foreach($shortcode_list as $shortcode) {
			add_shortcode($shortcode, array('NBWShortCodes', $shortcode));
		}
	}
	/* Configurable fields */
	static function nbw_site_url() {
		return get_home_url();
	}
	static function nbw_theme_url() {
		return get_stylesheet_directory_uri();
	}
	static function nbw_phone() {
		return NBWAdmin::get_option(NBWAdmin::OPTION_PHONE);
	}
	static function nbw_alt_phone() {
		return NBWAdmin::get_option(NBWAdmin::OPTION_ALT_PHONE);
	}
	static function nbw_fax() {
		return NBWAdmin::get_option(NBWAdmin::OPTION_FAX);
	}
	static function nbw_email() {
		return NBWAdmin::get_option(NBWAdmin::OPTION_EMAIL);
	}
	static function nbw_facebook_url() {
		return NBWAdmin::get_option(NBWAdmin::OPTION_SOCIAL_FACEBOOK_URL);
	}
	static function nbw_twitter_url() {
		return NBWAdmin::get_option(NBWAdmin::OPTION_SOCIAL_TWITTER_URL);
	}
	static function nbw_linkedin_url() {
		return NBWAdmin::get_option(NBWAdmin::OPTION_SOCIAL_LINKEDIN_URL);
	}
	static function nbw_google_plus_url() {
		return NBWAdmin::get_option(NBWAdmin::OPTION_SOCIAL_GOOGLEPLUS_URL);
	}
	static function nbw_pinterest() {
		return NBWAdmin::get_option(NBWAdmin::OPTION_SOCIAL_PINTEREST_URL);
	}
	static function nbw_instagram_url() {
		return NBWAdmin::get_option(NBWAdmin::OPTION_SOCIAL_INSTAGRAM_URL);
	}
	static function nbw_youtube_url() {
		return NBWAdmin::get_option(NBWAdmin::OPTION_SOCIAL_YOUTUBE_URL);
	}
}