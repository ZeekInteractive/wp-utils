<?php

namespace Zeek\WP_Util;

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
 * Gets a DateTime set to the WordPress's timezone
 *
 * @return \DateTime
 */
function get_current_datetime() {
	$current_datetime = new \DateTime( 'now', get_site_timezone() );

	return $current_datetime;
}


function get_iso_datetime( $post, $mod = false ) {

	$timezone_string = get_option( 'timezone_string' );
	if ( empty( $timezone_string ) ) {
		return false;
	}

	if ( $mod ) {
		$post_date = new \DateTime( get_post_modified_time( 'c', true, $post ) );
	} else {
		$post_date = new \DateTime( get_post_time( 'c', true, $post ) );
	}
	$timezone = new \DateTimeZone( $timezone_string );

	if ( empty( $post_date ) || empty( $timezone ) ) {
		return false;
	}

	$post_date->setTimeZone( $timezone );

	return $post_date->format( 'c' );

}