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
	if ( ! cc_aha_user_can_do_assessment() ) {
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
			<li><a href="<?php echo cc_aha_get_analysis_permalink(); ?>" class="button">Return to Analysis Summary</a></li>
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
	// [2] is section
	// [3] is the impact area
	if ( ! bp_action_variable( 2 ) ) {
		cc_aha_print_single_report_card( $metro_id );
	} else {
		cc_aha_print_impact_area_report( $metro_id, bp_action_variable( 2 ), bp_action_variable( 3 ) );
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
function cc_aha_print_single_report_card( $metro_id = 0 ) {
	?>
	<section id="single-report-card" class="clear">
		<?php // Building out a table of responses for one metro
		$sections = cc_aha_get_summary_sections( $metro_id );
		?>
		<table>
			<thead>
				<tr>
					<th>Impact Area</th>
					<th>Healthy Community Criteria</th>
					<th>Health Need</th>
					<th>Score</th>
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
								<td>
									Maybe a gauge goes here.
								</td>
								<td class="<?php echo cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key ); ?>">
									<?php cc_aha_print_dial_label( cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key ) ); ?>
								</td>
								<td>
									Yes (?)
								</td>
							</tr>

							<?php
							}
						}
					}
				?>
			</tbody>
		</table>

	</section>
	<?php 
}

function cc_aha_print_impact_area_report( $metro_id, $section, $impact_area ) {
	?>

	<section id="<?php echo $impact_area; ?>" class="clear">
		<h2 class="screamer"><?php echo cc_aha_get_summary_impact_area_title( $section, $impact_area ); ?></h2>

		<?php 
		$dial_ids = cc_aha_get_summary_impact_area_widgets( $section, $impact_area );
		$fips = cc_aha_get_fips();

		// Show the top section if there's something to show.
		if ( $dial_ids && $fips ) :
			// TODO: Real title needed
		?>
		<h3>How we fit in to the bigger picture</h3>
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
				<p><strong>Background: </strong><?php echo cc_aha_get_summary_introductory_text( $section, $impact_area, $crit_key ); ?></p>
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
			</div>
		</div>
<?php
	}
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

	<h5>Policy Landscape</h5>
	<ul>
		<li>Your state <?php echo $data['1.1.2.1'] ? 'does' : 'does not'; ?> preempt local communities from adopting their own clean indoor air laws.</li>
		<li><?php 
		if ( ! $data['1.1.1.4'] || $data['1.1.3.1'] == 'Not a viable issue at any level at this time' ) {
			echo 'Preliminary analyses indicate that this is not a viable issue at this time.';
		} else {
			echo 'Given the current political/policy environment, we envision smoke­free air public policy change will most likely occur ' . $data['1.1.3.1'] . ' potentially in ' . $data['1.1.1.4']; 
		}
		?></li>
	</ul>

	<h5>Discussion Questions</h5>
	<?php 
	//Show Discussion questions from the db (where 'group' here == 'summary_section' in db)
	$criteria = cc_aha_get_impact_area_criteria( $section, $impact_area );
	
	$disc_questions = array();
	
	foreach ( $criteria as $criterion ){
		//echo 'Criteria !' . $criterion;
		//print_r( $criterion );
		$disc_questions[] = cc_aha_get_questions_for_summary_criterion( $criterion['group'] ); 
	}
	
	$disc_questions = current( $disc_questions );
	
	//if we have multiple question rows in table for group
	foreach ( $disc_questions as $question ) {
		//$question = current( $question );
		
		if ( $question['summary_section'] == $group ) { //make sure we're looking at the right set of questions
			echo $question['summary_label'];
		}
	}
	
	?>
	
	<!--<h5>Discussion Questions</h5>
	<strong>Is this a priority at the state level?</strong>
	<ul>
		<li>Are their state legislators from this community who are key targets that we need to influence for supporting a state-wide campaign?</li>
		<li>Do the board members or other AHA volunteers have relationships with these key targets and legislators?</li>
		<li>Do the board members have relationships with key opinion leaders (key business leaders, members of the media, etc.) with the community to help support this effort?</li>
		<li>What’s the current level of grassroots activity in the community to support this effort?</li>
	</ul>
	<strong>Does the community have capacity to take on this issue?</strong>
	<ul>
		<li>What potential coalition partners (such as ACS/ALA, local hospitals, medical association, etc.) community partners (neighborhood groups, PTO, church groups, etc.) are in place?  </li>
		<li>What is the current political climate?</li>
		<li>What is the likelihood of the current council support for clean indoor air law?  </li>
		<li>Do the board members and other AHA volunteers have the capacity to lead and fully engage in this campaign?</li>
		<li>Is there any external funding available to do the work?  (ex. Community Transformation Grants, etc.)</li>
	</ul>-->
	
	<?php 
	echo PHP_EOL . ">> open response";
	echo PHP_EOL . ">> top 3";

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
	
	<h5>Policy Landscape</h5>
	<ul>
		<li><?php echo $data['State']; ?> <?php echo $data['1.2.2.2'] ? 'does' : 'does not'; ?> allow local communities to levy tobacco excise taxes locally.</li>
		<li><?php 
		if ( ! $data['1.2.1.2'] || $data['1.2.3.1'] == 'Not a viable issue at any level at this time' ) {
			echo 'Preliminary analyses indicate that this is not a viable issue at this time.';
		} else {
			echo 'Given the current political/policy environment, we envision tobacco excise tax public policy change will most likely occur ' . $data['1.2.1.2'] . ' potentially in ' . $data['1.2.3.1']; 
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
	echo PHP_EOL . ">> open response";
	echo PHP_EOL . ">> top 3";

}

function cc_aha_print_criterion_community_phys_1( $metro_id ) {
	$complete_streets = cc_aha_get_complete_streets_data( $metro_id );
	$data = cc_aha_get_form_data( $metro_id );
	?>
	<h5>Current Status</h5>
	<p>Your community is <?php 
		if ( $data['2.3.1.1'] == "meet guidelines" ) {
			echo 'covered by a Complete Streets policy that meets AHA guidelines';
		} else if ( $data['2.3.1.1'] == "below guidelines" ) {
			echo 'covered by a Complete Streets policy that is below AHA guidelines';
		} else {
			echo 'not covered by a Complete Streets policy';
		}

	?>.</p>

	<?php if ( ! empty( $complete_streets ) ) : ?>
	<p>The following Complete Streets policies are in effect in your area.</p>
	<table>
		<thead>
			<tr>
				<th></th>
				<th>Geography Level</th>
				<th>Policy Name</th>
				<th>Policy Year</th>
				<th>Policy Score</th>
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
	<?php endif; // if ( ! empty( $complete_streets ) )  ?>

	<h5>Policy Landscape</h5>
	<ul>
		<li>There <?php echo $data['2.3.2.1'] ? 'is' : 'is not'; ?> a state, regional or local complete streets policy under consideration.</li>
		<?php if ( $data['2.3.2.1'] ) : ?>
			<ul>
				<li><?php echo $data['2.3.2.2']; ?> is leading the effort.</li>
			</ul>
		<?php endif; ?>
		<li><?php
		if ( ! $data['2.3.3'] || $data['2.3.3']  == 'neither' ) {
			echo 'Preliminary analyses indicate that this is not a viable issue at this time.';
		} else if ( $data['2.3.3']  == 'state and local' ) {
			echo 'Given the current political/policy environment, we envision complete streets policy will most likely occur at the state and local level. We expect to see state level policy potentially in ' . $data['2.3.1.2'] . ' and local level policy in ' . $data['2.3.1.3'];
		} else {
			echo 'Given the current political/policy environment, we envision complete streets policy will most likely occur at the' . $data['2.3.3'] . 'level potentially in ';
			echo  ( $data['2.3.1.2'] == 'state') ? $data['2.3.1.2'] : $data['2.3.1.3'] ;
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
	echo PHP_EOL . ">> open response";
	echo PHP_EOL . ">> top 3";

}



function cc_aha_print_criterion_care_factors_1( $metro_id ) {
	$counties = cc_aha_get_county_data( $metro_id );
	$data = cc_aha_get_form_data( $metro_id );
	
	$section = 'care';
	$impact_area = 'factors';
	$group = 'care_factors_1';
	
	?>
	<h5>Current State</h5>
	<p><?php echo $data['4.1.1']; ?>% of residents aged <65 have health insurance.</p>
	
	<p><?php echo $data['State']; ?> <?php echo $data['4.1.2.1'] ? 'is' : 'is not'; ?> covered by Medicaid expansion.</p>
	
	<p><?php echo $data['State']; ?> <?php echo $data['4.1.2.2'] ? 'is' : 'is not'; ?> covered by USPSTF A and/or B.</p>
	
	<h5>Policy Landscape</h5>
	<ul>
		<li><?php 
		if ( ( $data['4.1.4'] == 'Not a viable issue at any level at this time' ) || ( $data['4.1.4'] == 'no' ) ) {
			echo 'Preliminary analyses indicate that this is not a viable issue at this time.';
		} else if ( ( $data['4.1.4'] == 'Yes – Medicaid expansion at the state level' ) || ( $data['4.1.4'] == 'yes - Medicaid expansion' ) ) {
			echo 'Given the current political/policy environment, we envision “Medicaid expansion at the state level”'; 
			if ( isset ( $data['4.1.3.1'] ) ) 
				echo ' potentially in ' . $data['4.1.3.1'];
		} else if ( ( $data['4.1.4'] == 'Yes – USPSTF at the state level' ) || ( $data['4.1.4'] == 'yes - USPSTF' ) ) {
			echo 'Given the current political/policy environment, we envision “USPSTF A and B cardiovascular preventative benefits under Medicaid” will be passed in your state'; 
			if ( isset ( $data['4.1.3.2'] ) ) 
				echo ' potentially in ' . $data['4.1.3.2'];
		}
		?></li>
	</ul>
	
	<h5>Discussion Questions</h5>
	<?php 
	//Show Discussion questions from the db (where 'group' here == 'summary_section' in db)
	$criteria = cc_aha_get_impact_area_criteria( $section, $impact_area );
	
	$disc_questions = array();
	
	foreach ( $criteria as $criterion ){
		$disc_questions[] = cc_aha_get_questions_for_summary_criterion( $criterion['group'] ); 
	}
	
	$disc_questions = current( $disc_questions );
	
	//if we have multiple question rows in table for group
	foreach ( $disc_questions as $question ) {
		
		if ( $question['summary_section'] == $group ) { //make sure we're looking at the right set of questions
			echo $question['summary_label'];
		}
	}
	
	?>
	
	<?php 
	echo PHP_EOL . ">> open response";
	echo PHP_EOL . ">> top 3";

}

function cc_aha_print_criterion_care_acute_1( $metro_id ) {
	$counties = cc_aha_get_county_data( $metro_id );
	$data = cc_aha_get_form_data( $metro_id );
	$hospitals = cc_aha_get_hospital_data( $metro_id );
	
	$section = 'care';
	$impact_area = 'acute';
	$group = 'care_acute_1';
	
	?>
	<h5>Current State</h5>
	<p><?php echo $data['6.1.1']; ?>% of CVD discharges are from hospitals with a CMS bonus penalty >-0.4%.</p>
	
	<p><?php echo $data['6.1.2']; ?>% of underserved CVD discharges are from hospitals with a CMS bonus penalty ≥-0.4%.</p>
	
	
	<table>
		<thead>
			<tr>
				<th>Hospital Name</th>
				<th>CVD Discharges</th>
				<th>Underserved CVD Discharges</th>
				<th>Any AHA Quality Program</th>
				<th>Total VBP Bonus/Penalty</th>
				<th>CVD Discharge Target Rank by Board</th>
				<th>Underserved Discharge Rank by Board</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ( $hospitals as $hospital ) {
				?>
				<tr>
					<td><?php echo $hospital['Hospital Name']; ?></td>
					<td><?php echo $hospital['CVD Discharges']; ?></td>
					<td><?php echo $hospital['Underserved CVD Discharges']; ?></td>
					<td><?php echo $hospital['Any AHA Quality Program']; ?></td>
					<td><?php echo $hospital['Total VBP & Readmission Bonus/Penalty  2014']; ?></td>
					<td><?php echo $hospital['CVD Discharge Target Rank by Board']; ?></td>
					<td><?php echo $hospital['Underserved Discharge Rank by Board']; ?></td>
				</tr>
				<?php
			} ?>
		</tbody>
	</table>
	
	<h5>Discussion Questions</h5>
	<?php 
	//Show Discussion questions from the db (where 'group' here == 'summary_section' in db)
	$criteria = cc_aha_get_impact_area_criteria( $section, $impact_area );
	
	$disc_questions = array();
	
	foreach ( $criteria as $criterion ){
		$disc_questions[] = cc_aha_get_questions_for_summary_criterion( $criterion['group'] ); 
	}
	
	$disc_questions = current( $disc_questions );
	
	//if we have multiple question rows in table for group
	foreach ( $disc_questions as $question ) {
		
		if ( $question['summary_section'] == $group ) { //make sure we're looking at the right set of questions
			echo $question['summary_label'];
		}
	}
	
	?>
	
	<?php 
	echo PHP_EOL . ">> open response";
	echo PHP_EOL . ">> top 3";

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

								<a href="http://www.heart.org/idc/groups/heart-public/@wcm/@adv/documents/downloadable/ucm_463595.pdf">Learn more</a>',
							'group' => 'community_tobacco_1' ),
						2 => array(
							'label' => 'Tobacco Excise Taxes',
							'background' => 'To help save these lives, the AHA advocates for significant increases in tobacco excise taxes at the state, county or municipal levels that cover all tobacco products. These taxes are a health win that reduces tobacco use, saves lives, raises revenue for cash-strapped states, and lowers health care costs.

								<a href="http://www.heart.org/idc/groups/heart-public/@wcm/@adv/documents/downloadable/ucm_461792.pdf">Learn more</a>',
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
							'background' => '',
							'group' => 'community_diet_1' ),
						2 => array(
							'label' => 'Sugar-sweetened Beverage Tax',
							'background' => '',
							'group' => 'community_diet_2' ),
						3 => array(
							'label' => 'Healthy Food Financing',
							'background' => '',
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
							'background' => '',
							'group' => 'school_phys_1' ),
						2 => array(
							'label' => 'Shared Use',
							'background' => '',
							'group' => 'school_phys_2' ),
					),
				),
				'diet' => array(
					'label' => 'Healthy Diet',
					'dial_ids' => array( 356, 358, 605 ),
					'criteria' => array(
						1 => array(
							'label' => 'School Nutrition Policy',
							'background' => '',
							'group' => 'school_diet_1' ),
						2 => array(
							'label' => 'School Nutrition Implementation',
							'background' => '',
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
							'background' => '',
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
							'background' => 'The burden of heart disease and stroke can be especially challenging for those without health insurance or with inadequate coverage.  Uninsured Americans with CVD have higher mortality rates and a more difficult time controlling their blood pressure or accessing needed medications. The uninsured and underinsured are also have a harder time accessing preventative care and needed medications.  The AHA advocates for states to accept federal funds to provide health insurance to low income adults and cover of all cardiovascular-related preventative benefits with an A or B recommendation by the USPSTF for Medicaid enrollees, with no cost for patients. <a href="http://www.heartforhealthcare.org">www.heartforhealthcare.org</a>',
							'group' => 'care_factors_1' ),
					),
				),
				'acute' => array(
					'label' => 'Acute Event',
					'background' => '',
					'dial_ids' => array( 640, 625 ),
					'criteria' => array(
						1 => array(
							'label' => 'CMS Penalty: Total CVD Discharges',
							'background' => 'More hospitals are receiving penalties than bonuses in the second year of Medicare’s quality incentive program.  Government records show that the average penalty is steeper than it was last year. These penalties were based on two-dozen quality measurements, including surveys of patient satisfaction and—for the first time—death rates.  Hospitals are encouraged to find ways to improve their scores.  The AHA believes that GWTG can help to increase their quality by helping them to identify process improvements, monitoring compliance with the AHA guidelines for Stroke, HF, Resuscitation, ACTION Registry-GWTG and AFIB.',
							'group' => 'care_acute_1' ),
						2 => array(
							'label' => 'CMS Penalty: Total CVD Discharges',
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
	return $section_data[$section]['impact_areas'][$impact_area]['criteria'][$crit_key]['background'];
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
			$score = cc_aha_calc_tobacco_excise( $metro_id );
			break;		
		case 'school_phys_1':
			// PE in schools
			$score = cc_aha_calc_three_tiers( $metro_id, '2.1.4.6' );
			break;
		case 'school_phys_2':
			// Shared use
			$score = cc_aha_calc_three_tiers( $metro_id, '2.2.5.6' );
			break;		
		case 'school_diet_1':
			// School nutrition policy
			$score = cc_aha_calc_three_tiers( $metro_id, '3.1.3.6' );
			break;
		case 'school_diet_2':
			// School nutrition implementation
			$score = cc_aha_calc_three_tiers( $metro_id, '3.2.1.6' );
			break;
		case 'school_cpr_1':
			// CPR grad requirement
			$score = cc_aha_calc_three_tiers( $metro_id, '5.1.4.6' );
			break;
		case 'care_factors_1':
			// Insurance coverage
			// TODO
			$score = 'poor';
			break;
		case 'care_acute_1':
			// CMS penalty
			// TODO
			$score = 'poor';
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

	if ( $data[ $qid ] == 100 ) {
		return 'healthy';
	} else if ( $data[ $qid ] >= 50 ) {
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
		
	if ( ( $num_yes == $total_questions ) && ( $num_yes > 0 ) ) {  //because 0 total questions and 0 yeses indicate 1
		return 'healthy';
	} else if ( ( ( $num_yes / $total_questions ) >= 0.5 ) && ( ( $num_yes / $total_questions ) < 1 ) ) {
		return 'intermediate';
	} else {
		return 'poor';
	}	
	
}
