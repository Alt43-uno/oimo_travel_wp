<?php
/**
 * Highlights Widget Controls.
 *
 * @since 1.3.0
 * @package wptravelengine-elementor-widgets
 */

namespace WPTRAVELENGINEEB;

global $post;

$trip_settings = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );

$highlights           = array();
$demo_trip_highlights = array(
	'0' => array(
		'highlight_text' => __( 'Spectacular views of Everest, Lhotse, Nuptse, and other towering peaks', 'wptravelengine-elementor-widgets' ),
	),
	'1' => array(
		'highlight_text' => __( 'Exploring vibrant Sherpa culture and monasteries in Namche Bazaar', 'wptravelengine-elementor-widgets' ),
	),
	'2' => array(
		'highlight_text' => __( 'Passing through the stunning Sagarmatha National Park', 'wptravelengine-elementor-widgets' ),
	),
	'3' => array(
		'highlight_text' => __( 'Reaching Everest Base Camp at an altitude of about 5,364 meters (17,598 feet)', 'wptravelengine-elementor-widgets' ),
	),
	'4' => array(
		'highlight_text' => __( 'Visiting the vantage point of Kala Patthar for sunrise views', 'wptravelengine-elementor-widgets' ),
	),
	'5' => array(
		'highlight_text' => __( 'Immersing yourself in the warmth of local Sherpa hospitality', 'wptravelengine-elementor-widgets' ),
	),
);

$trip_highlights = isset( $trip_settings['trip_highlights'] ) ? $trip_settings['trip_highlights'] : $demo_trip_highlights;
if ( ! empty( $trip_highlights ) && isset( $trip_highlights ) ) {
	foreach ( $trip_highlights as $key => $highlight_content ) {
		$highlights[] = '<li>' . esc_html( $highlight_content['highlight_text'] ) . '</li>';
	}
	$highlights = implode( '', $highlights );
}

$selectors = array(
	// General Section.
	'general_typography'       => '{{WRAPPER}} .elementor-widget-container .highlights-content',
	'general_padding'          => array(
		'{{WRAPPER}} .elementor-widget-container .highlights-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
	),
	'general_margin'           => array(
		'{{WRAPPER}} .elementor-widget-container .highlights-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
	),
	'general_color'            => array(
		'{{WRAPPER}} .elementor-widget-container .highlights-content' => 'color: {{VALUE}};',
	),
	'general_boxshadow'        => '{{WRAPPER}} .elementor-widget-container .highlights-content',
	'general_border'           => '{{WRAPPER}} .elementor-widget-container .highlights-content',
	'general_background_color' => array(
		'{{WRAPPER}} .elementor-widget-container .highlights-content' => 'background-color: {{VALUE}};',
	),
	'general_border_radius'    => array(
		'{{WRAPPER}} .elementor-widget-container .highlights-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	),
	'text_alignment'           => array(
		'{{WRAPPER}} .elementor-widget-container .highlights-content' => 'text-align: {{VALUE}};',
	),
	'text_shadow'              => array(
		'{{WRAPPER}} .elementor-widget-container .highlights-content' => 'text-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};',
	),
);

$controls = array(
	'categories'      => 'wptravelengine',
	'controls'        => array(
		'type'        => 'control_section',
		'label'       => __( 'Highlights', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'highlights' => array(
				'type'    => \Elementor\Controls_Manager::WYSIWYG,
				'default' => $highlights,
			),
		),
	),
	'general_section' => array(
		'type'        => \Elementor\Controls_Manager::TAB_STYLE,
		'label'       => __( 'General', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'general_typography'       => array(
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'selector' => $selectors['general_typography'],
				'label'    => __( 'Typography', 'wptravelengine-elementor-widgets' ),
			),
			'general_color'            => array(
				'type'      => \Elementor\Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'wptravelengine-elementor-widgets' ),
				'selectors' => $selectors['general_color'],
			),
			'text_alignment'           => array(
				'label'     => esc_html__( 'Alignment', 'wptravelengine-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'wptravelengine-elementor-widgets' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'wptravelengine-elementor-widgets' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'wptravelengine-elementor-widgets' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'left',
				'toggle'    => true,
				'selectors' => $selectors['text_alignment'],
			),
			'text_shadow'              => array(
				'type'      => \Elementor\Group_Control_Text_Shadow::get_type(),
				'label'     => __( 'Text Shadow', 'wptravelengine-elementor-widgets' ),
				'selectors' => $selectors['text_shadow'],
			),
			'general_padding'          => array(
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'label'      => __( 'Padding', 'wptravelengine-elementor-widgets' ),
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => $selectors['general_padding'],
			),
			'general_margin'           => array(
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'label'      => __( 'Margin', 'wptravelengine-elementor-widgets' ),
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => $selectors['general_margin'],
			),
			'general_background_color' => array(
				'type'      => \Elementor\Controls_Manager::COLOR,
				'label'     => __( 'Background Color', 'wptravelengine-elementor-widgets' ),
				'selectors' => $selectors['general_background_color'],
			),
			'general_boxshadow'        => array(
				'type'     => \Elementor\Group_Control_Box_Shadow::get_type(),
				'selector' => $selectors['general_boxshadow'],
				'label'    => __( 'Box Shadow', 'wptravelengine-elementor-widgets' ),
			),
			'general_border'           => array(
				'type'     => \Elementor\Group_Control_Border::get_type(),
				'selector' => $selectors['general_border'],
			),
			'general_border_radius'    => array(
				'type'       => 'DIMENSIONS',
				'label'      => __( 'Border Radius', 'wptravelengine-elementor-widgets' ),
				'size_units' => array( 'px', '%' ),
				'selectors'  => $selectors['general_border_radius'],
			),
		),
	),
);

return $controls;
