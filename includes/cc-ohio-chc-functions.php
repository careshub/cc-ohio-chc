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
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( cc_ohio_chc_get_form_num_slug( 2 ), 0 ) ){
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


//adds dropdown list of regions to User-County Assignment
add_filter("gform_column_input_37_1_2", "set_column", 10, 5);
function set_column($input_info, $field, $column, $value, $form_id){
    return array("type" => "select", "choices" => "Adams-Brown Counties,Allen County,Athens County,Cincinnati,Columbus City,Cuyahoga County,Lorain County,Lucas County,Marion County,Meigs County,Montgomery County,Richland County,Summit County,Trumbull County,Washington County");
}

add_filter('gform_field_value_region_name', 'cc_ohio_add_region');
function cc_ohio_add_region($value){
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

    return $region;
}

add_filter('gform_field_value_entry_year', 'cc_ohio_add_year');
function cc_ohio_add_year($value){
    return date("Y");
}

add_action( 'init', 'register_cpt_odh_chc_entry' );

function register_cpt_odh_chc_entry() {

    $labels = array( 
        'name' => _x( 'ODH_CHC Entries', 'odh_chc_entry' ),
        'singular_name' => _x( 'ODH_CHC Entry', 'odh_chc_entry' ),
        'add_new' => _x( 'Add New', 'odh_chc_entry' ),
        'all_items' => _x( 'ODH_CHC Entries', 'odh_chc_entry' ),
        'add_new_item' => _x( 'Add New ODH_CHC Entry', 'odh_chc_entry' ),
        'edit_item' => _x( 'Edit ODH_CHC Entry', 'odh_chc_entry' ),
        'new_item' => _x( 'New ODH_CHC Entry', 'odh_chc_entry' ),
        'view_item' => _x( 'View ODH_CHC Entry', 'odh_chc_entry' ),
        'search_items' => _x( 'Search ODH_CHC Entries', 'odh_chc_entry' ),
        'not_found' => _x( 'No odh_chc entries found', 'odh_chc_entry' ),
        'not_found_in_trash' => _x( 'No odh_chc entries found in Trash', 'odh_chc_entry' ),
        'parent_item_colon' => _x( 'Parent ODH_CHC Entry:', 'odh_chc_entry' ),
        'menu_name' => _x( 'ODH_CHC Entries', 'odh_chc_entry' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
		'supports' => array( 'title', 'editor', 'custom-fields', 'page-attributes', 'author', 'excerpt' ),   
        'show_in_menu' => true
    );

    register_post_type( 'odh_chc_entry', $args );
}

