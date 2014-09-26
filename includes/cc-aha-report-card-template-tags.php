<?php
/*
 *
 *
 */

 //Overarching function to render report card
function cc_aha_render_report_card(){
	cc_aha_print_all_report_card_health();
}


// Major template pieces
function cc_aha_print_all_report_card_health( ) {
	
	//Get ALL board data.
	$all_data = cc_aha_get_all_board_data();
	$state_array = cc_aha_get_unique_board_states();
	$affiliate_array = cc_aha_get_unique_board_affiliates();
	
	//var_dump($all_data);
	
	?>

	<h3 class="screamer">Summary Dashboard - All Boards</h3>

	<section id="summary-report-card" class="clear">
		<?php // Building out a table of responses for one metro
		?>
		<h4>Community Health Assessment Analysis</h4>
		
		<ul class="horizontal no-bullets">
			<li class="filter-type">Filter by Category:</li>
			<li><a class="button community-hide-trigger">HIDE COMMUNITY</a></li>
			<li><a class="button school-hide-trigger">HIDE SCHOOL</a></li>
			<li><a class="button care-hide-trigger">HIDE CARE</a></li>
			
			<li class="alignright"><a class="button" onClick="window.print()">PRINT</a></li>
				<li><a class="button all-show-trigger">SHOW ALL</a></li>
		</ul>
		
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
		<?php cc_aha_print_report_card_table( $all_data ); ?>

	</section>
	<?php 
} 

/* returns criteria labels
 *
 */
function cc_aha_get_all_criteria_labels(){

	$summary_data = cc_aha_get_summary_sections();
	$criteria_labels = array();
	//doing this cheaply.
	foreach ( $summary_data as $summary ){
		$impact_areas = $summary['impact_areas'];
		foreach ($impact_areas as $impact){
			$criteria = $impact['criteria'];
			foreach ( $criteria as $cr ){
				$criteria_labels[] = $cr['label'];
			}
		}
	}
	return $criteria_labels;
}

/* returns criteria groups
 *
 */
function cc_aha_get_all_criteria_groups(){

	$summary_data = cc_aha_get_summary_sections();
	$criteria_groups = array();
	//doing this cheaply.
	foreach ( $summary_data as $summary ){
		$impact_areas = $summary['impact_areas'];
		foreach ($impact_areas as $impact){
			$criteria = $impact['criteria'];
			foreach ( $criteria as $cr ){
				$criteria_groups[] = $cr['group'];
			}
		}
	}
	return $criteria_groups;
}

function cc_aha_print_report_card_table( $all_data ) {

	//get titles and subtitles of sections
	$sections = cc_aha_get_summary_sections();
	//TODO, remove these if we're going w version above
	$state_array = cc_aha_get_unique_board_states();
	$affiliate_array = cc_aha_get_unique_board_affiliates();
	
	$criteria_labels = cc_aha_get_all_criteria_labels();
	$criteria_groups = cc_aha_get_all_criteria_groups();
	
	//var_dump( $criteria_groups );
	
	//just testing a few.. TODO: remove this
	$data3 = array_slice ( $all_data , 0, 3);
	?>
	
	<table id="report-card-table" class="tablesorter">
		
		
	<?php 

		
		echo '</tr>';
		
		//because of tablesort's thead needs
		?>
		<thead>
			<tr class="overall-header summary-section">
				<th class="{sorter: false} no-side-border"></th>
				<th class="{sorter: false} no-side-border"></th>
				<th class="{sorter: false} no-side-border"></th>
				<th class="{sorter: false} white-border community-show" colspan="6">Community Policies<br></th>
				
				<th class="{sorter: false} white-border school-show" colspan="5">Healthy Schools<br></th>
				
				<th class="{sorter: false} white-border care-show" colspan="3">Healthcare Quality and Access<br></th>
				<!--<th class="">Total Score</th>-->
			</tr>
			
			<tr class="impact-row">
				<th class="{sorter: false}"></th>
				<th class="{sorter: false}"></th>
				<th class="{sorter: false}"></th>
				<th class="{sorter: false} white-border community-show" colspan="2">Tobacco<br></th>
				<th class="{sorter: false} white-border community-show" colspan="1">Physical Activity<br></th>
				<th class="{sorter: false} white-border community-show" colspan="3">Healthy Diet<br></th>
				
				<th class="{sorter: false} white-border school-show" colspan="2">Physical Activity<br></th>
				<th class="{sorter: false} white-border school-show" colspan="2">Healthy Diet<br></th>
				<th class="{sorter: false} white-border school-show" colspan="1">Chain of Survival<br></th>
				
				<th class="{sorter: false} white-border care-show" colspan="3">Healthy Outcomes<br></th>
				<!--<th class="{sorter: false}"></th>-->
			</tr>
		
			<tr class="criteria-row">
				<th class="min60">Board<div class='sort-arrow'>&#x25BC;</div></th>
				<th class="">State<div class='sort-arrow'>&#x25BC;</div></th>
				<th class="">Affiliate<div class='sort-arrow'>&#x25BC;</div></th>
		
		<?php
			
			printCriteriaTH();
		
		//one more to account for total score
		//echo '<th></th></tr>'; //no more 'Total' column
		echo '</tr>';
		
		//3rd row for Top 3 buttons - cheap implementaton, I know
		?>
			<tr class="top-3-row">
				<th class="{sorter: false}" colspan="3"><h4>Filter by Board Priority:</h4></th>
				<th class="{sorter: false} white-border community-show report-card-top3 community_tobacco_1-top-3" data-top3group="<?php echo $criteria_groups[0] . '-top-3'; ?>" data-top3name="<?php echo $criteria_labels[0]; ?>"><a class="button"><div class="top-3-star"></div></a><br></th>
				<th class="{sorter: false} white-border community-show report-card-top3 <?php echo $criteria_groups[1] . '-top-3'; ?>" data-top3group="<?php echo $criteria_groups[1] . '-top-3'; ?>" data-top3name="<?php echo $criteria_labels[1]; ?>"><a class="button"><div class="top-3-star"></div></a><br></th>
				<th class="{sorter: false} white-border community-show report-card-top3 <?php echo $criteria_groups[2] . '-top-3'; ?>" data-top3group="<?php echo $criteria_groups[2] . '-top-3'; ?>" data-top3name="<?php echo $criteria_labels[2]; ?>"><a class="button"><div class="top-3-star"></div></a><br></th>
				<th class="{sorter: false} white-border community-show report-card-top3 <?php echo $criteria_groups[3] . '-top-3'; ?>" data-top3group="<?php echo $criteria_groups[3] . '-top-3'; ?>" data-top3name="<?php echo $criteria_labels[3]; ?>"><a class="button"><div class="top-3-star"></div></a><br></th>
				<th class="{sorter: false} white-border community-show report-card-top3 <?php echo $criteria_groups[4] . '-top-3'; ?>" data-top3group="<?php echo $criteria_groups[4] . '-top-3'; ?>" data-top3name="<?php echo $criteria_labels[4]; ?>"><a class="button"><div class="top-3-star"></div></a><br></th>
				<th class="{sorter: false} white-border community-show report-card-top3 <?php echo $criteria_groups[5] . '-top-3'; ?>" data-top3group="<?php echo $criteria_groups[5] . '-top-3'; ?>" data-top3name="<?php echo $criteria_labels[5]; ?>"><a class="button"><div class="top-3-star"></div></a><br></th>
				
				<th class="{sorter: false} white-border school-show report-card-top3 <?php echo $criteria_groups[6] . '-top-3'; ?>" data-top3group="<?php echo $criteria_groups[6] . '-top-3'; ?>" data-top3name="<?php echo $criteria_labels[6]; ?>"><a class="button"><div class="top-3-star"></div></a><br></th>
				<th class="{sorter: false} white-border school-show report-card-top3 <?php echo $criteria_groups[7] . '-top-3'; ?>" data-top3group="<?php echo $criteria_groups[7] . '-top-3'; ?>" data-top3name="<?php echo $criteria_labels[7]; ?>"><a class="button"><div class="top-3-star"></div></a><br></th>
				<th class="{sorter: false} white-border school-show report-card-top3 <?php echo $criteria_groups[8] . '-top-3'; ?>" data-top3group="<?php echo $criteria_groups[8] . '-top-3'; ?>" data-top3name="<?php echo $criteria_labels[8]; ?>"><a class="button"><div class="top-3-star"></div></a><br></th>
				<th class="{sorter: false} white-border school-show report-card-top3 <?php echo $criteria_groups[9] . '-top-3'; ?>" data-top3group="<?php echo $criteria_groups[9] . '-top-3'; ?>" data-top3name="<?php echo $criteria_labels[9]; ?>"><a class="button"><div class="top-3-star"></div></a><br></th>
				<th class="{sorter: false} white-border school-show report-card-top3 <?php echo $criteria_groups[10] . '-top-3'; ?>" data-top3group="<?php echo $criteria_groups[10] . '-top-3'; ?>" data-top3name="<?php echo $criteria_labels[10]; ?>"><a class="button"><div class="top-3-star"></div></a><br></th>
				
				<th class="{sorter: false} white-border care-show report-card-top3 <?php echo $criteria_groups[11] . '-top-3'; ?>" data-top3group="<?php echo $criteria_groups[11] . '-top-3'; ?>" data-top3name="<?php echo $criteria_labels[11]; ?>"><a class="button"><div class="top-3-star"></div></a><br></th>
				<th class="{sorter: false} white-border care-show report-card-top3 <?php echo $criteria_groups[12] . '-top-3'; ?>" data-top3group="<?php echo $criteria_groups[12] . '-top-3'; ?>" data-top3name="<?php echo $criteria_labels[12]; ?>"><a class="button"><div class="top-3-star"></div></a><br></th>
				<th class="{sorter: false} white-border care-show report-card-top3 <?php echo $criteria_groups[13] . '-top-3'; ?>" data-top3group="<?php echo $criteria_groups[13] . '-top-3'; ?>" data-top3name="<?php echo $criteria_labels[13]; ?>"><a class="button"><div class="top-3-star"></div></a><br></th>
				<!--<th class="{sorter: false}"></th>-->
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
			
			<!--<th class="{ sorter: false }"><select name="affiliate-select" id="affiliate-dropdown">
				<option value="-1">See all Affiliates</option>
			<?php
				foreach ( $criteria_labels as $criteria ){ 
					$criteria_nospace = str_replace(' ', '', $criteria);
					$option_output = '<option value="';
					$option_output .= $criteria_nospace;
					$option_output .= '">';
					$option_output .= $criteria;
					$option_output .= '</option>';
					print $option_output;
					
				}
			?>
			</select>
			</th>-->
			<th class="{ sorter: false } community-show"></th>
			<th class="{ sorter: false } community-show"></th>
			<th class="{ sorter: false } community-show"></th>
			<th class="{ sorter: false } community-show"></th>
			<th class="{ sorter: false } community-show"></th>
			<th class="{ sorter: false } community-show"></th>
			<th class="{ sorter: false } school-show"></th>
			<th class="{ sorter: false } school-show"></th>
			<th class="{ sorter: false } school-show"></th>
			<th class="{ sorter: false } school-show"></th>
			<th class="{ sorter: false } school-show"></th>
			<th class="{ sorter: false } care-show"></th>
			<th class="{ sorter: false } care-show"></th>
			<th class="{ sorter: false } care-show"></th>
			<!--<th class="{ sorter: false }"></th>-->
		
		<?php
		echo '</tr>';
		echo '</thead>';
		
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
				foreach ($sections as $section_name => $section_data) {
				
					$hiding_class = $section_name . '-show';
					//and again for the criterion
					foreach ( $section_data['impact_areas'] as $impact_area_name => $impact_area_data ) {
						foreach ( $impact_area_data['criteria'] as $crit_key => $criteria_data ) {
							//$top3 = $data[$section_name . '-' . $impact_area_name . '-' . $crit_key . '-top-3'];
							
							$top3Yes = $data[$section_name . '-' . $impact_area_name . '-' . $crit_key . '-top-3'] ? $criteria_data['group'] . '-top-3' : ''; ;
							
							$health_level = cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key, $metro_id );
							switch( $health_level ){
								case "healthy":
									$total_score += 2;
									break;
								case "intermediate":
									$total_score += 1;
									break;
								case "poor":
									$total_score += 0;
									break;							
							}
						?>
							<td class="<?php echo $health_level . ' ' . $hiding_class . ' ' . $top3Yes; ?>" title="<?php cc_aha_print_dial_label( cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key, $metro_id ) ); ?>" data-top3group="<?php echo $top3Yes; ?>">
								<div class="hidden">
									<?php cc_aha_print_dial_label( cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key, $metro_id ) ); ?>
								</div>
							</td>					

						<?php
						}
					}
				}
			
			//Insert total score based on calculations above
			//$total_percent = intval( $total_score / 28 * 100 );
			//echo '<td>' . $total_percent . '%<br />[=' . $total_score . '/28] </td>';
			
			echo '</tr>';
			//echo($data);
		}
		
	?>
	</table>
	
	<?php


}

function printCriteriaTH(){
	$hiding_class = $section_name . '-show';
	$rotate_class = ' report-card-rotate';
	$skinny_column_class = ' skinny-column';
	?>
	
	<th class="<?php echo 'community-show' . $rotate_class . $skinny_column_class; ?>">
		Smoke Free Air<div class='sort-arrow'>&#x25BC;</div>
	</th>
	<th class="<?php echo 'community-show' . $rotate_class . $skinny_column_class; ?>">
		Tobacco Excise Taxes<div class='sort-arrow'>&#x25BC;</div>
	</th>
	<th class="<?php echo 'community-show' . $rotate_class . $skinny_column_class; ?>">
		Complete Streets<div class='sort-arrow'>&#x25BC;</div>
	</th>
	<th class="<?php echo 'community-show' . $rotate_class . $skinny_column_class; ?>">
		Local Govt Procure&shy;ment<div class='sort-arrow'>&#x25BC;</div>
	</th>
	<th class="<?php echo 'community-show' . $rotate_class . $skinny_column_class; ?>">
		Sugar Bevg Tax<div class='sort-arrow'>&#x25BC;</div>
	</th>
	<th class="<?php echo 'community-show' . $rotate_class . $skinny_column_class; ?>">
		Healthy Food Financing<div class='sort-arrow'>&#x25BC;</div>
	</th>
	<th class="<?php echo 'school-show' . $rotate_class . $skinny_column_class; ?>">
		PE in Schools<div class='sort-arrow'>&#x25BC;</div>
	</th>
	<th class="<?php echo 'school-show' . $rotate_class . $skinny_column_class; ?>">
		Shared Use<div class='sort-arrow'>&#x25BC;</div>
	</th>
	<th class="<?php echo 'school-show' . $rotate_class . $skinny_column_class; ?>">
		School Nutr Policy<div class='sort-arrow'>&#x25BC;</div>
	</th>
	<th class="<?php echo 'school-show' . $rotate_class . $skinny_column_class; ?>">
		School Nutr Implement&shy;ation<div class='sort-arrow'>&#x25BC;</div>
	</th>
	<th class="<?php echo 'school-show' . $rotate_class . $skinny_column_class; ?>">
		CPR Grad Require&shy;ment<div class='sort-arrow'>&#x25BC;</div>
	</th>
	<th class="<?php echo 'care-show' . $rotate_class . $skinny_column_class; ?>">
		Insurance Covg<div class='sort-arrow'>&#x25BC;</div>
	</th>
	<th class="<?php echo 'care-show' . $rotate_class . $skinny_column_class; ?>">
		CMS Penalty Hospitals: Total<div class='sort-arrow'>&#x25BC;</div>
	</th>
	<th class="<?php echo 'care-show' . $rotate_class . $skinny_column_class; ?>">
		CMS Penalty: Under-served<div class='sort-arrow'>&#x25BC;</div>
	</th>
<?php
}
