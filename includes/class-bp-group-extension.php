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
            'name' => 'Quarterly Assessment',
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

    public function display( $group_id = null ) {

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
			if (cc_ohio_chc_find_admin_mod()) {				
				cc_ohio_chc_render_form_subnav();
				echo "Click <a href='mailto:johnm@ip-3.org?subject=Ohio CHC CSV Request'>HERE</a> to request .csv file of form results";
				//if (cc_ohio_chc_is_stickyform_active()) {
					//echo do_shortcode( "[directory form='8']" );					
				//}
			} else {			
				if ( current_user_has_county() ) {
					cc_ohio_chc_render_form_subnav();
					//echo 'blah';
					cc_ohio_chc_render_form( 1 );
				} else {	
					//TODO: print message
					echo 'Error message form 1';
				}
			}
		} else if ( cc_ohio_chc_on_form2_screen() ){ //before forms screen, because nested
			if (cc_ohio_chc_find_admin_mod()) {				
				cc_ohio_chc_render_form_subnav();
				echo "Click <a href='mailto:johnm@ip-3.org?subject=Ohio CHC CSV Request'>HERE</a> to request .csv file of form results";
				// if (cc_ohio_chc_is_stickyform_active()) {
					// echo do_shortcode( "[stickylist id='15']" );					
				// }
			} else {				
				if ( current_user_has_county() ) {
					cc_ohio_chc_render_form_subnav();
				
					cc_ohio_chc_render_form( 2 );
				} else {
					//TODO: print message
					echo 'Error message form 2';
				}
			}
		} else if ( cc_ohio_chc_on_form3_screen() ){ //before forms screen, because nested
			if (cc_ohio_chc_find_admin_mod()) {				
				cc_ohio_chc_render_form_subnav();
				echo "Click <a href='mailto:johnm@ip-3.org?subject=Ohio CHC CSV Request'>HERE</a> to request .csv file of form results";
				// if (cc_ohio_chc_is_stickyform_active()) {
					// echo do_shortcode( "[stickylist id='16']" );					
				// }
			} else {				
				if ( current_user_has_county() ) {
					cc_ohio_chc_render_form_subnav();
				
					cc_ohio_chc_render_form( 3 );
				} else {
					//TODO: print message
					echo 'Error message form 3';
				}
			}
		} else if ( cc_ohio_chc_on_form4_screen() ){ //before forms screen, because nested
			if (cc_ohio_chc_find_admin_mod()) {				
				cc_ohio_chc_render_form_subnav();
				echo "Click <a href='mailto:johnm@ip-3.org?subject=Ohio CHC CSV Request'>HERE</a> to request .csv file of form results";
				// if (cc_ohio_chc_is_stickyform_active()) {
					// echo do_shortcode( "[stickylist id='18']" );					
				// }
			} else {				
				if ( current_user_has_county() ) {
					cc_ohio_chc_render_form_subnav();
				
					cc_ohio_chc_render_form( 4 );
				} else {
					//TODO: print message
					echo 'Error message form 4';
				}
			}
		} else if ( cc_ohio_chc_on_form5_screen() ){ //before forms screen, because nested
			if (cc_ohio_chc_find_admin_mod()) {				
				cc_ohio_chc_render_form_subnav();
				echo "Click <a href='mailto:johnm@ip-3.org?subject=Ohio CHC CSV Request'>HERE</a> to request .csv file of form results";
				// if (cc_ohio_chc_is_stickyform_active()) {
					// echo do_shortcode( "[stickylist id='20']" );					
				// }
			} else {				
				if ( current_user_has_county() ) {
					cc_ohio_chc_render_form_subnav();
				
					cc_ohio_chc_render_form( 5 );
				} else {
					//TODO: print message
					echo 'Error message form 5';
				}
			}
		} else if ( cc_ohio_chc_on_form6_screen() ){ //before forms screen, because nested
			if (cc_ohio_chc_find_admin_mod()) {				
				cc_ohio_chc_render_form_subnav();
				echo "Click <a href='mailto:johnm@ip-3.org?subject=Ohio CHC CSV Request'>HERE</a> to request .csv file of form results";
				// if (cc_ohio_chc_is_stickyform_active()) {
					// echo do_shortcode( "[stickylist id='23']" );					
				// }
			} else {				
				if ( current_user_has_county() ) {
					cc_ohio_chc_render_form_subnav();
				
					cc_ohio_chc_render_form( 6 );
				} else {
					//TODO: print message
					echo 'Error message form 6';
				}
			}
		} else if ( cc_ohio_chc_on_form7_screen() ){ //before forms screen, because nested
			if (cc_ohio_chc_find_admin_mod()) {				
				cc_ohio_chc_render_form_subnav();
				echo "Click <a href='mailto:johnm@ip-3.org?subject=Ohio CHC CSV Request'>HERE</a> to request .csv file of form results";
				// if (cc_ohio_chc_is_stickyform_active()) {
					// echo do_shortcode( "[stickylist id='19']" );					
				// }
			} else {				
				if ( current_user_has_county() ) {
					cc_ohio_chc_render_form_subnav();
				
					cc_ohio_chc_render_form( 7 );
				} else {
					//TODO: print message
					echo 'Error message form 7';
				}
			}
		} else if ( cc_ohio_chc_on_reportform1_screen() ){ //before forms screen, because nested
		
			cc_ohio_chc_render_report_subnav(); 
			cc_ohio_county_results();		
		
		} else if ( cc_ohio_chc_on_reportform2_screen() ){ //before forms screen, because nested
		
			cc_ohio_chc_render_report_subnav(); 
			cc_ohio_program_data_summary();	
			//mb_test_ohio();
			
			
	
		} else if ( cc_ohio_chc_on_report_screen() ) {
		
			cc_ohio_chc_render_report_subnav(); 

		
		} else if ( cc_ohio_chc_on_form_screen() ) {
		
			if ( current_user_has_county() ) {
			
				cc_ohio_chc_render_form_subnav();
				
			} else {
				if (cc_ohio_chc_find_admin_mod()) {			
					echo "Click <a href='mailto:johnm@ip-3.org?subject=Ohio CHC CSV Request'>HERE</a> to request .csv file of form results";			
				} else {			
					//TODO: print message
					echo 'You must be assigned a region/county by your administrator to view the forms.';
				}
			}
		
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