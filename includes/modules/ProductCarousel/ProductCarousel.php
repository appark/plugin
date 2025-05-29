<?php
require_once DGPC_DIR_PATH . '/functions/dgpc_utilites.php';
class ProductCarousel extends ET_Builder_Module_Type_PostBased {

	public $slug       = 'dgpc_product_carousel';
	public $vb_support = 'on';
	protected $dgpc_shop_count = 0 ;
	static $plugin_name = 'ProductCarousel';

	protected $module_credits = array(
		'module_uri' => 'https://www.divigear.com/',
		'author'     => 'DiviGear',
		'author_uri' => 'https://www.divigear.com/',
	);

	public function init() {
		$this->name = esc_html__( 'Product Carousel', 'et_builder' );
		$this->main_css_element = '%%order_class%%';
		$this->icon_path = plugin_dir_path( __FILE__ ). 'icon.svg';
	}

	public function get_settings_modal_toggles(){
		return array(
			'general'  => array(
					'toggles' => array(
							'main_content' 					=> esc_html__( 'Content', 'et_builder' ),
							'elements' 					=> esc_html__( 'Elements', 'et_builder' ),
							'carousel_settings'				=> esc_html__('Carousel Settings', 'et_builder'),
					),
			),
			'advanced'  =>  array(
					'toggles'   =>  array(
							'overlay'				=> esc_html__('Overlay', 'et_builder'),
							'title_text'			=> esc_html__('Title', 'et_builder'),
							'price_text'			=> esc_html__('Price', 'et_builder'),
							'description_text'		=> esc_html__('Description', 'et_builder'),
							'sale_settings'			=> esc_html__('Sale Badge', 'et_builder'),
							'cart_button'			=> esc_html__('Add to Cart Button', 'et_builder'),
							'review'				=> esc_html__('Review', 'et_builder'),
							'arrow_settings'		=> esc_html__('Arrow Settings', 'et_builder'),
							'dots_settings'			=> esc_html__('Dot Settings', 'et_builder'),
							'image_settings'		=> esc_html__('Image Settings', 'et_builder'),
							'content_spacing'		=> esc_html__('Content Spacing', 'et_builder'),
							'hover'					=> esc_html__('Hover', 'et_builder'),
					)
			),
			
			// Advance tab's slug is "custom_css"
			'custom_css' => array(
				'toggles' => array(
					'limitation' => esc_html__( 'Limitation', 'et_builder' ), // totally made up
				),
			),
		);
	}

	public function get_advanced_fields_config() {
		$advanced_fields = array();
		$advanced_fields['text'] = false;
		$advanced_fields['fonts'] = array(
			// Title
			'title'   => array(
				'label'         => esc_html__( 'Title', 'et_builder' ),
				'toggle_slug'   => 'title_text',
				'tab_slug'		=> 'advanced',
				'hide_text_shadow'  => true,
				'line_height' => array (
					'default' => '1em',
				),
				'font_size' => array(
					'default' => '14px',
				),
				'css'      => array(
					'main' => "%%order_class%%.dgpc_product_carousel .woocommerce ul .product .woocommerce-loop-product__title,
						%%order_class%%.dgpc_product_carousel .woocommerce ul .product .woocommerce-loop-product__title a",
					'hover' => "%%order_class%%.dgpc_product_carousel .woocommerce ul .product:hover .woocommerce-loop-product__title, 
						%%order_class%%.dgpc_product_carousel .woocommerce ul .product:hover .woocommerce-loop-product__title a",
					'important'	=> 'all'
				),
			),
			// Price
			'price'   => array(
				'label'         => esc_html__( 'Price', 'et_builder' ),
				'toggle_slug'   => 'price_text',
				'tab_slug'		=> 'advanced',
				'hide_text_shadow'  => true,
				'line_height' => array (
					'default' => '1em',
				),
				'font_size' => array(
					'default' => '16px',
				),
				'css'      => array(
					'main' => "%%order_class%%.dgpc_product_carousel .woocommerce ul .product .price del, 
								%%order_class%%.dgpc_product_carousel .woocommerce ul .product .price ins,
								%%order_class%%.dgpc_product_carousel .woocommerce ul .product .price",
					'hover' => "%%order_class%%.dgpc_product_carousel .woocommerce ul .product:hover .price del, 
								%%order_class%%.dgpc_product_carousel .woocommerce ul .product:hover .price ins,
								%%order_class%%.dgpc_product_carousel .woocommerce ul .product:hover .price",
					'important'	=> 'all'
				),
			),
			// Description
			'description'   => array(
				'label'         => esc_html__( 'Description', 'et_builder' ),
				'toggle_slug'   => 'description_text',
				'tab_slug'		=> 'advanced',
				'hide_text_shadow'  => true,
				'line_height' => array (
					'default' => '1em',
				),
				'font_size' => array(
					'default' => '14px',
				),
				'css'      => array(
					'main' => "%%order_class%%.dgpc_product_carousel .woocommerce ul .product .product-content p",
					'hover' => "%%order_class%%.dgpc_product_carousel .woocommerce ul .product:hover .product-content p",
					'important'	=> 'all'
				),
			),
			// Sale
			'sale'   => array(
				'label'         => esc_html__( 'Sale Badge', 'et_builder' ),
				'toggle_slug'   => 'sale_settings',
				'tab_slug'		=> 'advanced',
				'hide_text_shadow'  => true,
				'line_height' => array (
					'default' => '1.6em',
				),
				'font_size' => array(
					'default' => '16px',
				),
				'css'      => array(
					'main' => "%%order_class%%.dgpc_product_carousel .woocommerce ul .product .onsale",
					'hover' => "%%order_class%%.dgpc_product_carousel .woocommerce ul .product:hover .onsale",
					'important'	=> 'all'
				),
			),

			// Cart Button
			'cart'   => array(
				'label'         => esc_html__( 'Cart Button', 'et_builder' ),
				'toggle_slug'   => 'cart_button',
				'tab_slug'		=> 'advanced',
				'hide_text_shadow'  => true,
				'line_height' => array (
					'default' => '1.3em',
				),
				'font_size' => array(
					'default' => '14px',
				),
				'css'      => array(
					'main' => "%%order_class%%.dgpc_product_carousel .woocommerce ul .product .button,%%order_class%% ul li.product .et_shop_image .wc-forward",
					'hover' => "%%order_class%%.dgpc_product_carousel .woocommerce ul .product .button:hover,%%order_class%% ul li.product .et_shop_image .wc-forward:hover",
					'important' => 'all',
				),
			),



		);
		// $advanced_fields['max_width'] = false;

		$advanced_fields['borders'] = array(
			'default' => array(
				'css'      => array(
					'main' => array(
						'border_styles' => "%%order_class%% .swiper-container ul .product",
						'border_radii'	=> "%%order_class%% .swiper-container ul .product",
					),
					'important'	=> true
				),
			),
			'image'	=> array(
				'label'         => esc_html__( 'Image Border', 'et_builder' ),
				'css'             => array(
					'main' => array(
						'border_radii' => "%%order_class%% .et_shop_image",
						'border_styles' => "%%order_class%% .et_shop_image",
					),
					'important'	=> true
				),
				'label_prefix'    => esc_html__( 'Image', 'et_builder' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'image_settings',
			),
			'add_to_cart'	=> array(
				'label'         => esc_html__( 'Add-To-Cart', 'et_builder' ),
				'css'             => array(
					'main' => array(
						'border_radii' => "%%order_class%% .dgpc-container .dgpc_cart_button_container .button",
						'border_styles' => "%%order_class%% .dgpc-container .dgpc_cart_button_container .button",
					),
					'important'	=> true
				),
				// 'label_prefix'    => esc_html__( 'Image', 'et_builder' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'cart_button',
			)
		);
		$advanced_fields['background'] = array(
			'css'      => array(
				'main' => '%%order_class%%.dgpc_product_carousel .swiper-container ul .product',
				'hover'	=> '%%order_class%%.dgpc_product_carousel .swiper-container ul .product:hover',
				'important'	=> true
			),
			'use_background_color'          =>true, // default
			'use_background_color_gradient' => true, // default
			'use_background_image'          => true, // default
			'use_background_video'          => false, // default
		);
		$advanced_fields['box_shadow'] = array(
			'default'	=> array(
				'css'	=> array (
					'main'	=> '%%order_class%%.dgpc_product_carousel .swiper-container ul .product',
					'important'	=> true
				)
			)
		);
		$advanced_fields['margin_padding'] = array(
			'css'	=> array (
				'main'	=> '%%order_class%%.dgpc_product_carousel .swiper-container',
				'important'	=> 'all'
			)
		);
		$advanced_fields['filters'] = false;
		$advanced_fields['animation'] = false;

		return $advanced_fields;
	}

	public function get_custom_css_fields_config() {
		return array(
			'main_item' => array(
				'label'    => esc_html__( 'Product', 'et_builder' ),
				'selector' => '%%order_class%%.dgpc_product_carousel .swiper-container.woocommerce ul .product',
			),
			'image' => array(
				'label'    => esc_html__( 'Image', 'et_builder' ),
				'selector' => '%%order_class%%.dgpc_product_carousel .swiper-container.woocommerce ul .product .et_shop_image',
			),
			'title' => array(
				'label'    => esc_html__( 'Title', 'et_builder' ),
				'selector' => '%%order_class%%.dgpc_product_carousel .swiper-container.woocommerce ul .product .woocommerce-loop-product__title',
			),
			'price' => array(
				'label'    => esc_html__( 'Price', 'et_builder' ),
				'selector' => '%%order_class%%.dgpc_product_carousel .swiper-container.woocommerce ul .product .price',
			),
			'sale' => array(
				'label'    => esc_html__( 'Sale Badge', 'et_builder' ),
				'selector' => '%%order_class%%.dgpc_product_carousel .swiper-container.woocommerce ul .product .onsale',
			),
			'button' => array(
				'label'    => esc_html__( 'Button', 'et_builder' ),
				'selector' => '%%order_class%%.dgpc_product_carousel .swiper-container.woocommerce ul .product .button',
			),
			'arrow_prev' => array(
				'label'    => esc_html__( 'Arrow Prev', 'et_builder' ),
				'selector' => '%%order_class%%.dgpc_product_carousel .dgpc-container .swiper-button-prev',
			),
			'arrow_next' => array(
				'label'    => esc_html__( 'Arrow Next', 'et_builder' ),
				'selector' => '%%order_class%%.dgpc_product_carousel .dgpc-container .swiper-button-next',
			),
			'dots' => array(
				'label'    => esc_html__( 'Dots', 'et_builder' ),
				'selector' => '%%order_class%%.dgpc_product_carousel .dgpc-container .swiper-pagination .swiper-pagination-bullet',
			),
		);
	}

	/**
	 * Add the paged param to a product shortcode query.
	 *
	 * @param WP_Query $query
	 */
	public function add_paged_param( $query ) {
		$is_product_query = self::is_product_query( $query );

		if ( ! $is_product_query || is_archive() || is_post_type_archive() ) {
			return;
		}

		$paged = $this->get_paged_var();

		$query->is_paged                    = true;
		$query->query['paged']              = $paged;
		$query->query_vars['paged']         = $paged;

		$query->query['posts_per_page']      = (int) $this->props['posts_number'];
		$query->query_vars['posts_per_page'] = (int) $this->props['posts_number'];

		$query->query['no_found_rows']      = false;
		$query->query_vars['no_found_rows'] = false;
	}

	public function get_fields() {
		$_utl = 'DGPCutilites';
		$general = array(
			// Content
			'type' => array(
				'label'           => esc_html__( 'Type', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'options'         => array(
					'recent'  => esc_html__( 'Recent Products', 'et_builder' ),
					'featured' => esc_html__( 'Featured Products', 'et_builder' ),
					'sale' => esc_html__( 'Sale Products', 'et_builder' ),
					'best_selling' => esc_html__( 'Best Selling Products', 'et_builder' ),
					'top_rated' => esc_html__( 'Top Rated Products', 'et_builder' ),
					'product_category' => esc_html__( 'Product Category', 'et_builder' ),
				),
				'default_on_front' => 'recent',
				'affects'        => array(
					'include_categories',
				),
				'description'      => esc_html__( 'Choose which type of products you would like to display.', 'et_builder' ),
				'toggle_slug'      => 'main_content',
				'computed_affects' => array(
					'__products',
				),
			),
			'include_categories'   => array(
				'label'            => esc_html__( 'Include Categories', 'et_builder' ),
				'type'             => 'categories',
				'renderer_options' => array(
					'use_terms'    => true,
					'term_name'    => 'product_cat',
				),
				'depends_show_if'  => 'product_category',
				'description'      => esc_html__( 'Choose which categories you would like to include.', 'et_builder' ),
				'taxonomy_name'    => 'product_cat',
				'toggle_slug'      => 'main_content',
				'computed_affects' => array(
					'__products',
				),
			),
			'posts_number' => array(
				'default'           => '12',
				'label'             => esc_html__( 'Product Count', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'description'       => esc_html__( 'Define the number of products that should be displayed per page.', 'et_builder' ),
				'computed_affects'  => array(
					'__products',
				),
				'toggle_slug'       => 'main_content',
			),
			'orderby' => array(
				'label'             => esc_html__( 'Order By', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'menu_order'  => esc_html__( 'Sort By Menu order', 'et_builder' ),
					'popularity' => esc_html__( 'Sort By Popularity', 'et_builder' ),
					'rating' => esc_html__( 'Sort By Rating', 'et_builder' ),
					'date' => esc_html__( 'Sort By Date: Oldest To Newest', 'et_builder' ),
					'date-desc' => esc_html__( 'Sort By Date: Newest To Oldest', 'et_builder' ),
					'price' => esc_html__( 'Sort By Price: Low To High', 'et_builder' ),
					'price-desc' => esc_html__( 'Sort By Price: High To Low', 'et_builder' ),
					'rand' => esc_html__( 'Sort By Random', 'et_builder' )
				),
				'default_on_front' => 'date-desc',
				'default' => 'date-desc',
				'description'       => esc_html__( 'Choose how your products should be ordered.', 'et_builder' ),
				'computed_affects'  => array(
					'__products',
				),
				'toggle_slug'       => 'main_content',
			),
			'add_to_cart'	=> array(
				'label'				=> 	esc_html__('Add to cart button', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'		=>	'main_content',
				'default_on_front'			=> 'off',
				'computed_affects'  => array(
					'__products',
				),
			),
			'product_description' 	=> array(
				'label'				=> 	esc_html__('Description', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'toggle_slug'		=>	'main_content',
				'default'			=> 'off',
				'computed_affects'  => array(
					'__products',
				),
			),
			'description_full' 	=> array(
				'label'				=> 	esc_html__('Show Full Description', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'toggle_slug'		=>	'main_content',
				'default'			=> 'off',
				'show_if'			=> array(
					'product_description' => 'on'
				),
				'computed_affects'  => array(
					'__products',
				),
			),
			// Carousel Settings
			'show_items_desktop'	=> array(
				'label'				=> 	esc_html__('Show item Desktop', 'et_builder'),
				'type'				=>	'select',
				'options'           => array(
					'4' => esc_html__( 'default', 'et_builder' ),
					'6' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '6' ) ),
					'5' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '5' ) ),
					'4' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '4' ) ),
					'3' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '3' ) ),
					'2' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '2' ) ),
					'1' => esc_html__( '1 Column', 'et_builder' ),
				),
				'default'  => '4',
				'toggle_slug'		=> 'carousel_settings'
			),
			'show_items_tablet'	=> array(
				'label'				=> 	esc_html__('Show item Tablet', 'et_builder'),
				'type'				=>	'select',
				'options'           => array(
					'4' => esc_html__( 'default', 'et_builder' ),
					'6' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '6' ) ),
					'5' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '5' ) ),
					'4' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '4' ) ),
					'3' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '3' ) ),
					'2' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '2' ) ),
					'1' => esc_html__( '1 Column', 'et_builder' ),
				),
				'default'  => '3',
				'toggle_slug'		=> 'carousel_settings'
			),
			'show_items_mobile'	=> array(
				'label'				=> 	esc_html__('Show item Mobile', 'et_builder'),
				'type'				=>	'select',
				'options'           => array(
					'4' => esc_html__( 'default', 'et_builder' ),
					'6' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '6' ) ),
					'5' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '5' ) ),
					'4' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '4' ) ),
					'3' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '3' ) ),
					'2' => sprintf( esc_html__( '%1$s Columns', 'et_builder' ), esc_html( '2' ) ),
					'1' => esc_html__( '1 Column', 'et_builder' ),
				),
				'default'  => '1',
				'toggle_slug'		=> 'carousel_settings'
			),
			'multislide'	=> array(
				'label'				=> 	esc_html__('Multislide', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'		=>	'carousel_settings',
				'default'			=> 'off'
			),
			'item_spacing'     => array(
                'label'             => esc_html('Item Spacing', 'et_builder'),
                'type'              => 'range',
                'toggle_slug'       => 'carousel_settings',
                'range_settings '   => array(
                    'min'       => '5',
                    'max'       => '50',
                    'step'      => '1',
                ),
                'default'  => '30',
			),
			'transition_duration'	=> array(
				'label'				=> 	esc_html__('Transition Duration', 'et_builder'),
				'type'              => 'text',
				'toggle_slug'		=>	'carousel_settings',
                'default'  => '500',
			),
			'centermode'	=> array(
				'label'				=> 	esc_html__('Center Slide', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'		=>	'carousel_settings',
				'default'			=> 'off'
			),
			'loop'	=> array(
				'label'				=> 	esc_html__('Loop', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'		=>	'carousel_settings',
				'default'			=> 'off'
			),
			'autoplay'	=> array(
				'label'				=> 	esc_html__('AutoPlay', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'		=>	'carousel_settings',
				'default'			=> 'off'
			),
			'hoverpause'	=> array(
				'label'				=> 	esc_html__('Pause on hover', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'		=>	'carousel_settings',
				'default'			=> 'off',
				'show_if'         => array(
					'autoplay' => 'on',
				),
			),
			'autoplay_speed'		=> array(
				'label'				=> 	esc_html__('Auto Play Speed', 'et_builder'),
				'type'				=>	'text',
				'default'			=>	'1500',
				'toggle_slug'		=> 'carousel_settings',
				'show_if'         => array(
					'autoplay' => 'on',
				),
			),
			'arrow_nav'	=> array(
				'label'				=> 	esc_html__('Arrow Navigtion', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'		=>	'carousel_settings',
				'default'			=> 'off'
			),
			'dot_nav'	=> array(
				'label'				=> 	esc_html__('Dot Navigtion', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'		=>	'carousel_settings',
				'default'			=> 'off'
			),
			'dot_alignment'	=> array(
				'label'				=> 	esc_html__('Dots Alignment', 'et_builder'),
				'type'				=>	'text_align',
				'options'         	=> et_builder_get_text_orientation_options( array( 'justified' ) ),
				'toggle_slug'		=>	'carousel_settings',
				'default'			=> 'center',
				'default'	=> 'center',
				'show_if'         => array(
					'dot_nav' => 'on',
				),
			),
			'equal_height'	=> array(
				'label'				=> 	esc_html__('Equal Height Product', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'		=>	'carousel_settings',
				'default'			=> 'off'
			),
			'item_vertical_align'	=> array(
				'label'				=> 	esc_html__('Vertical Align', 'et_builder'),
				'type'				=>	'select',
				'options'         => array(
					'flex-start' 	=> esc_html__( 'Top', 'et_builder' ),
					'center'  		=> esc_html__( 'Center', 'et_builder' ),
					'flex-end'  	=> esc_html__( 'Bottom', 'et_builder' ),
				),
				'toggle_slug'		=>	'carousel_settings',
				'show_if'      => array(
					'equal_height' => 'off',
				),
			),
			'effect' => array(
				'label'             => esc_html__( 'Slide Effect', 'et_builder' ),
				'type'              => 'select',
				'options'           => array(
					'slide'  => esc_html__( 'Slide', 'et_builder' ),
					'coverflow' => esc_html__( 'Coverflow', 'et_builder' ),
				),
				'default' => 'slide',
				'toggle_slug'       => 'carousel_settings',
			),
			'coverflow_rotate'     => array(
                'label'             => esc_html('Rotate', 'et_builder'),
                'type'              => 'range',
                'toggle_slug'       => 'carousel_settings',
                'range_settings '   => array(
                    'min'       => '0',
                    'max'       => '100',
                    'step'      => '1',
                ),
				'default'          => '50',
				'show_if'         => array(
					'effect' => 'coverflow',
				),
			),
			'slide_shadow'	=> array(
				'label'				=> 	esc_html__('Slide Shadow', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'		=>	'carousel_settings',
				'default'			=> 'on',
				'show_if'         => array(
					'effect' => 'coverflow',
				),
			),
			// Advanced Settings
			'overlay'				=> array(
				'label'				=> 	esc_html__('Overlay', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'		=>	'overlay',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'off'
			),
			'ovarlay_color'		=> array(
				'label'             => esc_html('Overlay Color', 'et_builder'),
				'type'				=>	'color-alpha',
				'toggle_slug'		=>	'overlay',
				'default'			=> 'rgba(45, 45, 45, 0.7)',
				'tab_slug'          => 'advanced',
			),
			'sale_badge_backgeound'		=> array(
				'label'             => esc_html('Sale Badge Background', 'et_builder'),
				'type'				=>	'color-alpha',
				'toggle_slug'		=>	'sale_settings',
				'default'			=> '#0c71c3',
				'tab_slug'          => 'advanced',
			),
			// cart button position
			'cart_button_position'	=> array(
				'label'				=> 	esc_html__('Add-to-cart Position', 'et_builder'),
				'type'				=>	'select',
				'options'         => array(
					'over_image' => esc_html__( 'Over Image', 'et_builder' ),
					'bottom'  => esc_html__( 'Bottom', 'et_builder' ),
				),
				'toggle_slug'		=>	'cart_button',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'over_image'
			),
			'cart_button_alignment'	=> array(
				'label'				=> 	esc_html__('Add-to-cart Alignment', 'et_builder'),
				'type'				=> 'text_align',
				'options'         	=> et_builder_get_text_orientation_options( array( 'justified' ) ),
				'toggle_slug'		=> 'cart_button',
				'tab_slug'			=> 'advanced',
				'default'			=> 'left',
				'show_if'         => array(
					'cart_button_position' => 'bottom',
				),
			),
			'add_to_cart_on_bottom'	=> array(
				'label'				=> 	esc_html__('Add to Cart Align Bottom', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'toggle_slug'		=>	'cart_button',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'off',
				'show_if'  => array(
					'equal_height' => 'on',
					'cart_button_position' => 'bottom',
				),
			),
			'cart_button_full'	=> array(
				'label'				=> 	esc_html__('Add-to-cart Full width', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'		=>	'cart_button',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'off',
				'show_if'         => array(
					'cart_button_position' => 'bottom',
				),
			),
			// cart button position background
			'cart_background'		=> array(
				'label'             => esc_html('Cart Button Background', 'et_builder'),
				'type'				=>	'color-alpha',
				'toggle_slug'		=>	'cart_button',
				'default'			=> '#444444',
				'tab_slug'          => 'advanced',
			),
			'cart_background_hover'		=> array(
				'label'             => esc_html('Cart Button Background Hover', 'et_builder'),
				'type'				=>	'color-alpha',
				'toggle_slug'		=>	'cart_button',
				'default'			=> '#333',
				'tab_slug'          => 'advanced',
			),
			'review_color'		=> array(
				'label'             => esc_html('Review Color', 'et_builder'),
				'type'				=>	'color-alpha',
				'toggle_slug'		=>	'review',
				'default'			=> '#333',
				'tab_slug'          => 'advanced',
			),
			'review_bg_color'		=> array(
				'label'             => esc_html('Review Background Color', 'et_builder'),
				'type'				=>	'color-alpha',
				'toggle_slug'		=>	'review',
				'default'			=> '#fff',
				'tab_slug'          => 'advanced',
			),

			// Arrow settings
			'use_prev_icon'	=> array(
				'label'				=> 	esc_html__('Use previous custom icon', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'		=>	'arrow_settings',
				'default'			=> 'off',
			),
			'prev_icon' => array(
				'label'               => esc_html__( 'Select previous icon', 'et_builder' ),
				'type'                => 'et_font_icon_select',
				'renderer'            => 'select_icon',
				'renderer_with_field' => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'arrow_settings',
				'show_if'         => array(
					'use_prev_icon' => 'on',
				),
			),
			'use_next_icon'	=> array(
				'label'				=> 	esc_html__('Use next custom icon', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'		=>	'arrow_settings',
				'default'			=> 'off',
			),
			'next_icon' => array(
				'label'               => esc_html__( 'Select next icon', 'et_builder' ),
				'type'                => 'et_font_icon_select',
				'renderer'            => 'select_icon',
				'renderer_with_field' => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'arrow_settings',
				'show_if'         => array(
					'use_next_icon' => 'on',
				),
			),
			'nav_font_size' => array(
				'label'           => esc_html__( 'Font Size', 'et_builder' ),
				'type'            => 'range',
				'mobile_options'    => true,
                'responsive'        => true,
                'default'           => '53px',
                'default_unit'      => 'px',
				'range_settings '   => array(
                    'min'       => '0',
                    'max'       => '100',
                    'step'      => '1',
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'arrow_settings',
			),

			'arrow_color'		=> array(
				'label'             => esc_html('Arrow Color', 'et_builder'),
				'type'				=>	'color-alpha',
				'toggle_slug'		=>	'arrow_settings',
				'default'			=> '#0C71C3',
				'tab_slug'          => 'advanced',
			),
			'arrow_background'		=> array(
				'label'             => esc_html('Arrow Background', 'et_builder'),
				'type'				=>	'color-alpha',
				'toggle_slug'		=>	'arrow_settings',
				'default'			=> '#ffffff',
				'tab_slug'          => 'advanced',
			),
			'arrow_circle'		=> array(
				'label'				=> 	esc_html__('Arrow Circle', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'		=>	'arrow_settings',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'off'
			),
			'arrow_on_hover'		=> array(
				'label'				=> 	esc_html__('Arrow on hover', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'		=>	'arrow_settings',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'off'
			),
			'arrow_inside'		=> array(
				'label'				=> 	esc_html__('Arrow position inside', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'		=>	'arrow_settings',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'off'
			),
			'dots_color'		=> array(
				'label'             => esc_html('Dot Color', 'et_builder'),
				'type'				=>	'color-alpha',
				'toggle_slug'		=>	'dots_settings',
				'default'			=> '#c7c7c7',
				'tab_slug'          => 'advanced',
			),
			'dots_active_color'		=> array(
				'label'             => esc_html('Active Dot Color', 'et_builder'),
				'type'				=>	'color-alpha',
				'toggle_slug'		=>	'dots_settings',
				'default'			=> '#007aff',
				'tab_slug'          => 'advanced',
			),
			'dot_circle'		=> array(
				'label'				=> 	esc_html__('Dot Circle', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'toggle_slug'		=>	'dots_settings',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'on'
			),
			'image_hover_scale' 	=> array(
				'label'				=> 	esc_html__('Hover Scale', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'toggle_slug'		=>	'image_settings',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'on'
			),
			'title_on_top' 			=> array(
				'label'				=> 	esc_html__('Title on top', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'toggle_slug'		=>	'title_text',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'off',
				'computed_affects'  => array(
					'__products',
				),
			),
			'hide_the_title' 		=> array(
				'label'				=> 	esc_html__('Hide the title', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'toggle_slug'		=>	'title_text',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'off'
			),
			'price_on_top' 			=> array(
				'label'				=> 	esc_html__('Price on top', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'toggle_slug'		=>	'price_text',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'off',
				'computed_affects'  => array(
					'__products',
				),
			),
			'hide_the_price' 		=> array(
				'label'				=> 	esc_html__('Hide the price', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'toggle_slug'		=>	'price_text',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'off'
			),
			
			// hover effects
			'background_hover_set' 	=> array(
				'label'				=> 	esc_html__('Hover Background', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'toggle_slug'		=>	'hover',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'off'
			),
			'background_hover'		=> array(
				'label'             => esc_html('Backround Color', 'et_builder'),
				'type'				=>	'color-alpha',
				'toggle_slug'		=>	'hover',
				'default'			=> '#f2f2f2',
				'tab_slug'          => 'advanced',
				'show_if'			=> array (
					'background_hover_set' => 'on'
				)
			),
			'title_hover' 	=> array(
				'label'				=> 	esc_html__('Title Hover', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'toggle_slug'		=>	'hover',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'off'
			),
			'title_hover_color'		=> array(
				'label'             => esc_html('Title Color', 'et_builder'),
				'type'				=>	'color-alpha',
				'toggle_slug'		=>	'hover',
				'default'			=> '#333',
				'tab_slug'          => 'advanced',
				'show_if'			=> array (
					'title_hover' => 'on'
				)
			),
			'price_hover' 	=> array(
				'label'				=> 	esc_html__('Price Hover', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'toggle_slug'		=>	'hover',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'off'
			),
			'price_hover_color'		=> array(
				'label'             => esc_html('Price Color', 'et_builder'),
				'type'				=>	'color-alpha',
				'toggle_slug'		=>	'hover',
				'default'			=> '#333',
				'tab_slug'          => 'advanced',
				'show_if'			=> array (
					'price_hover' => 'on'
				)
			),
			'description_hover' 	=> array(
				'label'				=> 	esc_html__('Description Hover', 'et_builder'),
				'type'				=>	'yes_no_button',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'toggle_slug'		=>	'hover',
				'tab_slug'			=> 	'advanced',
				'default'			=> 'off'
			),
			'description_hover_color'		=> array(
				'label'             => esc_html('Description Color', 'et_builder'),
				'type'				=>	'color-alpha',
				'toggle_slug'		=>	'hover',
				'default'			=> '#333',
				'tab_slug'          => 'advanced',
				'show_if'			=> array (
					'description_hover' => 'on'
				)
			)
			
		);
		$content_spacing = $_utl::add_margin_padding(array(
			'title'			=> esc_html ('Content', 'et_builder'),
			'key_margin'    => 'content_margin',
			'key_padding'   => 'content_spacing',
			'toggle_slug'   => 'margin_padding',
		));
		$content_spacing_top = $_utl::add_margin_padding(array(
			'title'			=> esc_html ('Content Top', 'et_builder'),
			'key_margin'    => 'content_margin_top',
			'key_padding'   => 'content_spacing_top',
			'toggle_slug'   => 'margin_padding',
		));
		$button_spacing = $_utl::add_margin_padding(array(
			'title'			=> esc_html ('Add-To-Cart', 'et_builder'),
			'key_margin'    => 'add_to_cart_margin',
			'key_padding'   => 'add_to_cart_padding',
			'toggle_slug'   => 'margin_padding',
		));
		return array_merge(
			$general, 
			$content_spacing, 
			$content_spacing_top, 
			$button_spacing
		);
	}


	public function dgpc_get_products($args = array(), $conditional_tags = array(), $current_page = array() ) {
		foreach ( $args as $arg => $value ) {
			$this->props[ $arg ] = $value;
		}

		$type                 	= $this->props['type'] != '' ? $this->props['type'] : 'recent';
		$include_category_ids 	= explode ( ",", $this->props['include_categories'] );
		$posts_number         	= $this->props['posts_number'];
		$orderby              	= $this->props['orderby'] != '' ? $this->props['orderby'] : 'date-desc';
		$add_to_cart 			= $this->props[ 'add_to_cart' ];
		$title_on_top 			= $this->props[ 'title_on_top' ];
		$price_on_top 			= $this->props[ 'price_on_top' ];
		$product_description 	= $this->props[ 'product_description' ];
		$description_full 		= $this->props[ 'description_full' ];
		$description_full 		= $this->props[ 'description_full' ];

		$product_orderby = 'date-desc';

		$order = array(
			'date' => 'date',
			'date-desc' => 'date-desc',
			'menu_order' => 'menu_order',
			'popularity' => 'popularity',
			'rating' => 'rating',
			'rand' => 'rand',
			'price' => 'price',
			'price-desc' => 'price-desc'
		);

		// product order
		if (array_key_exists($orderby, $order)) {
			$product_orderby = $orderby;
		}

		// custom views like on_sale, best_selling, top_rated, visibility
		$wc_custom_view = '';
		$wc_custom_views = array(
			'sale'         => array( 'on_sale', 'true' ),
			'best_selling' => array( 'best_selling', 'true' ),
			'top_rated'    => array( 'top_rated', 'true' ),
			'featured'     => array( 'visibility', 'featured' ),
		);

		if(array_key_exists($type, $wc_custom_views)) {
			$custom_view_data = $wc_custom_views[ $type ];
			$wc_custom_view   = sprintf( '%1$s="%2$s"', esc_attr( $custom_view_data[0] ), esc_attr( $custom_view_data[1] ) );
		}

		$options = array (
			'add_to_cart' 			=> $add_to_cart,
			'title_on_top'			=> $title_on_top,
			'price_on_top'			=> $price_on_top,
			'product_description'	=> $product_description,
			'description_full'		=> $description_full,
			'hide_the_title'		=> $this->props['hide_the_title'],
			'hide_the_price'		=> $this->props['hide_the_price'],
			'cart_button_position'	=> $this->props['cart_button_position'],
			'background_pattern'    => isset($this->props['background_enable_pattern_style']) ? $this->props['background_enable_pattern_style'] : 'off',
			'background_mask'    	=> isset($this->props['background_enable_mask_style']) ? $this->props['background_enable_mask_style'] : 'off'
		);

		// add product categories
		$product_categories = array();
		$all_shop_categories = et_builder_get_shop_categories();
		if ( is_array( $all_shop_categories ) && ! empty( $all_shop_categories ) ) {
			foreach ( $all_shop_categories as $category ) {
				if ( is_object( $category ) && is_a($category, 'WP_Term') ) {
					if ( in_array( $category->term_id, $include_category_ids ) ) {
						$product_categories[] = $category->slug;
					}
				}
			}
		}

		do_action( 'et_pb_shop_before_print_shop' );

		// https://github.com/woocommerce/woocommerce/issues/17769
		$post = $GLOBALS['post'];

		do_action( 'dgpc_shop_before_print' , $options , false );

		if ($type) {
			$shop = do_shortcode(
				sprintf( '[products per_page="%1$s" class="swiper-container" orderby="%2$s" columns="4" %3$s %4$s ]',
					esc_attr( $posts_number ),
					esc_attr( $product_orderby ),
					$wc_custom_view,
					$type == 'product_category' ? sprintf( 'category="%s"', esc_attr( implode( ',', $product_categories ) ) ) : ''
				)
			);
		} else {
			$shop = "<h2>No Results Found</h2>";
		}

		do_action( 'dgpc_shop_after_print' , $options );


		// https://github.com/woocommerce/woocommerce/issues/17769
		$GLOBALS['post'] = $post;

		do_action( 'et_pb_shop_after_print_shop' );

		if ( '<div class="woocommerce columns-0"></div>' === $shop ) {
			$shop = self::get_no_results_template();
		}

		return $shop;
	}

	/**
	 * Whether or not the provided query is for products.
	 *
	 * @param WP_Query $query
	 *
	 * @return bool
	 */
	public static function is_product_query( $query ) {
		if ( ! isset( $query->query['post_type'] ) || ! empty( $query->query['p'] ) ) {
			return false;
		}

		if ( isset( $query->query['composite_component'] ) ) {
			return false;
		}

		$post_type = $query->query['post_type'];

		if ( 'product' === $post_type ) {
			return true;
		}

		if ( is_array( $post_type ) && in_array( 'product', $post_type ) ) {
			return true;
		}

		return false;
	}

	// Additional Css
	public function additional_css_styles($render_slug){
		$_utl = 'DGPCutilites';
		$dot_alignment 				= 	$this->props['dot_alignment'];
		$order_class 				= 	self::get_module_order_class( $render_slug );
		$content_spacing			=	array_diff(explode("|", $this->props['content_spacing']), ['true', 'false']);
		$content_spacing_top		=	array_diff(explode("|", $this->props['content_spacing_top']), ['true', 'false']);

		if( $dot_alignment !== '' ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dgpc_product_carousel .dgpc-container .swiper-pagination',
				'declaration' => sprintf(
					'text-align:%1$s !important;', $dot_alignment),
			) );
		}	
		if( '' !== $this->props['sale_badge_backgeound'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% ul .product .onsale',
				'declaration' => sprintf(
					'background-color: %1$s!important;', $this->props['sale_badge_backgeound']),
			) );
		} 
		if( '' !== $this->props['cart_background'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% ul .product .button,%%order_class%% ul li.product .et_shop_image .wc-forward',
				'declaration' => sprintf(
					'background-color: %1$s!important;', $this->props['cart_background']),
			) );
		} 
		if( '' !== $this->props['cart_background_hover'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% ul .product .button:hover,%%order_class%% ul li.product .et_shop_image .wc-forward:hover',
				'declaration' => sprintf(
					'background-color: %1$s!important;', $this->props['cart_background_hover']),
			) );
		} 
		if( '' !== $this->props['arrow_color'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .swiper-button-next:before,%%order_class%% .swiper-button-prev:before',
				'declaration' => sprintf(
					'color: %1$s!important;', $this->props['arrow_color']),
			) );
		} 
		if( '' !== $this->props['arrow_background'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .swiper-button-next,%%order_class%% .swiper-button-prev',
				'declaration' => sprintf(
					'background-color: %1$s!important;', $this->props['arrow_background']),
			) );
		} 
		if( 'off' !== $this->props['arrow_circle'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .swiper-button-next, %%order_class%% .swiper-button-prev',
				'declaration' => 'border-radius: 50% !important;',
			) );
		} 
		if( '' !== $this->props['dots_color'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .swiper-pagination-bullet',
				'declaration' => sprintf(
					'background-color: %1$s!important;', $this->props['dots_color']),
			) );
		} 
		if( '' !== $this->props['dots_active_color'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .swiper-pagination-bullet.swiper-pagination-bullet-active',
				'declaration' => sprintf(
					'background: %1$s!important;', $this->props['dots_active_color']),
			) );
		} 
		if( 'on' !== $this->props['dot_circle'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .swiper-pagination .swiper-pagination-bullet',
				'declaration' => 'border-radius: 0!important;',
			) );
		} 

		if( 'on' == $this->props['overlay'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dgpc_product_carousel .dgpc-container.dg-overlay .product-link:before',
				'declaration' => sprintf('background-color: %1$s!important;', $this->props['ovarlay_color'] ),
			) );
		} 
		if( 'off' !== $this->props['image_hover_scale'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dgpc_product_carousel .dgpc-container .product:hover .product-link img',
				'declaration' => sprintf('transform: scale(1.05)!important;' ),
			) );
		} 

		if( '' !== $this->props['review_color'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% ul .product .dgpc-rating-container .star-rating span::before',
				'declaration' => sprintf('color: %1$s!important;', $this->props['review_color'] ),
			) );
		} 
		if( '' !== $this->props['review_bg_color'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% ul .product .dgpc-rating-container',
				'declaration' => sprintf('background-color: %1$s!important;', $this->props['review_bg_color'] ),
			) );
		} 
		if( 'on' !== $this->props['equal_height'] && '' !== $this->props['item_vertical_align']) {
            ET_Builder_Element::set_style( $render_slug, array(
                'selector'    => '%%order_class%% .woocommerce ul .product',
                'declaration' => sprintf('align-self:%1$s;', $this->props['item_vertical_align']),
            ) );
		}
		
		// hover effect
		if( 'on' == $this->props['background_hover_set'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dgpc_product_carousel .swiper-container .product:hover',
				'declaration' => sprintf('background: %1$s!important;', $this->props['background_hover'] ),
			) );
		} 
		if( 'on' == $this->props['title_hover'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dgpc_product_carousel .swiper-container ul .product:hover .woocommerce-loop-product__title',
				'declaration' => sprintf('color: %1$s!important;', $this->props['title_hover_color'] ),
			) );
		} 
		if( 'on' == $this->props['price_hover'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dgpc_product_carousel .swiper-container ul .product:hover .price,
				%%order_class%%.dgpc_product_carousel .swiper-container ul .product:hover .price del,
				%%order_class%%.dgpc_product_carousel .swiper-container ul .product:hover .price ins',
				'declaration' => sprintf('color: %1$s!important;', $this->props['price_hover_color'] ),
			) );
		} 
		if( 'on' == $this->props['description_hover'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dgpc_product_carousel .swiper-container ul .product:hover p',
				'declaration' => sprintf('color: %1$s!important;', $this->props['description_hover_color'] ),
			) );
		} 

		// arrow font size
		if(isset($this->props['nav_font_size']) && '' !== $this->props['nav_font_size']) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector' => '%%order_class%% .dgpc-container .swiper-button-next, %%order_class%% .dgpc-container .swiper-button-prev',
				'declaration' => sprintf('font-size: %1$s!important; width:%1$s; height:%1$s;', 
				$this->props['nav_font_size']),
			));
		}
		if(isset($this->props['nav_font_size_tablet']) && '' !== $this->props['nav_font_size_tablet']) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector' => '%%order_class%% .dgpc-container .swiper-button-next, %%order_class%% .dgpc-container .swiper-button-prev',
				'declaration' => sprintf('font-size: %1$s!important; width:%1$s; height:%1$s;', 
				$this->props['nav_font_size_tablet']),
				'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
			));
		}
		if(isset($this->props['nav_font_size_phone']) && '' !== $this->props['nav_font_size_phone']) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector' => '%%order_class%% .dgpc-container .swiper-button-next, %%order_class%% .dgpc-container .swiper-button-prev',
				'declaration' => sprintf('font-size: %1$s!important; width:%1$s; height:%1$s;', 
				$this->props['nav_font_size_phone']),
				'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
			));
		}
		// cart button alignment
		if(isset($this->props['cart_button_alignment']) && '' !== $this->props['cart_button_alignment']) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector' => "%%order_class%% .dgpc-container .dgpc_cart_button_container",
				'declaration' => sprintf('text-align: %1$s!important; ', 
				$this->props['cart_button_alignment'])
			));
		}
		// cart button full width
		if(isset($this->props['cart_button_full']) && 'on' === $this->props['cart_button_full']) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector' => "%%order_class%% .dgpc-container .dgpc_cart_button_container .button",
				'declaration' => 'display:block;'
			));
		}
		// cart button at the boottom position
		if(isset($this->props['add_to_cart_on_bottom']) && 'on' === $this->props['add_to_cart_on_bottom']) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector' => "%%order_class%% .dgpc-container .dgpc_cart_button_container",
				'declaration' => 'margin-top:auto; margin-bottom:0;'
			));
		}
		
		// spacing content
		$_utl::set_margin_padding_styles(array(
			'module'		=> $this,
			'render_slug'   => $render_slug,
			'slug'          => 'content_margin',
			'type'          => 'margin',
			'selector'      => '%%order_class%% .woocommerce ul .product .product-content',
            'hover'         => '%%order_class%% .woocommerce ul .product:hover .product-content',
		));
		$_utl::set_margin_padding_styles(array(
			'module'		=> $this,
			'render_slug'   => $render_slug,
			'slug'          => 'content_spacing',
			'type'          => 'padding',
			'selector'      => '%%order_class%% .woocommerce ul .product .product-content',
            'hover'         => '%%order_class%% .woocommerce ul .product:hover .product-content',
		));
		// spacing content top
		$_utl::set_margin_padding_styles(array(
			'module'		=> $this,
			'render_slug'   => $render_slug,
			'slug'          => 'content_margin_top',
			'type'          => 'margin',
			'selector'      => '%%order_class%% .woocommerce ul .product .product-content-top',
            'hover'         => '%%order_class%% .woocommerce ul .product:hover .product-content-top',
		));
		$_utl::set_margin_padding_styles(array(
			'module'		=> $this,
			'render_slug'   => $render_slug,
			'slug'          => 'content_spacing_top',
			'type'          => 'padding',
			'selector'      => '%%order_class%% .woocommerce ul .product .product-content-top',
            'hover'         => '%%order_class%% .woocommerce ul .product:hover .product-content-top',
		));
		// spacing button
		$_utl::set_margin_padding_styles(array(
			'module'		=> $this,
			'render_slug'   => $render_slug,
			'slug'          => 'add_to_cart_margin',
			'type'          => 'margin',
			'selector'      => '%%order_class%% .woocommerce ul .product .dgpc_cart_button_container .button',
            'hover'         => '%%order_class%% .woocommerce ul .product:hover .dgpc_cart_button_container .button',
		));
		$_utl::set_margin_padding_styles(array(
			'module'		=> $this,
			'render_slug'   => $render_slug,
			'slug'          => 'add_to_cart_padding',
			'type'          => 'padding',
			'selector'      => '%%order_class%% .woocommerce ul .product .dgpc_cart_button_container .button',
            'hover'         => '%%order_class%% .woocommerce ul .product:hover .dgpc_cart_button_container .button',
		));
		
	}

	public function render( $attrs, $content, $render_slug ) {

		$type                    	= $this->props['type'];
		$include_categories      	= $this->props['include_categories'];
		$posts_number            	= $this->props['posts_number'];
		$orderby                 	= $this->props['orderby'];
		$overlay                 	= $this->props['overlay'];
		$hide_the_title             = $this->props['hide_the_title'];
		$hide_the_price             = $this->props['hide_the_price'];
		$overlay_class              = '';
		$order_class 				= self::get_module_order_class( $render_slug );
		$order_number				= str_replace('_','',str_replace($this->slug,'', $order_class));
		$hide_classes				= '';
		$this->additional_css_styles($render_slug);
		self::add_hover_to_selectors('%%order_class%% .product');

		// add_action( 'wp_ajax_request_data', array( $this, 'request_data' ) ); 

		if( $hide_the_title == 'on') {
			$hide_classes .= ' hide_title';
		}
		if( $hide_the_price == 'on') {
			$hide_classes .= ' hide_price';
		}
		if( $this->props['arrow_on_hover'] == "on" ) {
			$arrow_on_hover = ' arrow_on_hover';
		} else {
			$arrow_on_hover = ' arrow_display';
		}
		if( $this->props['arrow_inside'] == "on" ) {
			$arrow_inside = ' arrow_inside';
		} else {
			$arrow_inside = '';
		}
		if ($overlay === 'on' ) {
			$overlay_class = ' dg-overlay';
		}

		$equal_height_class = 'on' == $this->props['equal_height'] ? ' product_equal_height': '';

		// Carousel settings
		$option = sprintf('data-slidesPerView=%1$s data-tablet=%2$s data-mobile=%3$s data-spaceBetween=%4$s data-transition=%5$s
							data-center=%6$s data-loop=%7$s data-autoplay=%8$s data-hoverpause=%9$s data-autospeed=%10$s data-arrow=%11$s
							data-dots=%12$s data-effect=%13$s data-coverflow=%14$s data-multislide=%15$s data-order=%16$s data-shadow=%17$s', 
			$this->props['show_items_desktop'],
			$this->props['show_items_tablet'],
			$this->props['show_items_mobile'],
			$this->props['item_spacing'],
			$this->props['transition_duration'],
			$this->props['centermode'],
			$this->props['loop'],
			$this->props['autoplay'],
			$this->props['hoverpause'],
			$this->props['autoplay_speed'],
			$this->props['arrow_nav'],
			$this->props['dot_nav'],
			$this->props['effect'],
			$this->props['coverflow_rotate'],
			$this->props['multislide'],
			$order_number,
			$this->props['slide_shadow']
		); 
		$data_prev_icon = 'on' === $this->props['use_prev_icon'] ? 
		sprintf( 'data-icon="%1$s"', esc_attr( et_pb_process_font_icon($this->props['prev_icon']) ) ) : 'data-icon="4"';
		$data_next_icon = 'on' === $this->props['use_next_icon'] ? 
		sprintf( 'data-icon="%1$s"', esc_attr( et_pb_process_font_icon($this->props['next_icon']) ) ) : 'data-icon="5"';

		$navigation		= ($this->props['arrow_nav'] == 'on') ? 
		sprintf('<div class="swiper-button-next sbn%1$s" %3$s>
		</div><div class="swiper-button-prev sbp%1$s" %2$s></div>', 
		$order_number,
		$data_prev_icon,
		$data_next_icon) : '' ;

		$pagination		= ($this->props['dot_nav'] == 'on') ? sprintf('<div class="swiper-pagination sp%1$s"></div>', $order_number) : '' ;
		// Render the module
		$output = sprintf( '<div class="dgpc-container%5$s%6$s%7$s%8$s%9$s" %2$s >%1$s %3$s %4$s</div>', 
			$this->dgpc_get_products(), 
			$option, 
			$navigation, 
			$pagination,
			$arrow_on_hover,
			$arrow_inside,
			$overlay_class,
			$equal_height_class,
			$hide_classes
		);
		return $output;
	}

	/**
	 * Products shortcode query args.
	 *
	 * @param array  $query_args
	 *
	 * @return array
	 */
	public function shortcode_products_query_cb( $query_args ) {
		$query_args['paged'] = $this->get_paged_var();

		$products   = new WP_Query( $query_args );

		// save the number of pages to global var so it can be used to render pagination
		$GLOBALS['et_pb_shop_pages'] = $products->max_num_pages;

		return $query_args;
	}

	/**
	 * Modifying WooCommerce' product query filter based on $orderby value given
	 * @see WC_Query->get_catalog_ordering_args()
	 */
	function modify_woocommerce_shortcode_products_query( $args, $atts ) {

		if ( function_exists( 'WC' ) ) {
			// Default to ascending order
			$orderby = $this->props['orderby'];
			$order   = 'ASC';

			// Switch to descending order if orderby is 'price-desc' or 'date-desc'
			if ( in_array( $orderby, array( 'price-desc', 'date-desc' ) ) ) {
				$order = 'DESC';
			}

			// Supported orderby arguments (as defined by WC_Query->get_catalog_ordering_args() ): rand | date | price | popularity | rating | title
			$orderby = in_array( $orderby, array( 'price-desc', 'date-desc' ) ) ? str_replace( '-desc', '', $orderby ) : $orderby;

			// Get arguments for the given non-native orderby
			$query_args = WC()->query->get_catalog_ordering_args( $orderby, $order );

			// Confirm that returned argument isn't empty then merge returned argument with default argument
			if ( is_array( $query_args ) && ! empty( $query_args ) ) {
				$args = array_merge( $args, $query_args );
			}

		}

		return $args;
	}

	static function modify_woocommerce_shortcode_products_query_vb( $args, $atts ) {

		if ( function_exists( 'WC' ) ) {
			// Default to ascending order
			$orderby = $args['orderby'];
			$order   = 'ASC';

			// Switch to descending order if orderby is 'price-desc' or 'date-desc'
			if ( in_array( $orderby, array( 'price-desc', 'date-desc' ) ) ) {
				$order = 'DESC';
			}

			// Supported orderby arguments (as defined by WC_Query->get_catalog_ordering_args() ): rand | date | price | popularity | rating | title
			$orderby = in_array( $orderby, array( 'price-desc', 'date-desc' ) ) ? str_replace( '-desc', '', $orderby ) : $orderby;

			// Get arguments for the given non-native orderby
			$query_args = WC()->query->get_catalog_ordering_args( $orderby, $order );

			// Confirm that returned argument isn't empty then merge returned argument with default argument
			if ( is_array( $query_args ) && ! empty( $query_args ) ) {
				$args = array_merge( $args, $query_args );
			}

		}

		return $args;
	}

	/**
     * render pattern or mask markup
     * 
     */
    public function dgpc_render_pattern_or_mask_html( $props, $type ) {
        $html = array(
            'pattern' => '<span class="et_pb_background_pattern"></span>',
            'mask' => '<span class="et_pb_background_mask"></span>'
        );
        return $props == 'on' ? $html[$type] : '';
    }
}

new ProductCarousel;
