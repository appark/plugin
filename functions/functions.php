<?php

// VB AJAX request
add_action( 'wp_ajax_request_data', 'request_data' );
function request_data() {
    $data = json_decode(file_get_contents('php://input'), true);
    $args = $data['props'];

    $options = array (
        'add_to_cart' 			=> $args['add_to_cart'],
        'title_on_top'			=> $args['title_on_top'],
        'price_on_top'			=> $args['price_on_top'],
        'product_description'	=> $args['product_description'],
        'description_full'		=> $args['description_full'],
        'hide_the_title'		=> $args['hide_the_title'],
        'hide_the_price'		=> $args['hide_the_price'],
        'cart_button_position'	=> $args['cart_button_position'],
        'background_pattern'    => isset($args['background_enable_pattern_style']) ? $args['background_enable_pattern_style'] : 'off',
		'background_mask'    	=> isset($args['background_enable_mask_style']) ? $args['background_enable_mask_style'] : 'off'
    );

    $type = $args['type'] != '' ? $args['type'] : 'recent';
    $orderby = $args['orderby'] != '' ? $args['orderby'] : 'date-desc';

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

    // products categories
    $include_category_ids = isset($args['include_categories']) ? explode ( ",", $args['include_categories'] ) : array();
    $product_categories = array();
    $all_shop_categories = dg_get_shop_categories();
    if ( is_array( $all_shop_categories ) && ! empty( $all_shop_categories ) ) {
        foreach ( $all_shop_categories as $category ) {
            if ( is_object( $category ) && is_a($category, 'WP_Term') ) {
                if ( in_array( $category->term_id, $include_category_ids ) ) {
                    $product_categories[] = $category->slug;
                }
            }
        }
    }    

    
    do_action( 'dgpc_shop_before_print', $options , true);

    if ($args ['type']) {
        $shop = do_shortcode(
            sprintf( '[products per_page="%1$s" class="swiper-container" orderby="%2$s" columns="4" order="ASC" %3$s %4$s ]',
                esc_attr( $args [ 'posts_number'] ),
                esc_attr( $product_orderby ),
                $wc_custom_view,
                $args ['type'] == 'product_category' ? sprintf( 'category="%s"', esc_attr( implode( ',', $product_categories ) ) ) : ''
            )
        );
        // $shop = do_shortcode(
        //     sprintf( '[%1$s per_page="%2$s" class="swiper-container" orderby="%3$s" columns="4" category="%4$s" ]',
        //         esc_html( $woocommerce_shortcodes_types [$args['type']]),
        //         esc_attr( $args [ 'posts_number'] ),
        //         esc_attr( $args [ 'orderby'] ),
        //         esc_attr( implode ( ",", $product_categories ) )
        //     )
        // );
    } else {
        $shop = "<h2>No Results Found</h2>";
    }
    
    do_action( 'dgpc_shop_after_print', $options );

    // Response with HTML markup
    wp_send_json_success($shop);
} 

// dg get product categories
function dg_get_shop_categories( $args = array() ) {
	$defaults = apply_filters( 'et_builder_include_categories_shop_defaults', array (
		'use_terms' => true,
		'term_name' => 'product_cat',
	) );

	$term_args = apply_filters( 'et_builder_include_categories_shop_args', array( 'hide_empty' => false, ) );
	$args = wp_parse_args( $args, $defaults );
	$product_categories = $args['use_terms'] ? get_terms( $args['term_name'], $term_args ) : get_categories( apply_filters( 'et_builder_get_categories_shop_args', 'hide_empty=0' ) );

	return $product_categories;
}


// shop before print
add_action('dgpc_shop_before_print', 'dgpc_shop_before_print_fn', 10, 5 );
function dgpc_shop_before_print_fn( $options , $builder = false )  {
    // disabe product hook
    remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 10 ); // changed version 1.0.13 ( 5 - 10 )
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
    remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

    // remove duplicate image and add-to-cart button
    if ( function_exists('et_divi_builder_template_loop_product_thumbnail')) {
        remove_action( 'woocommerce_before_shop_loop_item_title', 'et_divi_builder_template_loop_product_thumbnail', 10);
    }
    
    // add new wrapper container
    if ($options['title_on_top'] == 'on' || $options['price_on_top'] == 'on' ) {
        add_action( 'woocommerce_before_shop_loop_item', 'dgpc_top_content_container_open', 10 ); 
        add_action( 'woocommerce_before_shop_loop_item_title', 'dgpc_top_content_container_close', 5 );
    }
    // add add_to_cart Button over image
    if ($options['add_to_cart'] == 'on' && $options['cart_button_position'] != 'bottom') {
       //add_action( 'woocommerce_before_shop_loop_item_title', 'woocomerce_image_container_with_button', 10 , 2 );
       $callback = 'woocomerce_image_container_with_button';
        add_action(
            'woocommerce_before_shop_loop_item_title',
            function() use ( $callback , $options , $builder ) {
                $callback($options , $builder);           
            },
            10
        );
    } else {
        //add_action( 'woocommerce_before_shop_loop_item_title', 'woocomerce_image_container', 10 , 2);
        $callback = 'woocomerce_image_container';
        add_action(
            'woocommerce_before_shop_loop_item_title',
            function() use ( $callback , $options , $builder  ) {
                $callback($options , $builder );           
            },
            10
        );
    }
    
    // title
    if ($options['title_on_top'] == 'on'  ) {
        add_action( 'woocommerce_before_shop_loop_item', 'dgpc_template_loop_product_title', 10 );
    } else {
        add_action( 'woocommerce_shop_loop_item_title', 'dgpc_template_loop_product_title', 10 );
    }
    // price
    if($options['price_on_top'] == 'on' ) {
        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
        add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_price', 10 );
    } 

    // woocommerce short description
    if ($options['product_description'] == 'on') {
        if($options['description_full'] == 'on') {
            add_action( 'woocommerce_after_shop_loop_item_title', 'dgpc_product_shopt_description_full', 10 );
        } else {
            add_action( 'woocommerce_after_shop_loop_item_title', 'dgpc_product_shopt_description', 10 );
        }  
    }
    // add add_to_cart Button bottom
    if ($options['add_to_cart'] == 'on' && $options['cart_button_position'] == 'bottom') {
        add_action( 'woocommerce_after_shop_loop_item_title', 'dgpc_add_to_cart_btn_bottom', 10 );
    } 
    // open and close product-content
    if ($options['title_on_top'] !== 'on' || $options['price_on_top'] !== 'on' || $options['product_description'] == 'on') {
        add_action( 'woocommerce_shop_loop_item_title', 'dgpc_content_container_open', 9 );
        add_action( 'woocommerce_after_shop_loop_item', 'dgpc_content_container_close', 5 );
    }

}
// shop after print
add_action('dgpc_shop_after_print', 'dgpc_shop_after_print_fn', 10, 5 );
function dgpc_shop_after_print_fn( $options ) {

    // remove wrapper div action
    if ($options['title_on_top'] == 'on' || $options['price_on_top'] == 'on' ) {
        remove_action( 'woocommerce_before_shop_loop_item', 'dgpc_top_content_container_open', 10 ); // changed version 1.0.13 ( 9 - 10 )
        remove_action( 'woocommerce_before_shop_loop_item_title', 'dgpc_top_content_container_close', 5 );
    }
    // add add_to_cart Button over image
    if ($options['add_to_cart'] == 'on'  && $options['cart_button_position'] != 'bottom') {
        remove_all_actions('woocommerce_before_shop_loop_item_title');
       // remove_action( 'woocommerce_before_shop_loop_item_title', 'woocomerce_image_container_with_button', 10 );
       
    } else {
        remove_all_actions('woocommerce_before_shop_loop_item_title');
        //remove_action( 'woocommerce_before_shop_loop_item_title', 'woocomerce_image_container', 10 );

    }
    // title
    if ($options['title_on_top'] == 'on' ) {
        remove_action( 'woocommerce_before_shop_loop_item', 'dgpc_template_loop_product_title',10 );
    } else {
        remove_action( 'woocommerce_shop_loop_item_title', 'dgpc_template_loop_product_title', 10 );
    }
    // price
    if($options['price_on_top'] == 'on'  ) {
        remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_price', 10 );
        add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
    }
    // Remove woocommerce short description
    if ($options['product_description'] == 'on') {
        if($options['description_full'] == 'on') {
            remove_action( 'woocommerce_after_shop_loop_item_title', 'dgpc_product_shopt_description_full', 10 );
        } else {
            remove_action( 'woocommerce_after_shop_loop_item_title', 'dgpc_product_shopt_description', 10 );
        }
    }
    // remover hook add_to_cart Button Bottom
    if ($options['add_to_cart'] == 'on' && $options['cart_button_position'] == 'bottom') {
        remove_action( 'woocommerce_after_shop_loop_item_title', 'dgpc_add_to_cart_btn_bottom', 10 );
    } 
    // open and close product-content
    if ($options['title_on_top'] !== 'on' || $options['price_on_top'] !== 'on' || $options['product_description'] == 'on') {
        add_action( 'woocommerce_shop_loop_item_title', 'dgpc_content_container_open', 9 );
        add_action( 'woocommerce_after_shop_loop_item', 'dgpc_content_container_close', 5 );
    }

    // enable product link
    add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 ); 
    add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 10 ); // changed version 1.0.13 ( 5 - 10 )
    add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
    add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
    add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );
    add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

}
// Product short description
function dgpc_product_shopt_description() {
    global $product;
    $data = substr(get_the_excerpt(), 0, 80);
    echo '<p>'.$data . '...</p>';
}
// Product short description
function dgpc_product_shopt_description_full() {
    global $product;
    echo '<p>'.get_the_excerpt(). '</p>';
}
// open product top content
function dgpc_top_content_container_open() {
    echo '<div class="product-content-top content-wrapper">';
}
// close product top content
function dgpc_top_content_container_close() {
    echo '</div>';
}
// open product-content 
function dgpc_content_container_open() {
    echo '<div class="product-content content-wrapper">';
}
// close product-content 
function dgpc_content_container_close() {
    echo "</div>";
}

// title with link
function dgpc_template_loop_product_title() {
    global $product;
    $link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
    $output = sprintf( '<h2 class="woocommerce-loop-product__title"><a href="%2$s">%1$s</a></h2>',get_the_title(),$link );
    echo $output;
}

// image container without button
function woocomerce_image_container($options = array() , $builder = false) {
    global $post, $product;

    // rating
    ob_start();
    echo '<div class="dgpc-rating-container">';
    woocommerce_template_loop_rating();
    echo '</div>';
    $rating = ob_get_clean();
    $rating_content = '';

    if('<div class="dgpc-rating-container"></div>' === $rating) {
        $has_rating = '';
    } else {
        $has_rating = ' has-rating';
        $rating_content = $rating;
    }

    $onsale = '';
    if ($product->is_on_sale()) {
        $onsale = apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woocommerce' ) . '</span>', $post, $product );
    }

    $link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
    $output = sprintf( '%6$s%7$s<span class="et_shop_image"><a href="%2$s" class="product-link">%3$s %1$s</a><div class="product-item-footer%5$s">%4$s</div></span>',
        woocommerce_get_product_thumbnail(), 
        $link,
        $onsale,
        $rating_content,
        $has_rating,
        ($builder === true) ? '<span class="et_pb_background_pattern"></span>' : ( isset($options['background_pattern']) && $options['background_pattern'] === 'on' ?  '<span class="et_pb_background_pattern"></span>': '' ),
        ($builder === true) ? '<span class="et_pb_background_mask"></span>' : ( isset($options['background_mask']) && $options['background_mask'] === 'on' ?  '<span class="et_pb_background_mask"></span>': '' )
        
    );
    echo $output;
}
// image container with buuton
function woocomerce_image_container_with_button($options = array() , $builder = false) {
    global $post, $product;

    // rating
    ob_start();
    echo '<div class="dgpc-rating-container">';
    woocommerce_template_loop_rating();
    echo '</div>';
    $rating = ob_get_clean();
    $rating_content = '';

    if('<div class="dgpc-rating-container"></div>' === $rating) {
        $has_rating = '';
    } else {
        $has_rating = ' has-rating';
        $rating_content = $rating;
    }

    $onsale = '';
    if ($product->is_on_sale()) {
        $onsale = apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woocommerce' ) . '</span>', $post, $product );
    }

    ob_start();
    woocommerce_template_loop_add_to_cart();
    $add_to_cart_button = ob_get_clean();

    $link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
    
    $output = sprintf( '%7$s%8$s<span class="et_shop_image"><a href="%3$s"  class="product-link">%4$s %1$s</a> <div class="product-item-footer has-cart-button%6$s">%2$s %5$s</div></span>',
        woocommerce_get_product_thumbnail(), 
        $add_to_cart_button,
        $link,
        $onsale,
        $rating_content,
        $has_rating,
       ($builder === true) ? '<span class="et_pb_background_pattern"></span>' : ( isset($options['background_pattern']) && $options['background_pattern'] === 'on' ?  '<span class="et_pb_background_pattern"></span>': '' ),
       ($builder === true) ? '<span class="et_pb_background_mask"></span>' : ( isset($options['background_mask']) && $options['background_mask'] === 'on' ?  '<span class="et_pb_background_mask"></span>': '' )
        
    );
    echo $output;
}

// add to cart button at bottom
function dgpc_add_to_cart_btn_bottom() {
    global $post, $product;
    ob_start();
    echo '<div class="dgpc_cart_button_container">';
    woocommerce_template_loop_add_to_cart();
    echo "</div>";
    $add_to_cart_button = ob_get_clean();
    echo $add_to_cart_button;
}

// Modify woocommerce query
function modify_woocommerce_shortcode_products_query_vb( $args, $atts ) {

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
