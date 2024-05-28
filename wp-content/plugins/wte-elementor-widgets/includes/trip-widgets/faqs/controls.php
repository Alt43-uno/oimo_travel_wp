<?php
/**
 * Trip-faqs Widget Controls.
 *
 * @since 1.3.0
 * @package wptravelengine-elementor-widgets
 */

namespace WPTRAVELENGINEEB;

$selectors = array(
	// General section.
	'expand_all_typography'   => '{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-header .expand-all-button label',
	'expand_all_color'        => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-header .expand-all-button label' => 'color: {{VALUE}};',
	),
	'generals_padding'        => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
	),
	'generals_margin'         => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
	),
	'generals_gap'            => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-content .faq-row:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
	),

	// Title.
	'title_typography'        => '{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-header .wpte-faqs-title',
	'title_padding'           => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-header .wpte-faqs-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	),
	'title_margin'            => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-header .wpte-faqs-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
	),
	'title_color'             => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-header .wpte-faqs-title' => 'color: {{VALUE}};',
	),
	'title_text_shadow'       => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-header .wpte-faqs-title' => 'text-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};',
	),
	'title_text_stroke'       => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-header .wpte-faqs-title',
	),
	'title_alignment'         => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-header .wpte-faqs-title' => 'text-align: {{VALUE}};',
	),

	// Faqs title.
	'faqs_typography'         => '{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-content .faq-row .accordion-tabs-toggle .faq-title',
	'faqs_padding'            => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-content .faq-row .accordion-tabs-toggle .faq-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	),
	'faqs_margin'             => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-content .faq-row .accordion-tabs-toggle .faq-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
	),
	'faqs_color'              => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-content .faq-row .accordion-tabs-toggle .faq-title' => 'color: {{VALUE}};',
	),
	'faqs_background_color'   => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-content .faq-row .accordion-tabs-toggle .faq-title' => 'background-color: {{VALUE}};',
	),

	// Faqs content.
	'faqs_content_typography' => '{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-content .faq-row .faq-content',
	'faqs_content_padding'    => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-content .faq-row .faq-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	),
	'faqs_content_margin'     => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-content .faq-row .faq-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
	),
	'faqs_content_color'      => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-content .faq-row .faq-content' => 'color: {{VALUE}};',
	),

	// Toogle.
	'toogle_background_color' => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .expand-all-button input[type=checkbox]:checked' => 'background-color: {{VALUE}};',
	),

	// Icon.
	'icon_color'              => array(
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-content .faq-row .accordion-tabs-toggle .rotator:before,
		{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-content .faq-row .accordion-tabs-toggle .rotator:after' => 'background-color: {{VALUE}};',
		'{{WRAPPER}} .elementor-widget-container .post-data .wp-travel-engine-faq-tab-content .faq-row .accordion-tabs-toggle .rotator' => 'border-color: {{VALUE}};',
	),
);

$controls = array(
	'title'                => __( 'Trip - FAQs', 'wptravelengine-elementor-widgets' ),
	'control_section'      => array(
		'type'        => 'control_section',
		'label'       => __( 'Title', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'show_title'        => array(
				'label'   => __( 'Show Title', 'wptravelengine-elementor-widgets' ),
				'type'    => 'SWITCHER',
				'default' => 'yes',
			),
			'html_tag'          => array(
				'type'    => 'SELECT',
				'label'   => __( 'HTML Tag', 'wptravelengine-elementor-widgets' ),
				'default' => 'h2',
				'options' => array(
					'h1' => __( 'H1', 'wptravelengine-elementor-widgets' ),
					'h2' => __( 'H2', 'wptravelengine-elementor-widgets' ),
					'h3' => __( 'H3', 'wptravelengine-elementor-widgets' ),
					'h4' => __( 'H4', 'wptravelengine-elementor-widgets' ),
					'h5' => __( 'H5', 'wptravelengine-elementor-widgets' ),
					'h6' => __( 'H6', 'wptravelengine-elementor-widgets' ),
				),
			),
			'title_text_stroke' => array(
				'type'      => \Elementor\Group_Control_Text_Stroke::get_type(),
				'label'     => __( 'Text Stroke', 'wptravelengine-elementor-widgets' ),
				'selectors' => $selectors['title_text_stroke'],
			),
			'title_text_shadow' => array(
				'type'      => \Elementor\Group_Control_Text_Shadow::get_type(),
				'label'     => __( 'Text Shadow', 'wptravelengine-elementor-widgets' ),
				'selectors' => $selectors['title_text_shadow'],
			),
		),
	),
	'expand_all_settings'  => array(
		'type'        => 'control_section',
		'label'       => __( 'Expand All', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'show_expand_all'  => array(
				'label'     => __( 'Show Expand All', 'wptravelengine-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'wptravelengine-elementor-widgets' ),
				'label_off' => __( 'Hide', 'wptravelengine-elementor-widgets' ),
				'default'   => 'yes',
			),
			'expand_all_label' => array(
				'label'   => __( 'Expand All Label', 'wptravelengine-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Expand All', 'wptravelengine-elementor-widgets' ),
			),
		),
	),
	'general_section'      => array(
		'type'        => \Elementor\Controls_Manager::TAB_STYLE,
		'label'       => __( 'General', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'expand_all_typography' => array(
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'selector' => $selectors['expand_all_typography'],
				'label'    => __( 'Typography', 'wptravelengine-elementor-widgets' ),
			),
			'expand_all_color'      => array(
				'type'      => \Elementor\Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'wptravelengine-elementor-widgets' ),
				'selectors' => $selectors['expand_all_color'],
			),
			'generals_padding'      => array(
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'label'      => __( 'Padding', 'wptravelengine-elementor-widgets' ),
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => $selectors['generals_padding'],
			),
			'generals_margin'       => array(
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'label'      => __( 'Margin', 'wptravelengine-elementor-widgets' ),
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => $selectors['generals_margin'],
			),
			'generals_gap'          => array(
				'label'      => __( 'Gap', 'wptravelengine-elementor-widgets' ),
				'type'       => 'SLIDER',
				'size_units' => array( 'px', '%' ),
				'selectors'  => $selectors['generals_gap'],
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
					'size' => 50,
				),
			),
		),
	),
	'title_section'        => array(
		'type'        => \Elementor\Controls_Manager::TAB_STYLE,
		'label'       => __( 'Title', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'title_typography' => array(
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'selector' => $selectors['title_typography'],
				'label'    => __( 'Typography', 'wptravelengine-elementor-widgets' ),
			),
			'title_color'      => array(
				'type'      => \Elementor\Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'wptravelengine-elementor-widgets' ),
				'selectors' => $selectors['title_color'],
			),
			'title_padding'    => array(
				'label'      => esc_html__( 'Padding', 'wptravelengine-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => $selectors['title_padding'],
			),
			'title_margin'     => array(
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'label'      => __( 'Margin', 'wptravelengine-elementor-widgets' ),
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => $selectors['title_margin'],
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
	'faqs_title'           => array(
		'type'        => \Elementor\Controls_Manager::TAB_STYLE,
		'label'       => __( 'Faq Title', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'faq_title_typography'       => array(
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'selector' => $selectors['faqs_typography'],
				'label'    => __( 'Typography', 'wptravelengine-elementor-widgets' ),
			),
			'faq_title_color'            => array(
				'type'      => \Elementor\Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'wptravelengine-elementor-widgets' ),
				'selectors' => $selectors['faqs_color'],
			),
			'faq_title_padding'          => array(
				'label'      => esc_html__( 'Padding', 'wptravelengine-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => $selectors['faqs_padding'],
			),
			'faq_title_margin'           => array(
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'label'      => __( 'Margin', 'wptravelengine-elementor-widgets' ),
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => $selectors['faqs_margin'],
			),
			'faq_title_background_color' => array(
				'type'      => \Elementor\Controls_Manager::COLOR,
				'label'     => __( 'Background Color', 'wptravelengine-elementor-widgets' ),
				'selectors' => $selectors['faqs_background_color'],
			),

		),
	),
	'faqs_content_section' => array(
		'type'        => \Elementor\Controls_Manager::TAB_STYLE,
		'label'       => __( ' Faq Content', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'faqs_content_typography' => array(
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'selector' => $selectors['faqs_content_typography'],
				'label'    => __( 'Typography', 'wptravelengine-elementor-widgets' ),
			),
			'faqs_content_color'      => array(
				'type'      => \Elementor\Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'wptravelengine-elementor-widgets' ),
				'selectors' => $selectors['faqs_content_color'],
			),
			'faqs_content_padding'    => array(
				'label'      => esc_html__( 'Padding', 'wptravelengine-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => $selectors['faqs_content_padding'],
			),
			'faqs_content_margin'     => array(
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'label'      => __( 'Margin', 'wptravelengine-elementor-widgets' ),
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => $selectors['faqs_content_margin'],
			),
		),
	),
	'toogle_section'       => array(
		'type'        => \Elementor\Controls_Manager::TAB_STYLE,
		'label'       => __( 'Toogle', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'toogle_background_color' => array(
				'type'      => \Elementor\Controls_Manager::COLOR,
				'label'     => __( 'Background Color', 'wptravelengine-elementor-widgets' ),
				'selectors' => $selectors['toogle_background_color'],
			),
		),
	),
	'icon_section'         => array(
		'type'        => \Elementor\Controls_Manager::TAB_STYLE,
		'label'       => __( 'Icon', 'wptravelengine-elementor-widgets' ),
		'subcontrols' => array(
			'icon_color' => array(
				'type'      => \Elementor\Controls_Manager::COLOR,
				'label'     => __( 'Color', 'wptravelengine-elementor-widgets' ),
				'selectors' => $selectors['icon_color'],
			),
		),
	),
);

return $controls;
