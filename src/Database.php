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
	static function get_meta_key_from_meta_value( $post_id, $meta_value ) {
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
	 * Performs a very direct, simple query to the WordPress Post Meta table
	 * that bypasses normal WP caching
	 *
	 * @param $key
	 *
	 * @return int
	 */
	static function get_raw_post_meta_value( $post_id, $key ) {
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

		return $wpdb->get_var( $sql );
	}

	/**
	 * Performs a very direct, simple query to the WordPress Options table
	 * that bypasses normal WP caching
	 *
	 * @param $key
	 *
	 * @return int
	 */
	static function get_raw_option_value( $key ) {
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

		return $wpdb->get_var( $sql );
	}
}