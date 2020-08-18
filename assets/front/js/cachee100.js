jQuery(document).ready(function($){
	var $classifieds_clear_cache_text = $('.classifieds_clear_cache a').text();
	$(document).on( 'click', '.classifieds_clear_cache a', function(e){
		e.preventDefault();
		$('.classifieds_clear_cache a').text( '...' );
		$.ajax({
			url: ajaxurl,
			data:{
				action: 'classifieds_clear_cache'
			},
			success: function(){
				$('.classifieds_clear_cache a').text( $classifieds_clear_cache_text );
			}
		})
	});
});