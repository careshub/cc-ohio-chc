<?php
/*
Plugin Name: CC Creating Healthy Communities Ohio Extras
Description: Adds extras to the Ohio CHC group space
Version: 1.0
*/
/**
 * CC Creating Healthy Communities Ohio Extras
 *
 * @package   CC Creating Healthy Communities Ohio Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2015 CommmunityCommons.org
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

function cc_ohio_chc_extras_class_init(){
	// Get the class fired up
	// Helper and utility functions
	require_once( dirname( __FILE__ ) . '/includes/cc-ohio-chc-functions.php' );
	require_once( dirname( __FILE__ ) . '/includes/cc-ohio-chc-gf-functions.php' );
	// Template-y functions
	require_once( dirname( __FILE__ ) . '/includes/cc-ohio-chc-template-tags.php' );
	
	// The main class
	require_once( dirname( __FILE__ ) . '/includes/cc-ohio-chc-extras.php' );
	add_action( 'bp_include', array( 'CC_Ohio_CHC_Extras', 'get_instance' ), 21 );
		
}
add_action( 'bp_include', 'cc_ohio_chc_extras_class_init' );

/* Only load the component if BuddyPress is loaded and initialized. */
function startup_ohioh_chc_extras_group_extension_class() {
	require( dirname( __FILE__ ) . '/includes/class-bp-group-extension.php' );
}
add_action( 'bp_include', 'startup_ohioh_chc_extras_group_extension_class', 24 );