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
 * Produce the page code for the summary page, based on the survey and other info.
 *
 * @since   1.0.0
 * @return  html - generated code
 */
function cc_aha_render_summary_page(){
	if ( ! $metro_id = cc_aha_resolve_summary_metro_id() )
		return false;

	// TODO: This must be removed before the summaries are made public.
	if ( ! cc_aha_user_can_do_assessment() && ! cc_aha_user_has_super_secret_clearance() ) {
		echo '<p class="info">Sorry, you do not have permission to view this page.</p>';
		return;
	}

	// Get the data for this metro ID
	$data = cc_aha_get_form_data( $metro_id );
	$school_data = cc_aha_get_school_data( $metro_id );
	
	// Do some math to figure out what's what.

	// Match Yan's dial gauge colors
	$red = '#FF0A17';
	$green = '#78B451';
	$yellow = '#FCA93C';

	$cleanedfips = cc_aha_get_fips( true );	
	
	?>
	<div id="summary-navigation">
		<ul class="horizontal no-bullets">
			<!-- <li><a href="#tobacco" class="tab-select">Tobacco</a></li>
			<li><a href="#physical-activity" class="tab-select">Physical Activity</a></li>
			<li><a href="#healthy-diet" class="tab-select">Healthy Diet</a></li>
			<li><a href="#chain-of-survival" class="tab-select">Chain of Survival</a></li> -->
			<?php if ( bp_action_variable( 3 ) ) : ?>
				<li><a href="<?php echo cc_aha_get_analysis_permalink( bp_action_variable( 2 ) ); ?>" class="button">Return to <?php echo ucwords( bp_action_variable( 2 ) ); ?> Analysis Summary</a></li>
			<?php endif; ?>
			<li class="alignright">
			<?php if ( ! empty( $cleanedfips ) ) { ?>
				<a href="http://assessment.communitycommons.org/CHNA/OpenReport.aspx?reporttype=AHA&areatype=county&areaid=<?php echo $cleanedfips; ?>" target="_blank" class="button">View Data Report</a>
			<?php } else { ?>	
				<a href="http://assessment.communitycommons.org/CHNA/selectarea.aspx?reporttype=AHA " target="_blank" class="button">View Data Report</a>				
			<?php } ?>	
				&emsp;</li>
		</ul>
		<!-- <input type="button" class="button" value="Print Summary" /> -->
	</div>
	<?php
	// bp action variables tell us where we are.
	// [1] is the metro_id
	// [2] is section (health or revenue)
	// [3] is the impact area
	$major_section = bp_action_variable( 2 );
	if ( $major_section == cc_aha_get_analysis_health_slug() ){
		if ( ! bp_action_variable( 3 ) ) {
			cc_aha_print_single_report_card_health( $metro_id );
		} else if ( bp_action_variable( 3 ) == 'environmental-scan' ) {
			cc_aha_print_environmental_scan( $metro_id );
		} else {
			cc_aha_print_impact_area_report( $metro_id, bp_action_variable( 3 ), bp_action_variable( 4 ) );
		}
	}  else if ( $major_section == cc_aha_get_analysis_revenue_slug() ) {
		if ( ! bp_action_variable( 3 ) ) {
			cc_aha_print_single_report_card_revenue( $metro_id );
		} else { 
			cc_aha_print_revenue_section_report( $metro_id, bp_action_variable( 3 ) );
		}
	} 
	?>
	
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$(".dial").knob();
		},(jQuery))
	</script>
	<?php
}
// Major template pieces
function cc_aha_print_single_report_card_health( $metro_id = 0 ) {
	$data = cc_aha_get_form_data( $metro_id ); 
	?>

	<h2 class="screamer">HEALTH ANALYSIS REPORT</h2>
	<h3><?php cc_aha_print_environmental_scan_link( $metro_id ); ?></h3>

	<section id="single-report-card-health" class="clear">
		<?php // Building out a table of responses for one metro
		$sections = cc_aha_get_summary_sections( $metro_id );
		?>
		<h3>Community Health Assessment Analysis</h3>
		<table>
			<thead>
				<tr>
					<th>Impact Area</th>
					<th>Healthy Community Criteria</th>
					<!-- <th>Health Need</th> -->
					<th><a href="http://sharepoint.heart.org/nat/Volunteerism/Community%20Planning%202014-2017/Healthy%20Comm.%20Criteria%208-29-14%20Color.docx" target="_blank">Score</th>
					<th>Top 3 Priority</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					foreach ($sections as $section_name => $section_data) { 
						echo '<tr class="summary-table-section"><td colspan=5>' . $section_data['label'] . '</td></tr>';
						
						foreach ( $section_data['impact_areas'] as $impact_area_name => $impact_area_data ) {
							foreach ( $impact_area_data['criteria'] as $crit_key => $criteria_data ) {
							?>
							<tr>
								<?php if ( $crit_key == 1 ) : ?>
								<td rowspan=<?php echo count( $impact_area_data['criteria'] ); ?>>
									<a href="<?php echo cc_aha_get_analysis_permalink() . $section_name . '/' . $impact_area_name; ?>">
									<?php echo $impact_area_data['label']; ?>
									</a>
								</td>
							<?php endif; ?>
								<td>
									<?php echo $criteria_data['label']; ?>
								</td>
								<!-- <td>
									Maybe a gauge goes here.
								</td> -->
								<td class="<?php echo cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key ); ?>">
									<?php cc_aha_print_dial_label( cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key ) ); ?>
								</td>
								<td>
									<?php echo $data[$section_name . '-' . $impact_area_name . '-' . $crit_key . '-top-3'] ? 'Yes' : 'No'; ?>
								</td>
							</tr>

							<?php
							}
						}
					}
				?>
			</tbody>
		</table>

	<p><a href="http://sharepoint.heart.org/nat/Volunteerism/Community%20Planning%202014-2017/Healthy%20Comm.%20Criteria%208-29-14%20Color.docx" target="_blank">Learn more about the scoring methodology</a></p>
	</section>
	<?php 
}

function cc_aha_print_impact_area_report( $metro_id, $section, $impact_area ) {
	
	//to populate response fields if filled out already
	$data = cc_aha_get_form_data( $metro_id );
	?>

	<section id="<?php echo $impact_area; ?>" class="clear">
		<form id="aha_summary-<?php echo $section . '-' . $impact_area; ?>" class="standard-form aha-survey" method="post" action="<?php echo cc_aha_get_home_permalink() . 'update-summary/'; ?>">
		
		<h2 class="screamer"><?php echo cc_aha_get_summary_impact_area_title( $section, $impact_area ); ?></h2>

		<?php 
		$dial_ids = cc_aha_get_summary_impact_area_widgets( $section, $impact_area );
		$fips = cc_aha_get_fips();

		// Show the top section if there's something to show.
		if ( $dial_ids && $fips ) :
			// TODO: Real title needed
		?>
		<h3>Health Needs Indicators</h3>
		<div class="content-row">
			<?php 
			$dial_ids = cc_aha_get_summary_impact_area_widgets( $section, $impact_area );
			$fips = cc_aha_get_fips();
			foreach ( $dial_ids as $dial_id) {
				//TODO: Could there be more than two widgets? YES! like 6!
				?>
				<div class="half-block">
					<script src='http://maps.communitycommons.org/jscripts/dialWidget.js?geoid=<?php echo $fips; ?>&id=<?php echo $dial_id; ?>'></script>
				</div>
				<?php
			}
			?>
		</div>
	<?php endif; // if ( $dial_ids && $fips ) : 

	?>

	<?php
	// Next, we loop through our criteria
	$criteria = cc_aha_get_impact_area_criteria( $section, $impact_area );
	foreach ( $criteria as $crit_key => $criterion ) {
		?>
		<h3><?php echo $criterion['label']; ?> </h3>
		<div class="content-row">
			<div class="third-block clear">
				<?php // Get a big dial
					cc_aha_print_dial( cc_aha_section_get_score( $section, $impact_area, $crit_key ) );
				?>
			</div>

			<div class="third-block spans-2">
				<?php if( cc_aha_get_summary_introductory_text( $section, $impact_area, $crit_key ) != '' ) { 
					//since at least one of our sections - care_acute_1 is a dial only.. 
					?>
					<p><strong>Background: </strong><?php echo cc_aha_get_summary_introductory_text( $section, $impact_area, $crit_key ); ?></p>
				<?php } ?>
				<?php // Mayhaps we can loop through these.
				if ( $criterion[ 'auto_build' ] ) {
					// Get the questions for this criterion
					$statements = cc_aha_get_questions_for_summary_criterion( $criterion = null );
					?>
					<ul>
						<?php
						foreach ( $statements as $statement ) {
							?>
							<li><?php cc_aha_print_summary_statement( $statement, $data ); ?></li>
							<?php
						}
						?>
					<ul>
					<?php 
				} else {
					// We'll refer to the "handbuilt" code for this criterion.
					$function_name = 'cc_aha_print_criterion_' . $section . '_' . $impact_area . '_' . $crit_key;
					if ( function_exists( $function_name ) ) {
						$function_name( $metro_id );
					} else {
						// TODO: Remove this debugging code.
						echo "no function by the name: " . $function_name;
					}
				}

				 ?>

		 		<fieldset>
		 			<?php 
		 			$input_prefix = $section . '-' . $impact_area . '-' . $crit_key;
		 			
					if( $input_prefix != 'care-acute-1' ) { //because this one has no discussion questions.. ?>
						<textarea id="<?php echo $input_prefix . '-open-response'; ?>" name="board[<?php echo $input_prefix . '-open-response'; ?>]"><?php echo $data[$input_prefix . '-open-response']; ?></textarea>
					<?php } ?>
					
					<?php if ( ( $input_prefix != 'care-acute-1' ) && ( $input_prefix != 'care-acute-2' ) ) { ?>
						<?php $radio_checked = $data[$section . '-' . $impact_area . '-' . $crit_key . '-top-3']; ?>
						<label for="<?php echo $section . '-' . $impact_area . '-' . $crit_key . '-top-3'; ?>"><p>Based on your preliminary discussions, do you think this may be a top 3 health impact opportunity for your board?</p>
						<label><input type="radio" value="1" name="board[<?php echo $section . '-' . $impact_area . '-' . $crit_key . '-top-3'; ?>]" <?php checked( $radio_checked, 1 ) ; ?>> Yes</label>
						<label><input type="radio" value="0" name="board[<?php echo $section . '-' . $impact_area . '-' . $crit_key . '-top-3'; ?>]" <?php checked( $radio_checked, 0 ); ?>> No</label>
					
					<?php } else if ( $input_prefix == 'care-acute-2' ) { 
						//display Top 3 radios for BOTH carse_acute_1 and cares_acute 2 in cares_acute 2 section ?>
						<?php $radio_checked = $data['care-acute-1-top-3']; ?>
						<label for="<?php echo 'care-acute-1-top-3'; ?>"><p>Based on your preliminary discussions, do you think addressing TOTAL CVD DISCHARGES from CMS penalty hospitals may be a top 3 health impact opportunity for your board?</p></label>
						<label><input type="radio" value="1" name="board[<?php echo 'care-acute-1-top-3'; ?>]" <?php checked( $radio_checked, 1 ) ; ?>> Yes</label>
						<label><input type="radio" value="0" name="board[<?php echo 'care-acute-1-top-3'; ?>]" <?php checked( $radio_checked, 0 ); ?>> No</label>
					
						<?php $radio_checked = $data['care-acute-2-top-3']; ?>
						<label for="<?php echo 'care-acute-2-top-3'; ?>"><p>Based on your preliminary discussions, do you think addressing UNDERSERVED CVD DISCHARGES from CMS penalty hospitals may be a top 3 health impact opportunity for your board?</p></label>
						<label><input type="radio" value="1" name="board[<?php echo 'care-acute-2-top-3'; ?>]" <?php checked( $radio_checked, 1 ) ; ?>> Yes</label>
						<label><input type="radio" value="0" name="board[<?php echo 'care-acute-2-top-3'; ?>]" <?php checked( $radio_checked, 0 ); ?>> No</label>
					
					<?php } ?>
				</fieldset>
			</div>
		</div>

<?php
	}
	?>
    <input type="hidden" name="analysis-section" value="<?php echo bp_action_variable( 2 ); ?>">
	<input type="hidden" name="metro_id" value="<?php echo $metro_id; ?>">
	<input type="hidden" name="section-impact-area" value="<?php echo $section . '-' . $impact_area; ?>">
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
 * Individual criterion outputs that are too complicated for auto building
 * 
 * @since   1.0.0
 * @return  html
 */
function cc_aha_print_criterion_community_tobacco_1( $metro_id ) {
	$counties = cc_aha_get_county_data( $metro_id );
	$data = cc_aha_get_form_data( $metro_id );
	
	$section = 'community';
	$impact_area = 'tobacco';
	$group = 'community_tobacco_1';
	
	?>
	<h5>Current Status</h5>
	<h6>Smoke free air regulations covering restaurants, bars, and workplaces</h6>
	
	<table>
		<thead>
			<tr>
				<th></th>
				<th>Restaurants</th>
				<th>Bars</th>
				<th>Workplaces</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>State of <?php echo $data['State']; ?></td>
				<td><?php echo $data['1.1.1.1'] ? 'Yes' : 'No'; ?></td>
				<td><?php echo $data['1.1.1.2'] ? 'Yes' : 'No'; ?></td>
				<td><?php echo $data['1.1.1.3'] ? 'Yes' : 'No'; ?></td>
			</tr>
			<?php 
			foreach ( $counties as $county ) {
				?>
				<tr>
					<td><?php echo $county['County_Name']; ?></td>
					<td><?php echo $county['smokefree_restaurants_state_local'] ? 'Yes' : 'No'; ?></td>
					<td><?php echo $county['smokefree_bars_state_local'] ? 'Yes' : 'No'; ?></td>
					<td><?php echo $county['smokefree_workplaces_state_local'] ? 'Yes' : 'No'; ?></td>
				</tr>
				<?php
			} ?>
			<tr>
					<td>Total % of Population Covered in Board Territory</td>
					<td><?php echo $data['1.1.2.2'] . '%'; ?></td>
					<td><?php echo $data['1.1.2.3'] . '%'; ?></td>
					<td><?php echo $data['1.1.2.4'] . '%';  ?></td>
				</tr>
		</tbody>
	</table>

	<p><em>In order to earn a <span class='healthy'>“Healthy”</span> score in this category a community be 100% covered by clean indoor air legislation in all restaurants/bars/workplaces.</em></p>
	
	<h5>Policy Landscape</h5>
	<ul>
		<?php if ( $data['1.1.2.1'] != '' ) : ?>
			<li>Your state <?php echo ( $data['1.1.2.1'] ) ? 'does' : 'does not'; ?> preempt local communities from adopting their own clean indoor air laws.</li>
		<?php endif; ?>
		<li><?php 
		if ( ! $data['1.1.1.4'] || $data['1.1.3.1'] == 'neither' ) {
			echo 'Preliminary analyses indicate that this is not a viable issue at this time.';
		} else {
			echo 'Given the current political/policy environment, we envision smoke­free air public policy change will most likely occur at the ' . $data['1.1.3.1'] . 'level potentially in ' . $data['1.1.1.4'] . '.'; 
		}
		?></li>
	</ul>

	<h5>Discussion Questions</h5>
	<strong>Is this a priority at the state level?</strong>
	<ul>
		<li>Are there state legislators from this community who are key targets that we need to influence for supporting a state-wide campaign?</li>
		<li>Do the board members or other AHA volunteers have relationships with these key targets and legislators?</li>
		<li>Do the board members have relationships with key opinion leaders (key business leaders, members of the media, etc.) with the community to help support this effort?</li>
		<li>What’s the current level of grassroots activity in the community to support this effort?</li>
	</ul>
	<strong>Does the community have capacity to take on this issue?</strong>
	<ul>
		<li>What potential coalition partners (such as ACS/ALA, local hospitals, medical associations, etc.) and community partners (neighborhood groups, PTO, church groups, etc.) are in place?  </li>
		<li>What is the current political climate?</li>
		<li>What is the likelihood of the current council support for clean indoor air law?  </li>
		<li>Do the board members and other AHA volunteers have the capacity to lead and fully engage in this campaign?</li>
		<li>Is there any external funding available to do the work?  (ex. Community Transformation Grants, etc.)</li>
	</ul>
	
	<?php 

}

function cc_aha_print_criterion_community_tobacco_2( $metro_id ) {
	$counties = cc_aha_get_county_data( $metro_id );
	$data = cc_aha_get_form_data( $metro_id );
	?>
	<h5>Current Status</h5>
	<p>Your current state tobacco excise tax is $<?php echo $data['1.2.1.1']; ?> and your local excise tax rate (for the largest municipality in your area) is $<?php echo ( $data['1.2.2.1'] ) ? $data['1.2.2.1'] : 0 ; ?> for a total effective tax rate of $<?php 
	// echo money_format('%i', $data['1.2.1.1'] + $data['1.2.2.1'] ); // money_format fails on dev
		echo $data['1.2.1.1'] + $data['1.2.2.1']; 

	?>.</p>
	<p><em>For a community to earn a <span class='healthy'>“Healthy”</span> score, they must have an effective state plus local tobacco excise tax rate greater than or equal to $1.85 per pack.</em></p>
	
	<h5>Policy Landscape</h5>
	<ul>
		<li><?php echo $data['State']; ?> <?php echo $data['1.2.2.2'] ? 'does' : 'does not'; ?> allow local communities to levy tobacco excise taxes locally.</li>
		<li><?php 
		if ( ! $data['1.2.1.2'] || $data['1.2.3.1'] == 'neither' ) {
			echo 'Preliminary analyses indicate that this is not a viable issue at this time.';
		} else { //TODO - Mel thinks 1.2.3.1 and 1.2.1.2 should be swapped, based on data (email pending to Ben)
			echo 'Given the current political/policy environment, we envision tobacco excise tax public policy change will most likely occur at the ' . $data['1.2.3.1'] . ' level potentially in ' . $data['1.2.1.2']; 
		}
		?></li>
	</ul>

	<h5>Discussion Questions</h5>
	<strong>Does the community have capacity to take on this issue?</strong>
	<ul>
		<li>What potential coalition partners are in place?  </li>
		<li>What is the current political climate?</li>
		<li>Do the volunteers have the needed skills?</li>
		<li>Is there any external funding available to do the work?  (ex. Community Transformation Grants,  etc.)</li>
	</ul>
	<?php 

}

function cc_aha_print_criterion_community_phys_1( $metro_id ) {
	$complete_streets = cc_aha_get_complete_streets_data( $metro_id );
	$data = cc_aha_get_form_data( $metro_id );
	?>
	<h5>Current Status</h5>
	<p>Your community is <?php 
		if ( $data['2.3.1.1'] == "meets guidelines" ) {
			echo 'covered by a Complete Streets policy that meets AHA guidelines';
		} else if ( $data['2.3.1.1'] == "below guidelines" ) {
			echo 'covered by a Complete Streets policy that is below AHA guidelines';
		} else {
			echo 'not covered by a Complete Streets policy';
		}

	?>.</p>
	<p><em>In order to earn a <span class='healthy'>“Healthy”</span> score more than 50% of your community’s population must be covered by Complete Streets policies which meet AHA’s guidelines.</em></p>

	<?php if ( ! empty( $complete_streets ) ) : ?>
	<p>The following Complete Streets policies are in effect in your area:</p>
	<table>
		<thead>
			<tr>
				<th></th>
				<th>Geography Level</th>
				<th>Policy Name</th>
				<th>Policy Year</th>
				<th>Policy Score *</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ( $complete_streets as $entry ) {
				?>
				<tr>
					<td><?php echo $entry['geog_name']; ?></td>
					<td><?php echo $entry['geog_level']; ?></td>
					<td><?php echo $entry['policy']; ?></td>
					<td><?php echo $entry['year']; ?></td>
					<td><?php echo $entry['score']; ?></td>

				</tr>
				<?php
			} ?>
		</tbody>
	</table>
	<p><em>*Source: <a href="http://www.smartgrowthamerica.org/documents/best-complete-streets-policies-of-2013.pdf" target="_blank">http://www.smartgrowthamerica.org/documents/best-complete-streets-policies-of-2013.pdf</a>
	<?php endif; // if ( ! empty( $complete_streets ) )  ?>

	<h5>Policy Landscape</h5>
	<ul>
		<li>There <?php echo $data['2.3.2.1'] ? 'is' : 'is not'; ?> a state, regional or local complete streets policy under consideration.
			<?php if ( $data['2.3.2.1'] ) : ?>
				<ul>
					<li><?php echo $data['2.3.2.2']; ?> is leading the effort.</li>
				</ul>
			<?php endif; ?>
		</li>
		<li><?php
		if ( ! $data['2.3.3'] || $data['2.3.3']  == 'neither' ) {
			echo 'Preliminary analyses indicate that this is not a viable issue at this time.';
		} else if ( $data['2.3.3']  == 'state and local' ) {
			echo 'Given the current political/policy environment, we envision complete streets policy will most likely occur at the state and local level. We expect to see state level policy potentially in ' . $data['2.3.1.2'] . ' and local level policy in ' . $data['2.3.1.3'];
		} else {
			echo 'Given the current political/policy environment, we envision complete streets policy will most likely occur at the ' . $data['2.3.3'] . ' level potentially in ';
			echo  ( $data['2.3.1.2'] == 'state') ? $data['2.3.1.2'] : $data['2.3.1.3'] ;
			echo '.';
		}
		?></li>
	</ul>

	<h5>Discussion Questions</h5>
	<strong>Does the community have capacity to take on this issue?</strong>
	<ul>
		<li>What potential coalition partners are in place?</li>
		<li>Who would pass a Complete Streets Policy at the local level – i.e. Mayor’s Office, City Council, Zoning Authority, other?</li>
		<li>What is the current political climate?</li>
		<li>Does AHA have volunteers that are involved in this effort?</li>
		<li>Do the board members and other AHA volunteers have the capacity to lead and fully engage in this campaign?  </li>
		<li>Is there any external funding available to do the work?  (ex. Community Transformation Grants, Voices for Healthy Kids, etc.)</li>
		<li>What is the current level of grassroots activity in the community to support this effort?</li>
	</ul>
	<?php 

}

function cc_aha_print_criterion_community_diet_1( $metro_id ) {
	$data = cc_aha_get_form_data( $metro_id );
	?>
	<h5>Current Status</h5>
	<ul>
		<li>There are <?php 
			if ( $data['3.3.3.1'] == 'yes - exceed AHA standards' ) {
				echo 'vending and/or service policies that meet or exceed AHA standards';
			} else if ( $data['3.3.3.1'] == 'yes - below AHA standards' ) {
				echo 'vending and/or service policies below AHA’s standards';
			} else {
				echo 'not vending and/or service policies';
			}

			?> in place in this community.
			<?php if ( $data['3.3.3.1'] == 'yes - exceed AHA standards' || $data['3.3.3.1'] == 'yes - below AHA standards' ) : ?>
						<ul>
							<li>Those cities and counties are <?php echo $data['3.3.3.2']; ?>.</li>
						</ul>
			<?php endif; ?>
		</li>
		<li><?php echo $data['State']; ?> <?php echo $data['3.3.1.1'] ? 'does' : 'does not'; ?> currently have nutrition standards related to state level food and beverage vending policy.</li>
		<li><?php echo $data['State']; ?> <?php echo $data['3.3.1.2'] ? 'does' : 'does not'; ?> currently have food and beverage procurement service policies for all state agencies.</li>
	</ul>
	<p><em>For a community to earn a <span class='healthy'>“Healthy”</span> score, the local government must adopt procurement policy for vending AND service contracts in accordance with AHA’s standards.</em></p>
		

	<h5>Policy Landscape</h5>
	<ul>
		<li><?php
		if ( ! $data['3.3.4'] || $data['3.3.4']  == 'neither' ) {
			echo 'Preliminary analyses indicate that this is not a viable issue at this time.';
		} else if ( $data['3.3.4']  == 'state' ) {
			echo 'Given the current political/policy environment, we envision food and beverage vending and/or procurement service policy change will most likely occur at the state level.';
		} else { // "local" or "state and local"
			echo 'Given the current political/policy environment, we envision food and beverage vending and/or procurement service policy change will most likely occur at the ' . $data['3.3.4'] . ' level.';
			if ( $data['3.3.2.1'] || $data['3.3.2.2'] ) {
				?>
				<ul>
					<?php 
					if ( $data['3.3.2.1'] ) {
						?>
						<li>We anticipate that food and beverage <em>vending</em> policies will be passed in this community in <?php echo $data['3.3.2.1']; ?></li>
						<?php
					}
					?>
					<?php 
					if ( $data['3.3.2.2'] ) {
						?>
						<li>We anticipate that food and beverage <em>procurement service</em> policies will be passed in this community in <?php echo $data['3.3.2.2']; ?></li>
						<?php
					}
					?>
				</ul>
				<?php
			}
		}
		?></li>
	</ul>

	<h5>Discussion Questions</h5>
	<strong>Does the community have capacity to take on this issue?</strong>
	<ul>
		<li>What potential coalition partners are in place?</li>
		<li>What is the current political climate?</li>
		<li>Has your mayor and/or govt. leadership expressed interest in this topic&mdash;in providing healthy food options for city employees, visitors to city buildings, and custodial populations within their care (i.e., inmates)?</li>
		<li>Has the local AHA office adopted the <a href="http://www.heart.org/foodwhereur" target="_blank">AHA’s Healthy Workplace Guidelines</a>?</li>
		<li>Do the board members and other AHA volunteers have the capacity to lead and fully engage in this campaign?  </li>
		<li>Is there any external funding available to do the work?  (ex. Community Transformation Grants, Voices for Healthy Kids, etc.)</li>
		<li>Within the community who has the authority (mayor, city manager, city council or city procurement officer) to pass these policies?</li>
	</ul>
	<?php 
}

function cc_aha_print_criterion_community_diet_2( $metro_id ) {
	$data = cc_aha_get_form_data( $metro_id );
	?>
	<h5>Current Status</h5>
	<ul>
		<li>There are currently no sugar sweetened beverage tax policies in place in your community that meet AHA's guidelines.</li>
		<li>There is <?php 
			if ( $data['3.4.1'] == 'meets guidelines' ) {
				echo 'a state or local SSB tax policy proposed which meets our guidelines under consideration';
			} else if ( $data['3.4.1'] == 'below guidelines' ) {
				echo 'a state or local SSB tax policy proposed which does not meet our guidelines';
			} else {
				echo 'not a state or local SSB tax policy under consideration';
			}
			?>.</li>
	</ul>
	<p><em>For a community to earn a <span class="healthy">“Healthy”</span> score, the community must have a tax of more than one cent per ounce on sugar-sweetened beverages.</em></p>

	<h5>Policy Landscape</h5>
	<ul>
		<li>There <?php echo $data['3.4.2'] ? 'is' : 'is not'; ?> the ability to levy SSB taxes locally in <?php echo $data['State']; ?>.</li>
		<li><?php
		if ( ! $data['3.4.4'] || $data['3.4.4']  == 'neither' ) {
			echo 'Preliminary analyses indicate that this is not a viable issue at this time.';
		} else if ( $data['3.4.4']  == 'state and local' ) {
			echo 'Given the current political/policy environment, we envision sugar sweetened beverage tax policy will most likely occur at the state and local level.';
			if ( $data['3.4.3.1'] && $data['3.4.3.2'] ){
				echo 'We expect to see state level policy potentially in ' . $data['3.4.3.1'] . ' and local level policy in ' . $data['3.4.3.2'] . '.';
			}
		} else {
			echo 'Given the current political/policy environment, we envision sugar sweetened beverage tax policy will most likely occur at the ' . $data['3.4.4'] . ' level';
			if ( $data['3.4.4'] == 'state' && $data['3.4.3.1'] ) {
				echo 'potentially in ' . $data['3.4.3.1'] . '.';
			} else if ( $data['3.4.4'] == 'local' && $data['3.4.3.2'] ) {
				echo 'potentially in ' . $data['3.4.3.2'] . '.';
			}
		}
		?></li>
	</ul>

	<h5>Discussion Questions</h5>
	<strong>Does the community have capacity to take on this issue?</strong>
	<ul>
		<li>What potential coalition partners are in place?</li>
		<li>What is the current political climate?</li>
		<li>Do the volunteers have the needed skills?</li>
		<li>Is there any external funding available to do the work? (ex. Community Transformation Grants, Voices for Healthy Kids, etc.)</li>
	</ul>
	<?php 
}

function cc_aha_print_criterion_community_diet_3( $metro_id ) {
	$data = cc_aha_get_form_data( $metro_id );
	?>
	<h5>Current Status</h5>
	<ul>
		<li><?php echo $data['3.5.1'] ?>% of the population in your community has low or no access to healthy food outlets based on the CDC’s Modified Retail Food Environmental Index (mRFEI).</li>
	</ul>
	<p><em>For a community to earn a <span class='healthy'>“Healthy”</span> score, less than 46% of the population in your community must live in tracts with a low mRFEI score.</em></p>
	
	<h5>Policy Landscape</h5>
	<ul>
		<li>Your state or community <?php echo $data['3.5.2'] ? 'is' : 'is not'; ?> pursuing appropriations to establish or supplement a Healthy Food Financing Initiative program.</li>
		<?php if ( $data['3.5.3'] ) : ?>
			<li>We envision Healthy Food Financing policy change will most likely occur in <?php echo $data['3.5.3']; ?>.</li>
		<?php endif; ?>
	</ul>

	<h5>Discussion Questions</h5>
	<strong>Does the community have capacity to take on this issue?</strong>
	<ul>
		<li>What potential coalition partners are in place?</li>
		<li>What is the current political climate?</li>
		<li>Do the volunteers have the needed skills?</li>
		<li>Is there any external funding available to do the work? (ex. Community Transformation Grants, Voices for Healthy Kids, etc.)</li>
	</ul>
	<?php 
}

function cc_aha_print_criterion_school_phys_1( $metro_id ) {
	$data = cc_aha_get_form_data( $metro_id );
	$school_data = cc_aha_get_school_data( $metro_id );

	?>
	<h5>Current Status</h5>
	<ul>
		<li><?php echo cc_aha_top_5_school_pe_calculation( $metro_id, 'all' ); ?>% of students in your community’s top 5 school districts receive the AHA’s recommended amount of PE minutes.</li>
	</ul>
	<p><em>For a community to earn a <span class="healthy">“Healthy”</span> score, 100% school-age children in your top 5 largest school districts must have physical education requirement and implementation according to AHA’s guidelines.</em></p>
	
	<?php if ( ! empty( $school_data ) ) : ?>

	<h6>Top 5 Largest School Districts: PE Requirements Meet AHA’s Guidelines</h6>
	<table>
		<thead>
			<tr>
				<th></th>
				<th>Elementary PE (150 min/wk)</th>
				<th>Middle School PE (225 min/wk)</th>
				<th>High School PE (required for graduation)</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Total Percent of student covered in your community</th>
				<th><?php echo cc_aha_top_5_school_pe_calculation( $metro_id, 'elem' ); ?>%</th>
				<th><?php echo cc_aha_top_5_school_pe_calculation( $metro_id, 'midd' ); ?>%</th>
				<th><?php echo cc_aha_top_5_school_pe_calculation( $metro_id, 'high' ); ?>%</th>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td>State of <?php echo $data['State']; ?></td>
				<td><?php echo $data['2.1.1.1'] ? 'Yes' : 'No'; ?></td>
				<td><?php echo $data['2.1.1.2'] ? 'Yes' : 'No'; ?></td>
				<td><?php echo $data['2.1.1.3'] ? 'Yes' : 'No'; ?></td>
			</tr>
			<?php 
			foreach ( $school_data as $school ) {
				?>
				<tr>
					<td><?php echo $school['DIST_NAME']; ?></td>
					<td><?php //echo $entry['2.1.4.1.1'] ? 'Yes' : 'No'; ?>
						<?php if ( isset ( $school['2.1.4.1.1']) && $school['2.1.4.1.1'] != '' ) {
							echo $school['2.1.4.1.1'] ? 'Yes' : 'No'; 
						} else {
							echo 'Don\'t Know';
						} ?>
					</td>
					<td><?php //echo $entry['2.1.4.1.2'] ? 'Yes' : 'No'; ?>
						<?php if ( isset ( $school['2.1.4.1.2']) && $school['2.1.4.1.2'] != '' ) {
							echo $school['2.1.4.1.2'] ? 'Yes' : 'No'; 
						} else {
							echo 'Don\'t Know';
						} ?>
					</td>
					<td><?php //echo $entry['2.1.4.1.3'] ? 'Yes' : 'No'; ?>
						<?php if ( isset ( $school['2.1.4.1.3']) && $school['2.1.4.1.3'] != '' ) {
							echo $school['2.1.4.1.3'] ? 'Yes' : 'No'; 
						} else {
							echo 'Don\'t Know';
						} ?>
					</td>
				</tr>
				<?php
			} ?>
		</tbody>
	</table>
	<?php endif; // if ( ! empty( $complete_streets ) )  ?>

	<h5>Policy Landscape</h5>
	<?php
	$pe_levels = array( 
		array( 'label' => 'Elementary School PE', 'likelihood' => '2.1.3.1', 'local_time' => '2.1.2.2', 'state_time' => '2.1.2.1', 'name' => 'elementary' ),
		array( 'label' => 'Middle School PE', 'likelihood' => '2.1.3.2', 'local_time' => '2.1.2.4', 'state_time' => '2.1.2.3', 'name' => 'middle' ),
		);
	foreach ($pe_levels as $level) {
		?>
		<h6><?php echo $level['label']; ?></h6>
		<ul>
			<li><?php
			if ( ! $data[ $level['likelihood'] ] || $data[ $level['likelihood'] ]  == 'neither' ) {
				echo 'Preliminary analyses indicate that this is not a viable issue at this time';
			} else if ( $data[ $level['likelihood'] ]  == 'state and local' ) {
				echo 'Given the current political/policy environment, we envision PE in ' . $level['name'] . ' schools policy will most likely occur at the state and local level. We expect to see state level policy potentially in ' . $data[ $level['state_time'] ] . ' and local level policy in ' . $data[ $level['state_time'] ];
			} else {
				echo 'Given the current political/policy environment, we envision PE in ' . $level['name'] . ' schools policy will most likely occur at the ' . $data[ $level['likelihood'] ] . ' level potentially in ';
				echo ( $data[ $level['likelihood'] ] == 'state') ? $data[ $level['state_time'] ] : $data[ $level['local_time'] ] ;
			}
			?>.</li>
		</ul>
		<?php
		}
		?> 

	<h5>Discussion Questions</h5>
	<strong>Does the community have capacity to take on this issue?</strong>
	<ul>
		<li>Is there a local coalition or other grassroots activity already in place and is AHA an active member?</li>
		<li>Does your state policy allow local districts to make these core curriculum decisions?</li>
		<li>Is there a plan in place to gauge the interest of the local school district to take action on this issue?</li>
		<li>What is the current local political climate for this topic?</li>
		<li>Does AHA already have volunteers that may be involved with this topic?</li>
		<li>Do the volunteers have the needed skills?</li>
		<li>Is there any external funding available to do the work? (ex. Community Transformation Grants, Voices for Healthy Kids, etc.)</li>
	</ul>
	<?php 
}

function cc_aha_print_criterion_school_phys_2( $metro_id ) {
	$data = cc_aha_get_form_data( $metro_id );
	$school_data = cc_aha_get_school_data( $metro_id );

	?>
	<h5>Current Status</h5>
	<ul>
		<li><?php echo cc_aha_top_5_school_percent_match_value( $metro_id, '2.2.5.1', 'broad' ); ?>% of the top 5 school districts in your community have a shared use policy to open up their facilities for broad community use.</li>
		<li><?php 
			if ( $data[ '2.2.1.1' ] == 'None of the above - we have already met this goal' ) {
				echo 'Your state has policies providing school districts liability protection from injury and property damage and clarifies that users are liable.';
			} else {
				echo 'Your state&rsquo;s shared use policy does not address ' . strtolower($data[ '2.2.1.1' ]);
			}
		?></li>
		<li><?php echo $data[ 'State' ] ?> currently <?php echo $data['2.2.2.1'] ? 'does' : 'does not'; ?> provide promotion, incentives, technical assistance or other resources to schools to encourage shared use.
		<?php if ( $data[ '2.2.2.2' ] ) : ?>
			<ul>
				<li>The policies can be described as follows: <?php echo $data[ '2.2.2.2' ]; ?></li>
			</ul>
		<?php endif; ?></li>
	</ul>
	<p><em>For a community to earn a <span class="healthy">“Healthy”</span> score, a shared use policy allowing for community recreational use of school property must be in place in 100% of the top 5 largest school districts.</em></p>

	<?php if ( ! empty( $school_data ) ) : ?>
	<table>
		<thead>
			<tr>
				<th>Top 5 School Districts</th>
				<th>Shared Use Policy in Place</th>
				<th>Types of Facilities Covered</th>
				<th>District Policy URL</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ( $school_data as $entry ) {
				?>
				<tr>
					<td><?php echo $entry['DIST_NAME']; ?></td>
					<td><?php echo cc_aha_get_matching_option_label( '2.2.5.1', $entry[ '2.2.5.1' ] ); ?></td>
					<td><?php 
						if ( $entry[ '2.2.5.1.2' ] ){
							echo $entry[ '2.2.5.1.2' ];
						}
					?></td>
					<td><?php echo '<a href="' . $entry['2.2.5.1.3'] . '">' . $entry['2.2.5.1.3'] . '</a>'; ?></td>
				</tr>
				<?php
			} ?>
		</tbody>
	</table>
	<?php endif; // if ( ! empty( $school_data ) )  ?>

	<h5>Policy Landscape</h5>
	<ul>
		<li><?php
		if ( ! $data['2.2.4.1'] || $data['2.2.4.1']  == 'neither' ) {
			echo 'Preliminary analyses indicate that this is not a viable issue at this time';
		} else {
			echo 'Given the current political/policy environment, we envision shared use policy will most likely occur at the ' . $data['2.2.4.1'] . ' level.';
		}
		?></li>
		<li><?php
		if ( ! $data['2.2.3.1'] ) {
			echo 'Preliminary analyses indicate that shared use liability protection policy will not be passed in ' . $data['State'] . ' in the next 3 years';
		} else {
			echo 'We expect shared use liability protection policy may be passed in ' . $data['State'] . ' in ' . $data['2.2.3.1'];
		}
		?>.</li>
		<li><?php
		if ( ! $data['2.2.3.2'] ) {
			echo 'Preliminary analyses indicate that shared use incentives and monitoring appropriations will not be passed in ' . $data['State'] . ' in the next 3 years';
		} else {
			echo 'We expect shared use incentives and monitoring appropriations may be passed in ' . $data['State'] . ' in ' . $data['2.2.3.2'];
		}
		?>.</li>
	</ul>


	<h5>Discussion Questions</h5>
	<strong>Does the community have capacity to take on this issue?</strong>
	<ul>
		<li>What potential coalition partners are in place?</li>
		<li>What is the current political climate?</li>
		<li>Do the volunteers have the needed skills?</li>
		<li>Is there any external funding available to do the work? (ex. Community Transformation Grants, Voices for Healthy Kids, etc.)</li>
		<li>If liability protection is not in place at the state level is there still interest in Shared Use Agreements at the local level?</li>
	</ul>
	<?php 
}

/**
 * Output dial html based on input
 * Expects parameter "healthy, intermediate or poor"
 *
 * @since   1.0.0
 * @return  html
 */
function cc_aha_print_dial( $status ){
	?>
	<div class="progress-dial">
		<span class="dial-label"><?php cc_aha_print_dial_label( $status ); ?></span>
		<input type="text" value="<?php cc_aha_print_dial_value( $status ); ?>" class="dial" autocomplete="off" data-width="200" data-fgColor="<?php cc_aha_print_dial_fill_color( $status ); ?>" data-angleOffset=-125 data-angleArc=250 data-displayInput=false data-displayprevious=true data-readOnly=true>
	</div>
	<?php
}
/**
 * Output dial states: label, color, how full
 * 
 *
 * @since   1.0.0
 * @return  string
 */
function cc_aha_print_dial_label( $value ){
	$params = cc_aha_get_dial_parameters( $value );
	echo $params[ 'label' ];
}

function cc_aha_print_dial_value( $value ){
	$params = cc_aha_get_dial_parameters( $value );
	echo $params[ 'percent' ];
}

function cc_aha_print_dial_fill_color( $value ){
	$params = cc_aha_get_dial_parameters( $value );
	echo $params[ 'fill_color' ];
}

function cc_aha_get_dial_parameters( $value ){
		// Match Yan's dial gauge colors
		$green = '#78B451';
		$yellow = '#FCA93C';
		$red = '#FF0A17';

		switch ( $value ) {
		case 'healthy':
			$params = array( 	'percent' 		=> 	100, 
								'fill_color'	=>	'#78B451', 
								'label' 		=> 	'Healthy' 
							);
			break;
		case 'intermediate':
			$params = array( 	'percent' 		=> 	50, 
								'fill_color'	=>	'#FCA93C', 
								'label' 		=> 	'Intermediate' 
							);
			break;
		case 'poor':
		default:
			$params = array( 	'percent' 		=> 	20, 
								'fill_color'	=>	'#FF0A17', 
								'label' 		=> 	'Needs Improvement' 
							);
			break;

	}
	return $params;
}

function cc_aha_print_cia_law_status( $data ) {
	if ( $data['1.1.2.1'] == 'Yes' ) { 
		echo 'Your state has clean indoor air laws covering restaurants, bars, and workplaces.';
	} else {
		echo 'Your state does not have clean indoor air laws covering restaurants, bars, and workplaces.';
	}
}
function cc_aha_print_state_cia_preempt( $data ) {
	if ( $data['1.1.2.1'] == 'Yes' ) { 
		echo 'The state does preempt local communities from adopting their own clean indoor air laws.';
	} else {
		echo 'The state does not preempt local communities from adopting their own clean indoor air laws.';
	}
}

function cc_aha_get_summary_sections() {
	return array( 
		'community' => array(
			'label' => 'Community Policies',
			'impact_areas' => array(
				'tobacco' => array(
					'label' => 'Tobacco',
					'dial_ids' => array( 305, 354 ),
					'criteria' => array(
						1 => array(
							'label' => 'Smoke Free Air',
							'background' => 'Advocating for comprehensive smoke free air laws at the state and local level is a pillar of the AHA&rsquo;s tobacco control advocacy efforts. Second hand smoke causes heart disease, cancer, lung disease and other illnesses in non-smokers. Research shows smoke-free laws lead to drastic reductions in cardiovascular incidents. These laws should be in compliance with the Fundamentals of Smoke-free Workplace Laws guidelines which guide and maximize the impact of smoke free policy efforts and increase the number of workers and residents who are protected from second hand smoke.

								<a href="http://www.heart.org/idc/groups/heart-public/@wcm/@adv/documents/downloadable/ucm_463595.pdf" target="_blank">Learn more</a>',
							'group' => 'community_tobacco_1' ),
						2 => array(
							'label' => 'Tobacco Excise Taxes',
							'background' => 'To help save these lives, the AHA advocates for significant increases in tobacco excise taxes at the state, county or municipal levels that cover all tobacco products. These taxes are a health win that reduces tobacco use, saves lives, raises revenue for cash-strapped states, and lowers health care costs.

								<a href="http://www.heart.org/idc/groups/heart-public/@wcm/@adv/documents/downloadable/ucm_461792.pdf" target="_blank">Learn more</a>',
							'group' => 'community_tobacco_2' ),
					),
				),
				'phys' => array(
					'label' => 'Physical Activity',
					'dial_ids' => array( 306, 307, 603, 605, 540 ),
					'criteria' => array(
						1 => array(
							'label' => 'Complete Streets',
							'background' => 'Complete Streets policies consider the needs of all users in all transportation projects incorporating walking, bicycling, public transportation, and driving.',
							'group' => 'community_phys_1' ),
					),
				),
				'diet' => array(
					'label' => 'Healthy Diet',
					'dial_ids' => array( 301, 356, 358, 603, 605, 303 ),
					'criteria' => array(
						1 => array(
							'label' => 'Local Government Procurement',
							'background' => 'Procurement by definition is the basic term utilized by state and local government entities that provide all food services for government facilities.  Procurement is the way they contract with external parties to provide foods and beverages in vending machines and prepared foods/beverages on government property.  The AHA advocates for nutrition criteria to be included in these contracts to better provide government employees healthier options for food and beverage in their worksites and to members of the public who visit government buildings.',
							'group' => 'community_diet_1' ),
						2 => array(
							'label' => 'Sugar-sweetened Beverage Tax',
							'background' => 'Reducing the consumption of excess sugars from sugary beverages is an important way to improve the health of Americans. The American Heart Association advocates for impact assessments of beverage sales taxes or excise taxes on consumption rates and shifts in consumer choice with special attention on vulnerable populations by supporting tax initiatives in some states and localities.
								Key criteria for the Association&rsquo;s support are: 
								<ol>
								<li>at least a portion of the money is dedicated for heart disease and stroke prevention and/or obesity prevention</li>
								<li>the tax is structured so as to result in an increase in price for sugar-sweetened beverages (e.g., imposed at the time of sale as opposed to the manufacturer that can spread the cost of the tax among all products)</li>
								<li>the amount of tax is anticipated to be sufficient to result in a reduction in consumption of sugar-sweetened beverages (at least 1 cent/ounce)</li>
								<li>there are funds dedicated for evaluation with guidance that ensure rigorous evaluation including health outcome</li>
								<li>there is a standard definition of &ldquo;sugar-sweetened beverage,&rdquo;</li>
								<li>there is no sunset.</li>
								</ol>
								<a href="http://www.heart.org/HEARTORG/Advocate/Voices-for-Healthy-Kids---Healthy-Drinks_UCM_460610_SubHomePage.jsp" target="_blank">Learn more</a>',
							'group' => 'community_diet_2' ),
						3 => array(
							'label' => 'Healthy Food Financing',
							'background' => 'A food desert is an area where residents lack affordable access to foods that would allow them to have a healthy diet, such as fruits, vegetables, low-fat milk and whole grains. Existing in urban, suburban and rural communities, they are places where the nearest supermarket is too far away for residents to shop. Healthy Food Financing is a viable, effective, and economically sustainable solution to the problem of limited access to healthy foods. Healthy Food Financing Initiatives attract investment in underserved communities by providing critical loan and grant financing. These one-time resources help fresh food retailers overcome the initial barriers to entry into underserved, low-income urban and rural communities, and support renovation and expansion of existing stores so they can provide the healthy foods that communities want and need. Identifying food deserts is not an exact science, but you can <a href="http://maps.communitycommons.org/viewer/?action=open_map&id=2397" target="_blank">look at an overview of your county&rsquo;s access to healthier food here.</a>',
							'group' => 'community_diet_3' ),
					),
				),
			),
		),
		'school' => array(
			'label' => 'Healthy Schools',
			'impact_areas' => array(
				'phys' => array(
					'label' => 'Physical Activity',
					'dial_ids' => array( 307, 605, 408 ),
					'criteria' => array(
						1 => array(
							'label' => 'PE in Schools',
							'background' => 'The quality and quantity of physical education in the nation&rsquo;s schools is an important part of a student&rsquo;s comprehensive, well-rounded education program and a means of positively affecting life-long health and well-being. The AHA advocates for daily physical education for all students in all school levels.',
							'group' => 'school_phys_1' ),
						2 => array(
							'label' => 'Shared Use',
							'background' => 'Shared Use Agreements allow schools to share their physical activity facilities (gyms, running/walking tracks, multi-purpose rooms) with the community for recreation and exercise opportunities. The AHA works to provide liability protection within state law so school districts will feel comfortable opening up school facilities both before and after school hours without the fear of lawsuits for injuries occurring on school property. Once the liability protection is enacted into state law the AHA works to provide incentives and monitoring of shared use agreements once they are put in place.',
							'group' => 'school_phys_2' ),
					),
				),
				'diet' => array(
					'label' => 'Healthy Diet',
					'dial_ids' => array( 356, 358, 605 ),
					'criteria' => array(
						1 => array(
							'label' => 'School Nutrition Policy',
							'background' => ' The USDA Food and Nutrition Service interim final rule establishes nutrition standards for foods sold in schools other than those foods provided as part of the National School Lunch and School Breakfast Programs (NSLP/SBP).  These foods and beverages are called Competitive Foods because they “compete” with the traditional school lunch programs.  Examples are foods/beverages sold in the a la carte line, vending machines, school canteens, and onsite fundraisers.',
							'group' => 'school_diet_1' ),
						2 => array(
							'label' => 'School Nutrition Implementation',
							'background' => 'The Healthy, Hunger-Free Kids Act of 2010 instituted many changes to the National School Lunch Program (NSLP), and in concert with those changes, USDA issued new, more stringent school meal nutrition standards for the 2012-13 school year. All changes within school meals are expected to have occurred in advance of the beginning of the 2014-2015 school year to bring schools in compliance with federal law.',
							'group' => 'school_diet_2' ),
					),
				),
				'cpr' => array(
					'label' => 'Chain of Survival',
					'background' => '',
					'dial_ids' => array( 640 ),
					'criteria' => array(
						1 => array(
							'label' => 'CPR Grad Requirement',
							'background' => 'Sudden Cardiac Arrest is a leading cause of death in the U.S.—but when ordinary people, not just doctors and EMTs, are equipped with the skills to perform CPR, the survival rate can double, or even triple.  Help us add thousands of lifesavers to our communities. Join us in supporting public policy that will ensure all students learn quality CPR before they graduate from high school. <a href="http://www.becprsmart.org" target="_blank">www.becprsmart.org</a>',
							'group' => 'school_cpr_1' ),
					),
				),
			),
		),
		'care' => array(
			'label' => 'Healthcare Access and Quality',
			'impact_areas' => array(
				'factors' => array(
					'label' => 'Health Factors',
					'dial_ids' => array( 504, 508, 637, 607 ),
					'criteria' => array(
						1 => array(
							'label' => 'Insurance Coverage',
							'background' => 'The burden of heart disease and stroke can be especially challenging for those without health insurance or with inadequate coverage.  Uninsured Americans with CVD have higher mortality rates and a more difficult time controlling their blood pressure or accessing needed medications. The uninsured and underinsured also have a harder time accessing preventative care and needed medications.  The AHA advocates for states to accept federal funds to provide health insurance to low income adults and coverage of all cardiovascular-related preventative benefits with an A or B recommendation by the USPSTF for Medicaid enrollees, with no cost for patients. <a href="http://www.heartsforhealthcare.org" target="_blank">www.heartsforhealthcare.org</a>',
							'group' => 'care_factors_1' ),
					),
				),
				'acute' => array(
					'label' => 'Acute & Emergency Response',
					'background' => '',
					'dial_ids' => array( 640, 625 ),
					'criteria' => array(
						1 => array(
							'label' => 'CMS PENALTY: Total Discharges',
							'background' => 'More hospitals are receiving penalties than bonuses in the second year of Medicare’s quality incentive program.  Government records show that the average penalty is steeper than it was last year. These penalties were based on two-dozen quality measurements, including surveys of patient satisfaction and—for the first time—death rates.  Hospitals are encouraged to find ways to improve their scores.  The AHA believes that GWTG can help to increase their quality by helping them to identify process improvements, monitoring compliance with the AHA guidelines for Stroke, HF, Resuscitation, ACTION Registry-GWTG and AFIB.',
							'group' => 'care_acute_1' ),
						2 => array(
							'label' => 'CMS PENALTY: Underserved Discharges',
							'background' => '',
							'group' => 'care_acute_2' ),
					),
				),
			),
		),
	);
}
function cc_aha_get_summary_impact_area_title( $section, $impact_area ) {
	$section_data = cc_aha_get_summary_sections();
	return $section_data[$section]['impact_areas'][$impact_area]['label'];
}

function cc_aha_get_summary_introductory_text( $section, $impact_area, $crit_key ) {
	$section_data = cc_aha_get_summary_sections();
	$text = $section_data[$section]['impact_areas'][$impact_area]['criteria'][$crit_key]['background'];

	// Customize the url for the food desert map in the HFFI section
	if ( $section == 'community' && $impact_area == 'diet' && $crit_key == 3 ){
		// Create a string of the complete fips code
		$url = 'http://maps.communitycommons.org/viewer/?action=link_map&vm=1231&groupid=594&geoid=' . cc_aha_get_fips();
		$text = str_replace( '%food_desert_url%', $url, $text );
	}

	return $text;
}

function cc_aha_get_summary_impact_area_widgets( $section, $impact_area ) {
	$section_data = cc_aha_get_summary_sections();
	return $section_data[$section]['impact_areas'][$impact_area]['dial_ids'];
}
function cc_aha_get_impact_area_criteria( $section, $impact_area ){
	$section_data = cc_aha_get_summary_sections();
	return $section_data[$section]['impact_areas'][$impact_area]['criteria'];
}

/**
 * Analysis-related calculations.
 * Catch-all switch for health-related scoring: this takes the section name and returns the score string
 *
 * @since   1.0.0
 * @return  string - "healthy", "intermediate" or "poor"
 */ 
function cc_aha_section_get_score( $section, $impact_area, $crit_key, $metro_id = null ){
	if ( ! $metro_id )
		$metro_id = $_COOKIE['aha_summary_metro_id'];

	$search_key = $section . '_' . $impact_area . '_' . $crit_key;

	switch ( $search_key ) {
		case 'community_tobacco_1':
			// Clean indoor air
			$score = cc_aha_calc_cia( $metro_id );
			break;
		case 'community_tobacco_2':
			// Tobacco excise tax
			$score = cc_aha_calc_tobacco_excise( $metro_id );
			break;
		case 'community_phys_1':
			// Complete streets
			$tiers = array( 'meets guideline', 'below guidelines' );
			$score = cc_aha_calc_three_text_tiers( $metro_id, '2.3.1.1', $tiers );
			break;
		case 'community_diet_1':
			// Local gov't procurement
			$tiers = cc_aha_get_options_for_question( '3.3.1.1' );
			$score = cc_aha_calc_three_text_tiers( $metro_id, '3.3.1.1', $tiers );
			break;
		case 'community_diet_2':
			// Sugar-sweetened beverage tax
			// All are poor; no policies have passed
			$score = 'poor';
			break;
		case 'community_diet_3':
			// Healthy food financing
			// TODO
			$score = cc_aha_calc_hffi( $metro_id );
			break;		
		case 'school_phys_1':
			// PE in schools
			$percent = cc_aha_top_5_school_pe_calculation( $metro_id, 'all' );
			$score = cc_aha_convert_percent_to_three_tiers( $percent );
			break;
		case 'school_phys_2':
			// Shared use
			$value = cc_aha_top_5_school_percent_match_value( $metro_id, '2.2.5.1', 'broad' );
			$score = cc_aha_convert_percent_to_three_tiers( $value );
			break;		
		case 'school_diet_1':
			// School nutrition policy
			//$score = cc_aha_calc_three_tiers( $metro_id, '3.1.3.6' );
			$qids = array( '3.1.3.1.0', '3.1.3.1.1', '3.1.3.1.2', '3.1.3.1.3' );
			$school_data = cc_aha_get_school_data( $metro_id );
			$score = cc_aha_calc_n_question_district_yes_tiers( $school_data, $qids );
			//$score = cc_aha_calc_three_tiers( $metro_id, '3.1.3.6' );
			break;
		case 'school_diet_2':
			// School nutrition implementation
			//From AHA(Q3.2.1.6) CALCULATED: Percentage of top 5 districts answering “Yes” to Q3.2.1.1 – Q3.2.1.5 (we only have 3.2.1.1)
			$qids = array( '3.2.1.1' );
			$school_data = cc_aha_get_school_data( $metro_id );
			$score = cc_aha_calc_n_question_district_yes_tiers( $school_data, $qids );
			break;
		case 'school_cpr_1':
			// CPR grad requirement
			$qids = array( '5.1.4.1' );
			$school_data = cc_aha_get_school_data( $metro_id );
			$score = cc_aha_calc_n_question_district_yes_tiers( $school_data, $qids );
			break;
		case 'care_factors_1':
			// Insurance coverage
			$score = cc_aha_calc_three_tiers_80( $metro_id, '4.1.1' );
			break;
		case 'care_acute_1':
			// CMS penalty - TOTAL DISCHARGES
			$score = cc_aha_calc_three_tiers_50( $metro_id, '6.1.1' );
			break;
		case 'care_acute_2':
			// CMS penalty - UNDERSERVED DISCHARGES
			$score = cc_aha_calc_three_tiers_50( $metro_id, '6.1.2' );
			break;
		default:
			// When in doubt...
			$score = 'poor';
			break;
	}
	return $score;
}

function cc_aha_calc_cia( $metro_id ) {
	$data = cc_aha_get_form_data( $metro_id );

	if ( $data[ '1.1.2.2' ] == 100 && $data[ '1.1.2.3' ] == 100 ) {
		if ( $data[ '1.1.2.4' ] == 100 ) {
			return 'healthy';
		} else {
			return 'intermediate';
		}
		
	} else {
		return 'poor';
	}
}
function cc_aha_calc_tobacco_excise( $metro_id ){ 
	$data = cc_aha_get_form_data( $metro_id );

	$total_excise = $data[ '1.2.1.1' ] + $data[ '1.2.2.1' ];
	if ( $total_excise >= 1.85 ) {
		return 'healthy';
	} else if ( $total_excise >= 1 ) {
		return 'intermediate';
	} else {
		return 'poor';
	}
}
function cc_aha_calc_hffi( $metro_id ){ 
	$data = cc_aha_get_form_data( $metro_id );

	$pop = $data[ '3.5.1' ];
	if ( $pop < 46 ) {
		return 'healthy';
	} else if ( $pop < 53 ) {
		return 'intermediate';
	} else {
		return 'poor';
	}
}
function cc_aha_calc_three_text_tiers( $metro_id, $qid, $tiers ){
	// This takes an array for the third arg: the first value is the "healthy" value, the second is the "intermediate" value.
	if ( ! $metro_id )
		$metro_id = $_COOKIE['aha_summary_metro_id'];

	$data = cc_aha_get_form_data( $metro_id );

	if ( $data[ $qid ] == $tiers[0] ) {
		return 'healthy';
	} else if ( $data[ $qid ] == $tiers[1] ) {
		return 'intermediate';
	} else {
		return 'poor';
	}
}

// Generalized to identify 0-49, 50-99 and 100% tiers
function cc_aha_calc_three_tiers( $metro_id, $qid ) {
	if ( ! $metro_id )
		$metro_id = $_COOKIE['aha_summary_metro_id'];

	$data = cc_aha_get_form_data( $metro_id );

	return cc_aha_convert_percent_to_three_tiers( $data[ $qid ] );

}

// Generalized to identify 0-49, 50-99 and 100% tiers
function cc_aha_convert_percent_to_three_tiers( $value ) {

	if ( $value == 100 ) {
		return 'healthy';
	} else if ( $value >= 50 ) {
		return 'intermediate';
	} else {
		return 'poor';
	}
}

/* Generalized to take N yes/no questions for a board,
 *	get all data for districts w/in board,
 *	calculate % yes for total and place in tiers
 *	
 *	3.1, 3.2, 5.1 use this
 */
function cc_aha_calc_n_question_district_yes_tiers( $school_data, $qids = array() ) { //Gold star for BEST NAME EVER

	$num_yes = 0;
	$total_questions = 0;
	
	//loop through each school
	foreach ( $school_data as $school ){
	
		//loop through each question for this school
		foreach( $qids as $qid ){
		
			//if data is not defined, either no column or cell data, don't assume 'No'.
			if( isset( $school[ $qid ] ) ) {
				//depending on where the data comes from, it could be 'Yes' or '1'
				if ( ( $school[ $qid ] == 'Yes' ) || ( $school[ $qid ] == '1' ) ) {
					$num_yes++;
				}
				$total_questions++; //hmm
			}
		}
	}
	
	// If no total questions, bail rather than divide by zero.
	if ( ! $total_questions )
		return 'poor';

	if ( ( $num_yes == $total_questions ) ) {  // all are yes
		return 'healthy';
	} else if ( ( $num_yes / $total_questions ) >= 0.5 ) { 
		// Returns stop the function, so this continues only if the first 'if' didn't fire.
		return 'intermediate';
	} else {
		return 'poor';
	}
}