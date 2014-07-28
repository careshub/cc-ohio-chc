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


// Generalized click relation
// requires trigger input to have this markup: class="has-follow-up" and data-relatedQuestion="id_of_follow_up_q"
// Target question should have the class "follow-up-question" and be wrapped in a div with the attribute: data-relatedTarget="2.2.2.2"

	// On page load, refresh element visibility.
	refresh_follow_up_question_visibility();

	// When a watched input changes, refresh visbility.
	$('.has-follow-up').on( 'change', function() {
		refresh_follow_up_question_visibility();
	});

	function refresh_follow_up_question_visibility(){
		$( '.has-follow-up' ).each( function() {
			// We only want to run the refresh on elements with the special data attribute.
			if ( target_id = $( this ).data( 'relatedquestion' ) ) {
				console.log( target_id );

				if ( $( this ).prop( "checked" ) ) {
					$('.follow-up-question[data-relatedTarget="' + target_id + '"]').addClass('enabled');
				} else {
					$('.follow-up-question[data-relatedTarget="' + target_id + '"]').removeClass('enabled');
				}
			}
		});

	}

},(jQuery));