


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
	
	//is top 3 selected?
	jQuery('tr.top-3-row th').click(function(){
		var whichTop3Yes = jQuery(this).data("top3group");
		var whichTop3Name = jQuery(this).data("top3name");
		
		//we are deselecting ANY top 3 and showing all boards
		if ( jQuery(this).hasClass('selected-top-3') ){
			jQuery(this).removeClass('selected-top-3');
			
			//show all boards (fadeIn having rendering issues, sadly)
			jQuery("tr.board-data").show();
			
			//replace the 'Board Priority' print text with 'None'
			jQuery("ul#top3 .board-priority").html('None Selected');
			
		} else { //we are selecting a top 3 and hiding other board rows
			allTop3Buttons = jQuery('tr.top-3-row th');
			allTop3Buttons.removeClass('selected-top-3');
			jQuery(this).addClass('selected-top-3');
			
			jQuery("tr.board-data").fadeIn();
			jQuery("tr.board-data:not(:has(." + whichTop3Yes + " ))").fadeOut();
			
			//replace the 'Board Priority' print text with the data-top3name
			jQuery("ul#top3 .board-priority").html( whichTop3Name );
			
		}
		
		//console.log(whichTop3Yes);
		jQuery('#affiliate-dropdown').val("-1");
		jQuery('#state-dropdown').val("-1");
		jQuery('ul#geography .affiliate').html('All');
		jQuery('ul#geography .state').html('All');
				
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
	jQuery('.community-show').fadeIn();
	
	//add click handler back in
	jQuery('.community-hide-trigger').on("click", hideCommunityTrigger );
}

function hideCommunityTrigger(e){
	jQuery('.community-hide-trigger').html("SHOW COMMUNITY");
	jQuery('.community-hide-trigger').removeClass('community-hide-trigger').addClass('community-show-trigger');
	jQuery('.community-show').fadeOut();

	//reinstantiate the click handler
	jQuery('.community-show-trigger').on("click", showCommunityTrigger);
}

function hideSchoolTrigger(e){

	jQuery('.school-hide-trigger').html("SHOW SCHOOL");
	jQuery('.school-hide-trigger').removeClass('school-hide-trigger').addClass('school-show-trigger');
	jQuery('.school-show').fadeOut();

	//reinstantiate the click handler
	jQuery('.school-show-trigger').on("click", showSchoolTrigger);
}
function showSchoolTrigger(){
	jQuery('.school-show-trigger').html("HIDE SCHOOL");
	jQuery('.school-show-trigger').removeClass('school-show-trigger').addClass('school-hide-trigger');
	jQuery('.school-show').fadeIn();
	
	//add click handler back in
	jQuery('.school-hide-trigger').on("click", hideSchoolTrigger );
}

function showCareTrigger(){
	jQuery('.care-show-trigger').html("HIDE CARE");
	jQuery('.care-show-trigger').removeClass('care-show-trigger').addClass('care-hide-trigger');
	jQuery('.care-show').fadeIn();
	
	//add click handler back in
	jQuery('.care-hide-trigger').on("click", hideCareTrigger );
}
function hideCareTrigger(){

	jQuery('.care-hide-trigger').html("SHOW CARE");
	jQuery('.care-hide-trigger').removeClass('care-hide-trigger').addClass('care-show-trigger');
	jQuery('.care-show').fadeOut();

	//reinstantiate the click handler
	jQuery('.care-show-trigger').on("click", showCareTrigger);
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
}

function filterByState( state ){
	//console.log( state ); //works!
	
	if ( !( jQuery("tr.board-data." + state + "" ).is(":visible") ) && ( state != "-1") ) {
		jQuery("tr.board-data." + state + "" ).fadeIn();
	}
	if ( state != "-1" ) {
		jQuery("tr.board-data:not(." + state + " )").fadeOut();
		jQuery('ul#geography .state').html( state );
		jQuery('ul#geography .affiliate').html('All');
	} else {
		jQuery("tr.board-data").fadeIn();
		//change the print-only input to show all states
		jQuery('ul#geography .state').html('All');
	}
	
	//change the affiliate drop down to -1
	jQuery('#affiliate-dropdown').val("-1");
	
	//remove visual filtering from top3
	allTop3Buttons = jQuery('tr.top-3-row th');
	allTop3Buttons.removeClass('selected-top-3');
}

function filterByAffiliate( affiliate ){
	//console.log( affiliate ); //works!
	
	if ( !( jQuery("tr.board-data." + affiliate + "" ).is(":visible") ) && ( affiliate != "-1") ) {
		jQuery("tr.board-data." + affiliate + "" ).fadeIn();
	}
	if ( affiliate != "-1" ) {
		jQuery("tr.board-data:not(." + affiliate + " )").fadeOut();
		jQuery('ul#geography .affiliate').html( affiliate );
		jQuery('ul#geography .state').html('All');
	} else {
		jQuery("tr.board-data").fadeIn();
		//change the print-only input to show all affiliates
		jQuery('ul#geography .affiliate').html('All');
	}
	
	//change the state drop down to -1
	jQuery('#state-dropdown').val("-1");
	
	//remove visual filtering from top3
	allTop3Buttons = jQuery('tr.top-3-row th');
	allTop3Buttons.removeClass('selected-top-3');
}

//add stars or border to all top-3 tbody tds
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

	top3stars();
	
	
},(jQuery));

