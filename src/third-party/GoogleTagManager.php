<?php

namespace Zeek\WP_Util\ThirdParty;

class GoogleTagManager {
	/**
	 * The Google Tag Manager ID.
	 *
	 * Enable in .env.php by defining 'GOOGLE_TAG_MANAGER_ID'
	 *
	 * @var string
	 */
	private $id;

	public function __construct() {
		// Get the id from env
		if ( empty( env( 'GOOGLE_TAG_MANAGER_ID', null ) ) ) {
			return;
		}

		$this->id = env( 'GOOGLE_TAG_MANAGER_ID' );

		// If we have an ID, initialize the outputters
		$this->init_output();
	}


	public function init_output() {
		// Allow the codebase to dictate whether or not this should run
		$continue = apply_filters( __NAMESPACE__ . '\allow_google_tag_manager', true );

		if ( true !== $continue ) {
			return;
		}
		
		add_action('wp_head', [ $this, 'head_output' ], 100);
		add_action( 'wp_body_open', [ $this, 'body_output'] );
	}

	public function head_output() {
		?>
		<!-- Google Tag Manager -->
		<script>(function (w, d, s, l, i) {
				w[l] = w[l] || []
				w[l].push({
					'gtm.start':
						new Date().getTime(), event: 'gtm.js'
				})
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : ''
				j.async = true
				j.src =
					'https://www.googletagmanager.com/gtm.js?id=' + i + dl
				f.parentNode.insertBefore(j, f)
			})(window, document, 'script', 'dataLayer', '<?= esc_attr( $this->id ); ?>')</script>
		<!-- End Google Tag Manager -->
		<?php
	}

	public function body_output() {
		?>
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= esc_attr( $this->id ); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
		<?php
	}

}
