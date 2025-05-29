<?php

class DGPC_DgProductCarousel extends DiviExtension {

	/**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $gettext_domain = 'dgpc-dg-product-carousel';

	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $name = 'dg-product-carousel';

	/**
	 * The extension's version
	 *
	 * @since 1.0.26
	 *
	 * @var string
	 */
	public $version = '2.0.1';

	/**
	 * DGPC_DgProductCarousel constructor.
	 *
	 * @param string $name
	 * @param array  $args
	 */
	public function __construct( $name = 'dg-product-carousel', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );
		
		parent::__construct( $name, $args );

	}
}

new DGPC_DgProductCarousel;
