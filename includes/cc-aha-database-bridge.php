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
	$table_name = "wp_aha_assessment_board";
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
			FROM $wpdb->aha_assessment_board
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
 * Updates board database with answers from survey.
 *
 * @since    1.0.0
 * @param 	array
 * @return	
 */
function cc_aha_update_form_data( ){
	/*
		Hmm, things to think about:
		
		- we have a school table that will be updated differently than the board table:
			- parse POST requests?  
			
			
	*/
	
	//Mel isn't sure if this is necessary?
	if ( $_POST['metro_id'] != $_COOKIE['aha_active_metro_id'] ) return 0;
	
	/*First, let's deal with board table updating:
		$_POST['board'] holds key=>values of fields (with dots, not underscores, awesome!)
		[board] => Array
        (
            [2.2.2.1] => 1
            [2.2.2.2] => seom sthin  aethlkn 
            [2.2.4.1] => neither
        )
		
		$wpdb->update( $table, $data, $where, $format = null, $where_format = null );
		
	*/
	
	
	//take metro id (BOARD_ID in this table) and update fields for which values are supplied in form)
	global $wpdb;
	$board_id = $_COOKIE['aha_active_metro_id']; // 'BOARD_ID' column in wp_aha_assessment_board; our WHERE clause
	$board_table_name = $wpdb->aha_assessment_board;
	$where = array( 
		'BOARD_ID' => $board_id 
	);
	
	//we now have key => value pairs for $_POST['board']!
	$update_data = array();
	$update_data = $_POST['board'];
	
	//get values for wpdb->update statement..
	//$wpdb->update( $table, $data, $where, $format = null, $where_format = null );
	$num_rows_updated = $wpdb->update( $board_table_name, $update_data, $where, $format = null, $where_format = null );
	
	
	
	$towrite = PHP_EOL . '$_POST: ' . print_r($_POST, TRUE);
	//$towrite .=  print_r($board_values);
	$towrite .= 'db write success?: ' . $num_rows_updated;
	
	//$update_values = implode(' ', $update_values);
	//$towrite = sizeof($update_values);
	//$towrite .= $update_values;
	$fp = fopen("c:\\xampp\\logs\\aha_error_log.txt", 'a');
	fwrite($fp, $towrite);
	fclose($fp);
	
	return $num_rows_updated; //num rows on success, false on no success

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

