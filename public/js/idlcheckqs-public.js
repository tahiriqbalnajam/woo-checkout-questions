(function( $ ) {
	'use strict';
	$(document).ready(function(){
	  $("#customised_field_medical_conditions").change(function(){
	    var abc = $("#customised_field_medical_conditions").children("option:selected").val();
	    if (abc == "No") {
	    	$("#customised_field_medical_conditions_describe").val("No");
	    	//$("#customised_field_medical_conditions_describe").append("textMore");
	    }
	    else if (abc == "Yes") {
	    	$("#customised_field_medical_conditions_describe").val("");
	    	//$("#customised_field_medical_conditions_describe").append("textMore");
	    }
	     $("#customised_field_medical_conditions_describe_field").toggle();
	  });

	  $("#customised_field_medicines").change(function(){
	    var abc = $("#customised_field_medicines").children("option:selected").val();
	    if (abc == "No") {
	    	$("#customised_field_medicines_describe").val("No");
	    	//$("#customised_field_medicines_describe").append("textMore");
	    }
	    else if (abc == "Yes") {
	    	$("#customised_field_medicines_describe").val("");
	    	//$("#customised_field_medicines_describe").append("textMore");
	    }
	     $("#customised_field_medicines_describe_field").toggle();
	  });

	});
})( jQuery );
