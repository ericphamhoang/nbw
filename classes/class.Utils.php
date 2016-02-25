<?php

class NBWUtils {
    /**
     * Find a string $find with in $string
     * @param string $string Base string to find other string from
     * @param string $find String to find from base string
     * @param bool $caseSensitive Determine if the function should match case sensitive keyword (default=True)
     * @return bool
     */
    public static function str_contains($string, $find, $caseSensitive = true) {
        if ($caseSensitive) {
            if (strpos($string,$find) !== false) return true;
        }
        else {
            if (strpos(strtoupper($string),strtoupper($find)) !== false) return true;
        }
        return false;
    }

    /**
     * Check if $needle is at the beginning of $haystack string
     * @param string $haystack Base string to find $needle string from
     * @param string $needle String to test for
     * @param bool $caseSensitive Determine if the function should match case sensitive keyword (default=True)
     * @return bool
     */
    public static function start_with($haystack, $needle, $caseSensitive = true)
    {
        return strncmp($caseSensitive?$haystack:strtolower($haystack), $caseSensitive?$needle:strtolower($needle), strlen($needle)) == 0;
    }

    /**
     * Check if $needle is at the end of $haystack string
     * @param string $haystack Base string to find $needle string from
     * @param string $needle String to test for
     * @param bool $caseSensitive Determine if the function should match case sensitive keyword (default=True)
     * @return bool
     */
    public static function end_with($haystack, $needle, $caseSensitive = true)
    {
        $length = strlen($needle);
        if ($length == 0) {return true;}
        return (substr(($caseSensitive?$haystack:strtolower($haystack)), -$length) === ($caseSensitive?$needle:strtolower($needle)));
    }

	/**
     * Check if string is empty or white spaces
     * @param $question
     * @return bool
     */
    public static function is_null_or_empty($question){
        if ($question === null) return true;
        if (!is_string($question)) return false;
        return (!isset($question) || trim($question)==='');
    }

    /**
     * Format number to include dollar sign, thousands separator and format to two decimal points
     * @param $number
     * @param int $decimals
     * @param string $decPoint
     * @param string $thousandsSep
     * @return string
     */
	public static function priceFilter($number, $decimals = 2, $decPoint = '.', $thousandsSep = ',') {
        return ($number >= 0 ? '':'-') . '$' . self::thousandFilter(abs($number), $decimals, $decPoint, $thousandsSep);
    }

    /**
     * Format number to include thousands separator
     * @param $number
     * @param int $decimals
     * @param string $decPoint
     * @param string $thousandsSep
     * @return string
     */
	public static function thousandFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',') {
        return number_format($number, $decimals, $decPoint, $thousandsSep);
    }

    /**
     * Encrypting a string, using a key defaulting to Wordpress AUTH_KEY
     * @param $string
     * @param $key
     * @return string
     */
	public static function encrypt($string, $key = SECURE_AUTH_KEY) {
		$result = '';
		for ( $i = 0; $i < strlen( $string ); $i ++ ) {
			$char    = substr( $string, $i, 1 );
			$keychar = substr( $key, ( $i % strlen( $key ) ) - 1, 1 );
			$char    = chr( ord( $char ) + ord( $keychar ) );
			$result .= $char;
		}

		return base64_encode( $result );
	}

    /**
     * Decrypting a string, using a key defaulting to Wordpress AUTH_KEY
     * @param $string
     * @param $key
     * @return string
     */
	public static function decrypt($string, $key = SECURE_AUTH_KEY) {
	    $result = '';
	    $string = base64_decode($string);

	    for($i = 0; $i < strlen($string); $i++) {
	        $char = substr($string, $i, 1);
	        $keychar = substr($key, ($i % strlen($key))-1, 1);
	        $char = chr(ord($char) - ord($keychar));
	        $result .= $char;
	    }

	    return $result;
	}
    /**
     * Try to decrypt a string, return decrypted string if success or original string if encrypted
     * @param $string
     * @param $key
     * @return string
     */
    public static function attempt_decrypt($encrypted, $key = SECURE_AUTH_KEY) {
        $decrypted = self::decrypt($encrypted, $key);
        return (self::encrypt($decrypted, $key) == $encrypted) ? $decrypted : $encrypted;
    }

    /**
     * Converting an object to array
     * @param $d
     * @return array
     */
    public static function object_to_array($d) {
        if (is_object($d))
            $d = get_object_vars($d);

        return is_array($d) ? array_map(__METHOD__, $d) : $d;
    }

    /**
     * Convert an array to stdClass object
     * @param $d
     * @return object
     */
    public static function array_to_object($d) {
        return is_array($d) ? (object) array_map(__METHOD__, $d) : $d;
    }

    /**
     * Return current time
     * @return DateTime
     */
    public static function now() {
        return new \DateTime('now');
    }

    /**
     * Validate if APC exists
     * @return bool
     */
    public static function apc_enabled() {
        return (extension_loaded('apc') && ini_get('apc.enabled') && function_exists("apc_fetch"));
    }

    /**
     * Load a key <-> value pair from APC, providing expected expiry time to deprecate value
     * @param $key
     * @param $expiry
     * @return array|bool
     */
    public static function load_apc($key, $expiry) {
        if (!self::apc_enabled()) { return false; }
        if (apc_exists($key) && apc_exists($key . "_timestamp")) {
            $ts = \DateTime::createFromFormat("Y-m-d H:i:s", apc_fetch($key . "_timestamp"));
            $diff = $ts->diff(self::now(), true);
            foreach($expiry as $t => $exp) {
                if ($diff->$t >= $exp) {
                    return false; //expired
                }
            }
            $value = apc_fetch($key);
            return array(
                "value" => $value,
                "timestamp" => $ts
            );
        }
    }

    /**
     * Save a key <-> value pair to APC if APC is enabled
     * @param $key
     * @param $value
     * @param int $ttl
     * @return array
     */
    public static function save_apc($key, $value, $ttl = 0) {
        $now = self::now();

        if (self::apc_enabled()) {
            apc_store( $key, $value, $ttl );
            apc_store( $key . "_timestamp", $now->format( "Y-m-d H:i:s" ), $ttl );
        }

        return array(
            "value" => $value,
            "timestamp" => $now
        );
    }


    /**
     * Return a random alphanumeric string having length of $length
     * @param int $length
     * @param string $characters
     * @return string
     */
    public static function generate_random_string($length = 10, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * Get client's current IpAddress
     * @return string
     */
    public static function get_client_ip() {
        if ($_SERVER['HTTP_CLIENT_IP'])
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if($_SERVER['HTTP_X_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if($_SERVER['HTTP_X_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if($_SERVER['HTTP_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if($_SERVER['HTTP_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if($_SERVER['REMOTE_ADDR'])
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    /**
     * Render data to json output and exit
     * @param $data
     */
    public static function to_json_output($data){
        header('Content-type: application/json', true);
		echo json_encode($data);
		die();
    }


    /**
     * Get all Wordpress's published posts
     * @return array
     */
	public static function get_all_published_posts() {
		$posts = array();
		$args = array(
			'orderby'          => 'post_title',
			'order'            => 'ASC',
			'post_status'      => 'publish',
			'post_type'        => 'post',
            'posts_per_page'   => '-1',
			'suppress_filters' => true );

		$published_posts = get_posts( $args );
		foreach ($published_posts as $post) {
			/* @var $post WP_Post */

			$posts[] = array(
				"id" => $post->ID,
				"title" => $post->post_title,
				"name" => $post->post_name
			);
		}
		return $posts;
	}


    /**
     * Get all Wordpress's published pages
     * @return array
     */
	public static function get_all_published_pages() {
		// getting all pages

		$pages_args = array(
			'sort_order' => 'ASC',
			'sort_column' => 'post_title',
			'post_type' => 'page',
            'posts_per_page' => '-1',
			'post_status' => 'publish'
		);

		$published_pages = get_pages($pages_args);
		$pages = array();

		foreach ($published_pages as $page) {
			$pages[] = array(
				"id" => $page->ID,
				"title" => $page->post_title,
				"name" => $page->post_name
			);
		}

		return $pages;
	}
}