<?php 
/**
 * CC Creating Healthy Communities Ohio Extras
 *
 * Description: File that holds the gravity forms hooks and functions
 *
 * @package   CC Creating Healthy Communities Ohio Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2015 CommmunityCommons.org
 */

add_filter('gform_field_value_ohio_dynamic_userid', 'cc_ohio_chc_gf_dynamic_userid');

//maybe?
//add_filter('gform_update_post/public_edit', '__return_true');

function cc_ohio_chc_gf_dynamic_userid( $value ){
	$user_id = get_current_user_id();
	
	return $user_id;
}


//adds dropdown list of regions to User-County Assignment
add_filter("gform_column_input_37_1_2", "cc_ohio_populate_county_list", 10, 5);
add_filter("gform_column_input_31_1_2", "cc_ohio_populate_county_list", 10, 5);

function cc_ohio_populate_county_list($input_info, $field, $column, $value, $form_id){
    return array(
		"type" => "select", 
		"choices" => "Adams-Brown Counties,Allen County,Athens County,Cincinnati,Columbus City,Cuyahoga County,Lorain County,Lucas County,Marion County,Meigs County,Montgomery County,Richland County,Summit County,Trumbull County,Washington County"
		);
}


add_filter('gform_field_value_region_name', 'cc_ohio_add_region');
function cc_ohio_add_region($value){
	global $wpdb;
	$query = $wpdb->get_var( $wpdb->prepare(
		"
		SELECT value 
		FROM wp_rg_lead_detail
		WHERE form_id = 24 AND field_number = 1
		"
	));
	$array1 = unserialize($query);
	
	$current_user = wp_get_current_user();
    $current_user_email = $current_user->user_email;    
	$region;
	foreach ($array1 as $array2) {		
		if ( $array2['User Email']== $current_user_email ) {
			$region = $array2['Region'];	
		} 
	}

    return $region;
}

add_filter('gform_field_value_entry_year', 'cc_ohio_add_year');
function cc_ohio_add_year($value){
    return date("Y");
}

/* Populate Form by Existing entry
 * 
 * sort of...from here: http://pastie.org/1917904
 * http://www.gravityhelp.com/forums/topic/need-help-creating-an-edit-form
 * @params form object, entry object 
 * @returns form object
 *
 */

//TODO: make this 
add_filter("gform_pre_render", 'populate_by_existing');

function populate_by_existing( $form ){

	//which forms to care about? Get all Ohio county-input forms
	$form_array = cc_ohio_chc_get_gf_forms_all();
	
	//are we on the ohio forms?  If not, return the normal form
	if( !(in_array( $form["id"], $form_array ) ) ) {
		return $form;
	}
	
	//TODO: account for admin here...or at top of function or something
	$current_county = get_user_county();
	$county_field_name = "cc_ohio_county";
	
	//get the form object, to prepopulate
	$gf_form_num = $form["id"];
	$form = GFAPI::get_form( $gf_form_num );
	
	//for this form, what field id is the county?
	$county_field_id = get_gf_field_id_by_label( $form, $county_field_name );
	//var_dump( $county_field_id);

	
	$entry = cc_ohio_chc_get_county_entry_by_form_number( $gf_form_num );
	$entry = $entry[0]; //why you know work current()?
	
	
	//forms have $form['field']['id'], but entries have $entry[#] where 'id' = #
    foreach($form['fields'] as &$field){

		//if we have no entry, change county field and then bail
		if( !$entry ){
			if( $field['id'] == $county_field_id ) {
				$field['defaultValue'] = $current_county;
			}
			continue;
		}
	
        switch($field['type']){
        case 'checkbox':

            $i = 1;
            foreach($field['choices'] as &$choice){
			
				$current_field_id = (string)$field['id'];
				
                if(!isset( $entry[$current_field_id . '.' . $i])) {
                    $i++;   
                    continue;
                }

                if($choice['value'] == $entry[$current_field_id . '.' . $i])
                    $choice['isSelected'] = true;

                $i++;
            }
            break;

        case 'address': //not right now
            foreach($field['inputs'] as &$input){
				$current_field_id = (string)$field['id'];

                $field['allowsPrepopulate'] = true;
                $parameter_name = strtolower(str_replace(' ', '_', str_replace('/ ', '', $input['label'])));

                $input['name'] = $parameter_name;
                $input_value = rgar($entry, (string) $input['id']);

                add_filter('gform_field_value_' . $parameter_name, create_function("", "return '$input_value';"));

            }
            break;

        default:

			$current_field_id = (string)$field['id'];
			
			//var_dump( (string)$current_field_id);
			//var_dump( $entry[$current_field_id]);
			//var_dump( $entry[5]);
			
			//check to see if there's a value for the current field in the $entry object
			if( !isset( $entry[$current_field_id] ) ){
				continue; 
			} else {
				$field['defaultValue'] = $entry[$current_field_id];   
				
			}
        }

    }

    return $form;
}