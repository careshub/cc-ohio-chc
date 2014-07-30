<?php
/**
 * CC American Heart Association Extras
 *
 * @package   CC American Heart Association Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2014 CommmunityCommons.org
 */

class CC_AHA_Extras {

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
	protected $plugin_slug = 'cc-aha-extras';

	/**
	 *
	 * The ID for the AHA group on www.
	 *
	 *
	 *
	 * @since    1.0.0
	 *
	 * @var      int
	 */
	// public static cc_aha_get_group_id();// ( get_home_url() == 'http://commonsdev.local' ) ? 55 : 594 ; //594 on staging and www, 55 on local

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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_registration_styles') );

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		// add_action( '@TODO', array( $this, 'action_method_name' ) );
		// add_filter( '@TODO', array( $this, 'filter_method_name' ) );
		// add_action( 'bp_before_group_request_membership_content', array( $this, 'print_descriptive_text') );
		// add_action('bp_group_request_membership_content', array( $this, 'print_grantee_list' ) );
		// add_filter( 'groups_member_comments_before_save', array( $this, 'append_grantee_comment' ), 25, 2 );

		// Registration form additions
		add_action( 'bp_before_registration_submit_buttons', array( $this, 'registration_section_output' ), 60 );
		add_action( 'bp_core_signup_user', array( $this, 'registration_extras_processing'), 1, 71 );
		// Add "aha" as an interest if the registration originates from an AHA page
		// Filters array provided by registration_form_interest_query_string
		// @returns array with new element (or not)
		add_filter( 'registration_form_interest_query_string', array( $this, 'add_registration_interest_parameter' ), 12, 1 );

        add_filter( 'group_reports_create_new_label', array( $this, 'change_group_create_report_label' ), 2, 12 );

		// Add filter to catch form submission -- both "metro ID" and questionnaire answers
		add_action( 'bp_init', array( $this, 'save_form_submission'), 75 );

		// Add filter to catch form submission -- both "metro ID" and questionnaire answers
		add_action( 'bp_init', array( $this, 'set_metro_id_cookie_on_load'), 22 );

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
		if ( cc_aha_is_component() )
			wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'aha-extras-tab.css', __FILE__ ), array(), self::VERSION );
	}

	public function enqueue_registration_styles() {
	    if( bp_is_register_page() && isset( $_GET['aha'] ) && $_GET['aha'] )
	      wp_enqueue_style( 'aha-section-register-css', plugin_dir_url( __FILE__ ) . 'aha_registration_extras.css', array(), '0.1', 'screen' );
	}

	/**
	 * Register and enqueue public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( cc_aha_is_component() )
			wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'aha-group-pane-js.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}

	/**
	 * Output descriptive text above the request form.
	 *
	 * @since    1.0.0
	 */
	// 
	public function print_descriptive_text() {
		//If this isn't the AHA group or the registration page, don't bother.
		if ( ! cc_aha_is_aha_group() &&
		! ( bp_is_register_page() && ( isset( $_GET['aha'] ) && $_GET['aha'] ) ) )
			return false;

		// echo '<p class="description">The Robert Wood Johnson Foundation is offering access to the Childhood Obesity GIS collaborative group space to all current Childhood Obesity grantees free of charge. Within this space you can create maps, reports and documents collaboratively on the Commons. If you are interested in accessing this collaborative space, select your grant name from the list below. We&rsquo;ll respond with access within 24 hours.</p>';
	}

	// Registration form additions
	function registration_section_output() {
	  if ( isset( $_GET['aha'] ) && $_GET['aha'] ) :
	  ?>
	    <div id="aha-interest-opt-in" class="register-section checkbox">
		    <?php  $avatar = bp_core_fetch_avatar( array(
				'item_id' => cc_aha_get_group_id(),
				'object'  => 'group',
				'type'    => 'thumb',
				'class'   => 'registration-logo',

			) ); 
			echo $avatar; ?>
	      <h4 class="registration-headline">Join the Group: <em>American Heart Association</em></h4>

   	      <?php $this->print_descriptive_text(); ?>
	      
	      <label><input type="checkbox" name="aha_interest_group" id="aha_interest_group" value="agreed" <?php $this->determine_checked_status_default_is_checked( 'aha_interest_group' ); ?> /> Yes, I’d like to request membership in the group.</label>

	      <label for="group-request-membership-comments">Comments for the group admin (optional)</label>
	      <textarea name="group-request-membership-comments" id="group-request-membership-comments"><?php 
	      	if ( isset($_POST['group-request-membership-comments']) )
	      		echo $_POST['group-request-membership-comments'];
	      ?></textarea>

	    </div>
	    <?php
	    endif;
	}

	/**
	* Update usermeta with custom registration data
	* @since 0.1
	*/
	public function registration_extras_processing( $user_id ) {
	  
	  if ( isset( $_POST['aha_interest_group'] ) ) {
	  	// Create the group request
	  	$request = groups_send_membership_request( $user_id, cc_aha_get_group_id() );
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

	    if ( bp_is_groups_component() && cc_aha_is_aha_group() ) {
	    	$interests[] = 'aha';
		}

	    return $interests;
	}

	/**
	 * Changes the label of the "Create New Report" button on the AHA page, since it will go to a different report
	 *
	 * @since    1.0.0
	 */
	public function change_group_create_report_label( $label, $group_id ) {

		if ( ! cc_aha_is_aha_group() )
			return $label; 

		return 'Create an AHA Report';

	}

	/**
	 * Handle form submissions
	 *  
	 * @since   1.0.0
	 * @return  boolean
	 */
	public function save_form_submission() {
		// Fires on bp_init action, so this is a catch-action type of filter.
		// Bail out if this isn't the narrative component.
		if ( ! cc_aha_is_component() )
			return false;

		//Handle saving metro ID from AHA tab form
		if ( bp_is_action_variable( 'save-metro-id', 0 ) ) {

			// Is the nonce good?
			if ( ! wp_verify_nonce( $_REQUEST['set-metro-nonce'], 'cc-aha-set-metro-id' ) )
				return false;

			// Try to save the ID
		    if ( cc_aha_save_metro_ids() ) {
   				bp_core_add_message( __( 'Your board affiliation has been updated.', $this->plugin_slug ) );
		    } else {
				bp_core_add_message( __( 'Your board affiliation could not be updated.', $this->plugin_slug ), 'error' );
		    }

			// Redirect and exit
			bp_core_redirect( wp_get_referer() );

			return false;
		}

		//Handle saving metro ID to cookie for viewing persistence
		if ( bp_is_action_variable( 'set-metro-id-cookie', 0 ) ) {

			// Is the nonce good?
			if ( ! wp_verify_nonce( $_REQUEST['set-metro-cookie-nonce'], 'cc-aha-set-metro-id-cookie' ) )
				return false;

			// Create the cookie
			if ( isset( $_POST['aha_metro_id_cookie'] ) )
				setcookie( 'aha_active_metro_id', $_POST['aha_metro_id_cookie'], 0, '/' );

			// Redirect and exit
			bp_core_redirect( wp_get_referer() );

			return false;
		}

		// Handle questionnaire form saves
		if ( bp_is_action_variable( 'update-assessment', 0 ) ) {
			// Is the nonce good?
			if ( ! wp_verify_nonce( $_REQUEST['set-aha-assessment-nonce'], 'cc-aha-assessment' ) )
				return false;

			$page = bp_action_variable(1);

			
			
			
			// Try to save the ID
		    if ( cc_aha_update_form_data() ) {
   				bp_core_add_message( __( 'This form been updated.', $this->plugin_slug ) );
		    } else {
				bp_core_add_message( __( 'This form could not be updated.', $this->plugin_slug ), 'error' );
		    }

			// Redirect to the appropriate page of the form
			bp_core_redirect( $this->after_save_get_form_page_url( $page ) );
			
		}
	}

	/**
	 * Determine the correct page to redirect the user to after a form page save
	 *  
	 * @since   1.0.0
	 * @return  string - url
	 */
	public function after_save_get_form_page_url( $page ){
			// From $_POST, we know whether the user clicked "continue" or "return to toc" and the form page number
			if ( isset( $_POST['submit-survey-to-toc'] ) ) {
				$url = cc_aha_get_survey_permalink( 1 );
			} else if ( $page == cc_aha_get_max_page_number() ) {
				bp_core_add_message( __( 'Thank you for completing the assessment.', $this->plugin_slug ) );
				$url = cc_aha_get_survey_permalink( 1 );
			} else {
				$url = cc_aha_get_survey_permalink( ++$page );
			}

		return $url;
	}

	/**
	 * Handle form submissions - checkbox fields & yes/no radios
	 *  
	 * @since   1.0.0
	 * @return  boolean
	 */
	public function save_boolean_fields( $metro_id, $fields ){
		foreach ($fields as $field) {
			// If checked, enter 1 in the field
			if ( isset( $_POST['$field'] ) && !empty( $_POST['$field'] ) ) {
				$success = update_aha_field( $metro_id, $field, 1 );
			} else {
				$success = update_aha_field( $metro_id, $field, 0 ); 
			}
			
		}

	}
	/**
	 * Handle form submissions - radio fields NOT BOOLEAN
	 *  
	 * @since   1.0.0
	 * @return  boolean
	 */
	public function save_radio_fields( $metro_id, $fields ){
		foreach ($fields as $field) {
			// If marked "yes" enter 1 in the field
			if ( isset( $_POST['$field'] ) && !empty( $_POST['$field'] ) ) {
				$success = update_aha_field( $metro_id, $field, 1 );
			} else {
				$success = update_aha_field( $metro_id, $field, 0 ); 
			}
			
		}
	}
	/**
	 * Handle form submissions - text fields
	 *  
	 * @since   1.0.0
	 * @return  boolean
	 */
	public function save_text_fields( $metro_id, $fields ){
		foreach ($fields as $field) {
			if ( isset( $_POST['$field'] ) && !empty( $_POST['$field'] ) ) {
				$input = sanitize_text_field( $_POST[ $field ] );

				// update_aha_field( $metro_id, $field, $input )

			}
			
		}
		
	}

	/**
	 * Checks existing metro ID cookie value and tries to gracefully set cookie value for Metro ID on page load.
	 *  
	 * @since   1.0.0
	 * @return  none, creates cookie
	 * @uses 	setcookie(), reset(), wp_redirect()
	 */
	public function set_metro_id_cookie_on_load() {
		// Only needed on the AHA tab, and only for logged-in users. (User has to be logged in to reach AHA tab, though. So we'll let BP handle that.)
		if ( ! cc_aha_is_component() )
			return;

		$cookie_name = 'aha_active_metro_id';
	    // We need to know the user's affiliations
	    $selected_metro_ids = cc_aha_get_array_user_metro_ids();
	    $redirect = false;

        // Cookie is set, we check that it's a valid value, if not, unset it.
        // Most common case for this is user changes affiliations, so "active" metro ID is no longer applicable
	    if ( ! empty( $_COOKIE[ $cookie_name ] ) && ! in_array( $_COOKIE[ $cookie_name ], $selected_metro_ids ) ) {
        	// Cookie path must match the cookie we're trying to unset
            setcookie( $cookie_name, '', time()-3600, '/' );
            // Remove it from the $_COOKIE array, too, so the following action will fire.
            unset( $_COOKIE[ $cookie_name ] );
			$redirect = true;	           
	    }

	    // If cookie doesn't exist (or we just deleted it above), we try to set it.
	    // If user has only one affiliation, we can set the cookie
	    if ( empty( $_COOKIE[ $cookie_name ] ) && count( $selected_metro_ids ) == 1  ){
            setcookie( $cookie_name, reset( $selected_metro_ids ), 0, '/' );
			$redirect = true;	
        }

        if ( $redirect )
        	wp_redirect( wp_get_referer() );


	}
} // End class