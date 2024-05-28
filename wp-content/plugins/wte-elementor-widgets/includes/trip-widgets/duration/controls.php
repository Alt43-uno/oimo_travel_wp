<?php
/**
 * Trip Duration Widget Controls.
 *
 * @since 1.3.0
 * @package wptravelengine-elementor-widgets
 */

namespace WPTRAVELENGINEEB;

$selectors = array(
	// Duration.
	'duration_typography'          => '{{WRAPPER}} .elementor-widget-container .wte-title-duration .duration',
	'duration_padding'             => array(
		'{{WRAPPER}} .elementor-widget-container .wte-title-duration .duration' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
	),
	'duration_color'               => array(
		'{{WRAPPER}} .elementor-widget-container .wte-title-duration .duration' => 'color: {{VALUE}};',
	),
	'duration_bg_color'            => array(
		'{{WRAPPER}} .elementor-widget-container .wte-title-duration .duration' => 'background-color: {{VALUE}};',
	),
	'duration_border_radius'       => array(
		'{{WRAPPER}} .elementor-widget-container .wte-title-duration .duration' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	),
	'duration_boxshadow'           => '{{WRAPPER}} .elementor-widget-container .wte-title-duration .duration',
	'duration_border'              => '{{WRAPPER}} .elementor-widget-container .wte-title-duration .duration',

	// Hover duration.
	'duration_color_hover'         => array(
		'{{WRAPPER}} .elementor-widget-container .wte-title-duration .duration:hover' => 'color: {{VALUE}};',
	),
	'duration_bg_color_hover'      => array(
		'{{WRAPPER}} .elementor-widget-container .wte-title-duration .duration:hover' => 'background-color: {{VALUE}};',
	),
	'duration_border_radius_hover' => array(
		'{{WRAPPER}} .elementor-widget-container .wte-title-duration .duration:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	),
	'duration_boxshadow_hover'     => '{{WRAPPER}} .elementor-widget-container .wte-title-duration .duration:hover',
	'duration_border_hover'        => '{{WRAPPER}} .elementor-widget-container .wte-title-duration .duration:hover',

	// Days.
	'date_typography'              => '{{WRAPPER}} .elementor-widget-container .entry-header .wte-title-duration .days',
	'date_padding'                 => array(
		'{{WRAPPER}} .elementor-widget-container .entry-header .wte-title-duration .days' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
	),
	'date_title_color'             => array(
		'{{WRAPPER}} .elementor-widget-container .entry-header .wte-title-duration .days' => 'color: {{VALUE}};',
	),
	'date_hover_color'             => array(
		'{{WRAPPER}} .elementor-widget-container .entry-header .wte-title-duration .days:hover' => 'color: {{VALUE}};',
	),
	'date_bg_color'                => array(
		'{{WRAPPER}} .elementor-widget-container .entry-header .wte-title-duration .days' => 'background-color: {{VALUE}};',
	),
	'bg_hover_color'               => array(
		'{{WRAPPER}} .elementor-widget-container .entry-header .wte-title-duration:hover' => 'background-color: {{VALUE}};',
	),
	'border_radius'                => array(
		'{{WRAPPER}} .elementor-widget-container .entry-header .wte-title-duration .days' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	),
	'border_radius_hover'          => array(
		'{{WRAPPER}} .elementor-widget-container .entry-header .wte-title-duration .days:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	),
	'boxshadow'                    => '{{WRAPPER}} .elementor-widget-container .entry-header .wte-title-duration, .days',
	'boxshadow_hover'              => '{{WRAPPER}} .elementor-widget-container .entry-header .wte-title-duration .days:hover',
	'date_border'                  => '{{WRAPPER}} .elementor-widget-container .entry-header .wte-title-duration .days',
	'date_border_hover'            => '{{WRAPPER}} .elementor-widget-container .entry-header .wte-title-duration .days:hover',
);

$controls = array(
	'title'            => __( 'Trip Duration', 'wptravelengine-elementor-widgets' ),
	'icon'             => 'wtei-b-trips',
	'categories'       => 'wptravelengine',
	'display_settings' => array(
		'type'        => 'control_section',
		'label'       => __( 'Layout', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'displayStyle' => array(
				'type'    => 'SELECT',
				'label'   => __( 'Display Style', 'wptravelengine-elementor-widgets' ),
				'default' => 'vertically',
				'options' => array(
					'vertically'   => __( 'Vertical', 'wptravelengine-elementor-widgets' ),
					'horizontally' => __( 'Horizontal', 'wptravelengine-elementor-widgets' ),
				),
			),
		),
	),
	'duration_section' => array(
		'type'        => \Elementor\Controls_Manager::TAB_STYLE,
		'label'       => __( 'Duration', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'duration_typography' => array(
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'selector' => $selectors['duration_typography'],
			),
			'duration_padding'    => array(
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'label'      => __( 'Padding', 'wptravelengine-elementor-widgets' ),
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => $selectors['duration_padding'],
			),
			'duration_tabs'       => array(
				'type' => 'start_controls_tabs',
				'tabs' => array(
					'duration_normal' => array(
						'type'        => 'start_controls_tab',
						'label'       => __( 'Normal', 'wptravelengine-elementor-widgets' ),
						'subcontrols' => array(
							'duration_color'         => array(
								'type'      => \Elementor\Controls_Manager::COLOR,
								'label'     => esc_html__( 'Text Color', 'wptravelengine-elementor-widgets' ),
								'selectors' => $selectors['duration_color'],
							),
							'duration_bg_color'      => array(
								'type'      => \Elementor\Controls_Manager::COLOR,
								'label'     => esc_html__( 'Background Color', 'wptravelengine-elementor-widgets' ),
								'selectors' => $selectors['duration_bg_color'],
							),
							'duration_border_radius' => array(
								'type'       => 'DIMENSIONS',
								'label'      => esc_html__( 'Border Radius', 'wptravelengine-elementor-widgets' ),
								'size_units' => array( 'px', '%' ),
								'selectors'  => $selectors['duration_border_radius'],
							),
							'duration_boxshadow'     => array(
								'type'     => \Elementor\Group_Control_Box_Shadow::get_type(),
								'selector' => $selectors['duration_boxshadow'],
								'label'    => __( 'Box Shadow', 'wptravelengine-elementor-widgets' ),
							),
							'duration_border'        => array(
								'type'     => \Elementor\Group_Control_Border::get_type(),
								'selector' => $selectors['duration_border'],
							),
						),
					),
					'duration_hover'  => array(
						'type'        => 'start_controls_tab',
						'label'       => __( 'Hover', 'wptravelengine-elementor-widgets' ),
						'subcontrols' => array(
							'duration_color_hover'         => array(
								'type'      => \Elementor\Controls_Manager::COLOR,
								'label'     => esc_html__( 'Text Color', 'wptravelengine-elementor-widgets' ),
								'selectors' => $selectors['duration_color_hover'],
							),
							'duration_bg_color_hover'      => array(
								'type'      => \Elementor\Controls_Manager::COLOR,
								'label'     => esc_html__( 'Background Color', 'wptravelengine-elementor-widgets' ),
								'selectors' => $selectors['duration_bg_color_hover'],
							),
							'duration_border_radius_hover' => array(
								'type'       => 'DIMENSIONS',
								'label'      => __( 'Border Radius', 'wptravelengine-elementor-widgets' ),
								'size_units' => array( 'px', '%' ),
								'selectors'  => $selectors['duration_border_radius_hover'],
							),
							'duration_boxshadow_hover'     => array(
								'type'     => \Elementor\Group_Control_Box_Shadow::get_type(),
								'selector' => $selectors['duration_boxshadow_hover'],
								'label'    => __( 'Box Shadow', 'wptravelengine-elementor-widgets' ),
							),
							'duration_border_hover'        => array(
								'type'     => \Elementor\Group_Control_Border::get_type(),
								'selector' => $selectors['duration_border_hover'],
							),
						),
					),
				),
			),
		),
	),
	'date_section'     => array(
		'type'        => \Elementor\Controls_Manager::TAB_STYLE,
		'label'       => __( 'Days', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'date_typography' => array(
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'selector' => $selectors['date_typography'],
			),
			'date_padding'    => array(
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'label'      => __( 'Padding', 'wptravelengine-elementor-widgets' ),
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => $selectors['date_padding'],
			),
			'date_tabs'       => array(
				'type' => 'start_controls_tabs',
				'tabs' => array(
					'date_normal' => array(
						'type'        => 'start_controls_tab',
						'label'       => __( 'Normal', 'wptravelengine-elementor-widgets' ),
						'subcontrols' => array(
							'date_title_color' => array(
								'type'      => \Elementor\Controls_Manager::COLOR,
								'label'     => esc_html__( 'Text Color', 'wptravelengine-elementor-widgets' ),
								'selectors' => $selectors['date_title_color'],
							),
							'date_bg_color'    => array(
								'type'      => \Elementor\Controls_Manager::COLOR,
								'label'     => esc_html__( 'Background Color', 'wptravelengine-elementor-widgets' ),
								'selectors' => $selectors['date_bg_color'],
							),
							'border_radius'    => array(
								'type'       => 'DIMENSIONS',
								'label'      => esc_html__( 'Border Radius', 'wptravelengine-elementor-widgets' ),
								'size_units' => array( 'px', '%' ),
								'selectors'  => $selectors['border_radius'],
							),
							'boxshadow'        => array(
								'type'     => \Elementor\Group_Control_Box_Shadow::get_type(),
								'selector' => $selectors['boxshadow'],
								'label'    => __( 'Box Shadow', 'wptravelengine-elementor-widgets' ),
							),
							'date_border'      => array(
								'type'     => \Elementor\Group_Control_Border::get_type(),
								'selector' => $selectors['date_border'],
							),
						),
					),
					'date_hover'  => array(
						'type'        => 'start_controls_tab',
						'label'       => __( 'Hover', 'wptravelengine-elementor-widgets' ),
						'subcontrols' => array(
							'date_hover_color'    => array(
								'type'      => \Elementor\Controls_Manager::COLOR,
								'label'     => esc_html__( 'Text Color', 'wptravelengine-elementor-widgets' ),
								'selectors' => $selectors['date_hover_color'],
							),
							'bg_hover_color'      => array(
								'type'      => \Elementor\Controls_Manager::COLOR,
								'label'     => esc_html__( 'Background Color', 'wptravelengine-elementor-widgets' ),
								'selectors' => $selectors['bg_hover_color'],
							),
							'border_radius_hover' => array(
								'type'       => 'DIMENSIONS',
								'label'      => __( 'Border Radius', 'wptravelengine-elementor-widgets' ),
								'size_units' => array( 'px', '%' ),
								'selectors'  => $selectors['border_radius_hover'],
							),
							'boxshadow_hover'     => array(
								'type'     => \Elementor\Group_Control_Box_Shadow::get_type(),
								'selector' => $selectors['boxshadow_hover'],
								'label'    => __( 'Box Shadow', 'wptravelengine-elementor-widgets' ),
							),
							'date_border_hover'   => array(
								'type'     => \Elementor\Group_Control_Border::get_type(),
								'selector' => $selectors['date_border_hover'],
							),
						),
					),
				),
			),
		),
	),
);

return $controls;
