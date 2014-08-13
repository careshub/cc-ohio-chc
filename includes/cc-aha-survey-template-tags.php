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
 * All the page titles kept in one place, for easier updating
 * Used tp produce table of contents and page header text.
 *
 * @since   1.0.0
 * @return 	array
 */
function cc_aha_form_page_list(){
	return array(
	1 => 'Table of Contents',
	2 => 'Complete Streets',
	3 => 'Local Government Procurement Policy (Vending & Service Contracts)',
	4 => 'Healthy Food Financing',
	5 => 'Health Factors - Insurance Coverage',
	6 => 'Quality Physical Education in Schools',
	7 => 'Shared Use Policies',
	8 => 'Shared Use Policies, continued',
	9  => 'School Nutrition Policies',
	10  => 'School Nutrition Policies, continued',
	11  => 'Chain of Survival - CPR Graduation Requirements',
	12  => 'Recruit Event Leadership',
	13  => 'Secure Top ELT Leadership',
	14  => 'Secure Platform/Signature Sponsorship',
	15  => 'Expand Youth Market Efforts - Participating Schools',
	16  => 'Increase Individual Giving - Individual Giving Prospects',
	17  => 'Increase Individual Giving - Cor Vitae Recruitment',
	18  => 'Enhance Donor Stewardship - Donor Retention',
	19  => 'Membership in the Paul Dudley White Legacy Society - Donor Retention'
	);
}

function cc_aha_get_max_page_number(){
    return count( cc_aha_form_page_list() );
}

/**
 * Output logic for the form. includes the wrapper pieces.
 * Question building is handled separately
 *
 * @since   1.0.0
 * @return 	outputs html
 */
function cc_aha_render_form( $page = null ){
	$page = (int) $page;
	$last_page = cc_aha_get_max_page_number();
	$page = ( !empty( $page ) && ( $page >= 1 && $page <= $last_page ) ) ? $page : 1;

	if ( ! isset( $_COOKIE['aha_active_metro_id'] ) )
		return;

	?>
	<form id="aha_survey-page-<?php echo $page; ?>" class="standard-form aha-survey" method="post" action="<?php echo cc_aha_get_home_permalink() . 'update-assessment/' . $page; ?>">
		<?php

			// Some pages can be auto-built. Others we're going to hand-code.
			$hand_built = array( 1, 6, 7, 9, 11 );
			if ( in_array( $page, $hand_built ) ) {
				$aha_form_function_name = 'cc_aha_handcoded_questions_' . $page;
				$aha_form_function_name();
			} else {
				cc_aha_auto_build_questions( $page );
			}
		?>
		<input type="hidden" name="board[<?php echo 'p' . $page; ?>]" value="1">
		<input type="hidden" name="metro_id" value="<?php echo $_COOKIE['aha_active_metro_id']; ?>">
		<?php wp_nonce_field( 'cc-aha-assessment', 'set-aha-assessment-nonce' ) ?>
		<div class="form-navigation clear">
		<?php if ( $page == 1 ) : ?>
			<a href="<?php echo cc_aha_get_home_permalink() . cc_aha_get_quick_survey_summary_slug(); ?>" class="button alignleft">View Quick Summary of Responses</a>
			<a href="<?php echo cc_aha_get_survey_permalink(2); ?>" class="begin-survey button alignright">Begin Survey</a>
		<?php else: ?>
			<div class="submit toc alignleft">
		        <input id="submit-survey-to-toc" type="submit" value="Save, Return to Table of Contents" name="submit-survey-to-toc">
		    </div>
			<div class="submit">
		        <input id="submit-survey-next-page" type="submit" value="Save Responses and Continue" name="submit-survey-next-page">
		    </div>
		<?php endif; ?>
		</div>
	</form>
	<?php
}

//For the pages we can auto build
function cc_aha_auto_build_questions( $page ) {
	$questions = cc_aha_get_form_questions( $page );
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 1 );
	?>
	<h2><?php cc_aha_print_form_page_header( $page ); ?></h2>

	<?php 
		foreach ( $questions as $question ) {
			if ( $question[ 'loop_schools' ] ) {
				cc_aha_render_school_question( $question, $data );
			} else {
				cc_aha_render_question( $question, $data );
			}
		}
	?>

	<?php
}


function cc_aha_handcoded_questions_1(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 1 );
	?>
	<p>Introductory paragraph. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut adipiscing sem a nisl egestas, nec tempus massa pretium. Nam sagittis hendrerit lectus eget imperdiet. Nunc eget est magna. Nullam adipiscing, urna eu tempus dictum, mi mauris malesuada ligula, non pulvinar tellus dolor id velit. Fusce et augue nec libero elementum porttitor in quis ligula. Cras lacinia turpis a dictum malesuada. Duis gravida dapibus commodo.</p>

	<h2><?php cc_aha_print_form_page_header( 1 ); ?></h2>
	<ul>
		<?php 
		$all_pages = cc_aha_form_page_list();

			foreach ($all_pages as $page_number => $label) {
				// Don't list page 1 here:
				if ( $page_number == 1 )
					continue;
				// Add section headers.
				switch ( $page_number ) {
				 	case 2:
				 		echo '<h5>Health Assessment: Community Policies</h5>';
				 		break;
				 	case 6:
				 		echo '<h5>Health Assessment: Top 5 School District Policies</h5>';
				 		break;
				 	case 12:
				 		echo '<h5>Revenue Assessment</h5>';
				 		break;
				 	
				 	default:
				 		# code...
				 		break;
				 } 
			?>
			<li><a href="<?php echo cc_aha_get_survey_permalink( $page_number ); ?>"><?php echo $label; ?></a><?php if ( $data['p'.$page_number] ) { echo '&emsp;<em>completed</em>'; } ?></li>
			<?php 
			}
		?>
	</ul>

	<?php
}

function cc_aha_handcoded_questions_6(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 2 );
	$school_districts = cc_aha_get_school_data( $_COOKIE['aha_active_metro_id'] );
	?>

	<h2><?php cc_aha_print_form_page_header( 6 ); ?></h2>
		<?php 
	if ( $data['2.1.1.1'] && $data['2.1.1.2'] && $data['2.1.1.3'] ) {
		echo "Your state has enacted state-wide physical education requirements at all levels.";
	} else {
			
		foreach ($school_districts as $district) {
			?>
			<fieldset class="spacious">

				<legend><h4><?php cc_aha_print_school_question_text( '2.1.4.1.1', $district ); ?></h4></legend>

				<?php if ( ! $data['2.1.1.1'] ) : ?>
					<label>Elementary (150 mins) <?php aha_render_school_boolean_radios( '2.1.4.1.1', $district ); ?></label>
				<?php 
				endif;
				if ( ! $data['2.1.1.2'] ) :
				?>
					<label>Middle (225 mins) <?php aha_render_school_boolean_radios( '2.1.4.1.2', $district ); ?></label>
				<?php 
				endif;
				if ( ! $data['2.1.1.3'] ) :
				?>
					<label>High School (Graduation Requirement) <?php aha_render_school_boolean_radios( '2.1.4.1.3', $district ); ?></label>
				<?php endif; ?>
			</fieldset>
			<?php
		}
	}
}

function cc_aha_handcoded_questions_7(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 9 );
	$school_districts = cc_aha_get_school_data( $_COOKIE['aha_active_metro_id'] );
	?>

	<h2><?php cc_aha_print_form_page_header( 7 ); ?></h2>
	<?php
	foreach ( $school_districts as $district ) {
		?>
		<fieldset class="spacious">
			<legend><h4><?php cc_aha_print_school_question_text( '2.2.5.1', $district ); ?></h4></legend>
			<?php aha_render_school_radio_group( '2.2.5.1', $district ); ?>
		</fieldset>

			<div class="follow-up-question" data-relatedTarget="<?php echo $district['DIST_ID']; ?>-2.2.5.1.1">
				<fieldset>
					<legend><?php cc_aha_print_school_question_text( '2.2.5.1.1', $district ); ?> </legend>
					<?php
						aha_render_school_radio_group( '2.2.5.1.1', $district );
					?>
				</fieldset>
			</div>

			<div class="follow-up-question" data-relatedTarget="<?php echo $district['DIST_ID']; ?>-2.2.5.1.1.1">
				<label><?php
					cc_aha_print_school_question_text( '2.2.5.1.1.1', $district );
					aha_render_school_textarea_input( '2.2.5.1.1.1', $district );
					?></label>
			</div>

			<div class="follow-up-question" data-relatedTarget="<?php echo $district['DIST_ID']; ?>-2.2.5.1.3">
				<label><?php
				cc_aha_print_school_question_text( '2.2.5.1.3', $district );
				aha_render_school_text_input( '2.2.5.1.3', $district );
				?></label>
			</div>

		<?php
	}
}

function cc_aha_handcoded_questions_9(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 10 );
	$school_districts = cc_aha_get_school_data( $_COOKIE['aha_active_metro_id'] );
	?>

	<h2><?php cc_aha_print_form_page_header( 9 ); ?></h2>
	<?php
	foreach ( $school_districts as $district ) {
			// School district stuff will require a different save routine, since they're keyed by district ID.
			?>
			<fieldset class="spacious">
				<legend><h4><?php cc_aha_print_school_question_text( '3.1.3.1.0', $district ); ?></h4></legend>
				<?php aha_render_school_radio_group( '3.1.3.1.0', $district ); ?>
			</fieldset>

			<div class="follow-up-question" data-relatedTarget="<?php echo $district['DIST_ID'] . '-3.1.3.1.x'; ?>">
				<fieldset>
					<label><?php 
					cc_aha_print_school_question_text( '3.1.3.1.1', $district );
					aha_render_school_boolean_radios( '3.1.3.1.1', $district ); 
					?></label>
					<label><?php
					cc_aha_print_school_question_text( '3.1.3.1.2', $district );
					aha_render_school_boolean_radios( '3.1.3.1.2', $district );
					?></label>
					<label><?php 
					cc_aha_print_school_question_text( '3.1.3.1.3', $district );
					aha_render_school_boolean_radios( '3.1.3.1.3', $district );
					?></label>
					<label><?php
					cc_aha_print_school_question_text( '3.1.3.1.4', $district );
					aha_render_school_text_input( '3.1.3.1.4', $district );
					?></label>
				</fieldset>
			</div>
			<?php
		}
	//aha_render_checkbox_input( '3.1.4', $data);
}

function cc_aha_handcoded_questions_11(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 2 );
	$questions = cc_aha_get_form_questions( 11 );
	?>

	<h2><?php cc_aha_print_form_page_header( 11 ); ?></h2>
	<?php 
	if ( $data['5.1.1'] ) {
		echo "Your state has CPR graduation requirements in place.";
	} else {
		foreach ( $questions as $question ) {
			cc_aha_render_school_question( $question, $data );
			//aha_render_school_boolean_radios( '5.1.4.1', $district );
		}
	}
}

function cc_aha_render_question( $question, $data ){
	if ( $question[ 'follows_up' ] ) {
		?>
		<div class="follow-up-question" data-relatedTarget="<?php echo $question[ 'QID' ] ; ?>">
		<?php
	} 
	switch ( trim( $question[ 'type' ] ) ) {
		case 'text':
		?>
			<label for="<?php echo $question[ 'QID' ]; ?>"><h4><?php echo $question[ 'label' ]; ?></h4></label>
			<?php aha_render_text_input( $question[ 'QID' ], $data );
			break;
		case 'number':
		?>
			<label for="<?php echo $question[ 'QID' ]; ?>"><h4><?php echo $question[ 'label' ]; ?></h4></label>
			<?php cc_aha_render_number_input( $question[ 'QID' ], $data, .01 );
			break;
		case 'radio':
		?>	<fieldset>
				<label for="<?php echo $question[ 'QID' ]; ?>"><h4><?php echo $question[ 'label' ]; ?></h4></label>
				<?php aha_render_radio_group( $question[ 'QID' ], $data ); ?>
			</fieldset>
			<?php
			break;
		case 'textarea':
		?>	<fieldset>
				<label for="<?php echo $question[ 'QID' ]; ?>"><h4><?php echo $question[ 'label' ]; ?></h4></label>
				<?php aha_render_textarea_input( $question[ 'QID' ], $data ); ?>
			</fieldset>
			<?php
			break;
		case 'checkboxes':
		?>	<fieldset>
				<label for="<?php echo $question[ 'QID' ]; ?>"><h4><?php echo $question[ 'label' ]; ?></h4></label>
				<?php cc_aha_render_checkboxes( $question[ 'QID' ], $data ); ?>
			</fieldset>
			<?php
			break;
		
		default:
			echo "default thing";

			break;
	}
	if ( $question[ 'follows_up' ] ) {
		?>
		</div>
		<?php
	} 
}
function cc_aha_render_school_question( $question, $data ){
	// These questions have to loop through each of the associated school districts.
	// They also have to use different output functions. :(

	$school_districts = cc_aha_get_school_data( $_COOKIE['aha_active_metro_id'] );

	foreach ( $school_districts as $district ) {
		$qname = 'school[' . $district['DIST_ID'] . '][' . $question[ 'QID' ] . ']'; 

		if ( $question[ 'follows_up' ] ) {
			?>
			<div class="follow-up-question" data-relatedTarget="<?php echo $district['DIST_ID'] . '-' . $question[ 'QID' ] ; ?>">
			<?php
		} 
		switch ( trim( $question[ 'type' ] ) ) {
			case 'text':
			?>
				<label for="<?php echo $qname; ?>"><h4><?php cc_aha_print_school_question_text( $question[ 'QID' ], $district ); ?></h4></label>
				<?php aha_render_school_text_input( $question[ 'QID' ], $district );
				break;
			case 'number':
			?>
				<label for="<?php echo $qname; ?>"><h4><?php cc_aha_print_school_question_text( $question[ 'QID' ], $district ); ?></h4></label>
				<?php cc_aha_render_school_number_input( $question[ 'QID' ], $district, .01 );
				break;
			case 'radio':
			?>	<fieldset>
					<label for="<?php echo $qname; ?>"><h4><?php cc_aha_print_school_question_text( $question[ 'QID' ], $district ); ?></h4></label>
					<?php aha_render_school_radio_group( $question[ 'QID' ],  $district ); ?>
				</fieldset>
				<?php
				break;
			case 'textarea':
			?>	<fieldset>
					<label for="<?php echo $qname; ?>"><h4><?php cc_aha_print_school_question_text( $question[ 'QID' ], $district ); ?></h4></label>
					<?php aha_render_school_textarea_input( $question[ 'QID' ],  $district ); ?>
				</fieldset>
				<?php
				break;
			default:
				echo "That question type isn't supported.";
				break;
		}
		if ( $question[ 'follows_up' ] ) {
			?>
			</div>
			<?php
		}
	} // End foreach district
}

function aha_render_boolean_radios( $qid, $data, $follow_up_id = null, $follow_up_on_value = 1 ) {
	?>
	
	<label><input type="radio" name="<?php echo 'board[' . $qid . ']'; ?>" value="1" <?php if ( $data[ $qid ] ) echo 'checked="checked"'; ?> <?php if ( $follow_up_id ) echo 'class="has-follow-up"'; ?> <?php if ( $follow_up_id && $follow_up_on_value == 1 ) echo 'data-relatedQuestion="' . $follow_up_id .'"'; ?>> Yes</label>
	<label><input type="radio" name="<?php echo 'board[' . $qid . ']'; ?>" value="0" <?php if ( ! $data[ $qid ] ) echo 'checked="checked"'; ?> <?php if ( $follow_up_id ) echo 'class="has-follow-up"'; ?> <?php if ( $follow_up_id && $follow_up_on_value == 0 ) echo 'data-relatedQuestion="' . $follow_up_id .'"'; ?>> No</label>
	<?php 
}

function aha_render_radio_group( $qid, $data, $options = array() ){

	if ( empty( $options ) )
		$options = cc_aha_get_options_for_question( $qid );

	// If any of the options triggers a follow-up question, each option must have the class applied.
	// Only the "trigger" response needs the "data-relatedquestion" bit.
	$has_follow_up = cc_aha_question_has_follow_up( $options );

	foreach ($options as $option) {
		?>
		<label><input type="radio" name="<?php echo 'board[' . $qid . ']'; ?>" value="<?php echo $option['value']; ?>" <?php 
			checked( $data[ $qid ], $option['value'] );

			// If any option specifies a follow-up, all must include the class
			if ( $has_follow_up ) 
				echo 'class="has-follow-up"';
			// If this option is the trigger, add the data
			if ( $option['followup_id'] )
				echo 'data-relatedQuestion="' . $option['followup_id'] .'"'; 

			?>> <?php echo $option['label']; ?></label>
		<?php
		
	}
}

function aha_render_text_input( $qid, $data ){
	?>
	<input type="text" name="<?php echo 'board[' . $qid . ']'; ?>" id="<?php echo $qid; ?>" value="<?php echo $data[ $qid ]; ?>" />
	<?php
}

function cc_aha_render_number_input( $qid, $data, $step = 1 ){
	?>
	<input type="text" name="<?php echo 'board[' . $qid . ']'; ?>" id="<?php echo $qid; ?>" value="<?php echo $data[ $qid ]; ?>"/>
	<p class="input-helper">Please do not include dollar (&#36;) or percent (&#37;) signs in your answer.</p>
	<?php
}

function aha_render_textarea_input( $qid, $data ){
	?>
	<textarea name="<?php echo 'board[' . $qid . ']'; ?>" id="<?php echo $qid; ?>"><?php echo $data[ $qid ]; ?></textarea>
	<?php
}

function cc_aha_render_checkboxes( $qid, $data, $options = array() ){
	if ( empty( $options ) )
		$options = cc_aha_get_options_for_question( $qid );

	// If any of the options triggers a follow-up question, each option must have the class applied.
	// Only the "trigger" response needs the "data-relatedquestion" bit.
	$has_follow_up = cc_aha_question_has_follow_up( $options );

	// Saved data should be serialized, assuming our form created it.
	$saved_checks = maybe_unserialize( $data[ $qid ] );

	foreach ($options as $option) {
		?>
		<label><input type="checkbox" name="<?php echo 'board[' . $qid . '][' . $option['value'] . ']'; ?>" value="1" <?php 
			if ( $saved_checks[ $option['value'] ] )
				echo 'checked="checked" ';

			// If any option specifies a follow-up, all must include the class
			if ( $has_follow_up ) 
				echo 'class="has-follow-up"';
			// If this option is the trigger, add the data
			if ( $option['followup_id'] )
				echo 'data-relatedQuestion="' . $option['followup_id'] .'"'; 

			?>> <?php echo $option['label']; ?></label>
		<?php
		
	}
}

//School district-specific form fields
function aha_render_school_boolean_radios( $qid, $district, $follow_up_id = null, $follow_up_on_value = 1  ) {
	$qname = 'school[' . $district['DIST_ID'] . '][' . $qid . ']'; 
	?>
	<label><input type="radio" name="<?php echo $qname; ?>" value="1" <?php if ( $district[ $qid ] ) echo 'checked="checked"'; ?> <?php if ( $follow_up_id ) echo 'class="has-follow-up"'; ?> <?php if ( $follow_up_id && $follow_up_on_value == 1 ) echo 'data-relatedQuestion="' . $follow_up_id .'"'; ?>> Yes</label>
	<label><input type="radio" name="<?php echo $qname;  ?>" value="0" <?php if ( ! $district[ $qid ] ) echo 'checked="checked"'; ?> <?php if ( $follow_up_id ) echo 'class="has-follow-up"'; ?> <?php if ( $follow_up_id && $follow_up_on_value == 0 ) echo 'data-relatedQuestion="' . $follow_up_id .'"'; ?>> No</label>
	<?php 
}

function aha_render_school_radio_group( $qid, $district, $options = array() ){
	$qname = 'school[' . $district['DIST_ID'] . '][' . $qid . ']';
	if ( empty( $options ) )
		$options = cc_aha_get_options_for_question( $qid );

	$has_follow_up = cc_aha_question_has_follow_up( $options );

	foreach ($options as $option) {
		?>
		<label><input type="radio" name="<?php echo $qname; ?>" value="<?php echo $option['value']; ?>" <?php 
			checked( $district[ $qid ], $option['value'] );
			
			//Because 5.1.4.1 has 'YES' in the database for certain school districts and we need to render as checked if '1', 'Yes', '0' [, 'No']
			//Mel: seems safe enough, tested with range of values in database ('Yes', '1', '1', '0', 'No') -> works
			checked( $district[ $qid ], $option['label'] );
			
			if ( $has_follow_up )
				echo 'class="has-follow-up"';
			if ( $option['followup_id'] )
			echo 'data-relatedQuestion="' . $district['DIST_ID'] . '-'  . $option['followup_id'] .'"'; 

			?>> <?php echo $option['label']; ?></label>
		<?php	
	}
}

function aha_render_school_text_input( $qid, $district ){
	$qname = 'school[' . $district['DIST_ID'] . '][' . $qid . ']'; 
	?>
	<input type="text" name="<?php echo $qname; ?>" id="<?php echo $qname; ?>" value="<?php echo $district[ $qid ]; ?>" />
	<?php
}

function cc_aha_render_school_number_input( $qid, $district, $step = 1 ){
	$qname = 'school[' . $district['DIST_ID'] . '][' . $qid . ']'; 
	?>
	<input type="text" name="<?php echo $qname; ?>" id="<?php echo $qname; ?>" value="<?php echo $district[ $qid ]; ?>" />
	<p class="input-helper">Please do not include dollar (&#36;) or percent (&#37;) signs in your answer.</p>
	<?php
}

function aha_render_school_textarea_input( $qid, $district ){
	$qname = 'school[' . $district['DIST_ID'] . '][' . $qid . ']'; 
	?>
	<textarea name="<?php echo $qname; ?>" id="<?php echo $qname; ?>"><?php echo $district[ $qid ]; ?></textarea>
	<?php
}

// UTILITY FUNCTIONS
/**
 * Outputs the title of each form page.
 *
 * @since   1.0.0
 * @return 	echoes string
 */
function cc_aha_print_form_page_header( $page = 1 ){
	$all_pages = cc_aha_form_page_list();
	echo $all_pages[ $page ];
}

/**
 * Return the question's text
 *
 * @since   1.0.0
 * @return 	string
 */
function cc_aha_print_question_text( $qid ) {
	echo cc_aha_get_question_text( $qid );
}
function cc_aha_get_question_text( $qid ) {
	$question = cc_aha_get_question( $qid );

	return $question[ 'label' ];
}

function cc_aha_print_school_question_text( $qid, $district ){
	$label = cc_aha_get_question_text( $qid );

	echo str_replace('%%district_name%%', $district[ 'DIST_NAME' ], $label);
}

function cc_aha_question_has_follow_up( $options ){
	$has_follow_up = false;
	foreach ( $options as $option ) {
		if ( $option['followup_id'] ) {
			$has_follow_up = true;
			break; // Don't need to keep looking - only need to find one that's true.
		}
	}
	return $has_follow_up;
}

function cc_aha_render_all_questions_and_answers( $metro_id = null ) {
	if ( ! cc_aha_user_can_do_assessment() ) {
		echo '<p class="info">Sorry, you do not have permission to view this page.</p>';
		return;
	}

	// Get all of the questions.
	$questions = cc_aha_get_all_form_questions();

	// Get all of the answers for this metro_id.
	if ( ! $metro_id )
		$metro_id = $_COOKIE['aha_active_metro_id'];

	$data = cc_aha_get_form_data( $metro_id );

	date_default_timezone_set("America/Chicago");
	echo '<h3>Responses for ' . $data[ 'Board_Name' ] . ' | ' . date( "F j, Y, g:i a T" ) . '</h3>';

	// Get the school data for this metro_id.
	$school_data = cc_aha_get_school_data( $metro_id );

	foreach ($questions as $question) {

		if ( $question[ 'loop_schools' ] ){
			echo '<p>' . str_replace('%%district_name%%', '(list follows)', $question[ 'label' ]) . '<br />';

			$options = array();
			if ( in_array( $question[ 'type' ], array( 'radio', 'checkboxes' ) ) )
				$options = cc_aha_get_options_for_question( $question[ 'QID' ] );

			foreach ($school_data as $school) {
				$response = $school[ $question[ 'QID' ] ];
				if ( is_null( $response ) || strlen( trim( $response ) ) == 0 ) {
					echo $school[ 'DIST_NAME' ] . ': ' . 'Not answered.' . '<br />';
				} else {
					// Display the option's human-readable label, if there 
					if ( ! empty( $options ) ) {
						if ( $question[ 'type' ] == 'radio' ) {

							foreach ($options as $option) {
								if ( $option[ 'value' ] == $response ){
									echo $school[ 'DIST_NAME' ] . ': ' . $option[ 'label' ] . '<br />';
									break; // Once we have a match, we can stop.
								}
							}
						} else {
							// must be checkboxes
							$response_array = array_keys( maybe_unserialize( $response ) );
							$selected_options = array();

							foreach ($options as $option) {
								if ( in_array( $option[ 'value' ], $response_array ) ){
									$selected_options[] = $option[ 'label' ];
								}
							}
							echo implode('<br /> ', $selected_options);
						}
					} else {
						echo $school[ 'DIST_NAME' ] . ': ' . $response . '<br />';
					}
				}
			}
			echo '</p>';

		} else {
			echo '<p>' . $question[ 'label' ] . '<br />';

			$response = $data[ $question[ 'QID' ] ];
			if ( is_null( $response )  || strlen( trim( $response ) ) == 0 ) {
				echo "Not answered.";
			} else {
				// Display the option's human-readable label
				$options = array();
				if ( in_array( $question[ 'type' ], array( 'radio', 'checkboxes' ) ) )
					$options = cc_aha_get_options_for_question( $question[ 'QID' ] );

				if ( ! empty( $options ) ) {
					if ( $question[ 'type' ] == 'radio' ) {
						foreach ($options as $option) {
							if ( $option[ 'value' ] == $response ){
								echo $option[ 'label' ] . '</p>';
								break; // Once we have a match, we can stop.
							}
						}
					} else {
						// must be checkboxes
						$response_array = array_keys( maybe_unserialize( $response ) );
						$selected_options = array();

						foreach ($options as $option) {
							if ( in_array( $option[ 'value' ], $response_array ) ){
								$selected_options[] = $option[ 'label' ];
							}
						}
						echo implode('<br /> ', $selected_options);
					}
				} else {
					echo $response . '</p>';
				}
			}
		}

	}

}