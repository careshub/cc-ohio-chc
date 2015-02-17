jQuery(document).ready(function($){
	
	$(".gform_wrapper .disable input").attr('disabled','disabled');
	$( ".gf_ohio_question" ).css( "min-width", "300px" );	
	$( ".gf_ohio_question" ).css( "max-width", "300px" );
	$( ".gf_ohio_entrybox" ).css( "min-width", "300px" );	
		
	//Section I	
	var arrSec1 = new Array();
	for (var i=1; i < 23; i++) {
		arrSec1.push(i);	
	}
		
	var col1_tot = 0;
	var col2_tot = 0;
	var col3_tot = 0;
	var col4_tot = 0;
	var col5_tot = 0;	
		
	$.each( arrSec1, function( i, val ) {	
		if( !$("li.sec1_" + val + " input.small").val() ) {
			$("li.sec1_" + val + " input.small").val(0);
		}
		
		
		
		$("li.sec1_" + val + " input.small").keydown(function (e) {
			// Allow: backspace, delete, tab, escape, enter and .
			if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
				 // Allow: Ctrl+A
				(e.keyCode == 65 && e.ctrlKey === true) || 
				 // Allow: home, end, left, right, down, up
				(e.keyCode >= 35 && e.keyCode <= 40)) {
					 // let it happen, don't do anything
					 return;
			}
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});
		
		clearInputs("1", val);		
		
		if ( !$("li.sec1_" + val + "_T input.small").val() ) {
			var ttl = parseInt($("li.sec1_" + val + "_1 input.small").val()) + parseInt($("li.sec1_" + val + "_2 input.small").val()) + parseInt($("li.sec1_" + val + "_3 input.small").val()) + parseInt($("li.sec1_" + val + "_4 input.small").val());
			$("li.sec1_" + val + "_T input.small").val(ttl);		
		}
		
		//for sec_1_3, just find the max entry and display in YTD Total, else add up all numbers.
		if (val == 3) {
			$("li.sec1_" + val + " input.small").blur(function() {
				var arr = [parseInt($("li.sec1_" + val + "_1 input.small").val()), parseInt($("li.sec1_" + val + "_2 input.small").val()), parseInt($("li.sec1_" + val + "_3 input.small").val()), parseInt($("li.sec1_" + val + "_4 input.small").val())];
				var maxinput = Math.max.apply(Math,arr);
				$("li.sec1_" + val + "_T input.small").val(maxinput);
			});
		} else {
			$("li.sec1_" + val + " input.small").blur(function() {
				var ttl = parseInt($("li.sec1_" + val + "_1 input.small").val()) + parseInt($("li.sec1_" + val + "_2 input.small").val()) + parseInt($("li.sec1_" + val + "_3 input.small").val()) + parseInt($("li.sec1_" + val + "_4 input.small").val());
				$("li.sec1_" + val + "_T input.small").val(ttl);		
			
				calcImpacted(val);
			});		
		}
		
	});
	
	$("li.sec1_19_3 input.small").trigger('blur');
	
	
	//Section II
	var arrSec2 = new Array();
	for (var j=1; j < 75; j++) {
		arrSec2.push(j);	
	}	
	
	$.each( arrSec2, function( j, val2 ) {	
		if( !$("li.sec2_" + val2 + " input.small").val() ) {
			$("li.sec2_" + val2 + " input.small").val(0);
		}
		
		$("li.sec2_" + val2 + " input.small").keydown(function (e) {
			// Allow: backspace, delete, tab, escape, enter and .
			if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
				 // Allow: Ctrl+A
				(e.keyCode == 65 && e.ctrlKey === true) || 
				 // Allow: home, end, left, right, down, up
				(e.keyCode >= 35 && e.keyCode <= 40)) {
					 // let it happen, don't do anything
					 return;
			}
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});		
		
		
		clearInputs("2", val2);
		
		if ( !$("li.sec2_" + val2 + "_T input.small").val() ) {
			var ttl2 = parseInt($("li.sec2_" + val2 + "_1 input.small").val()) + parseInt($("li.sec2_" + val2 + "_2 input.small").val()) + parseInt($("li.sec2_" + val2 + "_3 input.small").val()) + parseInt($("li.sec2_" + val2 + "_4 input.small").val());
			$("li.sec2_" + val2 + "_T input.small").val(ttl2);		
		}
		
		$("li.sec2_" + val2 + " input.small").blur(function() {
			var ttl2 = parseInt($("li.sec2_" + val2 + "_1 input.small").val()) + parseInt($("li.sec2_" + val2 + "_2 input.small").val()) + parseInt($("li.sec2_" + val2 + "_3 input.small").val()) + parseInt($("li.sec2_" + val2 + "_4 input.small").val());
			$("li.sec2_" + val2 + "_T input.small").val(ttl2);		
		

		});
		
	});	
	
	//Section III
	var arrSec3 = new Array();
	for (var h=1; h < 86; h++) {
		arrSec3.push(h);	
	}	
	
	$.each( arrSec3, function( h, val3 ) {	
		if( !$("li.sec3_" + val3 + " input.small").val() ) {
			$("li.sec3_" + val3 + " input.small").val(0);
		}
		
		$("li.sec3_" + val3 + " input.small").keydown(function (e) {
			// Allow: backspace, delete, tab, escape, enter and .
			if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
				 // Allow: Ctrl+A
				(e.keyCode == 65 && e.ctrlKey === true) || 
				 // Allow: home, end, left, right, down, up
				(e.keyCode >= 35 && e.keyCode <= 40)) {
					 // let it happen, don't do anything
					 return;
			}
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});		
		
		
		clearInputs("3", val3);
		
		if ( !$("li.sec3_" + val3 + "_T input.small").val() ) {
			var ttl3 = parseInt($("li.sec3_" + val3 + "_1 input.small").val()) + parseInt($("li.sec3_" + val3 + "_2 input.small").val()) + parseInt($("li.sec3_" + val3 + "_3 input.small").val()) + parseInt($("li.sec3_" + val3 + "_4 input.small").val());
			$("li.sec3_" + val3 + "_T input.small").val(ttl3);		
		}		
		$("li.sec3_" + val3 + " input.small").blur(function() {
			var ttl3 = parseInt($("li.sec3_" + val3 + "_1 input.small").val()) + parseInt($("li.sec3_" + val3 + "_2 input.small").val()) + parseInt($("li.sec3_" + val3 + "_3 input.small").val()) + parseInt($("li.sec3_" + val3 + "_4 input.small").val());
			$("li.sec3_" + val3 + "_T input.small").val(ttl3);		
		});		
	});		
	
	//Section IV
	var arrSec4 = new Array();
	for (var m=1; m < 75; m++) {
		arrSec4.push(m);	
	}	
	
	$.each( arrSec4, function( m, val4 ) {	
		if( !$("li.sec4_" + val4 + " input.small").val() ) {
			$("li.sec4_" + val4 + " input.small").val(0);
		}
		
		$("li.sec4_" + val4 + " input.small").keydown(function (e) {
			// Allow: backspace, delete, tab, escape, enter and .
			if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
				 // Allow: Ctrl+A
				(e.keyCode == 65 && e.ctrlKey === true) || 
				 // Allow: home, end, left, right, down, up
				(e.keyCode >= 35 && e.keyCode <= 40)) {
					 // let it happen, don't do anything
					 return;
			}
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});		
		
		
		clearInputs("4", val4);
		
		if ( !$("li.sec4_" + val4 + "_T input.small").val() ) {
			var ttl4 = parseInt($("li.sec4_" + val4 + "_1 input.small").val()) + parseInt($("li.sec4_" + val4 + "_2 input.small").val()) + parseInt($("li.sec4_" + val4 + "_3 input.small").val()) + parseInt($("li.sec4_" + val4 + "_4 input.small").val());
			$("li.sec4_" + val4 + "_T input.small").val(ttl4);		
		}		
		$("li.sec4_" + val4 + " input.small").blur(function() {
			var ttl4 = parseInt($("li.sec4_" + val4 + "_1 input.small").val()) + parseInt($("li.sec4_" + val4 + "_2 input.small").val()) + parseInt($("li.sec4_" + val4 + "_3 input.small").val()) + parseInt($("li.sec4_" + val4 + "_4 input.small").val());
			$("li.sec4_" + val4 + "_T input.small").val(ttl4);		
		});		
	});	
	
	//Section V
	var arrSec5 = new Array();
	for (var p=1; p < 75; p++) {
		arrSec5.push(p);	
	}	
	
	$.each( arrSec5, function( p, val5 ) {	
		if( !$("li.sec5_" + val5 + " input.small").val() ) {
			$("li.sec5_" + val5 + " input.small").val(0);
		}
		
		$("li.sec5_" + val5 + " input.small").keydown(function (e) {
			// Allow: backspace, delete, tab, escape, enter and .
			if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
				 // Allow: Ctrl+A
				(e.keyCode == 65 && e.ctrlKey === true) || 
				 // Allow: home, end, left, right, down, up
				(e.keyCode >= 35 && e.keyCode <= 40)) {
					 // let it happen, don't do anything
					 return;
			}
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});		
		
		clearInputs("5", val5);
		
		if ( !$("li.sec5_" + val5 + "_T input.small").val() ) {
			var ttl5 = parseInt($("li.sec5_" + val5 + "_1 input.small").val()) + parseInt($("li.sec5_" + val5 + "_2 input.small").val()) + parseInt($("li.sec5_" + val5 + "_3 input.small").val()) + parseInt($("li.sec5_" + val5 + "_4 input.small").val());
			$("li.sec5_" + val5 + "_T input.small").val(ttl5);		
		}		
		$("li.sec5_" + val5 + " input.small").blur(function() {
			var ttl5 = parseInt($("li.sec5_" + val5 + "_1 input.small").val()) + parseInt($("li.sec5_" + val5 + "_2 input.small").val()) + parseInt($("li.sec5_" + val5 + "_3 input.small").val()) + parseInt($("li.sec5_" + val5 + "_4 input.small").val());
			$("li.sec5_" + val5 + "_T input.small").val(ttl5);		
		});		
	});	

	function calcImpacted(val) {
			if (val > 17 && val < 22) {
				col1_tot = parseInt($("li.sec1_18_1 input.small").val()) + parseInt($("li.sec1_19_1 input.small").val()) + parseInt($("li.sec1_20_1 input.small").val()) + parseInt($("li.sec1_21_1 input.small").val());
				col2_tot = parseInt($("li.sec1_18_2 input.small").val()) + parseInt($("li.sec1_19_2 input.small").val()) + parseInt($("li.sec1_20_2 input.small").val()) + parseInt($("li.sec1_21_2 input.small").val());
				col3_tot = parseInt($("li.sec1_18_3 input.small").val()) + parseInt($("li.sec1_19_3 input.small").val()) + parseInt($("li.sec1_20_3 input.small").val()) + parseInt($("li.sec1_21_3 input.small").val());
				col4_tot = parseInt($("li.sec1_18_4 input.small").val()) + parseInt($("li.sec1_19_4 input.small").val()) + parseInt($("li.sec1_20_4 input.small").val()) + parseInt($("li.sec1_21_4 input.small").val());
				col5_tot = parseInt($("li.sec1_18_T input.small").val()) + parseInt($("li.sec1_19_T input.small").val()) + parseInt($("li.sec1_20_T input.small").val()) + parseInt($("li.sec1_21_T input.small").val());
			}		
			
			$("li.sec1_eventQ1_T input.small").val(col1_tot);
			$("li.sec1_eventQ2_T input.small").val(col2_tot);
			$("li.sec1_eventQ3_T input.small").val(col3_tot);
			$("li.sec1_eventQ4_T input.small").val(col4_tot);
			$("li.sec1_eventAll_T input.small").val(col5_tot);	
	}	
	
	function clearInputs(secNo, val) {
		var default_value1 = $("li.sec" + secNo + "_" + val + "_1 input.small").val();	
		var default_value2 = $("li.sec" + secNo + "_" + val + "_2 input.small").val();
		var default_value3 = $("li.sec" + secNo + "_" + val + "_3 input.small").val();
		var default_value4 = $("li.sec" + secNo + "_" + val + "_4 input.small").val();
		
		$("li.sec" + secNo + "_" + val + "_1 input.small").focus(function() {		
			if ($("li.sec" + secNo + "_" + val + "_1 input.small").val() == default_value1) {
				$("li.sec" + secNo + "_" + val + "_1 input.small").val("");
			}			
		});
		$("li.sec" + secNo + "_" + val + "_1 input.small").blur(function() {		
			if (!$("li.sec" + secNo + "_" + val + "_1 input.small").val()) {
				$("li.sec" + secNo + "_" + val + "_1 input.small").val(default_value1);
			}
		});

		$("li.sec" + secNo + "_" + val + "_2 input.small").focus(function() {		
			if ($("li.sec" + secNo + "_" + val + "_2 input.small").val() == default_value2) {
				$("li.sec" + secNo + "_" + val + "_2 input.small").val("");
			}			
		});
		$("li.sec" + secNo + "_" + val + "_2 input.small").blur(function() {		
			if (!$("li.sec" + secNo + "_" + val + "_2 input.small").val()) {
				$("li.sec" + secNo + "_" + val + "_2 input.small").val(default_value2);
			}
		});

		$("li.sec" + secNo + "_" + val + "_3 input.small").focus(function() {		
			if ($("li.sec" + secNo + "_" + val + "_3 input.small").val() == default_value3) {
				$("li.sec" + secNo + "_" + val + "_3 input.small").val("");
			}			
		});
		$("li.sec" + secNo + "_" + val + "_3 input.small").blur(function() {		
			if (!$("li.sec" + secNo + "_" + val + "_3 input.small").val()) {
				$("li.sec" + secNo + "_" + val + "_3 input.small").val(default_value3);
			}
		});

		$("li.sec" + secNo + "_" + val + "_4 input.small").focus(function() {		
			if ($("li.sec" + secNo + "_" + val + "_4 input.small").val() == default_value4) {
				$("li.sec" + secNo + "_" + val + "_4 input.small").val("");
			}			
		});
		$("li.sec" + secNo + "_" + val + "_4 input.small").blur(function() {		
			if (!$("li.sec" + secNo + "_" + val + "_4 input.small").val()) {
				$("li.sec" + secNo + "_" + val + "_4 input.small").val(default_value4);
			}
		});		
	}	

	
},(jQuery));