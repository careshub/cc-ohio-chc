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
	$policy_percent = cc_aha_calc_n_question_district_all_yes_percent( $school_data, $qids );
	?>
	
	<h5>Current State</h5>
	<p><?php echo $policy_percent; ?>% of the top 5 school districts have a documented and publicly available wellness policy covering school meals, smart snacks and before/after school offerings meeting AHA criteria.
	</p>
	<p><em>For a community to earn a <span class="healthy">“Healthy”</span> score, 100% of the top 5 school districts must meet this criteria.</em></p>
	
	<?php //TODO: check new data from Ben once incorporated 
	?>
	<p>The competitive foods policy in <?php echo $data['State'] ?> is <?php echo $data['3.1.1.1']; ?>.</p>
	
	<p>The competitive foods policy defense priority level in <?php echo $data['State'] ?> is <?php echo $data['3.1.1.1.1'] ?>.
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
					<td><?php echo $school['DIST_NAME']; ?></td>
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
						$url_text = '<a href="' . $school['3.1.3.1.4'] . '" target="_blank">View Policy</a>';
						echo $school['3.1.3.1.4'] ? $url_text : 'No'; 
					} else {
						echo 'Don\'t Know';
					} ?>
					</td>
					<td><?php if ( isset ( $school['3.1.2']) && $school['3.1.2'] != '' ) {
						echo $school['3.1.2'] ? 'Yes' : 'No'; //TODO: check new data from Ben once incorporated 
					} ?>
					</td>
				</tr>
				<?php
			} ?>
		</tbody>
	</table>
	
	<!--<ul>
		<li><?php 
		/*if ( ( $data['3.1.4'] == 'No – none of the above' ) || ( $data['3.1.4'] == 'no' ) || ( $data['3.1.4'] == '' ) ) {
			echo 'Preliminary analyses indicate that this is not a viable issue at this time.';
		} else {
			echo 'Possible opportunities to drive impact include: ' . $data['3.1.4'];
			
			//TODO: Mel, figure this out based on data we're no longer collecting
			//echo ' potentially in ' . $data['3.1.4'];
		} */
		?></li>
	</ul>-->
		
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
		<li>What are the biggest needs for impacting school nutrition policies in your community?
			<ul>
				<li>Publish / strengthen the district wellness policy </li>
				<li>Open a door for the Alliance for a Healthier Generation</li>
				<li>Strengthen competitive foods policy by applying nutrition standards to after school activities</li>
				<li>Strengthen competitive foods policy by addressing celebrations and fundraisers</li>
				<li>Other?</li>
			</ul>
		</li>
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
	<p><em>For a community to earn a <span class="healthy">“Healthy”</span> score, 100% of the top 5 school districts must meet this criteria.</em></p>
	
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
					<td><?php echo $school['DIST_NAME']; ?></td>
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
	$data = cc_aha_get_form_data( $metro_id );
	
	$section = 'care';
	$impact_area = 'factors';
	$group = 'care_factors_1';
	
	?>
	<h5>Current State</h5>
	<p><?php echo $data['4.1.1']; ?>% of residents aged <65 have health insurance.</p>
	<p><em>For a community to earn a <span class="healthy">“Healthy”</span> score, >90% of residents aged <65 must have health insurance.</em></p>
	
	<p><?php echo $data['State']; ?> <?php echo $data['4.1.2.1'] ? 'is' : 'is not'; ?> covered by Medicaid expansion.</p>
	
	<p><?php echo $data['State']; ?> <?php echo $data['4.1.2.2'] ? 'is' : 'is not'; ?> covered by USPSTF A and/or B.</p>
	
	<h5>Policy Landscape</h5>
	<ul>
		<li><?php 
		if ( ( $data['4.1.4'] == 'Not a viable issue at any level at this time' ) || ( $data['4.1.4'] == 'no' ) || ( $data['4.1.4'] == '' ) ) {
			echo 'Preliminary analyses indicate that this is not a viable issue at this time.';
		} else if ( ( $data['4.1.4'] == 'Yes – Medicaid expansion at the state level' ) || ( $data['4.1.4'] == 'yes - Medicaid expansion' ) ) {
			echo 'Given the current political/policy environment, we envision “Medicaid expansion at the state level”'; 
			if ( isset( $data['4.1.3.1'] ) ) 
				echo ' potentially in ' . $data['4.1.3.1'];
		} else if ( ( $data['4.1.4'] == 'Yes – USPSTF at the state level' ) || ( $data['4.1.4'] == 'yes - USPSTF' ) ) {
			echo 'Given the current political/policy environment, we envision “USPSTF A and B cardiovascular preventative benefits under Medicaid” will be passed in your state'; 
			if ( isset( $data['4.1.3.2'] ) ) 
				echo ' potentially in ' . $data['4.1.3.2'];
		}
		?></li>
	</ul>
	
	<h5>Discussion Questions</h5>
	<strong>Does the community have capacity to take on this issue?</strong>
	<ul>
		<li>Is there an active campaign around this issue in the community?</li>
		<li>What potential and existing coalition partners are in place?</li>
		<li>What is the current political climate?</li>
		<li>Do the board members and other AHA volunteers have the capacity to lead and fully engage in this campaign?</li>
		<li>Is there any external funding available to do the work?</li>
		<li>Are there state legislators from this community that we need to engage for the statewide campaign?</li>
		<li>What is the Grassroots capacity of the board to engage in the opportunity?</li>
	</ul>
	<?php 
}

//6.1 CMS PENALTY: Total Discharges & Underserved Discharges
function cc_aha_print_criterion_care_acute_1( $metro_id ) {
	//Just need the function to get rid of the warning, no actual data being displayed here, except the dial
}
function cc_aha_print_criterion_care_acute_2( $metro_id ) {
	$data = cc_aha_get_form_data( $metro_id );
	$hospitals = cc_aha_get_hospital_data( $metro_id );
	
	$section = 'care';
	$impact_area = 'acute';
	$group = 'care_acute_1';
	
	?>
	<h5>Current State</h5>
	<p><?php echo $data['6.1.1']; ?>% of TOTAL CVD discharges and <?php echo $data['6.1.2']; ?>% of "UNDERSERVED" CVD discharges are from hospitals with a CMS bonus penalty above -0.4%.</p>
	<p><em>For a community to earn a <span class="healthy">“Healthy”</span> score, >90% of total CVD discharges and “underserved” patient CVD discharges in the community must be from hospitals with a CMS bonus/penalty above -0.4%.</em></p>
	
	<p><em>*Underserved – Racially/ethnically diverse patients and/or Medicaid patients. </em></p>
	
	<?php if ( $hospitals ) : ?>
	<table>
		<thead>
			<tr>
				<th>Hospital Name</th>
				<th>TOTAL CVD Discharges (2012)</th>
				<th>Underserved CVD Discharges (Weighted – 2012)</th>
				<th>Any AHA Quality Program</th>
				<th>Total VBP Bonus/Penalty</th>
				<th>TOTAL CVD Discharge Target Rank</th>
				<th>Underserved CVD Discharge Target Rank</th>
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
	<?php endif; ?>
	
	<h5>Discussion Questions</h5>
	<ul>
		<li>Based upon the list of the most penalized hospitals in your community, are there board members that can help open the door to provide AHA quality improvement solutions for these hospitals?</li>
		<li>Are there additional hospitals that the market has prioritized for which board members can help open the door for AHA’s quality programs?</li>
		<li>Are there other healthcare quality improvement gaps that the Board can help address?</li>
	</ul>
	
	<?php 
}

//school_cpr_1
function cc_aha_print_criterion_school_cpr_1( $metro_id ) {
	$data = cc_aha_get_form_data( $metro_id );
	$school_data = cc_aha_get_school_data( $metro_id );
	
	$section = 'school';
	$impact_area = 'cpr';
	$group = 'school_cpr_1';
	
	$qids = array( '5.1.4.1' );
	$cpr_percent = cc_aha_calc_cpr_percent( $metro_id );
	
	?>
	<h5>Current State</h5>
	<p><?php echo $cpr_percent; ?>% of school districts have CPR as a graduation requirement meeting AHA’s guidelines.</p>
	<p><em>For a community to earn a <span class="healthy">“Healthy”</span> score, 100% of the top 5 school districts must meet this criteria.</em></p>
	
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
					<td><?php echo $school['DIST_NAME']; ?></td>
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
	<?php if ( $data['5.1.3'] == 'neither' ) {
		echo 'Preliminary analyses indicate that this is not a viable issue at this time.';
	} else if ( $data['5.1.3'] != '' ) { 
		echo 'Given the current political/policy environment, we envision policies requiring CPR training for high school graduation will most likely occur at the ' . $data['5.1.3'] . ' level.';
	}
	?></p>
	
	<p>
	<?php if ( ! $data['5.1.2'] ) {
		echo 'We do not anticipate that policies requiring CPR training for high school graduation will be passed in ' . $data['State'] . ' in the next 3 years.';
	} else { 
		echo 'We anticipate policies requiring CPR training for high school graduation will be passed in ' . $data['State'] . ' in ' . $data['5.1.2'] . '.';
	}
	?></p>
	
	<h5>Discussion Questions</h5>
	<strong>Does the community have capacity to take on this issue?</strong>
	
	<ul>
		<li>What potential coalition partners are in place?</li>
		<li>What is the current political climate?</li>
		<li>Do the board members and other AHA volunteers have the capacity to lead and fully engage in this campaign?</li>
		<li>Are there volunteer leaders engaged with AHA who are passionate about the issue?</li>
		<li>Is there any external funding available to do the work?</li>
	</ul>
	
	<?php
}

/*
 * Produce page code for Environmental Scan form with appropriate data if filled out already
 *
 */
function cc_aha_print_environmental_scan( $metro_id = 0 ) {

	$questions = cc_aha_get_environmental_scan_questions();
	$data = cc_aha_get_form_data( $metro_id );
	
?>
	<section id="environmental-scan" class="clear">
		<form id="aha_summary-<?php echo $section . '-' . $impact_area; ?>" class="standard-form aha-survey" method="post" action="<?php echo cc_aha_get_home_permalink() . 'update-summary/'; ?>">
		
		<h2 class="screamer">Environmental Scan</h2>
		<div class="content-row">
		
			<strong>For the following section please consult your Community Health Needs Assessment as well as discuss these questions strategically with staff and volunteers.</strong>
			
			<ul>
				<?php foreach( $questions['questions'] as $question ){ 
					$title = "environmental-scan-" . $question['name']; 
				?>
				
					<fieldset>
						<label for="<?php echo $title; ?>"><h6><?php echo ucfirst( $question['name'] ) . ' - ' . $question['label']; ?></h6>
						<textarea id="<?php echo $title; ?>" name="board[<?php echo $title; ?>]"><?php echo $data[$title]; ?></textarea>

					</fieldset>
					
				<?php } ?>				
				
			</ul>
		</div>
		
		<input type="hidden" name="metro_id" value="<?php echo $metro_id; ?>">
		
		<?php wp_nonce_field( 'cc-aha-assessment', 'set-aha-assessment-nonce' ) ?>
			
		<div class="form-navigation clear">
			<div class="submit">
				<input type="submit" name="submit-survey-to-toc" value="Save, Return to Table of Contents" id="submit-survey-to-toc">
			</div>
		</div>
		</form>

<?php
}

function cc_aha_print_environmental_scan_link(){
?>
	<a href="<?php echo cc_aha_get_analysis_permalink() . 'environmental-scan/' ; ?>">
		Environmental Scan
	</a>
<?php
}

/* 
 * Returns array of Environmental Scan questions 
*/
function cc_aha_get_environmental_scan_questions() {

	return array( 
		'questions' => array(
			'Health' => array(
				'name' => 'health',
				'label' => 'What are the areas of greatest health need for your community? Are there sub-populations of higher need? See your CHNA and consider the Simple 7, mortality, etc.'
			),
			'Demographica' => array(
				'name' => 'demographics',
				'label' => 'What major demographic changes are occurring in your community which your plan should consider?  (Ex. growing diverse or aging populations)'
			),
			'Political' => array(
				'name' => 'political',
				'label' => 'What is the present political environment in your community?  Is this an election year in your community or state?  Do you anticipate significant shifts in the political landscape in the next 3 years?  Is the general political climate accepting of health related priorities?  Has your community adopted a “Health in All Policies” approach?'
			),
			'Economic' => array(
				'name' => 'economic',
				'label' => 'What is the present economic environment in your community?  What is the unemployment rate?  Is your community economically stable?  Do you anticipate that changing in the next 3 years?'
			),
			'Social' => array(
				'name' => 'social',
				'label' => 'Is your community socially stable or are there social issues (homelessness, poverty, discrimination) that are social justices issues that may impact health or health policy?'
			),
			'Environmental' => array(
				'name' => 'environmental',
				'label' => 'Is your community environmentally progressive?  Is there strong policy and adoption of environmentally sound practices?'
			),
			'Education' => array(
				'name' => 'education',
				'label' => 'What is the general education climate?  Graduation rates?  Significant variation between areas of the community?  Quality of schools?'
			),
			'Other' => array(
				'name' => 'other',
				'label' => 'Other Opportunities or Barriers that are not identified above that might influence interest in improved health initiatives'
			),
		),
	);
}

// Generalized to identify 0- <80, 80-90 and >90% tiers
function cc_aha_calc_three_tiers_80( $metro_id, $qid ) {
	if ( ! $metro_id )
		$metro_id = $_COOKIE['aha_summary_metro_id'];

	$data = cc_aha_get_form_data( $metro_id );

	if ( $data[ $qid ] > 90 ) {
		return 'healthy';
	} else if ( $data[ $qid ] >= 80 ) {
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
	} else if ( $data[ $qid ] >= 50 ) {
		return 'intermediate';
	} else {
		return 'poor';
	}
}

/*
 * Iterates through school questions for a board and calculates percent 'yes' answers of total
 *
 */
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

/*
 * Iterates through school questions for a board and calculates number of districts
 * 	that have TOTAL 'yes'es for series of questions
 * 
 * (used for logic in school_diet_1)
 */
function cc_aha_calc_n_question_district_all_yes_percent( $school_data, $qids = array() ) {
	$num_yes = 0;
	$total_questions = 0;
	$all_district_question_is_yes = 0; //var for final 'are they all yeses?'
	$num_districts = 0;
	
	//loop through each school
	foreach ( $school_data as $school ){
		$num_districts++;
		
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
		
		if ( $num_yes == $total_questions ){
			$all_district_question_is_yes++;
		} 
		$total_questions = 0;
		$num_yes = 0;
	}
	
	if ( $num_districts > 0 ){
		return ( $all_district_question_is_yes / $num_districts ) * 100;
	} else {
		return false;
	}
}

function cc_aha_calc_n_question_district_add_amount( $school_data, $qids = array() ) {
	$total_amount = 0;
	
	//loop through each school
	foreach ( $school_data as $school ){
	
		//loop through each question for this school
		foreach( $qids as $qid ){
			$num = intval($school[ $qid ]);
			$total_amount = $total_amount + $num;
		}
	}
	
	return $total_amount;
}
