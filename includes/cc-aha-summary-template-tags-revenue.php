<?php 
/**
 * CC American Heart Association Extras
 *
 * @package   CC American Heart Association Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2014 CommmunityCommons.org
 */


function cc_aha_print_single_report_card_revenue( $metro_id = 0 ){
	if ( cc_aha_user_can_do_assessment() ) : 
		?>
	<section id="revenue-analysis-navigation" class="clear">
		<h3>Revenue Assessment Analysis</h3>
		<ul>
		<?php 
		$revenue_sections = cc_aha_get_summary_revenue_sections();
		foreach ( $revenue_sections as $revenue_name => $revenue_section ) {
			?>
			<li><a href="<?php echo cc_aha_get_analysis_permalink( 'revenue' ) . $revenue_section['slug'];?>"><?php  echo $revenue_section['label']; ?></a></li>
			<?php
		}

		?>
		</ul>
	</section>
	<?php 
	endif;
}

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
		
		if( $section_key == 'event_leadership' || $section_key == 'sponsorship' || $section_key == 'donor_stewardship' ) { ?>
		
			<form id="aha_summary-revenue-<?php echo $section_key; ?>" class="standard-form aha-survey" method="post" action="<?php echo cc_aha_get_home_permalink() . 'update-summary/'; ?>">
			
			<h2 class="screamer"><?php echo $revenue_sections[$section_key]['label']; ?></h2>
			
			<p><strong>Background: </strong><?php echo cc_aha_get_summary_introductory_text_revenue( $section_key ); ?></p>
			
			<p><strong>Outcome: </strong><?php echo cc_aha_get_summary_outcome_text_revenue( $section_key ); ?></p>
			<?php 
				
				$function_name = 'cc_aha_print_revenue_summary_' . $section_key;
				if ( function_exists( $function_name ) ) {
					$function_name( $metro_id );
				} else {
					// TODO: Remove this debugging code.
					//echo "no function by the name: " . $function_name;
				}
			 ?>
			 
			<!--<fieldset>
				
				<textarea id="<?php echo $section_key . '-open-response'; ?>" name="board[<?php echo $section_key . '-open-response'; ?>]"><?php echo $data[$section_key . '-open-response']; ?></textarea>
				
			</fieldset>-->
			
			<input type="hidden" name="metro_id" value="<?php echo $metro_id; ?>">
			<input type="hidden" name="revenue-section" value="revenue-<?php echo $section; ?>">
			<?php wp_nonce_field( 'cc-aha-assessment', 'set-aha-assessment-nonce' ) ?>
				
			<div class="form-navigation clear">
				<div class="submit">
					<input type="submit" name="submit-revenue-analysis-to-toc" value="Save, Return to Table of Contents" id="submit-revenue-analysis-to-toc">
				</div>
			</div>
			</form>
		<?php } else { ?>
		
			<h2 class="screamer"><?php echo $revenue_sections[$section_key]['label']; ?></h2>
			
			<p><strong>Background: </strong><?php echo cc_aha_get_summary_introductory_text_revenue( $section_key ); ?></p>
			
			<p><strong>Outcome: </strong><?php echo cc_aha_get_summary_outcome_text_revenue( $section_key ); ?></p>
			<?php 
				
				$function_name = 'cc_aha_print_revenue_summary_' . $section_key;
				if ( function_exists( $function_name ) ) {
					$function_name( $metro_id );
				} else {
					// TODO: Remove this debugging code.
					//echo "no function by the name: " . $function_name;
				}
			 ?>
            <input type="hidden" name="analysis-section" value="<?php echo bp_action_variable( 2 ); ?>">
			<input type="hidden" name="metro_id" value="<?php echo $metro_id; ?>">
			<input type="hidden" name="revenue-section" value="revenue-<?php echo $section_key['slug']; ?>">
				
			<div class="form-navigation clear">
				<a href="<?php echo cc_aha_get_analysis_permalink( 'revenue' ); ?>" class="button alignright">Return to Table of Contents</a>
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
		
		<fieldset>
			<label for="revenue-7.1-1"><h4>What companies are in your pipeline for recruitment (for next year and next 3 years)?</h4>
			<textarea id="revenue-7.1-1" name="board[revenue-7.1-1]"><?php echo $data['revenue-7.1-1']; ?></textarea>
		</fieldset>
		
		<fieldset>
			<label for="revenue-7.1-2"><h4>Who are the top 5 executives that are in your pipeline for Event Chair for the next year?</h4>			
			<textarea id="revenue-7.1-2" name="board[revenue-7.1-2]"><?php echo $data['revenue-7.1-2']; ?></textarea>
		</fieldset>
		
		<fieldset>
			<label for="revenue-7.1-3"><h4>Who are the top 10 executives that are in your pipeline for Event Chair for the next three years?</h4>
			<textarea id="revenue-7.1-3" name="board[revenue-7.1-3]"><?php echo $data['revenue-7.1-3']; ?></textarea>
		</fieldset>
		
	</ul>

<?php
}

function cc_aha_print_revenue_summary_elt_leadership( $metro_id ){
	$data = cc_aha_get_form_data( $metro_id );
	
	?>
	<h5>Current State</h5>

	<p>A report has been prepared for you showing ELT giving overall and by event and how you compare to markets of like size. 
	Click <a href='http://sharepoint.heart.org/nat/Volunteerism/Community%20Planning%202014-2017/Forms/AllItems.aspx?RootFolder=%2Fnat%2FVolunteerism%2FCommunity%20Planning%202014%2D2017%2FCPP2%20Revenue%20Assessment%20Analysis&FolderCTID=0x012000CFD890B30E39714BB20EE7AD8D89525D&View={035A3458-0EA6-40A9-9276-2F7B89EA536B}' target='_blank'>here</a> to download the report to share with your board.</p>
	
	<p><em>Please Note: This link will open in Sharepoint where you will see a list of affiliate folders. Choose the one you belong to and find your offices analysis. There you will find summaries of many of the criteria in the revenue section. Keep this report and note the tabs summarizing different aspects of this assessment.</em></p>
	
	<p><?php echo $data['8.1.2'] ? 'Your ELT leadership has all industries for your area represented.' : 'Your ELT leadership does not have all industries for your area represented.'; ?>
	</p>
	
	<?php
}

function cc_aha_print_revenue_summary_top_25_companies( $metro_id ){
	
	$data = cc_aha_get_form_data( $metro_id );
	?>
	<h5>Current State</h5>
	<p>Find a link to your Top 25 report with summary <a href='http://sharepoint.heart.org/nat/Volunteerism/Community%20Planning%202014-2017/Forms/AllItems.aspx?RootFolder=%2Fnat%2FVolunteerism%2FCommunity%20Planning%202014%2D2017%2FCPP2%20Revenue%20Assessment%20Analysis&FolderCTID=0x012000CFD890B30E39714BB20EE7AD8D89525D&View={035A3458-0EA6-40A9-9276-2F7B89EA536B}' target='_blank'>here</a>.</p>
	
	<p><em>Please Note: This link will open in Sharepoint where you will see a list of affiliate folders. Choose the one you belong to and find your offices analysis. There you will find summaries of many of the criteria in the revenue section. Keep this report and note the tabs summarizing different aspects of this assessment. </em></p>
	
	<p>Locate your affiliate and find your office from the list there.</p>
	
	<?php //TODO: get this link..

}

function cc_aha_print_revenue_summary_sponsorship( $metro_id ){
	
	$data = cc_aha_get_form_data( $metro_id );
	?>
	<h5>Current State</h5>

	<p>Find a report on your Platform and Signature sponsorships for the market’s events <a href='http://sharepoint.heart.org/nat/Volunteerism/Community%20Planning%202014-2017/Forms/AllItems.aspx?RootFolder=%2Fnat%2FVolunteerism%2FCommunity%20Planning%202014%2D2017%2FCPP2%20Revenue%20Assessment%20Analysis&FolderCTID=0x012000CFD890B30E39714BB20EE7AD8D89525D&View={035A3458-0EA6-40A9-9276-2F7B89EA536B}' target='_blank'>here</a>.</p>

	<p><em>Please Note: This link will open in Sharepoint where you will see a list of affiliate folders. Choose the one you belong to and find your offices analysis. There you will find summaries of many of the criteria in the revenue section. Keep this report and note the tabs summarizing different aspects of this assessment. </em></p>
	
	<p>You listed the following companies as ones who place a focus on corporate social responsibility: <?php echo $data['9.1.4'] ? $data['9.1.4'] : '<em>none</em>'; ?></p>

	<h5>Discussion Questions</h5>
	<ul>
		<fieldset>
			<label for="revenue-10.1"><h4>Is the board involved in developing a pipeline for Platform and Signature sponsorship?</h4>
			<textarea id="revenue-10.1" name="board[revenue-10.1]"><?php echo $data['revenue-10.1']; ?></textarea>
			<!--<input type="text" name="board[revenue-10.1]" value="<?php echo $data['revenue-10.1']; ?>" autocomplete="off" />-->
		</fieldset>
		
		<fieldset>
			<label for="revenue-10.3"><h4>What other companies could be top prospects to join your existing Platform/Signature sponsors?</h4>
			<textarea id="revenue-10.3" name="board[revenue-10.3]"><?php echo $data['revenue-10.3']; ?></textarea>
			<!--<input type="text" name="10.3" value="<?php echo $data['revenue-10.3']; ?>" autocomplete="off" />-->
		</fieldset>
		
		<fieldset>
			<label for="revenue-10.6"><h4>What can be done to bring those current sponsors up to the recommended level?</h4>			
			<textarea id="revenue-10.6" name="board[revenue-10.6]"><?php echo $data['revenue-10.6']; ?></textarea>
		</fieldset>
		
		<fieldset>
			<label for="revenue-10.7"><h4>How is the metro market team working together to implement account management of Platform sponsors or potential Platform sponsors?</h4>
			<textarea id="revenue-10.7" name="board[revenue-10.7]"><?php echo $data['revenue-10.7']; ?></textarea>
		</fieldset>
		
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
			<fieldset>
				<label for="revenue-13.1.6"><h4>Will you be developing a cultivation plan this year?</h4>
				<!--<input type="text" name="board[revenue-13.1.6]" value="<?php echo $data['revenue-13.1.6']; ?>" autocomplete="off" />-->
				<textarea id="revenue-13.1.6" name="board[revenue-13.1.6]"><?php echo $data['revenue-13.1.6']; ?></textarea></fieldset>
		<?php } ?>
		
		<fieldset>
			<label for="revenue-13.1.5"><h4>Are you inviting top donors to all events in market?</h4>
			<textarea id="revenue-13.1.5" name="board[revenue-13.1.5]"><?php echo $data['revenue-13.1.5']; ?></textarea>
		</fieldset>
		
		<fieldset>
			<label for="revenue-13.1.7"><h4>Do you know who your top donors are?</h4>			
			<textarea id="revenue-13.1.7" name="board[revenue-13.1.7]"><?php echo $data['revenue-13.1.7']; ?></textarea>
		</fieldset>
		
		<fieldset>
			<label for="revenue-13.1.8"><h4>Is there a way board members could engage top donors?</h4>
			<textarea id="revenue-13.1.8" name="board[revenue-13.1.8]"><?php echo $data['revenue-13.1.8']; ?></textarea>
		</fieldset>
		
	</ul>

	<?php
}

function cc_aha_print_revenue_summary_pdw_legacy( $metro_id ){
	
	$data = cc_aha_get_form_data( $metro_id );
	?>
	
	<h3>Donor Retention</h3>
	
	<h5>Current State</h5>

	<p><?php echo $data['14.1.1'] ? 'You have acknowledged that your board has knowledge of the Paul Dudley White Legacy Society.' : 'You have stated that your board does not have knowledge of the Paul Dudley White Legacy Society.'; ?></p>
	
	<p><?php echo $data['14.1.2'] ? 'Your market has a Paul Dudley White Legacy Society Champion connected to the board and/or a board member.' : 'Your market does not have a Paul Dudley White Legacy Society Champion connected to the board and/or a board member.'; ?></p>

	<p><?php echo $data['14.1.3']; ?>% of your board members are currently Paul Dudley White Legacy Society members.</p>
	<?php
}

function cc_aha_get_summary_revenue_sections() {
	// Key is used in function names, label is the label, slug is used in url
	return array( 
		'event_leadership' => array(
			'label' => 'Recruit Event Leadership',
			'slug' 	=> 'event-leadership',
			'background' => 'The Best Practice for recruitment of Event Chair leadership is a critical element in the successful execution of fundraising events which both raise the greatest potential revenue and reach our targeted audiences for community outreach.  Individuals that are selected to be chair are ideally leaders/top managers in the community that have the ability to recruit and lead others, give corporately at the prescribed top 2 levels for that MSA, and give personally of their time and financial commitment.  Chairs should be recruited, at a minimum, 12-18 months prior to the event, with a focus on the depth of recruitment being multiple (3) years in order to allow sufficient time for future chairs to observe, train, network, and become active in AHA/ASA’s mission.',
			'outcome' => 'Ensure event revenue increases by recruiting the needed event chairs.',
			'outcome-stats' => array(
				1 	=> 'Recruit event chairs who are community and corporate leaders, decision makers, and have the ability to give at the top two levels of sponsorship for the market size.',
				2	=> 'Recruit 3 years of event chairs who are community and corporate leaders, decision makers, and have the ability to give at the top two levels of sponsorship for the market size.'
				)
			),
		'elt_leadership' => array(
			'label' => 'Secure Top ELT Leadership',
			'slug' 	=> 'elt-leadership',
			'background' => 'The Best Practice for recruitment of the Executive Leadership Team (ELT)  is a critical element in the execution of successful fundraising events.  Ideally, these committees will be made up of at least fifteen members who can give corporately at one of the prescribed top levels of sponsorship, give a generous personal gift, and dedicate sufficient time to complete their responsibilities as a member of ELT.  These individuals should be recruited by the event Chair, in partnership with staff.  They are responsible for helping raise new and renewed corporate and individual revenue.',
			'outcome' => 'Grow event revenue through recruitment of ELT members.',
			'outcome-stats' => array(
				1	=> 'Recruit 15 or more ELT members for all three core events that are giving at the top four levels of sponsorship for the size market and securing other companies giving at the same level.'
				)
			),
		'top_25_companies' => array(
			'label' => 'Grow Top 25 Company Engagement',
			'slug' 	=> 'top-25-companies',
			'background' => 'Involvement from the top 25 corporations in markets is critical for the current and future success of fundraising events and for helping us “meet people where they are” with healthy living strategies.  Because of the larger number of employees involved, there is greater likelihood for employee involvement in different AHA events, and the greatest financial potential for corporate giving.  This corporate segment gives a pipeline for recruitment and source for new company development as well as provides depth with current partners due to size and scope.',
			'outcome' => 'Board demonstrates meaningful contributions from top 25 employers through leadership/CSuite involvement, fundraising, and health-related activities.',
			'outcome-stats' => array(
				1	=> 'Increase companies that raise over 100K to X.',
				2	=> 'Increase top 25 employers featuring AHA in their employee giving program from X to Y.'
				)
			),
		'sponsorship' => array(
			'label' => 'Secure Platform/ Signature Sponsorship',
			'slug' 	=> 'sponsorship',
			'background' => 'The recruitment of renewed and additional top level partners each year for events are critical to the fundraising success of corporate events.  These partners bring year-round opportunities for partnering with AHA/ASA in our mission activation and helping people in our communities become healthier.',
			'outcome' => 'Increase annual revenue through Platform/Signature sponsors in market (sponsorship levels at bottom of document).',
			'outcome-stats' => array(
				1	=> 'Increase Platform/Signature sponsors in market to X'
				)
			),
		'youth_market' => array(
			'label' => 'Expand Youth Market Efforts',
			'slug' 	=> 'youth-market',
			'background' => 'Increasing the number of donors who have the capacity to make a $100,000+ gift, as well as increasing the acquisition and retention of Cor Vitae Society Members ($5,000+ annually) will help further the mission of AHA/ASA.  These measurements are consistent with our commitment to our Guiding Values and directly correlate with “Making an Extraordinary Impact” and “Inspiring Passionate Commitment”.  By increasing revenue from Cor Vitae Society Members, as well as those already giving at high levels, local board members will also be impacting other Guiding Values such as “Bringing Science to Life”, “Improving and Extending People’s Lives”, and “Ensuring Equitable Health for All”.',
			'outcome' => 'Increase the number of schools participating in AHA health and revenue activities.',
			'outcome-stats' => array(
				1	=> 'Recruit Superintendent who sets a district wide goal and actively engages all the schools in the district with students, faculty & all district wide employees.'
				)
			),
		'individual_giving' => array(
			'label' => 'Increase Individual Giving',
			'slug' 	=> 'individual-giving',
			'background' => 'Increasing the number of donors who have the capacity to make a $100,000+ gift, as well as increasing the acquisition and retention of Cor Vitae Society Members ($5,000+ annually) will help further the mission of AHA/ASA.  These measurements are consistent with our commitment to our Guiding Values and directly correlate with “Making an Extraordinary Impact” and “Inspiring Passionate Commitment”.  By increasing revenue from Cor Vitae Society Members, as well as those already giving at high levels, local board members will also be impacting other Guiding Values such as “Bringing Science to Life”, “Improving and Extending People’s Lives”, and “Ensuring Equitable Health for All”.',
			'outcome' => 'Increase pipeline of potential individual donors that have the ability to give $100,000+ gifts.',
			'outcome-stats' => array(
				1	=> 'Increase prospects that have the ability of giving a gift of $100,000 or more from X to Y.',
				2	=> 'Retain at least X Cor Vitae members.'
				)
			),
		'donor_stewardship' => array(
			'label' => 'Enhance Donor Stewardship',
			'slug' 	=> 'donor-stewardship',
			'background' => 'A consistent, strategic Stewardship plan is the cornerstone for building engagement with any mission-centered organization.  In order to help meet the AHA’s 2020 Impact Goal, organization-wide standards and practices are being implemented to provide consistent, meaningful stewardship and engagement opportunities for donors and volunteers.  National and Affiliate staff and volunteers will partner to implement a comprehensive Stewardship plan in order to: <ul><li>Advance our strategic priorities</li><li>Support annual and long-range fundraising goals</li><li>Increase donor retention</li><li>Create inspiring opportunities to engage our donors and volunteers for the long-term</li>',
			'outcome' => 'Retain donors through robust engagement and stewardship activities.',
			'outcome-stats' => array(
				1	=> 'Increase stewardship activities (thank you calls, personal notes, invitations to lunches, dinners, events) in order to foster ongoing interest and engagement in AHA’s mission.'
				)
			),
		'pdw_legacy' => array(
			'label' => 'Membership in the Paul Dudley White Legacy Society',
			'slug' 	=> 'pdw-legacy',
			'background' => 'Paul Dudley White was President Eisenhower’s personal physician. He guided the president’s recovery from heart attack in 1955. But this forward-thinking physician was also a founder of the American Heart Association and served as its president from 1940-41. In honor of his vision in building an organization that would lead in heart research and education and to honor those who share that vision, the Paul Dudley White Legacy Society was organized. In joining the Paul Dudley White Legacy Society you are securing the immortality of your generosity through the lives that will be touched by the research and education made possible through your gifts.',
			'outcome' => 'Increase understanding of AHA’s personal legacy and estate planning programs.',
			'outcome-stats' => array(
				1	=> 'Increase understanding of AHA’s personal legacy and estate planning programs.'
				)
			),
		);
}

function cc_aha_get_summary_introductory_text_revenue( $section_key ) {
	$section_data = cc_aha_get_summary_revenue_sections();
	$text = $section_data[$section_key]['background'];

	return $text;
}

function cc_aha_get_summary_outcome_text_revenue( $section_key ) {
	$section_data = cc_aha_get_summary_revenue_sections();
	$text = $section_data[$section_key]['outcome'];

	return $text;
}

