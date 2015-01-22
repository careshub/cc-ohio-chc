<?php 
/* 
 * Description: File that extends the tabs and holds the rendering calls
 *
 */
 
if ( class_exists( 'BP_Group_Extension' ) ) : // Recommended, to prevent problems during upgrade or when Groups are disabled

class CC_Ohio_CHC_Extras_Extension extends BP_Group_Extension {

    function __construct() {
        $args = array(
            'slug' => cc_ohio_chc_get_slug(),
            'name' => 'Assessment Tool',
            'visibility' => 'private',
            'enable_nav_item'   => $this->ohio_chc_tab_is_enabled(),
            // 'access' => 'members',
            // 'show_tab' => 'members',
            'nav_item_position' => 15,
            // 'nav_item_name' => ccgn_get_tab_label(),
            'screens' => array(
                'edit' => array(
                  'enabled' => false,
                ),
                'create' => array(
                    'enabled' => false,
                    // 'position' => 100,
                ),
                'admin' => array(
                    'enabled' => false,
                ),


            ),
        );
        parent::init( $args );
    }
 
    public function display() {

        cc_ohio_chc_render_tab_subnav();
		
		cc_ohio_chc_on_form1_screen();

        if ( cc_ohio_chc_on_main_screen() ) {

            cc_ohio_chc_print_introductory_text();

        } else if ( cc_ohio_chc_on_assessment_screen() ) {

                //if ( ! cc_aha_user_can_do_assessment() ) {
               //     echo '<div class="message info">Sorry, you do not have permission to view this page.</div>';
                //} else {
				
					//TODO: print the table of contents
                    // We'll store the "active" metro id in a cookie for persistence.
                    cc_ohio_chc_print_toc();              
					
                    // Get the right page of the form to display. bp_action_variable(1) is the page number
                    //cc_aha_render_form( bp_action_variable(1) );
                //}

        } else if ( cc_ohio_chc_on_form1_screen() ){ //before forms screen, because nested
		
			cc_ohio_chc_render_form_subnav();
			
			cc_ohio_chc_render_form1();
			
		} else if ( cc_ohio_chc_on_form2_screen() ){ //before forms screen, because nested
		
			cc_ohio_chc_render_form_subnav();
			
			cc_ohio_chc_render_form2();
			
		} else if ( cc_ohio_chc_on_form_screen() ) {
		
			cc_ohio_chc_render_form_subnav();
		
		
		} else if ( cc_ohio_chc_on_county_assignment_screen() ) {
		
			//render county assignment page
			//echo 'County assignment test';
			cc_ohio_chc_print_county_assignment_page();
			
		}
    }

    public function ohio_chc_tab_is_enabled(){

    	if ( cc_ohio_chc_is_ohio_chc_group() ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
bp_register_group_extension( 'CC_Ohio_CHC_Extras_Extension' );
 
endif;