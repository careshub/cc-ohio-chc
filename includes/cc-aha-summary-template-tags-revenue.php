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

	<section id="revenue-<?php echo $section_key; ?>" class="clear">
	
		<?php //to form or not to form?
		
		if( $section_key == 'event_leadership' || $section_key == 'elt_leadership' || $section_key == 'sponsorship' || $section_key == 'donor_stewardship' ) { ?>
		
			<form id="aha_summary-revenue-<?php echo $section_key; ?>" class="standard-form aha-survey" method="post" action="<?php echo cc_aha_get_home_permalink() . 'update-summary/'; ?>">
			
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
			 
			<fieldset>
				
				<textarea id="<?php echo $section_key . '-open-response'; ?>" name="board[<?php echo $section_key . '-open-response'; ?>]"><?php echo $data[$section_key . '-open-response']; ?></textarea>
				
			</fieldset>
			
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
		<?php } else { ?>
		
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
			<input type="hidden" name="revenue-section" value="revenue-<?php echo $section_key; ?>">
				
			<div class="form-navigation clear">
				<!--<input type="submit" name="submit-survey-to-toc" value="Return to Table of Contents" id="submit-survey-to-toc">-->
				<a href="<?php echo cc_aha_get_analysis_permalink(); ?>" class="button alignright">Return to Table of Contents</a>
				<!-- <div class="submit">
					<input type="submit" name="submit-survey-next-page" value="Save Responses and Continue" id="submit-survey-next-page">
				</div> -->
			</div>
			
		<?php } ?>
		
	<?php
}
/**
 * Produce the revenue-section-specific code.
 *
 * @since   1.0.0
 * @return  html - generated code
 */

function cc_aha_print_revenue_summary_event_leadership( $metro_id ){

	$data = cc_aha_get_form_data( $metro_id );
	
	?>
	<h5>Current State</h5>
	<p><?php echo $data['7.1.1'] ? 'You have recruited all event chairs for fiscal year 2014-2015' : 'You have not recruited all event chairs for fiscal year 2014-2015'; ?>
	<?php if ( $data['7.1.1.1'] != null || $data['7.1.1.1'] != '' ) echo ' and have indicated the following events need chairs: ' . $data['7.1.1.1']; ?></p>
	
	<p><?php echo $data['7.1.2'] ? 'You have recruited all event chairs for fiscal year 2015-2016' : 'You have not recruited all event chairs for fiscal year 2015-2016'; ?>
	<?php if ( $data['7.1.2.1'] != null || $data['7.1.2.1'] != '' ) echo ' and have indicated the following events need chairs: ' . $data['7.1.2.1']; ?></p>
	
	<p><?php echo $data['7.1.3'] ? 'You have recruited all event chairs for fiscal year 2016-2017' : 'You have not recruited all event chairs for fiscal year 2016-2017'; ?>
	<?php if ( $data['7.1.3.1'] != null || $data['7.1.3.1'] != '' ) echo ' and have indicated the following events need chairs: ' . $data['7.1.3.1']; ?></p>
	
	<p><?php echo $data['7.1.4.1'] ? 'For fiscal year 2014-2015 the Event Chairs have given at the top 2 levels for Go Red.' : 'For fiscal year 2014-2015 the Event Chairs have not given at the top 2 levels for Go Red.'; ?>
	</p>
	
	<p><?php echo $data['7.1.4.2'] ? 'For fiscal year 2014-2015 the Event Chairs have given at the top 2 levels for Heart Ball.' : 'For fiscal year 2014-2015 the Event Chairs have not given at the top 2 levels for Heart Ball.'; ?>
	</p>
	
	<p><?php echo $data['7.1.4.3'] ? 'For fiscal year 2014-2015 the Event Chairs have given at the top 2 levels for Heart Walk.' : 'For fiscal year 2014-2015 the Event Chairs have not given at the top 2 levels for Heart Walk.'; ?>
	</p>
	
	<h5>Discussion Questions</h5>
	
	<strong>When considering your open Chair positions:</strong>
	<ul>
		<li>What companies are in your pipeline for recruitment (for next year and next 3 years)?</li>
		<li>Who are the top 5 executives that are in your pipeline for Event Chair for the next year?</li>
		<li>Who are the top 10 executives that are in your pipeline for Event Chair for the next three years?</li>
	</ul>
	

<?php
}

function cc_aha_print_revenue_summary_elt_leadership( $metro_id ){
	$data = cc_aha_get_form_data( $metro_id );
	
	?>
	<h5>Current State</h5>

	<p>A report has been prepared for you showing ELT giving overall and by event and how you compare to markets of like size. 
	Click here to download the report to share with your board [insert link].</p> <?php //TODO: where are these links coming from? ?>
	
	<p><?php echo $data['8.1.2'] ? 'Your ELT leadership has all industries for your area represented.' : 'Your ELT leadership does not have all industries for your area represented.'; ?>
	</p>
	
	<?php 
	//TODO: Some of the questions in this section are already asked in the assessment. Are we re-asking or just adding? Mel asked Christian 8/28 ?>
	
	<h5>Discussion Questions</h5>
	<ul>
		<li>What is the average gift of the ELT for 
		<ul>
			<li>Heart Walk?</li>
			<li>Heart Ball?</li>
			<li>Go Red for Women?</li>
		</ul>
		</li>
		<li>Do you have all the industries represented? (Accounting, Banking, Energy, Cable, Healthcare, Media, Lawyers, Manufacturing, Real Estate, etc.)</li>
		<li>What is the largest gift given by an ELT member?</li>
		<li>What is the average size gift? </li>
		<li>How many ELT members are giving no corporate gift? </li>
		<li>How do you compare to other markets of like size?</li>
	</ul>


	
	<?php
}

function cc_aha_print_revenue_summary_top_25_companies( $metro_id ){
	
	$data = cc_aha_get_form_data( $metro_id );
	?>
	<h5>Current State</h5>
	<p>Find a link to your Top 25 report with summary here. [provide link]</p>
	<p>Locate your affiliate and find your office from the list there.</p>
	
	<?php //TODO: get this link..

}

function cc_aha_print_revenue_summary_sponsorship( $metro_id ){
	
	$data = cc_aha_get_form_data( $metro_id );
	?>
	<h5>Current State</h5>

	<?php //TODO: get this link; also, Mel coded a 'none' below, hope that's great.  ?>
	<p>Find a report on your Platform and Signature sponsorships for the market’s events here. [provide link]</p>
	
	<p>You listed the following companies as ones who place a focus on corporate social responsibility: <?php echo $data['9.1.4'] ? $data['9.1.4'] : '<em>none</em>'; ?></p>

	<h5>Discussion Questions</h5>
	<ul>
		<li>Is the board involved in developing a pipeline for Platform and Signature sponsorship?</li>
		<li>What other companies could be top prospects to join your existing Platform/Signature sponsors?</li>
		<li>What can be done to bring those current sponsors up to the recommended level?</li>
		<li>How is the metro market team working together to implement account management of Platform sponsors or potential Platform sponsors?</li>
	</ul>
	
	<?php
}

function cc_aha_print_revenue_summary_youth_market( $metro_id ){

	$data = cc_aha_get_form_data( $metro_id );
	$school_data = cc_aha_get_school_data( $metro_id );
	
	$qids = array( '11.1.1' );
	$percent_11_1_1 = cc_aha_calc_n_question_district_yes_percent( $school_data, $qids );
	
	$qids2 = array( '11.1.2' );
	$amount_11_1_2 = cc_aha_calc_n_question_district_add_amount( $school_data, $qids2 );
	?>
	
	<h3>Participating Schools</h3>
	
	<h5>Current State</h5>

	<?php //TODO: get this link; also, Mel coded a '0' below, hope that's great.  ?>
	<p>You have Superintendents’ support within <?php echo $percent_11_1_1; ?>% of the top 5 school districts in your community.</p>
	
	<table>
		<thead>
			<tr>
				<th>Top 5 School Districts</th>
				<th>Has Superintendent support</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ( $school_data as $school ) {
				?>
				<tr>
					<td><?php echo $school['DIST_NAME']; ?></td>
					<td><?php echo $school['11.1.1'] ? 'Yes' : 'No'; ?></td>
				</tr>
				<?php
			} ?>
		</tbody>
	</table>
	
	<p>The top 5 school districts in your area raised $<?php echo $amount_11_1_2; ?>.</p>
	
	<br />
	<table>
		<thead>
			<tr>
				<th>Top 5 School Districts</th>
				<th>Amount Raised</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ( $school_data as $school ) {
				?>
				<tr>
					<td><?php echo $school['DIST_NAME']; ?></td>
					<td><?php echo '$'; echo $school['11.1.2'] ? $school['11.1.2'] : '0'; ?></td>
				</tr>
				<?php
			} ?>
		</tbody>
	</table>
	
	<?php
}

function cc_aha_print_revenue_summary_individual_giving( $metro_id ){

	$data = cc_aha_get_form_data( $metro_id );
	?>
	
	<h3>Individual Giving Prospects</h3>
	
	<h5>Current State</h5>

	<?php //TODO: get this link; also, Mel coded a '0' below, hope that's great.  ?>
	<p>You have <?php echo $data['12.1.2'] ? $data['12.1.2'] : '0'; ?> $100k donors in the pipeline.</p>
	
	
	
	<h3>Cor Vitae Recruitment</h3>
	
	<h5>Current State</h5>
	
	<p><?php echo $data['12.2.1'] ? $data['12.2.1'] : '0'; ?>% of your board members are Cor Vitae Members</p>
	<p><?php echo $data['12.2.2'] ? $data['12.2.2'] : '0'; ?> Cor Vitae members are in your market.</p>
	<p>You stated that you are retaining your Cor Vitae members in the following way: <?php echo $data['12.2.3']; ?></p>

	<?php
}

function cc_aha_print_revenue_summary_donor_stewardship( $metro_id ){

	$data = cc_aha_get_form_data( $metro_id );
	?>
	
	<h3>Donor Retention</h3>
	
	<h5>Current State</h5>

	<p>You stated that you are acknowledging your donors in the following ways: <?php echo $data['13.1.2']; ?></p>

	<p><?php echo $data['13.1.3'] ? 'You stated that you do have a current list of stewardship events in your market.' : 'You stated that you do not have a current list of stewardship events in your market.'; ?></p>
	
	<p><?php echo $data['13.1.6'] ? 'You stated that you are following a cultivation plan.' : 'You stated that you are not following a cultivation plan.'; ?></p>


	<h5>Discussion Questions</h5>
	<ul>
		<?php if ( $data['13.1.6'] == 0 ) { ?> 
			<li>Will you be developing a cultivation plan this year?</li>
		<?php } ?>
		<li>Are you inviting top donors to all events in market?</li>
		<li>Do you know who your top donors are?</li>
		<li>Is there a way board members could engage top donors?</li>
	</ul>

	<?php
}

function cc_aha_print_revenue_summary_pdw_legacy( $metro_id ){
	
	$data = cc_aha_get_form_data( $metro_id );
	?>
	
	<h3>Donor Retention</h3>
	
	<h5>Current State</h5>

	<p><?php echo $data['14.1.1'] ? 'You have acknowledged that your board has knowledge of the Paul Dudley White Legacy Society.' : 'You have stated that your board does not have knowledge of the Paul Dudley White Legacy Society.'; ?></p>
	
	<p><?php echo $data['14.1.2'] ? 'You market has a Paul Dudley White Legacy Society Champion connected to the board and/or a board member.' : 'You market does not have a Paul Dudley White Legacy Society Champion connected to the board and/or a board member.'; ?></p>

	<p><?php echo $data['14.1.3']; ?>% of your board members are currently Paul Dudley White Legacy Society members.</p>
	<?php
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