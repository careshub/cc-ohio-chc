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
 * Handling Metro ID input and save
 *
 * @since   1.0.0
 */

/**
 * Save metro ids as user meta
 * Saves selection as serialized data in the usermeta table
 * 
 * @since   1.0.0
 * @return  boolean
 */
function cc_aha_save_metro_ids(){
    $selected_metros = $_POST['aha_metro_ids'];
    $user_metros = get_user_meta( get_current_user_id(), 'aha_board' );

    if ( empty( $selected_metros ) ) {
        $success = delete_user_meta( get_current_user_id(), 'aha_board' );
    } else {
        $success = update_user_meta( get_current_user_id(), 'aha_board', $selected_metros );
    }

    return $success;
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
 * Logic to navigate the pages of the long form
 * 
 * @since   1.0.0
 * @return  string - URL
 */
function cc_aha_get_next_form_page_url(){
    // TODO: Add some logic to navigate the form pages
    $towrite = PHP_EOL . 'referer is: ' . print_r(wp_get_referer(), TRUE);
    $fp = fopen('aha_form_save.txt', 'a');
    fwrite($fp, $towrite);
    fclose($fp);
    return wp_get_referer();
}
function cc_aha_get_prev_form_page_url(){
    // TODO: Add some logic to navigate the form pages
    return wp_get_referer();
}
function cc_aha_get_max_page_number(){
    return 7;
}

/**
 * Fetch the array of all Metro IDs
 * 
 * @since   1.0.0
 * @return  array( Metro_ID => Location )
 */
function cc_aha_get_metro_array(){
    // Will probably get this from the new table. This is a short-term convenience.
    return array( 'GRA01' => 'Adams (PA)',
        'GRA02' => 'Akron (OH)',
        'FDA01' => 'Albany (NY)',
        'SWA01' => 'Albuquerque (NM)',
        'GRA03' => 'Allegheny (PA)',
        'GSA01' => 'Atlanta (GA)',
        'SWA02' => 'Austin (TX)',
        'GSA02' => 'Baton Rouge (LA)',
        'GRA04' => 'Beaver Butler Counties Board (PA)',
        'GSA03' => 'Birmingham (AL)',
        'WSA01' => 'Boise (ID)',
        'FDA02' => 'Buffalo Niagara Region (NY)',
        'GRA05' => 'Canton (OH)',
        'GRA06' => 'Capital Region (PA)',
        'WSA02' => 'Central Coast Division (CA)',
        'FDA19' => 'Central NJ (NJ)',
        'WSA03' => 'Central Valley Division (CA)',
        'GRA07' => 'Charleston (WV)',
        'MAA01' => 'Charlotte (NC)',
        'MWA01' => 'Chicago (IL)',
        'GRA08' => 'Cincinnati (OH)',
        'GRA09' => 'Cleveland (OH)',
        'WSA04' => 'Coachella Valley Division (CA)',
        'SWA03' => 'Colorado Springs (CO)',
        'MAA07' => 'Columbia (SC)',
        'GRA10' => 'Columbus (OH)',
        'SWA04' => 'Corpus Christi (TX)',
        'SWA05' => 'Dallas (TX)',
        'GRA11' => 'Dayton (OH)',
        'SWA06' => 'Denver (CO)',
        'MWA02' => 'Des Moines (IA)',
        'MWA03' => 'Detroit (MI)',
        'FDA04' => 'Dutchess-Ulster Counties (NY)',
        'WSA05' => 'East Bay Division (CA)',
        'GRA12' => 'Erie (PA)',
        'GRA29' => 'Fayette (PA)',
        'GRA13' => 'Franklin Fulton (PA)',
        'MWA12' => 'Grand Rapid (MI)',
        'MAA02' => 'Greater Baltimore (MD)',
        'FDA05' => 'Greater Boston (MA)',
        'FDA06' => 'Greater Hartford (CT)',
        'GSA04' => 'Greater New Orleans Area (LA)',
        'MAA03' => 'Greater Washington Region (DC)',
        'MAA04' => 'Hampton Roads (VA)',
        'WSA06' => 'Hawaii (HI)',
        'SWA07' => 'Houston (TX)',
        'MWA04' => 'Indianapolis (IN)',
        'WSA07' => 'Inland Empire Division (CA)',
        'GSA14' => 'Jackson (MS)',
        'GSA05' => 'Jacksonville (FL)',
        'MWA05' => 'Kansas City (MO)',
        'WSA08' => 'Kern County Division (CA)',
        'GRA14' => 'Lancaster (PA)',
        'WSA09' => 'Las Vegas Division (NV)',
        'GRA15' => 'Lebanon (PA)',
        'GRA16' => 'Lehigh Valley (PA)',
        'GRA17' => 'Lexington (KY)',
        'SWA08' => 'Little Rock (AR)',
        'FDA07' => 'Long Island (NY)',
        'WSA10' => 'Los Angeles County Division (CA)',
        'GRA18' => 'Louisville (KY)',
        'MWA11' => 'Madison (WI)',
        'FDA08' => 'Maine (ME)',
        'GSA06' => 'Memphis (TN)',
        'GSA07' => 'Miami / Ft. Lauderdale (FL)',
        'GRA19' => 'Middletown (OH)',
        'MWA06' => 'Milwaukee (WI)',
        'SWA14' => 'Montgomery County (TX)',
        'GSA08' => 'Nashville (TN)',
        'GRA20' => 'New Castle (DE)',
        'FDA09' => 'New Hampshire  (NH)',
        'FDA10' => 'New York (NY)',
        'GRA21' => 'Northeast PA (PA)',
        'WSA11' => 'Northern Nevada (NV)',
        'FDA11' => 'Northern New Jersey (NJ)',
        'SWA09' => 'Northwest Arkansas (AR)',
        'MWA13' => 'NW Indiana  (IN)',
        'SWA10' => 'Oklahoma City (OK)',
        'MWA07' => 'Omaha (NE)',
        'WSA13' => 'Orange County Division (CA)',
        'FDA12' => 'Orange-Sullivan-Rockland Counties (Tri County Board) (NY)',
        'GSA09' => 'Orlando (FL)',
        'GSA10' => 'Palm Beach (FL)',
        'GRA22' => 'Philadelphia (PA)',
        'WSA14' => 'Phoenix (AZ)',
        'WSA12' => 'Portland (OR)',
        'FDA13' => 'Rhode Island (RI)',
        'MAA05' => 'Richmond (VA)',
        'FDA14' => 'Rochester (NY)',
        'GSA13' => 'Rutherford County (TN)',
        'WSA15' => 'Sacramento Division (CA)',
        'SWA11' => 'San Antonio (TX)',
        'WSA16' => 'San Diego Division (CA)',
        'WSA17' => 'San Francisco Division (CA)',
        'WSA18' => 'Seattle (WA)',
        'WSA19' => 'Silicon Valley (CA)',
        'GRA23' => 'Southern Delaware (DE)',
        'FDA18' => 'Southern NJ (NJ)',
        'GSA11' => 'Southwest Florida (FL)',
        'MWA08' => 'St Louis (MO)',
        'FDA15' => 'Syracuse (NY)',
        'WSA20' => 'Tacoma (WA)',
        'GSA12' => 'Tampa Bay (FL)',
        'SWA12' => 'Tarrant County (TX)',
        'GRA28' => 'Toledo (OH)',
        'MAA06' => 'Triangle Metro Division (NC)',
        'WSA21' => 'Tucson (AZ)',
        'SWA13' => 'Tulsa (OK)',
        'MWA09' => 'Twin Cities (MN)',
        'WSA22' => 'Utah Division (UT)',
        'FDA16' => 'Utica (NY)',
        'FDA17' => 'Vermont (VT)',
        'GRA24' => 'Washington County and Mon Valley Board (PA)',
        'GRA25' => 'Westmoreland County Board (PA)',
        'MWA10' => 'Wichita (KS)',
        'GRA26' => 'York (PA)',
        'GRA27' => 'Youngstown (OH)'
    );
}