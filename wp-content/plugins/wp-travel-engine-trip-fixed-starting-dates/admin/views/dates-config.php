<?php
	$sortable_settings = get_post_meta( get_the_ID(), 'list_serialized', true );
	$options = get_option( 'wp_travel_engine_settings', true );
	$set_date = '';
	$wp_travel_engine_setting = get_post_meta( get_the_ID(),'wp_travel_engine_setting',true );
	$WTE_Fixed_Starting_Dates_setting = get_post_meta( get_the_ID(), 'WTE_Fixed_Starting_Dates_setting', true );
	$option = isset($WTE_Fixed_Starting_Dates_setting['departure_dates']['section']) ? esc_attr($WTE_Fixed_Starting_Dates_setting['departure_dates']['section']) : '';
	$availability_options = WTE_Fixed_Starting_Dates_Functions::availability();
?>
<div class="dd" id="nestable1">
	<div>
		<input type="hidden" name="WTE_Fixed_Starting_Dates_setting[departure_dates][flag]" value="1">
		<label><?php _e('Number of Available Spot: ','wte-fixed-departure-dates'); ?><input type="number" name="WTE_Fixed_Starting_Dates_setting[departure_dates][availability]" class="departure-availability" placeholder="Available Spot" value="<?php echo isset($WTE_Fixed_Starting_Dates_setting['departure_dates']['availability']) ? $WTE_Fixed_Starting_Dates_setting['departure_dates']['availability']: '';?>"></label><span class="settings-note"><?php _e('Set the default number of available spot to be filled automatically in each of the fixed dates.','wte-fixed-departure-dates') ?></span>
	</div>
	<ol class="dd-list outer">
		<?php

		$cost = isset( $wp_travel_engine_setting['trip_price'] ) ? $wp_travel_engine_setting['trip_price']: '';
		$prev_cost = isset( $wp_travel_engine_setting['trip_prev_price'] ) ? $wp_travel_engine_setting['trip_prev_price']: '';

		if( $cost!='' && isset($wp_travel_engine_setting['sale']) )
		{
			$price = $cost;
		}
		else{

			$price = isset( $wp_travel_engine_setting['trip_prev_price'] ) ? $wp_travel_engine_setting['trip_prev_price']: '';
		}

		if( !is_array( $sortable_settings ) )
		{
		  $sortable_settings = json_decode( $sortable_settings );
		}
		if(isset($WTE_Fixed_Starting_Dates_setting) && $WTE_Fixed_Starting_Dates_setting!='' && isset($sortable_settings) && $sortable_settings!='')
		{
		  foreach($sortable_settings as $content){
			if(isset($WTE_Fixed_Starting_Dates_setting['departure_dates']['edate'][$content->id]))
			{
				$today = strtotime(date("Y-m-d"))*1000;
				if( $today <= strtotime($WTE_Fixed_Starting_Dates_setting['departure_dates']['edate'][$content->id])*1000 )
				{
			  ?>
		<li class="dd-item dd3-item clearfix" data-id="<?php echo $content->id;?>" id="<?php echo $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'][$content->id];?>">
			<div class="dd-handle dd3-handle"></div>
			<i class="dashicons dashicons-no-alt delete-dates delete-icon" data-id="<?php echo $content->id;?>"></i>
			<a class="accordion-toggle" href="javascript:void(0);">
				<span class="dashicons dashicons-arrow-down custom-toggle-dates"></span>
				<span class="date-info">
					<?php if (isset($WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'][$content->id]) && $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'][$content->id]!='')
						  {
						  $set_date .=  "'".$WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'][$content->id]."'".',';
						  echo esc_attr($WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'][$content->id]).' to '.esc_attr($WTE_Fixed_Starting_Dates_setting['departure_dates']['edate'][$content->id]);
						  } ?>
				</span>
			</a>
			<div class="accordion-content clearfix" name="1">
				<div class="accordion-sdate">
					<label for="WTE_Fixed_Starting_Dates_setting[departure_dates][sdate][<?php echo $content->id;?>]"><?php _e('Trip Starting Date:','wte-fixed-departure-dates'); ?></label>
					<input type="text" name="WTE_Fixed_Starting_Dates_setting[departure_dates][sdate][<?php echo $content->id;?>]" class="datepicker" value="<?php echo isset($WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'][$content->id]) ? $WTE_Fixed_Starting_Dates_setting['departure_dates']['sdate'][$content->id]: '';?>" required>
				</div>
				<div class="accordion-edate">
					<label for="WTE_Fixed_Starting_Dates_setting[departure_dates][edate][<?php echo $content->id;?>]"><?php _e('Trip End Date:','wte-fixed-departure-dates'); ?></label>
					<input type="text" name="WTE_Fixed_Starting_Dates_setting[departure_dates][edate][<?php echo $content->id;?>]" class="datepicker" value="<?php  echo isset($WTE_Fixed_Starting_Dates_setting['departure_dates']['edate'][$content->id]) ? $WTE_Fixed_Starting_Dates_setting['departure_dates']['edate'][$content->id]: '';?>" required>
					<div class="date-error"><?php _e('Error: Ending date must be greater than Starting date!','wte-fixed-departure-dates');?></div>
				</div>
				<div class="accordion-seats-available">
					<label for="WTE_Fixed_Starting_Dates_setting[departure_dates][cost][<?php echo $content->id;?>]"><?php _e('Trip Cost:','wte-fixed-departure-dates'); ?></label>
					<input type="number" min="0" name="WTE_Fixed_Starting_Dates_setting[departure_dates][cost][<?php echo $content->id;?>]" id="WTE_Fixed_Starting_Dates_setting[departure_dates][cost][<?php echo $content->id;?>]" value="<?php echo isset($WTE_Fixed_Starting_Dates_setting['departure_dates']['cost'][$content->id]) ? $WTE_Fixed_Starting_Dates_setting['departure_dates']['cost'][$content->id]: $price ;?>">
				</div>
				<div class="accordion-seats-availability">
					<label for="WTE_Fixed_Starting_Dates_setting[departure_dates][availability_type][<?php echo $content->id;?>]"><?php _e('Trip Availability:','wte-fixed-departure-dates'); ?></label>
					<select name="WTE_Fixed_Starting_Dates_setting[departure_dates][availability_type][<?php echo $content->id;?>]" id="WTE_Fixed_Starting_Dates_setting[departure_dates][availability_type][<?php echo $content->id;?>]">
						<?php
								$availability_type = isset($WTE_Fixed_Starting_Dates_setting['departure_dates']['availability_type'][$content->id]) ? $WTE_Fixed_Starting_Dates_setting['departure_dates']['availability_type'][$content->id]: 'guaranteed' ;

								foreach ($availability_options as $key => $value) {
									?>

						<option value="<?php echo esc_attr( $key );?>" <?php echo selected( $availability_type, $key ); ?>><?php echo esc_attr( $value ) ;?></option>
						<?php

								}
								?>
					</select>
				</div>
				<div class="accordion-seats-available">
					<label for="WTE_Fixed_Starting_Dates_setting[departure_dates][seats_available][<?php echo $content->id;?>]"><?php _e('Number of Available Spot:','wte-fixed-departure-dates'); ?></label>
					<input type="number" class="seats-available-<?php echo $content->id;?>" min="0" name="WTE_Fixed_Starting_Dates_setting[departure_dates][seats_available][<?php echo $content->id;?>]" id="WTE_Fixed_Starting_Dates_setting[departure_dates][seats_available][<?php echo $content->id;?>]" value="<?php echo isset($WTE_Fixed_Starting_Dates_setting['departure_dates']['seats_available'][$content->id]) ? $WTE_Fixed_Starting_Dates_setting['departure_dates']['seats_available'][$content->id]: '';?>">
				</div>
			</div>
		</li>
		<?php
				}
			}
		  }
		}
		$set_date = substr($set_date, 0, strlen($set_date)-1);
		?>
		<span id="writeroot"></span>
		<textarea name="list_serialized" id="list_serialized" style="display: none;"></textarea>
	</ol>
</div>
<div>
	<label><?php _e('Selected Dates','wte-fixed-departure-dates'); ?></label><input type="text" name="departure-dates-sdate" class="departure-dates-sdate" placeholder="Select Multiple Starting Dates">
</div>
<div id="add-dates">
	<?php $attributes = array( 'data-style' => 'appendnestable' );
		submit_button ( 'Add Date', 'primary', 'submit', true, $attributes );?>
</div>
<?php
	$enable_multiple_pricing_fsd = isset( $WTE_Fixed_Starting_Dates_setting['enable_multiple_pricing_fsd'] ) && '1' === $WTE_Fixed_Starting_Dates_setting['enable_multiple_pricing_fsd'] ? true : false;
?>
<div class="departure-dates-options">
	<label
		for="WTE_Fixed_Starting_Dates_setting[enable_multiple_pricing_fsd]">
		<?php _e( 'Apply Multi Pricing:', 'wte-fixed-departure-dates' ); ?>
	</label>
	<input
		type="checkbox"
		class="wp-travel-engine-setting-enable-pricing-sale"
		id="WTE_Fixed_Starting_Dates_setting[enable_multiple_pricing_fsd]"
		name="WTE_Fixed_Starting_Dates_setting[enable_multiple_pricing_fsd]"
		value="1"
		<?php checked( $enable_multiple_pricing_fsd, true ); ?> />
	<label
		for="WTE_Fixed_Starting_Dates_setting[enable_multiple_pricing_fsd]"
		class="checkbox-label">
	</label>
	<div class="settings-note">
		<?php _e( 'Check this if you want to enable multi pricing for all the fixed starting dates. This overrides the prices stated in each date.', 'wte-fixed-departure-dates' ); ?>
	</div>
</div>
<script>
jQuery(document).ready(function($) {

	var dates = new Array();

	function addDate(date) {
		if (jQuery.inArray(date, dates) < 0) dates.push(date);
	}

	function removeDate(index) {

		dates.splice(index, 1);
	}

	function printArray() {
		var printArr = new String;
		return dates;
	}

	function get_max_id() {
		var maximum = 0;
		$('.dd-item').each(function() {
			var value = $(this).attr('data-id');
			if (!isNaN(value)) {
				value = parseInt(value);
				maximum = (value > maximum) ? value : maximum;
			}
		});
		maximum++;
		return maximum;
	}
	var updateOutput = function(e) {
		var list = e.length ? e : $(e.target),
			output = list.data('output');
		// console.log(list);
		if (output) {
			if (window.JSON) {
				output.val(window.JSON.stringify(list.nestable('serialize')));
			} else {
				output.val('JSON browser support required for this sorting.');
			}
		}
	};


	$('body').on('click', '.delete-dates', function(e) {
		e.preventDefault();
		var id = $(this).attr('data-id');
		var val = $(this).parent().attr('id');
		var c = confirm(WPTE_OBJ.lang.are_you_sure_faq);
		if (c == true) {
			$(this).parent().fadeOut(300, function() {
				$(this).remove();
				updateOutput($('#nestable1').data('output', $('#list_serialized')));
				var index = jQuery.inArray(val, dates);
				if (index >= 0) {
					removeDate(index);
					printArray();
				}
			}); // Removing title
		}
	});

	$(document).on('click', '#add-dates .submit #submit', function(e) {
		e.preventDefault();
		dates = printArray();

		$('.dd-item').each(function() {
			var value = $(this).attr('id');

			if ($.inArray(value, dates) > -1) {
				dates.splice(dates.indexOf(value), 1);
				return;
			}
		});
		var seat = $('.departure-availability').val();
		$id = get_max_id();
		dates.forEach(function(val) {
			var value = $('.dd-item').attr('id');

			var newFieldsx = $('#template-wrap1').clone();

			newFieldsx.html(function(i, oldHTML) {
				return oldHTML.replace(/{{index}}/g, $id);
			});
			newFieldsx.find('.accordion-content').addClass('show');
			newFieldsx.find('.accordion-content').slideDown('slow');
			newFieldsx.find('.accordion-content').css('height', 'auto');
			newFieldsx.find('.dd-item').attr('id', val);
			$('.accordion-content .datepicker').datepicker({
				dateFormat: 'yy-mm-dd',
				changeMonth: true,
				changeYear: true
			});
			$('#writeroot').before(newFieldsx.html());

			newFieldsx.find($('.datepicker').removeClass('hasDatepicker').datepicker({
				minDate: 0,
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd'
			}));
			len = $('.accordion-sdate').length;
			$('.accordion-content[name=' + $id + '] .accordion-sdate').find('.datepicker').val(val);
			var days = $('.trip-duration').find('input').val();
			$('.accordion-content[name=' + $id + '] .accordion-sdate').val(val);
			days = parseInt(days);
			var tt = $('.accordion-content[name=' + $id + '] .accordion-sdate').val();
			var date = new Date(tt);
			var newdate = new Date(date);
			days = days - 1;
			newdate.setDate(newdate.getDate() + days);
			var dd = newdate.getDate();
			var mm = newdate.getMonth() + 1;
			var y = newdate.getFullYear();
			var someFormattedDate = y + '-' + mm + '-' + dd;
			$('.accordion-content[name=' + $id + '] .accordion-sdate').siblings('.accordion-edate').find('.datepicker').val(someFormattedDate);
			$('.seats-available-' + $id).attr('value', seat);

			$('#nestable1').nestable();

			updateOutput($('#nestable1').data('output', $('#list_serialized')));
			$id++;
			//end clone
		});
	});

	// Adds a date if we don't have it yet, else remove it
	function addOrRemoveDate(date) {
		var index = jQuery.inArray(date, dates);
		if (index >= 0) {
			removeDate(index);
		} else {
			addDate(date);
		}
		printArray();
	}

	// Takes a 1-digit number and inserts a zero before it
	function padNumber(number) {
		var ret = new String(number);
		if (ret.length == 1) ret = '0' + ret;
		return ret;
	}
	var today = new Date();

	var y = today.getFullYear();

	$('.departure-dates-sdate').multiDatesPicker({
		// minDate: 0,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		<?php if($set_date){
		  echo 'addDates: ['.$set_date.'],';
		}
		?>
		onSelect: function(dateText, inst) {
			$('.dd-item').each(function() {
				var value = $(this).attr('id');
				if (value == dateText) {
					$(this).remove();
				}
			});
			addOrRemoveDate(dateText);
		},
		beforeShowDay: function(date) {
			var year = date.getFullYear();
			// months and days are inserted into the array in the form, e.g '01/01/2009', but here the format is '1/1/2009'
			var month = padNumber(date.getMonth() + 1);
			var day = padNumber(date.getDate());
			// This depends on the datepicker's date format
			var dateString = year + '-' + month + '-' + day;

			var gotDate = jQuery.inArray(dateString, dates);
			if (gotDate >= 0) {
				// Enable date so it can be deselected. Set style to be highlighted
				return [true, 'ui-state-highlight'];
			}
			// Dates not in the array are left enabled, but with no extra style
			return [true, ''];
		},
	});

	$.datepicker._selectDateOverload = $.datepicker._selectDate;
	$.datepicker._selectDate = function(id, dateStr) {
		var target = $(id);
		var inst = this._getInst(target[0]);
		inst.inline = true;
		$.datepicker._selectDateOverload(id, dateStr);
		inst.inline = false;
		if (target[0].multiDatesPicker != null) {
			target[0].multiDatesPicker.changed = false;
		} else {
			target.multiDatesPicker.changed = false;
		}
		this._updateDatepicker(inst);
	};
	var datepickerHideFix = function() {

		$(document).on("click", function(e) {

			var elee = $(e.target);

			if (!elee.hasClass('hasDatepicker') &&
				elee.isChildOf('.ui-datepicker') === false &&
				elee.parent().hasClass('ui-datepicker-header') === false) {

				$('.hasDatepicker').datepicker('hide');
			}

			e.stopPropagation();
		});
	};
});
</script>
