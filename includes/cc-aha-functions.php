<?php 

/**
 * Are we on the AHA extras tab?
 *
 * @since    0.1.0
 */
function cc_aha_is_component() {
    if ( bp_is_groups_component() && bp_is_current_action( cc_aha_get_slug() ) )
        return true;

    return false;
}

function cc_aha_is_aha_group(){
    return ( bp_get_current_group_id() == cc_aha_get_group_id() );
}

function cc_aha_get_group_id(){
    return ( get_home_url() == 'http://commonsdev.local' ) ? 55 : 594 ;
}

function cc_aha_get_slug(){
    return 'assessment';
}
/**
 * Get base URI for this plugin's tab
 * 
 * @return string URL
 */
function cc_aha_get_home_permalink( $group_id = false ) {
    // If a group_id is supplied, it is probably because the post originated from another group (and editing should occur from the original group's space).
    $group_id = !( $group_id ) ? bp_get_current_group_id() : $group_id ;
    $permalink = bp_get_group_permalink( groups_get_group( array( 'group_id' => $group_id ) ) ) .  cc_aha_get_slug();
    return apply_filters( "cc_aha_home_permalink", $permalink, $group_id);
}

// Dealing with Metro IDs
// Creating form to set metro ids for users
function cc_aha_metro_select_markup(){
    $user_metros_array = cc_aha_get_array_user_metro_ids();
    $metros = cc_aha_get_metro_array();

    // Using checkboxes since a user could choose one or several
    ?>
    <form id="aha_metro_id_select" class="" method="post" action="<?php echo cc_aha_get_home_permalink(); ?>/save-metro-id/">
        <ul class="aha_metro_id_list no-bullets text-columns-three">
    <?php 
        foreach ($metros as $metro_id => $location) {
            ?>
            <li>
                <input type="checkbox" name="aha_metro_ids[]" id="aha_metro_ids-<?php echo $metro_id; ?>" value="<?php echo $metro_id; ?>" <?php if ( in_array( $metro_id, $user_metros_array) ) : ?>checked<?php endif; ?> /> <label for="aha_metro_ids-<?php echo $metro_id; ?>" class=""><?php echo $location . ' (' . $metro_id . ')'; ?></label>
            </li>
            <?php
        }
        ?></ul>
        <?php wp_nonce_field( 'cc-aha-set-metro-id', 'set-metro-nonce' ) ?>
        <div class="submit">
            <input id="submit-metro-ids" type="submit" value="Save" name="submit-metro-ids">
        </div>
    </form>
<?php
}

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

function cc_aha_print_metro_id_list(){
    echo cc_aha_get_metro_id_list();
}
function cc_aha_get_metro_id_list(){
    $user_metros = cc_aha_get_array_user_metro_ids();
    $retval = '';
    $count = 1;
    foreach ($user_metros as $metro_id) {
        if ( $count != 1 ){ 
            $retval .= ', ';
        }
        $retval .= $metro_id;
        $count++;
    }
    return $retval;

}
function cc_aha_get_array_user_metro_ids() {
    return get_user_meta( get_current_user_id(), 'aha_board', true );
}

function cc_aha_get_next_form_page(){
    // TODO: Add some logic to navigate the form pages
    return wp_get_referer();
}
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