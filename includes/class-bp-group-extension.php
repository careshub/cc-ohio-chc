<?php 
if ( class_exists( 'BP_Group_Extension' ) ) : // Recommended, to prevent problems during upgrade or when Groups are disabled

class CC_AHA_Extras_Extension extends BP_Group_Extension {

    function __construct() {
        $args = array(
            'slug' => cc_aha_get_slug(),
            'name' => 'AHA Tab Name',
            'visibility' => 'private',
            'enable_nav_item'   => $this->aha_tab_is_enabled(),
            // 'access' => 'members',
            // 'show_tab' => 'members',
            'nav_item_position' => 105,
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

    	// Get the user's Metro ID
    	if ( cc_aha_get_array_user_metro_ids() ) {
            $summary_message = 'Your regional associations: ' . cc_aha_get_metro_id_list();
            $link_text = 'Change';
        } else {
            $summary_message = 'Please set your AHA board association.';
            $link_text = 'Set region';
        }

    	?>
        <div class="toggleable toggle-closed message info">
            <p class="toggle-switch" id="update-metro-id-toggle">
                <?php echo $summary_message; ?>&emsp;<a class="toggle-link" id="update-metro-id-toggle-link" href="#"><span class="show-pane plus-or-minus"></span><?php echo $link_text; ?></a>
            </p>

            <div class="toggle-content">
                <?php cc_aha_metro_select_markup(); ?>
            </div>
        </div>


    	<?php

	    // Only members who have an "@heart.org" email address (and site admins) are allowed to fill out the assessment 
		$current_user = wp_get_current_user();
    	$email_parts = explode('@', $current_user->user_email);
    	
    	if ( current_user_can( 'delete_others_posts' ) || $email_parts[1] == 'heart.org' ) :
    		?>
		    <a href="/assessment" class="button">Assessment</a>
		<?php endif; ?>
	    <a href="/view-aha-report" class="button">View Report</a>
	    <?php
    }

    public function aha_tab_is_enabled(){

    	if ( cc_aha_is_aha_group() ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
bp_register_group_extension( 'CC_AHA_Extras_Extension' );
 
endif;