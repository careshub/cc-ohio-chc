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
 * Produce the page code for the summary page, based on the survey and other info.
 *
 * @since   1.0.0
 * @return  html - generated code
 */
function cc_aha_render_summary_page(){
	if ( ! $metro_id = $_COOKIE['aha_summary_metro_id'] )
		return false;

	// Get the data for this metro ID
	$data = cc_aha_get_form_data( $metro_id );
	$school_data = cc_aha_get_school_data( $metro_id );
	
	// Do some math to figure out what's what.


	// Match Yan's dial gauge colors
	$red = '#FF0A17';
	$green = '#78B451';
	$yellow = '#FCA93C';


	$metroid = $data['BOARD_ID'];
	
	//MB added JSON service to get FIPS using selected metro id.
		$response = wp_remote_get( 'http://maps.communitycommons.org/api/service.svc/json/AHAfips/?metroid=' . $metroid );

	//default fips if JSON response fails.	
	 $fips = '05000US17143';
	
	//read JSON response
	 if( is_array( $response ) ) {
			$r = wp_remote_retrieve_body( $response );
			$output = json_decode( $r, true );
			//var_dump($output);
			$fips = $output['getAHAfipsResult'][0]['fips'];
				
		} 	
	
	
	?>
	<div id="summary-navigation">
		<ul class="horizontal no-bullets">
			<li><a href="#tobacco" class="tab-select">Tobacco</a></li>
			<li><a href="#physical-activity" class="tab-select">Physical Activity</a></li>
			<li><a href="#healthy-diet" class="tab-select">Healthy Diet</a></li>
			<li><a href="#chain-of-survival" class="tab-select">Chain of Survival</a></li>
			<li class="alignright"><a href="http://staging.maps.communitycommons.org/CHNA/SelectArea.aspx?reporttype=AHA" target="_blank" class="button">View Data Report</a>&emsp;</li>
		</ul>
		<!-- <input type="button" class="button" value="Print Summary" /> -->
	</div>

	<section id="tobacco" class="clear">
		<h2 class="screamer">Tobacco</h2>
		<h3>How we fit in to the bigger picture</h3>
		<div class="content-row">
			<div class="half-block">
				<?php //TODO: What kind of GeoID should be used to draw these gauges? ?>
				<script src='http://maps.communitycommons.org/jscripts/dialWidget.js?geoid=<?php echo $fips; ?>&id=305'></script>
			</div>
			<div class="half-block">
				<script src='http://maps.communitycommons.org/jscripts/dialWidget.js?geoid=<?php echo $fips; ?>&id=354'></script>	
			</div>
		</div>

		<h3>Clean Indoor Air Laws</h3>
		<div class="content-row">
			<div class="third-block clear">
				<?php // Get a big dial
					cc_aha_print_dial( cc_aha_section_get_score( 'comm_tobacco_1' ) );
				?>
			</div>

			<div class="third-block spans-2">
				<ul>
					<li><?php cc_aha_print_cia_law_status( $data ); ?></li>
					<li><?php cc_aha_print_state_cia_preempt( $data ); ?></li>
					<li>XX % of your community's population is covered by clean indoor air laws.
						<ul>
							<li>Workplaces <?php echo $data[ '1.1.2.2' ]; ?>%</li>
							<li>Restaurants <?php echo $data[ '1.1.2.3' ]; ?>%</li>
							<li>Bars <?php echo $data[ '1.1.2.4' ]; ?>%</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>

		<h3>Tobacco Excise Taxes</h3>
		<div class="content-row">
			<div class="third-block clear">
				<?php // Get a big dial
					cc_aha_print_dial( cc_aha_section_get_score( 'comm_tobacco_2' ) );
				?>
			</div>

			<div class="third-block spans-2">
				<ul>
					<li>The current state tobacco excise tax rate is $<?php echo $data[ '1.2.1.1' ]; ?></li>
					<li>The current local tobacco excise tax rate is $<?php echo $data[ '1.2.2.1' ]; ?></li>
					<li>There is currently XX ability to levy tabacco excise taxes locally</li>
				</ul>
			</div>
		</div>
	</section>

	
	<section id="physical-activity" class="clear">
		<?php // Section setup
			$pe_in_schools = cc_aha_calc_three_tiers( $data, '2.1.4.6' );
			$shared_use = cc_aha_calc_three_tiers( $data, '2.2.5.6' );
		?>
		<h2 class="screamer">Physical Activity</h2>
		<h3>Physical Education in Schools</h3>
		<div class="content-row">
			<div class="third-block clear">
				<?php // Get a big dial
					cc_aha_print_dial( cc_aha_section_get_score( 'school_phys_1' ) );
				?>
			</div>

			<div class="third-block spans-2">
				<ul>
					<li>Your state XXX clean indoor air laws covering restaurants, bars, and workplaces.</li>
					<li>The state does XXX preempt local communities from adopting their own clean indoor air laws.</li>
					<li>XX % of your community's population is covered by clean indoor air laws.
						<ul>
							<li>Workplaces XX %</li>
							<li>Restaurants XX %</li>
							<li>Bars XX %</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>

		<h3>Shared Use Policies</h3>
		<div class="content-row">
			<div class="third-block clear">
				<?php // Get a big dial
					cc_aha_print_dial( cc_aha_section_get_score( 'school_phys_2' )  );
				?>
			</div>

			<div class="third-block spans-2">
				<ul>
					<li>The current state tobacco excise tax rate is $</li>
					<li>The current local tobacco excise tax rate is $.XX</li>
					<li>There is currently XX ability to levy tabacco excise taxes locally</li>
				</ul>
			</div>
		</div>
	</section>



	
	<section id="healthy-diet" class="clear">
		<?php // Section setup
			$nutrition_policy_questions = array( '3.1.3.1.0', '3.1.3.1.1', '3.1.3.1.2', '3.1.3.1.3' );
			$nutrition_policy = cc_aha_calc_n_question_district_yes_tiers( $school_data, $nutrition_policy_questions );
			$nutrition_imp_questions = array( '3.2.1.1', '3.2.1.2', '3.2.1.3', '3.2.1.4', '3.2.1.5' );
			$nutrition_imp = cc_aha_calc_n_question_district_yes_tiers( $school_data, $nutrition_imp_questions );
		?>
		<h2 class="screamer">Healthy Diet</h2>
		<h3>School Nutrition Policy</h3>
		<div class="content-row">
			<div class="third-block clear">
				<?php // Get a big dial
					cc_aha_print_dial( $nutrition_policy );
				?>
			</div>

			<div class="third-block spans-2">
				<ul>
					<li>Your state XXX clean indoor air laws covering restaurants, bars, and workplaces.</li>
					<li>The state does XXX preempt local communities from adopting their own clean indoor air laws.</li>
					<li>XX % of your community's population is covered by clean indoor air laws.
						<ul>
							<li>Workplaces XX %</li>
							<li>Restaurants XX %</li>
							<li>Bars XX %</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>

		<h3>3.2 School Nutrition Implementation</h3>
		<div class="content-row">
			<div class="third-block clear">
				<?php // Get a big dial
					cc_aha_print_dial( $nutrition_imp );
				?>
			</div>

			<div class="third-block spans-2">
				<ul>
					<?php 
						cc_aha_get_assessment_school_results( $metro_id, '3.2.2' );
					?>
				</ul>
			</div>
		</div>
	</section>
	
	
	<section id="chain-of-survival" class="clear">
		<?php // Section setup
			$chain_questions = array( '5.1.4.1' );  //only 5.1.4.1 right now, there is no - 5.1.4.5
			$chain_indicator = cc_aha_calc_n_question_district_yes_tiers( $school_data, $chain_questions );
		?>
		<h2 class="screamer">CHAIN OF SURVIVAL</h2>
		<h3>CPR Graduation Requirements</h3>
		<div class="content-row">
			<div class="third-block clear">
				<?php // Get a big dial
					cc_aha_print_dial( $chain_indicator );
				?>
			</div>

			<div class="third-block spans-2">
				<ul>
					<li>Your state XXX clean indoor air laws covering restaurants, bars, and workplaces.</li>
					<li>The state does XXX preempt local communities from adopting their own clean indoor air laws.</li>
					<li>XX % of your community's population is covered by clean indoor air laws.
						<ul>
							<li>Workplaces XX %</li>
							<li>Restaurants XX %</li>
							<li>Bars XX %</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</section>
	
	
	
	
	


	<script type="text/javascript">
		jQuery(document).ready(function($){
			$(".dial").knob();
		},(jQuery))
	</script>
	<?php


}
/**
 * Output dial html based on input
 * Expects parameter "healthy, intermediate or poor"
 *
 * @since   1.0.0
 * @return  html
 */
function cc_aha_print_dial( $status ){
	?>
	<div class="progress-dial">
		<span class="dial-label"><?php cc_aha_print_dial_label( $status ); ?></span>
		<input type="text" value="<?php cc_aha_print_dial_value( $status ); ?>" class="dial" autocomplete="off" data-width="200" data-fgColor="<?php cc_aha_print_dial_fill_color( $status ); ?>" data-angleOffset=-125 data-angleArc=250 data-displayInput=false data-displayprevious=true data-readOnly=true>
	</div>
	<?php
}
/**
 * Output dial states: label, color, how full
 * 
 *
 * @since   1.0.0
 * @return  string
 */
function cc_aha_print_dial_label( $value ){
	$params = cc_aha_get_dial_parameters( $value );
	echo $params[ 'label' ];
}

function cc_aha_print_dial_value( $value ){
	$params = cc_aha_get_dial_parameters( $value );
	echo $params[ 'percent' ];
}

function cc_aha_print_dial_fill_color( $value ){
	$params = cc_aha_get_dial_parameters( $value );
	echo $params[ 'fill_color' ];
}

function cc_aha_get_dial_parameters( $value ){
		// Match Yan's dial gauge colors
		$green = '#78B451';
		$yellow = '#FCA93C';
		$red = '#FF0A17';

		switch ( $value ) {
		case 'healthy':
			$params = array( 	'percent' 		=> 	100, 
								'fill_color'	=>	'#78B451', 
								'label' 		=> 	'Healthy' 
							);
			break;
		case 'intermediate':
			$params = array( 	'percent' 		=> 	50, 
								'fill_color'	=>	'#FCA93C', 
								'label' 		=> 	'Intermediate' 
							);
			break;
		case 'poor':
		default:
			$params = array( 	'percent' 		=> 	20, 
								'fill_color'	=>	'#FF0A17', 
								'label' 		=> 	'Needs Improvement' 
							);
			break;

	}
	return $params;
}

function cc_aha_print_cia_law_status( $data ) {
	if ( $data['1.1.2.1'] == 'Yes' ) { 
		echo 'Your state has clean indoor air laws covering restaurants, bars, and workplaces.';
	} else {
		echo 'Your state does not have clean indoor air laws covering restaurants, bars, and workplaces.';
	}
}
function cc_aha_print_state_cia_preempt( $data ) {
	if ( $data['1.1.2.1'] == 'Yes' ) { 
		echo 'The state does preempt local communities from adopting their own clean indoor air laws.';
	} else {
		echo 'The state does not preempt local communities from adopting their own clean indoor air laws.';
	}
}



/**
 * Analysis-related calculations.
 * Catch-all switch for health-related scoring: this takes the section name and returns the score string
 *
 * @since   1.0.0
 * @return  string - "healthy", "intermediate" or "poor"
 */ 
function cc_aha_section_get_score( $section, $metro_id = null ){
	if ( ! $metro_id )
		$metro_id = $_COOKIE['aha_summary_metro_id'];

	switch ( $section ) {
		case 'comm_tobacco_1':
			// Clean indoor air
			$score = cc_aha_calc_cia( $metro_id );
			break;
		case 'comm_tobacco_2':
			// Tobacco excise tax
			$score = cc_aha_calc_tobacco_excise( $metro_id );
			break;
		case 'comm_phys_1':
			// Complete streets
			$tiers = array( 'Yes, meets our guidelines', 'Yes, but below our guidelines' );
			$score = cc_aha_calc_three_text_tiers( $metro_id, '2.3.1.1', $tiers );
			break;
		case 'comm_diet_1':
			// Local gov't procurement
			$tiers = cc_aha_get_options_for_question( '3.3.1.1' );
			$score = cc_aha_calc_three_text_tiers( $metro_id, '3.3.1.1', $tiers );
			break;
		case 'comm_diet_2':
			// Sugar-sweetened beverage tax
			// All are poor; no policies have passed
			$score = 'poor';
			break;
		case 'comm_diet_3':
			// Healthy food financing
			// TODO
			$score = cc_aha_calc_tobacco_excise( $metro_id );
			break;		
		case 'school_phys_1':
			// PE in schools
			$score = cc_aha_calc_three_tiers( $metro_id, '2.1.4.6' );
			break;
		case 'school_phys_2':
			// Shared use
			$score = cc_aha_calc_three_tiers( $metro_id, '2.2.5.6' );
			break;		
		case 'school_diet_1':
			// School nutrition policy
			$score = cc_aha_calc_three_tiers( $metro_id, '3.1.3.6' );
			break;
		case 'school_diet_2':
			// School nutrition implementation
			$score = cc_aha_calc_three_tiers( $metro_id, '3.2.1.6' );
			break;
		case 'school_cpr_1':
			// CPR grad requirement
			$score = cc_aha_calc_three_tiers( $metro_id, '5.1.4.6' );
			break;
		case 'care_factors_1':
			// Insurance coverage
			// TODO
			$score = 'poor';
			break;
		case 'care_acute_1':
			// CMS penalty
			// TODO
			$score = 'poor';
			break;
		default:
			// When in doubt...
			$score = 'poor';
			break;
	}
	return $score;
}

function cc_aha_calc_cia( $metro_id ) {
	$data = cc_aha_get_form_data( $metro_id );

	if ( $data[ '1.1.2.2' ] == 100 && $data[ '1.1.2.3' ] == 100 ) {
		if ( $data[ '1.1.2.4' ] == 100 ) {
			return 'healthy';
		} else {
			return 'intermediate';
		}
		
	} else {
		return 'poor';
	}
}
function cc_aha_calc_tobacco_excise( $metro_id ){ 
	$data = cc_aha_get_form_data( $metro_id );

	$total_excise = $data[ '1.2.1.1' ] + $data[ '1.2.2.1' ];
	if ( $total_excise >= 1.85 ) {
		return 'healthy';
	} else if ( $total_excise >= 1 ) {
		return 'intermediate';
	} else {
		return 'poor';
	}
}
function cc_aha_calc_three_text_tiers( $metro_id, $qid, $tiers ){
	// This takes an array for the third arg: the first value is the "healthy" value, the second is the "intermediate" value.
	if ( ! $metro_id )
		$metro_id = $_COOKIE['aha_summary_metro_id'];

	$data = cc_aha_get_form_data( $metro_id );

	if ( $data[ $qid ] == $tiers[0] ) {
		return 'healthy';
	} else if ( $data[ $qid ] == $tiers[1] ) {
		return 'intermediate';
	} else {
		return 'poor';
	}
}
// Generalized to identify 0-49, 50-99 and 100% tiers
function cc_aha_calc_three_tiers( $metro_id, $qid ) {
	if ( ! $metro_id )
		$metro_id = $_COOKIE['aha_summary_metro_id'];

	$data = cc_aha_get_form_data( $metro_id );

	if ( $data[ $qid ] == 100 ) {
		return 'healthy';
	} else if ( $data[ $qid ] >= 50 ) {
		return 'intermediate';
	} else {
		return 'poor';
	}
}

/* Generalized to take N yes/no questions for a board,
 *	get all data for districts w/in board,
 *	calculate % yes for total and place in tiers
 *	
 *	3.1, 3.2, 5.1 use this
 */
function cc_aha_calc_n_question_district_yes_tiers( $school_data, $qids = array() ) { //Gold star for BEST NAME EVER

	$num_yes = 0;
	$total_questions = 0;
	
	//loop through each school
	foreach ( $school_data as $school ){
	
		//loop through each question for this school
		foreach( $qids as $qid ){
		
			//if data is not defined, either no column or cell data, don't assume 'No'.
			if( isset( $school[ $qid ] ) ) {
				//depending on where the data comes from, it could be 'Yes' or '1'
				if ( ( $school[ $qid ] == 'Yes' ) || ( $school[ $qid ] == '1' ) ) {
					$num_yes++;
				}
				$total_questions++; //hmm
			}
		}
	}
		
	if ( ( $num_yes == $total_questions ) && ( $num_yes > 0 ) ) {  //because 0 total questions and 0 yeses indicate 1
		return 'healthy';
	} else if ( ( ( $num_yes / $total_questions ) >= 0.5 ) && ( ( $num_yes / $total_questions ) < 1 ) ) {
		return 'intermediate';
	} else {
		return 'poor';
	}	
	
}
