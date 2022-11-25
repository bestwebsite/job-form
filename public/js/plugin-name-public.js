(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 jQuery(document).ready(function(){
		jQuery(".date_field").datetimepicker({
			format: "m/d/Y",
			timepicker: false,
		});
		$('#jobsTable').DataTable();
	});
	jQuery(document).ready(function(){
		jQuery("#submit_job").on("click", function(){
			var ajaxurl = ajax_object.ajaxurl;
			jQuery("#jobloader").show();
			var jobdata = {
				"action" 					: 	"frontend_post_job",
				"job_title" 				: 	jQuery("#job_title").val(),
				"full_name" 				: 	jQuery("#full_name").val(),
				"company" 					: 	jQuery("#company").val(),
				"job_location"				:	jQuery("#job_location").val(),
				"job_description"			:	jQuery("#job_description").val(),
				"about_us"					:	jQuery("#about_us").val(),
				"major_function"			:	jQuery("#major_function").val(),
				"job_qualifications"		:	jQuery("#job_qualifications").val(),
				"education_and_experience"	:	jQuery("#education_and_experience").val(),
				"additional_information"	:	jQuery("#additional_information").val(),
				"position_application"		:	jQuery("#position_application").val(),
				"application_deadline"		:	jQuery("#application_deadline").val(),
				"apply_url"					:	jQuery("#apply_url").val(),
				"apply_email"				:	jQuery("#apply_email").val(),
				"job_category"				:	jQuery("#job_category").val(),
				"job_employment_type"		:	jQuery("#job_employment_type").val(),
				"job_type"					:	jQuery("#job_type").val(),
				"topic"						:	jQuery("#topic").val()
			};
			jQuery.post(ajaxurl, jobdata, function(result){
				if(result && result === "job added"){
					jQuery("#jobform").hide();
					jQuery("#jobadded").show();
				}else{
					alert("Some error occured, please try again with all fields filled");
				}
				jQuery("#jobloader").hide();
			});
		});
	});

})( jQuery );
