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
 * Returns array of saved form data by metro id for the page being built.
 *
 * @since    1.0.0
 * @return 	array
 */
function cc_aha_get_form_data( $metro_id, $page = 1 ){
	// This is sample data. Will ultimately come from the db.
	return array( 	
				'1.2.1.1' => 1,
				'1.2.1.2' => 0, 
				'1.2.1.3' => 'Saved text box stuff',
				'1.2.2.1' => '23.45' 
				);

}

/**
 * Returns array of arrays of school district data by metro id.
 *
 * @since    1.0.0
 * @return 	array of arrays
 */
function cc_aha_get_school_data( $metro_id ){
	// sample return array
	return array( 	
				0 => array(
					'id' => 4206550,
					'name' => 'CONEWAGO VALLEY SD',
					'rank' => 1,
					'2.1.4.1.1' => 0, // Elementary school PE requirements
					'2.1.4.1.2' => 1, // Middle school PE requirements
					'2.1.4.1.3' => 0, // High school PE requirements
					),				
				1 => array(
					'id' => 4210710,
					'name' => 'GETTYSBURG AREA SD',
					'rank' => 2,
					'2.1.4.1.1' => 1, // Elementary school PE requirements
					'2.1.4.1.2' => 1, // Middle school PE requirements
					'2.1.4.1.3' => 0, // High school PE requirements
					), 
				2 => array(
					'id' => 4206550,
					'name' => 'LITTLESTOWN AREA SD',
					'rank' => 3,
					'2.1.4.1.1' => 0, // Elementary school PE requirements
					'2.1.4.1.2' => 0, // Middle school PE requirements
					'2.1.4.1.3' => 0, // High school PE requirements
					),
				3 => array(
					'id' => 4203450,
					'name' => 'BERMUDIAN SPRINGS SD',
					'rank' => 4,
					'2.1.4.1.1' => 0, // Elementary school PE requirements
					'2.1.4.1.2' => 0, // Middle school PE requirements
					'2.1.4.1.3' => 1, // High school PE requirements
					), 
				4 => array(
					'id' => 4224300,
					'name' => 'UPPER ADAMS SD',
					'rank' => 5,
					'2.1.4.1.1' => 0, // Elementary school PE requirements
					'2.1.4.1.2' => 0, // Middle school PE requirements
					'2.1.4.1.3' => 0, // High school PE requirements
					),
				);
}