<?php declare( strict_types = 1 );

namespace Zeek\WP_Util;

class WP_Util {

	/**
	 * Returns the current URL.
	 *
	 * @link http://wordpress.stackexchange.com/a/126534
	 *
	 * @return string
	 */
	static function get_current_url() {
		return home_url( add_query_arg( null, null ) );
	}

	/**
	 * Returns the current URL, but without query args.
	 *
	 * @return string
	 */
	static function get_current_url_clean() {
		$current_url = self::get_current_url();

		$url_parts = parse_url( $current_url );

		return home_url( $url_parts['path'] );
	}

	/**
	 * Checks for and returns a term by the slug
	 *
	 * Initializes the term if it does not yet exist
	 *
	 * @param string $slug
	 * @param string $taxonomy
	 *
	 * @return bool
	 */
	static function init_term( $slug, $taxonomy ) {

		$term = get_term_by( 'slug', $slug, $taxonomy );

		if ( ! empty( $term->ID ) ) {
			return $term->ID;
		}

		return wp_insert_term( $slug, $taxonomy );
	}
}