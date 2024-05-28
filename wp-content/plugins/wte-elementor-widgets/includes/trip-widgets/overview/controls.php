<?php
/**
 * Overview Widget Controls.
 *
 * @since 1.3.0
 * @package wptravelengine-elementor-widgets
 */

namespace WPTRAVELENGINEEB;

global $post;

$trip_settings = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
$key           = '1_wpeditor';
$overview      = isset( $trip_settings['tab_content'][ $key ] ) ? $trip_settings['tab_content'][ $key ] : '';
if ( empty( $overview ) ) {
	$overview = __( 'Embark on a breathtaking journey to the iconic Everest Base Camp, a trek that offers stunning vistas, cultural experiences, and a chance to be up close with the world\'s highest peak. This adventure takes you through picturesque Himalayan landscapes, traditional Sherpa villages, and concludes at the base of Mount Everest itself.', 'wptravelengine-elementor-widgets' );
}

$selectors = array(
	// general section.
	'general_typography'       => '{{WRAPPER}} .elementor-widget-container .overview-content',
	'general_padding'          => array(
		'{{WRAPPER}} .elementor-widget-container .overview-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
	),
	'general_margin'           => array(
		'{{WRAPPER}} .elementor-widget-container .overview-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
	),
	'general_color'            => array(
		'{{WRAPPER}} .elementor-widget-container .overview-content' => 'color: {{VALUE}};',
	),
	'general_boxshadow'        => '{{WRAPPER}} .elementor-widget-container .overview-content',
	'general_border'           => '{{WRAPPER}} .elementor-widget-container .overview-content',
	'general_background_color' => array(
		'{{WRAPPER}} .elementor-widget-container .overview-content' => 'background-color: {{VALUE}};',
	),
	'general_border_radius'    => array(
		'{{WRAPPER}} .elementor-widget-container .overview-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	),
	'text_alignment'           => array(
		'{{WRAPPER}} .elementor-widget-container .overview-content' => 'text-align: {{VALUE}};',
	),
	'text_shadow'              => array(
		'{{WRAPPER}} .elementor-widget-container .overview-content' => 'text-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};',
	),
);

$controls = array(
	'categories'      => 'wptravelengine',
	'controls'        => array(
		'type'        => 'control_section',
		'label'       => __( 'Overview', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'overview' => array(
				'type'    => \Elementor\Controls_Manager::WYSIWYG,
				'default' => $overview,
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
				'label'     => esc_html__( 'Text color', 'wptravelengine-elementor-widgets' ),
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
