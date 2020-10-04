(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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
	$(function(){
		let tag_type_tag = $("select[name='tag-type']");
		let meta_tag = $(".form-field.type_meta");

		tag_type_tag.val(tag_type_tag.data('value'));
		tag_type_tag.on("change", function(){
			let type = tag_type_tag.val()
			if(type === "date" || type === "text"){
				meta_tag.hide();
				return;
			}
			meta_tag.show();		
		})
		let type = tag_type_tag.val()
		if(type === "date" || type === "text"){
			meta_tag.hide();
		}else{
			meta_tag.show();
		}

		let meta_input = $("select[name='tag-meta']");
		let meta_value = meta_input.val();
		meta_input.tagsinput('add', meta_input);

		let priority_tag = $("select[name='tag-priority']");
		priority_tag.val(priority_tag.data('value'));

		let required_tag = $("select[name='tag-required']");
		required_tag.val(required_tag.data('value'));


	});
	
})( jQuery );
