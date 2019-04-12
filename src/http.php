<?php

namespace Zeek\WP_Utils;

/**
 * Make a remote POST request with application/json header set and auto JSON encoding of the data 
 * 
 * @param string $url
 * @param array  $data
 * @param array  $headers
 *
 * @return array
 * @throws \Exception
 */
function remote_post( string $url, array $data, array $headers = [] ) : array {

	$header_defaults = [
		'Content-Type' => 'application/json'
	];

	$headers = wp_parse_args( $headers, $header_defaults );
	
	$response = wp_remote_post( $url, [
		'headers'     => $headers,
		'body'        => json_encode( $data ),
		'method'      => 'POST',
		'data_format' => 'body',
	] );
	
	if ( is_wp_error( $response ) ) {
		throw new \Exception( $response->get_error_message() );
	}
	
	return $response;
}
