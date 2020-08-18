jQuery(document).ready(function($){
	"use strict";
	$(document).on('change', '.custom-select-change', function(){
		var $this = $(this);
		var val = $this.val();
		var text = $this.find('option[value="'+val+'"]').text();

		$this.find('option').each(function(){
			var val = $(this).val();
			var text = $(this).text();

			$('div.'+val+'-'+text).attr('name', '').parent().addClass('hidden');
		});

		$('div.'+val+'-'+text).parent().removeClass('hidden');
		$('div.'+val+'-'+text+' select').attr('name', $('div.'+val+'-'+text+' select').data('name') );
	});

	function cf_init(){
		$('.custom-select-change').each(function(){
			var $this = $(this);
			var val = $this.val();
			if( val !== '' ){
				var text = $this.find('option[value="'+val+'"]').text();
				$('div.'+val+'-'+text).parent().removeClass('hidden');
			}
		});

		$('.custom-select-wrap').each(function(){
			var $this = $(this);
			var $field = $this.find( 'select' );
			$field.data( 'name', $field.attr('name') );
			if( $this.parent().hasClass('hidden') ){
				$field.attr( 'name', '' );
			}
		});	
	}
	cf_init();

	$('#ad-categorychecklist input').change(function(){
		var cats_list = [];
		$('#ad-categorychecklist input').each(function(){
			if( $(this).prop('checked') ){
				cats_list.push( $(this).val() );
			}
		});

		$.ajax({
			url: ajaxurl,
			method: 'POST',
			data: {
				cats: cats_list.join(','),
				post_id: $('#post_ID').val(),
				action: 'classifieds_cf'
			},
			success: function(response){
				$('#classifieds_custom_meta_ad .inside').html( response );
				cf_init();
			}
		})
	});

	$('#ad_category').change(function(){
		var val = $(this).val();
		if( val !== '' ){
			$.ajax({
				url: ajaxurl,
				method: 'POST',
				data: {
					cats: val,
					post_id: $('input[name="post_id"]').val(),
					action: 'classifieds_cf'
				},
				success: function( response ){
					$('.custom-fields-holder').html( response );
					cf_init();
				}
			});
		}
	});	
});