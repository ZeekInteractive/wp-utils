<?php

namespace Zeek\WP_Util;

class Misc {

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

	/**
	 * Gets a DateTimeZone object based on the site's timezone string.
	 *
	 * @return \DateTimeZone
	 */
	static function get_site_timezone() {
		$timezone_string = get_option( 'timezone_string' );

		if ( empty( $timezone_string ) ) {
			$timezone_string = 'UTC';
		}

		return new \DateTimeZone( $timezone_string );
	}

	/**
	 * Gets a DateTime set to the WordPress's timezone
	 *
	 * @return \DateTime
	 */
	static function get_current_datetime() {
		$current_datetime = new \DateTime( 'now', self::get_site_timezone() );

		return $current_datetime;
	}

	/**
	 * Helper function to check for an environmental variable in a variety of places:
	 * - $_ENV (for setting via .env.php files)
	 * - Constant (for setting via a define() call)
	 * - Filter, utilizing a passed in filter
	 *
	 * @param      $key
	 *
	 * @return mixed|null
	 */
	static function get_env_value( $key ) {
		if ( ! empty( $_ENV[ $key ] ) ) {
			return $_ENV[ $key ];
		}

		if ( defined( $key ) ) {
			return constant( $key );
		}

		return null;
	}

	/**
	 * @deprecated 2.0.0 Use action hook 'acf/init' to register field and ENV value of 'acf_lite' to set Lite mode
	 *
	 * @return bool
	 */
	static function is_acf_loadable() {
		return false;
	}

	/**
	 * Safely load inline SVG file, if exists
	 *
	 * @param null $path
	 *
	 * @return bool|null|string
	 */
	static function get_inline_svg( $path = null ) {

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
	 * Allow to remove method for an hook when, it's a class method used and the class
	 * instance isn't accessible, but you know the class name
	 *
	 * @param string $hook_name
	 * @param string $class_name
	 * @param string $method_name
	 * @param int    $priority
	 *
	 * @return bool
	 */
	static function remove_filters_for_anonymous_class( $hook_name = '', $class_name = '', $method_name = '', $priority = 0 ) {
		global $wp_filter;

		// Take only filters on right hook name and priority
		if ( ! isset( $wp_filter[ $hook_name ]->callbacks[ $priority ] ) || ! is_array( $wp_filter[ $hook_name ]->callbacks[ $priority ] ) ) {
			return false;
		}

		$status = false;

		// Loop on filters registered
		foreach ( (array) $wp_filter[ $hook_name ]->callbacks[ $priority ] as $unique_id => $filter_array ) {
			// Test if filter is an array ! (always for class/method)
			if ( isset( $filter_array['function'] ) && is_array( $filter_array['function'] ) ) {

				// Test if object is a class, class and method is equal to param !
				if ( is_object( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) == $class_name && $filter_array['function'][1] == $method_name ) {

					// Test for WordPress >= 4.7 WP_Hook class (https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/)
					unset( $wp_filter[ $hook_name ]->callbacks[ $priority ][ $unique_id ] );
					$status = true;
				}
			}

		}

		return $status;
	}
}