<?php
/**
 * CC Creating Healthy Communities Ohio Extras
 *
 * @package   CC Creating Healthy Communities Ohio Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2015 CommmunityCommons.org
 */

/**
 * Are we on the Ohio CHC extras tab?
 *
 * @since   1.0.0
 * @return  boolean
 */
function cc_ohio_chc_is_component() {
    if ( bp_is_groups_component() && bp_is_current_action( cc_ohio_chc_get_slug() ) )
        return true;

    return false;
}

/**
 * Is this the Ohio CHC group?
 *
 * @since    1.0.0
 * @return   boolean
 */
function cc_ohio_chc_is_ohio_chc_group(){
    return ( bp_get_current_group_id() == cc_ohio_chc_get_group_id() );
}

/**
 * Get the group id based on the context
 *
 * @since   1.0.0
 * @return  integer
 */
function cc_ohio_chc_get_group_id(){
    switch ( get_site_url( null, '', 'http' ) ) {
        case 'http://localhost/wordpress':
            $group_id = 596;
            break;
		case 'http://localhost/cc_local':
            $group_id = 633; //599
            break;
        case 'http://dev.communitycommons.org':
        default: //live site
            $group_id = 633;
            break;
    }
    return $group_id;
}

/**
 * Get various slugs
 * These are gathered here so when, inevitably, we have to change them, it'll be simple
 *
 * @since   1.0.0
 * @return  string
 */
function cc_ohio_chc_get_slug(){
    return 'ohio-chc-assessment';
}
function cc_ohio_chc_get_form_slug(){
    return 'forms';
}
function cc_ohio_chc_get_report_slug(){
	return 'reports';
}
function cc_ohio_chc_get_county_slug(){
	return 'county-assignment';
}
function cc_ohio_chc_get_form_num_slug( $formnum = 1 ){
	return cc_ohio_chc_get_form_slug() . '/' . $formnum;
}
function cc_ohio_chc_get_report_num_slug( $formnum = 1 ){
	return cc_ohio_chc_get_report_slug() . '/' . $formnum;
}

/**
 * Get URIs for the various pieces of this tab
 *
 * @return string URL
 */
function cc_ohio_chc_get_home_permalink( $group_id = false ) {
    $group_id = ( $group_id ) ? $group_id : bp_get_current_group_id() ;
    $permalink = bp_get_group_permalink( groups_get_group( array( 'group_id' => $group_id ) ) ) .  cc_ohio_chc_get_slug() . '/';
    return apply_filters( "cc_ohio_chc_home_permalink", $permalink, $group_id);
}
function cc_ohio_chc_get_assessment_permalink( $page = 1, $group_id = false ) { //TODO: what is this?
    $permalink = cc_ohio_chc_get_home_permalink( $group_id ) . cc_ohio_chc_get_form_slug() . '/';
    return apply_filters( "cc_ohio_chc_get_assessment_permalink", $permalink, $group_id);
}
function cc_ohio_chc_get_main_form_permalink( $page = 1, $group_id = false ) {
    $permalink = cc_ohio_chc_get_home_permalink( $group_id ) . cc_ohio_chc_get_form_slug() . '/';
    return apply_filters( "cc_ohio_chc_get_assessment_permalink", $permalink, $group_id);
}
function cc_ohio_chc_get_report_permalink( $formnum = 1, $group_id = false ) {
    $permalink = cc_ohio_chc_get_home_permalink( $group_id ) . cc_ohio_chc_get_report_slug() . '/' . $formnum ;
    return apply_filters( "cc_ohio_chc_get_report_permalink", $permalink, $group_id);
}
function cc_ohio_chc_get_county_assignment_permalink( $group_id = false ) {
    $permalink = cc_ohio_chc_get_home_permalink( $group_id ) . cc_ohio_chc_get_county_slug() . '/';
    return apply_filters( "cc_ohio_chc_get_county_assignment_permalink", $permalink, $group_id);
}
function cc_ohio_chc_get_form_permalink( $formnum = 1, $group_id = false ) {
    $permalink = cc_ohio_chc_get_home_permalink( $group_id ) . cc_ohio_chc_get_form_slug() . '/' . $formnum ;
    return apply_filters( "cc_ohio_chc_get_form_permalink", $permalink, $group_id);
}


/**
 * Can this user fill out the assessment and such?
 *
 * @return boolean
 */
function cc_ohio_chc_user_can_do_assessment(){
    // TODO: this is where we'll figure out user assignments for a particular county?  Maybe?



    return false;
}

function cc_ohio_chc_resolve_county(){
    // TODO: this, if function needed..

}

/**
 * Where are we?
 * Checks for the various screens
 *
 * @since   1.0.0
 * @return  string
 */
function cc_ohio_chc_on_main_screen(){
    // There should be no action variables if on the main tab
    if ( cc_ohio_chc_is_component() && ! ( bp_action_variables() )  ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_assessment_screen(){ //what is this??
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( cc_ohio_chc_get_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_report_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( cc_ohio_chc_get_report_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_form_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_county_assignment_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( cc_ohio_chc_get_county_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}

function cc_ohio_chc_on_form1_screen(){
	//var_dump( bp_action_variable(0));
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '1', 1 ) && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_form2_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '2', 1 ) && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_form3_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '3', 1 ) && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_form4_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '4', 1 ) && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_form5_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '5', 1 ) && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_form6_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '6', 1 ) && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_form7_screen(){
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '7', 1 ) && bp_is_action_variable( cc_ohio_chc_get_form_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}

function cc_ohio_chc_on_reportform1_screen(){
	//var_dump( bp_action_variable(0));
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '1', 1 ) && bp_is_action_variable( cc_ohio_chc_get_report_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}
function cc_ohio_chc_on_reportform2_screen(){
	//var_dump( bp_action_variable(0));
    if ( cc_ohio_chc_is_component() && bp_is_action_variable( '2', 1 ) && bp_is_action_variable( cc_ohio_chc_get_report_slug(), 0 ) ){
        return true;
    } else {
        return false;
    }
}

/**
 * Retrieve a user's county affiliation
 *
 * @since   1.0.0
 * @return  string
 */
function cc_ohio_chc_get_user_county() {
    $selected = get_user_meta( get_current_user_id(), 'ohio_chc_county', true );

    return $selected;
}

/*
 * Returns array of members of Ohio CHC Group
 *
 * @params int Group_ID
 * @return array Array of Member ID => name
 */
function cc_ohio_chc_get_member_array( ){

	global $bp;
	$group_id = cc_ohio_chc_get_group_id();

	$group = groups_get_group( array( 'group_id' => $group_id ) );
	//var_dump($group);

	//set up group member array for drop downs
	$group_members = array();
	if ( bp_group_has_members( array( 'group_id' => $group_id, 'per_page' => 9999 ) ) ) {

		//iterate through group members, creating array for form list (drop down)
		while ( bp_group_members() ) : bp_group_the_member();
			$group_members[bp_get_group_member_id()] = bp_get_group_member_name();
		endwhile;

		//var_dump ($group_members);  //works!
	}

	return $group_members;

}

/*
 * Returns array of counties in Ohio
 *
 * @return array Array of county names
 */
function cc_ohio_chc_get_county_array( ){

	$counties = array(
			"Adams County",
			"Allen County",
			"Athens County",
			"Clark County",
			"Cuyahoga County",
			"Delaware County",
			"Franklin County",
			"Hamilton County",
			"Knox County",
			"Licking County",
			"Lorain County",
			"Lucas County",
			"Marion County",
			"Meigs County",
			"Montgomery County",
			"Perry County",
			"Richland County",
			"Sandusky County",
			"Stark County",
			"Summit County",
			"Trumbull County",
			"Union County",
			"Washington County"
		);

	return $counties;

}

/*
 * Checks whether current user has county assigned to them
 *	TODO: admin check
 *
 * @return bool
 */
function current_user_has_county() {
	//who is the current user?
	$user_id = get_current_user_id();

	//does the current user have a county assigned to them?
	$user_county_meta = get_user_meta( $user_id, 'cc-ohio-user-county', false);

	//empty string returned if no meta found
	if( $user_county_meta == "" ){
		return false;
	} else {
		return $user_county_meta;
	}
}

/*
 * Gets county assignment of user
 *	TODO:
 *
 * @param int User ID
 * @return string County
 */
function get_user_county( $user_id = 0 ) {

	//if incoming user_id not set, use current user id
	if ( $user_id == 0 ){
		$user_id = get_current_user_id();
	}

	//does the current user have a county assigned to them?
	$user_county_meta = get_user_meta( $user_id, 'cc-ohio-user-county', true);
	//var_dump ($user_county_meta);

	return $user_county_meta;
}

/*
 * Get a form for a particular user, with admin check (or in the call)?
 * 	if no form returned, show new one of number type
 *
 */
function cc_ohio_chc_get_county_entry_by_form_number( $form_num, $user_id = 0 ){

	//GF form number lookup
	//$gf_form_num = cc_ohio_chc_get_form_num( $form_num );
	$gf_form_num = $form_num;

	//if incoming user_id not set, use current user id
	if ( $user_id == 0 ){
		$user_id = get_current_user_id();
	}

	//find user assigned to county
	$user_county = get_user_county( $user_id ); //"Montgomery County"
	//var_dump( $user_county);

	//var_dump( $user_county);
	$county_field_name = "cc_ohio_county";

	$total_count = 1;

	//Get the field id for cc_ohio_county (for this form) arg.
	$form_obj = GFAPI::get_form( $gf_form_num );

	$field_id = get_gf_field_id_by_label( $form_obj, $county_field_name ); //since we have to query GF by field ID
	//var_dump( $field_id );

	//if there's an entry from this county, populate the form
	$search_criteria["field_filters"][] = array('key' => $field_id, 'value' => $user_county);
	$entry_this_county = GFAPI::get_entries( $gf_form_num, $search_criteria, null, null, $total_count );

	//var_dump ( $entry_this_county );

	// If no user assigned to county, get new GF form of gf_form_num and prepopulate county field
	if( $entry_this_county == NULL ){
		return NULL;

	} else {
		return $entry_this_county;
	}



}

/*
 * Form lookup; which form for which environment?
 *	TODO: update this list as forms created
 *
 */
function cc_ohio_chc_get_form_num( $form_num = 1 ){
	//TODO: fill in as we create on locals and devs
	$site_url = get_site_url( null, '', 'http' );

	switch ( $site_url ) {
        case 'http://localhost/wordpress':
			switch( $form_num ){
				case 1:
					return 28;
					break;
				case 2:
					return 32;
					break;
				case 3:
					return 25;
					break;
				case 4:
					return 26;
					break;
				case 5:
					return 27;
					break;
				case 6:
					return 30;
					break;
				case 7:
					return 29;
					break;
				default:
					return 28;
					break;
			}
            break;
		case 'http://localhost/cc_local':
			switch( $form_num ){
				case 1:
					return 30;
					break;
				case 2:
					return 32;
					break;
				default:
					return 30;
					break;
			}
            break;
        case 'http://dev.communitycommons.org':
        case 'http://staging.communitycommons.org':
        case 'http://www.communitycommons.org':
        default:
			switch( $form_num ){
				case 1:
					return 33;
					break;
				case 2:
					return 38;
					break;
				case 3:
					return 30;
					break;
				case 4:
					return 31;
					break;
				case 5:
					return 32;
					break;
				case 6:
					return 36;
					break;
				case 7:
					return 35;
					break;
				default:
					return 33;
					break;
			}
            break;
    }
    return $gf_form_num;

}

/*
 * Form lookup; get array of ohio forms (except user-county) by environment
 *	TODO: update this list as forms created
 *
 */
function cc_ohio_chc_get_gf_forms_all( ){


	//TODO: fill in as we create on locals and devs
	 switch ( get_site_url( null, '', 'http' ) ) {
        case 'http://localhost/wordpress':
			//TODO: Mike, fill in your gf form numbers
			$form_array = array( 8, 15, 16, 18, 19, 20, 23 );
            break;
		case 'http://localhost/cc_local':
			$form_array = array(30, 32, 33, 34, 35, 36);
            break;
        case 'http://dev.communitycommons.org':

			$form_array = array( 38, 48, 49, 50, 51, 44, 46 );
            break;
        default: //live site

			$form_array = array( 30, 31, 32, 33, 36, 38 );
            break;
    }
    return $form_array;

}


/*
 * User-county assignment Form lookup; which form for which environment?
 *	TODO: update this list as forms created
 *
 */
function cc_ohio_chc_get_user_county_form_num(){
	//TODO: fill in as we create on locals and devs
	switch ( get_site_url( null, '', 'http' ) ) {
        case 'http://localhost/wordpress':
			return 24;
		case 'http://localhost/cc_local':
			return 37;
        case 'http://dev.communitycommons.org':
            return 45;

        default: //live site
            return 37;
    }
}

/*
 * Get GF's field id (what we have to query on, arg) for a form with a label
 *
 * @param form object, string
 * @return int Field ID
 */
function get_gf_field_id_by_label( $form_obj, $label_name ){

	foreach( $form_obj['fields'] as $key => $field ) {
		//var_dump( $key, $field['label']);
		if( $field['label'] == $label_name ){
			return($field['id']);

		}
	}

	return NULL;


}

function cc_ohio_chc_is_stickyform_active() {
	/**
	 * Detect plugin. For use on Front End only.
	 */
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	// check for plugin using plugin name
	if ( is_plugin_active( 'gravity-forms-sticky-list/sticky-list.php' ) ) {
	  //plugin is activated
		return true;
	} else {
		return false;
	}
}

function cc_ohio_county_results() {
?>
<script type="text/javascript">
	function checkAll(formname, checktoggle)
	{
	  var checkboxes = new Array();
	  checkboxes = document[formname].getElementsByTagName('input');

	  for (var i=0; i<checkboxes.length; i++)  {
		if (checkboxes[i].type == 'checkbox')   {
		  checkboxes[i].checked = checktoggle;
		}
	  }
	}
	function printContent(el) {
		var restorepage = document.body.innerHTML;
		var printcontent = document.getElementById(el).innerHTML;
		document.body.innerHTML = printcontent;
		window.print();
		document.body.innerHTML = restorepage;
	}
</script>

<?php
	$counties = cc_ohio_chc_get_county_array();
	$newurl = '/PHPExcel/Examples/oh-county-new-xls.php';
?>
	<form id="form1" name="form1" method="post" action="">
		<strong>Select County:</strong><br /><br />
		<div style="margin-left:20px;">
			<select name="county">
			<?php
				if ($_POST["county"]) {
					echo "<option value='" . $_POST["county"] . "'>" . $_POST["county"] . "</option>";
				} else {
					echo "<option value=''>---Select County---</option>";
				}
				foreach($counties as $key => $value):
				echo '<option value="' . $value . '">' . $value . '</option>';
				endforeach;
				?>
			</select>
		</div>
		<br /><br />
		<?php
			if ($_POST["chk_group"]) {
				$reportarr = $_POST["chk_group"];
			} else {
				$reportarr = array();
			}
		?>
		<strong style="display:none;">Select forms to include in report:</strong><br /><br />
		<div style="margin-left:20px;display:none;">
			<a onclick="javascript:checkAll('form1', true);" href="javascript:void();">check all</a>&nbsp;
			<a onclick="javascript:checkAll('form1', false);" href="javascript:void();">uncheck all</a>
			<br /><br />
			<input class="checkbox1" type="checkbox" name="chk_group[]" value="33" checked="checked" />General<br />
			<input class="checkbox1" type="checkbox" name="chk_group[]" value="38" checked="checked" />Active Living<br />
			<input class="checkbox1" type="checkbox" name="chk_group[]" value="30" checked="checked" />Healthy Eating<br />
			<input class="checkbox1" type="checkbox" name="chk_group[]" value="31" checked="checked" />Tobacco<br />
			<input class="checkbox1" type="checkbox" name="chk_group[]" value="32" checked="checked" />Supplemental<br />
			<input class="checkbox1" type="checkbox" name="chk_group[]" value="35" checked="checked" />Success Stories<br /><br />
		</div>
		<input id="form1_submit" type="submit" value="Submit" />
	</form>
	<br /><br />
<?php
	if ($_POST["county"]) {
	$countyform="";
	$titles="";
?>
	<form id="form2" name="form2" method="post" action="<?php echo $newurl; ?>">


<?php
		//echo "<div id='printdiv'>";
		//echo "<span style='font-size:18pt;font-weight:bold;color:#000080;'>" . $_POST["county"] . " Report</span><hr />";

		if(!empty($_POST['chk_group'])) {

			$keysarray = $_POST['chk_group'];

			$keycount = 0;
			$noentries;
			foreach ($keysarray as $k) {

				$startform = GFFormsModel::get_form_meta( $k );
				//var_dump($startform['title']);
				if (strpos($startform['title'],'-') !== false) {
					$titlearr = explode("-",$startform['title']);

					$titles = $titles . trim($titlearr[2]) . "|";
				}
				foreach( $startform['fields'] as $startfield ) {
					if ($startfield['label'] == 'cc_ohio_county') {
						$countykey = $startfield['id'];

					}
				}

				$search_criteria = array();
				$search_criteria["field_filters"][] = array( 'key' => $countykey, 'value' => $_POST["county"] );
				$entries = GFAPI::get_entries( $k, $search_criteria );
				//print_r($search_criteria);
				//var_dump($entries);

				if (empty($entries)) {
					//echo "NO ENTRIES";
					$noentries = $noentries . $keycount . "|";
				}
				$keycount = $keycount + 1;

				$entry = $entries[0];
				foreach ($entry as $entrykey => $entryval) {
					$entryid = 0;
					if ($entrykey == 'id') {
						$entryid = $entryval;
					}
					if ($entryid > 0) {
						$lead = RGFormsModel::get_lead( $entryid );
						$form = GFFormsModel::get_form_meta( $lead['form_id'] );
						//echo "<span style='font-size:14pt;font-weight:bold;'>" . $form['title'] . "</span><br /><br />";
						$countyform = $countyform . $form['title'] . "|";
						//var_dump($form);

							foreach( $form['fields'] as $field ) {
								if ($field['label'] != 'cc_ohio_county' && $field['label'] != 'cc_ohio_update_entry_id' && $field['label'] != 'cc_ohio_year') {

									if ($field['type'] == 'section') {
										//echo "<br /><strong>" . $field['label'] . "</strong><br />";
										$countyform = $countyform . "SECTION_" . $field['label'] . "|";
									} elseif ( $field['type'] == 'html') {
										$newhtml = str_replace('<br />', ' ', $field['content']);
										$newhtml = str_replace('font-size:16pt;', 'font-size:12pt;', $newhtml);
										//echo "<br /><span>" . $newhtml . "</span><br />";
										$countyform = $countyform . "HTML_" . $newhtml . "|";
									} elseif ($field['type'] == 'textarea') {
										$lastchr = substr($field['label'], -1);
										$fieldlbl = "";
										if ($lastchr == ":") {
											$fieldlbl = $field['label'];
										} else {
											$fieldlbl = $field['label'] . ":";
										}
										//echo "<span style='margin-left: 20px;'>" . $fieldlbl . $lead[ $field['id'] ] . "</span><br />";
										$countyform = $countyform . "TEXTAREA_" . $fieldlbl . $lead[ $field['id'] ] . "|";
									} elseif ($field['type'] == 'text') {
										$lastchr = substr($field['label'], -1);
										$fieldlbl = "";
										if ($lastchr == ":") {
											$fieldlbl = $field['label'];
										} else {
											$fieldlbl = $field['label'] . ":";
										}
										//echo "<span style='margin-left: 20px;'>" . $fieldlbl . $lead[ $field['id'] ] . "</span><br />";
										$countyform = $countyform . "TEXT_" . $fieldlbl . $lead[ $field['id'] ] . "|";
									} else {
										$lastchr = substr($field['label'], -1);
										$fieldlbl = "";
										if ($lastchr == ":") {
											$fieldlbl = $field['label'];
										} else {
											$fieldlbl = $field['label'] . ":";
										}
										//echo $field['type'] . "<br />";

										$reslt = $lead[ $field['id'] ];
										if (empty($reslt)) {
											$reslt = 0;
										}

										//echo "<span style='margin-left: 20px;'>" . $fieldlbl . $reslt . "</span><br />";
										$countyform = $countyform . $fieldlbl . $reslt . "|";
									}
								}
							}

					}

				}


				$countyform = str_replace("'","",$countyform);
				//echo "<br /><br />";
			}

			//var_dump($titles);
			if (!empty($noentries)) {
				//var_dump($titles);
				$noentries = substr_replace($noentries ,"", -1);

				if (strpos($noentries,'|') !== false) {
					$noearr = explode("|", $noentries);
					foreach ($noearr as $noe) {
						$titlecount = 0;
						//var_dump($titles);
						$titlearr = explode("|", $titles);
						foreach ($titlearr as $ti) {
							if ($titlecount == floatval($noentries)) {
								$titles = str_replace($ti . "|", "", $titles);
							}
							$titlecount = $titlecount + 1;
						}

					}
				} else {
					$titlecount = 0;
					//var_dump($titles);
					$titlearr = explode("|", $titles);
					foreach ($titlearr as $ti) {
						if ($titlecount == floatval($noentries)) {
							$titles = str_replace($ti . "|", "", $titles);
						}
						$titlecount = $titlecount + 1;
					}
					//unset($titles[floatval($noentries)]);
				}

			}

			//var_dump($titles);
			//var_dump($countyform);
			//echo "<div style='display:none;'>County Form<input type='hidden' name='countyform' value='" . $countyform . "/></div>";
		}

		$current_user = wp_get_current_user();
		$email = $current_user->user_email;
		if ($email == "barbarom@missouri.edu" || $email == "illmo76@yahoo.com") {
			//var_dump($countyform);
			//var_dump(rtrim($titles,"|"));
		}


?>
			<div style='display:none;'>
				<input type='hidden' name='countyform' value='<?php echo $countyform ?>' />
				<input type='hidden' name='formlist' value='<?php echo rtrim($titles,"|") ?>' />
				<input type='hidden' name='county' value='<?php echo $_POST["county"] ?>' />
				<input type='hidden' name='email' value='<?php echo $email ?>' />
			</div>
			</div>

				<input id="form2_submit" type="submit" value="Export to Excel" style="font-size:18pt;" />

			</form>
			<br /><br />
<?php
	}


}



function cc_ohio_program_data_summary() {
?>
	This process creates one Excel file of aggregated county data by topic (worksheet). <strong>PLEASE NOTE: The first 5 worksheets hold the aggregate data.</strong> The remainder of the worksheets come directly from each county.<br /><br />
	<span style="font-style:italic;color:red;font-weight:bold;">This process will take several minutes to complete. Please be patient and allow your browser to run until the process is complete!</span><br /><br />
	<form id="summarizeform" name="summarizeform" action="" method="post" onsubmit="return confirm('This process will take several minutes to complete. Please be patient and allow your browser to run until the process is complete!')">
		<input id="sum_submit" name="sum_submit" type="submit" value="Run Script to Aggregate County Data" />
	</form>
	<br /><br />
<?php
if ($_POST['sum_submit']) {
	$counties = cc_ohio_chc_get_county_array();
	foreach ($counties as $cnty) {

			$countyname = explode(" ", $cnty);

			//$countyform = $countyform . "COUNTY=" . $cnty . "|";

			$countyform="";
			$titles="";

			$keysarray = array("33","38","30","31","32");

			$keycount = 0;
			$noentries = "";
			foreach ($keysarray as $k) {

				$startform = GFFormsModel::get_form_meta( $k );
				//var_dump($startform['title']);
				if (strpos($startform['title'],'-') !== false) {
					$titlearr = explode("-",$startform['title']);

					$titles = $titles . $countyname[0] . "_" . trim($titlearr[2]) . "|";
				}
				foreach( $startform['fields'] as $startfield ) {
					if ($startfield['label'] == 'cc_ohio_county') {
						$countykey = $startfield['id'];

					}
				}

				$search_criteria = array();
				$search_criteria["field_filters"][] = array( 'key' => $countykey, 'value' => $cnty );
				$entries = GFAPI::get_entries( $k, $search_criteria );
				//print_r($search_criteria);
				//var_dump($entries);

				if (empty($entries)) {
					//echo "NO ENTRIES";
					$noentries = $noentries . $keycount . "|";
				} else {


				$entry = $entries[0];
				foreach ($entry as $entrykey => $entryval) {
					$entryid = 0;
					if ($entrykey == 'id') {
						$entryid = $entryval;
					}
					if ($entryid > 0) {
						$lead = RGFormsModel::get_lead( $entryid );
						$form = GFFormsModel::get_form_meta( $lead['form_id'] );
						//echo "<span style='font-size:14pt;font-weight:bold;'>" . $form['title'] . "</span><br /><br />";
						//$countyform = $countyform . "COUNTY=" . $cnty . "|";
						$countyform = $countyform . $form['title'] . "|";
						//var_dump($form);

							foreach( $form['fields'] as $field ) {
								if ($field['label'] != 'cc_ohio_county' && $field['label'] != 'cc_ohio_update_entry_id' && $field['label'] != 'cc_ohio_year') {

									if ($field['type'] == 'section') {
										//echo "<br /><strong>" . $field['label'] . "</strong><br />";
										$countyform = $countyform . "SECTION_" . $field['label'] . "|";
									} elseif ( $field['type'] == 'html') {
										$newhtml = str_replace('<br />', ' ', $field['content']);
										$newhtml = str_replace('font-size:16pt;', 'font-size:12pt;', $newhtml);
										//echo "<br /><span>" . $newhtml . "</span><br />";
										$countyform = $countyform . "HTML_" . $newhtml . "|";
									} elseif ($field['type'] == 'textarea') {
										$lastchr = substr($field['label'], -1);
										$fieldlbl = "";
										if ($lastchr == ":") {
											$fieldlbl = $field['label'];
										} else {
											$fieldlbl = $field['label'] . ":";
										}
										//echo "<span style='margin-left: 20px;'>" . $fieldlbl . $lead[ $field['id'] ] . "</span><br />";
										$countyform = $countyform . "TEXTAREA_" . $fieldlbl . $lead[ $field['id'] ] . "|";
									} elseif ($field['type'] == 'text') {
										$lastchr = substr($field['label'], -1);
										$fieldlbl = "";
										if ($lastchr == ":") {
											$fieldlbl = $field['label'];
										} else {
											$fieldlbl = $field['label'] . ":";
										}
										//echo "<span style='margin-left: 20px;'>" . $fieldlbl . $lead[ $field['id'] ] . "</span><br />";
										$countyform = $countyform . "TEXT_" . $fieldlbl . $lead[ $field['id'] ] . "|";
									} else {
										$lastchr = substr($field['label'], -1);
										$fieldlbl = "";
										if ($lastchr == ":") {
											$fieldlbl = $field['label'];
										} else {
											$fieldlbl = $field['label'] . ":";
										}
										//echo $field['type'] . "<br />";

										$reslt = $lead[ $field['id'] ];
										if (empty($reslt)) {
											$reslt = 0;
										}

										//echo "<span style='margin-left: 20px;'>" . $fieldlbl . $reslt . "</span><br />";
										$countyform = $countyform . $fieldlbl . $reslt . "|";
									}
								}
							}

					}

				}


				$countyform = str_replace("'","",$countyform);
				//echo "<br /><br />";
				}
				$keycount = $keycount + 1;
			}
			//var_dump($titles);
			if (!empty($noentries)) {
				//var_dump($titles);
				$noentries = substr_replace($noentries ,"", -1);

				if (strpos($noentries,'|') !== false) {
					$noearr = explode("|", $noentries);
					foreach ($noearr as $noe) {
						$titlecount = 0;
						//var_dump($titles);
						$titlearr = explode("|", $titles);
						foreach ($titlearr as $ti) {
							if ($titlecount == floatval($noentries)) {
								$titles = str_replace($ti . "|", "", $titles);
							}
							$titlecount = $titlecount + 1;
						}

					}
				} else {
					$titlecount = 0;
					//var_dump($titles);
					$titlearr = explode("|", $titles);
					foreach ($titlearr as $ti) {
						if ($titlecount == floatval($noentries)) {
							$titles = str_replace($ti . "|", "", $titles);
						}
						$titlecount = $titlecount + 1;
					}
					//unset($titles[floatval($noentries)]);
				}

			}

			//var_dump($titles);
			//var_dump($countyform);
			//echo "<div style='display:none;'>County Form<input type='hidden' name='countyform' value='" . $countyform . "/></div>";









				//var_dump($countyform);
				//var_dump(rtrim($titles,"|"));

				$current_user = wp_get_current_user();
				$email = $current_user->user_email;
				if ($email == "barbarom@missouri.edu" || $email == "illmo76@yahoo.com") {
					//var_dump($countyform);
					//var_dump(rtrim($titles,"|"));
				}
	?>

					<div style='display:none;'>
						<input type='hidden' name='countyform' value='<?php echo $countyform ?>' />
						<input type='hidden' name='formlist' value='<?php echo rtrim($titles,"|") ?>' />
						<input type='hidden' id='county' name='county' value='<?php echo $cnty ?>' />
						<input type='hidden' name='email' value='<?php echo $email ?>' />
					</div>



<?php
			//echo "Processing " . $cnty . "<br />";
			cc_ohio_process_all_counties($countyform,$email,$cnty,rtrim($titles,"|"));
		}

		//exit;


		// This code attempts to combine sheets from all the county files into one master file.

		//echo "Creating All Counties File<br />";
		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('America/Chicago');

		if (PHP_SAPI == 'cli')
			die('This example should only be run from a Web Browser');

		require_once( ABSPATH . 'PHPExcel/Classes/PHPExcel.php' );

		$objPHPExcel = new PHPExcel();

		$template_file = ABSPATH . "PHPExcel/Examples/ohio_cnty_files/sum_template.xls";
		$allcounties_file = ABSPATH . "PHPExcel/Examples/ohio_cnty_files/allcounties.xls";

		$objPHPExcel1 = PHPExcel_IOFactory::load($template_file);

		foreach ($counties as $thecounty) {
			$undscore_cnty = str_replace(" ","_",$thecounty);
			$input_file = ABSPATH . "PHPExcel/Examples/ohio_cnty_files/" . $undscore_cnty . ".xls";
			$objPHPExcel2 = PHPExcel_IOFactory::load($input_file);
			foreach($objPHPExcel2->getAllSheets() as $sheet) {

				$objPHPExcel1->addExternalSheet($sheet);
			}
		}

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel1, "Excel5");

		$objWriter->save( ABSPATH . "PHPExcel/Examples/ohio_cnty_files/ALL.xls" );
		//echo "ALL County file created!<br />";
		cc_ohio_sum_like_sheets();
	}
}

function cc_ohio_process_all_counties($countyform,$email,$county,$formlist) {

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Chicago');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once( ABSPATH . 'PHPExcel/Classes/PHPExcel.php' );


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();




$r = explode("|", $countyform);

$wksheetarr = explode("|", $formlist);
$wksheetarrcount = 0;
foreach ($wksheetarr as $ws) {
				$ws2 = str_replace(" ","",$ws);
				$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, $ws2);
				// Attach the “My Data” worksheet as the first worksheet in the PHPExcel object
				$objPHPExcel->addSheet($myWorkSheet, $wksheetarrcount);
				$wksheetarrcount = $wksheetarrcount + 1;
}
$thecount = 1;
$wscount = -1;
$numinprogress = false;
foreach ($r as $mb) {
	//var_dump($mb[0]);
	//echo $mb . "<br />";

	if (strlen($mb) > 0) {
		if (substr($mb,0,1) != "Q") {
			if(substr($mb,0,3) == "YTD") {
				$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('F' . $thecount,'=SUM(B' . $thecount . ':E' . $thecount . ')');
				// $yarr = explode(":", $mb);
				// $objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('F' . $thecount, $yarr[1]);
				$objPHPExcel->setActiveSheetIndex($wscount)->getStyle('F' . $thecount)->getFont()->setBold(true);
				$thecount = $thecount + 1;
			} elseif (substr($mb,0,7) == "ODH-CHC") {
				$wscount = $wscount + 1;
				$thecount = 1;
				// $objPHPExcel->setActiveSheetIndex($wscount);
				// var_dump( $objPHPExcel->setActiveSheetIndex($wscount) );
				// echo "<br /><br /><br /><br />";
			} else {

				$mb = str_replace("<strong>", "", $mb);
				$mb = str_replace("</strong>", "", $mb);
				$mb = str_replace("<br />", "", $mb);
				$mb = str_replace('<strong style="font-size:12pt;">', '', $mb);
				if (substr($mb,0,8) == "SECTION_") {
					$mb = str_replace("SECTION_", "", $mb);
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('A' . $thecount, $mb);
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('B' . $thecount, 'Q1');
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('C' . $thecount, 'Q2');
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('D' . $thecount, 'Q3');
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('E' . $thecount, 'Q4');
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('F' . $thecount, 'YTD');
					$objPHPExcel->setActiveSheetIndex($wscount)->getStyle('A' . $thecount)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex($wscount)->getStyle('B' . $thecount)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex($wscount)->getStyle('C' . $thecount)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex($wscount)->getStyle('D' . $thecount)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex($wscount)->getStyle('E' . $thecount)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex($wscount)->getStyle('F' . $thecount)->getFont()->setBold(true);
					$thecount = $thecount + 1;
				} elseif (substr($mb,0,9) == "TEXTAREA_") {
					$mb = str_replace("TEXTAREA_", "", $mb);
					$mb = str_replace(":", ":  ", $mb);
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('A' . $thecount, $mb);
					$thecount = $thecount + 1;
				} elseif (substr($mb,0,5) == "TEXT_") {
					$mb = str_replace("TEXT_", "", $mb);
					$mb = str_replace(":", ":  ", $mb);
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('A' . $thecount, $mb);
					$thecount = $thecount + 1;
				} elseif (substr($mb,0,5) == "HTML_") {
					$mb = str_replace("HTML_", "", $mb);
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('A' . $thecount, $mb);
					if (strpos($mb,'progress') !== false) {
						$numinprogress = true;
						//$thecount = $thecount + 1;
					}


				} else {
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('A' . $thecount, $mb);
					$thecount = $thecount + 1;
				}
			}
		} elseif(substr($mb,0,2) == "Q1") {
			$Qnum = substr($mb, 3);
			$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('B' . $thecount, $Qnum);
		} elseif(substr($mb,0,2) == "Q2") {
			$Qnum = substr($mb, 3);
			$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('C' . $thecount, $Qnum);
		} elseif(substr($mb,0,2) == "Q3") {
			$Qnum = substr($mb, 3);
			$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('D' . $thecount, $Qnum);
		} elseif(substr($mb,0,2) == "Q4") {
			$Qnum = substr($mb, 3);
			$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('E' . $thecount, $Qnum);
			if ($numinprogress == true) {
				$thecount = $thecount + 1;
				$numinprogress = false;
			}
		}
	}
}

$letterArray = array('B','C','D','E');

foreach ($letterArray as $letter) {
	//Active Living Sum of Number Impacted
	$AL = 0;
	$i = 4;
	do {
		$AL = $AL + $objPHPExcel->setActiveSheetIndex(1)->getCell($letter . $i)->getValue();
		$i = $i + 5;

	} while ($i < 115);
	$AL = $AL + $objPHPExcel->setActiveSheetIndex(1)->getCell($letter . '120')->getValue();
	$objPHPExcel->setActiveSheetIndex(1)->setCellValue($letter . '131', $AL);

	//Healthy Eating Sum of Number Impacted
	$HE = 0;
	$h = 6;
	do {
		$HE = $HE + $objPHPExcel->setActiveSheetIndex(2)->getCell($letter . $h)->getValue();
		$h = $h + 5;

	} while ($h < 27);
	$j = 32;
	do {
		$HE = $HE + $objPHPExcel->setActiveSheetIndex(2)->getCell($letter . $j)->getValue();
		$j = $j + 5;

	} while ($j < 133);
	$objPHPExcel->setActiveSheetIndex(2)->setCellValue($letter . '143', $HE);

	//Tobacco Sum of Number Impacted
	$TB = 0;
	$k = 4;
	do {
		$TB = $TB + $objPHPExcel->setActiveSheetIndex(3)->getCell($letter . $k)->getValue();
		$k = $k + 5;

	} while ($k < 35);
	$TB = $TB + $objPHPExcel->setActiveSheetIndex(3)->getCell($letter . '40')->getValue();
	$objPHPExcel->setActiveSheetIndex(3)->setCellValue($letter . '51', $TB);

	//Supplemental Sum of Number Impacted
	$SP = 0;
	$p = 4;
	do {
		$SP = $SP + $objPHPExcel->setActiveSheetIndex(4)->getCell($letter . $p)->getValue();
		$p = $p + 5;

	} while ($p < 15);

	$objPHPExcel->setActiveSheetIndex(4)->setCellValue($letter . '17', $SP);
}

	$val1 = $objPHPExcel->setActiveSheetIndex(1)->getCell('F126')->getCalculatedValue();
	$objPHPExcel->setActiveSheetIndex(1)->setCellValue('F131', $val1);
	$val2 = $objPHPExcel->setActiveSheetIndex(2)->getCell('F133')->getCalculatedValue();
	$objPHPExcel->setActiveSheetIndex(2)->setCellValue('F143', $val2);


	//Get sum Total Impacted and put on General tab.
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A51', 'Number Impacted (ALL TABS):');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B51', 'Q1');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C51', 'Q2');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D51', 'Q3');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E51', 'Q4');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F51', 'YTD');
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A51:F51')->getFont()->setBold(true);

	$q1 = $objPHPExcel->setActiveSheetIndex(1)->getCell('B131')->getValue() + $objPHPExcel->setActiveSheetIndex(2)->getCell('B143')->getValue() + $objPHPExcel->setActiveSheetIndex(3)->getCell('B51')->getValue() + $objPHPExcel->setActiveSheetIndex(4)->getCell('B17')->getValue();
	$q2 = $objPHPExcel->setActiveSheetIndex(1)->getCell('C131')->getValue() + $objPHPExcel->setActiveSheetIndex(2)->getCell('C143')->getValue() + $objPHPExcel->setActiveSheetIndex(3)->getCell('C51')->getValue() + $objPHPExcel->setActiveSheetIndex(4)->getCell('C17')->getValue();
	$q3 = $objPHPExcel->setActiveSheetIndex(1)->getCell('D131')->getValue() + $objPHPExcel->setActiveSheetIndex(2)->getCell('D143')->getValue() + $objPHPExcel->setActiveSheetIndex(3)->getCell('D51')->getValue() + $objPHPExcel->setActiveSheetIndex(4)->getCell('D17')->getValue();
	$q4 = $objPHPExcel->setActiveSheetIndex(1)->getCell('E131')->getValue() + $objPHPExcel->setActiveSheetIndex(2)->getCell('E143')->getValue() + $objPHPExcel->setActiveSheetIndex(3)->getCell('E51')->getValue() + $objPHPExcel->setActiveSheetIndex(4)->getCell('E17')->getValue();
	$ytdsum = $q1 + $q2 + $q3 + $q4;

	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B52', $q1);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C52', $q2);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D52', $q3);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E52', $q4);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F52', '=SUM(B52:E52)');

	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A39', 'Total Q1: ' . $q1);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A40', 'Total Q2: ' . $q2);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A41', 'Total Q3: ' . $q3);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A42', 'Total Q4: ' . $q4);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A43', 'Total YTD: ' . $ytdsum);







	//Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$xlsname = str_replace(" ", "_", $county);

	$sheetIndex = $objPHPExcel->getIndex($objPHPExcel-> getSheetByName('Worksheet'));
	$objPHPExcel->removeSheetByIndex($sheetIndex);


	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save(ABSPATH . "PHPExcel/Examples/ohio_cnty_files/" . $xlsname . ".xls");
	//exit;
}

function cc_ohio_sum_like_sheets() {


	/** Error reporting */
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('America/Chicago');

	if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');

	/** Include PHPExcel */
	require_once( ABSPATH . 'PHPExcel/Classes/PHPExcel.php' );


	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	//***START SUMMING LIKE SHEETS***
	//$sheetGeneral = $objPHPExcel->getIndex($objPHPExcel-> getSheetByName('Worksheet'));


	$all_file = ABSPATH . "PHPExcel/Examples/ohio_cnty_files/ALL.xls";

	$objPHPExcel = PHPExcel_IOFactory::load($all_file);


	//***SUM GENERAL SHEET***
	$GenSumStr="";
	$GenColArr = array("B","C","D","E","F");
	$GenRowArr = array("2","3","4","8","13","14","15","16","17","18","19","20","21","22","28","29","30","32","34","36","38");
	$GenArr = array();

	$GenTotalArray = array("A39","A40","A41","A42","A43");
	$GenA39 = 0;
	$GenA40 = 0;
	$GenA41 = 0;
	$GenA42 = 0;
	$GenA43 = 0;



	foreach ($GenRowArr as $row) {
		foreach ($GenColArr as $col) {
			array_push($GenArr, $col.$row);
		}
	}

	foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
		$ws_title = $worksheet->getTitle();
		if (strpos($ws_title,'_') !== false) {
			$ws_arr = explode('_',$ws_title);
			if ($ws_arr[1] == 'General') {
				$GenSumStr = $GenSumStr . $ws_title . "!XX,";


				foreach ($GenTotalArray as $GenTotal) {
					$ttlstr = $worksheet->getCell($GenTotal)->getValue();
					$pieces = explode(":", $ttlstr);
					if ($GenTotal == "A39") {
						$GenA39 = $GenA39 + (int)$pieces[1];
					} else if ($GenTotal == "A40") {
						$GenA40 = $GenA40 + (int)$pieces[1];
					} else if ($GenTotal == "A41") {
						$GenA41 = $GenA41 + (int)$pieces[1];
					} else if ($GenTotal == "A42") {
						$GenA42 = $GenA42 + (int)$pieces[1];
					} else if ($GenTotal == "A43") {
						$GenA43 = $GenA43 + (int)$pieces[1];
					}
				}

			}
		}
	}

	$GenSumStr = rtrim($GenSumStr, ",");

	foreach ($GenArr as $GenCoord) {
		$GenSumStr2 = str_replace("XX",$GenCoord,$GenSumStr);
		$GenWS = $objPHPExcel->getSheetByName('General');
		$GenWS->setCellValue($GenCoord, '=SUM(' . $GenSumStr2 . ')');
	}

	foreach ($GenTotalArray as $GenCoord2) {
		$GenWS2 = $objPHPExcel->getSheetByName('General');
		if ($GenCoord2 == "A39") {
			$GenWS2->setCellValue($GenCoord2, "Total Q1:" . $GenA39);
		} else if ($GenCoord2 == "A40") {
			$GenWS2->setCellValue($GenCoord2, "Total Q2:" . $GenA40);
		} else if ($GenCoord2 == "A41") {
			$GenWS2->setCellValue($GenCoord2, "Total Q3:" . $GenA41);
		} else if ($GenCoord2 == "A42") {
			$GenWS2->setCellValue($GenCoord2, "Total Q4:" . $GenA42);
		} else if ($GenCoord2 == "A43") {
			$GenWS2->setCellValue($GenCoord2, "Total YTD:" . $GenA43);
		}
	}



	//echo "Sheet General Complete! <br />";

	//***SUM ACTIVE LIVING SHEET***
	$ALSumStr="";
	$ALColArr = array("B","C","D","E","F");
	$ALRowArr = array("2","3","4","7","8","9","12","13","14","17","18","19","22","23","24","27","28","29","32","33","34","37","38","39","42","43","44","47","48","49","52","53","54","57","58","59","62","63","64","67","68","69","72","73","74","77","78","79","82","83","84","87","88","89","92","93","94","97","98","99","102","103","104","107","108","109","112","113","114","118","119","120","122","123","126","128","131");
	$ALArr = array();

	foreach ($ALRowArr as $ALrow) {
		foreach ($ALColArr as $ALcol) {
			array_push($ALArr, $ALcol.$ALrow);
		}
	}

	foreach ($objPHPExcel->getWorksheetIterator() as $ALworksheet) {
		$ALws_title = $ALworksheet->getTitle();
		if (strpos($ALws_title,'_') !== false) {
			$ALws_arr = explode('_',$ALws_title);
			if ($ALws_arr[1] == 'ActiveLiving') {
				$ALSumStr = $ALSumStr . $ALws_title . "!YY,";
			}
		}
	}

	$ALSumStr = rtrim($ALSumStr, ",");

	foreach ($ALArr as $ALCoord) {
		$ALSumStr2 = str_replace("YY",$ALCoord,$ALSumStr);
		$ALWS = $objPHPExcel->getSheetByName('ActiveLiving');
		$ALWS->setCellValue($ALCoord, '=SUM(' . $ALSumStr2 . ')');
	}

	//echo "Sheet Active Living Complete! <br />";

	//***SUM HEALTHY EATING SHEET***
	$HESumStr="";
	$HEColArr = array("B","C","D","E","F");
	$HERowArr = array("2","3","4","5","6","9","10","11","14","15","16","19","20","21","24","25","26","29","30","31","32","35","36","37","40","41","42","45","46","47","50","51","52","55","56","57","60","61","62","65","66","67","70","71","72","75","76","77","80","81","82","85","86","87","90","91","92","95","96","97","100","101","102","105","106","107","110","111","112","115","116","117","120","121","122","125","126","127","130","131","132","134","135","138","140","143");
	$HEArr = array();

	foreach ($HERowArr as $HErow) {
		foreach ($HEColArr as $HEcol) {
			array_push($HEArr, $HEcol.$HErow);
		}
	}

	foreach ($objPHPExcel->getWorksheetIterator() as $HEworksheet) {
		$HEws_title = $HEworksheet->getTitle();
		if (strpos($HEws_title,'_') !== false) {
			$HEws_arr = explode('_',$HEws_title);
			if ($HEws_arr[1] == 'HealthyEating') {
				$HESumStr = $HESumStr . $HEws_title . "!YY,";
			}
		}
	}

	$HESumStr = rtrim($HESumStr, ",");

	foreach ($HEArr as $HECoord) {
		$HESumStr2 = str_replace("YY",$HECoord,$HESumStr);
		$HEWS = $objPHPExcel->getSheetByName('HealthyEating');
		$HEWS->setCellValue($HECoord, '=SUM(' . $HESumStr2 . ')');
	}

	//echo "Sheet Healthy Eating Complete! <br />";

	//***SUM Tobacco SHEET***
	$TOBSumStr="";
	$TOBColArr = array("B","C","D","E","F");
	$TOBRowArr = array("2","3","4","7","8","9","12","13","14","17","18","19","22","23","24","27","28","29","32","33","34","38","39","40","42","43","46","48","51");
	$TOBArr = array();

	foreach ($TOBRowArr as $TOBrow) {
		foreach ($TOBColArr as $TOBcol) {
			array_push($TOBArr, $TOBcol.$TOBrow);
		}
	}

	foreach ($objPHPExcel->getWorksheetIterator() as $TOBworksheet) {
		$TOBws_title = $TOBworksheet->getTitle();
		if (strpos($TOBws_title,'_') !== false) {
			$TOBws_arr = explode('_',$TOBws_title);
			if ($TOBws_arr[1] == 'Tobacco') {
				$TOBSumStr = $TOBSumStr . $TOBws_title . "!YY,";
			}
		}
	}

	$TOBSumStr = rtrim($TOBSumStr, ",");

	foreach ($TOBArr as $TOBCoord) {
		$TOBSumStr2 = str_replace("YY",$TOBCoord,$TOBSumStr);
		$TOBWS = $objPHPExcel->getSheetByName('Tobacco');
		$TOBWS->setCellValue($TOBCoord, '=SUM(' . $TOBSumStr2 . ')');
	}

	//echo "Sheet Tobacco Complete! <br />";

	//***SUM Supplemental SHEET***
	$SUPSumStr="";
	$SUPColArr = array("B","C","D","E","F");
	$SUPRowArr = array("2","3","4","7","8","9","12","13","14","17");
	$SUPArr = array();

	foreach ($SUPRowArr as $SUProw) {
		foreach ($SUPColArr as $SUPcol) {
			array_push($SUPArr, $SUPcol.$SUProw);
		}
	}

	foreach ($objPHPExcel->getWorksheetIterator() as $SUPworksheet) {
		$SUPws_title = $SUPworksheet->getTitle();
		if (strpos($SUPws_title,'_') !== false) {
			$SUPws_arr = explode('_',$SUPws_title);
			if ($SUPws_arr[1] == 'Supplemental') {
				$SUPSumStr = $SUPSumStr . $SUPws_title . "!YY,";
			}
		}
	}

	$SUPSumStr = rtrim($SUPSumStr, ",");

	foreach ($SUPArr as $SUPCoord) {
		$SUPSumStr2 = str_replace("YY",$SUPCoord,$SUPSumStr);
		$SUPWS = $objPHPExcel->getSheetByName('Supplemental');
		$SUPWS->setCellValue($SUPCoord, '=SUM(' . $SUPSumStr2 . ')');
	}

	//echo "Sheet Supplemental Complete! <br />";


	$objPHPExcel->setActiveSheetIndex(0);

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save(ABSPATH . "PHPExcel/Examples/ohio_cnty_files/ALL.xls");


	echo "<strong>Aggregate County File Created!</strong>  To download the Aggregate County File, click on the following button.<br /><br /><span style='font-style:italic;color:red;font-weight:bold;'>This process will take about 1 minute to compile.</span><br /><br />";
	echo "<form id='downloadform' name='downloadform' action='/PHPExcel/Examples/oh-county-ALL-xls.php' method='post'>";
	echo "<input type='submit' id='downloadALL' name='downloadALL' value='Download the Aggregate County File' />";
	echo "</form>";

	exit;
}