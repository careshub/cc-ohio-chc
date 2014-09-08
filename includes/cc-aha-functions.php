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
function cc_aha_get_analysis_health_slug(){
    return 'health';
}
function cc_aha_get_analysis_revenue_slug(){
    return 'revenue';
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
function cc_aha_get_analysis_permalink( $section = false, $metro_id = false ) {
    // If none is specified, we need to insert a placeholder, so that the bp_action_variables stay in the correct position.
    // if we pass a metro_id, it trumps all
    if ( $metro_id ) {
        $metro_id_string = $metro_id . '/';
    } else {
        $metro_id_string = ( $metro_id = $_COOKIE['aha_summary_metro_id'] ) ? $metro_id . '/' : '00000/';
    }

    // If we've specified a section, build it, else assume health.
    // Expects 'revenue' or 'health'
    $section_string = ( $section == 'revenue' ) ? cc_aha_get_analysis_revenue_slug() . '/' : cc_aha_get_analysis_health_slug() . '/';

    $permalink = cc_aha_get_home_permalink() . cc_aha_get_analysis_slug() . '/' . $metro_id_string . $section_string;
    return apply_filters( "cc_aha_analysis_permalink", $permalink, $section, $metro_id);
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
 * Super access for secret development
 * 
 * @return boolean
 */
function cc_aha_user_has_super_secret_clearance(){
    // Only members who have an "@heart.org" email address (and site admins) are allowed to fill out the assessment 
    if ( ! is_user_logged_in() ) {
        return false;
    } else if ( current_user_can( 'delete_others_posts' ) ) {
        return true;
    } else {
        $current_user = wp_get_current_user();
        if ( in_array( strtolower( $current_user->user_email ), cc_aha_super_secret_access_list() ) ) {
            return true;
        } 
    }
    // If none of the above fired...
    return false;
}

function cc_aha_super_secret_access_list(){
    return array(
            'ben.weittenhiller@heart.org', 'christian.caldwell@heart.org', 'johnsonange@missouri.edu', 'david.cavins+cassie@gmail.com'
        );

}

function cc_aha_resolve_summary_metro_id(){
    // Cookies set with setcookie() aren't available until next page load. So we compare the URL to the cookie and see what's what. We prefer the URL info.

    // We need to compare to exclude action .

    if ( bp_action_variable( 1 ) != '00000' && $metro_id = bp_action_variable( 1 ) )
        return $metro_id;

    if ( $metro_id = $_COOKIE['aha_summary_metro_id'] )
        return $metro_id;

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
function cc_aha_on_analysis_screen( $section = null ){
    // If we're checking for a specific subsection, check for it.
    if ( $section && in_array( $section, array(  cc_aha_get_analysis_health_slug(), cc_aha_get_analysis_revenue_slug() ) ) ) {

        if ( cc_aha_is_component() && bp_is_action_variable( cc_aha_get_analysis_slug(), 0 ) && bp_is_action_variable( $section, 2 ) ){
            return true;
        } else {
            return false;
        }

    }

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
function cc_aha_on_analysis_complete_report_screen(){
   if ( cc_aha_is_component() && bp_is_action_variable( 'all', 3 ) ){
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
        return "none selected";

    $metro = cc_aha_get_single_metro_data( $metro_id );

    return $metro['Board_Name'];
    // return $metro['Board_Name'] . ' &ndash; ' . $metro['BOARD_ID'];

}
/**
 * Find the human-readable option label for a question's saved response.
 * 
 * @since   1.0.0
 * @return  string
 */
function cc_aha_get_matching_option_label( $qid, $value ){
    $question = cc_aha_get_question( $qid );
    $options = cc_aha_get_options_for_question( $qid );
    
    if ( $question[ 'type' ] == 'radio' ) {
        foreach ($options as $option) {
            if ( $option[ 'value' ] == $value ){
                $retval = $option[ 'label' ];
                break; // Once we have a match, we can stop.
            }
        }
    } else {
        // must be checkboxes
        $response_array = array_keys( maybe_unserialize( $value ) );
        $selected_options = array();

        foreach ($options as $option) {
            if ( in_array( $option[ 'value' ], $response_array ) ){
                $selected_options[] = $option[ 'label' ];
            }
        }
        $retval = implode(', ', $selected_options);
    }

    return $retval;
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

    // Top 25 Companies is weird. We have no idea how "complete" this section is, since it's done off-site.
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

/**
 * Get the FIPS codes for a metro_id
 *
 * @since   1.0.0
 * @return  comma-delimited string.
 */ 
function cc_aha_get_fips( $cleaned = false ){
    if ( ! $metro_id = cc_aha_resolve_summary_metro_id() )
        return false;

    //MB added JSON service to get FIPS using selected metro id.
    $response = wp_remote_get( 'http://maps.communitycommons.org/api/service.svc/json/AHAfips/?metroid=' . $metro_id );

    //read JSON response
     if( is_array( $response ) ) {
         $r = wp_remote_retrieve_body( $response );
         $output = json_decode( $r, true );
         //var_dump($output);
         $fips = $output['getAHAfipsResult'][0]['fips'];
         $cleanedfips = str_replace('05000US','',$fips); 
         
         return ( $cleaned ) ? $cleanedfips : $fips;
     } 

}


/**
 * Not a statistically sound calculation of % of students that receive a certain number of PE minutes per week.
 *
 * @since   1.0.0
 * @arguments   $metro_id and $level which is one of elem, midd, high, or all
 * @return  integer (formatted like a percentage)
 */ 
function cc_aha_top_5_school_pe_calculation( $metro_id, $level = 'all' ) {
    $school_data = cc_aha_get_school_data( $metro_id );
    $total_pop = 0;
    $covered_pop = 0;
    foreach ( $school_data as $district ) {
        if ( $level == 'elem' || $level == 'all' ) {
            $total_pop = $total_pop + (int) $district['ELEM'];
            if ( $district['2.1.4.1.1'] ) {
                $covered_pop = $covered_pop + (int) $district['ELEM'];
            }
        }
        if ( $level == 'midd' || $level == 'all' ) {
            $total_pop = $total_pop + (int) $district['MIDD'];
            if ( $district['2.1.4.1.2'] ) {
                $covered_pop = $covered_pop + (int) $district['MIDD'];
            }
        }
        if ( $level == 'high' || $level == 'all' ) {
            $total_pop = $total_pop + (int) $district['HIGH'];
            if ( $district['2.1.4.1.3'] ) {
                $covered_pop = $covered_pop + (int) $district['HIGH'];
            }
        }
    }

    return ( $total_pop ) ? round( $covered_pop / $total_pop * 100 ) : 0;

}

/**
 * % of districts that have a particular value for a particular question.
 *
 * @since   1.0.0
 * @arguments   $metro_id and $level which is one of elem, midd, high, or all
 * @return  integer (formatted like a percentage)
 */ 
function cc_aha_top_5_school_percent_match_value( $metro_id, $qid, $value ) {
    $school_data = cc_aha_get_school_data( $metro_id );
    $matches = 0;
    $num_disticts = count( $school_data );
    foreach ( $school_data as $district ) {
        if ( $district[ $qid ] == $value )
            ++$matches;
    }

    return ( $num_disticts ) ? round( $matches / $num_disticts * 100 ) : 0;

}