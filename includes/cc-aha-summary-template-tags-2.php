<?php 
/**
 * CC American Heart Association Extras; Summary Template Tags part deux
 *
 * @package   CC American Heart Association Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2014 CommmunityCommons.org
 */


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
	echo PHP_EOL . ">> open response";
	echo PHP_EOL . ">> top 3";

}

//school_cpr_1
function cc_aha_print_criterion_school_cpr_1( $metro_id ) {
	$counties = cc_aha_get_county_data( $metro_id );
	$data = cc_aha_get_form_data( $metro_id );
	$school_data = cc_aha_get_school_data( $metro_id );
	
	$section = 'care';
	$impact_area = 'acute';
	$group = 'care_acute_1';
	
	$qids = array( '5.1.4.1' );
	$cpr_percent = cc_aha_calc_n_question_district_yes_percent( $school_data, $qids );
	
	?>
	<h5>Current State</h5>
	<p><?php echo $cpr_percent; ?>% of school districts have CPR as a graduation requirement meeting AHA’s guidelines.</p>
	
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
	echo PHP_EOL . ">> open response";
	echo PHP_EOL . ">> top 3";

}
