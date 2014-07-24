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
	$page = ( !empty( $page ) ) ? $page : 1;

	if ( ! isset( $_COOKIE['aha_active_metro_id'] ) )
		return;

	?>
	<form id="aha_survey-page-<?php echo $page; ?>" class="" method="post" action="<?php echo cc_aha_get_home_permalink() . 'update-assessment/' . $page; ?>">
		<?php
		switch ( $page ) {
			case 1:
				// Page one of the form output
				?>

				<input type="radio" name="1.2.1.1" value="1"> Yes<br />
				<input type="radio" name="1.2.1.1" value="0" checked> No<br />

				<input type="checkbox" name="1.2.1.2" id="1.2.1.2" value="1" /> <label for="1.2.1.2" class="">Label label</label><br />

				<label for="1.2.1.3" class="">text label</label>
				<input type="text" name="1.2.1.3" id="1.2.1.3" value="this is the value" /> 

				<?php
				break;
			
			default:
				# code...
				break;
		}
		?>
		<input type="hidden" name="metro_id" value="<?php echo $_COOKIE['aha_active_metro_id']; ?>">
		<?php wp_nonce_field( 'cc-aha-assessment', 'set-aha-assessment-nonce' ) ?>
		<div class="submit">
	        <input id="submit-survey-<?php echo $page; ?>" type="submit" value="Save responses" name="submit-survey-<?php echo $page; ?>">
	    </div>
	</form>

	<?php
}