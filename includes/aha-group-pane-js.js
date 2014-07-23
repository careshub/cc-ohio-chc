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
// Target question should have the class "follow-up-question"
// Will need a page-load refresh method to set visibilities, too.
	$('#aha-assessment-form').on( 'change', '.has-follow-up', function() {
		alert( $( this ).text() );
	});

},(jQuery));