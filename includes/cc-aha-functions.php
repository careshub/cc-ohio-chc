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
 * Are we on the AHA extras tab?
 *
 * @since   1.0.0
 * @return  boolean
 */
function cc_aha_is_component() {
    if ( bp_is_groups_component() && bp_is_current_action( cc_aha_get_slug() ) )
        return true;

    return false;
}

/**
 * Is this the AHA group?
 *
 * @since    1.0.0
 * @return   boolean
 */
function cc_aha_is_aha_group(){
    return ( bp_get_current_group_id() == cc_aha_get_group_id() );
}

/**
 * Get the group id based on the context
 *
 * @since   1.0.0
 * @return  integer
 */
function cc_aha_get_group_id(){
    switch ( get_home_url() ) {
        case 'http://commonsdev.local':
            $group_id = 55;
            break;
        case 'http://dev.communitycommons.org':
            $group_id = 592;
            break;
        default:
            $group_id = 594;
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
function cc_aha_get_slug(){
    return 'assessment';
}
function cc_aha_get_survey_slug(){
    return 'survey';
}
function cc_aha_get_analysis_slug(){
    return 'analysis';
}

/**
 * Get URIs for the various pieces of this tab
 * 
 * @return string URL
 */
function cc_aha_get_home_permalink( $group_id = false ) {
    $group_id = ( $group_id ) ? $group_id : bp_get_current_group_id() ;
    $permalink = bp_get_group_permalink( groups_get_group( array( 'group_id' => $group_id ) ) ) .  cc_aha_get_slug() . '/';
    return apply_filters( "cc_aha_home_permalink", $permalink, $group_id);
}
function cc_aha_get_survey_permalink( $page = 1, $group_id = false ) {
    $permalink = cc_aha_get_home_permalink( $group_id ) . cc_aha_get_survey_slug() . '/' . $page . '/';
    return apply_filters( "cc_aha_survey_permalink", $permalink, $group_id);
}
function cc_aha_get_analysis_permalink( $group_id = false ) {
    $permalink = cc_aha_get_home_permalink( $group_id ) . cc_aha_get_analysis_slug() . '/';
    return apply_filters( "cc_aha_analysis_permalink", $permalink, $group_id);
}

/**
 * Where are we?
 * Checks for the various screens
 *
 * @since   1.0.0
 * @return  string
 */
function cc_aha_on_main_screen(){
    // There should be no action variables if on the main tab
    if ( cc_aha_is_component() && ! ( bp_action_variables() )  ){
        return true;
    } else {
        return false;
    }
}
function cc_aha_on_survey_screen(){
    if ( cc_aha_is_component() && bp_is_action_variable( cc_aha_get_survey_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_aha_on_analysis_screen(){
   if ( cc_aha_is_component() && bp_is_action_variable( cc_aha_get_analysis_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}

/**
 * Retrieve a user's metro affiliation
 * 
 * @since   1.0.0
 * @return  array of metro IDs, empty array if none (helps with counting later)
 */
function cc_aha_get_array_user_metro_ids() {
    $selected = get_user_meta( get_current_user_id(), 'aha_board', true );

    if ( ! is_array( $selected ) )
        $selected = array();

    return $selected;
}