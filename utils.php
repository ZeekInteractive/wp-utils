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