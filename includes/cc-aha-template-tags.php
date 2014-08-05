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
 * Creating container for form to set metro ids for users
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_aha_print_metro_select_container_markup() {
    // Get the user's Metro ID
    if ( cc_aha_get_array_user_metro_ids() ) {
        $summary_message = 'Your board affiliations: ' . cc_aha_get_metro_id_list();
        $link_text = 'Change';
    } else {
        $summary_message = 'Please set your AHA board affiliation.';
        $link_text = 'Set region';
    }

    ?>
    <div class="toggleable toggle-closed message info">
        <p class="toggle-switch first" id="update-metro-id-toggle">
            <?php echo $summary_message; ?>&emsp;<a class="toggle-link" id="update-metro-id-toggle-link" href="#"><span class="show-pane plus-or-minus"></span><?php echo $link_text; ?></a>
        </p>

        <div class="toggle-content">
            <?php cc_aha_metro_select_markup(); ?>
        </div>
    </div>
<?php
}

/**
 * Creating form to set metro ids for users
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_aha_metro_select_markup(){
    $user_metros_array = cc_aha_get_array_user_metro_ids();
    $metros = cc_aha_get_metro_id_array();

    // Using checkboxes since a user could choose one or several
    ?>
    <form id="aha_metro_id_select" class="" method="post" action="<?php echo cc_aha_get_home_permalink(); ?>save-metro-id/">
        <ul class="aha_metro_id_list no-bullets text-columns-three">
    <?php 
        foreach ($metros as $metro) {
            ?>
            <li>
                <input type="checkbox" name="aha_metro_ids[]" id="aha_metro_ids-<?php echo $metro['BOARD_ID']; ?>" value="<?php echo $metro['BOARD_ID']; ?>" <?php if ( in_array( $metro['BOARD_ID'], $user_metros_array) ) : ?>checked<?php endif; ?> /> <label for="aha_metro_ids-<?php echo $metro['BOARD_ID']; ?>" class=""><?php echo $metro['Board_Name'] . ' &ndash; ' . $metro['BOARD_ID']; ?></label>
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

/**
 * Builds a comma-separated list of metro ids and descriptions
 * Probably of the form: Adams (PA) - GRA01
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_aha_print_metro_id_list(){
    echo cc_aha_get_metro_id_list();
}
function cc_aha_get_metro_id_list(){
    $user_metros = cc_aha_get_array_user_metro_ids();
    $retval = '';
    $count = 1;
    foreach ($user_metros as $metro_id) {
        $metro = cc_aha_get_single_metro_data( $metro_id );

        if ( $count != 1 ){ 
            $retval .= ', ';
        }
        $retval .= $metro['Board_Name'] . ' &ndash; ' . $metro['BOARD_ID'];
        $count++;
    }
    return $retval;

}
/**
 * Builds the subnav of the AHA group tab
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_aha_render_tab_subnav(){

        // Only members who have an "@heart.org" email address (and site admins) are allowed to fill out the assessment 
        $current_user = wp_get_current_user();
        $email_parts = explode('@', $current_user->user_email);
        ?>
        <div id="subnav" class="item-list-tabs no-ajax">
            <ul class="nav-tabs">
                <li <?php if ( cc_aha_on_main_screen() ) { echo 'class="current"'; } ?>>
                    <a href="<?php echo cc_aha_get_home_permalink(); ?>">Introduction</a>
                </li>
                <?php
                if ( current_user_can( 'delete_others_posts' ) || $email_parts[1] == 'heart.org' ) :
                    ?>
                    <li <?php if ( cc_aha_on_survey_screen() ) { echo 'class="current"'; } ?>>
                        <a href="<?php echo cc_aha_get_survey_permalink(); ?>">Assessment</a>
                    </li>
                <?php endif; ?>
                <li <?php if ( cc_aha_on_analysis_screen() ) { echo 'class="current"'; } ?>>
                    <a href="<?php echo cc_aha_get_analysis_permalink(); ?>">View Report</a>
                </li>
            </ul>
        </div>
        <?php
}
/**
 * Stores the selected metro ID as a cookie value for persistence
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_aha_metro_id_cookie_selector(){

    $cookie_name = 'aha_active_metro_id';
    // We need to know the user's affiliations
    $selected_metro_ids = cc_aha_get_array_user_metro_ids();

    // If cookie doesn't exist, we may need to show the user a form.
    if ( empty( $_COOKIE[ $cookie_name ] ) ) {
            // User hasn't selected an "active" metro ID yet, display form
            if ( empty( $selected_metro_ids ) ){
                 cc_aha_print_metro_select_container_markup();
            } else if ( count( $selected_metro_ids ) > 1 ) {
                 cc_aha_metro_id_cookie_select_form();
            }
    } else {
        //TODO: get human readable description
        $metro_info = $_COOKIE[$cookie_name];
        // If the user has more than one affiliation, give chance to change
        if ( count( $selected_metro_ids ) > 1 ) {
            ?>
            <div class="toggleable toggle-closed message info">
                <p class="toggle-switch first" id="update-metro-id-toggle">You are currently viewing information for <?php echo $metro_info; ?>. &emsp;<a class="toggle-link" id="update-metro-id-toggle-link" href="#">Change</a>
                </p>

                <div class="toggle-content">
                    <?php cc_aha_metro_id_cookie_select_dropdown(); ?>
                </div>
            </div>
        <?php
        } else { 
            ?>
            <div class="message info">
                <p class="first">You are currently viewing information for <?php echo $metro_info; ?></p>
            </div>
        <?php
        }
    }
}

function cc_aha_metro_id_cookie_select_form(){
    ?>
    <div class="message info">
        <?php cc_aha_metro_id_cookie_select_dropdown(); ?>
    </div>
    <?php
}

function cc_aha_metro_id_cookie_select_dropdown(){
    $selected_metro_ids = cc_aha_get_array_user_metro_ids();
    ?>
        <form id="aha_metro_id_cookie_select" class="" method="post" action="<?php echo cc_aha_get_home_permalink(); ?>set-metro-id-cookie/">
            <p>Choose a region to view. </p>
            <select name="aha_metro_id_cookie">
            <?php foreach ($selected_metro_ids as $metro_id) {
                //TODO: get human readable description
                $metro_info = $metro_id;
                ?>
                <option value="<?php echo $metro_id; ?>"><?php echo $metro_info; ?></option>
                <?php
            } 
            ?>
            </select>
            <?php wp_nonce_field( 'cc-aha-set-metro-id-cookie', 'set-metro-cookie-nonce' ); ?>
            <div class="submit">
                <input id="submit-metro-ids" type="submit" value="Save" name="submit-metro-ids">
            </div>
        </form>
    <?php
}