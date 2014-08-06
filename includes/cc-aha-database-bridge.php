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
	 
	//get board data from database
	//$table_name = "wp_aha_assessment_board";
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
	
	// get_results returns a multi-dimensional array, we just want the first array
	return current( $form_rows );
}

/**
 * Updates board and school database tables with answers from survey.
 *
 * Takes $_POST arrays of [board] and [school] on form submit,
 *	makes sure their values aren't null, false or empty (so we don't overwrite values
 *	that weren't set in the survey) and wpdb->update s the respective table 
 *
 * @since    1.0.0
 * @param 	array
 * @return	
 */
function cc_aha_update_form_data( ){
	
	// $towrite = PHP_EOL . '$_POST: ' . print_r( $_POST, TRUE);
	// $fp = fopen('aha_form_save.txt', 'a');
	// fwrite($fp, $towrite);
	// fclose($fp);

	//Mel isn't sure if this is necessary?
	if ( $_POST['metro_id'] != $_COOKIE['aha_active_metro_id'] ) return 0;

	global $wpdb;
	
	//get our board vars for the wpdb->update statement
	$board_id = $_COOKIE['aha_active_metro_id']; // 'BOARD_ID' column in wp_aha_assessment_board; our WHERE clause
	$board_table_name = $wpdb->aha_assessment_board;
	$board_where = array(
		'BOARD_ID' => $board_id 
	);
	
	//get have key => value pairs for $_POST['board']!
	$update_board_data = array();
	$update_board_data = $_POST['board'];
	$numeric_inputs = cc_aha_get_number_type_questions();

	// Input data cleaning
	foreach ($update_board_data as $key => $value) {
		// Serialize data if necessary
		if ( is_array( $value ) )
			$update_board_data[ $key ] = maybe_serialize( $value );

		// Strip dollar signs and percent signs from numeric entries
		if ( in_array( $key, $numeric_inputs ) )
			$update_board_data[ $key ] = str_replace( array( '$', '%'), '', $value);
	}

	//TODO: If form fields are disabled (via jQ) then they are not included in the $_POST array, so they're not updated. We could:
	// 1) Do some $_POST data checking, based on what questions should be on the page, and provide empty strings for those which are not represented (more specifically, we could get the qids of text and textarea inputs for a given page and make sure those are represented in the $_POST data)
	// 2) Do some jQ gyrations on submit that find disabled form fields, enable them and provide empty values
	// 3) Instead of disabling, empty the field when it's hidden. (This might be kind of irritating if you're the type of form-filler-outer who changes her mind or makes accidental clicks.)
	// 4) Do a data scrub on the final db before handing it over.
	
	//if we have [board] values set by the form, update the table
	// wpdb->update is perfect for this. Wow. Ref: https://codex.wordpress.org/Class_Reference/wpdb#UPDATE_rows
	if ( !empty ( $update_board_data ) ) {
		$num_board_rows_updated = $wpdb->update( $board_table_name, $update_board_data, $board_where, $format = null, $where_format = null );
	}
	
	//get our school vars for the wpdb->update statement
	$school_table_name = $wpdb->aha_assessment_school;
	
	//get key => value pairs for $_POST['school']!
	$update_school_data = array();
	$update_school_data = $_POST['school'];

	//foreach district in survey, update db
	foreach ( $update_school_data as $key => $value ){
		
		$district_id = $key;
		
		//set where clause with this district and board
		$school_where = array(
			'AHA_ID' => $board_id,
			'DIST_ID' => $district_id
		);
		
		//the array in value is the district-specific data
		$update_school_dist_data = $value;

		// Input data cleaning - WPDB does the heavy lifting 
		foreach ($update_school_dist_data as $key => $value) {
			// Serialize data if necessary
			if ( is_array( $value ) )
				$update_school_dist_data[ $key ] = maybe_serialize( $value );

			// Strip dollar signs and percent signs from numeric entries
			if ( in_array( $key, $numeric_inputs ) )
				$update_school_dist_data[ $key ] = str_replace( array( '$', '%'), '', $value);
		}

		//update the table for this district
		$num_school_rows_updated = $wpdb->update( $school_table_name, $update_school_dist_data, $school_where, $format = null, $where_format = null );
	
	}
	
	$towrite .= PHP_EOL . '$_POST: ' . print_r($_POST, TRUE);
	$towrite .= 'db write success board: ' . $num_rows_updated;
	$towrite .= 'db write success school: ' . $num_school_rows_updated;
	
	
	//$towrite .= sizeof($update_board_data);
	//$towrite .= sizeof($update_board_data_notempty);
	//$towrite .= print_r($update_board_data_notempty, TRUE);
	$towrite .= print_r($update_board_data, TRUE);
	//$towrite .= sizeof($update_board_data_notempty);
	$fp = fopen("c:\\xampp\\logs\\aha_log.txt", 'a');
	fwrite($fp, $towrite);
	fclose($fp);
	
	
	//will have to account for school data getting updated as well
	if ( $num_board_rows_updated === FALSE || $num_school_rows_updated === FALSE  ) {
		return false; //we have a problem updating
	} else {
		return ( $num_board_rows_updated ); //num rows on success (wpdb->update returns 0 if no data change), FALSE on no success 
	}

}


/**
 * Returns array of arrays of questions to build for the requested page of the form.
 *
 * @since    1.0.0
 * @return 	array of arrays
 */
function cc_aha_get_form_questions( $page = 1 ){
	global $wpdb;
	
	$questions = $wpdb->get_results( 
		$wpdb->prepare( 
		"
		SELECT * 
		FROM $wpdb->aha_assessment_questions
		WHERE page = %d
		",
		$page )
		, ARRAY_A
	);

	return $questions;
}

/**
 * Returns single question info.
 *
 * @since    1.0.0
 * @return 	array 
 */
function cc_aha_get_question( $qid ){
	global $wpdb;
	
	$question = $wpdb->get_results( 
		$wpdb->prepare( 
		"
		SELECT * 
		FROM $wpdb->aha_assessment_questions
		WHERE QID = %s
		",
		$qid )
		, ARRAY_A
	);

	return current( $question );
}

/**
 * Returns array of arrays of questions to build for the requested page of the form.
 *
 * @since    1.0.0
 * @return 	array of arrays
 */
function cc_aha_get_options_for_question( $qid ){
	global $wpdb;
	
	$options = $wpdb->get_results( 
		$wpdb->prepare( 
		"
		SELECT * 
		FROM $wpdb->aha_assessment_q_options
		WHERE qid = %s
		",
		$qid )
		, ARRAY_A
	);

	return $options;

}

/**
 * Returns array of arrays of followup questions.
 *
 * @since    1.0.0
 * @return 	array of arrays
 */
function cc_aha_get_follow_up_questions( $qid ){
	global $wpdb;
	
	$questions = $wpdb->get_results( 
		$wpdb->prepare( 
		"
		SELECT * 
		FROM $wpdb->aha_assessment_questions
		WHERE follows_up = %s
		",
		$qid )
		, ARRAY_A
	);

	return $questions;
}

/**
 * Get question IDs of type=number questions.
 * Used for data validation
 *
 * @since    1.0.0
 * @return 	array of question IDs
 */
function cc_aha_get_number_type_questions(){
	global $wpdb;
	
	$questions = $wpdb->get_col( 
		"
		SELECT * 
		FROM $wpdb->aha_assessment_questions
		WHERE type = 'number'
		"
		, 2
	);

	return $questions;

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
	//$table_name = "wp_aha_assessment_school";
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

/**
 * Fetch array of arrays of all Metro IDs and names only
 * 
 * @since   1.0.0
 * @return  array of arrays
 */
function cc_aha_get_metro_id_array(){
	global $wpdb;

	$metros = $wpdb->get_results( 
		"
		SELECT BOARD_ID, Board_Name
		FROM $wpdb->aha_assessment_board
		", ARRAY_A
	);
	
	return $metros;
}

/**
 * Returns info array for single Metro ID.
 *
 * @since   1.0.0
 * @return 	array
 */
function cc_aha_get_single_metro_data( $metro_id ){
	global $wpdb;
	 
	$metro = $wpdb->get_results( 
		$wpdb->prepare( 
		"
		SELECT BOARD_ID, Board_Name, State, State2, Affiliate, Nearest_MSA
		FROM $wpdb->aha_assessment_board
		WHERE BOARD_ID = %s
		",
		$metro_id )
		, ARRAY_A
	);
	
	// We want to return a single result, so the first will do.
	return current( $metro );
}