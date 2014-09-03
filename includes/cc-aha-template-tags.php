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
 * Print content for the "Introduction" (default tab)
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_aha_print_introductory_text(){
    ?>
    <p>
        Welcome to the American Heart Association’s Community Planning 2.0 page. We are pleased to partner with Community Commons to build a culture of health so that all Americans live in environments that support healthy behaviors, timely and quality care. The American Heart Association has established a robust and overarching goal to improve the cardiovascular health of all Americans by 20% and reduce deaths from cardiovascular diseases and stroke by 20% by the year 2020. Here on the Commons, we're conducting an internal survey to identify needs and priorities for each of the American Heart Associations's metro areas. Check back later this fall as we share our results and related community indicators. 
    </p>
    <?php
}
/**
 * Creating container for form to set metro ids for users
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_aha_print_metro_select_container_markup() {
    ?>
    <form id="aha_metro_id_select" class="" method="post" action="<?php echo cc_aha_get_home_permalink() . 'save-board-ids/'; ?>">
    <?php

    // If user hasn't selected board affiliations, start there.
    if ( ! cc_aha_get_array_user_metro_ids() ) {
        ?>
        <div class="toggleable toggle-open message info">
            <p class="toggle-switch first" id="update-metro-id-toggle">
                Please select your AHA board affiliation.&emsp;<a class="toggle-link" id="update-metro-id-toggle-link" href="#"><span class="show-pane plus-or-minus"></span>Select your board</a>
            </p>

            <div class="toggle-content">
                <?php cc_aha_metro_select_markup(); ?>
            </div>
        </div>
        <?php
    } else {

        // If the user has selected affiliations, handle survey and analysis cases
        if ( cc_aha_on_survey_screen() ) {
            cc_aha_metro_id_cookie_selector( 'survey' );
        }

        if ( cc_aha_on_analysis_screen() ) {
            cc_aha_metro_id_cookie_selector( 'analysis' );
        ?>
            <input type="hidden" name="analysis-section" value="<?php echo bp_action_variable( 2 ); ?>">
        <?php
        }

    } // end if cc_aha_get_array_user_metro_ids()
    ?>

    <?php wp_nonce_field( 'cc-aha-save-board-id', 'save-aha-boards' ); ?>
    </form>
    <?php
}

/**
 * Creating form to set metro ids for users
 * Default version is checkboxes, secondary is radio buttons, for use on selecting summary to view
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_aha_metro_select_markup( $style = 'assessment' ){
    $user_metros_array = cc_aha_get_array_user_metro_ids();
    $metros = cc_aha_get_metro_id_array();

    // Using checkboxes since a user could choose one or several
    ?>

    <?php 
        if ( $style == 'assessment' ) {
            echo '<p class="info"><em>If you are responsible for multiple boards please select all the boards that apply.</em></p>';
        }
        foreach ($metros as $metro) {
            // Close, then start new affiliate group
            if ( $last_affiliate != $metro[ 'Affiliate' ] ) {
                // Close last ul if we're not at the beginning of the list
                if ( ! empty( $last_affiliate ) )
                echo '</ul>';

                ?>
                <h5><?php echo $metro[ 'Affiliate' ] . ' Affiliate'; ?></h5>
                <ul class="aha_metro_id_list no-bullets text-columns-three">
                <?php
            }

            ?>
            <li>
                <?php if ( $style == 'summary' ) : ?>
                    <input type="radio" name="aha_summary_metro_id" id="aha_summary_metro_id-<?php echo $metro['BOARD_ID']; ?>" value="<?php echo $metro['BOARD_ID']; ?>" <?php if ( isset( $_COOKIE['aha_summary_metro_id'] ) && $_COOKIE['aha_summary_metro_id'] == $metro['BOARD_ID'] ) { echo "checked"; } ?> /> <label for="aha_summary_metro_id-<?php echo $metro['BOARD_ID']; ?>" class=""><?php echo $metro['Board_Name']; ?></label>
                <?php else: ?>
                     <input type="checkbox" name="aha_metro_ids[]" id="aha_metro_ids-<?php echo $metro['BOARD_ID']; ?>" value="<?php echo $metro['BOARD_ID']; ?>" <?php if ( in_array( $metro['BOARD_ID'], $user_metros_array) ) : ?>checked<?php endif; ?> /> <label for="aha_metro_ids-<?php echo $metro['BOARD_ID']; ?>" class=""><?php echo $metro['Board_Name']; ?></label>
                <?php endif; ?>
            </li>
            <?php
            $last_affiliate = $metro[ 'Affiliate' ];
        }
        ?></ul>

        <div class="submit">
            <input id="submit-metro-ids" type="submit" value="Save" name="submit_save_usermeta_aha_board">
        </div>
<?php
}

/**
 * Stores the selected metro ID as a cookie value for persistence
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_aha_metro_id_cookie_selector( $context ){

    if ( $context == 'survey' ) {
        $cookie_name = 'aha_active_metro_id';
    } else if ( $context == 'analysis' ) {
        $cookie_name = 'aha_summary_metro_id';
    } else {
        // Not a match; don't continue.
        return;
    }

    // We need to know the user's affiliations
    $selected_metro_ids = cc_aha_get_array_user_metro_ids();
    $toggled = isset( $_COOKIE[$cookie_name] ) ? 'toggle-closed' : 'toggle-open';
    ?>
    <div class="toggleable <?php echo $toggled; ?> message info">
        <?php if ( $_COOKIE[$cookie_name] ) : ?>
            <span class="toggle-switch first" id="update-metro-id-toggle">You are currently viewing information for <?php echo cc_aha_get_metro_nicename( $_COOKIE[$cookie_name] ); ?>. &emsp;<a class="toggle-link" id="update-metro-id-toggle-link" href="#">Change</a>
            </span>
        <?php endif; ?>

        <div class="toggleable toggle-closed toggle-content">
            <label>Choose a board to view.
                <select name="cookie_<?php echo $cookie_name; ?>">
                <?php foreach ($selected_metro_ids as $metro_id) {
                    ?>
                    <option value="<?php echo $metro_id; ?>"><?php echo cc_aha_get_metro_nicename( $metro_id ); ?></option>
                    <?php
                } 
                ?>
                </select>
            </label>&emsp;<a class="nested-toggle-link" id="change-board-affiliations-toggle-link" href="#">Change or add board affiliations</a>

                <div class="toggle-content">
                    <?php cc_aha_metro_select_markup(); ?>
                </div>

            <div class="submit">
                <input id="submit-metro-id-cookie" type="submit" value="Save" name="submit_cookie_<?php echo $cookie_name; ?>">
            </div>
        </div>
    </div>
    <?php
}

/**
 * Builds the subnav of the AHA group tab
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_aha_render_tab_subnav(){
        ?>
        <div id="subnav" class="item-list-tabs no-ajax">
            <ul class="nav-tabs">
                <li <?php if ( cc_aha_on_main_screen() ) { echo 'class="current"'; } ?>>
                    <a href="<?php echo cc_aha_get_home_permalink(); ?>">Introduction</a>
                </li>
                <?php
                if ( cc_aha_user_can_do_assessment() ) :
                    ?>
                    <li <?php if ( cc_aha_on_survey_screen() ) { echo 'class="current"'; } ?>>
                        <a href="<?php echo cc_aha_get_survey_permalink(); ?>">Assessment</a>
                    </li>
                <?php endif; ?>
                <?php if ( cc_aha_user_has_super_secret_clearance() ) : ?>
                <li <?php if ( cc_aha_on_analysis_screen( 'health' ) ) { echo 'class="current"'; } ?>>
                    <a href="<?php echo cc_aha_get_analysis_permalink(); ?>">Health Analysis Report</a>
                </li>
                <?php endif; ?>
                <?php if ( cc_aha_user_has_super_secret_clearance() ) : ?>
                <li <?php if ( cc_aha_on_analysis_screen( 'revenue' ) ) { echo 'class="current"'; } ?>>
                    <a href="<?php echo cc_aha_get_analysis_permalink( 'revenue' ); ?>">Revenue Analysis Report</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
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

            if ( $count != 1 ){ 
                $retval .= ', ';
            }
            $retval .= cc_aha_get_metro_nicename( $metro_id );
            $count++;
        }
        return $retval;
    }