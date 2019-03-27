<?php

namespace Zeek\WP_Utils;

/**
 * Make a remote POST request with application/json header set and auto JSON encoding of the data 
 * 
 * @param string $url
 * @param array  $data
 *
 * @return array
 * @throws \Exception
 */
function remote_post( string $url, array $data ) : array {
	
	$response = wp_remote_post( $url, [
		'headers'     => [ 'Content-Type' => 'application/json; charset=utf-8' ],
		'body'        => json_encode( $data ),
		'method'      => 'POST',
		'data_format' => 'body',
	] );
	
	if ( is_wp_error( $response ) ) {
		throw new \Exception( $response->get_error_message() );
	}
	
	return $response;
}
