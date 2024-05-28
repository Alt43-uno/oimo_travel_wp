<!-- Temaplate for Package Dates. -->
<script type="text/html" id="tmpl-wte-package-dates">
	<# 
	var tripPackage = data.tripPackage;
	var fpconfig = JSON.stringify({mode:'multiple', minDate: new Date()})
	#>
	<div class="wpte-block-content">
		<div class="wpte-block-heading">
			<h4><?php esc_html_e( 'Dates', 'wte-fixed-departure-dates' ); ?></h4>
		</div>
		<div class="wpte-fsd-date-picker">
			<?php
			$field_builder = new WTE_Field_Builder_Admin(
				array(
					array(
						'label'       => __( 'Select Dates', 'wte-fixed-departure-dates' ),
						'type'        => 'datepicker',
						'classes'     => 'wte-flatpickr',
						'name'        => 'wte-flatpickr__date',
						'id'          => 'wte-flatpickr__date_{{tripPackage.id}}',
						'attributes'  => array(
							'data-fpconfig' => '{{fpconfig}}',
						),
						'after_field' => '<button class="wpte-btn wpte-primary wte-package-date-add" data-package-id="{{tripPackage.id}}">' . esc_html__( 'Add Dates', 'wte-fixed-departure-dates' ) . '</button>',
					),
				)
			);
			$field_builder->render();
			?>
		</div>
		<div class="wte-accordion" id="wte-package-dates-list_{{tripPackage.id}}"><!-- Dates will be rendered here. --></div>
	</div>
</script>

<?php
require_once plugin_dir_path( WTE_FIXED_DEPARTURE_FILE_PATH ) . 'templates/script-templates/trip-edit/tab-pricing/tmpl-wte-package-date-single.php';
