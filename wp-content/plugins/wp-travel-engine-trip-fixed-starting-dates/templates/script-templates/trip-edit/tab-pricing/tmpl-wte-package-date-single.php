<script type="text/html" id="tmpl-wte-package-date">
	<#
	var tripPackage = data.tripPackage,
	dateIndex = data.dateIndex,
	date = data.date,
	idSuffix = [tripPackage.id, dateIndex].join('_'),
	rrule = Object.assign({
		'r_frequency' : 'DAILY',
		'r_months' : [],
		'r_weekdays': [],
		'r_until' : '',
	}, date.rrule );

	var sdfpconfig = JSON.stringify({minDate: 'today' })
	#>
	<div class="wte-accordion-item" id="wte-package-date__{{idSuffix}}">
		<div class="wte-accordion-header">
			<label class="wte-accordion-button" style="display:block;">{{date.dtstart}}</label>
		</div>
		<div id="wte-collapse-{{idSuffix}}" class="wte-accordion-collapse wte-collapse">
			<div class="wte-package-date-times">
				<?php
				wte_form_fields(
					array(
						array(
							'type'       => 'datepicker',
							'name'       => 'dates[{{tripPackage.id}}][{{dateIndex}}][dtstart]',
							'value'      => '{{date.dtstart}}',
							'id'         => 'dtstart_{{idSuffix}}',
							'label'      => __( 'Starting Date', 'wte-fixed-departure-dates' ),
							'attributes' => array(
								'data-fpconfig' => '{{sdfpconfig}}',
							),
						),
					)
				);
				?>
				<div class="wpte-field">
					<div id="package-date_{{idSuffix}}">
					<#
						var times = date.times || {};
						for( var timeIndex in times) {
							var fpconfig = JSON.stringify({ enableTime: !0, noCalendar: !0, dateFormat: 'H:i', static: true })
					#>
						<span class="tourtimewrap">
							<span class="wte-tourtime wte-tt-from">
								<input
									name="dates[{{tripPackage.id}}][{{dateIndex}}][times][{{timeIndex}}][from]"
									type="text" class="wte-flatpickr" value="{{times[timeIndex]['from']}}"
									data-fpconfig='{{fpconfig}}' placeholder="<?php esc_attr_e( 'from', 'wte-fixed-departure-dates' ); ?>" />
							</span> -
							<span class="wte-tourtime wte-tt-to">
								<input name="dates[{{tripPackage.id}}][{{dateIndex}}][times][{{timeIndex}}][to]"
									type="text" class="wte-flatpickr" value="{{times[timeIndex]['to']}}"
									data-fpconfig='{{fpconfig}}' placeholder="<?php esc_attr_e( 'to', 'wte-fixed-departure-dates' ); ?>"/>
							</span>
							<button class="wpte-btn wpte-btn-danger wte-tourtime-remove">X</button>
						</span>
					<# } //End for times. #>
					</div>
					<button class="wpte-btn wte-package-datetime-add" data-date-index="{{dateIndex}}" data-package-id="{{tripPackage.id}}" data-target="package-date_{{idSuffix}}"><?php esc_html_e( '+ Add time', 'wte-fixed-departure-dates' ); ?></button>
				</div>
			</div>
			<div class="wte-package-date-recurring">
				<?php
				wte_form_fields(
					array(
						array(
							'label'             => __( 'Enable Repeat', 'wte-fixed-departure-dates' ),
							'type'              => 'checkbox',
							'name'              => 'dates[{{tripPackage.id}}][{{dateIndex}}][is_recurring]',
							'id'                => "{{'wte_repeat_enable_' + tripPackage.id + '_' + dateIndex}}",
							'attributes'        => array(
								'checked'            => "{{date.is_recurring ? 'checked' : ''}}",
								'data-toggle-target' => '#wte-rrule-container__{{idSuffix}}',
							),
							'checked_classname' => "{{date.is_recurring ? ' active' : ''}}",
							'value'             => '1',
							'after_field'       => call_user_func(
								function() {
									ob_start();
									?>
									<div style="width:100%;{{date.is_recurring ? '' : 'display:none;'}}" id="wte-rrule-container__{{idSuffix}}">
										<div class="wte-accordion wte-accordion-radio" data-accordion-single="true" data-multi-items="off">
											<div class="wte-accordion-item{{rrule.r_frequency.toUpperCase() == 'DAILY' ? ' wte-accordion-open' : ''}} no-arrow">
												<div class="wte-accordion-header">
													<input type="radio" id="wte-package-date-daily-{{idSuffix}}"
														name="dates[{{tripPackage.id}}][{{dateIndex}}][rrule][r_frequency]"
														value="DAILY"
														{{rrule.r_frequency.toUpperCase() == 'DAILY' ? 'checked' : ''}}
														/>
													<label for="wte-package-date-daily-{{idSuffix}}" class="wte-accordion-button" style="display:block"><?php esc_html_e( 'Daily', 'wte-fixed-departure-dates' ); ?></label>
												</div>
											</div>
											<div class="wte-accordion-item{{rrule.r_frequency.toUpperCase() == 'WEEKLY' ? ' wte-accordion-open' : ''}} no-arrow">
												<div class="wte-accordion-header">
													<input id="wte-package-date-weekly-{{idSuffix}}" type="radio"
														name="dates[{{tripPackage.id}}][{{dateIndex}}][rrule][r_frequency]"
														value="WEEKLY"
														{{rrule.r_frequency.toUpperCase() == 'WEEKLY' ? 'checked' : ''}}
														/>
													<label for="wte-package-date-weekly-{{idSuffix}}" class="wte-accordion-button" data-target="wte-collapse-{{idSuffix}}-weekly" style="display:block"><?php esc_html_e( 'Weekly', 'wte-fixed-departure-dates' ); ?></label>
												</div>
												<div id="wte-collapse-{{idSuffix}}-weekly" class="wte-accordion-collapse wte-collapse">
													<label for="" class="wpte-recurr-inn-title"><?php esc_html_e( 'Select Weekdays:', 'wte-fixed-departure-dates' ); ?></label>
													<div id="wpte-fsd-recurr-weekdays" class="wpte-multi-checboxes">
														<#
														var r_weekdays = rrule.r_weekdays || [];
														#>
														<?php
														foreach ( array( 'SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA' ) as $index => $day ) {
															echo "<# var checked = Object.values(r_weekdays).includes('{$day}') ? 'checked' : ''; #>";
															$D = date_i18n( 'D', mktime( 0, 0, 0, 11, $index + 1, 2020 ) );
															echo '<label for="" class="wte-checkbox">';
															echo "<input type='checkbox' value='{$day}' {{checked}} name='dates[{{tripPackage.id}}][{{dateIndex}}][rrule][r_weekdays][{$index}]' />";
															echo $D;
															echo '</label>';
														}
														?>
													</div>
												</div>
											</div>
											<div class="wte-accordion-item{{rrule.r_frequency.toUpperCase() == 'MONTHLY' ? ' wte-accordion-open' : ''}} no-arrow">
												<div class="wte-accordion-header">
													<input id="wte-package-date-monthly-{{idSuffix}}" type="radio"
														name="dates[{{tripPackage.id}}][{{dateIndex}}][rrule][r_frequency]"
														{{rrule.r_frequency.toUpperCase() == 'MONTHLY' ? 'checked' : ''}}
														value="MONTHLY" />
													<label for="wte-package-date-monthly-{{idSuffix}}" class="wte-accordion-header wte-accordion-button" data-target="wte-collapse-{{idSuffix}}-monthly" style="display:block"><?php esc_html_e( 'Monthly', 'wte-fixed-departure-dates' ); ?></label>
												</div>
												<div id="wte-collapse-{{idSuffix}}-monthly" class="wte-accordion-collapse wte-collapse">
													<label for="" class="wpte-recurr-inn-title"><?php esc_html_e( 'Select Months:', 'wte-fixed-departure-dates' ); ?></label>
													<div id="wpte-fsd-recurr-weekdays" class="wpte-multi-checboxes">
														<?php
														foreach ( range( 1, 12 ) as $month ) {
															echo "<# var checked = Object.values( rrule.r_months ).includes('{$month}') ? 'checked' : ''; #>";
															$M = date_i18n( 'M', mktime( 0, 0, 0, $month, 1, 2021 ) );
															echo '<label for="" class="wte-checkbox">';
															echo "<input type='checkbox' {{checked}} value='{$month}' name='dates[{{tripPackage.id}}][{{dateIndex}}][rrule][r_months][{$month}]' />";
															echo $M;
															echo '</label>';
														}
														?>
													</div>
												</div>
											</div>
											<div class="wte-accordion-item{{rrule.r_frequency.toUpperCase() == 'YEARLY' ? ' wte-accordion-open' : ''}} no-arrow">
												<div class="wte-accordion-header">
													<input id="wte-package-date-yearly-{{idSuffix}}" type="radio"
														name="dates[{{tripPackage.id}}][{{dateIndex}}][rrule][r_frequency]"
														{{rrule.r_frequency.toUpperCase() == 'YEARLY' ? 'checked' : ''}}
														value="YEARLY">
													<label for="wte-package-date-yearly-{{idSuffix}}" class="wte-accordion-button" style="display:block"><?php esc_html_e( 'Yearly', 'wte-fixed-departure-dates' ); ?></label>
												</div>
											</div>
										</div>
										<?php
										wte_form_fields(
											array(
												array(
													'type' => 'multifields',
													'subfields' => array(
														array(
															'label' => __( 'Repeat until', 'wte-fixed-departure-dates' ),
															'type' => 'datepicker',
															'name' => 'dates[{{tripPackage.id}}][{{dateIndex}}][rrule][r_until]',
															'value' => '{{rrule.r_until}}',
														),
														array(
															'label' => __( 'Repeat Limit', 'wte-fixed-departure-dates' ),
															'type' => 'number',
															'name' => 'dates[{{tripPackage.id}}][{{dateIndex}}][rrule][r_count]',
															'value' => '{{rrule.r_count || 10}}',
														),
													),
												),
											)
										);
										?>
										<div class="wpte-recurr-limit-text">
											<strong>Recursion Summary: </strong>Daily, starting from 2/20/21, 10 times
										</div>
									</div>
									<?php
									return ob_get_clean();
								}
							),
						),
					)
				);
				?>
			</div>
			<?php
			$availability_options = wte_get_availability_options();
			$options              = array();
			foreach ( $availability_options as $key => $value ) {
				$options[ $key ] = array(
					'label'      => $value,
					'attributes' => array(
						'selected' => "{{ date.availability_label === '{$key}' ? ' selected ' : ''}}",
					),
				);
			}
			wte_form_fields(
				array(
					array(
						'label'      => __( 'Total Seats', 'wte-fixed-departure-dates' ),
						'type'       => 'number',
						'value'      => '{{date.seats}}',
						'name'       => 'dates[{{tripPackage.id}}][{{dateIndex}}][seats]',
						'id'         => '',
						'attributes' => array(
							'min' => 1,
						),
					),
					array(
						'label'   => __( 'Availability Label', 'wte-fixed-departure-dates' ),
						'type'    => 'select',
						'name'    => 'dates[{{tripPackage.id}}][{{dateIndex}}][availability_label]',
						'id'      => '',
						'value'   => '{{date.availability_label}}',
						'options' => $options,
					),
				)
			);
			?>
			<!-- <button><?php esc_html_e( 'Save Changes', 'wte-fixed-departure-dates' ); ?></button>
			<button><?php esc_html_e( 'Cancel', 'wte-fixed-departure-dates' ); ?></button> -->
		</div>
		<button class="wpte-delete wte-package-date-remove" data-target="#wte-package-date__{{idSuffix}}" data-package-id="{{tripPackage.id}}" data-date-index="{{dateIndex}}"></button>
	</div>
</script>
