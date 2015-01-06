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

	// Grab the first array only
	$row = current( $form_rows );

	// Process the data to remove escape characters and return
	return array_map( 'stripslashes', $row );
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
 * Returns array of all saved board data. 
 *
 * @since    1.0.0
 * @return 	array
 */
function cc_aha_get_all_board_data( ){

	global $wpdb;
	 
	//get board data from database
	//$table_name = "wp_aha_assessment_board";
	$form_rows = $wpdb->get_results( 
		$wpdb->prepare( 
		"
		SELECT * 
		FROM $wpdb->aha_assessment_board
		",
		$metro_id )
		, ARRAY_A
	);

	return $form_rows;
}

/**
 * Returns array of all states in board table. 
 *
 * @since    1.0.0
 * @return 	array
 */
function cc_aha_get_unique_board_states( ){

	global $wpdb;
	
	$form_col = $wpdb->get_col( 
		$wpdb->prepare( 
		"
		SELECT State
		FROM $wpdb->aha_assessment_board
		",
		$metro_id )
	);

	$form_col = array_unique( $form_col );
	sort( $form_col );
	return $form_col;
}

/**
 * Returns array of all affiliates in board table. 
 *
 * @since    1.0.0
 * @return 	array
 */
function cc_aha_get_unique_board_affiliates( ){

	global $wpdb;
	
	$form_col = $wpdb->get_col( 
		$wpdb->prepare( 
		"
		SELECT Affiliate
		FROM $wpdb->aha_assessment_board
		",
		$metro_id )
	);

	$form_col = array_unique( $form_col );
	sort( $form_col );
	return $form_col;
}

/**
 * Returns array of school district data by qid and metro id. - is this too specific?
 *
 * @since   1.0.0
 * @return 	associative array $summary_responses Array of [ district name ] [ summary-labelled answer ]
 */
function cc_aha_get_assessment_school_results( $metro_id, $qid ){
	global $wpdb;
	
	//gosh, this is one ugly sql function, so let's split it up
	
	//get dist_name, qid (it's columns, grr) and values from school table
	$dist_data = cc_aha_get_school_data( $metro_id );
	//print_r( $dist_data );
	
	//get summary_label, qid, value from options table
	// could foreach this (or what is more clever...) if >1 $qid
	$options_data = $wpdb->get_results( 
		$wpdb->prepare( 
		"
		SELECT value, summary_value, summary_label 
		FROM $wpdb->aha_assessment_q_options
		WHERE qid = %s
		",
		$qid )
		, ARRAY_A
	);
	
	print_r( $options_data );
	
	//return $form_rows;
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
function cc_aha_update_form_data( $board_id = null ){
	
	// $towrite = PHP_EOL . '$_POST: ' . print_r( $_POST, TRUE);
	// $fp = fopen("c:\\xampp\\logs\\aha_log.txt", 'a');
	// fwrite($fp, $towrite);
	// fclose($fp);

	global $wpdb;
		
	//get our board vars for the wpdb->update statement
	// If we haven't supplied a board ID, use the cookie setting
	//TODO: Check saving summary responses with two diff cookie vals.
	if ( ! $board_id )
		$board_id = $_COOKIE['aha_active_metro_id']; // 'BOARD_ID' column in wp_aha_assessment_board; our WHERE clause
	$board_table_name = $wpdb->aha_assessment_board;
	$board_where = array(
		'BOARD_ID' => $board_id 
	);
	
	//get key => value pairs for $_POST['board']!
	$update_board_data = array();
	$update_board_data = $_POST['board'];
	$numeric_inputs = cc_aha_get_number_type_questions();
	
	$followup_question = array();
	 
	// Input data cleaning
	foreach ($update_board_data as $key => $value) {
		
		//just triple-checking to make sure we won't clear out OTHER followup values of non-displayed questions..
		// Serialize data if necessary
		if ( is_array( $value ) )
			$update_board_data[ $key ] = maybe_serialize( $value );

		// Strip dollar signs and percent signs from numeric entries
		if ( in_array( $key, $numeric_inputs ) )
			$update_board_data[ $key ] = str_replace( array( '$', '%', ','), '', $value);
		
		//Empty disabled form fields in the db (or those that ARE followups whose followee question option != followup_id of $this)
		
		//Get followup questions for this question (TODO: make sure this assumption-of-one for board is valid)
		$followup_question = current( cc_aha_get_follow_up_questions( $key ) ); //we'll only ever have one, yes?  Or is this not safe enough?
		
		$followup_question_id =  $followup_question[ 'QID' ];		
		
		//if we have a followup question, let's see if it's value is $_POSTed and, if not, set it to update to NULL in the db
		// if the input has disabled attribute, it won't submit
		if ( !empty( $followup_question_id ) ) {
			//get the value of the followup question
			$followup_question_value = $update_board_data[ $followup_question_id ];
			
			//if there IS no value to a followup question, it's been disabled
			if ( empty( $followup_question_value ) && ( $followup_question_value != '0' ) ) { //because 0 means empty to PHP
			
				//update the value in the db to NULL
				$update_board_data[ $followup_question_id ] = NULL;
			}
		}
	}
	 
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
	
	$followup_questions = array();
	$nested_followup_questions = array();
	
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
				$update_school_dist_data[ $key ] = str_replace( array( '$', '%', ','), '', $value);
				
				
			/** Set currently-disabled form fields to NULL in the db update 
			 **(or those that ARE followups whose followee question option != followup_id of them.  Niner.) **/
		
			//Get followup questions for this question - in school, there are multiple.  Should rollout to board if necessary
			$followup_questions =  cc_aha_get_follow_up_questions( $key ); 
			
			foreach( $followup_questions as $followup_question ) {
				//Get the followup question id
				$followup_question_id =  $followup_question[ 'QID' ];
				
				//if we have a followup question, let's see if it's value is $_POSTed and, if not, set it to update to NULL in the db
				// if the input has disabled attribute, it won't submit
				if ( !empty( $followup_question_id ) ) {
					//get the value of the followup question
					$followup_question_value = $update_school_dist_data[ $followup_question_id ];
					//$towrite .= 'not empty id: ' . print_r($followup_question_id, TRUE) . ', value: ' . $followup_question_value;
					
					//if there IS no value to a followup question, it's been disabled
					if ( empty( $followup_question_value ) && ( $followup_question_value != '0' ) ) {
						//update the value in the db to NULL
						$update_school_dist_data[ $followup_question_id ] = NULL;
					}
				}
				
				//Do we have nested followup questions?
				$nested_followup_questions = cc_aha_get_follow_up_questions ( $followup_question_id );
				
				//disabled is not being properly set on some of these...maybe just hard-code for now, since it's one case
				
				foreach( $nested_followup_questions as $nested_followup_question ) {
					
					$nested_followup_question_id =  $nested_followup_question[ 'QID' ];
					//$towrite .= 'Nested followup question id: ' . print_r( $nested_followup_question_id, TRUE ) . "\r\n";
					//$towrite .= 'Nested followup question: ' . print_r( $nested_followup_question, TRUE ) . "\r\n";
					
					//if we have a followup question, let's see if it's value is $_POSTed and, if not, set it to update to NULL in the db
					// if the input has disabled attribute, it won't submit
					if ( !empty( $nested_followup_question_id ) ) {
						//get the value of the followup question
						$nested_followup_question_value = $update_school_dist_data[ $nested_followup_question_id ];
						//$towrite .= 'not empty id: ' . print_r($nested_followup_question_id, TRUE) . ', value: ' . $nested_followup_question_value;
						
						//if there IS no value to a followup question, it's been disabled
						if ( empty( $nested_followup_question_value ) && ( $nested_followup_question_value != '0' ) ) {
							//update the value in the db to NULL
							//$towrite .= '   IS EMPTY' . "\r\n";
							$update_school_dist_data[ $nested_followup_question_id ] = NULL;
						}
					}
				}
								
			}

		}

		//update the table for this district
		$num_school_rows_updated = $wpdb->update( $school_table_name, $update_school_dist_data, $school_where, $format = null, $where_format = null );
	
	}

	
	//$towrite .= print_r($update_board_data, TRUE);
	//$fp = fopen("c:\\xampp\\logs\\aha_log.txt", 'a');
	//fwrite($fp, $towrite);
	//fclose($fp);

	//wpdb->update returns num rows on success, 0 if no data change, FALSE on error
	//either wpdb->update return error?
	if ( $num_board_rows_updated === FALSE || $num_school_rows_updated === FALSE  ) {
		return false; //we have a problem updating
	} else {
		return ( $num_board_rows_updated ); 
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
 * Returns array of arrays of all questions that should appear on form.
 * Questions with a page of 0 shouldn't appear on the form
 *
 * @since    1.0.0
 * @return 	array of arrays
 */
function cc_aha_get_all_form_questions(){
	global $wpdb;
	
	$questions = $wpdb->get_results( 
		"
		SELECT * 
		FROM $wpdb->aha_assessment_questions
		WHERE page != 0
		",
		ARRAY_A
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
 * Fetch array of arrays of all Metro IDs and names only
 * 
 * @since   1.0.0
 * @return  array of arrays
 */
function cc_aha_get_metro_id_array(){
	global $wpdb;

	$metros = $wpdb->get_results( 
		"
		SELECT BOARD_ID, Board_Name, Affiliate
		FROM $wpdb->aha_assessment_board
		ORDER BY BOARD_ID
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

/**
 * Returns all the counties sharing a metro ID.
 *
 * @since   1.0.0
 * @return 	array of arrays
 */
function cc_aha_get_county_data( $metro_id ){
	global $wpdb;
	 
	$counties = $wpdb->get_results( 
		$wpdb->prepare( 
		"
		SELECT *
		FROM $wpdb->aha_assessment_counties
		WHERE board_id = %s
		",
		$metro_id )
		, ARRAY_A
	);
	
	return $counties;
}

/**
 * Returns all the complete streets entries for a metro ID.
 *
 * @since   1.0.0
 * @return 	array
 */
function cc_aha_get_complete_streets_data( $metro_id ){
	global $wpdb;
	 
	$results = $wpdb->get_results( 
		$wpdb->prepare( 
		"
		SELECT *
		FROM $wpdb->aha_assessment_complete_streets
		WHERE board_id = %s
		",
		$metro_id )
		, ARRAY_A
	);
	
	return $results;
}

/**
 * Returns all the hospital entries for a metro ID.
 *
 * @since   1.0.0
 * @return 	array
 */
function cc_aha_get_hospital_data( $metro_id ){
	global $wpdb;
	 
	$results = $wpdb->get_results( 
		$wpdb->prepare( 
		"
		SELECT *
		FROM $wpdb->aha_assessment_hospitals
		WHERE `AHA Board Territory` = %s
		",
		$metro_id )
		, ARRAY_A
	);
	
	return $results;
}

/**
 * Retrieve all of the questions that should appear in an analysis criterion.
 *
 * @since   1.0.0
 * @return 	array of arrays
 */
function cc_aha_get_questions_for_summary_criterion( $criterion = null ){
	global $wpdb;
	
	$questions = $wpdb->get_results( 
		$wpdb->prepare( 
		"
		SELECT * 
		FROM $wpdb->aha_assessment_questions
		WHERE summary_section = %s
		ORDER BY QID
		",
		$criterion )
		, ARRAY_A
	);

	return $questions;
}

/*
 * Get priorities based on metro id
 *
 * @param int
 * @returns array
 */
function cc_aha_get_priorities_by_board( $metro_id ){
	//TODO: consider extending this function to ask for return type
	//search aha-priority cpt by aha-board taxonomy taxonomy
	$args = array(
		'post_type' => 'aha-priority',
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'aha-board-term',
				'field'    => 'name',
				'terms'    => $metro_id
			)
		),
	);

	//var_dump( $args);
	$priority_query = new WP_Query( $args );
	//array to hold ids of priorities
	$priority_array = array();
	
	if ( $priority_query->have_posts() ) {
	
		while ( $priority_query->have_posts() ) {
			$priority_query->the_post();
			array_push( $priority_array, get_the_ID() );
		}
	} else {
		// no posts found
	}

	return $priority_array;

}

/*
 * Get priorities based on metro id, date and (opt)return data
 *
 * @param int, int, string
 * @returns array
 */
function cc_aha_get_priorities_by_board_date( $metro_id, $date, $return_criteria = null ){
	
	//search aha-priority cpt by aha-board taxonomy taxonomy
	$args = array(
		'post_type' => 'aha-priority',
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'aha-board-term',
				'field'    => 'name',
				'terms'    => $metro_id
			),
			array(
				'taxonomy' => 'aha-benchmark-date-term',
				'field'    => 'name',
				'terms'    => $date
			),
		),
	);

	//var_dump( $args);
	$priority_query = new WP_Query( $args );
	//array to hold ids of priorities
	$priority_array = array();
	
	if ( $priority_query->have_posts() ) {
	
		while ( $priority_query->have_posts() ) {
			$priority_query->the_post();
			if ( $return_criteria != null ){
				//associative array with ['criteria_name'] = ID
				$criteria_name = wp_get_post_terms( get_the_ID(), 'aha-criteria-term' );
				//var_dump( current( $criteria_name )->name );
				$priority_array[ current( $criteria_name )->name ] = get_the_ID();
			} else {
				array_push( $priority_array, get_the_ID() );
			}
		}
	} else {
		// no posts found
	}

	return $priority_array;

}

/*
 * Get priorities based on metro id, date, criterion
 *
 * @param int
 * @returns array
 */
function cc_aha_get_priorities_by_board_date_criterion( $metro_id, $date, $criterion ){
	//TODO: consider extending this function to ask for return type
	//search aha-priority cpt by aha-board taxonomy taxonomy
	$args = array(
		'post_type' => 'aha-priority',
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'aha-board-term',
				'field'    => 'name',
				'terms'    => $metro_id
			),
			array(
				'taxonomy' => 'aha-benchmark-date-term',
				'field'    => 'name',
				'terms'    => $date
			),
			array(
				'taxonomy' => 'aha-criteria-term',
				'field'    => 'name',
				'terms'    => $criterion
			)
		),
	);

	//var_dump( $args);
	$priority_query = new WP_Query( $args );
	//array to hold ids of priorities
	$priority_array = array();
	
	if ( $priority_query->have_posts() ) {
	
		while ( $priority_query->have_posts() ) {
			$priority_query->the_post();
			array_push( $priority_array, get_the_ID() );
		}
	} else {
		// no posts found
	}

	return $priority_array;

}
/**
 * Updates/Adds board priorities
 *
 * Takes $_POST array of priority-specific data from health/revenue summary,
 *	makes sure that priority of same board and criteria and date doesn't already exist...somehow
 *
 * @since    1.0.0
 * @param 	string, int, string
 * @return	
 */
function cc_aha_update_priority( $metro_id, $date, $criteria ){
	
	global $wpdb;
	$current_user = wp_get_current_user();
	
	//Make sure requisite $_POST variables exist
	/*
	$metro_id = $priority_data['metro_id'];
	$date = $priority_data['date'];
	$criteria = $priority_data['criteria_name'];
	*/
	
	//Check to see if priority (of this board, criterion and date) exists
	$priorities = cc_aha_get_priorities_by_board_date_criterion( $metro_id, $date, $criteria );
	//var_dump( $metro_id );
	//var_dump( $date );
	//var_dump( $criteria );
	
	//if it returns more than 1 (it really shouldn't!), take current only
	$priority_id = current( $priorities );
	
	//If exists, update data; if not, add
	if ( empty( $priority_id ) ){
	
		//create new priority!
		$post_args = array(
			'post_title'    => $metro_id . '-' . $criteria . '-' . $date,
			'post_status'   => 'publish',
			'post_type'		=> 'aha-priority',
			'post_author'   => $current_user->ID
		);
		$post_id = wp_insert_post( $post_args, $wp_error );
		
		$affiliate_state_array = current( cc_aha_get_affiliate_state_by_board( $metro_id ));
		//var_dump ( current ( $affiliate_state_array['Affiliate'] ));
	
		
		if( $post_id > 0 ){  
			//add taxonomy to new priority
			$error = wp_set_object_terms( $post_id, $metro_id, 'aha-board-term' );
			//var_dump( $error );
			$error = wp_set_object_terms( $post_id, $date, 'aha-benchmark-date-term' );
			//var_dump( $error );
			$error = wp_set_object_terms( $post_id, $criteria, 'aha-criteria-term' );
			//var_dump( $error );
			$error = wp_set_object_terms( $post_id, $affiliate_state_array["Affiliate"], 'aha-affiliate-term' );
			//var_dump( $error );
			$error = wp_set_object_terms( $post_id, $affiliate_state_array["State"], 'aha-state-term' );
			//var_dump( $error );
		
			//TODO: set affiliate and state, based on 'board' table
			
			echo $post_id; //send new priority id back to server
		} else {
			echo '0';
		}
	} else {
		//update priority of id
		
		//echo 'yes, priorities: ' . $priority_id;
	}
	//var_dump( $priorities );
	//echo 'hello';
	//die();
	
}

/* Sets the staff lead and volunteer champion for a priority
 *
 * @params int PriorityID, string, string
 * @returns
 */
function cc_aha_set_staff_for_priorities( $priority_id, $staff_partner, $volunteer ){
	
	global $wpdb;
	//$current_user = wp_get_current_user();
	
	//Make sure requisite $_POST variables exist
	if( $priority_id <= 0 || $priority_id == false ){
		return;
	}
	
	//both return false if value is the same as in db...SUPER helpful
	$staff_success = update_post_meta( $priority_id, "staff_partner", $staff_partner );
	$volunteer_success = update_post_meta( $priority_id, "volunteer_lead", $volunteer );
	
	
	if( $staff_success == false || $volunteer_success == false ){
		echo 'error on saving staff'; //or...no change
		die();
	} else {
	
		echo 'staff saving..';
		die();
	}


}

/*
 * Gets the affiliate and state of a particular board
 *
 * @param string $metro_id
 * @return array
 */
 function cc_aha_get_affiliate_state_by_board( $metro_id ){
	
	global $wpdb;
	 
	//get board data from database
	//$table_name = "wp_aha_assessment_board";
	$affiliate_state_array = $wpdb->get_results( 
		$wpdb->prepare( 
		"
		SELECT State, Affiliate
		FROM $wpdb->aha_assessment_board
		WHERE BOARD_ID = %s
		",
		$metro_id )
		, ARRAY_A
	);

	return $affiliate_state_array;
 
 
 
 }