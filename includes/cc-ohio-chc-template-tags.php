<?php
/**
 * CC Creating Healthy Communities Ohio Extras
 *
 * Description: File that holds the rendering functions - future gravity forms calls
 *
 * @package   CC Creating Healthy Communities Ohio Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2015 CommmunityCommons.org
 */


/**
 * Print content for the "Introduction" (default tab)
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_ohio_chc_print_introductory_text(){
    ?>
    <p>
        Welcome to the Creating Healthy Communities – Ohio’s [  ] page.
    </p>
    <?php
}
/**
 * Creating container for form to set counties for users
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_ohio_chc_print_county_select_container_markup() {
	//TODO: create form w/select that calls cc_ohio_chc_get_member_array and cc_ohio_chc_get_county_array and do some usermeta saving (there's a funciton for that, too!)
    //TODO: if user is NOT a moderator, they can't see this (they shouldnt have the relevant tab, anywayz)

	?>


    <form id="ohio_chc_county_select" class="" method="post" action="<?php echo cc_ohio_chc_get_home_permalink() . 'save-counties/'; ?>">

    </form>
    <?php
}

function cc_ohio_chc_print_toc(){
	//TODO, print the table of contents with links to appropriate forms, based on usermeta 'ohio_chc_county'
	echo 'table of contents<br />';

	global $wpdb;
	$query = $wpdb->get_var( $wpdb->prepare(
		"
		SELECT value
		FROM wp_rg_lead_detail
		WHERE form_id = 24 AND field_number = 1
		"
	));
	$array1 = unserialize($query);

	$current_user = wp_get_current_user();
    $current_user_email = $current_user->user_email;
	$region;
	foreach ($array1 as $array2) {
		if ( $array2['User Email']== $current_user_email ) {
			$region = $array2['Region'];
		}
	}

	if(!empty($region)) {
		echo "Region = " . $region;




		$edit_post_id = 761;

		if ( !empty( $edit_post_id ) ) {

			gform_update_post::setup_form( $edit_post_id );
			gravity_form( 8 );
		} else {
			gravity_form( 8 );
		}

	} else {
		echo "No Region";
	}

	//LOGIC: First, $region must not be empty for user to proceed to form (i.e. A user's email must be in the list submitted by the admin using the User-County Assessment form).
	//Second, $region must then be saved into hidden field in form.
	//With data-persistence on, each individual user will be allowed to update their own individual form. If more than one user is associated with a particular region then each user will
	//be filling out separate forms for that region. A judgement will then have to be made by Ohio or us as to which one is to be used.


}

/*
 * Renders the county-assignment page.  Should be for admins only!  TODO: make sure
 *
 */
function cc_ohio_chc_print_county_assignment_page() {

	//echo 'county assignment';
	$gform_id = cc_ohio_chc_get_user_county_form_num();

	//gravity_form( $gform_id );
	echo do_shortcode("[gravityform id='" . $gform_id . "' title='false' description='false' ajax='true']");
}

/**
 * Renders the first form.  TODO: make this a switch-case (or something) instead of individual functions for each form!
 *
 */
function cc_ohio_chc_render_form( $url_form_num = 1) {

	//get the appropriate form for this county/user
	//TODO: check if admin, get list selection if so
	$gf_form_num = cc_ohio_chc_get_form_num( $url_form_num );

	//$entry_obj = cc_ohio_chc_get_county_entry_by_form_number( 1 );

	//display which gravity form, maybe prepopulated..
	//gravity_form( $gf_form_num );

	echo do_shortcode("[gravityform id='" . $gf_form_num . "' title='false' description='false' ajax='true']");
}


/**
 * Builds the subnav of the Ohio CHC group tab
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_ohio_chc_render_tab_subnav(){
	?>
	<div id="subnav" class="item-list-tabs no-ajax">
		<ul class="nav-tabs">
			<li <?php if ( cc_ohio_chc_on_main_screen() ) { echo 'class="current"'; } ?>>
				<a href="<?php echo cc_ohio_chc_get_home_permalink(); ?>">Introduction</a>
			</li>
			<li <?php if ( cc_ohio_chc_on_form_screen() ) { echo 'class="current"'; } ?>>
				<a href="<?php echo cc_ohio_chc_get_main_form_permalink(); ?>">Forms</a>
			</li>

			<?php
				//TODO: set up assignment permalink thing

				//Check if user is mod or admin (in group) to let them see User-County Assignment form.


				if (cc_ohio_chc_find_admin_mod()) {
			?>
				<li <?php if ( cc_ohio_chc_on_report_screen() ) { echo 'class="current"'; } ?>>
					<a href="<?php echo cc_ohio_chc_get_report_permalink(); ?>">Reports</a>
				</li>
				<li <?php if ( cc_ohio_chc_on_county_assignment_screen() ) { echo 'class="current"'; } ?>>
					<a href="<?php echo cc_ohio_chc_get_county_assignment_permalink(  ); ?>">User-County Assignment</a>
				</li>
			<?php
				}
			// endif; ?>

		</ul>
	</div>
	<?php
}

//Find out if the user is EITHER Admin OR Mod of Ohio Group
function cc_ohio_chc_find_admin_mod() {
	$user_ID = get_current_user_id();
	$group_id = bp_get_group_id();
	//$ismod = groups_is_user_mod( $user_ID, $group_id );
	//$isadmin = groups_is_user_admin( $user_ID, $group_id );
	$ismod = bp_group_is_mod();
	$isadmin = bp_group_is_admin();

	//var_dump($ismod);
	//var_dump($isadmin);
	if ($ismod == true || $isadmin == true) {
		return true;
	} else {
		return false;
	}
}
/**
 * Render the subnav for the forms tab , likely temporary because ugly?
 *
 */
function cc_ohio_chc_render_form_subnav(){
?>
	<div id="subnav" class="item-list-tabs no-ajax">
		<ul class="nav-tabs">
			<li <?php if ( cc_ohio_chc_on_form1_screen() ) { echo 'class="current"'; } ?>>
				<a href="<?php echo cc_ohio_chc_get_form_permalink( 1 ); ?>">General</a>
			</li>
			<li <?php if ( cc_ohio_chc_on_form2_screen() ) { echo 'class="current"'; } ?>>
				<a href="<?php echo cc_ohio_chc_get_form_permalink( 2 ); ?>">Active Living</a>
			</li>
			<li <?php if ( cc_ohio_chc_on_form3_screen() ) { echo 'class="current"'; } ?>>
				<a href="<?php echo cc_ohio_chc_get_form_permalink( 3 ); ?>">Healthy Eating</a>
			</li>
			<li <?php if ( cc_ohio_chc_on_form4_screen() ) { echo 'class="current"'; } ?>>
				<a href="<?php echo cc_ohio_chc_get_form_permalink( 4 ); ?>">Tobacco</a>
			</li>
			<li <?php if ( cc_ohio_chc_on_form5_screen() ) { echo 'class="current"'; } ?>>
				<a href="<?php echo cc_ohio_chc_get_form_permalink( 5 ); ?>">Supplemental</a>
			</li>
			<li <?php if ( cc_ohio_chc_on_form6_screen() ) { echo 'class="current"'; } ?>>
				<a href="<?php echo cc_ohio_chc_get_form_permalink( 6 ); ?>">Work Plan</a>
			</li>
			<li <?php if ( cc_ohio_chc_on_form7_screen() ) { echo 'class="current"'; } ?>>
				<a href="<?php echo cc_ohio_chc_get_form_permalink( 7 ); ?>">Success Story</a>
			</li>
		</ul>
	</div>
	<?php




}

function cc_ohio_chc_render_report_subnav(){
?>
	<div id="subnav" class="item-list-tabs no-ajax">
		<ul class="nav-tabs">
			<li <?php if ( cc_ohio_chc_on_reportform1_screen() ) { echo 'class="current"'; } ?>>
				<a href="<?php echo cc_ohio_chc_get_report_permalink( 1 ); ?>">County Reports</a>
			</li>
			<li <?php if ( cc_ohio_chc_on_reportform2_screen() ) { echo 'class="current"'; } ?>>
				<a href="<?php echo cc_ohio_chc_get_report_permalink( 2 ); ?>">Program Data Summary</a>
			</li>

		</ul>
	</div>
	<?php




}
