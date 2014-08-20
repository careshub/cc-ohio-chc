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
function cc_aha_get_quick_survey_summary_slug(){
    return 'quick-summary';
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
 * Can this user fill out the assessment and such?
 * 
 * @return boolean
 */
function cc_aha_user_can_do_assessment(){
    // Only members who have an "@heart.org" email address (and site admins) are allowed to fill out the assessment 
    if ( ! is_user_logged_in() ) {
        return false;
    } else if ( current_user_can( 'delete_others_posts' ) ) {
        return true;
    } else {
        $current_user = wp_get_current_user();
        $email_parts = explode('@', $current_user->user_email);
        if ( $email_parts[1] == 'heart.org' ) {
            return true;
        } 
    }
    // If none of the above fired...
    return false;
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
function cc_aha_on_survey_quick_summary_screen(){
    if ( cc_aha_is_component() && bp_is_action_variable( cc_aha_get_quick_survey_summary_slug(), 0 ) ){
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
/**
 * Create a "nice" version of the metro's info
 * 
 * @since   1.0.0
 * @return  string
 */
function cc_aha_get_metro_nicename( $metro_id = null ) {
    if ( ! $metro_id )
        $metro_id = $_COOKIE[ 'aha_active_metro_id' ];

    if ( ! $metro_id )
        return "None selected";

    $metro = cc_aha_get_single_metro_data( $metro_id );

    return $metro['Board_Name'];
    // return $metro['Board_Name'] . ' &ndash; ' . $metro['BOARD_ID'];

}
/**
 * Check for a survey page's completeness by comparing the questions to the saved data
 * 
 * @since   1.0.0
 * @return  boolean
 */
function aha_survey_page_completed( $page, $board_data, $school_data ) {
    $questions = cc_aha_get_form_questions( $page );

    // Some pages need to be handled differently. 
    $form_pages = cc_aha_form_page_list();

    // CPR is weird. If the state has requirements, we don't need to ask.
    $cpr_page = array_search( 'Chain of Survival - CPR Graduation Requirements', $form_pages );
    if ( $page == $cpr_page && $board_data['5.1.1'] ){
        return true;
    }

    // Top 25 Companies is weird
    $top_25 = array_search( 'Top 25 Companies', $form_pages ); 
    if ( $page == $top_25 ){
        return false;
    }

    foreach ($questions as $question) {
        // Ignore the follow-up questions
        if ( $question['follows_up'] )
            continue;

        // If any of the data points are null (they might be 0, which is OK), we return false.
        if ( $question['loop_schools'] ){
            // This data will be in the schools table. And we'll need to loop through
            foreach ($school_data as $district) {
                if ( $district[ $question['QID'] ] == '' ) {
                    return false;   
                }
            }
        } else {
            // This data will be in the board table
            if ( $board_data[ $question['QID'] ] == '' ) {
                return false;   
            }
        }
    }
    // If we make it out of the foreach, all is well.
    return true;
}