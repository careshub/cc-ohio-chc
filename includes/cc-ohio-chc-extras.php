<?php
/**
 * CC Creating Healthy Communities Ohio Extras
 *
 * @package   CC Creating Healthy Communities Ohio Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2015 CommmunityCommons.org
 */

class CC_Ohio_CHC_Extras {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'cc-ohio-chc-extras';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		// add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add filter to catch removal of a story from a group
		// add_action( 'bp_init', array( $this, 'remove_story_from_group'), 75 );

		// Activate plugin when new blog is added
		// add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_registration_styles') );

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		// add_action( '@TODO', array( $this, 'action_method_name' ) );
		// add_filter( '@TODO', array( $this, 'filter_method_name' ) );
		// add_action( 'bp_before_group_request_membership_content', array( $this, 'print_descriptive_text') );
		// add_action('bp_group_request_membership_content', array( $this, 'print_grantee_list' ) );
		// add_filter( 'groups_member_comments_before_save', array( $this, 'append_grantee_comment' ), 25, 2 );

		// Add "ohio_chc" as an interest if the registration originates from an Ohio CHC page
		// Filters array provided by registration_form_interest_query_string
		// @returns array with new element (or not)
		add_filter( 'registration_form_interest_query_string', array( $this, 'add_registration_interest_parameter' ), 12, 1 );

		// Registration form additions - These all rely on ?aha=1 being appended to the register url.
		add_action( 'bp_before_account_details_fields', array( $this, 'registration_form_intro_text' ), 60 );
		add_action( 'bp_before_registration_submit_buttons', array( $this, 'registration_section_output' ), 60 );
		add_action( 'bp_core_signup_user', array( $this, 'registration_extras_processing'), 1, 71 );
		
		//TODO?
		// BuddyPress redirects logged-in users away from the registration page. Catch that request and redirect requests that include the AHA parameter to the AHA group.
        add_filter( 'bp_loggedin_register_page_redirect_to', array( $this, 'loggedin_register_page_redirect_to' ) );

        // If a user with an @heart email address makes a request, approve it automatically
        add_action( 'groups_membership_requested', array( $this, 'approve_member_requests' ), 12, 4 );
		
		//Register Custom post types and taxonomies associated with this plugin
		add_action( 'init', array( $this, 'register_cpt_odh_chc_entry' ) );

		// AJAX functions would go here
		//add_action( 'wp_ajax_save_board_approved_priority' , array( $this, 'save_board_approved_priority' ) );
		

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if ( cc_ohio_chc_is_component() ) {
			//echo 'etf';
			wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/cc-ohio-chc-extras-tab.css', __FILE__ ), array(), '1.32' );
		}
	}

	/**
	 * Register and enqueue public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( cc_ohio_chc_is_component() ) {
			wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/cc-ohio-chc-extras-js.js', __FILE__ ), array( 'jquery' ), self::VERSION );
			wp_localize_script( 
				$this->plugin_slug . '-plugin-script', 
				'ohio_chc_ajax',
				array( 
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'ajax_nonce' => wp_create_nonce( 'cc_ohio_chc_ajax_nonce' )
				)
			);
		}

	}

	/**
	 * Output descriptive text above the request form.
	 *
	 * @since    1.0.0
	 */
	// 
	public function print_descriptive_text() {
		//If this isn't the Ohio CHC group or the registration page, don't bother.
		if ( ! cc_ohio_chc_is_ohio_chc_group() &&
		! ( bp_is_register_page() && ( isset( $_GET['ohio-chc'] ) && $_GET['ohio-chc'] ) ) )
			return false;

		// echo '<p class="description">The Robert Wood Johnson Foundation is offering access to the Childhood Obesity GIS collaborative group space to all current Childhood Obesity grantees free of charge. Within this space you can create maps, reports and documents collaboratively on the Commons. If you are interested in accessing this collaborative space, select your grant name from the list below. We&rsquo;ll respond with access within 24 hours.</p>';
	}

	// Registration form additions
	function registration_form_intro_text() {
	  if ( isset( $_GET['ohio-chc'] ) && $_GET['ohio-chc'] ) :
	  ?>
	    <p class="">
		  If you are already a Community Commons member, simply visit the <a href="<?php 
    		echo bp_get_group_permalink( groups_get_group( array( 'group_id' => cc_ohio_chc_get_group_id() ) ) );
    	?>">Creating Healthy Communities – Ohio group</a> to get started. 
	    </p>
	    <?php
	    endif;
	}

	function registration_section_output() {
	  if ( isset( $_GET['ohio-chc'] ) && $_GET['ohio-chc'] ) :
	  ?>
	    <div id="ohio-chc-interest-opt-in" class="register-section checkbox">
		    <?php  $avatar = bp_core_fetch_avatar( array(
				'item_id' => cc_ohio_chc_get_group_id(),
				'object'  => 'group',
				'type'    => 'thumb',
				'class'   => 'registration-logo',

			) ); 
			echo $avatar; ?>
	      <h4 class="registration-headline">Join the Group: <em>Creating Healthy Communities – Ohio</em></h4>

   	      <?php $this->print_descriptive_text(); ?>
	      
	      <label><input type="checkbox" name="ohio_chc_interest_group" id="ohio_chc_interest_group" value="agreed" <?php $this->determine_checked_status_default_is_checked( 'ohio_chc_interest_group' ); ?> /> Yes, I’d like to request membership in the group.</label>

	      <label for="group-request-membership-comments">Comments for the group admin (optional)</label>
	      <textarea name="group-request-membership-comments" id="group-request-membership-comments"><?php 
	      	if ( isset($_POST['group-request-membership-comments']) )
	      		echo $_POST['group-request-membership-comments'];
	      ?></textarea>

	    </div>
	    <?php
	    endif;
	}

	function loggedin_register_page_redirect_to( $redirect_to ) {
	  	if ( isset( $_GET['ohio-chc'] ) && $_GET['ohio-chc'] ) {
	  		$redirect_to = bp_get_group_permalink( groups_get_group( array( 'group_id' => cc_ohio_chc_get_group_id() ) ) );
	  	}

	  	return $redirect_to;
	}
	/**
	* Accept requests that come from members with @heart.org email addresses
	* @since 0.1
	*/
	function approve_member_requests( $user_id, $admins, $group_id, $membership_id ) {

		//TODO?

	}

	/**
	* Update usermeta with custom registration data
	* @since 0.1
	*/
	public function registration_extras_processing( $user_id ) {
	  
	  if ( isset( $_POST['ohio_chc_interest_group'] ) ) {
	  	// Create the group request
	  	$request = groups_send_membership_request( $user_id, cc_ohio_chc_get_group_id() );
	  }
	  
	  return $user_id;
	}

	public function determine_checked_status_default_is_checked( $field_name ){
		  // In its default state, no $_POST should exist. If this is a resubmit effort, $_POST['signup_submit'] will be set, then we can trust the value of the checkboxes.
		  if ( isset( $_POST['signup_submit'] ) && !isset( $_POST[ $field_name ] ) ) {
		    // If the user specifically unchecked the box, don't make them do it again.
		  } else {
		    // Default state, $_POST['signup_submit'] isn't set. Or, it is set and the checkbox is also set.
		    echo 'checked="checked"';
		  } 
	}
	public function add_registration_interest_parameter( $interests ) {

	    if ( bp_is_groups_component() && cc_ohio_chc_is_ohio_chc_group() ) {
	    	$interests[] = 'ohio-chc';
		}

	    return $interests;
	}

	/**
	 * Save county as user meta
	 * Saves selection as atring in usermeta table
	 * 
	 * @since   1.0.0
	 * @return  boolean
	 */
	function save_user_county( $user_id, $county){
		//TODO: this?
	    $success = update_user_meta( $user_id, 'ohio_chc_county', $county );
	    
	    return $success;
	}

	/**
	 * Determine the correct page to redirect the user to after a form page save
	 *  
	 * @since   1.0.0
	 * @return  string - url
	 */
	public function after_save_get_form_page_url( $page ){
			// From $_POST, we know whether the user clicked "continue" or "return to toc" and the form page number
			/*if ( isset( $_POST['submit-survey-to-toc'] ) ) {
				$url = cc_aha_get_survey_permalink( 1 );
			} else if ( isset( $_POST['submit-revenue-analysis-to-toc'] ) ) {
				$url = cc_aha_get_analysis_permalink( 'revenue' );
			} else if ( $page == cc_aha_get_max_page_number() ) {
				bp_core_add_message( __( 'Thank you for completing the assessment.', $this->plugin_slug ) );
				$url = cc_aha_get_survey_permalink( 1 );
			} else {
				$url = cc_aha_get_survey_permalink( ++$page );
			}*/

		//return $url;
	}


	public function register_cpt_odh_chc_entry() {

		$labels = array( 
			'name' => _x( 'ODH_CHC Entries', 'odh_chc_entry' ),
			'singular_name' => _x( 'ODH_CHC Entry', 'odh_chc_entry' ),
			'add_new' => _x( 'Add New', 'odh_chc_entry' ),
			'all_items' => _x( 'ODH_CHC Entries', 'odh_chc_entry' ),
			'add_new_item' => _x( 'Add New ODH_CHC Entry', 'odh_chc_entry' ),
			'edit_item' => _x( 'Edit ODH_CHC Entry', 'odh_chc_entry' ),
			'new_item' => _x( 'New ODH_CHC Entry', 'odh_chc_entry' ),
			'view_item' => _x( 'View ODH_CHC Entry', 'odh_chc_entry' ),
			'search_items' => _x( 'Search ODH_CHC Entries', 'odh_chc_entry' ),
			'not_found' => _x( 'No odh_chc entries found', 'odh_chc_entry' ),
			'not_found_in_trash' => _x( 'No odh_chc entries found in Trash', 'odh_chc_entry' ),
			'parent_item_colon' => _x( 'Parent ODH_CHC Entry:', 'odh_chc_entry' ),
			'menu_name' => _x( 'ODH_CHC Entries', 'odh_chc_entry' ),
		);

		$args = array( 
			'labels' => $labels,
			'hierarchical' => false,
			'public' => true,
			'show_ui' => true,
			'supports' => array( 'title', 'editor', 'custom-fields', 'page-attributes', 'author', 'excerpt' ),   
			'show_in_menu' => true
		);

		register_post_type( 'odh_chc_entry', $args );
	}


	
} // End class