<?php 
/**
 * CC American Heart Association Extras; Summary Template Tags part deux
 *
 * @package   CC American Heart Association Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2014 CommmunityCommons.org
 */


//school_diet_1
function cc_aha_print_criterion_school_diet_1( $metro_id ) {
	$data = cc_aha_get_form_data( $metro_id );
	$school_data = cc_aha_get_school_data( $metro_id );
	
	$section = 'school';
	$impact_area = 'diet';
	$group = 'school_diet_2';
	
	$qids = array( '3.1.3.1.0', '3.1.3.1.1', '3.1.3.1.2', '3.1.3.1.3' );
	$policy_percent = cc_aha_calc_n_question_district_yes_percent( $school_data, $qids );
	?>
	
	<h5>Current State</h5>
	<p><?php echo $policy_percent; ?>% of the top 5 school districts have a documented and publicly available wellness policy covering school meals, smart snacks and before/after school offerings meeting AHA criteria.
	</p>
	
	<?php //TODO: what is going on here? According to the table provided, these are yes/no answers... 
	?>
	<p>The competitive foods policy in <?php echo $data['State'] ?> is “required by law and is stronger than the USDA policy / voluntary… etc.” [Q3.1.1.1]
	</p>
	
	<p>The competitive foods policy defense priority level in <?php echo $data['State'] ?> is <?php echo $data['3.1.1.2'] ?>.
	</p>
	
	<table>
		<thead>
			<tr>
				<th rowspan="2">Top 5 School Districts</th>
				<th rowspan="2">District Wellness Policy Accessible</th>
				<th rowspan="1" colspan="3">Policy meets AHA’s criteria for</th>
				
				<th rowspan="2">District Policy URL</th>
				<th rowspan="2">Alliance for a Healthier Generation Recruitment Interest</th>
			</tr>
				<th>School meals</th>
				<th>Competitive Foods</th>
				<th>Before / After School Offering</th>
		</thead>
		<tbody>
			<?php 
			foreach ( $school_data as $school ) {
				?>
				<tr>
					<td><?php echo $school['DIST_NAME'] . '<br / >' . $school['DIST_ID']; ?></td>
					<td><?php if ( isset ( $school['3.1.3.1.0']) && $school['3.1.3.1.0'] != '' ) {
						echo $school['3.1.3.1.0'] ? 'Yes' : 'No'; 
					} else {
						echo 'Don\'t Know';
					} ?>
					</td>
					<td><?php if ( isset ( $school['3.1.3.1.1']) && $school['3.1.3.1.1'] != '' ) {
						echo $school['3.1.3.1.1'] ? 'Yes' : 'No'; 
					} else {
						echo 'Don\'t Know';
					} ?>
					</td>
					<td><?php if ( isset ( $school['3.1.3.1.2']) && $school['3.1.3.1.2'] != '' ) {
						echo $school['3.1.3.1.2'] ? 'Yes' : 'No'; 
					} else {
						echo 'Don\'t Know';
					} ?>
					</td>
					<td><?php if ( isset ( $school['3.1.3.1.3']) && $school['3.1.3.1.3'] != '' ) {
						echo $school['3.1.3.1.3'] ? 'Yes' : 'No'; 
					} else {
						echo 'Don\'t Know';
					} ?>
					</td>
					<td><?php if ( isset ( $school['3.1.3.1.4']) && $school['3.1.3.1.4'] != '' ) {
						echo $school['3.1.3.1.4'] ? 'Yes' : 'No'; 
					} else {
						echo 'Don\'t Know';
					} ?>
					</td>
					<td><?php if ( isset ( $school['3.1.2']) && $school['3.1.2'] != '' ) {
						echo $school['3.1.2'] ? 'Yes' : 'No'; 
					} ?>
					</td>
				</tr>
				<?php
			} ?>
		</tbody>
	</table>
	
	<ul>
		<li><?php 
		if ( ( $data['3.1.4'] == 'No – none of the above' ) || ( $data['3.1.4'] == 'no' ) ) {
			echo 'Preliminary analyses indicate that this is not a viable issue at this time.';
		} else {
			echo 'Possible opportunities to drive impact include: ';
			
			//TODO: figure this out based on data we're collecting
			//publish / strengthen the district wellness policy and/or open a door for the Alliance for a Healthier Generation and/or strengthen competitive foods policy by applying nutrition standards to after school activities and/or strengthen competitive foods policy by addressing celebrations and fundraisers.”'; 
			echo ' potentially in ' . $data['3.1.4'];
		} 
		?></li>
	</ul>
		
	<h5>Discussion Questions</h5>
	
	<strong>Does the community have capacity to take on this issue?</strong>
	<ul>
		<li>Is there a local coalition already in place? Is AHA an active member?</li>
		<li>What potential coalition partners are in place?</li>
		<li>What is the current local political climate?</li>
		<li>Are AHA volunteers already involved with this effort?</li>
		<li>What is the current level of grassroots activity in the community supporting this effort?</li>
		<li>Do the board members and other AHA volunteers have the capacity to lead and fully engage in this campaign?</li>
		<li>Is there any external funding available to do the work?  (ex. Community Transformation Grants, etc.)</li>
	</ul>

<?php
}

function cc_aha_print_criterion_school_diet_2( $metro_id ) {
	$data = cc_aha_get_form_data( $metro_id );
	$school_data = cc_aha_get_school_data( $metro_id );
	
	$section = 'school';
	$impact_area = 'diet';
	$group = 'school_diet_2';
	
	$qids = array( '3.2.1.1' );
	$meals_percent = cc_aha_calc_n_question_district_yes_percent( $school_data, $qids );
	
	?>
	<h5>Current State</h5>
	<p><?php echo $meals_percent; ?>% of the top 5 school districts in your community are meeting federal nutrition regulations for school meals.</p>
		
	<table>
		<thead>
			<tr>
				<th>Top 5 School Districts</th>
				<th>Compliant with the School Meals Nutrition regulations</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ( $school_data as $school ) {
				?>
				<tr>
					<td><?php echo $school['DIST_NAME'] . '<br / >' . $school['DIST_ID']; ?></td>
					<td><?php if ( isset ( $school['3.2.1.1']) && $school['3.2.1.1'] != '' ) {
						echo $school['3.2.1.1'] ? 'Yes' : 'No'; 
					} else {
						echo 'Don\'t Know';
					} ?></td>
				</tr>
				<?php
			} ?>
		</tbody>
	</table>
	
	<p>Based on preliminary analyses there <?php echo $data['3.2.2'] ? 'are' : 'are not'; ?> impactful opportunities for the local board to help school districts implement School Meals Nutrition regulations in this community.
	</p>
	
	<h5>Discussion Questions</h5>
	
	<strong>Does the community have capacity to take on this issue?</strong>
	<ul>
		<li>Are there meaningful opportunities to help schools and districts implement the School Meals or Smart Snacks guidelines?</li>
		<li>What potential community coalition partners are in place?</li>
		<li>What is the current political climate?</li>
		<li>Do the volunteers have the needed skills?</li>
		<li>Is there any external funding available to do the work?  (ex. Community Transformation Grants, etc.)?</li>
		<li>What is the status of the local school district nutrition director?</li>
		<li>Has he/she been outspoken about the school district’s ability or inability to comply with the federal law?</li>
	</ul>
	
	<?php 
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
	
	//if we have multiple question rows in table for group, make sure we get them all
	foreach ( $disc_questions as $question ) {
	
		//make sure we're looking at the right set of questions
		if ( $question['summary_section'] == $group ) { 
			echo $question['summary_label'];
		}
	}
	
	?>
	
	<?php 
}

//6.1 CMS PENALTY: Total Discharges & Underserved Discharges
function cc_aha_print_criterion_care_acute_1( $metro_id ) {
	//Just need the function to get rid of the warning, no actual data being displayed here, except the dial
}
function cc_aha_print_criterion_care_acute_2( $metro_id ) {
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
	
	<p><em>*Underserved – Racially / diverse patients and/or Medicaid patients. </em></p>
	
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
}

//school_cpr_1
function cc_aha_print_criterion_school_cpr_1( $metro_id ) {
	$counties = cc_aha_get_county_data( $metro_id );
	$data = cc_aha_get_form_data( $metro_id );
	$school_data = cc_aha_get_school_data( $metro_id );
	
	$section = 'school';
	$impact_area = 'cpr';
	$group = 'school_cpr_1';
	
	$qids = array( '5.1.4.1' );
	$cpr_percent = cc_aha_calc_n_question_district_yes_percent( $school_data, $qids );
	
	?>
	<h5>Current State</h5>
	<p><?php echo $cpr_percent; ?>% of school districts have CPR as a graduation requirement meeting AHA’s guidelines.</p>
	
	<p><?php echo $data['State']; ?> <?php echo $data['5.1.1'] ? 'does' : 'does not'; ?> have CPR graduation requirements in place.</p>
	
	
	<table>
		<thead>
			<tr>
				<th>Top 5 School Districts</th>
				<th>CPR Graduation Requirements</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ( $school_data as $school ) {
				?>
				<tr>
					<td><?php echo $school['DIST_NAME'] . '<br / >' . $school['DIST_ID']; ?></td>
					<td><?php if ( isset ( $school['5.1.4.1']) && $school['5.1.4.1'] != '' ) {
						echo $school['5.1.4.1'] ? 'Yes' : 'No'; 
					} else {
						echo 'Don\'t Know';
					} ?></td>
				</tr>
				<?php
			} ?>
		</tbody>
	</table>
	
	<p>
	<?php if ( $data['5.1.3'] == 'Not a viable issue at any level at this time' ) {
		echo 'Preliminary analyses indicate that this is not a viable issue at this time.';
	} else if ( $data['5.1.3'] != '' ) { 
		echo 'Given the current political/policy environment, we envision policies requiring CPR training for high school graduation will most likely occur ' . strtolower( $data['5.1.3'] );
	}
	?></p>
	
	<p>
	<?php if ( $data['5.1.2'] == 'No – none of the above' ) {
		echo 'We do not anticipate that policies requiring CPR training for high school graduation will be passed in ' . $data['State'] . ' in the next 3 years.';
	} else if ( $data['5.1.2'] != '' ){ 
		echo 'We anticipate policies requiring CPR training for high school graduation will be passed in ' . $data['State'] . ' in ' . $data['5.1.2'];
	}
	?></p>
	
	
	
	
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
}




//TODO: move these to summary-template-tags(-1)

// Generalized to identify 0- <80, 80-90 and >90% tiers
function cc_aha_calc_three_tiers_80( $metro_id, $qid ) {
	if ( ! $metro_id )
		$metro_id = $_COOKIE['aha_summary_metro_id'];

	$data = cc_aha_get_form_data( $metro_id );

	if ( $data[ $qid ] > 90 ) {
		return 'healthy';
	} else if ( ( $data[ $qid ] >= 80 ) && ( $data[ $qid ] <= 90 ) ) {
		return 'intermediate';
	} else {
		return 'poor';
	}
}

// Generalized to identify <50, 50-90 and >90% tiers
function cc_aha_calc_three_tiers_50( $metro_id, $qid ) {
	if ( ! $metro_id )
		$metro_id = $_COOKIE['aha_summary_metro_id'];

	$data = cc_aha_get_form_data( $metro_id );

	if ( $data[ $qid ] > 90 ) {
		return 'healthy';
	} else if ( ( $data[ $qid ] >= 50 ) && ( $data[ $qid ] <= 90 ) ) {
		return 'intermediate';
	} else {
		return 'poor';
	}
}


function cc_aha_calc_n_question_district_yes_percent( $school_data, $qids = array() ) {
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
	
	if ( $total_questions > 0 ){
		return ( $num_yes / $total_questions ) * 100;
	} else {
		return false;
	}
}
