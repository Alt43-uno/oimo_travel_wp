<script type="text/html" id="tmpl-wte-fsd-all-row">
	<#
	// Trip Data.
	var trip = data.trip;
	var tripTitle = trip.title,
	tripLink = trip.link,
	tripDuration = trip.duration;

	//Date Data.
	var fsd = data.fsd;
	startDate = data.startDate,
	endDate = data.endDate,
	costString = data.costString,
	seatsCount = data.seatsAvailable,
	actionLink = data.actionLink,
	availabilityText = data.availabilityText,
	availabilityKey = fsd.availability_type,
	currencyCode = data.currencyCode,
	duration = trip.duration,
	durationUnit = trip.durationUnit,
	daysLabel = trip.daysLabel,
	nights = trip.nights,
	nightsLabel = trip.nightsLabel,
	currencySymbol = data.currencySymbol;
	#>
	<tr style="display: table-row;">
		<td data-label="<?php esc_html_e( 'TRIP DATES', 'wte-fixed-departure-dates' ); ?>" class="accordion-sdate">
			<div class="wte-start-end-wrap">
				<# if( 'hours' == durationUnit ){ #>
					<div class="wte-sew">
					<!-- start date starts  -->
						<span class="start-date">{{startDate}}</span>
					</div>
				<# }else{ #>
				<div class="wte-sew">
					<!-- start date starts  -->
					<?php
					echo wp_kses(
						// translators: 1. Formated Date.
						sprintf( _x( 'From %s', 'All fixed departure listing Shortcode start date label.', 'wte-fixed-departure-dates' ), '<span class="start-date">{{startDate}}</span>' ),
						array(
							'span' => array(
								'class'   => array(),
								'data-id' => array(),
							),
						)
					);
					?>
				</div>
				<div class="wte-sew">
					<!-- end date starts -->
					<?php
					echo wp_kses(
						// translators: 1. Formated Date.
						sprintf( _x( 'To %s', 'All fixed departure listing Shortcode end date label.', 'wte-fixed-departure-dates' ), '<span class="end-date">{{endDate}}</span>' ),
						array(
							'span' => array(
								'class'   => array(),
								'data-id' => array(),
							),
						)
					);
					?>
				</div>
				<# } #>
			</div>
		</td>
		<td data-label="<?php esc_html_e( 'Trip', 'wte-fixed-departure-dates' ); ?>">
			<span><a href="{{tripLink}}" target="_blank">{{{tripTitle}}}</a></span>
		</td>
		<td data-label="<?php esc_html_e( 'Duration', 'wte-fixed-departure-dates' ); ?>">
			<div class="trip-duration">
				<#
				if( durationUnit == 'hours' ) {
				#>
					{{duration}} {{trip.hoursLabel}}
				<# } else {
				#>
				{{duration}} {{daysLabel}} {{nights && +nights > 0 ? nights + ' ' + nightsLabel : ''}}
				<# } #>
			</div>
		</td>
		<td data-label="<?php esc_html_e( 'AVAILABILITY', 'wte-fixed-departure-dates' ); ?>" class="accordion-availability" data-id="{{availabilityKey}}">
			<div class="seats-available">
				<span class="seats"><?php echo sprintf( __( '%s Spaces Left', 'wte-fixed-departure-dates' ), '{{seatsCount}}' ); ?></span><br/><span class="{{availabilityKey}}">{{availabilityText}}</span>
			</div>
		</td>
		<td data-label="<?php esc_html_e( 'PRICE', 'wte-fixed-departure-dates' ); ?>" class="accordion-cost">
			<strong class="trip-cost-holder">{{{costString}}}</strong><br/>
			<span><?php esc_html_e( 'per person', 'wte-fixed-departure-dates' ); ?></span>
		</td>
		<td class="accordion-book"><button data-target-trip="{{actionLink}}" class="wte-fsd-action"><?php esc_html_e( 'Book Now', 'wte-fixed-departure-dates' ); ?></button></td>
	</tr>
</script>
