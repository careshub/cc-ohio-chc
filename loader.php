<?php
/*
Plugin Name: CC American Heart Association Extras
Description: Adds extras to the AHA group space
Version: 1.0
*/
/**
 * CC American Heart Association Extras
 *
 * @package   CC American Heart Association Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2014 CommmunityCommons.org
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

function cc_aha_extras_class_init(){
	// Get the class fired up
	// Helper and utility functions
	require_once( dirname( __FILE__ ) . '/includes/cc-aha-functions.php' );
	// Template-y functions
	require_once( dirname( __FILE__ ) . '/includes/cc-aha-template-tags.php' );
	// Template functions for the form
	require_once( dirname( __FILE__ ) . '/includes/cc-aha-survey-template-tags.php' );
	// Template functions for the summary pages
	require_once( dirname( __FILE__ ) . '/includes/cc-aha-summary-template-tags.php' );
	require_once( dirname( __FILE__ ) . '/includes/cc-aha-summary-template-tags-2.php' );
	require_once( dirname( __FILE__ ) . '/includes/cc-aha-summary-template-tags-revenue.php' );
	//Template functions for the Summary Report Card, Revenue Report Card
	require_once( dirname( __FILE__ ) . '/includes/cc-aha-report-card-template-tags.php' );
	require_once( dirname( __FILE__ ) . '/includes/cc-aha-all-revenue-report-card-template-tags.php' );

	// Database helper functions
	require_once( dirname( __FILE__ ) . '/includes/cc-aha-database-bridge.php' );
	// The main class
	require_once( dirname( __FILE__ ) . '/includes/cc-aha-extras.php' );
	add_action( 'bp_include', array( 'CC_AHA_Extras', 'get_instance' ), 21 );
	
	//Mel...is this overkill
	global $wpdb;
	
	//Read only tables 
    $wpdb->aha_assessment_questions = $wpdb->prefix . 'aha_assessment_questions';
    $wpdb->aha_assessment_q_options = $wpdb->prefix . 'aha_assessment_q_options';
    $wpdb->aha_assessment_school = $wpdb->prefix . 'aha_assessment_school';
    $wpdb->aha_assessment_board = $wpdb->prefix . 'aha_assessment_board';
    $wpdb->aha_assessment_counties = $wpdb->prefix . 'aha_assessment_counties';
    $wpdb->aha_assessment_complete_streets = $wpdb->prefix . 'aha_assessment_complete_streets';
    $wpdb->aha_assessment_hospitals = $wpdb->prefix . 'aha_assessment_hospitals';
	
}
add_action( 'bp_include', 'cc_aha_extras_class_init' );

/* Only load the component if BuddyPress is loaded and initialized. */
function startup_aha_extras_group_extension_class() {
	require( dirname( __FILE__ ) . '/includes/class-bp-group-extension.php' );
}
add_action( 'bp_include', 'startup_aha_extras_group_extension_class', 24 );