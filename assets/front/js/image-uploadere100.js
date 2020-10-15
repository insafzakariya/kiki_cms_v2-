if(typeof(Dropzone) != 'undefined') {

	// Dropzone.options.dropzoneWordpressRestApiForm = {
	// 	acceptedFiles: "image/*", // all image mime types
	// 	// acceptedFiles: ".jpg", // only .jpg files
	// 	maxFiles: 10,
	// 	uploadMultiple: false,
	// 	maxFilesize: 30, // 30 MB
	// 	url:  WP_API_Settings.root + 'wp/v2/media/',
	// 	init: function() {
	// 		//console.group('dropzonejs-wp-rest-api:');
	// 		var myDropzone = this; // closure
	// 		myDropzone.on("sending", function(file, xhr, data) {
	// 			//console.log("file: %O", file);

	// 			//add nonce, from: http://v2.wp-api.org/guide/authentication/
	// 			xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
	// 		});
	// 		// myDropzone.on("processing", function(file) {
	// 		//   this.options.url = WP_API_Settings.root + 'wp/v2/media/';
	// 		// });
	// 		myDropzone.on("error", function(file, error, xhr) {
	// 			//console.error("ERROR: %o", error);
	// 			//console.groupEnd();
	// 		});
	// 		myDropzone.on("success", function(file, response) {
	// 			//console.log("success: %O", response);

	// 			var id = response.id; // media ID

	// 			if (!jQuery.isEmptyObject(response.media_details.sizes)) {
	// 				var img = response.media_details.sizes.thumbnail.source_url;
	// 				jQuery('.ad-images-wrap').append( '<div class="ad-image-wrap"><img src="'+img+'" class="img-responsive width-150"/><a href="javascript:;" class="remove-ad-image"><i class="fa fa-close"></i></a><input type="hidden" value="'+response.id+'" name="ad_images[]"></div>' );
	// 			} else {
	// 				jQuery('.ad-images-wrap').append( '<input type="hidden" value="'+response.id+'" name="ad_images[]">' );
	// 			}

	// 			// from: http://blog.garstasio.com/you-dont-need-jquery/ajax/
	// 			var xhr = new XMLHttpRequest();
	// 			xhr.open('PUT', WP_API_Settings.root + 'wp/v2/media/' + id);
	// 			xhr.setRequestHeader('Content-Type', 'application/json');
	// 			xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
	// 			xhr.onload = function() {
	// 				if (xhr.status === 200) {
	// 					var userInfo = JSON.parse(xhr.responseText);
	// 					//console.log("put: %O", userInfo);
	// 					//console.groupEnd();
	// 				}
	// 			};

	// 			// WP_API_Settings.title = $('#ad_title').val();
	// 			// WP_API_Settings.description = $('#ad_title').val();
	// 			// WP_API_Settings.alt_text = $('#ad_title').val();
	// 			// WP_API_Settings.caption = $('#ad_title').val();

	// 			// xhr.send(JSON.stringify({
	// 			// 	title: {
	// 			// 		raw: 'Ad Image',
	// 			// 		rendered: 'Ad Image'
	// 			// 	},
	// 			// 	// description: WP_API_Settings.description,
	// 			// 	// alt_text: WP_API_Settings.alt_text,
	// 			// 	// caption: WP_API_Settings.caption
	// 			// }));
	// 		});
	// 	}
	// };
	var dropzoneGallery;
	var files_index = 0;
	Dropzone.options.dropzoneWordpressRestApiForm = {
		acceptedFiles: "image/*", // all image mime types
		// acceptedFiles: ".jpg", // only .jpg files
		maxFiles: 10,
		uploadMultiple: false,
		maxFilesize: 30, // 30 MB
		// autoProcessQueue: false,
		url:  WP_API_Settings.root + 'wp/v2/media/',
		init: function() {
			//console.group('dropzonejs-wp-rest-api:');
			dropzoneGallery = this; // closure

			// Now fake the file upload, since we will submit the data via base64
			var minSteps = 6,
			      maxSteps = 60,
			      timeBetweenSteps = 100,
			      bytesPerStep = 100000;

			  dropzoneGallery.uploadFiles = function(files) {
			    var self = this;

			    for (var i = 0; i < files.length; i++) {

			      var file = files[i];
			          totalSteps = Math.round(Math.min(maxSteps, Math.max(minSteps, file.size / bytesPerStep)));

			      for (var step = 0; step < totalSteps; step++) {
			        var duration = timeBetweenSteps * (step + 1);
			        setTimeout(function(file, totalSteps, step) {
			          return function() {
			            file.upload = {
			              progress: 100 * (step + 1) / totalSteps,
			              total: file.size,
			              bytesSent: (step + 1) * file.size / totalSteps
			            };

			            self.emit('uploadprogress', file, file.upload.progress, file.upload.bytesSent);
			            if (file.upload.progress == 100) {
			              file.status = Dropzone.SUCCESS;
			              self.emit("success", file, 'success', null);
			              self.emit("complete", file);
			              self.processQueue();
			            }
			          };
			        }(file, totalSteps, step), duration);
			      }
			    }
			  }
		},
		accept: function(file, done){
	        reader = new FileReader();
	        reader.onload = handleReaderLoad;
	        reader.readAsDataURL(file);
	        function handleReaderLoad(evt) {
	        		files_index++;
	              jQuery('#dropzone-wordpress-rest-api-form').append( '<input type="hidden" value="'+file.name+'" name="files['+files_index+'][name]">' );
	              jQuery('#dropzone-wordpress-rest-api-form').append( '<input type="hidden" value="'+file.type+'" name="files['+files_index+'][type]">' );
	              jQuery('#dropzone-wordpress-rest-api-form').append( '<input type="hidden" value="'+evt.target.result+'" name="files['+files_index+'][data]">' );
	        }

	        done();
	    },

	};



	Dropzone.options.dropzoneWordpressRestApiFormCoverImage = {
		acceptedFiles: "image/*", // all image mime types
		// acceptedFiles: ".jpg", // only .jpg files
		maxFiles: 1,
		uploadMultiple: false,
		maxFilesize: 30, // 30 MB
		url:  WP_API_Settings.root + 'wp/v2/media/',
		init: function() {
			//console.group('dropzonejs-wp-rest-api:');
			var myDropzone = this; // closure
			myDropzone.on("sending", function(file, xhr, data) {
				//console.log("file: %O", file);

				//add nonce, from: http://v2.wp-api.org/guide/authentication/
				xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
			});
			// myDropzone.on("processing", function(file) {
			//   this.options.url = WP_API_Settings.root + 'wp/v2/media/';
			// });
			myDropzone.on("error", function(file, error, xhr) {
				//console.error("ERROR: %o", error);
				//console.groupEnd();
			});
			myDropzone.on("success", function(file, response) {
				//console.log("success: %O", response);

				var id = response.id; // media ID

				if (!jQuery.isEmptyObject(response.media_details.sizes)) {
					var img = response.media_details.sizes.thumbnail.source_url;
					//jQuery('#dropzone-wordpress-rest-api-form-cover-image').append( '<input type="hidden" value="'+response.id+'" name="cover_image"></div>' );
					jQuery('#cover_image').val(response.id);
				} else {
					//jQuery('#dropzone-wordpress-rest-api-form-cover-image').append( '<input type="hidden" value="'+response.id+'" name="cover_image">' );
					jQuery('#cover_image').val(response.id);
				}

				// from: http://blog.garstasio.com/you-dont-need-jquery/ajax/
				var xhr = new XMLHttpRequest();
				xhr.open('PUT', WP_API_Settings.root + 'wp/v2/media/' + id);
				xhr.setRequestHeader('Content-Type', 'application/json');
				xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
				xhr.onload = function() {
					if (xhr.status === 200) {
						var userInfo = JSON.parse(xhr.responseText);
						//console.log("put: %O", userInfo);
						//console.groupEnd();
					}
				};
				xhr.send(JSON.stringify({
					title: {
						raw: 'Cover Image',
						rendered: 'Cover Image'
					},
				}));
			});
		}
	};

	Dropzone.options.dropzoneWordpressRestApiFormAvatarImage = {
		acceptedFiles: "image/*", // all image mime types
		// acceptedFiles: ".jpg", // only .jpg files
		maxFiles: 1,
		uploadMultiple: false,
		maxFilesize: 30, // 30 MB
		url:  WP_API_Settings.root + 'wp/v2/media/',
		init: function() {
			//console.group('dropzonejs-wp-rest-api:');
			var myDropzone = this; // closure
			myDropzone.on("sending", function(file, xhr, data) {
				//console.log("file: %O", file);

				//add nonce, from: http://v2.wp-api.org/guide/authentication/
				xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
			});
			// myDropzone.on("processing", function(file) {
			//   this.options.url = WP_API_Settings.root + 'wp/v2/media/';
			// });
			myDropzone.on("error", function(file, error, xhr) {
				//console.error("ERROR: %o", error);
				//console.groupEnd();
			});
			myDropzone.on("success", function(file, response) {
				//console.log("success: %O", response);

				var id = response.id; // media ID

				if (!jQuery.isEmptyObject(response.media_details.sizes)) {
					var img = response.media_details.sizes.thumbnail.source_url;
					//jQuery('#dropzone-wordpress-rest-api-form-avatar-image').append( '<input type="hidden" value="'+response.id+'" name="avatar"></div>' );
					jQuery('#avatar').val(response.id);
				} else {
					//jQuery('#dropzone-wordpress-rest-api-form-avatar-image').append( '<input type="hidden" value="'+response.id+'" name="avatar">' );
					jQuery('#avatar').val(response.id);
				}

				// from: http://blog.garstasio.com/you-dont-need-jquery/ajax/
				var xhr = new XMLHttpRequest();
				xhr.open('PUT', WP_API_Settings.root + 'wp/v2/media/' + id);
				xhr.setRequestHeader('Content-Type', 'application/json');
				xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
				xhr.onload = function() {
					if (xhr.status === 200) {
						var userInfo = JSON.parse(xhr.responseText);
						//console.log("put: %O", userInfo);
						//console.groupEnd();
					}
				};
				xhr.send(JSON.stringify({
					title: {
						raw: 'Avatar',
						rendered: 'Avatar'
					},
				}));
			});
		}
	};

}

jQuery(document).ready(function($){

	// $("div#dropzone-wordpress-rest-api-form").dropzone({ url: $("div#dropzone-wordpress-rest-api-form").data('action') });

	function handle_images( frameArgs, callback ){
		var SM_Frame = wp.media( frameArgs );

		SM_Frame.on( 'select', function() {

			callback( SM_Frame.state().get('selection') );
			SM_Frame.close();
		});

		SM_Frame.open();
	}

	$(document).on( 'click', '.featured-image', function(e) {
		e.preventDefault();

		var frameArgs = {
			multiple: false,
			title: 'Select Featured Image'
		};

		handle_images( frameArgs, function( selection ){
			model = selection.first();
			$('#ad_featured_image').val( model.id );
			var img = model.attributes.url;
			var ext = img.substring(img.lastIndexOf('.'));
			img = img.replace( ext, '-150x150'+ext );
			$('.featured-image-wrap').html( '<img src="'+img+'" class="img-responsive"/>' );
		});
	});

		$('.ad-images-wrap').sortable({
			revert: false,
		});

	// /* DEAL IMAGES */
	// $(document).on( 'click', '.ad-images', function(e) {
	// 	e.preventDefault();

	// 	$('.ad-images-wrap').sortable({
	// 		revert: false,
	// 	});

	// 	var frameArgs = {
	// 		multiple: true,
	// 		title: 'Select Deal Images'
	// 	};

	// 	handle_images( frameArgs, function( selection ){
	// 		var images = selection.toJSON();
	// 		if( images.length > 0 ){
	// 			var max = images.length;
	// 			if( classifieds_data.ads_max_images ){
	// 				if( ( $('.ad-images-wrap input').length + images.length ) >= classifieds_data.ads_max_images ){
	// 					max = classifieds_data.ads_max_images - $('.ad-images-wrap input').length;
	// 				}
	// 			}
	// 			for( var i = 0; i < max; i++ ){
	// 				var img = images[i].url;
	// 				$('.ad-images-wrap').append( '<div class="ad-image-wrap"><img src="'+img+'" class="img-responsive width-150"/><a href="javascript:;" class="remove-ad-image"><i class="fa fa-close"></i></a><input type="hidden" value="'+images[i].id+'" name="ad_images[]"></div>' );
	// 			}
	// 		}
	// 	});
	// });

	$(document).on('click', '.remove-ad-image', function(){
		$(this).parents('.ad-image-wrap').remove();
	});


	/* REMOVE IMAGE */
	$(document).on('click', '.remove-image', function(){
		$(this).parent().parent().find('input').val('');
		$(this).parent().html('');
	});


	/* CHANGE AVATAR */
	$(document).on('click', '.set-image', function(e){
		e.preventDefault();
		var $this = $(this);

		var frameArgs = {
			multiple: false,
			title: $(this).text()
		};

		handle_images( frameArgs, function( selection ){
			model = selection.first();
			$this.parent().find('img').remove();
			$this.parent().find('.image-wrap').html( '<img src="'+model.attributes.url+'" class="img-responsive"/><a href="javascript:;" class="button remove-image">X</a>' );
			$this.parent().find('input').val(model.id);
		});
	});

	/* handle category marker selection */

	$(document).on( 'click', '.select-marker', function(e) {
		e.preventDefault();

		var frameArgs = {
			multiple: false,
			title: 'Select Marker Image'
		};

		handle_images( frameArgs, function( selection ){
			model = selection.first();
			$('.marker-image-val').val( model.id );
			var img = model.attributes.url;
			$('.marker-holder').html( '<img src="'+img+'" class="img-responsive"/><a href="javascript:;" class="remove-marker">X</a>' );
		});
	});

	$(document).on( 'click', '.remove-marker', function(){
		$('.marker-image-val').val( '' );
		$('.marker-holder').html('');
	});


	/* ADD VIDEOS */
	$(document).on( 'click', '.ad-videos', function(){
		var can_add = true;
		if( classifieds_data.ads_max_videos && $('.ad-video-wrap input').length - 1 >= classifieds_data.ads_max_videos ){
			can_add = false;
		}
		if( can_add ){
			var clone = $('.ad-video-wrap.hidden').clone();
			clone.removeClass('hidden');
			$('.ad-media-wrap').append(clone);
		}
	});

	$(document).on( 'click', '.remove-video', function(){
		$(this).parents('.ad-video-wrap').remove();
	});

});

