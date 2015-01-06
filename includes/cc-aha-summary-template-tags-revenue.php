<?php 
/**
 * CC American Heart Association Extras: template tags for the Revenue Summary pages
 *
 * @package   CC American Heart Association Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2014 CommmunityCommons.org
 */


function cc_aha_print_single_report_card_revenue( $metro_id = 0 ){
	?>
	<section id="revenue-analysis-navigation" class="clear">
		<h3 class="screamer">Revenue Assessment Analysis</h3>
		
		<!-- <h3>Community Health Assessment Analysis</h3> -->
		<?php cc_aha_print_revenue_report_card_table( $metro_id ); ?>

		<a href="<?php echo cc_aha_get_analysis_permalink( 'revenue' ); ?>all/" class="button">View Full Revenue Analysis Report</a>
	</section>
	<?php 
}

function cc_aha_print_revenue_report_card_table( $metro_id ){
	$revenue_sections = cc_aha_get_summary_revenue_sections();
	$data = cc_aha_get_form_data( $metro_id );
	
	//get priorites of this board in current benchmark cycle (true?)
	$date = get_current_benchmark_year();
	$priorities = cc_aha_get_priorities_by_board_date( $metro_id, $date, "criteria" );  //returns ids of criteria
	$selected_priority; //to hold selected priority for each criteria
	$priority_squished; //holder for no-space name of criteria
	
	$group_members = cc_aha_get_member_array();
?>
	<table>
		<thead>
			<tr>
				<th>Impact Area</th>
				<th>Potential Top 3 Priority</th>
				<th class="limited-width">Board-Approved Priority</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach ( $revenue_sections as $revenue_name => $revenue_section ) { 
				$selected_priority = 0; //re-set for each criteria
				$selected_staff_partner = 0;
				$selected_volunteer_lead = 0;
			?>
				<tr>
					<td class="criteria_title">
						<a href="<?php 
							if ( cc_aha_on_analysis_complete_report_screen() ) {
								// Output a local link if on the complete report
								echo '#revenue' . '-' . $revenue_name;
							} else { 
								echo cc_aha_get_analysis_permalink( 'revenue' ) . $revenue_section['slug'];
							} ?>">
						<?php echo $revenue_section['label']; ?>
						</a>
					</td>
					<td>
						<?php echo $data[$revenue_name . '-top-3'] ? 'Yes' : 'No'; ?>
					</td>
					<td class="board-approved-priority-checkbox" >
						<?php //cycle through 'priorities' and mark those already added to system
						$priority_squished = str_replace(' ', '', $revenue_section['label']);
						foreach( $priorities as $key => $value ){

							if ( $key == $priority_squished ){
								$selected_priority = $value;
								if( $selected_priority > 0 ){
									$selected_staff_partner = get_post_meta( $selected_priority, "staff_partner", true );
									$selected_volunteer_lead = get_post_meta( $selected_priority, "volunteer_lead", true );
								}
							} 
						} 
						?>
						<input type="checkbox" data-criteria="<?php echo $priority_squished; ?>" data-metroid="<?php echo $metro_id; ?>" <?php if( $selected_priority > 0 ) echo 'checked'; ?> />
						<?php 
						
						//add link next to checkbox (Priority properties, incl: Staff lead, Volunteer champion
						if ( $selected_priority > 0 ) {
							$hidden = '';
						} else {
							$hidden = 'hidden';
						}
						
						echo "<a class='priority_staff_link alignright " . $hidden . "' data-criteria='" . $priority_squished . "'>Edit Staff Assignments<br>& View Resources</a>";
						
						?>
					</td>
				</tr>
				
				<tr class="priority_staff_select hidden shaded" data-criteria="<?php echo $priority_squished; ?>" data-impact="<?php echo $revenue_name; ?>" >
					<td colspan="1"></td>
					<td>Select Staff Lead:</td>
					<td><select class="staff_partner" name="staff_partner" data-criteria="<?php echo $priority_squished; ?>" >
					<?php 
						//echo $selected_staff_partner;
						foreach ( $group_members as $key => $value ) {
						if( $selected_staff_partner == (int)$key ) {
							$selected = "selected"; 
						} else {
							$selected = "";
						}
						$option_output = '<option value="';
						$option_output .= $key;
						$option_output .= '"' . $selected . '>';
						$option_output .= $value;
						$option_output .= '</option>';
						print $option_output;
						
					} ?>
					</select></td>
				</tr>
				<tr class="priority_volunteer_select hidden shaded" data-criteria="<?php echo $priority_squished; ?>" data-impact="<?php echo $revenue_name; ?>" >
					<td colspan="1"></td>
					<td>Select Volunteer Champion:</td>
					<td><select class="volunteer_lead" name="staff_partner" data-criteria="<?php echo $priority_squished; ?>" data-impact="<?php echo $revenue_name; ?>" >
					<?php foreach ( $group_members as $key => $value ) {
						if( $selected_volunteer_lead == (int)$key ) {
							$selected = "selected"; 
						} else {
							$selected = "";
						}
						$option_output = '<option value="';
						$option_output .= $key;
						$option_output .= '"' . $selected . '>';
						$option_output .= $value;
						$option_output .= '</option>';
						print $option_output;
						
					} ?>
					</select></td>
				</tr>
				<tr class="priority_staff_save hidden shaded" data-criteria="<?php echo $priority_squished; ?>" data-priorityid="<?php echo $selected_priority; ?>" >
					<!--<td colspan="1"></td>-->
					<td colspan="2"><a href="" class="alignleft resource-link">View Resources for <?php echo $revenue_section['label']; ?></td>
					<td>
						<span><a class="button submit_staff_partners ">Save Staff</a><div class="spinny"></div><div class="staff_save_message"></span>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>

<?php
}

/**
 * Produce the page code for the revenue pages of the summary.
 *
 * @since   1.0.0
 * @return  html - generated code
 */
function cc_aha_print_revenue_section_report( $metro_id, $slug ){
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
	
		<?php //to show discussion textfield ?
		
		//if( $section_key == 'event_leadership' || $section_key == 'sponsorship' || $section_key == 'donor_stewardship' ) { ?>
		<?php if ( cc_aha_user_can_do_assessment() && ! cc_aha_on_analysis_complete_report_screen() ) : ?>
			<form id="aha_summary-revenue-<?php echo $section_key; ?>" class="standard-form aha-survey" method="post" action="<?php echo cc_aha_get_home_permalink() . 'update-summary/'; ?>">
		<?php endif; ?>
			
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
		 		<?php if ( cc_aha_user_can_do_assessment() && ! cc_aha_on_analysis_complete_report_screen() ) : ?>		
					<input type="hidden" name="metro_id" value="<?php echo $metro_id; ?>">
					<input type="hidden" name="revenue-section" value="revenue-<?php echo $section; ?>">
					<?php wp_nonce_field( 'cc-aha-assessment', 'set-aha-assessment-nonce' ) ?>
					
					<?php $radio_checked = $data[$section_key . '-top-3']; ?>
					<label for="<?php echo $section_key . '-top-3'; ?>"><p>Based on your preliminary discussions, do you think this may be a top 3 revenue impact opportunity for your board?</p>
					<label><input type="radio" value="1" name="board[<?php echo $section_key . '-top-3'; ?>]" <?php checked( $radio_checked, 1 ) ; ?>> Yes</label>
					<label><input type="radio" value="0" name="board[<?php echo $section_key . '-top-3'; ?>]" <?php checked( $radio_checked, 0 ); ?>> No</label>
						
					<div class="form-navigation clear">
						<div class="submit">
							<input type="submit" name="submit-revenue-analysis-to-toc" value="Save, Return to Table of Contents" id="submit-revenue-analysis-to-toc">
						</div>
					</div>
				</form>
				<?php elseif ( cc_aha_user_can_do_assessment() && cc_aha_on_analysis_complete_report_screen() ) : ?>
					<p>Based on your preliminary discussions, do you think this may be a top 3 revenue impact opportunity for your board? <em><?php echo $data[$section_key . '-top-3'] ? 'Yes' : 'No'; ?></em></p>

				<?php elseif ( ! cc_aha_on_analysis_complete_report_screen() ) : ?>
					<?php if ( bp_action_variable( 3 ) ) : ?>
						<div class="form-navigation clear">
							<a href="<?php echo cc_aha_get_analysis_permalink( bp_action_variable( 2 ) ); ?>" class="button">Return to <?php echo ucwords( bp_action_variable( 2 ) ); ?> Analysis Summary</a>
						</div>
					<?php endif; ?>
				<?php endif; ?>
		<?php /*} else { ?>
		
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
			
		<?php } */ ?>
	</section> <!-- #revenue-section -->
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
			<label for="revenue-7.1-1">What companies are in your pipeline for recruitment (for next year and next 3 years)?
			<?php if ( cc_aha_user_can_do_assessment() && ! cc_aha_on_analysis_complete_report_screen() ) : ?>
				<textarea id="revenue-7.1-1" name="board[revenue-7.1-1]"><?php echo $data['revenue-7.1-1']; ?></textarea>
			<?php elseif ( cc_aha_user_can_do_assessment() && cc_aha_on_analysis_complete_report_screen() ) : ?>
				<p><em>Response:</em> <?php echo $data['revenue-7.1-1']; ?></p>
			<?php endif; ?>
		</fieldset>
		
		<fieldset>
			<label for="revenue-7.1-2">Who are the top 5 executives that are in your pipeline for Event Chair for the next year?
			<?php if ( cc_aha_user_can_do_assessment() && ! cc_aha_on_analysis_complete_report_screen() ) : ?>
				<textarea id="revenue-7.1-2" name="board[revenue-7.1-2]"><?php echo $data['revenue-7.1-2']; ?></textarea>
			<?php elseif ( cc_aha_user_can_do_assessment() && cc_aha_on_analysis_complete_report_screen() ) : ?>
				<p><em>Response:</em> <?php echo $data['revenue-7.1-2']; ?></p>
			<?php endif; ?>
		</fieldset>
		
		<fieldset>
			<label for="revenue-7.1-3">Who are the top 10 executives that are in your pipeline for Event Chair for the next three years?
			<?php if ( cc_aha_user_can_do_assessment() && ! cc_aha_on_analysis_complete_report_screen() ) : ?>
				<textarea id="revenue-7.1-3" name="board[revenue-7.1-3]"><?php echo $data['revenue-7.1-3']; ?></textarea>
			<?php elseif ( cc_aha_user_can_do_assessment() && cc_aha_on_analysis_complete_report_screen() ) : ?>
				<p><em>Response:</em> <?php echo $data['revenue-7.1-3']; ?></p>
			<?php endif; ?>
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
	
	<p><em>Please Note: This link will open in SharePoint where you will see a list of affiliate folders. Choose the one you belong to and find your offices analysis. There you will find summaries of many of the criteria in the revenue section. Keep this report and note the tabs summarizing different aspects of this assessment.</em></p>
	
	<p><?php echo $data['8.1.2'] ? 'Your ELT leadership has all industries for your area represented.' : 'Your ELT leadership does not have all industries for your area represented.'; ?>
	</p>
	
	<?php
}

function cc_aha_print_revenue_summary_top_25_companies( $metro_id ){
	
	$data = cc_aha_get_form_data( $metro_id );
	?>
	<h5>Current State</h5>
	<p>Find a link to your Top 25 report with summary <a href='http://sharepoint.heart.org/nat/Volunteerism/Community%20Planning%202014-2017/Forms/AllItems.aspx?RootFolder=%2Fnat%2FVolunteerism%2FCommunity%20Planning%202014%2D2017%2FCPP2%20Revenue%20Assessment%20Analysis&FolderCTID=0x012000CFD890B30E39714BB20EE7AD8D89525D&View={035A3458-0EA6-40A9-9276-2F7B89EA536B}' target='_blank'>here</a>.</p>
	
	<p><em>Please Note: This link will open in SharePoint where you will see a list of affiliate folders. Choose the one you belong to and find your offices analysis. There you will find summaries of many of the criteria in the revenue section. Keep this report and note the tabs summarizing different aspects of this assessment. </em></p>
	
	<p>Locate your affiliate and find your office from the list there.</p>
	
	<?php //TODO: get this link..

}

function cc_aha_print_revenue_summary_sponsorship( $metro_id ){
	
	$data = cc_aha_get_form_data( $metro_id );
	?>
	<h5>Current State</h5>

	<p>Find a report on your Platform and Signature sponsorships for the market’s events <a href='http://sharepoint.heart.org/nat/Volunteerism/Community%20Planning%202014-2017/Forms/AllItems.aspx?RootFolder=%2Fnat%2FVolunteerism%2FCommunity%20Planning%202014%2D2017%2FCPP2%20Revenue%20Assessment%20Analysis&FolderCTID=0x012000CFD890B30E39714BB20EE7AD8D89525D&View={035A3458-0EA6-40A9-9276-2F7B89EA536B}' target='_blank'>here</a>.</p>

	<p><em>Please Note: This link will open in SharePoint where you will see a list of affiliate folders. Choose the one you belong to and find your offices analysis. There you will find summaries of many of the criteria in the revenue section. Keep this report and note the tabs summarizing different aspects of this assessment. </em></p>
	
	<p>You listed the following companies as ones who place a focus on corporate social responsibility: <?php echo $data['9.4'] ? $data['9.4'] : '<em>none</em>'; ?></p>

	<h5>Discussion Questions</h5>
	<ul>
		<fieldset>
			<label for="revenue-10.1">Is the board involved in developing a pipeline for Platform and Signature sponsorship?
			<?php if ( cc_aha_user_can_do_assessment() && ! cc_aha_on_analysis_complete_report_screen() ) : ?>
				<textarea id="revenue-10.1" name="board[revenue-10.1]"><?php echo $data['revenue-10.1']; ?></textarea>
			<?php elseif ( cc_aha_user_can_do_assessment() && cc_aha_on_analysis_complete_report_screen() ) : ?>
				<p><em>Response:</em> <?php echo $data['revenue-10.1']; ?></p>
			<?php endif; ?>
		</fieldset>
		
		<fieldset>
			<label for="revenue-10.3">What other companies could be top prospects to join your existing Platform/Signature sponsors?
			<?php if ( cc_aha_user_can_do_assessment() && ! cc_aha_on_analysis_complete_report_screen() ) : ?>
				<textarea id="revenue-10.3" name="board[revenue-10.3]"><?php echo $data['revenue-10.3']; ?></textarea>
			<?php elseif ( cc_aha_user_can_do_assessment() && cc_aha_on_analysis_complete_report_screen() ) : ?>
				<p><em>Response:</em> <?php echo $data['revenue-10.3']; ?></p>
			<?php endif; ?>
		</fieldset>
		
		<fieldset>
			<label for="revenue-10.6">What can be done to bring those current sponsors up to the recommended level?
			<?php if ( cc_aha_user_can_do_assessment() && ! cc_aha_on_analysis_complete_report_screen() ) : ?>
				<textarea id="revenue-10.6" name="board[revenue-10.6]"><?php echo $data['revenue-10.6']; ?></textarea>
			<?php elseif ( cc_aha_user_can_do_assessment() && cc_aha_on_analysis_complete_report_screen() ) : ?>
				<p><em>Response:</em> <?php echo $data['revenue-10.6']; ?></p>
			<?php endif; ?>
		</fieldset>
		
		<fieldset>
			<label for="revenue-10.7">How is the metro market team working together to implement account management of Platform sponsors or potential Platform sponsors?
			<?php if ( cc_aha_user_can_do_assessment() && ! cc_aha_on_analysis_complete_report_screen() ) : ?>
				<textarea id="revenue-10.7" name="board[revenue-10.7]"><?php echo $data['revenue-10.7']; ?></textarea>
			<?php elseif ( cc_aha_user_can_do_assessment() && cc_aha_on_analysis_complete_report_screen() ) : ?>
				<p><em>Response:</em> <?php echo $data['revenue-10.7']; ?></p>
			<?php endif; ?>
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
	
	<?php if ( $fips = cc_aha_get_fips( $metro_id ) && false ) : ?>
	<div class="health-needs-container alignright">
		<div class="dial-container">
			<script src='http://maps.communitycommons.org/jscripts/mapWidget.js?geoid=<?php echo $fips; ?>&mapid=2406'></script>
		</div>
	</div>
	<?php endif; ?>

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
				<label for="revenue-13.1.6">Will you be developing a cultivation plan this year?
				<?php if ( cc_aha_user_can_do_assessment() && ! cc_aha_on_analysis_complete_report_screen() ) : ?>
					<textarea id="revenue-13.1.6" name="board[revenue-13.1.6]"><?php echo $data['revenue-13.1.6']; ?></textarea>
				<?php elseif ( cc_aha_user_can_do_assessment() && cc_aha_on_analysis_complete_report_screen() ) : ?>
					<p><em>Response:</em> <?php echo $data['revenue-13.1.6']; ?></p>
				<?php endif; ?>
			</fieldset>
		<?php } ?>
		
		<fieldset>
			<label for="revenue-13.1.5">Are you inviting top donors to all events in market?
			<?php if ( cc_aha_user_can_do_assessment() && ! cc_aha_on_analysis_complete_report_screen() ) : ?>
				<textarea id="revenue-13.1.5" name="board[revenue-13.1.5]"><?php echo $data['revenue-13.1.5']; ?></textarea>
			<?php elseif ( cc_aha_user_can_do_assessment() && cc_aha_on_analysis_complete_report_screen() ) : ?>
				<p><em>Response:</em> <?php echo $data['revenue-13.1.5']; ?></p>
			<?php endif; ?>
		</fieldset>
		
		<fieldset>
			<label for="revenue-13.1.7">Do you know who your top donors are?
			<?php if ( cc_aha_user_can_do_assessment() && ! cc_aha_on_analysis_complete_report_screen() ) : ?>
				<textarea id="revenue-13.1.7" name="board[revenue-13.1.7]"><?php echo $data['revenue-13.1.7']; ?></textarea>
			<?php elseif ( cc_aha_user_can_do_assessment() && cc_aha_on_analysis_complete_report_screen() ) : ?>
				<p><em>Response:</em> <?php echo $data['revenue-13.1.7']; ?></p>
			<?php endif; ?>
		</fieldset>
		
		<fieldset>
			<label for="revenue-13.1.8">Is there a way board members could engage top donors?
			<?php if ( cc_aha_user_can_do_assessment() && ! cc_aha_on_analysis_complete_report_screen() ) : ?>
				<textarea id="revenue-13.1.8" name="board[revenue-13.1.8]"><?php echo $data['revenue-13.1.8']; ?></textarea>
			<?php elseif ( cc_aha_user_can_do_assessment() && cc_aha_on_analysis_complete_report_screen() ) : ?>
				<p><em>Response:</em> <?php echo $data['revenue-13.1.8']; ?></p>
			<?php endif; ?>
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
			'short_label' => 'Event Leadership',
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
			'short_label' => 'ELT Leadership',
			'slug' 	=> 'elt-leadership',
			'background' => 'The Best Practice for recruitment of the Executive Leadership Team (ELT)  is a critical element in the execution of successful fundraising events.  Ideally, these committees will be made up of at least fifteen members who can give corporately at one of the prescribed top levels of sponsorship, give a generous personal gift, and dedicate sufficient time to complete their responsibilities as a member of ELT.  These individuals should be recruited by the event Chair, in partnership with staff.  They are responsible for helping raise new and renewed corporate and individual revenue.',
			'outcome' => 'Grow event revenue through recruitment of ELT members.',
			'outcome-stats' => array(
				1	=> 'Recruit 15 or more ELT members for all three core events that are giving at the top four levels of sponsorship for the size market and securing other companies giving at the same level.'
				)
			),
		'top_25_companies' => array(
			'label' => 'Grow Top 25 Company Engagement',
			'short_label' => 'Top 25 Company',
			'slug' 	=> 'top-25-companies',
			'background' => 'Involvement from the top 25 corporations in markets is critical for the current and future success of fundraising events and for helping us &ldquo;meet people where they are&rdquo; with healthy living strategies.  Because of the larger number of employees involved, there is greater likelihood for employee involvement in different AHA events, and the greatest financial potential for corporate giving.  This corporate segment gives a pipeline for recruitment and source for new company development as well as provides depth with current partners due to size and scope.',
			'outcome' => 'Board demonstrates meaningful contributions from top 25 employers through leadership/CSuite involvement, fundraising, and health-related activities.',
			'outcome-stats' => array(
				1	=> 'Increase companies that raise over 100K to X.',
				2	=> 'Increase top 25 employers featuring AHA in their employee giving program from X to Y.'
				)
			),
		'sponsorship' => array(
			'label' => 'Secure Platform/Signature Sponsorship',
			'short_label' => 'Platform / Signature Sponsorship',
			'slug' 	=> 'sponsorship',
			'background' => 'The recruitment of renewed and additional top level partners each year for events are critical to the fundraising success of corporate events.  These partners bring year-round opportunities for partnering with AHA/ASA in our mission activation and helping people in our communities become healthier.',
			'outcome' => 'Increase annual revenue through Platform/Signature sponsors in market (sponsorship levels at bottom of document).',
			'outcome-stats' => array(
				1	=> 'Increase Platform/Signature sponsors in market to X'
				)
			),
		'youth_market' => array(
			'label' => 'Expand Youth Market Efforts',
			'short_label' => 'Youth Market',
			'slug' 	=> 'youth-market',
			'background' => 'The renewal and recruitment of new schools is critical to implementing the strategies of the Youth Market program.  Recruiting the superintendent gives top-level collaboration to influence and recruit throughout the system,  including district employees and school leadership.  This partnership results in more individuals: employees as well as students, fundraising for the AHA and being touched by critical messaging for heart health.',
			'outcome' => 'Increase the number of schools participating in AHA health and revenue activities.',
			'outcome-stats' => array(
				1	=> 'Recruit Superintendent who sets a district wide goal and actively engages all the schools in the district with students, faculty & all district wide employees.'
				)
			),
		'individual_giving' => array(
			'label' => 'Increase Individual Giving',
			'short_label' => 'Individual Giving',
			'slug' 	=> 'individual-giving',
			'background' => 'Increasing the number of donors who have the capacity to make a $100,000+ gift, as well as increasing the acquisition and retention of Cor Vitae Society Members ($5,000+ annually) will help further the mission of AHA/ASA.  These measurements are consistent with our commitment to our Guiding Values and directly correlate with &ldquo;Making an Extraordinary Impact&rdquo; and &ldquo;Inspiring Passionate Commitment&rdquo;.  By increasing revenue from Cor Vitae Society Members, as well as those already giving at high levels, local board members will also be impacting other Guiding Values such as &ldquo;Bringing Science to Life&rdquo;, &ldquo;Improving and Extending People’s Lives&rdquo;, and &ldquo;Ensuring Equitable Health for All&rdquo;.',
			'outcome' => 'Increase pipeline of potential individual donors that have the ability to give $100,000+ gifts.',
			'outcome-stats' => array(
				1	=> 'Increase prospects that have the ability of giving a gift of $100,000 or more from X to Y.',
				2	=> 'Retain at least X Cor Vitae members.'
				)
			),
		'donor_stewardship' => array(
			'label' => 'Enhance Donor Stewardship',
			'short_label' => 'Donor Stewardship',
			'slug' 	=> 'donor-stewardship',
			'background' => 'A consistent, strategic Stewardship plan is the cornerstone for building engagement with any mission-centered organization.  In order to help meet the AHA’s 2020 Impact Goal, organization-wide standards and practices are being implemented to provide consistent, meaningful stewardship and engagement opportunities for donors and volunteers.  National and Affiliate staff and volunteers will partner to implement a comprehensive Stewardship plan in order to: <ul><li>Advance our strategic priorities</li><li>Support annual and long-range fundraising goals</li><li>Increase donor retention</li><li>Create inspiring opportunities to engage our donors and volunteers for the long-term</li>',
			'outcome' => 'Retain donors through robust engagement and stewardship activities.',
			'outcome-stats' => array(
				1	=> 'Increase stewardship activities (thank you calls, personal notes, invitations to lunches, dinners, events) in order to foster ongoing interest and engagement in AHA’s mission.'
				)
			),
		'pdw_legacy' => array(
			'label' => 'Membership in the Paul Dudley White Legacy Society',
			'short_label' => 'Paul Dudley Society',
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

