<script type="text/html" id="tmpl-wte-fsd-all">
	<table>
		<thead>
			<tr>
				<th class="wte-fsd-table-head wte-fsd-from-to" data-label="<?php esc_html_e( 'From - To', 'wte-fixed-departure-dates' ); ?>"><?php esc_html_e( 'From - To', 'wte-fixed-departure-dates' ); ?></th>
				<th class="wte-fsd-table-head wte-fsd-trip-name" data-label="<?php esc_html_e( 'Trip Name', 'wte-fixed-departure-dates' ); ?>"><?php esc_html_e( 'Trip Name', 'wte-fixed-departure-dates' ); ?></th>
				<th class="wte-fsd-table-head wte-fsd-duration" data-label="<?php esc_html_e( 'Duration', 'wte-fixed-departure-dates' ); ?>"><?php esc_html_e( 'Duration', 'wte-fixed-departure-dates' ); ?></th>
				<th class="wte-fsd-table-head wte-fsd-availability" data-label="<?php esc_html_e( 'Availability', 'wte-fixed-departure-dates' ); ?>"><?php esc_html_e( 'Availability', 'wte-fixed-departure-dates' ); ?></th>
				<th class="wte-fsd-table-head wte-fsd-price" data-lable="<?php esc_html_e( 'Price', 'wte-fixed-departure-dates' ); ?>"><?php esc_html_e( 'Price', 'wte-fixed-departure-dates' ); ?></th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		{{{data.dateRowsHTMl}}}
		</tbody>
	</table>
	<div class="loader" style="display:none;">
		<div class="table">
			<div class="table-row">
				<div class="table-cell">
					<svg class="svg-inline--fa fa-spinner fa-w-16 fa-spin" aria-hidden="true" data-prefix="fa" data-icon="spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg><!-- <i class="fa fa-spinner fa-spin"></i> -->
				</div>
			</div>
		</div>
	</div>
</script>
