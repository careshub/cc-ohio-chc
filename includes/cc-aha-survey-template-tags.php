<?php 
/**
 * CC American Heart Association Extras
 *
 * @package   CC American Heart Association Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2014 CommmunityCommons.org
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
			// Kind of weird, but splitting the various pages out into separate functions will make git happier, I think.
			$aha_form_function_name = 'cc_aha_get_form_piece_' . $page;
			$aha_form_function_name();
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

function cc_aha_get_form_piece_1(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 1 );
	?>
	<p>Introductory paragraph. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut adipiscing sem a nisl egestas, nec tempus massa pretium. Nam sagittis hendrerit lectus eget imperdiet. Nunc eget est magna. Nullam adipiscing, urna eu tempus dictum, mi mauris malesuada ligula, non pulvinar tellus dolor id velit. Fusce et augue nec libero elementum porttitor in quis ligula. Cras lacinia turpis a dictum malesuada. Duis gravida dapibus commodo.</p>

	<h2>Table of Contents</h2>
	<ul>
		<li><a href="<?php echo cc_aha_get_survey_permalink(2); ?>">Tobacco</a></li>
		<li><a href="<?php echo cc_aha_get_survey_permalink(3); ?>">Physical Education in Schools</a></li>
		<li><a href="<?php echo cc_aha_get_survey_permalink(4); ?>">Shared Use Policies</a></li>
		<li><a href="<?php echo cc_aha_get_survey_permalink(5); ?>">Complete Streets</a></li>
		<li><a href="<?php echo cc_aha_get_survey_permalink(6); ?>">School Nutrition Policies</a></li>
		<li><a href="<?php echo cc_aha_get_survey_permalink(7); ?>">School Nutrition Implementation</a></li>
	</ul>

	<?php
}

function cc_aha_get_form_piece_2(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 1 );
	?>

	<h2>Tobacco</h2>
	<label for="1.2.2.1">If your community has a local tobacco excise tax, what is the tax rate? If none, enter 0.</label>
	<?php aha_render_text_input( '1.2.2.1', $data ); ?>
	<?php
}

function cc_aha_get_form_piece_3(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 2 );
	$school_districts = cc_aha_get_school_data( $_COOKIE['aha_active_metro_id'] );
	?>

	<h2>Physical Education in Schools</h2>
		<?php 
	if ( $data['2.1.1.1'] && $data['2.1.1.2'] && $data['2.1.1.3'] ) {
		echo "Your state is awesome.";
	} else {
			
		foreach ($school_districts as $district) {
			// School district stuff will require a different save routine, since they're keyed by district ID.
			// TODO: The "checked" state isn't properly reflected in all five sets for some reason?
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

function cc_aha_get_form_piece_4(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 3 );
	?>

	<h2>Shared Use Policies</h2>
	<fieldset>
		<legend>Does the state provide promotion, incentives, technical assistance or other resources to schools to encourage shared use?</legend>
		<?php aha_render_boolean_radios( '2.2.2.1', $data, '2.2.2.2', 1 ); ?>
		<div class="follow-up-question" data-relatedTarget="2.2.2.2">
			<label for="2.2.2.2">Please describe:</label>
			<textarea name="board[2.2.2.2]" id="2.2.2.2"><?php echo $data['2.2.2.2']; ?></textarea>
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




function aha_render_boolean_radios( $qid, $data, $follow_up_id = null, $follow_up_on_value = 1 ) {
	?>
	
	<label><input type="radio" name="<?php echo 'board[' . $qid . ']'; ?>" value="1" <?php if ( $data[ $qid ] ) echo 'checked="checked"'; ?> <?php if ( $follow_up_id ) echo 'class="has-follow-up"'; ?> <?php if ( $follow_up_id && $follow_up_on_value == 1 ) echo 'data-relatedQuestion="' . $follow_up_id .'"'; ?>> Yes</label>
	<label><input type="radio" name="<?php echo 'board[' . $qid . ']'; ?>" value="0" <?php if ( ! $data[ $qid ] ) echo 'checked="checked"'; ?> <?php if ( $follow_up_id ) echo 'class="has-follow-up"'; ?> <?php if ( $follow_up_id && $follow_up_on_value == 0 ) echo 'data-relatedQuestion="' . $follow_up_id .'"'; ?>> No</label>
	<?php 
}

function aha_render_radio_group( $qid, $options = array() ){
	// Follow-up value need to be an array in this case, as multiple "yes" options could fire it 
	foreach ($options as $option) {
		?>
		<label><input type="radio" name="<?php echo 'board[' . $qid . ']'; ?>" value="<?php echo $option['value']; ?>" <?php checked( $data[ $qid ], $option['value'] ); ?> <?php if ( $option['follow_up'] ) echo 'class="has-follow-up" data-relatedQuestion="' . $option['follow_up'] .'"'; ?>> <?php echo $option['label']; ?></label>
		<?php
		
	}
}

function aha_render_text_input( $qid, $data ){
	?>
	<input type="text" name="<?php echo 'board[' . $qid . ']'; ?>" id="<?php echo $qid; ?>" value="<?php echo $data[ $qid ]; ?>" />
	<?php
}

//School district-specific form fields
function aha_render_school_boolean_radios( $qid, $district, $follow_up_id = null, $follow_up_on_value = 1  ) {
	$qname = $district['DIST_ID'] . '[' . $qid . ']'; 
	?>
	<label><input type="radio" name="<?php echo $qname; ?>" value="1" <?php if ( $district[ $qid ] ) echo 'checked="checked"'; ?> <?php if ( $follow_up_id ) echo 'class="has-follow-up"'; ?> <?php if ( $follow_up_id && $follow_up_on_value == 1 ) echo 'data-relatedQuestion="' . $follow_up_id .'"'; ?>> Yes</label>
	<label><input type="radio" name="<?php echo $qname;  ?>" value="0" <?php if ( ! $district[ $qid ] ) echo 'checked="checked"'; ?> <?php if ( $follow_up_id ) echo 'class="has-follow-up"'; ?> <?php if ( $follow_up_id && $follow_up_on_value == 0 ) echo 'data-relatedQuestion="' . $follow_up_id .'"'; ?>> No</label>
	<?php 
}