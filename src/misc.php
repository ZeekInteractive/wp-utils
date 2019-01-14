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
 * Get an array of parts of the current URL
 * 
 * @return mixed
 */
function get_current_url_parts() {
	$current_url = get_current_url();

	$url_parts = parse_url( $current_url );
	
	return $url_parts;
}

/**
 * Returns the current URL, but without query args.
 *
 * @return string
 */
function get_current_url_clean() {
	$url_parts = get_current_url_parts();

	return home_url( $url_parts['path'] ?? null );
}

/**
 * Returns the current path
 * 
 * @return string
 */
function get_current_url_path() {
	$url_parts = get_current_url_parts();
	
	return $url_parts['path'] ?? '/';
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
function init_term( $slug, $taxonomy ) {

	$term = get_term_by( 'slug', $slug, $taxonomy );

	if ( ! empty( $term->ID ) ) {
		return $term->ID;
	}

	return wp_insert_term( $slug, $taxonomy );
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
function get_env_value( $key ) {
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
function is_acf_loadable() {
	return false;
}

/**
 * Safely load inline SVG file, if exists
 *
 * @param null $path
 *
 * @return bool|null|string
 */
function get_inline_svg( $path = null ) {

	if ( file_exists( $path ) ) {
		return file_get_contents( $path );
	}
	
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
function remove_filters_for_anonymous_class( $hook_name = '', $class_name = '', $method_name = '', $priority = 0 ) {
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

				/**
				 * Remove the callback now that we've found it's unique ID
				 * This method only works on WP 4.7+ due to the change to hooks/filters
				 * to WP in 4.7
				 */
				unset( $wp_filter[ $hook_name ]->callbacks[ $priority ][ $unique_id ] );
				$status = true;
			}
		}

	}

	return $status;
}
