<?php

namespace Zeek\WP_Util;

function admin_notices( $notice = null ) {
	static $notices;

	if ( null === $notices ) {
		$notices = [];
	}

	if ( empty( $notice ) ) {
		return $notices;
	}

	if ( ! in_array( $notice['key'], $notices ) ) {
		$notices[ $notice['key'] ] = $notice;
	}

	return $notices;
}

function add_admin_notice( $notice ) {
	admin_notices( $notice );
}

add_action( 'admin_notices', function() {

	$admin_notices = admin_notices();

	if ( empty( $admin_notices ) ) {
		return;
	}

	foreach ( $admin_notices as $notice ) {
		echo get_admin_notice_html( $notice['message'], $notice['type'] );
	}
} );

function get_admin_notice_html( $message, $type = 'info' ) {
	ob_start();

	?>
	<div class="notice notice-<?= esc_attr( $type ); ?> is-dismissible">
		<p><?php echo wp_kses_post( $message ); ?></p>
	</div>
	<?php

	return ob_get_clean();
}