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
			WHERE BOARD_ID = %s
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
			- $_POST[board] will hold board key=>values
			- $_POST[district][#] will hold distrit key=>values ?
			
	*/
	
	//Mel isn't sure if this is necessary?
	if ( $_POST['metro_id'] != $_COOKIE['aha_active_metro_id'] ) return 0;
	

	//take metro id (BOARD_ID in this table) and update fields for which values are supplied in form)
	global $wpdb;
	
	$board_id = $_COOKIE['aha_active_metro_id']; // 'BOARD_ID' column in wp_aha_assessment_board; our WHERE clause
	$board_table_name = $wpdb->aha_assessment_board;
	$board_where = array( 
		'BOARD_ID' => $board_id 
	);
	
	//get have key => value pairs for $_POST['board']!
	$update_board_data = array();
	$update_board_data = $_POST['board'];
	
	//remove null values from update_board_data - will not go into database
	//Mel note: is this the correct handling of this data?  Don't overwrite on null form values?
	$update_board_data_notempty = array();
	$update_board_data_notempty = array_filter($update_board_data, "strlen");  //strlen as callback will remove false, empty and null but leave 0
	
	//get values for wpdb->update statement..
	if ( !empty ( $update_board_data_notempty ) ) {
		$num_board_rows_updated = $wpdb->update( $board_table_name, $update_board_data_notempty, $board_where, $format = null, $where_format = null );
	}
	
	//Now for school data
	$school_table_name = $wpdb->aha_assessment_school;
	
	//get key => value pairs for $_POST['school']!
	$update_school_data = array();
	$update_school_data = $_POST['school'];
	
	//foreach district in survey 
	foreach ( $update_school_data as $key => $value ){
		
		$district_id = $key;
		
		$school_where = array(
			'AHA_ID' => $board_id,
			'DIST_ID' => $district_id
		);
		
		$update_school_dist_data = $value;
		$update_school_dist_data_notempty = array_filter($update_school_dist_data, "strlen");
		
		$towrite .= $district_id; 
		$num_school_rows_updated = $wpdb->update( $school_table_name, $update_school_dist_data_notempty, $school_where, $format = null, $where_format = null );
	
	}
	
	$towrite .= PHP_EOL . '$_POST: ' . print_r($_POST, TRUE);
	$towrite .= 'db write success board: ' . $num_rows_updated;
	$towrite .= 'db write success school: ' . $num_school_rows_updated;
	
	
	//$towrite .= sizeof($update_board_data);
	//$towrite .= sizeof($update_board_data_notempty);
	//$towrite .= print_r($update_board_data_notempty, TRUE);
	$towrite .= print_r($update_school_dist_data_notempty, TRUE);
	//$towrite .= sizeof($update_board_data_notempty);
	$fp = fopen("c:\\xampp\\logs\\aha_error_log.txt", 'a');
	fwrite($fp, $towrite);
	fclose($fp);
	
	
	//will have to account for school data getting updated as well
	if ( $num_board_rows_updated === FALSE ) {
		return false;
	} else {
		return $num_board_rows_updated; //num rows on success (wpdb->update returns 0 if no data change), false on no success 
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
					'3.1.3.1.0' => 0, // Is there a school nutrition policy in place?
					),				
				1 => array(
					'id' => 4210710,
					'DIST_NAME' => 'GETTYSBURG AREA SD',
					'rank' => 2,
					'2.1.4.1.1' => 1, // Elementary school PE requirements
					'2.1.4.1.2' => 1, // Middle school PE requirements
					'2.1.4.1.3' => 0, // High school PE requirements
					'3.1.3.1.0' => 1, // Is there a school nutrition policy in place?
					'3.1.3.1.1' => 0,
					'3.1.3.1.2' => 1,
					'3.1.3.1.3' => 1,
					'3.1.3.1.4' => 'thingy.com/policy?id=90&duh=2',

					), 
				2 => array(
					'id' => 4206545,
					'name' => 'LITTLESTOWN AREA SD',
					'rank' => 3,
					'2.1.4.1.1' => 0, // Elementary school PE requirements
					'2.1.4.1.2' => 0, // Middle school PE requirements
					'2.1.4.1.3' => 0, // High school PE requirements
					'3.1.3.1.0' => 1, // Is there a school nutrition policy in place?
					'3.1.3.1.1' => 1,
					'3.1.3.1.2' => 0,
					'3.1.3.1.3' => 0,
					'3.1.3.1.4' => 'thingy.com/policy?id=90&duh=2',
					),
				3 => array(
					'id' => 4203450,
					'DIST_NAME' => 'BERMUDIAN SPRINGS SD',
					'rank' => 4,
					'2.1.4.1.1' => 0, // Elementary school PE requirements
					'2.1.4.1.2' => 0, // Middle school PE requirements
					'2.1.4.1.3' => 1, // High school PE requirements
					'3.1.3.1.0' => 0, // Is there a school nutrition policy in place?
					), 
				4 => array(
					'id' => 4224300,
					'DIST_NAME' => 'UPPER ADAMS SD',
					'rank' => 5,
					'2.1.4.1.1' => 0, // Elementary school PE requirements
					'2.1.4.1.2' => 0, // Middle school PE requirements
					'2.1.4.1.3' => 0, // High school PE requirements
					'3.1.3.1.0' => 1, // Is there a school nutrition policy in place?
					'3.1.3.1.1' => 1,
					'3.1.3.1.2' => 0,
					'3.1.3.1.3' => 1,
					'3.1.3.1.4' => 'thingy.com/policy?id=90&duh=2',
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

