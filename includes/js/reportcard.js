




function showCommunityTrigger(){
	jQuery('.community-show-trigger').html("HIDE COMMUNITY");
	jQuery('.community-show-trigger').removeClass('community-show-trigger').addClass('community-hide-trigger');
	jQuery('.community-show').fadeIn();
	
	//add click handler back in
	jQuery('.community-hide-trigger').on("click", hideCommunityTrigger );
}

function hideCommunityTrigger(){

	jQuery('.community-hide-trigger').html("SHOW COMMUNITY");
	jQuery('.community-hide-trigger').removeClass('community-hide-trigger').addClass('community-show-trigger');
	jQuery('.community-show').fadeOut();

	//reinstantiate the click handler
	jQuery('.community-show-trigger').on("click", showCommunityTrigger);
}

function hideSchoolTrigger(){

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










jQuery(document).ready(function($){
	
	//let tablesorter know we want to sort this guy
	$("#report-card-table").tablesorter(); 

	//TODO: put delays on the subsequent handlers since OMG data
	jQuery('.community-show-trigger').on("click", showCommunityTrigger);
	jQuery('.community-hide-trigger').on("click", hideCommunityTrigger );
	
	jQuery('.school-show-trigger').on("click", showSchoolTrigger);
	jQuery('.school-hide-trigger').on("click", hideSchoolTrigger );
	
	jQuery('.care-hide-trigger').on("click", hideCareTrigger );
	jQuery('.care-show-trigger').on("click", showCareTrigger);
	
	
},(jQuery));

