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
 * Returns array of questions based on page number (not updated)
 *
 * @since    1.0.0
 * @return 	array
 */
function cc_aha_get_questions( $metro_id, $page = 1 ){
	global $wpdb;
	$question_sql = 
		"
		SELECT * 
		FROM $wpdb->aha_assessment_questions
		WHERE page_number = $page
		";
		
	$form_rows = $wpdb->get_results( $question_sql, OBJECT );
	return $form_rows;

}

/**
 * Returns array of saved form data by metro id for the page being built.
 *
 * @since    1.0.0
 * @return 	array
 */
function cc_aha_get_form_data( $metro_id, $page = 1 ){

	 global $wpdb;
	 
	 //so we will return some data for the moment
	$table_name = "wp_aha_assessment_school_NOTNOW";
	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'" ) != $table_name) {
		return array( 	
				'1.2.1.1' => 1,
				'1.2.1.2' => 0, 
				'1.2.1.3' => 'Saved text box stuff',
				'1.2.2.1' => '23.45',
				'2.1.1.1' => 1,
				'2.1.1.2' => 1,
				'2.1.1.3' => 0,
				);
	} else {
		$form_rows = $wpdb->get_results( 
			$wpdb->prepare( 
			"
			SELECT * 
			FROM $wpdb->aha_assessment_school
			WHERE AHA_ID = %s
			",
			$metro_id )
			, ARRAY_A
		);
		//print_r( $form_rows );
		return $form_rows;
	}
}

/**
 * Returns array of arrays of school district data by metro id.
 *
 * @since    1.0.0
 * @return 	array of arrays
 */
function cc_aha_get_school_data( $metro_id ){
	global $wpdb;
	
	//so we will return some data for the moment
	$table_name = "wp_aha_assessment_school";
	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'" ) != $table_name) {
		return array( 	
				0 => array(
					'id' => 4206550,
					'DIST_NAME' => 'CONEWAGO VALLEY SD',
					'rank' => 1,
					'2.1.4.1.1' => 0, // Elementary school PE requirements
					'2.1.4.1.2' => 1, // Middle school PE requirements
					'2.1.4.1.3' => 0, // High school PE requirements
					),				
				1 => array(
					'id' => 4210710,
					'DIST_NAME' => 'GETTYSBURG AREA SD',
					'rank' => 2,
					'2.1.4.1.1' => 1, // Elementary school PE requirements
					'2.1.4.1.2' => 1, // Middle school PE requirements
					'2.1.4.1.3' => 0, // High school PE requirements
					), 
				2 => array(
					'id' => 4206550,
					'DIST_NAME' => 'LITTLESTOWN AREA SD',
					'rank' => 3,
					'2.1.4.1.1' => 0, // Elementary school PE requirements
					'2.1.4.1.2' => 0, // Middle school PE requirements
					'2.1.4.1.3' => 0, // High school PE requirements
					),
				3 => array(
					'id' => 4203450,
					'DIST_NAME' => 'BERMUDIAN SPRINGS SD',
					'rank' => 4,
					'2.1.4.1.1' => 0, // Elementary school PE requirements
					'2.1.4.1.2' => 0, // Middle school PE requirements
					'2.1.4.1.3' => 1, // High school PE requirements
					), 
				4 => array(
					'id' => 4224300,
					'DIST_NAME' => 'UPPER ADAMS SD',
					'rank' => 5,
					'2.1.4.1.1' => 0, // Elementary school PE requirements
					'2.1.4.1.2' => 0, // Middle school PE requirements
					'2.1.4.1.3' => 0, // High school PE requirements
					),
				);
	} else {
		$form_rows = $wpdb->get_results( 
			$wpdb->prepare( 
			"
			SELECT * 
			FROM $wpdb->aha_assessment_school
			WHERE AHA_ID = %s
			",
			$metro_id )
			, ARRAY_A
		);
		//print_r( $form_rows );
		return $form_rows;
	}
}

