<?php
/**
 * Ratings Widget Controls.
 *
 * @since 1.3.0
 * @package wptravelengine-elementor-widgets
 */

$selectors = array(
	'hide_stars'      => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .content .aggregate-rating .stars .rating-star .wpte-trip-review-stars .stars-group-wrapper' => 'display: {{VALUE}};',
	),
	'hide_text'       => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .content .aggregate-rating .stars .review-based-on-wrap' => 'display: {{VALUE}};',
	),
	'star_gap'        => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .content .aggregate-rating .stars .rating-star .wpte-trip-review-stars .stars-group-wrapper svg:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
	),

	// Style - General.
	'color'           => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .content .aggregate-rating .stars' => 'color: {{VALUE}};',
	),

	// Style - Icon/ Star Rating.
	'margin'          => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .content .aggregate-rating .stars .rating-star .wpte-trip-review-stars' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
	),

	// Style Section.
	'text_typography' => '{{WRAPPER}} .elementor-widget-container .post-data .content .aggregate-rating .stars',
);

$controls = array(
	'rating'          => array(
		'type'        => 'control_section',
		'label'       => __( 'Rating Controls', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'star_gap'   => array(
				'label'      => __( 'Star Gap', 'wptravelengine-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 5,
				),
				'selectors'  => $selectors['star_gap'],
			),
			'hide_stars' => array(
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label'        => __( 'Hide Stars', 'wptravelengine-elementor-widgets' ),
				'default'      => 'no',
				'return_value' => 'none',
				'selectors'    => $selectors['hide_stars'],
			),
			'hide_text'  => array(
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label'        => __( 'Hide Text', 'wptravelengine-elementor-widgets' ),
				'default'      => 'no',
				'return_value' => 'none',
				'selectors'    => $selectors['hide_text'],
			),
		),
	),
	'general_section' => array(
		'type'        => \Elementor\Controls_manager::TAB_STYLE,
		'label'       => __( 'General', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'text_typography' => array(
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'label'    => __( 'Typography', 'wptravelengine-elementor-widgets' ),
				'selector' => $selectors['text_typography'],
			),
			'color'           => array(
				'type'      => \Elementor\Controls_Manager::COLOR,
				'label'     => __( 'Color', 'wptravelengine-elementor-widgets' ),
				'selectors' => $selectors['color'],
			),
		),
	),
	'icon_section'    => array(
		'type'        => \Elementor\Controls_Manager::TAB_STYLE,
		'label'       => __( 'Icon', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'rated_color'   => array(
				'label'     => __( 'Primary Color', 'wptravelengine-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f7b500',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-container .post-data .content .aggregate-rating .stars .rating-star .wpte-trip-review-stars .stars-group-wrapper .stars-rated-group svg path' => 'fill: {{VALUE}};',
				),
			),
			'unrated_color' => array(
				'label'     => __( 'Seconday Color', 'wptravelengine-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#e4e4e4',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-container .post-data .content .aggregate-rating .stars .rating-star .wpte-trip-review-stars .stars-group-wrapper .stars-placeholder-group svg path' => 'fill: {{VALUE}};',
				),
			),
			'icon_size'     => array(
				'type'      => 'SLIDER',
				'label'     => esc_html__( 'Size', 'wptravelengine-elementor-widgets' ),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-container .post-data .content .aggregate-rating .stars .rating-star .wpte-trip-review-stars' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
			'margin'        => array(
				'label'      => esc_html__( 'Margin', 'wptravelengine-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => $selectors['margin'],
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => true,
				),
			),
		),
	),
);

return $controls;
