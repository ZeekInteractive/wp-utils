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
				post_name = '%s' AND
				post_type = '%s'
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
