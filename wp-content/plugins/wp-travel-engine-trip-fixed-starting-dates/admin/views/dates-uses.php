<?php 
$custom_id = get_the_ID(); 
$WTE_Fixed_Starting_Dates_setting = get_post_meta( get_the_ID(), 'WTE_Fixed_Starting_Dates_setting', true ); 
$option = isset($WTE_Fixed_Starting_Dates_setting['departure_dates']['section']) ? esc_attr($WTE_Fixed_Starting_Dates_setting['departure_dates']['section']) : '';
?>
<h4><?php _e( 'Uses', 'wte-fixed-departure-dates' ); ?></h4>
<div class="wte-fixed-departure-settings-wrapper">
<div class="departure-dates-options">
  <label for="WTE_Fixed_Starting_Dates_setting[departure_dates][section]"><?php _e( 'Hide Fixed Trip Starts Dates section:','wte-fixed-departure-dates' ); ?></label>
  <input type="checkbox" name="WTE_Fixed_Starting_Dates_setting[departure_dates][section]" id="WTE_Fixed_Starting_Dates_setting[departure_dates][section]" value="1" <?php echo checked('1', $option); ?>/>
  <label 
        for   = "WTE_Fixed_Starting_Dates_setting[departure_dates][section]"
        class = "checkbox-label">
    </label>
  <div class="settings-note"><?php _e( 'Check this if you want to disable fixed trip starting dates section between featured image/slider and trip content sections.', 'wte-fixed-departure-dates' ); ?></div>
</div>
<h4 class="wte-fixed-departure-setting-title"><?php _e('Display via Shortcode','wte-fixed-departure-dates');?></h4>
	<div class="wte-fixed-departure-option-wrapper">
        <div class="wte-fixed-departure-option-field">
            <label class="wte-fixed-departure-plain-label">
            	<div class="wte-fixed-departure-side-note"> <?php _e('Copy this Shortcode to display fixed departure dates in pages/posts => ', 'wte-fixed-departure-dates') ?><br>
                <input type="text" readonly="readonly" class="shortcode-usage" value="[WTE_Fixed_Starting_Dates id='<?php echo $custom_id; ?>']" onClick="this.setSelectionRange(0, this.value.length)" >
                </div>
            </label>
        </div>
    </div>
<h4 class="wte-fixed-departure-setting-title"><?php _e('Display via PHP Function','wte-fixed-departure-dates');?></h4>
	<div class="wte-fixed-departure-option-wrapper">
       <div class="wte-fixed-departure-option-field">
             <label class="wte-fixed-departure-plain-label">
            	<div class="wte-fixed-departure-side-note"> <?php _e('Copy the PHP Function below to display fixed departure dates in templates :', 'wte-fixed-departure-dates') ?> <br>
                <textarea rows="2" cols="50" name="shortcode-function" readonly="readonly" onClick="this.setSelectionRange(0, this.value.length)">&lt;?php echo do_shortcode("[WTE_Fixed_Starting_Dates id='<?php echo $custom_id; ?>']"); ?&gt; </textarea>
                </div>
            </label>

        </div>
    </div>
</div>