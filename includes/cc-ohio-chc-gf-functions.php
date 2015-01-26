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

//add_filter("gform_pre_render_39", 'populate_by_lead');

function populate_by_existing( $form, $entry ){

    // if there is no lead ID, return the form unmodified
   // if( !$entry )
       // return $form;


    foreach($form['fields'] as &$field){

        switch($field['type']){
        case 'checkbox':

            $i = 1;
            foreach($field['choices'] as &$choice){

                if(!isset($entry[$field['id'] . '.' . $i])) {
                    $i++;   
                    continue;
                }

                if($choice['value'] == $entry[$field['id'] . '.' . $i])
                    $choice['isSelected'] = true;

                $i++;
            }
            break;

        case 'address':
            foreach($field['inputs'] as &$input){

                $field['allowsPrepopulate'] = true;
                $parameter_name = strtolower(str_replace(' ', '_', str_replace('/ ', '', $input['label'])));

                $input['name'] = $parameter_name;
                $input_value = rgar($entry, (string) $input['id']);

                add_filter('gform_field_value_' . $parameter_name, create_function("", "return '$input_value';"));

            }
            break;

        default:

            if(!isset($entry[$field['id']]))
                continue;

			var_dump( $entry[$field] );
            $field['defaultValue'] = $entry[$field['id']];   
        }

    }

	//var_dump($entry);
    return $form;
}