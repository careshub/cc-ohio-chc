<?php
/**
 * CC Creating Healthy Communities Ohio Extras
 *
 * Description: File that holds the rendering functions - future gravity forms calls
 *
 * @package   CC Creating Healthy Communities Ohio Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2015 CommmunityCommons.org
 */
 
 
/**
 * Print content for the "Introduction" (default tab)
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_ohio_chc_print_introductory_text(){
    ?>
    <p>
        Welcome to the Creating Healthy Communities – Ohio’s [  ] page.  
    </p>
    <?php
}
/**
 * Creating container for form to set counties for users
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_ohio_chc_print_county_select_container_markup() {
	//TODO: create form w/select that calls cc_ohio_chc_get_member_array and cc_ohio_chc_get_county_array and do some usermeta saving (there's a funciton for that, too!)
    //TODO: if user is NOT a moderator, they can't see this (they shouldnt have the relevant tab, anywayz)
	
	?>
    <form id="ohio_chc_county_select" class="" method="post" action="<?php echo cc_ohio_chc_get_home_permalink() . 'save-counties/'; ?>">
  
    </form>
    <?php
}

function cc_ohio_chc_print_toc(){
	//TODO, print the table of contents with links to appropriate forms, based on usermeta 'ohio_chc_county'
	echo 'table of contents';



}

/**
 * Builds the subnav of the Ohio CHC group tab
 *
 * @since   1.0.0
 * @return  HTML
 */
function cc_ohio_chc_render_tab_subnav(){
	?>
	<div id="subnav" class="item-list-tabs no-ajax">
		<ul class="nav-tabs">
			<li <?php if ( cc_ohio_chc_on_main_screen() ) { echo 'class="current"'; } ?>>
				<a href="<?php echo cc_ohio_chc_get_home_permalink(); ?>">Introduction</a>
			</li>
			<li <?php if ( cc_ohio_chc_on_assessment_screen() ) { echo 'class="current"'; } ?>>
				<a href="<?php echo cc_ohio_chc_get_assessment_permalink(); ?>">Forms</a>
			</li>
				
			<?php //TODO: if user is mod : 
				//TODO: set up assignment permalink thing
			?>
				<li <?php //if ( cc_aha_on_analysis_screen( 'revenue' ) ) { echo 'class="current"'; } ?>>
					<a href="<?php echo cc_ohio_chc_get_assessment_permalink(  ); ?>">User-County Assignment</a>
				</li>
			<?php// endif; ?>
			
		</ul>
	</div>
	<?php
}

