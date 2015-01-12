


function reportCardClickListen(){

	//TODO: put delays on the subsequent handlers since OMG data
	jQuery('.community-show-trigger').on("click", showCommunityTrigger);
	jQuery('.community-hide-trigger').on("click", hideCommunityTrigger );
	
	jQuery('.school-show-trigger').on("click", showSchoolTrigger);
	jQuery('.school-hide-trigger').on("click", hideSchoolTrigger );
	
	jQuery('.care-hide-trigger').on("click", hideCareTrigger );
	jQuery('.care-show-trigger').on("click", showCareTrigger);
	
	jQuery('.all-show-trigger').on("click", showAllTrigger);

	//what state is selected in the drop down?
	jQuery('#state-dropdown').on("change", function(){
		var thisState = jQuery( "select#state-dropdown option:selected").val();
		filterByState( thisState);
	});

	//is an affiliate selected?
	jQuery('#affiliate-dropdown').on("change", function(){
		var thisAffiliate = jQuery( "select#affiliate-dropdown option:selected").val();
		filterByAffiliate( thisAffiliate);
	});
	
	//are we on potential or board-approved priority? Show relevant stars and counts..
	jQuery('select.priority-select').on("change", function(){
	
		var thisPriorityLevel = getPriorityLevel(); //jQuery( "select.priority-select option:selected").val();
		changePriorityLevel( thisPriorityLevel );
	
	});
	
	//is top 3 selected?
	jQuery('tr.top-3-row th').click(function(){
		
		//make sure we're not on the select 
		if( !( jQuery(this).hasClass("ignore-sort") ) ){
			//determine whether we're on Potential or Board-approved priority
			var thisPriorityLevel = getPriorityLevel(); 
			if ( thisPriorityLevel == "potential" ) {
				var whichTop3Yes = jQuery(this).data("top3group");
				var whichTop3Name = jQuery(this).data("top3name");
			} else {
				var whichTop3Yes = jQuery(this).data("prioritygroup");
				var whichTop3Name = jQuery(this).data("priorityname");
			}
			filterByTop3( jQuery(this), whichTop3Yes, whichTop3Name );
		
		}
	});
	
	//take care of our sort arrows
	jQuery('tr.criteria-row th').on("click", function(){
		
		//all arrows desc until further notice
		resetCriteriaArrows();
		
		//are we in asc or desc order?
		if ( jQuery(this).hasClass('tablesorter-headerAsc') ) {
			jQuery(this).find('.sort-arrow').html('&#x25B2;');
		} else {
			jQuery(this).find('.sort-arrow').html('&#x25BC;');
		}
		
	});
}

function resetCriteriaArrows(){
	jQuery('.sort-arrow').html('&#x25BC;');

}

function showCommunityTrigger(){
	jQuery('.community-show-trigger').html("HIDE COMMUNITY");
	jQuery('.community-show-trigger').removeClass('community-show-trigger').addClass('community-hide-trigger');
	
	jQuery('.community-show').show();
	
	//for interim piece, 
	
	//add click handler back in
	jQuery('.community-hide-trigger').off("click", hideCommunityTrigger );
	jQuery('.community-hide-trigger').on("click", hideCommunityTrigger );
	jQuery('.community-show-trigger').off("click", showCommunityTrigger);
	jQuery('.community-show-trigger').on("click", showCommunityTrigger);
	
	//recalculate the number of top 3, priorities visible
	showTheRightStars('community-show');
	
	countTop3();
	countPriorities();
}
function hideCommunityTrigger(e){
	//make changes to the SHOW/HIDE button
	jQuery('.community-hide-trigger').html("SHOW COMMUNITY");
	jQuery('.community-hide-trigger').removeClass('community-hide-trigger').addClass('community-show-trigger');
	
	//since this removed columns, let's see if any top 3 are selected in the to-be-hidden
	var selectedObj = jQuery('tr.top-3-row th.community-show.selected-top-3');
	
	//hide-non-community data
	jQuery('.community-show').hide();
	
	//although there should only be one..
	selectedObj.each( function( index, element ) {
		//get data (tells us which top 3 class is highlighted)
		var top3element = jQuery(element).data("top3group");
		var top3elementName = jQuery(element).data("top3name");
		
		//filter by top 3 with object (which should UNfilter, while continuing to filter state/affiliate
		filterByTop3( jQuery(element), top3element, top3elementName);
		//alert(top3element);
	});
	
	

	//reinstantiate the click handler
	jQuery('.community-show-trigger').off("click", showCommunityTrigger);
	jQuery('.community-show-trigger').on("click", showCommunityTrigger);
	jQuery('.community-hide-trigger').off("click", hideCommunityTrigger );
	jQuery('.community-hide-trigger').on("click", hideCommunityTrigger );
	
}

function hideSchoolTrigger(e){
	//make visual changes to SHOW/HIDE button
	jQuery('.school-hide-trigger').html("SHOW SCHOOL");
	jQuery('.school-hide-trigger').removeClass('school-hide-trigger').addClass('school-show-trigger');
	
	//since this function removes columns, let's see if any top 3 are selected in the to-be-hidden
	var selectedObj = jQuery('tr.top-3-row th.school-show.selected-top-3');
	
	//hide non-school data
	jQuery('.school-show').hide();
	
	//although there should only be one..
	selectedObj.each( function( index, element ) {
		//get data (tells us which top 3 class is highlighted)
		var top3element = jQuery(element).data("top3group");
		var top3elementName = jQuery(element).data("top3name");
		
		//filter by top 3 with object (which should UNfilter, while continuing to filter state/affiliate
		filterByTop3( jQuery(element), top3element, top3elementName);
	});
	
	//reinstantiate the click handler
	jQuery('.school-show-trigger').off("click", showSchoolTrigger);
	jQuery('.school-show-trigger').on("click", showSchoolTrigger);
	jQuery('.school-hide-trigger').off("click", hideSchoolTrigger );
	jQuery('.school-hide-trigger').on("click", hideSchoolTrigger );
}
function showSchoolTrigger(){
	jQuery('.school-show-trigger').html("HIDE SCHOOL");
	jQuery('.school-show-trigger').removeClass('school-show-trigger').addClass('school-hide-trigger');
	jQuery('.school-show').show();
	
	//add click handler back in
	jQuery('.school-hide-trigger').off("click", hideSchoolTrigger );
	jQuery('.school-hide-trigger').on("click", hideSchoolTrigger );
	jQuery('.school-show-trigger').off("click", showSchoolTrigger);
	jQuery('.school-show-trigger').on("click", showSchoolTrigger);
	
	//recalculate the number of top 3, priorities visible
	showTheRightStars('school-show');
	
	countTop3();
	countPriorities();
}

function showCareTrigger(){
	jQuery('.care-show-trigger').html("HIDE CARE");
	jQuery('.care-show-trigger').removeClass('care-show-trigger').addClass('care-hide-trigger');
	jQuery('.care-show').show();
	
	//redo click handlers, since they are duplicating
	jQuery('.care-hide-trigger').off("click", hideCareTrigger );
	jQuery('.care-hide-trigger').on("click", hideCareTrigger );
	jQuery('.care-show-trigger').off("click", showCareTrigger);
	jQuery('.care-show-trigger').on("click", showCareTrigger);
	
	//recalculate the number of top 3, priorities visible
	showTheRightStars('care-show');
	
	countTop3();
	countPriorities();
}
function hideCareTrigger(){
	//make visual changes to SHOW/HIDE button
	jQuery('.care-hide-trigger').html("SHOW CARE");
	jQuery('.care-hide-trigger').removeClass('care-hide-trigger').addClass('care-show-trigger');
	
	//since this function removes columns, let's see if any top 3 are selected in the to-be-hidden
	var selectedObj = jQuery('tr.top-3-row th.care-show.selected-top-3');
	
	//hide non-care data
	jQuery('.care-show').hide();

	//although there should only be one..
	selectedObj.each( function( index, element ) {
		//get data (tells us which top 3 class is highlighted)
		var top3element = jQuery(element).data("top3group");
		var top3elementName = jQuery(element).data("top3name");
		
		//filter by top 3 with object (which should UNfilter, while continuing to filter state/affiliate
		filterByTop3( jQuery(element), top3element, top3elementName);
	});
	
	//reinstantiate the click handler
	jQuery('.care-show-trigger').off("click", showCareTrigger);
	jQuery('.care-show-trigger').on("click", showCareTrigger);
	jQuery('.care-hide-trigger').off("click", hideCareTrigger );
	jQuery('.care-hide-trigger').on("click", hideCareTrigger );
}

function showAllTrigger(){
	jQuery('.community-show-trigger').html("HIDE COMMUNITY");
	jQuery('.community-show-trigger').removeClass('community-show-trigger').addClass('community-hide-trigger');
	
	jQuery('.school-show-trigger').html("HIDE SCHOOL");
	jQuery('.school-show-trigger').removeClass('school-show-trigger').addClass('school-hide-trigger');
	
	jQuery('.care-show-trigger').html("HIDE CARE");
	jQuery('.care-show-trigger').removeClass('care-show-trigger').addClass('care-hide-trigger');
	
	jQuery('.community-show').fadeIn();
	jQuery('.school-show').fadeIn();
	jQuery('.care-show').fadeIn();
	
	//add click handlers back in
	jQuery('.community-hide-trigger').on("click", hideCommunityTrigger );
	jQuery('.school-hide-trigger').on("click", hideSchoolTrigger );
	jQuery('.care-hide-trigger').on("click", hideCareTrigger );
	
	//recalculate the number of top 3 visible
	showTheRightStars('all');
	
	countTop3();
	countPriorities();
}

function filterByState( state, fromTop3 ){
	//set default value for fromTop3, if not passed in
	fromTop3 = fromTop3 || false;
	
	//get other filters
	//if a state is selected, we will need to filter for it
	//Nope - Affiliate/State filters cancel each other out as of 7 Oct 2014
	//var affiliateName = jQuery('#affiliate-dropdown').val();
	
	//if a top 3 is selected, we will need to filter for it
	var top3selected = jQuery('tr.top-3-row').find('.selected-top-3');
	
	if ( !( jQuery("tr.board-data." + state + "" ).is(":visible") ) && ( state != "-1") ) {
		jQuery("tr.board-data." + state + "" ).show();
	}
	
	if ( state != "-1" ) {
		jQuery("tr.board-data:not(." + state + " )").hide();
		
		//change print text to reflect state and ALL affiliates
		jQuery('ul#geography .state').html( state );
		jQuery('ul#geography .affiliate').html('All');
		
		//if an affiliate is also selected, hide other rows
		//Nope - Affiliate/State filters cancel each other out as of 7 Oct 2014
		/*if ( affiliateName != "-1" ) {
			jQuery("tr.board-data:not(." + affiliateName + " )").hide();
		}*/
	} else {  //show all
		jQuery("tr.board-data").show();
		//change the print-only input to show all states
		jQuery('ul#geography .state').html('All');
		
		//if an affiliate is also selected, hide other rows
		//Nope - Affiliate/State filters cancel each other out as of 7 Oct 2014
		/*if ( affiliateName != "-1" ) {
			jQuery("tr.board-data:not(." + affiliateName + " )").hide();
		}*/
	}
	
	//if we're not coming from top 3, run that filter, too
	if ( fromTop3 == false ){
		//now filter the results for top 3
		//top3selected.data("top3group");
		var whichTop3 = top3selected.data("top3group");
		
		if( whichTop3 != undefined ) {
			jQuery("tr.board-data:not(:has(." + whichTop3 + " ))").hide();
		}
			
		//replace the 'Board Priority' print text with the data-top3name
		//jQuery("ul#top3 .board-priority").html( whichTop3Name );
		//TODO: check print filter name things
		
		//recalculate the number of top 3 visible
		countTop3();
		
		//Affiliate/State filters cancel each other out (again) as of 7 Oct 2014
		//change the affiliate drop down to -1
		jQuery('#affiliate-dropdown').val("-1");
	}
	
	
	
}

function filterByAffiliate( affiliate, fromTop3 ){
	//set default value for fromTop3, if not passed in
	fromTop3 = fromTop3 || false;
	
	var affilateName;
	//get other filters
	//if a state is selected, we will need to filter for it
	//Nope - Affiliate/State filters cancel each other out as of 7 Oct 2014
	//var stateName = jQuery('#state-dropdown').val();
	
	//if a top 3 is selected, we will need to filter for it
	var top3selected = jQuery('tr.top-3-row').find('.selected-top-3');
	
	//if the just-selected affiliate rows aren't visible, show them
	/*if ( !( jQuery("tr.board-data." + affiliate + "" ).is(":visible") ) && ( affiliate != "-1") ) {
		//jQuery("tr.board-data." + affiliate + "" ).fadeIn();
		jQuery("tr.board-data." + affiliate + "" ).show();
	}*/
	if ( affiliate != "-1" ) {
		//show all required affiliate rows
		jQuery("tr.board-data." + affiliate ).show();
		
		//if an affiliate is selected, hide others
		//jQuery("tr.board-data:not(." + affiliate + " )").fadeOut();
		jQuery("tr.board-data:not(." + affiliate + " )").hide();
		
		//update the print filter text
		//jQuery('ul#geography .affiliate').html( affiliate ); //this is value, not name
		affilateName = jQuery('th.affiliate-select select option:selected').text();
		affilateName = affilateName.replace('See all Affiliates','');
		jQuery('ul#geography .affiliate').html( affilateName );
		jQuery('ul#geography .state').html('All');
		
		//if a state is also selected, hide other rows
		//Nope - Affiliate/State filters cancel each other out as of 7 Oct 2014
		/*if ( stateName != "-1" ) {
			jQuery("tr.board-data:not(." + stateName + " )").hide();
		}*/
		
	} else {
		//jQuery("tr.board-data").fadeIn();
		jQuery("tr.board-data").show();
		
		//change the print-only input to show all affiliates
		jQuery('ul#geography .affiliate').html('All');
		
		//if a state is also selected, hide other rows
		//Affiliate/State filters cancel each other out as of 7 Oct 2014
		/*if ( stateName != "-1" ) {
			jQuery("tr.board-data:not(." + stateName + " )").hide();
		}*/
	}
	

	//if we're not coming from top3, run that filter, too
	if ( fromTop3 == false ){
		//now filter the results for top 3
		//top3selected.data("top3group");
		var whichTop3 = top3selected.data("top3group");
		
		if( whichTop3 != undefined ) {
			jQuery("tr.board-data:not(:has(." + whichTop3 + " ))").hide();
		}
			
		//replace the 'Board Priority' print text with the data-top3name
		//do we need to do this here?  TODO: check print filter info
		//jQuery("ul#top3 .board-priority").html( whichTop3Name );
		
		//recalculate the number of top 3 visible
		countTop3();
	
	
	}	
	
	//Affiliate/State filters cancel each other out (again) as of 7 Oct 2014
	//change the affiliate drop down to -1
	jQuery('#state-dropdown').val("-1");
	
	
}

function filterByTop3( thisObj, whichTop3Yes, whichTop3Name ) {

	//show all boards (fadeIn having rendering issues with background images not showing in FF)
	jQuery("tr.board-data").show();
	
	//filter by state OR affliate (7 Oct AHA chagne..)
	var stateName = jQuery('#state-dropdown').val();
	if ( stateName == '-1') {
		var affiliateName = jQuery('#affiliate-dropdown').val();
		//filter by state/affiliate, if necessary
		filterByAffiliate( affiliateName, true );
	} else {
	
		filterByState( stateName, true );
	}
	
	//we are deselecting ANY top 3 and showing all boards, minus geography filters (state, afflilate)
	if ( thisObj.hasClass('selected-top-3') ){
		thisObj.removeClass('selected-top-3');
				
		//replace the 'Board Priority' print text with 'None'
		jQuery("ul#top3 .board-priority").html('None Selected');
		
		//recalculate the number of top 3 visible
		countTop3();
		
	} else { //we are selecting a top 3 and hiding other board rows
		allTop3Buttons = jQuery('tr.top-3-row th');
		allTop3Buttons.removeClass('selected-top-3');
		thisObj.addClass('selected-top-3');
	
		//hide the rows that do not have the top 3 class
		jQuery("tr.board-data:not(:has(." + whichTop3Yes + " ))").hide();
		
		//replace the 'Board Priority' print text with the data-top3name
		jQuery("ul#top3 .board-priority").html( whichTop3Name );
		
		//console.log('top 3 count');
		//recalculate the number of top 3 visible
		//countTop3();
	}

}

//are we looking at Board-approved or proposed priorities?
function getPriorityLevel() {

	return jQuery( "select.priority-select option:selected").val();

}

//change which priorities are visible
function changePriorityLevel( priorityLevel ){
	
	if ( priorityLevel == "potential" ){
		jQuery("th.report-card-priority").hide();
		jQuery("th.report-card-top3").show();
		
		//now, go through table and remove stars from priority
		jQuery("tbody td[class*=-priority]").removeClass("has-star");
		jQuery("tbody td[class*=-top-3]").addClass("has-star");
		
		//change legend language
		jQuery(".legend .board-considering").html("= Board is considering as a possible priority");
		jQuery(".legend .board-not-considering").html("= Board is not considering as a priority at this time");
	} else {
		jQuery("th.report-card-top3").hide();
		jQuery("th.report-card-priority").show();
		
		//now, go through table and remove stars from top-3
		jQuery("tbody td[class*=-top-3]").removeClass("has-star");
		jQuery("tbody td[class*=-priority]").addClass("has-star");
		
		//change legend language
		jQuery(".legend .board-considering").html("= Board has approved as a priority");
		jQuery(".legend .board-not-considering").html("= Board is not considering as a priority at this time");
	}

}

//show the correct header stars on community-, care- and school-show
function showTheRightStars( which ){

	var priorityLevel = getPriorityLevel();
	
	switch( which ){
		case "community-show":
			if ( priorityLevel == "approved" ){
				jQuery("th.community-show.report-card-top3").hide();
				jQuery("th.community-show.report-card-priority").show();
			} else {
				jQuery("th.community-show.report-card-priority").hide();
				jQuery("th.community-show.report-card-top3").show();
			}
			break;
		case "school-show":
			if ( priorityLevel == "approved" ){
				jQuery("th.school-show.report-card-top3").hide();
				jQuery("th.school-show.report-card-priority").show();
			} else {
				jQuery("th.school-show.report-card-priority").hide();
				jQuery("th.school-show.report-card-top3").show();
			}
			
			break;
		
		case "care-show":
			if ( priorityLevel == "approved" ){
				jQuery("th.care-show.report-card-top3").hide();
				jQuery("th.care-show.report-card-priority").show();
			} else {
				jQuery("th.care-show.report-card-priority").hide();
				jQuery("th.care-show.report-card-top3").show();
			}
			
			break;
		case "all":
			if ( priorityLevel == "approved" ){
				jQuery("th.community-show.report-card-top3").hide();
				jQuery("th.community-show.report-card-priority").show();
				jQuery("th.school-show.report-card-top3").hide();
				jQuery("th.school-show.report-card-priority").show();
				jQuery("th.care-show.report-card-top3").hide();
				jQuery("th.care-show.report-card-priority").show();
			} else {
				jQuery("th.community-show.report-card-priority").hide();
				jQuery("th.community-show.report-card-top3").show();
				jQuery("th.school-show.report-card-priority").hide();
				jQuery("th.school-show.report-card-top3").show();
				jQuery("th.care-show.report-card-priority").hide();
				jQuery("th.care-show.report-card-top3").show();
			}
			
			break;
	}


}

//count the potential priorities visible and put that info in a div
function countTop3(){

	//get all th in top-3-row with class ending in -top-3 (report-card-table only, NOT report-card-table sticky
	var allTop3th = jQuery('#report-card-table tr.top-3-row th[class*=-top-3]');
	var top3group;
	var top3grouptd;
	var top3groupsize;
	
	allTop3th.each( function() {
		top3group = jQuery(this).data("top3group");
		
		//get number of tds with this top 3 group
		top3grouptd = jQuery('#report-card-table td.' + top3group + ':visible')
		
		//how many in table
		top3groupsize = top3grouptd.size();
		
		//update html to reflect number
		jQuery(this).find('.top-3-count').html( top3groupsize );
	
	});
	
}

function countPriorities(){

	//get all th in top-3-row with class ending in -top-3 (report-card-table only, NOT report-card-table sticky
	var allPriorityth = jQuery('#report-card-table tr.top-3-row th[class*=-priority]');
	var prioritygroup;
	var prioritygrouptd;
	var prioritygroupsize;
	
	allPriorityth.each( function() {
		prioritygroup = jQuery(this).data("prioritygroup");
		
		//get number of tds with this top 3 group
		prioritygrouptd = jQuery('#report-card-table td.' + prioritygroup + ':visible')
		
		//how many in table
		prioritygroupsize = prioritygrouptd.size();
		
		//update html to reflect number
		jQuery(this).find('.priority-count').html( prioritygroupsize );
	
	});
	
}

//NOPE:add stars or border to all top-3 tbody tds
function top3stars(){

	//get top 3 tbody tds
	var top3boxes = jQuery('tr.board-data').children('[class*=top-3]');
	var innerbox;
	//var starImage = jQuery("img.star-image").attr("src");
	//var starImage = jQuery("img.star-image").data("lazy-src");
	top3boxes.each( function() {
		//if ( jQuery(this).is(":visible") ) {
		
			innerbox = jQuery(this).find(".top-3-image");
			
			//innerbox.html("<img src='" + starImage + "'>");
			innerbox.removeClass('hidden');
		//}

	});
}

jQuery(document).ready(function($){
	var options = {
		widgets: [ 'stickyHeaders' ],
		widgetOptions: {

		  // extra class name added to the sticky header row
		  stickyHeaders : 'tablesorter-stickyHeader',
		
		  
		}
  };
	//let tablesorter know we want to sort this guy
	$("#report-card-table").tablesorter(options); 

	reportCardClickListen();

	countTop3();
	countPriorities();
	
	//on init, place priority stars
	changePriorityLevel( ); //default is approved
	
	//top3stars();
	
	
},(jQuery));

