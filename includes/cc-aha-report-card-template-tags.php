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
			
			<li><a href="<?php echo cc_aha_get_analysis_permalink( bp_action_variable( 2 ) ); ?>" class="button"></a></li>
			
			<li class="alignright">
				<?php if ( ! empty( $cleanedfips ) ) { ?>
					<a href="http://assessment.communitycommons.org/CHNA/OpenReport.aspx?reporttype=AHA&areatype=county&areaid=<?php echo $cleanedfips; ?>" target="_blank" class="button">View Data Report</a>
				<?php } else { ?>	
					<a href="http://assessment.communitycommons.org/CHNA/selectarea.aspx?reporttype=AHA " target="_blank" class="button">View Data Report</a>				
				<?php } ?>	
			</li>
			<li class="alignright">
				<a href="javascript:window.print()" class="button">Print Page</a>
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
				<th class="{sorter: false} white-border" colspan="6">Community Policies<br></th>
				<th class="{sorter: false} white-border" colspan="5">Healthy Schools<br></th>
				<th class="{sorter: false} white-border" colspan="3">Healthcare Quality and Access<br></th>
				<th class="">Total Score</th>
			</tr>
			
			<tr>
				<th class="{sorter: false}"></th>
				<th class="{sorter: false}"></th>
				<th class="{sorter: false}"></th>
				<th class="{sorter: false} white-border" colspan="2">Tobacco<br></th>
				<th class="{sorter: false} white-border" colspan="1">Physical Activity<br></th>
				<th class="{sorter: false} white-border" colspan="3">Healthy Diet<br></th>
				<th class="{sorter: false} white-border" colspan="2">Physical Activity<br></th>
				<th class="{sorter: false} white-border" colspan="2">Healthy Diet<br></th>
				<th class="{sorter: false} white-border" colspan="1">Chain of Survival<br></th>
				<th class="{sorter: false} white-border" colspan="3">Healthy Outcomes<br></th>
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
			foreach ( $section_data['impact_areas'] as $impact_area_name => $impact_area_data ) {
				foreach ( $impact_area_data['criteria'] as $crit_key => $criteria_data ) {
				?>
					<th>
						<?php echo $criteria_data['label']; ?>
					</th>
					<!-- <td>
						Maybe a gauge goes here.
					</td> 
					<td class="<?php //echo cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key ); ?>">
						<?php// cc_aha_print_dial_label( cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key ) ); ?>
					</td>
					<td>
						<?php echo $data[$section_name . '-' . $impact_area_name . '-' . $crit_key . '-top-3'] ? 'Yes' : 'No'; ?>
					</td>-->
				

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
					//and again for the creiterion
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
							<td class="<?php echo $health_level; ?>" title="<?php cc_aha_print_dial_label( cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key, $metro_id ) ); ?>">
								<div class="hidden">
									<?php cc_aha_print_dial_label( cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key, $metro_id ) ); ?>
								</div>
							</td>					

						<?php
						}
					}
				}
			
			//TODO: insert total score here
			$total_percent = intval( $total_score / 28 * 100 );
			echo '<td>' . $total_percent . '%<br />[=' . $total_score . '/28] </td>';
			
			echo '</tr>';
			//echo($data);
		}
		
	?>
	</table>
	
	<?php


}
