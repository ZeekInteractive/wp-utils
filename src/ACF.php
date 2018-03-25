<?php

namespace Zeek\WP_Util;

/**
 * Use this when you know an ACF field key and a post ID, but the field is within a group.
 *
 * This uses a non-performant lookup method, so use this with care.
 *
 * @param $key
 * @param $post_id
 *
 * @return bool|mixed
 */
function get_acf_meta_value_by_acf_key( $key, $post_id ) {

	$lookup_key = get_meta_key_from_meta_value( $post_id, $key );

	if ( empty( $lookup_key ) ) {
		return false;
	}

	if ( 0 !== strpos( $lookup_key, '_' ) ) {
		return false;
	}

	$lookup_key = substr( $lookup_key, 1 );

	$real_value = get_post_meta( $post_id, $lookup_key, true );

	return $real_value;
}