<script type="text/html" id="tmpl-wte-package-date-times">
	<#
	var tripPackage = data.tripPackage,
	dateIndex = data.dateIndex,
	date = tripPackage['package-dates'][dateIndex],
	idSuffix = '';
	#>
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
						data-fpconfig='{{fpconfig}}' placeholder="<?php esc_attr_e( 'From', 'wte-fixed-departure-dates' ); ?>" />
				</span> -
				<span class="wte-tourtime wte-tt-to">
					<input name="dates[{{tripPackage.id}}][{{dateIndex}}][times][{{timeIndex}}][to]"
						type="text" class="wte-flatpickr" value="{{times[timeIndex]['to']}}"
						data-fpconfig='{{fpconfig}}' placeholder="<?php esc_attr_e( 'To', 'wte-fixed-departure-dates' ); ?>" />
				</span>
				<button class="wpte-btn wpte-btn-danger wte-tourtime-remove">X</button>
			</span>
		<# } //End for times. #>
		</div>
		<button class="wpte-btn wte-package-datetime-add" data-date-index="{{dateIndex}}" data-package-id="{{tripPackage.id}}" data-target="package-date_{{idSuffix}}"><?php esc_html_e( '+ Add time', 'wte-fixed-departure-dates' ); ?></button>
	</div>
</script>
