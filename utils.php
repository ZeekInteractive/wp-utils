<?php

namespace Zeek\WP_Util;

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
