<?php 
if ( class_exists( 'BP_Group_Extension' ) ) : // Recommended, to prevent problems during upgrade or when Groups are disabled

class CC_AHA_Extras_Extension extends BP_Group_Extension {

    function __construct() {
        $args = array(
            'slug' => 'assessment',
            'name' => 'AHA Tab Name',
            'visibility' => 'private',
            'enable_nav_item'   => true, //$this->aha_tab_is_enabled(),
            'access' => 'anyone',
            'show_tab' => 'anyone',
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
    	$board_id = get_user_meta( get_current_user_id(), 'aha_board', TRUE );

    	?><details closed>
    	<?php if ( !empty( $board_id ) ) : ?>
    	<summary>Your metro ID is: <?php echo $board_id; ?> </summary>
    	<?php  else : ?>
    	<summary>Please set your metro ID. </summary>
    <?php endif; ?>
    	Blah blah blah. What's all this?


    	</details> 

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
    	if ( ! class_exists('CC_AHA_Extras') )
    		return FALSE;

    	return ( bp_get_current_group_id() == CC_AHA_Extras::aha_id ) ? TRUE : FALSE ;
 
    }
}
bp_register_group_extension( 'CC_AHA_Extras_Extension' );
 
endif;