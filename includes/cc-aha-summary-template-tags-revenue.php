<?php 
/**
 * CC American Heart Association Extras
 *
 * @package   CC American Heart Association Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2014 CommmunityCommons.org
 */


/**
 * Produce the page code for the revenue pages of the summary.
 *
 * @since   1.0.0
 * @return  html - generated code
 */
function cc_aha_print_revenue_section_report( $metro_id, $slug ){
	// These are only available to users who can do the assessment.
	if ( ! cc_aha_user_can_do_assessment() && ! cc_aha_user_has_super_secret_clearance() ) {
		echo '<p class="info">Sorry, you do not have permission to view this page.</p>';
		return;
	}

	//to populate response fields if filled out already
	$data = cc_aha_get_form_data( $metro_id );
	$revenue_sections = cc_aha_get_summary_revenue_sections();
	// Find the key of the array that contains the slug
	foreach ( $revenue_sections as $section_name => $section ) {
		if ( $slug == $section['slug'] ) {
			$section_key = $section_name;
			break;
		}
	}
	?>

	<section id="revenue-<?php echo $section; ?>" class="clear">
		<form id="aha_summary-revenue<?php echo $section; ?>" class="standard-form aha-survey" method="post" action="<?php echo cc_aha_get_home_permalink() . 'update-summary/'; ?>">
		
		<h2 class="screamer"><?php echo $revenue_sections[$section_key]['label']; ?></h2>
		<?php 
			
			$function_name = 'cc_aha_print_revenue_summary_' . $section_key;
			if ( function_exists( $function_name ) ) {
				$function_name( $metro_id );
			} else {
				// TODO: Remove this debugging code.
				echo "no function by the name: " . $function_name;
			}
		?>

	<input type="hidden" name="metro_id" value="<?php echo $metro_id; ?>">
	<input type="hidden" name="revenue-section" value="revenue-<?php echo $section; ?>">
	<?php wp_nonce_field( 'cc-aha-assessment', 'set-aha-assessment-nonce' ) ?>
		
	<div class="form-navigation clear">
		<div class="submit">
	        <input type="submit" name="submit-survey-to-toc" value="Save, Return to Table of Contents" id="submit-survey-to-toc">
	    </div>
		<!-- <div class="submit">
	        <input type="submit" name="submit-survey-next-page" value="Save Responses and Continue" id="submit-survey-next-page">
	    </div> -->
	</div>
	</form>
	<?php
}
/**
 * Produce the revenue-section-specific code.
 *
 * @since   1.0.0
 * @return  html - generated code
 */

function cc_aha_print_revenue_summary_event_leadership( $metro_id ){

}

function cc_aha_print_revenue_summary_elt_leadership( $metro_id ){

}

function cc_aha_print_revenue_summary_top_25_companies( $metro_id ){

}

function cc_aha_print_revenue_summary_sponsorship( $metro_id ){

}

function cc_aha_print_revenue_summary_youth_market( $metro_id ){

}

function cc_aha_print_revenue_summary_individual_giving( $metro_id ){

}

function cc_aha_print_revenue_summary_donor_stewardship( $metro_id ){

}

function cc_aha_print_revenue_summary_donor_pdw_legacy( $metro_id ){

}

function cc_aha_get_summary_revenue_sections() {
	// Key is used in function names, label is the label, slug is used in url
	return array( 
		'event_leadership' => array(
			'label' => 'Recruit Event Leadership',
			'slug' 	=> 'event-leadership'
			),
		'elt_leadership' => array(
			'label' => 'Secure Top ELT Leadership',
			'slug' 	=> 'elt-leadership'
			),
		'top_25_companies' => array(
			'label' => 'Grow Top 25 Company Engagement',
			'slug' 	=> 'top-25-companies'
			),
		'sponsorship' => array(
			'label' => 'Secure Platform/ Signature Sponsorship',
			'slug' 	=> 'sponsorship'
			),
		'youth_market' => array(
			'label' => 'Expand Youth Market Efforts',
			'slug' 	=> 'youth-market'
			),
		'individual_giving' => array(
			'label' => 'Increase Individual Giving',
			'slug' 	=> 'individual-giving'
			),
		'donor_stewardship' => array(
			'label' => 'Enhance Donor Stewardship',
			'slug' 	=> 'donor-stewardship'
			),
		'pdw_legacy' => array(
			'label' => 'Membership in the Paul Dudley White Legacy Society',
			'slug' 	=> 'pdw-legacy'
			),
		);
}