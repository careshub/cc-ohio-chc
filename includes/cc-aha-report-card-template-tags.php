<?php
/*
 *
 *
 */


// Major template pieces
function cc_aha_print_all_report_card_health( $metro_id = 0 ) {
	$data = cc_aha_get_form_data( $metro_id ); 
	?>

	<h3 class="screamer">Summary Dashboard - All Boards</h3>

	<section id="single-report-card-health" class="clear">
		<?php // Building out a table of responses for one metro
		?>
		<h4>Community Health Assessment Analysis</h4>
		<?php //cc_aha_print_health_report_card_table( $metro_id, $data ); ?>

	<a href="<?php echo cc_aha_get_analysis_permalink( 'health' ); ?>all/" class="button">View Full Health Analysis Report</a>
	</section>
	<?php 
} 