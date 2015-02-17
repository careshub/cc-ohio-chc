<?php 
/**
 * CC Creating Healthy Communities Ohio Extras
 *
 * @package   CC Creating Healthy Communities Ohio Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2015 CommmunityCommons.org
 */

/**
 * Are we on the Ohio CHC extras tab?
 *
 * @since   1.0.0
 * @return  boolean
 */
function cc_ohio_chc_is_component() {
    if ( bp_is_groups_component() && bp_is_current_action( cc_ohio_chc_get_slug() ) )
        return true;

    return false;
}

/**
 * Is this the Ohio CHC group?
 *
 * @since    1.0.0
 * @return   boolean
 */
function cc_ohio_chc_is_ohio_chc_group(){
    return ( bp_get_current_group_id() == cc_ohio_chc_get_group_id() );
}

/**
 * Get the group id based on the context
 *
 * @since   1.0.0
 * @return  integer
 */
function cc_ohio_chc_get_group_id(){
    switch ( get_home_url() ) {
        case 'http://localhost/wordpress':
            $group_id = 596;
            break;
		case 'http://localhost/cc_local':
            $group_id = 633; //599
            break;
        case 'http://dev.communitycommons.org':
            $group_id = 5316;
            break;
        default: //live site
            $group_id = 633;
            break;
    }
    return $group_id;
}

/**
 * Get various slugs
 * These are gathered here so when, inevitably, we have to change them, it'll be simple
 *
 * @since   1.0.0
 * @return  string
 */
function cc_ohio_chc_get_slug(){
    return 'ohio-chc-assessment';
}
function cc_ohio_chc_get_form_slug(){
    return 'forms';
}
function cc_ohio_chc_get_county_slug(){
	return 'county-assignment';
}
function cc_ohio_chc_get_form_num_slug( $formnum = 1 ){
	return cc_ohio_chc_get_form_slug() . '/' . $formnum;
}

/**
 * Get URIs for the various pieces of this tab
 * 
 * @return string URL
 */
function cc_ohio_chc_get_home_permalink( $group_id = false ) {
    $group_id = ( $group_id ) ? $group_id : bp_get_current_group_id() ;
    $permalink = bp_get_group_permalink( groups_get_group( array( 'group_id' => $group_id ) ) ) .  cc_ohio_chc_get_slug() . '/';
    return apply_filters( "cc_ohio_chc_home_permalink", $permalink, $group_id);
}
function cc_ohio_chc_get_assessment_permalink( $page = 1, $group_id = false ) { //TODO: what is this?
    $permalink = cc_ohio_chc_get_home_permalink( $group_id ) . cc_ohio_chc_get_form_slug() . '/';
    return apply_filters( "cc_ohio_chc_get_assessment_permalink", $permalink, $group_id);
}
function cc_ohio_chc_get_main_form_permalink( $page = 1, $group_id = false ) {
    $permalink = cc_ohio_chc_get_home_permalink( $group_id ) . cc_ohio_chc_get_form_slug() . '/';
    return apply_filters( "cc_ohio_chc_get_assessment_permalink", $permalink, $group_id);
}
function cc_ohio_chc_get_county_assignment_permalink( $group_id = false ) {
    $permalink = cc_ohio_chc_get_home_permalink( $group_id ) . cc_ohio_chc_get_county_slug() . '/';
    return apply_filters( "cc_ohio_chc_get_county_assignment_permalink", $permalink, $group_id);
}
function cc_ohio_chc_get_form_permalink( $formnum = 1, $group_id = false ) {
    $permalink = cc_ohio_chc_get_home_permalink( $group_id ) . cc_ohio_chc_get_form_slug() . '/' . $formnum ;
    return apply_filters( "cc_ohio_chc_get_form_permalink", $permalink, $group_id);
}


/**
 * Can this user fill out the assessment and such?
 * 
 * @return boolean
 */
function cc_ohio_chc_user_can_do_assessment(){
    // TODO: this is where we'll figure out user assignments for a particular county?  Maybe?

	
	
    return false;
}

function cc_ohio_chc_resolve_county(){
    // TODO: this, if function needed..

}

/**
 * Where are we?
 * Checks for the various screens
 *
 * @since   1.0.0
 * @return  string
 */
function cc_ohio_chc_on_main_screen(){
    // There should be no action variables if on the main tab
    if ( cc_ohio_chc_is_component() && ! ( bp_action_variables() )  ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_assessment_screen(){ //what is this??
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( cc_ohio_chc_get_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_form_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_county_assignment_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( cc_ohio_chc_get_county_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}

function cc_ohio_chc_on_form1_screen(){
	//var_dump( bp_action_variable(0));
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '1', 1 ) && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_form2_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '2', 1 ) && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_form3_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '3', 1 ) && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_form4_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '4', 1 ) && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_form5_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '5', 1 ) && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_form6_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '6', 1 ) && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_form7_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '7', 1 ) && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
/**
 * Retrieve a user's county affiliation
 * 
 * @since   1.0.0
 * @return  string 
 */
function cc_ohio_chc_get_user_county() {
    $selected = get_user_meta( get_current_user_id(), 'ohio_chc_county', true );

    return $selected;
}

/*
 * Returns array of members of Ohio CHC Group
 *
 * @params int Group_ID
 * @return array Array of Member ID => name
 */
function cc_ohio_chc_get_member_array( ){

	global $bp;
	$group_id = cc_ohio_chc_get_group_id();
	
	$group = groups_get_group( array( 'group_id' => $group_id ) );
	//var_dump($group);
	
	//set up group member array for drop downs
	$group_members = array();
	if ( bp_group_has_members( array( 'group_id' => $group_id, 'per_page' => 9999 ) ) ) {
	
		//iterate through group members, creating array for form list (drop down)
		while ( bp_group_members() ) : bp_group_the_member(); 
			$group_members[bp_get_group_member_id()] = bp_get_group_member_name();
		endwhile; 
		
		//var_dump ($group_members);  //works!
	}
	
	return $group_members;
	
}

/*
 * Returns array of counties in Ohio
 *
 * @return array Array of county names
 */
function cc_ohio_chc_get_county_array( ){

	$counties = array(
			"Adams-Brown Counties",
			"Allen County",
			"Athens County",
			"Cincinnati",
			"Columbus City",
			"Cuyahoga County",
			"Lorain County",
			"Lucas County",
			"Marion County",
			"Meigs County",
			"Montgomery County",
			"Richland County",
			"Summit County",
			"Trumbull County",
			"Washington County"
		);
	
	return $counties;
	
}

/* 
 * Checks whether current user has county assigned to them
 *	TODO: admin check
 *
 * @return bool 
 */
function current_user_has_county() {
	//who is the current user?
	$user_id = get_current_user_id();

	//does the current user have a county assigned to them?
	$user_county_meta = get_user_meta( $user_id, 'cc-ohio-user-county', false);
	
	//empty string returned if no meta found
	if( $user_county_meta == "" ){
		return false;
	} else {
		return $user_county_meta;
	}
}

/* 
 * Gets county assignment of user 
 *	TODO: 
 *
 * @param int User ID
 * @return string County 
 */
function get_user_county( $user_id = 0 ) {

	//if incoming user_id not set, use current user id
	if ( $user_id == 0 ){
		$user_id = get_current_user_id();
	}

	//does the current user have a county assigned to them?
	$user_county_meta = get_user_meta( $user_id, 'cc-ohio-user-county', true);
	//var_dump ($user_county_meta);
	
	return $user_county_meta;
}

/*
 * Get a form for a particular user, with admin check (or in the call)?
 * 	if no form returned, show new one of number type
 *
 */
function cc_ohio_chc_get_county_entry_by_form_number( $form_num, $user_id = 0 ){

	//GF form number lookup
	//$gf_form_num = cc_ohio_chc_get_form_num( $form_num );
	$gf_form_num = $form_num;
	
	//if incoming user_id not set, use current user id
	if ( $user_id == 0 ){
		$user_id = get_current_user_id();
	}
	
	//find user assigned to county
	$user_county = get_user_county( $user_id ); //"Montgomery County"
	//var_dump( $user_county);
	
	//var_dump( $user_county);
	$county_field_name = "cc_ohio_county";
	
	$total_count = 1;
	
	//Get the field id for cc_ohio_county (for this form) arg.
	$form_obj = GFAPI::get_form( $gf_form_num );
	
	$field_id = get_gf_field_id_by_label( $form_obj, $county_field_name ); //since we have to query GF by field ID
	//var_dump( $field_id );
	
	//if there's an entry from this county, populate the form 
	$search_criteria["field_filters"][] = array('key' => $field_id, 'value' => $user_county);
	$entry_this_county = GFAPI::get_entries( $gf_form_num, $search_criteria, null, null, $total_count );
	
	//var_dump ( $entry_this_county );
	
	// If no user assigned to county, get new GF form of gf_form_num and prepopulate county field
	if( $entry_this_county == NULL ){
		return NULL;
	
	} else {
		return $entry_this_county;
	}



}

/*
 * Form lookup; which form for which environment?
 *	TODO: update this list as forms created
 *
 */
function cc_ohio_chc_get_form_num( $form_num = 1 ){
	//TODO: fill in as we create on locals and devs
	 switch ( get_home_url() ) {
        case 'http://localhost/wordpress':
			switch( $form_num ){
				case 1:
					return 8;
					break;
				case 2:
					return 15;
					break;
				case 3:
					return 16;
					break;
				case 4:
					return 18;
					break;
				case 5:
					return 20;
					break;
				case 6:
					return 23;
					break;
				case 7:
					return 19;
					break;
				default:
					return 8;
					break;
			}
            break;
		case 'http://localhost/cc_local':
			switch( $form_num ){
				case 1:
					return 30;
					break;
				case 2:
					return 32;
					break;
				default:
					return 30;
					break;
			}
            break;
        case 'http://dev.communitycommons.org':
			switch( $form_num ){
				case 1:
					return 38;
					break;
				case 2:
					return 48;
					break;
				case 3:
					return 49;
					break;
				case 4:
					return 50;
					break;
				case 5:
					return 51;
					break;
				case 6:
					return 44;
					break;
				case 7:
					return 42;
					break;
				default:
					return 38;
					break;
			}
            break;
    }
    return $gf_form_num;
	
}

/*
 * Form lookup; get array of ohio forms (except user-county) by environment
 *	TODO: update this list as forms created
 *
 */
function cc_ohio_chc_get_gf_forms_all( ){

	
	//TODO: fill in as we create on locals and devs
	 switch ( get_home_url() ) {
        case 'http://localhost/wordpress':
			//TODO: Mike, fill in your gf form numbers
			$form_array = array( 8, 15, 16, 18, 19, 20, 23 );
            break;
		case 'http://localhost/cc_local':
			$form_array = array(30, 32, 33, 34, 35, 36);
            break;
        case 'http://dev.communitycommons.org':
		
			$form_array = array( 38, 48, 49, 50, 51, 44, 46 );
            break;
        default: //live site
		
			$form_array = array( 30 );
            break;
    }
    return $form_array;
	
}


/*
 * User-county assignment Form lookup; which form for which environment?
 *	TODO: update this list as forms created
 *
 */
function cc_ohio_chc_get_user_county_form_num(){
	//TODO: fill in as we create on locals and devs
	switch ( get_home_url() ) {
        case 'http://localhost/wordpress':
			return 24;
		case 'http://localhost/cc_local':
			return 37;
        case 'http://dev.communitycommons.org':
            return 45;
            
        default: //live site
            return 24;
    }
}

/*
 * Get GF's field id (what we have to query on, arg) for a form with a label
 *
 * @param form object, string
 * @return int Field ID
 */
function get_gf_field_id_by_label( $form_obj, $label_name ){
	
	foreach( $form_obj['fields'] as $key => $field ) {
		//var_dump( $key, $field['label']);
		if( $field['label'] == $label_name ){
			return($field['id']);
			
		}
	}
	
	return NULL;


}

function cc_ohio_chc_is_stickyform_active() {
	/**
	 * Detect plugin. For use on Front End only.
	 */
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	// check for plugin using plugin name
	if ( is_plugin_active( 'gravity-forms-sticky-list/sticky-list.php' ) ) {
	  //plugin is activated
		return true;
	} else {
		return false;
	}
}

