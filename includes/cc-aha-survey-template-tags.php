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
 * Output logic for the form. includes the wrapper pieces.
 * Question building is handled separately
 *
 * @since   1.0.0
 * @return 	outputs html
 */
function cc_aha_render_form( $page = null ){
	$page = (int) $page;
	$first_page = 1;
	$last_page = cc_aha_get_max_page_number(); // Set in functions
	$page = ( !empty( $page ) && ( $page >= $first_page && $page <= $last_page ) ) ? $page : 1;

	if ( ! isset( $_COOKIE['aha_active_metro_id'] ) )
		return;

	?>
	<form id="aha_survey-page-<?php echo $page; ?>" class="standard-form aha-survey" method="post" action="<?php echo cc_aha_get_home_permalink() . 'update-assessment/' . $page; ?>">
		<?php

			// Some pages can be auto-built. Others we're going to hand-code.
			$can_auto_build = array( 2, 4, 6 );
			if ( in_array( $page, $can_auto_build ) ) {
				cc_aha_auto_build_questions( $page );
			} else {
				$aha_form_function_name = 'cc_aha_handcoded_questions_' . $page;
				$aha_form_function_name();
			}
		?>
		<input type="hidden" name="metro_id" value="<?php echo $_COOKIE['aha_active_metro_id']; ?>">
		<?php wp_nonce_field( 'cc-aha-assessment', 'set-aha-assessment-nonce' ) ?>
		<div class="form-navigation clear">
		<?php if ( $page == 1 ) : ?>
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
			cc_aha_render_question( $question, $data );
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
			?>
			<li><a href="<?php echo cc_aha_get_survey_permalink( $page_number ); ?>"><?php echo $label; ?></a></li>
			<?php 
			}
		?>
	</ul>

	<?php
}

function cc_aha_handcoded_questions_3(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 2 );
	$school_districts = cc_aha_get_school_data( $_COOKIE['aha_active_metro_id'] );
	?>

	<h2>Physical Education in Schools</h2>
		<?php 
	if ( $data['2.1.1.1'] && $data['2.1.1.2'] && $data['2.1.1.3'] ) {
		echo "Your state has enacted state-wide physical education requirements at all levels.";
	} else {
			
		foreach ($school_districts as $district) {
			// School district stuff will require a different save routine, since they're keyed by district ID.
			// TODO: This is kind of weird, once marked "yes", these questions disappear... Do we need to have a "provided by AHA" entry and a "user response" entry? Or should the answers be pre-populated and the user can change them?
			?>
			<fieldset class="spacious">

				<legend><h4>In school district <?php echo $district['DIST_NAME']; ?>, do schools meet our PE requirements?</h4></legend>

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

function cc_aha_handcoded_questions_4(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 4 );
	?>

	<h2>Shared Use Policies</h2>
	<fieldset>
		<legend>Does the state provide promotion, incentives, technical assistance or other resources to schools to encourage shared use?</legend>
		<?php aha_render_boolean_radios( '2.2.2.1', $data, '2.2.2.2', 1 ); ?>
		<div class="follow-up-question" data-relatedTarget="2.2.2.2">
			<label for="2.2.2.2">Please describe:</label>
			<?php aha_render_textarea_input( '2.2.2.2', $data ); ?>
		</div>
	</fieldset>

	<fieldset>
		<legend>Given the current political/policy environment, where can this local board most likely help drive impact relative to shared use policies?</legend>
		<?php 
		$options = array( 
			array(
				'value' => 'state',
				'label' => 'At the state level',
				),
			array(
				'value' => 'local',
				'label' => 'At the local level',
			),
			array(
				'value' => 'state and local',
				'label' => 'At the state and local level',
			),
			array(
				'value' => 'neither',
				'label' => 'Not a viable issue at this time'
			)
		);
		aha_render_radio_group( '2.2.4.1', $options ); 
		?>
	</fieldset>

		<?php 
}

function cc_aha_handcoded_questions_5(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 5 );
	$school_districts = cc_aha_get_school_data( $_COOKIE['aha_active_metro_id'] );
	?>

	<h2>Shared Use Policies, continued</h2>
	<?php
	foreach ($school_districts as $district) {
		// School district stuff will require a different save routine, since they're keyed by district ID.
		// TODO: This is kind of weird, once marked "yes", these questions disappear... Do we need to have a "provided by AHA" entry and a "user response" entry? Or should the answers be pre-populated and the user can change them?
		?>
		<fieldset class="spacious">
			<legend><h4>In school district <?php echo $district['DIST_NAME']; ?>, is a district-wide policy and/or guidance in place for shared use of school facilities?</h4></legend>
			<?php 
			// Once databased, $options = cc_aha_get_q_options( '2.2.5.1' );
			$options = array( 
				array(
					'value' => 'no',
					'label' => 'No',
					'follow_up' => '2.2.5.1.1'
					),
				array(
					'value' => 'limited',
					'label' => 'Yes – limited use for certain partner organizations (Boy Scouts, Girl Scouts, etc.)',
					'follow_up' => '2.2.5.1.3'
				),
				array(
					'value' => 'broad',
					'label' => 'Yes – broader community use (community recreational use of school gymnasiums, track & field, etc.)',
					'follow_up' => '2.2.5.1.3'
				),
				array(
					'value' => 'other',
					'label' => 'Yes – other',
					'follow_up' => '2.2.5.1.3'
				)
			);
			aha_render_school_radio_group( '2.2.5.1', $district, $options );
			?>

			<div class="follow-up-question" data-relatedTarget="<?php echo $district['DIST_ID']; ?>-2.2.5.1.1">
				<fieldset>
					<legend>If no, what rationale was provided for not having a district-wide shared use policy? </legend>
					<?php
					// Once databased, $options = cc_aha_get_q_options( '2.2.5.1.1' );
					$options = array( 
						array(
							'value' => 'liability',
							'label' => 'Concerns about liability',
							),
						array(
							'value' => 'property damage',
							'label' => 'Concerns about property damage',
						),
						array(
							'value' => 'crime',
							'label' => 'Concerns about crime',
						),
						array(
							'value' => 'costs',
							'label' => 'Concerns about costs',
						),
						array(
							'value' => 'other',
							'label' => 'Other',
							'follow_up' => '2.2.5.1.1.1'
						)
					);
					aha_render_school_radio_group( '2.2.5.1.1', $district, $options );
					?>
					<div class="follow-up-question" data-relatedTarget="<?php echo $district['DIST_ID']; ?>-2.2.5.1.1.1">
						<label>If other, please describe: <?php aha_render_school_textarea_input( '2.2.5.1.1.1', $district ) ?></label>
					</div>
				</fieldset>
			</div>

			<div class="follow-up-question" data-relatedTarget="<?php echo $district['DIST_ID']; ?>-2.2.5.1.3">
				<label>If available, please provide a URL for the district shared use policy: <?php aha_render_school_text_input( '2.2.5.1.3', $district ) ?></label>
			</div>
		</fieldset>
		<?php
	}
}

function cc_aha_handcoded_questions_7(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 7 );
	$school_districts = cc_aha_get_school_data( $_COOKIE['aha_active_metro_id'] );
	?>

	<h2>School Nutrition Policies</h2>
	<?php
	foreach ($school_districts as $district) {
			// School district stuff will require a different save routine, since they're keyed by district ID.
			?>
			<fieldset class="spacious">
				<legend><h4>In school district <?php echo $district['DIST_NAME']; ?>, is there a documented and publicly available district wellness policy in place?</h4></legend>
				<?php aha_render_school_boolean_radios( '3.1.3.1.0', $district, $district['DIST_ID'] . '-3.1.3.1.x', 1 ); ?>

				<div class="follow-up-question" data-relatedTarget="<?php echo $district['DIST_ID'] . '-3.1.3.1.x'; ?>">
					<label>Does the policy meet the criteria related to school meals? <?php aha_render_school_boolean_radios( '3.1.3.1.1', $district ); ?></label>
					<label>Does the policy meet the criteria related to smart snacks? <?php aha_render_school_boolean_radios( '3.1.3.1.2', $district ); ?></label>
					<label>Does the policy meet the criteria related to before/after school offering? <?php aha_render_school_boolean_radios( '3.1.3.1.3', $district ); ?></label>
					<label>Please provide the URL to the district's wellness policy: <?php aha_render_school_text_input( '3.1.3.1.4', $district ); ?></label>
				</div>
			</fieldset>
			<?php
		}
	//aha_render_checkbox_input( '3.1.4', $data);
}

function cc_aha_render_question( $question, $data ){
	// TODO: decide where to make the split on school vs board questions. Above here? Here?
	if ( $question[ 'follows_up' ] ) {
		?>
		<div class="follow-up-question" data-relatedTarget="<?php echo $question[ 'QID' ] ; ?>">
		<?php
	} 
	switch ( $question[ 'type' ] ) {
		case 'text':
		?>
			<label for="<?php echo $question[ 'QID' ]; ?>"><?php echo $question[ 'label' ]; ?></label>
			<?php aha_render_text_input( $question[ 'QID' ], $data );
			break;
		case 'radio':
		?>	<fieldset>
				<label for="<?php echo $question[ 'QID' ]; ?>"><?php echo $question[ 'label' ]; ?></label>
				<?php aha_render_radio_group( $question[ 'QID' ], $data ); ?>
			</fieldset>
			<?php
			break;
		case 'textarea':
		?>	<fieldset>
				<label for="<?php echo $question[ 'QID' ]; ?>"><?php echo $question[ 'label' ]; ?></label>
				<?php aha_render_textarea_input( $question[ 'QID' ], $data ); ?>
			</fieldset>
			<?php
			break;
		
		default:
			# code...
			break;
	}
	if ( $question[ 'follows_up' ] ) {
		?>
		</div>
		<?php
	} 
}


function aha_render_boolean_radios( $qid, $data, $follow_up_id = null, $follow_up_on_value = 1 ) {
	?>
	
	<label><input type="radio" name="<?php echo 'board[' . $qid . ']'; ?>" value="1" <?php if ( $data[ $qid ] ) echo 'checked="checked"'; ?> <?php if ( $follow_up_id ) echo 'class="has-follow-up"'; ?> <?php if ( $follow_up_id && $follow_up_on_value == 1 ) echo 'data-relatedQuestion="' . $follow_up_id .'"'; ?>> Yes</label>
	<label><input type="radio" name="<?php echo 'board[' . $qid . ']'; ?>" value="0" <?php if ( ! $data[ $qid ] ) echo 'checked="checked"'; ?> <?php if ( $follow_up_id ) echo 'class="has-follow-up"'; ?> <?php if ( $follow_up_id && $follow_up_on_value == 0 ) echo 'data-relatedQuestion="' . $follow_up_id .'"'; ?>> No</label>
	<?php 
}

function aha_render_radio_group( $qid, $data ){
	$options = cc_aha_get_options_for_question( $qid );

	foreach ($options as $option) {
		?>
		<label><input type="radio" name="<?php echo 'board[' . $qid . ']'; ?>" value="<?php echo $option['value']; ?>" <?php checked( $data[ $qid ], $option['value'] ); ?> <?php if ( $option['followup_id'] ) echo 'class="has-follow-up" data-relatedQuestion="' . $option['followup_id'] .'"'; ?>> <?php echo $option['label']; ?></label>
		<?php
		
	}
}

function aha_render_text_input( $qid, $data ){
	?>
	<input type="text" name="<?php echo 'board[' . $qid . ']'; ?>" id="<?php echo $qid; ?>" value="<?php echo $data[ $qid ]; ?>" />
	<?php
}

function aha_render_textarea_input( $qid, $data ){

    // TODO: Firefox 31 Mac isn't displaying the value. Sf and Cr are fine. Works off-site: http://jsfiddle.net/58rPb/
	?>
	<textarea name="<?php echo 'board[' . $qid . ']'; ?>" id="<?php echo $qid; ?>"><?php echo $data[ $qid ]; ?></textarea>
	<?php
}

function aha_render_checkbox_input( $qid, $data, $options = array() ){

    // TODO: everything.
	foreach ($options as $option) {
	?>
		<input type="checkbox" name="<?php echo 'board[' . $qid . ']'; ?>" id="<?php echo $qid; ?>" />
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
	foreach ($options as $option) {
		?>
		<label><input type="radio" name="<?php echo $qname; ?>" value="<?php echo $option['value']; ?>" <?php checked( $district[ $qid ], $option['value'] ); ?> <?php if ( $option['follow_up'] ) echo 'class="has-follow-up" data-relatedQuestion="' . $district['DIST_ID'] . '-'  . $option['follow_up'] .'"'; ?>> <?php echo $option['label']; ?></label>
		<?php
		
	}
}

function aha_render_school_text_input( $qid, $district ){
	$qname = 'school[' . $district['DIST_ID'] . '][' . $qid . ']'; 
	?>
	<input type="text" name="<?php echo $qname; ?>" id="<?php echo $qname; ?>" value="<?php echo $district[ $qid ]; ?>" />
	<?php
}

function aha_render_school_textarea_input( $qid, $district ){
	$qname = 'school[' . $district['DIST_ID'] . '][' . $qid . ']'; 

    // TODO: Firefox 31 Mac isn't displaying the value. Sf and Cr are fine. Works off-site: http://jsfiddle.net/58rPb/
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
 * All the page titles kept in one place, for easier updating
 *
 * @since   1.0.0
 * @return 	array
 */
function cc_aha_form_page_list(){
	return array(
	1 => 'Table of Contents',
	2 => 'Tobacco',
	3 => 'Physical Education in Schools',
	4 => 'Shared Use Policies',
	6 => 'Complete Streets',
	7 => 'School Nutrition Policies',
	8 => 'School Nutrition Implementation'
	);
}