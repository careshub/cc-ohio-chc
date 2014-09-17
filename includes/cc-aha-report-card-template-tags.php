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
	
	//var_dump($all_data);
	
	?>

	<h3 class="screamer">Summary Dashboard - All Boards</h3>

	<section id="single-report-card-health" class="clear">
		<?php // Building out a table of responses for one metro
		?>
		<h4>Community Health Assessment Analysis</h4>
		
		<ul class="horizontal no-bullets">
			
			<li><div class="button community-show-trigger">SHOW COMMUNITY</div></li>
			<li><div class="button school-show-trigger">SHOW SCHOOL</div></li>
			<li><div class="button care-show-trigger">SHOW CARE</div></li>
			
			<li class="alignright">
				<li><div class="button all-show-trigger">SHOW ALL</div></li>
			</li>
		</ul>
		<?php cc_aha_print_report_card_table( $all_data ); ?>

	</section>
	<?php 
} 

function cc_aha_print_report_card_table( $all_data ) {

	//get titles and subtitles of sections
	$sections = cc_aha_get_summary_sections();
	
	//just testing a few.. TODO: remove this
	$data3 = array_slice ( $all_data , 0, 3);
	?>
	
	<table id="report-card-table" class="tablesorter">
		
		
	<?php 

		
		echo '</tr>';
		
		//because of tablesort's thead needs
		?>
		<thead>
			<tr class="overall-header">
				<th class="{sorter: false}"></th>
				<th class="{sorter: false}"></th>
				<th class="{sorter: false}"></th>
				<th class="{sorter: false} white-border community-show" colspan="6">Community Policies<br></th>
				
				<th class="{sorter: false} white-border school-show" colspan="5">Healthy Schools<br></th>
				
				<th class="{sorter: false} white-border care-trigger" colspan="3">Healthcare Quality and Access<br></th>
				<th class="">Total Score</th>
			</tr>
			
			<tr>
				<th class="{sorter: false}"></th>
				<th class="{sorter: false}"></th>
				<th class="{sorter: false}"></th>
				<th class="{sorter: false} white-border community-show" colspan="2">Tobacco<br></th>
				<th class="{sorter: false} white-border community-show" colspan="1">Physical Activity<br></th>
				<th class="{sorter: false} white-border community-show" colspan="3">Healthy Diet<br></th>
				
				<th class="{sorter: false} white-border school-show" colspan="2">Physical Activity<br></th>
				<th class="{sorter: false} white-border school-show" colspan="2">Healthy Diet<br></th>
				<th class="{sorter: false} white-border school-show" colspan="1">Chain of Survival<br></th>
				
				<th class="{sorter: false} white-border care-trigger" colspan="3">Healthy Outcomes<br></th>
				<th class="{sorter: false}"></th>
			</tr>
		
			<tr>
				<th class="">Board</th>
				<th class="">State</th>
				<th class="">Affiliate</th>
		
		<?php
		foreach ($sections as $section_name => $section_data) { 	
			//and again for the criterion
			// these need to be th so they are sortable (per jquery.tablesort.js)
			$hiding_class = $section_name . '-show';
			foreach ( $section_data['impact_areas'] as $impact_area_name => $impact_area_data ) {
				foreach ( $impact_area_data['criteria'] as $crit_key => $criteria_data ) {
				?>
					<th class="<?php echo $hiding_class; ?>">
						<?php echo $criteria_data['label']; ?>
					</th>
				

				<?php
				}
			}
		}
		echo '</tr></thead>';
		
		foreach( $all_data as $data ){
			echo '<tr>';
			$metro_id = $data['BOARD_ID']; 
			$total_score = 0;
			echo '<td class="">' . $data['Board_Name'] . '</td>';
			echo '<td class="">' . $data['State'] . '</td>';
			echo '<td class="">' . $data['Affiliate'] . '</td>';
				foreach ($sections as $section_name => $section_data) {
				
					$hiding_class = $section_name . '-show';
					//and again for the criterion
					foreach ( $section_data['impact_areas'] as $impact_area_name => $impact_area_data ) {
						foreach ( $impact_area_data['criteria'] as $crit_key => $criteria_data ) {
						
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
							<td class="<?php echo $health_level . ' ' . $hiding_class?>" title="<?php cc_aha_print_dial_label( cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key, $metro_id ) ); ?>">
								<div class="hidden">
									<?php cc_aha_print_dial_label( cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key, $metro_id ) ); ?>
								</div>
							</td>					

						<?php
						}
					}
				}
			
			//Insert total score based on calculations above
			$total_percent = intval( $total_score / 28 * 100 );
			echo '<td>' . $total_percent . '%<br />[=' . $total_score . '/28] </td>';
			
			echo '</tr>';
			//echo($data);
		}
		
	?>
	</table>
	
	<?php


}
