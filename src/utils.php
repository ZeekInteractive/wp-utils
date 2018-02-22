<?php

namespace Zeek\WP_Util;

/**
 * Returns the current URL.
 *
 * @link http://wordpress.stackexchange.com/a/126534
 *
 * @return string
 */
function get_current_url() {
	return home_url( add_query_arg( null, null ) );
}

/**
 * Returns the current URL, but without query args.
 *
 * @return string
 */
function get_current_url_clean() {
	$current_url = get_current_url();

	$url_parts = parse_url( $current_url );

	return home_url( $url_parts['path'] );
}

/**
 * Performs a reverse lookup of a post based on it's slug
 * Stores in whatever default cache is available in order to minimize duplicate
 * lookups (as this can get expensive)
 *
 * @return int|null
 */
function get_id_from_slug( $slug, $post_type = 'post', $force = false ) {

	global $wpdb;

	$cache_key = sprintf( 'post_%s_id', md5( $post_type . $slug ) );
	$id        = wp_cache_get( $cache_key );

	if ( false === $id || $force ) {

		$sql = sprintf( "
			SELECT 
				ID
			FROM 
				%s
			WHERE 
				post_status = 'publish' AND
				post_name   = '%s' AND
				post_type   = '%s'
			LIMIT 1
			",
			$wpdb->posts,
			sanitize_text_field( $slug ),
			sanitize_text_field( $post_type )
		);

		$id = $wpdb->get_var( $sql );

		wp_cache_set( $cache_key, $id );
	}

	if ( empty( $id ) ) {
		return null;
	}

	return intval( $id );
}

/**
 * Performs a very direct, simple query to the WordPress Options table
 * that bypasses normal WP caching
 *
 * @param $key
 *
 * @return int
 */
function get_raw_option_value( $key ) {
	global $wpdb;

	$sql = $wpdb->prepare( "
		SELECT 
			option_value 
		FROM 
			{$wpdb->options}
		WHERE 
			option_name = %s
		LIMIT 1
		",
		$key
	);

	$version = $wpdb->get_var( $sql );

	return intval( $version );
}

/**
 * Helper function to check for an environmental variable in a variety of places:
 * - $_ENV (for setting via .env.php files)
 * - Constant (for setting via a define() call)
 * - Filter, utilizing a passed in filter
 * 
 * @param      $key
 * @param null $filter
 *
 * @return mixed|null
 */
function get_env_value( $key, $filter = null ) {
	if ( ! empty( $_ENV[ $key ] ) ) {
		return $_ENV[ $key ];
	}

	if ( defined( $key ) ) {
		return constant( $key );
	}
	
	if ( function_exists( $filter ) ) {
		return apply_filters( $filter, null ); 
	}

	return null;
}

/**
 * Allow to remove method for an hook when, it's a class method used and
 * class don't have variable, but you know the class name
 * 
 * @param string $hook_name
 * @param string $class_name
 * @param string $method_name
 * @param int    $priority
 *
 * @return bool
 */
function remove_filters_for_anonymous_class( $hook_name = '', $class_name = '', $method_name = '', $priority = 0 ) {
	global $wp_filter;

	// Take only filters on right hook name and priority
	if ( ! isset( $wp_filter[ $hook_name ][ $priority ] ) || ! is_array( $wp_filter[ $hook_name ][ $priority ] ) ) {
		return false;
	}

	// Loop on filters registered
	foreach ( (array) $wp_filter[ $hook_name ][ $priority ] as $unique_id => $filter_array ) {
		// Test if filter is an array ! (always for class/method)
		if ( isset( $filter_array['function'] ) && is_array( $filter_array['function'] ) ) {
			// Test if object is a class, class and method is equal to param !
			if ( is_object( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) == $class_name && $filter_array['function'][1] == $method_name ) {
				// Test for WordPress >= 4.7 WP_Hook class (https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/)
				if ( is_a( $wp_filter[ $hook_name ], 'WP_Hook' ) ) {
					unset( $wp_filter[ $hook_name ]->callbacks[ $priority ][ $unique_id ] );
				} else {
					unset( $wp_filter[ $hook_name ][ $priority ][ $unique_id ] );
				}
			}
		}

	}

	return false;
}

/**
 * Check to see if:
 * - ACF has been loaded and we can define our fields
 * - ACF_LITE is enabled
 * 
 * If ACF_LITE is enabled, ensure the ACF_LITE constant is also defined
 * 
 * @return bool
 */
function is_acf_loadable() {

	// Bail if ACF is not found or deactivated
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return false;
	}

	// Only load our hardcoded fields if ACF Lite is off
	if ( true !== get_env_value( 'ACF_LITE' ) ) {
		return false;
	}

	// If ACF_LITE was defined in a manner different from a constant, set the constant so that ACF turns on LITE mode
	if ( ! defined( 'ACF_LITE' ) ) {
		define( 'ACF_LITE', true );
	}

	return true;
}


/**
 * Safely load inline SVG file, if exists
 * 
 * @param null $path
 *
 * @return bool|null|string
 */
function get_inline_svg( $path = null ) {

	$full_path = sprintf( '%s/%s',
		get_template_directory(),
		$path
	);

	if ( ! file_exists( $full_path ) ) {
		return null;
	}

	return file_get_contents( $full_path );
}

/**
 * Use this when you know an ACF field key and a post ID, but the field is within a group.
 * 
 * This uses a non-performant lookup method, so use this with care.
 * 
 * @param $key
 * @param $post_id
 *
 * @return bool|mixed
 */
function get_acf_meta_value_by_acf_key( $key, $post_id ) {

	$lookup_key = get_meta_key_from_meta_value( $post_id, $key );

	if ( empty( $lookup_key ) ) {
		return false;
	}

	if ( 0 !== strpos( $lookup_key, '_' ) ) {
		return false;
	}

	$lookup_key = substr( $lookup_key, 1 );

	$real_value = get_post_meta( $post_id, $lookup_key, true );

	return $real_value;
}

/**
 * Perform a reverse lookup for a meta key based on a meta value.
 * 
 * This is pretty non-performant, so take care in using this.
 * 
 * @param $post_id
 * @param $meta_value
 *
 * @return null|string
 */
function get_meta_key_from_meta_value( $post_id, $meta_value ) {
	global $wpdb;

	$sql = $wpdb->prepare( " 
		SELECT
			pm.meta_key
		FROM
			$wpdb->postmeta as pm
		WHERE
			pm.post_id = %d AND
			pm.meta_value = %s
		",
		intval( $post_id ),
		sanitize_text_field( $meta_value )
	);

	$result = $wpdb->get_var( $sql );

	return $result;
}

/**
 * Gets a DateTime set to the WordPress's timezone
 *
 * @return \DateTime
 */
function get_current_datetime() {
	$current_datetime = new \DateTime( 'now', get_site_timezone() );

	return $current_datetime;
}

/**
 * Gets a DateTimeZone object based on the site's timezone string.
 *
 * @return \DateTimeZone
 */
function get_site_timezone() {
	$timezone_string = get_option( 'timezone_string' );

	if ( empty( $timezone_string ) ) {
		$timezone_string = 'UTC';
	}

	return new \DateTimeZone( $timezone_string );
}


/**
 * Performs a very direct, simple query to the WordPress Post Meta table
 * that bypasses normal WP caching
 *
 * @param $key
 *
 * @return int
 */
function get_raw_post_meta_value( $post_id, $key ) {
	global $wpdb;

	$sql = $wpdb->prepare( "
		SELECT 
			meta_value
		FROM 
			{$wpdb->postmeta}
		WHERE 
			post_id = %d AND
			meta_key = %s
		LIMIT 1
		",
		intval( $post_id ),
		$key
	);

	$version = $wpdb->get_var( $sql );

	return intval( $version );
}
