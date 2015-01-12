jQuery(document).ready(function($){
	/* When a toggle is clicked, show the toggle-content */
	$('.toggle-link').click(function(){
		// Traverse for some items
		var $toggleable = $( this ).parents( '.toggleable' );

		if ( $toggleable.hasClass( 'toggle-open' ) ) {
			$toggleable.removeClass( 'toggle-open' ).addClass( 'toggle-closed' );
		} else {
			$toggleable.removeClass( 'toggle-closed' ).addClass( 'toggle-open' );
		}

		return false;
	});

	// Nested version; only acts on immediate parent
	$('.nested-toggle-link').click(function( e ){
		e.preventDefault();
		// Traverse for some items
		var $toggleable = $( this ).parent( '.toggleable' );

		if ( $toggleable.hasClass( 'toggle-open' ) ) {
			$toggleable.removeClass( 'toggle-open' ).addClass( 'toggle-closed' );
		} else {
			$toggleable.removeClass( 'toggle-closed' ).addClass( 'toggle-open' );
		}

		return false;
	});

	// Nested form hides outer form submit button when displayed.
	$('#change-board-affiliations-toggle-link').click(function( e ){
		e.preventDefault();
		// Traverse for some items
		var $toggleable = $( this ).parent( '.toggleable' );

		if ( $toggleable.hasClass( 'toggle-open' ) ) {
			$('#submit-metro-id-cookie').prop('disabled', true);
			$('#submit-metro-id-cookie').hide();
		} else {
			$('#submit-metro-id-cookie').prop('disabled', false);
			$('#submit-metro-id-cookie').show();
		}

		return false;
	});

	// Generalized click relation
	// Trigger input should have this markup: class="has-follow-up" and data-relatedQuestion="id_of_follow_up_q" (if a radio button, all items should have the class)
	// Target question should be wrapped in a div with the class "follow-up-question" and  with the attribute: data-relatedTarget="2.2.2.2"

	// On page load, refresh element visibility.
	// Begin by creating an array of target_ids that we'll use again and again.
	// We have to treat the inputs in groups by data attribute
	// First get all the "relatedquestion" possibilities on the page
	var follow_up_questions = [];
	var target_id = '';

	$( '.has-follow-up' ).each( function() {
      	if ( target_id = $( this ).data( 'relatedquestion' ) ) {
	      	if ( $.inArray( target_id, follow_up_questions ) == -1 ) {
	        	follow_up_questions.push( target_id );
	      	}
		}
	});

	refresh_follow_up_question_visibility();

	// When a watched input changes, refresh visbility.
	$('.has-follow-up').on( 'change', function() {
		refresh_follow_up_question_visibility();
	});

	function refresh_follow_up_question_visibility(){

		// Next we iterate through each group of non-disabled inputs that targets the same follow-up, if any is checked, show the follow-up question
		$.each( follow_up_questions, function( index, target ) {
			var show_target = false;

			$( '.has-follow-up[data-relatedquestion="' + target + '"]' ).each( function() {
				if ( $( this ).prop( "checked" ) && ! $( this ).prop( "disabled" ) ) {
					show_target = true;
				} 
			});

			if ( show_target ) {
				$('.follow-up-question[data-relatedtarget="' + target + '"]').addClass('enabled');
				$('.follow-up-question[data-relatedtarget="' + target + '"] input, .follow-up-question[data-relatedtarget="' + target + '"] textarea').prop('disabled', false);
			} else {
				$('.follow-up-question[data-relatedtarget="' + target + '"]').removeClass('enabled');
				// Disable children of children, too
				$('.follow-up-question[data-relatedtarget="' + target + '"]').find( 'input, textarea' ).prop('disabled', true);
			}
		});
	}

	// Prompt user for confirmation if she leaves a form page without saving
	$('.aha-survey input:not(:submit), .aha-survey textarea, .aha-survey select').change( function() {
		var aha_should_confirm = true;

		$('.aha-survey input:submit').on( 'click', function() {
			aha_should_confirm = false;
		});

		window.onbeforeunload = function(e) {
			if ( aha_should_confirm ) {
				return 'If you leave this page without saving, your changes will be lost.';
			}
		};
	});
	
	jQuery(".board-approved-priority-checkbox input").on("click", function(){
	
		var action;
		var thisCheckbox = jQuery(this);
		if ( jQuery(this).is(":checked") ) {
			action = "save_board_approved_priority";
		} else { //give em a chance to back out..
			var confirmed = confirm( "Are you sure you want to remove this priority?" );
			if ( confirmed == true ){
				action = "remove_board_approved_priority";
			} else {
				action = "cancel";
			}
		}
		
		var criteria_name = jQuery(this).data("criteria");
		var criteria_slug = jQuery(this).data("criteriaslug");
		//var nonce = jQuery("#set-aha-remove-priority-nonce-" + criteria_name).val();
		
		//var date = jQuery(this).data("date");
		//hmm, not sure where to set date.  Can we be certain it's every 3 years: 2017, 2020...etc?
		// TODO: review this, does it make sense?  Will AHA be setting 2017 only in 2015 on, or also in 2014 (since FY in June)?
		//		if FYE in June, could look whether current date in July and add one to remainder if remainder = 0...
		var currentYear = (new Date).getFullYear();
		
		//vague math to help
		var multiple; 
		var remainder;
		var startYear = 2014;
		var benchmarkYear;  //2017, 2020
		
		multiple = parseInt( ( currentYear - startYear ) / 3 );
		remainder = currentYear % startYear;
		
		if( remainder > 0 ){ //2015, 2016, 2018
			benchmarkYear = ( startYear + ( multiple * 3 ) ) + 3; //if remainder, benchmark in future year
		} else {
			benchmarkYear = ( startYear + ( multiple * 3 ) );  //if we're in a benchmark year, that's the year
		}
		
		//var metro_id = jQuery(this).data("metroid"); //better to get from here or $_COOKIE?
		
		var data = {
			'action': action,
			'criteria_name' : criteria_name,
			'criteria_slug' : criteria_slug,
			'date' : benchmarkYear,
			'aha_nonce' : aha_ajax.ajax_nonce
			
			/*'data' : {
				'criteria_name' : criteria_name,
				'date' : benchmarkYear,
				'aha_nonce' : aha_ajax.ajax_nonce
			}*/
			//'metro_id': metro_id,
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		if ( action != "cancel" ){
			jQuery.post(
				aha_ajax.ajax_url, 
				data, 
				function(response) {
					//response = new post id
					if ( ( response > 0 ) && ( response.length > 0 ) ) {
						if( action == "save_board_approved_priority" ){
							//turn on 'staff save' button for tihs priority
							jQuery('.priority_staff_save[data-criteria="' + criteria_name + '"]').attr("data-priorityid", parseInt( response ));
							//turn on 'Edit Staff Something'
							thisCheckbox.siblings('a.priority_staff_link').show();
						} 
					} else if ( response == 0 ) {
						if ( action == "remove_board_approved_priority" ){
							//turn off 'staff save' button for tihs priority
							jQuery('.priority_staff_save[data-criteria="' + criteria_name + '"]').attr("data-priorityid", 0);
							//turn on 'Edit Staff Something'
							thisCheckbox.siblings('a.priority_staff_link').hide();
							jQuery('.priority_staff_select[data-criteria="' + criteria_name + '"]').hide();
							jQuery('.priority_volunteer_select[data-criteria="' + criteria_name + '"]').hide();
							jQuery('.priority_staff_save[data-criteria="' + criteria_name + '"]').hide();
						}
						//console.log( 'something from the server: ' + response);
					}
				}
			);
		}
	
	});

	jQuery(".priority_staff_link").on("click", function(){
		
		//show staff edit row, if hidden, else hide
		var staffSelect = jQuery(('.priority_staff_select[data-criteria="' + jQuery(this).data("criteria") + '"]'));
		var volunteerSelect = jQuery(('.priority_volunteer_select[data-criteria="' + jQuery(this).data("criteria") + '"]'));
		var staffSave = jQuery(('.priority_staff_save[data-criteria="' + jQuery(this).data("criteria") + '"]'));
		
		var impactArea = staffSelect.data("impact");
		var impactTitle = jQuery('td.impact_title[data-impact="' + impactArea + '"]');
		var parent = jQuery(this).parent('td.board-approved-priority-checkbox');
		var rowspan;
		
		//rowspan of impact-title must be added to, or subtracted from
		if ( staffSelect.is(":hidden") ) {
			jQuery(this).parent("td.board-approved-priority-checkbox").siblings("td.criteria_title").css("font-weight", "bold");
			staffSelect.show();
			volunteerSelect.show();
			staffSave.show();
			rowspan = impactTitle.attr("rowspan");
			impactTitle.attr("rowspan", parseInt(rowspan) + 3);
		} else {
			jQuery(this).parent("td.board-approved-priority-checkbox").siblings("td.criteria_title").css("font-weight", "normal");
			staffSelect.hide();
			volunteerSelect.hide();
			staffSave.hide();
			rowspan = impactTitle.attr("rowspan");
			impactTitle.attr("rowspan", parseInt(rowspan) - 3);
		} 
		//console.log( jQuery(this).data("criteria") );
	
	});
	
	jQuery("a.submit_staff_partners").on("click", function() {
	
		var thisButton = jQuery(this);
		
		var criteria = jQuery(this).parents('tr.priority_staff_save').data("criteria");
		var priority_id = jQuery(this).parents('tr.priority_staff_save').data("priorityid");
		
		var staff_partner = parseInt( jQuery(('.priority_staff_select[data-criteria="' + criteria + '"] select.staff_partner')).val() );
		var volunteer_lead = parseInt( jQuery(('.priority_volunteer_select[data-criteria="' + criteria + '"] select.volunteer_lead')).val() );
	
		//prep ajax data
		var action = "save_board_approved_staff";
		var data = {
			'action': action,
			'staff_partner' : staff_partner,
			'volunteer_lead' : volunteer_lead,
			'priority_id' : priority_id,
			'aha_nonce' : aha_ajax.ajax_nonce
			/*'data' : {
				'staff_partner' : staff_partner,
				'volunteer_lead' : volunteer_lead,
				'priority_id' : priority_id,
				'aha_nonce' : aha_ajax.ajax_nonce
			}
			*/
		};
		
		thisButton.siblings(".spinny").css("display", "inline-block");
		
		jQuery.post(
			aha_ajax.ajax_url, 
			data,
			function(response) {
				thisButton.siblings(".spinny").css("display", "none");
				thisButton.siblings(".staff_save_message").html("Staff saved.");
				setTimeout( function(){ 
					thisButton.siblings(".staff_save_message").html("");
					}, 3000
				);
				//console.log( 'something from the server: ' + response);
			}
		);
	
	});
	
	jQuery('select.potential_priority').on("change", function(){
	
		var thisSelect = jQuery(this);
		var value = jQuery(this).val();
		var criteria = jQuery(this).data("criteria");
		var criteria_slug = jQuery(this).data("criteriaslug");

		//prep ajax data
		var action = "save_board_potential_priority";
		var data = {
			'action': action,
			'criteria' : criteria,
			'criteria_slug' : criteria_slug,
			'potential_priority' : value,
			'aha_nonce' : aha_ajax.ajax_nonce
		};
		//turn on spinny
		thisSelect.siblings(".spinny").css("display", "inline-block");
		jQuery.post(
			aha_ajax.ajax_url, 
			data,
			function(response) {
				
				thisSelect.siblings(".spinny").css("display", "none");
				//console.log( 'something from the server: ' + response);
			}
		);
	
	
	
	
	
	});
	
},(jQuery));