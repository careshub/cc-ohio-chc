<?php
/*
 *
 *
 */

 //Overarching function to render report card
function cc_aha_render_all_revenue_report_card(){
	cc_aha_print_all_report_card_revenue();
}


// Major template pieces
function cc_aha_print_all_report_card_revenue() {
	
	//Get ALL board data.
	$all_data = cc_aha_get_all_board_data();
	
	$state_array = cc_aha_get_unique_board_states();
	$affiliate_array = cc_aha_get_unique_board_affiliates();
	
	//var_dump($all_data);
	
	?>

	<h3 class="screamer">Revenue Dashboard - All Boards</h3>

	<section id="revenue-report-card" class="clear">
		<?php // Building out a table of responses for one metro
		?>
		<h4 class="">Revenue Analysis</h4>
		<div class="legend">
			<ul class="horizontal no-bullets">
				<li class="star-li"><span class="indicator star"></span><span class="legend-text">= Board is considering as a possible priority</span></li>
			
			
			</ul>
		</div>
				
		<span class="print-only">
			<ul id="geography" class="horizontal no-bullets">
				<li class="filter-type">Filter by Geography:</li>
				<li><em>State: </em><span class="state">All</span></li>
				<li><em>Affiliate: </em><span class="affiliate">All</span></li>
				
			</ul>
			
			<ul id="top3" class="horizontal no-bullets">
				<li class="filter-type">Filter by Board Priority: </li>
				<li><span class="board-priority">None Selected</span></li>
			</ul>
		</span>
		
		<!--<span>
			Filter by State: <select name="state-select" id="state-dropdown-top">
				<option value="-1">All States</option>
				<?php
				foreach ( $state_array as $state_option ){ 
					$option_output = '<option value="';
					$option_output .= $state_option;
					$option_output .= '">';
					$option_output .= $state_option;
					$option_output .= '</option>';
					print $option_output;
					
				} ?>
				
			</select>
			Filter by Affiliate: <select name="affiliate-select" id="affiliate-dropdown-top">
				<option value="-1">See all Affiliates</option>
				<?php
				foreach ( $affiliate_array as $affiliate_option ){ 
					$affiliate_nospace = str_replace(' ', '', $affiliate_option);
					$option_output = '<option value="';
					$option_output .= $affiliate_nospace;
					$option_output .= '">';
					$option_output .= $affiliate_option;
					$option_output .= '</option>';
					print $option_output;
					
				} ?>
				
			</select>
		</span>-->
		<?php cc_aha_print_all_revenue_report_card_table( $all_data ); ?>

	</section>
	<?php 
} 

function cc_aha_print_all_revenue_report_card_table( $all_data ) {

	//get titles and subtitles of sections
	$sections = cc_aha_get_summary_sections();
	//TODO, remove these if we're going w version above
	$state_array = cc_aha_get_unique_board_states();
	$affiliate_array = cc_aha_get_unique_board_affiliates();
	
	$revenue_labels = cc_aha_get_revenue_short_label();
	
	$revenue_sections = cc_aha_get_summary_revenue_sections();
	
	//var_dump( $revenue_labels );
	
	?>
	
	<table id="revenue-report-card-table" class="tablesorter">
		
		<!--<div class="hidden"><img class="star-image" src="../im/starswhite_29.png"></div>-->
		
	<?php 

		
		//because of tablesort's thead needs
		?>
		<thead>
			
			<tr class="revenue-labels">
				<th class="max3em">Board<div class='sort-arrow'>&#x25BC;</div></th>
				<th class="">State<div class='sort-arrow'>&#x25BC;</div></th>
				<th class="">Affiliate<div class='sort-arrow'>&#x25BC;</div></th>
		
				<?php 
				foreach ( $revenue_labels as $label ) {
					echo '<th class="">' . $label . '</th>';
				
				} 
				?>
		<?php
			
		
		//one more to account for total score
		//echo '<th></th></tr>'; //no more 'Total' column
		echo '</tr>';
		
		//3rd row for Top 3 buttons - cheap implementaton, I know
		?>
			<tr class="top-3-row">
				<th class="{sorter: false}" colspan="3"><h4>Filter by Board Priority:</h4></th>
				
				<?php 
				foreach ( $revenue_sections as $revenue_name => $revenue_section ) { 
					$top3group = $revenue_name . '-top-3';
					$top3name = $revenue_section['label'];
					echo '<th class="{sorter: false} white-border revenue-report-card-top3 ' . $top3group . '" data-top3group="' . $top3group . '" data-top3name="' . $top3name . '"><a class="button"><div class="top-3-star"></div><div class="top-3-count"></div></a><br></th>';
				
				} 
				?>
			</tr>
		
		<?php
		//4th row for state/affiliate sorting, as well as Top 3 selection
		echo '<tr class="overall-header { sorter: false } geography"><td class="filter-type">Geography:</td>';
		?>
			<th class="state-select { sorter: false }"><select name="state-select" id="state-dropdown">
				<option value="-1">All</option>
				<?php
				foreach ( $state_array as $state_option ){ 
					$option_output = '<option value="';
					$option_output .= $state_option;
					$option_output .= '">';
					$option_output .= $state_option;
					$option_output .= '</option>';
					print $option_output;
					
				} ?>
				
			</select></td>
			<th class="affiliate-select { sorter: false }"><select name="affiliate-select" id="affiliate-dropdown">
				<option value="-1">See all Affiliates</option>
				<?php
				foreach ( $affiliate_array as $affiliate_option ){ 
					$affiliate_nospace = str_replace(' ', '', $affiliate_option);
					$option_output = '<option value="';
					$option_output .= $affiliate_nospace;
					$option_output .= '">';
					$option_output .= $affiliate_option;
					$option_output .= '</option>';
					print $option_output;
					
				} ?>
				
			</select></th>
			
			
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
		
		<?php
		echo '</tr>';
		echo '</thead>';
		
		//var_dump( $all_data );
		
		foreach( $all_data as $data ){
			$metro_id = $data['BOARD_ID']; 
			$total_score = 0;
			$state = $data['State'];
			
			$affiliate = $data['Affiliate'];
			//strip spaces from affiliate
			$affiliate = str_replace(' ', '', $affiliate);
			
			//$state_array[] = $state; //push this state onto the array for displaying?
			
			echo '<tr class="board-data ' . $state . ' ' . $affiliate . '">';
			echo '<td class="">' . $data['Board_Name'] . '</td>';
			echo '<td class="">' . $data['State'] . '</td>';
			echo '<td class="">' . $data['Affiliate'] . '</td>';
			
				foreach ( $revenue_sections as $revenue_name => $revenue_section ) { 
					$top3answer = $data[$revenue_name . '-top-3'] ? 'Yes' : 'No';
					$top3class = 'top-3-answer-' . strtolower( $top3answer );
					$top3show = $data[$revenue_name . '-top-3'] ? $revenue_name . '-top-3' : '';
				?>
				<td class="<?php echo $top3show . ' ' . $top3class; ?>" title="<?php echo $top3answer; ?>">
					<?php //echo $data[$revenue_name . '-top-3'] ? 'Yes' : 'No'; ?>
				</td>
			<?php } 
			
			echo '</tr>';
			//echo($data);
		}
		
	?>
	</table>
	
	<?php


}

/*
 * Return the short_label/tabel label of the revenue sections
 *
 */
function cc_aha_get_revenue_short_label() {
	$section_data = cc_aha_get_summary_revenue_sections();
	$sections = array();
	
	foreach ( $section_data as $key => $value ) {
		$sections[] = $value['short_label'];
	}
	
	return $sections;
}

