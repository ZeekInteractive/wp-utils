<?php

namespace Zeek\WP_Util;

function safe_mail( $to, $subject, $message, $headers = '', $attachments = [] ) {

	// Bypass safe mail checking if the ENABLE_LIVE_MAIL var is set
	if ( true === env( 'ENABLE_LIVE_MAIL', false ) ) {
		return wp_mail( $to, $subject, $message, $headers, $attachments );
	}

	// Check to see if we have an approved safe email address to send to (@zeek.com or client's email)
	$safe_tlds = get_safe_email_tlds();

	$email_parts = explode( '@', $to );

	// Looks like an invalid email address
	if ( count( $email_parts ) !== 2 ) {
		return false;
	}

	// Couldn't find the TLD in the safe list, let's quietly bypass sending an actual mail and pretend like it worked
	if ( false === array_search( $email_parts[1], $safe_tlds ) ) {
		return true;
	}

	// Found a safe TLD, let's go ahead and let it send
	return wp_mail( $to, $subject, $message, $headers, $attachments );
}

function get_safe_email_tlds() {
	$safe_tlds = [
		'zeek.com',
	];

	return apply_filters( 'zeek/safe_mail_tlds', $safe_tlds );
}
