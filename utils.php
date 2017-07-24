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
