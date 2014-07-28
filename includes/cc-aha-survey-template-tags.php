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
		<div class="submit">
	        <input id="submit-survey-<?php echo $page; ?>" type="submit" value="Save responses" name="submit-survey-<?php echo $page; ?>">
	    </div>
	</form>
	<?php
}

function cc_aha_get_form_piece_1(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 1 );
	?>
	<!-- <fieldset>
		<legend>Do you agree with this thing?</legend>
		<?php aha_render_boolean_radios( '1.2.1.1', $data ); ?>
	</fieldset>

	<label><input type="checkbox" name="1.2.1.2" id="1.2.1.2" value="1" />Label label</label><br />

	<label for="1.2.1.3" class="">text label</label>
	<input type="text" name="1.2.1.3" id="1.2.1.3" value="this is the value" /> -->

	<h2>Tobacco</h2>
	<label for="1.2.2.1">If your community has a local tobacco excise tax, what is the tax rate? If none, enter 0.</label>
	<?php aha_render_text_input( '1.2.2.1', $data ); ?>
	<?php
}

function cc_aha_get_form_piece_2(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 2 );
	$school_districts = cc_aha_get_school_data( $_COOKIE['aha_active_metro_id'] );
	?>
	<h2>Physical Education in Schools</h2>
		<?php 
	foreach ($school_districts as $district) {
		// School district stuff will require a different save routine, since they're keyed by district ID.
		// TODO: The "checked" state isn't properly reflected in all five sets for some reason?
		// TODO: This is kind of weird, once marked "yes", these questions disappear... Do we need to have a "provided by AHA" entry and a "user response" entry? Or should the answers be pre-populated and the user can change them?
		?>
		<fieldset class="spacious">
			<legend><h4>In school district <?php echo $district['name']; ?>, do schools meet our PE requirements?</h4></legend>
			<?php if ( $district['2.1.4.1.1'] ) : ?>
				<label>Elementary (150 mins) <?php aha_render_school_boolean_radios( '2.1.4.1.1', $district ); ?></label>
			<?php 
			endif;
			if ( $district['2.1.4.1.2'] ) :
			?>
				<label>Middle (225 mins) <?php aha_render_school_boolean_radios( '2.1.4.1.2', $district ); ?></label>
			<?php 
			endif;
			if ( $district['2.1.4.1.3'] ) :
			?>
				<label>High School (Graduation Requirement) <?php aha_render_school_boolean_radios( '2.1.4.1.3', $district ); ?></label>
			<?php endif; ?>
		</fieldset>
		<?php
	}
}

function cc_aha_get_form_piece_3(){
	$data = cc_aha_get_form_data( $_COOKIE['aha_active_metro_id'], 3 );
	?>
	<h2>Shared Use Policy</h2>
	<fieldset>
		<legend>Does the state provide promotion, incentives, technical assistance or other resources to schools to encourage shared use?</legend>
		<?php //aha_render_boolean_radios( '2.2.2.1', $data ); ?>
		<label><input type="radio" value="1" name="2.2.2.1" class="has-follow-up" data-relatedQuestion="2.2.2.2"> Yes</label>
		<label><input type="radio" checked="checked" value="0" name="2.2.2.1" class="has-follow-up"> No</label>
		<div class="follow-up-question" data-relatedTarget="2.2.2.2">
			<label for="2.2.2.2">Please describe:</label>
			<textarea name="2.2.2.2" id="2.2.2.2"><?php echo $data['2.2.2.2']; ?></textarea>
		</div>
	</fieldset>
		<?php 
}




function aha_render_boolean_radios( $qid, $data ) {
	?>
	<label><input type="radio" name="<?php echo $qid; ?>" value="1" <?php if ( $data[ $qid ] ) echo 'checked="checked"'; ?>> Yes</label>
	<label><input type="radio" name="<?php echo $qid; ?>" value="0" <?php if ( ! $data[ $qid ] ) echo 'checked="checked"'; ?>> No</label>
	<?php 
}

function aha_render_text_input( $qid, $data ){
	?>
	<input type="text" name="<?php echo $qid; ?>" id="<?php echo $qid; ?>" value="<?php echo $data[ $qid ]; ?>" />
	<?php
}

//School district-specific form fields
function aha_render_school_boolean_radios( $qid, $district ) {
	$qname = $district['id'] . '[' . $qid . ']'; 
	?>
	<label><input type="radio" name="<?php echo $qname; ?>" value="1" <?php if ( $district[ $qid ] ) echo 'checked="checked"'; ?>> Yes</label>
	<label><input type="radio" name="<?php echo $qname;  ?>" value="0" <?php if ( ! $district[ $qid ] ) echo 'checked="checked"'; ?>> No</label>
	<?php 
}