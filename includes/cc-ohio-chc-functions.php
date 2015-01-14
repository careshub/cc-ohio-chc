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
        case 'http://localhost/wordpress/':
            $group_id = 596;
            break;
		case 'http://localhost/cc_local':
            $group_id = 599;
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
    return 'assessment';
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
function cc_ohio_chc_get_assessment_permalink( $page = 1, $group_id = false ) {
    $permalink = cc_ohio_chc_get_home_permalink( $group_id ) . cc_ohio_chc_get_slug() . '/' . $page . '/';
    return apply_filters( "cc_ohio_chc_get_assessment_permalink", $permalink, $group_id);
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
function cc_ohio_chc_on_assessment_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( cc_ohio_chc_get_slug(), 0 ) ){
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
	if ( bp_group_has_members( array( 'group_id' => $group_id ) ) ) {
	
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

	//TODO: populate this!
	$counties = array(
		"Hamilton",
		"Washington",
		"Burlington"
		);
	
	return $counties;
	
}