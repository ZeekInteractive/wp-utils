<?php

namespace Zeek\WP_Utils;

if ( ! class_exists('WP_Object_Cache' ) ) {
	return;
}

class CacheUtil extends \WP_Object_Cache {
	public function searchKeys( $search ) {
		if ( ! $this->redisExists() ) {
			return false;
		}

		return $this->_call_redis( 'keys', $search );
	}

	public function deleteKey( $key ) {
		if ( ! $this->redisExists() ) {
			return false;
		}

		return $this->_call_redis( 'del', $key );
	}

	private function redisExists() {
		return method_exists( $this, '_call_redis' );
	}
}
