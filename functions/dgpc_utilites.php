<?php
class DGPCutilites {
    /**
     * Add margin and padding fields
     */
    private static function margin_padding(&$fields, $options, $type ) {
        $key = $type == 'margin' ? $options['key_margin'] : $options['key_padding'];
 
        $fields[$key] = array(
            'label'				=> sprintf(esc_html__('%1$s %2$s', 'et_builder'), $options['title'], $type),
            'type'				=> 'custom_margin',
            'toggle_slug'       => $options['toggle_slug'],
            'sub_toggle'		=> $options['sub_toggle'],
            'tab_slug'			=> 'advanced',
            'mobile_options'    => true,
            'hover'				=> 'tabs',
            'priority' 			=> $options['priority'],
        );
        $fields[$key . '_tablet'] = array(
            'type'            	=> 'skip',
            'tab_slug'        	=> 'advanced',
            'toggle_slug'		=> $options['toggle_slug'],
            'sub_toggle'		=> $options['sub_toggle']
        );
        $fields[$key.'_phone'] = array(
            'type'            	=> 'skip',
            'tab_slug'        	=> 'advanced',
            'toggle_slug'		=> $options['toggle_slug'],
            'sub_toggle'		=> $options['sub_toggle']
        );
        $fields[$key.'_last_edited'] = array(
            'type'            	=> 'skip',
            'tab_slug'        	=> 'advanced',
            'toggle_slug'		=> $options['toggle_slug'],
            'sub_toggle'		=> $options['sub_toggle']
        );
    }
    static function add_margin_padding( $options = array() ) {
        $margin_padding = array();
        $default = array(
            'title'         => '',
            'key_margin'    => '',
            'key_padding'   => '',
            'toggle_slug'   => '',
            'sub_toggle'    => null,
            'option'        => 'both',
            'priority'      => 30
        );
        $args = wp_parse_args( $options, $default );

        if ( !empty($args['key_margin']) ) {
            self::margin_padding($margin_padding, $args, 'margin');
        }
        if ( !empty($args['key_padding']) ) {
            self::margin_padding($margin_padding, $args, 'padding');
        }
        return $margin_padding;
    }
    /**
     * Process Margin & Padding styles
     */
    static function set_margin_padding_styles($options = array()) {
        $default = array(
            'module'            => '',
            'render_slug'       => '',
            'slug'              => '',
            'type'              => '',
            'selector'          => '',
            'hover'             => '',
            'important'         => true
        );
        $options        = wp_parse_args( $options, $default );
        extract($options);
		$desktop 		= $module->props[$slug];
		$tablet 		= $module->props[$slug.'_tablet'];
        $phone 			= $module->props[$slug.'_phone'];
        
        if (class_exists('ET_Builder_Element')) {
            if(isset($desktop) && !empty($desktop)) {
                ET_Builder_Element::set_style($render_slug, array(
                    'selector' => $options['selector'],
                    'declaration' => et_builder_get_element_style_css($desktop, 
                        $options['type'], $options['important']),
                ));
            }
            if (isset($tablet) && !empty($tablet)) {
                ET_Builder_Element::set_style($render_slug, array(
                    'selector' => $options['selector'],
                    'declaration' => et_builder_get_element_style_css($tablet, 
                        $options['type'], $options['important']),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                ));
            }
            if (isset($phone) && !empty($phone)) {
                ET_Builder_Element::set_style($render_slug, array(
                    'selector' => $options['selector'],
                    'declaration' => et_builder_get_element_style_css($phone, 
                        $options['type'], $options['important']),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                ));
            }
			if (et_builder_is_hover_enabled( $slug, $module->props ) && isset($module->props[$slug.'__hover'])) {
				$hover = $module->props[$slug.'__hover'];
				ET_Builder_Element::set_style($render_slug, array(
					'selector' => $options['hover'],
                    'declaration' => et_builder_get_element_style_css($hover, 
                        $options['type'], $options['important']),
				));
			}
        }
    }
    /**
     * Process single value
     */
    static function apply_single_value($options = array()) {

        $default = array(
            'module'            => '',
            'render_slug'       => '',
            'slug'              => '',
            'type'              => '',
            'selector'          => '',
            'unit'              => '%',
            'hover'             => '',
            'decrease'          => false,
            'addition'          => true,
            'important'         => true
        );
        $options        = wp_parse_args( $options, $default );
        extract($options);

		$desktop 	= $decrease === false ? intval($module->props[$slug]) : 100 - intval($module->props[$slug]) ;
		$tablet 	= $decrease === false ? intval($module->props[$slug.'_tablet']) : 100 - intval($module->props[$slug.'_tablet']);
		$phone 		= $decrease === false ? intval($module->props[$slug.'_phone']) : 100 - intval($module->props[$slug.'_phone']);
		$negative = $addition == false ? '-' : '';

		$desktop.= $unit;
		$tablet.= $unit;
		$phone.= $unit;

		if(isset($desktop) && !empty($desktop)) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector' => $selector,
				'declaration' => sprintf('%1$s:%3$s%2$s !important;', $type, $desktop, $negative),
			));
		}
		if (isset($tablet) && !empty($tablet)) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector' => $selector,
				'declaration' => sprintf('%1$s:%3$s%2$s !important;', $type, $tablet,$negative),
				'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
			));
		}
		if (isset($phone) && !empty($phone)) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector' => $selector,
				'declaration' => sprintf('%1$s:%3$s%2$s !important;', $type, $phone,$negative),
				'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
			));
        }
        if ( isset($module->props[$slug.'__hover']) && et_builder_is_hover_enabled( $slug, $module->props )) {
            $hover = $module->props[$slug.'__hover'];
            ET_Builder_Element::set_style($render_slug, array(
                'selector' => $options['hover'],
                'declaration' => et_builder_get_element_style_css($hover, 
                    $options['type'], $options['important']),
            ));
        }
    }
    /**
     * Process background color
     */
    static function process_color( $options = array() ) {
        $default = array(
            'module'            => '',
            'render_slug'       => '',
            'slug'              => '',
            'type'              => '',
            'selector'          => '',
            'hover'             => '',
            'important'         => true
        );
        $options        = wp_parse_args( $options, $default );
        extract($options);

		$key = $module->props[$slug];
        $important_text = true === $important ? '!important' : '';
        
		if ('' !== $key) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector' => $selector,
				'declaration' => sprintf('%2$s: %1$s %3$s;', $key, $type, $important_text),
			));
		}
		if ( et_builder_is_hover_enabled( $slug, $module->props ) && isset($module->props[$slug . '__hover']) ) {
			$slug_hover = $module->props[$slug . '__hover'];
			ET_Builder_Element::set_style($render_slug, array(
				'selector' => $hover,
				'declaration' => sprintf('%2$s: %1$s %3$s;', $slug_hover, $type, $important_text),
			));
		}
    }
    /**
	 * Custom transition to elements
	 */
	static function apply_custom_transition($module, $render_slug, $selector, $type = 'all') {
		ET_Builder_Element::set_style($render_slug, array(
			'selector' => $selector,
			'declaration' => sprintf('transition:%1$s %2$s %3$s %4$s !important;', 
				$type, 
				$module->props['hover_transition_duration'],
				$module->props['hover_transition_speed_curve'],
				$module->props['hover_transition_delay']
			),
		));
    }
}