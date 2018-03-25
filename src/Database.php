<?php

namespace Zeek\WP_Util;

class Database {

	/**
	 * Performs a reverse lookup of a post based on it's slug
	 * Stores in whatever default cache is available in order to minimize duplicate
	 * lookups (as this can get expensive)
	 *
	 * @return int|null
	 */
	static function get_id_from_slug( $slug, $post_type = 'post', $force = false ) {

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
}