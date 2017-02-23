	jQuery(document).ready(function($) {

	    $('#ch_fade_slider_upload').click(function(e) {
	        var mediaUploader;
	        e.preventDefault();
	        // If the uploader object has already been created, reopen the dialog
	        if (mediaUploader) {
	            mediaUploader.open();
	            return;
	        }
	        // Extend the wp.media object
	        mediaUploader = wp.media.frames.file_frame = wp.media({
	            title: 'Choose Slider Image',
	            button: {
	                text: 'Choose Slider Image'
	            },
	            multiple: false
	        });

	        // When a file is selected, grab the URL and set it as the text field's value
	        mediaUploader.on('select', function() {
	            attachment = mediaUploader.state().get('selection').first().toJSON();
	            $('input#ch_slider_input_img').attr('value', attachment.url);
	            $('input#ch_slider_input_img_id').attr('value', attachment.id);
	            $('.previewch_slider img').attr('src', attachment.url);

	        });
	        // Open the uploader dialog
	        mediaUploader.open();
	    });

	    /* End Image Upload */




	    /* New Slider Submit */
	    $(document.body).on('click', 'input#ch_slider_submit', function() {
	        var imageUpload = $(this).closest('.submit_ch_sluder').prev('.uploader').find('input#ch_slider_input_img').val();

	        if (imageUpload == '' || UrlExists(imageUpload) == 404) {
	            $(this).closest('.submit_ch_sluder').prev('.uploader').find('input#ch_slider_input_img').addClass('error');
	            return false;
	        }
	        var formVar = $('form#ch_fade_slider').serialize();
	        var slider_title = $(this).parents('.newSliderWrap').find('#s_title').val();
	        var s_button_text = $(this).parents('.newSliderWrap').find('input#s_button_text').val();
	        var s_button_link = $(this).parents('.newSliderWrap').find('input#s_button_link').val();
	        var s_visiable = $(this).parents('.newSliderWrap').find('input:checked').val();
	        var input_img = $(this).parents('.newSliderWrap').find('input#ch_slider_input_img').val();
	        var img_id = $(this).parents('.newSliderWrap').find('input#ch_slider_input_img_id').val();
	        var endesable = $('table.widefat tbody tr').last().children('td').last().children('a').last().data('delete');
	        var encrise = 0;
	        if (typeof endesable === "undefined") {
	            encrise = 0;
	        } else {
	            encrise += parseInt(endesable + 1);
	        }

	        var newRaw = '<tr>';
	        newRaw += '<td>';
	        newRaw += '<span class="img_prev"><img class="sliderImg" src="' + input_img + '"/></span>';
	        newRaw += '<span class="hidden"><input required type="text" class="hidden" name="ch_slider_input_img[]" value="' + input_img + '">';
	        newRaw += '<input type="hidden" name="ch_slider_input_img_id[]" value="' + img_id + '"></span>';
	        newRaw += '</td>';
	        newRaw += '<td><span class="text" style="display: inline;">' + htmlEntities(slider_title) + '</span><span class="input hidden"><input type="text" class="" name="s_title[]" value="' + htmlEntities(slider_title) + '"></span></td>';
	        newRaw += '<td><span class="text" style="display: inline;">' + s_button_text + '</span><span class="input hidden"><input type="text" class="" name="s_button_text[]" value="' + s_button_text + '"></span></td>';
	        newRaw += '<td><span class="text" style="display: inline;">' + s_button_link + '</span><span class="input hidden"><input type="text" class="" name="s_button_link[]" value="' + s_button_link + '"></span></td>';
	        newRaw += '<td>';
	        newRaw += (s_visiable == 0) ? '<a href="javascript:void(0)" data-endesable="' + encrise + '" class="enableDesable readonly"><span class="desable">Desable</span></a>' : '<a href="javascript:void(0)" data-endesable="' + encrise + '" class="enableDesable readonly"><span class="enable">Enable</span></a>';
	        newRaw += '<span class="input hidden"><input type="hidden" name="s_visiable[]" value="' + s_visiable + '" ></span></td>';
	        newRaw += '<td><a class="edit_slide" data-edit="' + encrise + '" href="javascript:void(0)" ><div alt="f464" class="dashicons dashicons-edit"></div></a><a class="delete_slide" data-delete="' + encrise + '" href="javascript:void(0)"><div alt="f182" class="dashicons dashicons-trash"></div></a></td>';
	        newRaw += '</tr>';


	        jQuery.ajax({
	            type: "POST",
	            url: ajaxurl,
	            data: {
	                action: "ch_slider_item_insert",
	                formVar: formVar
	            },
	            success: function(data) {
	                // jQuery('.errorShow').text(data);
	                console.log(data);
	                if(data=='success'){
		                $('input#ch_slider_input_img').removeClass('error');
		                document.getElementById("ch_fade_slider").reset();
		                $('input#ch_slider_input_img, input#ch_slider_input_img_id').attr('value', '');
		                $('.previewch_slider img').attr('src', '');
		                $('table.widefat tbody').append(newRaw);
	            	}else{
	            		$('input#ch_slider_input_img').addClass('error');
	            		$('.message.ch_Message').html('<p>Duplicate Entry.</p>');
	            		$('.message.ch_Message').show();
	            	}
	            }
	        });
	    });



	    $(document.body).on('click', 'a.delete_slide', function() {
	        var dataDelete = $(this).data('delete');
	        var thistr = $(this).closest('td').closest('tr');

	        jQuery.ajax({
	            type: "POST",
	            url: ajaxurl,
	            data: {
	                action: "ch_slider_delete_item",
	                delete: dataDelete
	            },
	            success: function(data) {
	            	if(data=='success'){
	            		thistr.remove();	
	            	}else{
	            		alert('Slider Delete Failed');
	            	}
	                
	                //window.location.reload(true);
	            }
	        });
	        return false;
	    });


	    // Edit Main Slider Name
	    $(document.body).on('click', '.edit_slide_main', function(){
			/* Side Section */
	    	$sideHtml = '<form id="ch_settings">'
						+'<div class="inst header ch_main_side_header">'
						+'<h4>Slider Name</h4>'
						+'</div>'
						+'<div class="inst" style="margin-bottom:20px;">'
						+'<ul class="ch_slider_settings">'
						+'<li><a href="javascript:void(0)">Fonts</a>'
						+'<div class="stings_body">'
						+'<div class="form-group">'
						+'<label for="title_font_size">Title Font Size: </label>'	
						+'<input type="number" name="title_font_size" id="title_font_size">'
						+'</div>'
						+'<div class="form-group">'
						+'<label for="title_font_weight">Title Font Weight: </label>'
						+'<select name="title_font_weight" id="title_font_weight">'
						+'<option value="100">100</option>'
						+'<option value="200">200</option>'
						+'<option selected="selected" value="300">300</option>'
						+'<option value="400">400</option>'
						+'<option value="500">500</option>'
						+'<option value="600">600</option>'
						+'<option value="700">700</option>'
						+'</select>'
						+'</div>'
						+'<div class="form-group">'
						+'<label for="button_text_size">Button Font Size: </label>'
						+'<input type="number" name="button_font_size" id="button_text_size">'
						+'</div>'
						+'<div class="form-group">'
						+'<label for="button_font_weight">Button Font Weight: </label>'
						+'<select name="button_font_weight" id="button_font_weight">'
						+'<option value="100">100</option>'
						+'<option value="200">200</option>'
						+'<option selected="selected" value="300">300</option>'
						+'<option value="400">400</option>'
						+'<option value="500">500</option>'
						+'<option value="600">600</option>'
						+'<option value="700">700</option>'
						+'</select>'
						+'</div>'
						+'</div>'
						+'</li>'
						+'<li><a href="javascript:void(0)">Color&apos;s</a>'
						+'<div class="stings_body">'
						+'<div class="form-group">'
						+'<label for="title_text_color">Title Text Color: </label>'
						+'<input type="text" class="color-field" name="title_text_color" id="title_text_color">'
						+'</div>'
						+'<div class="form-group">'
						+'<label for="button_text_color">Button Text Color: </label>'
						+'<input type="text" class="color-field" name="button_text_color" id="button_text_color">'
						+'</div>'
						+'<div class="form-group">'
						+'<label for="button_buckground">Button Background: </label>'
						+'<input type="text" class="color-field" name="button_buckground" id="button_buckground">'
						+'</div>'
						+'</div>'
						+'</li>'
						+'</ul>'
						+'</div>'
						+'</form>';	    	

			$('div#ch_main_sidebar .editS').html($sideHtml);
			$('.color-field').wpColorPicker();
			$('div#ch_main_sidebar .editS').slideDown();				



	    	
			$('a.update_slide_main').hide();
	    	$('a.edit_slide_main').show();
	    	$('table.fadeSliderMain tr').find('span.input').show();
	    	$('table.fadeSliderMain tr').find('span.text').hide();
	    	$('div.two-one .header.ch_main_side_header h4').html($(this).closest('tr').find('input[name="slider_name"]').val());
	    	$('ul.ch_slider_settings input[name="title_font_size"]').val($(this).data('title_font_size'));
	    	$('ul.ch_slider_settings select[name="title_font_weight"]').val($(this).data('title_font_weight'));
	    	$('ul.ch_slider_settings input[name="button_font_size"]').val($(this).data('button_font_size'));
	    	$('ul.ch_slider_settings select[name="button_font_weight"]').val($(this).data('button_font_weight'));
	    	/*$('ul.ch_slider_settings input[name="title_text_color"]').val($(this).data('title_text_color'));
	    	$('ul.ch_slider_settings input[name="button_text_color"]').val($(this).data('button_text_color'));
	    	$('ul.ch_slider_settings input[name="button_buckground"]').val($(this).data('button_buckground'));*/

	    	$('ul.ch_slider_settings input[name="title_text_color"]').wpColorPicker('color', $(this).data('title_text_color'));
	    	$('ul.ch_slider_settings input[name="button_text_color"]').wpColorPicker('color', $(this).data('button_text_color'));
	    	$('ul.ch_slider_settings input[name="button_buckground"]').wpColorPicker('color', $(this).data('button_buckground'));


	    	$(this).hide();
	    	$(this).next('.update_slide_main').show();
	    	$(this).closest('tr').find('span.input').hide();
	    	$(this).closest('tr').find('span.text').show();

	    });

	    /*Update Slider Main Item */
	    $(document.body).on('click', 'a.update_slide_main', function(){
	    	var settings =  $('#ch_settings').serialize();	
	    	$thisBtn = $(this);
	    	$thistr = $(this).closest('tr');
	    	var slider_name 	= $(this).closest('tr').find('td.slider_name input').val();
	    	var slider_slug 	= $(this).closest('tr').find('td.slider_slug').text();
	    	var slider_active 	= $(this).closest('tr').find('td.slider_active input[type="radio"]:checked').val();
	    	var slider_id 		= $(this).closest('tr').data('id');

	    	jQuery.ajax({
	            type: "POST",
	            url: ajaxurl,
	            data: {
	                action: "ch_main_slider_update",
	                slider_name: slider_name,
	                slider_slug: slider_slug,
	                slider_active: slider_active,
	                settings: settings,
	                slider_id: slider_id 
	            },
	            success: function(data) {
	            	if(data=='success'){
	            	$thistr.find('td span.text').hide();
	            	$thistr.find('td span.input').show();
	                $thistr.find('td.slider_name span.input').text(slider_name);
	                $thistr.find('td.slider_active span.input').text( (slider_active==1)?'Yes':'No' );
	                $thisBtn.hide();
	                $thisBtn.prev('.edit_slide_main').show();
	                $('.thankYouMessage').html('<div alt="f147" class="dashicons dashicons-yes"></div> Success')
	                $('#loading').fadeIn(1500, function() { 
	                	$('#loading').fadeOut(100, function(){
	                		$('.thankYouMessage').html('');	
	                	});
	                	$('div#ch_main_sidebar .editS').slideUp(700, function(){
	                		$('div#ch_main_sidebar .editS').html('');
	                	});
	                });

	            	}
	            }
	        });

	    });

	    /* Main Slider Change text up */
	    $(document.body).on('keyup', 'table.fadeSliderMain tr td input[type="text"]', function(){
	    	$(this).closest('td').next('td').text($(this).val().split(' ').join('_').toLowerCase());
	    	$(this).closest('td').next('td').next('td').text('[chfs id="'+$(this).val().split(' ').join('_').toLowerCase()+'"]');
	    });

	    // Edit Function 
	    $(document.body).on('click', '.edit_slide', function() {
	        $('span.input').addClass('hidden');
	        $('span.text').show();
	        $('a.edit_slide').removeClass('active');
	        $('a.enableDesable').addClass('readonly');
	        $('a.edit_slide').html('<div alt="f464" class="dashicons dashicons-edit"></div>');
	        $('span.img_prev').removeClass('clicable');
	        $('tr').removeClass('editActive');
	        $(this).parents('tr').addClass('editActive');
	        $(this).addClass('active');
	        $(this).html('<div alt="f147" class="dashicons dashicons-yes"></div>');
	        $(this).parents('tr').find('span.text').hide();
	        $(this).parents('tr').find('span.input').removeClass('hidden');
	        $(this).parents('tr').find('span.img_prev').addClass('clicable');
	        $(this).parents('tr').find('a.enableDesable').removeClass('readonly').addClass('active');
	        $(this).closest('td').append('<span></span>');
	    });


	    /* Submit Edited Data */
	    $(document.body).on('click', 'a.edit_slide.active', function() {
	        var thisTr = $(this).parents('tr.editActive');
	        var slider_title = $(this).parents('tr.editActive').find('input[name="s_title"]').val();
	        var s_button_text = $(this).parents('tr.editActive').find('input[name="s_button_text"]').val();
	        var s_button_link = $(this).parents('tr.editActive').find('input[name="s_button_link"]').val();
	        var s_visiable = $(this).parents('tr.editActive').find('input[name="s_visiable"]').val();
	        //var input_img = $(this).parents('tr.editActive').find('input[name="ch_slider_input_img[]"]').val();
	        var img_id = $(this).parents('tr.editActive').find('input[name="ch_slider_input_img_id"]').val();
	        var edit_id = $(this).data('edit');
	        var slider_id = $(this).data('slider_id');

	        // Ajax Action 
	        jQuery.ajax({
	            type: "POST",
	            url: ajaxurl,
	            data: {
	                action: "ch_slider_edit",
	                title: slider_title,
	                button_text: s_button_text,
	                button_link: s_button_link,
	                visiable: s_visiable,
	                img_id: img_id,
	                id: edit_id,
	                slider_id: slider_id
	            },
	            success: function(data) {
	                //window.location.reload(true);
	                console.log(data);
	                if(data=='success'){
		                thisTr.find('input[name="s_title"]').closest('span.input').prev('span.text').text(slider_title);
		                thisTr.find('input[name="s_button_text"]').closest('span.input').prev('span.text').text(s_button_text);
		                thisTr.find('input[name="s_button_link"]').closest('span.input').prev('span.text').text(s_button_link);

		                thisTr.find('span.text').show();
		                thisTr.find('span.input').addClass('hidden');

		                thisTr.find('a.edit_slide').removeClass('active').html('<div alt="f464" class="dashicons dashicons-edit"></div>');
		                thisTr.find('a.enableDesable').addClass('readonly').removeClass('active');
		                thisTr.removeClass('editActive');
	            	}
	            }
	        });
	    });


	    // Active & Deactive click  When Edit Slider  
	    $(document.body).on('click', 'a.enableDesable.active', function() {
	        var currentVal = $(this).next('span.input').children('input').val();
	        var changeVal = (currentVal == 1) ? 0 : 1;
	        var thisText = ($(this).text() == 'Enable') ? 'Desable' : 'Enable';
	        $(this).next('span.input').children('input').val(changeVal);
	        $(this).text(thisText);

	    });

	    // Edit Image 
	    $(document.body).on('click', 'tr.editActive img.sliderImg', function(e) {
	        var mediaUploader;
	        var thisfield = $(this);
	        e.preventDefault();
	        // If the uploader object has already been created, reopen the dialog
	        if (mediaUploader) {
	            mediaUploader.open();
	            return;
	        }
	        // Extend the wp.media object
	        mediaUploader = wp.media.frames.file_frame = wp.media({
	            title: 'Choose Slider Image',
	            button: {
	                text: 'Choose Slider Image'
	            },
	            multiple: false
	        });

	        // When a file is selected, grab the URL and set it as the text field's value
	        mediaUploader.on('select', function() {
	            attachment = mediaUploader.state().get('selection').first().toJSON();
	            thisfield.attr('src', attachment.url);
	            thisfield.closest('span.img_prev.clicable').next('span.hidden').children('input[name="ch_slider_input_img"]').attr('value', attachment.url);
	            thisfield.closest('span.img_prev.clicable').next('span.hidden').children('input[name="ch_slider_input_img_id"]').val(attachment.id);
	        });
	        // Open the uploader dialog
	        mediaUploader.open();
	    });


	    // Shortable  
	    $(function() {
	        $("table.widefat.items tbody").sortable({
	            update: function(event, ui) {
	                var formVar = $('form#ch_fade_slider').serialize();

	                // Action Shortable Ajax 
	                jQuery.ajax({
	                    type: "POST",
	                    url: ajaxurl,
	                    data: {
	                        action: "ch_update_shortable",
	                        formVar: formVar
	                    },
	                    success: function(data) {
	                        console.log(data);
	                        jQuery('.errorShow').text(data);
	                        $('input#ch_slider_input_img').removeClass('error');
	                        //document.getElementById("of_slider").reset();
	                        //$('table.widefat tbody').append(newRaw);
	                    }
	                });

	            }
	        });
	        $("table.widefat tbody").disableSelection();
	        // End Shortable
	    });


	    /*
	     * Show Hide New Slider
	     */

	    $(".addSlider a").toggle(function(e) {
	    	  
	    	/* Side Section */
	    	$sideHtml = '<form id="ch_settings">'
						+'<div class="inst header ch_main_side_header">'
						+'<h4>Slider Name</h4>'
						+'</div>'
						+'<div class="inst" style="margin-bottom:20px;">'
						+'<ul class="ch_slider_settings">'
						+'<li><a href="javascript:void(0)">Fonts</a>'
						+'<div class="stings_body">'
						+'<div class="form-group">'
						+'<label for="title_font_size">Title Font Size: </label>'	
						+'<input type="number" name="title_font_size" id="title_font_size">'
						+'</div>'
						+'<div class="form-group">'
						+'<label for="title_font_weight">Title Font Weight: </label>'
						+'<select name="title_font_weight" id="title_font_weight">'
						+'<option value="100">100</option>'
						+'<option value="200">200</option>'
						+'<option selected="selected" value="300">300</option>'
						+'<option value="400">400</option>'
						+'<option value="500">500</option>'
						+'<option value="600">600</option>'
						+'<option value="700">700</option>'
						+'</select>'
						+'</div>'
						+'<div class="form-group">'
						+'<label for="button_text_size">Button Font Size: </label>'
						+'<input type="number" name="button_font_size" id="button_text_size">'
						+'</div>'
						+'<div class="form-group">'
						+'<label for="button_font_weight">Button Font Weight: </label>'
						+'<select name="button_font_weight" id="button_font_weight">'
						+'<option value="100">100</option>'
						+'<option value="200">200</option>'
						+'<option selected="selected" value="300">300</option>'
						+'<option value="400">400</option>'
						+'<option value="500">500</option>'
						+'<option value="600">600</option>'
						+'<option value="700">700</option>'
						+'</select>'
						+'</div>'
						+'</div>'
						+'</li>'
						+'<li><a href="javascript:void(0)">Color&apos;s</a>'
						+'<div class="stings_body">'
						+'<div class="form-group">'
						+'<label for="title_text_color">Title Text Color: </label>'
						+'<input type="text" class="color-field" name="title_text_color" id="title_text_color">'
						+'</div>'
						+'<div class="form-group">'
						+'<label for="button_text_color">Button Text Color: </label>'
						+'<input type="text" class="color-field" name="button_text_color" id="button_text_color">'
						+'</div>'
						+'<div class="form-group">'
						+'<label for="button_buckground">Button Background: </label>'
						+'<input type="text" class="color-field" name="button_buckground" id="button_buckground">'
						+'</div>'
						+'</div>'
						+'</li>'
						+'</ul>'
						+'</div>'
						+'</form>';

			$('div#ch_main_sidebar .editS').html($sideHtml);
			$('.color-field').wpColorPicker();
			$('div#ch_main_sidebar .editS').slideDown();
	    	/*Bottom Secton */


	        $('div#newSlider').animate({
	            opacity: 1,
	            height: '335px'
	        });
	        $("html, body").animate({
	            scrollTop: $(document).height()
	        }, 1000);
	        $(this).removeClass('dashicons-plus-alt').addClass('dashicons-dismiss');
	    }, function() {
	        $('div#newSlider').animate({
	            opacity: 0,
	            height: '0px'
	        });
	        $(this).removeClass('dashicons-dismiss').addClass('dashicons-plus-alt');
	        $('div#ch_main_sidebar .editS').slideUp('slow', function(){
	        	$('div#ch_main_sidebar .editS form').remove();
	        });

	    });


	    // Change New Slider Slug Name  
	    $(document.body).on('keyup', 'input#s_name', function(){
	    	var namVal = $(this).val();
	    	$('input#s_slug').val(namVal.toLowerCase().split(' ').join('_'));
	    });
	 


	 	/*
	 	* New Blank Slider Submit
	 	*/

	    $(document.body).on('click', '.submit_new_slider input', function() {
	    	var settings =  $('#ch_settings').serialize();	
	        var name = 		$('input#s_name').val();
	        var baseUrl = 	$('#base_url').data('url');
	        var slug = 		$('input#s_slug').val();
	        var active =	$('input[name="active"]:checked').val();
	        var activeTxt =	(active==1)?'Yes':'No';
	        var pluginUrl =	$('#plugin_url').data('url');
	        var lastD = 	$('table.widefat.fadeSliderMain tbody tr').last().data('id');
	        var lastID =	(lastD)?lastD:0;
	        var html = 		'<tr data-id="'+parseInt(lastID + 1)+'" class="ui-sortable-handle">'
							+'<td><a style="background:none; padding:0px;" href="'+baseUrl+'admin.php?page=ch_fade_slider&amp;slider='+parseInt(lastID + 1)+'">'
							+'<img style="max-width: 100%;" src="'+pluginUrl+'ch_files/img/dummy.jpg">'
							+'</a></td>'
							+'<td class="slider_name">'
							+'<span class="input">'+name+'</span>'
							+'<span style="display: none;" class="text"><input type="text" name="slider_name" value="'+name+'"></span>'
							+'</td>'
							+'<td class="slider_slug">'+slug+'</td>'
							+'<td>[chfs id="'+slug+'"]</td>'
							+'<td class="slider_active">'
							+'<span class="input">'+activeTxt+'</span>'
							+'<span class="text" style="display: none;">'
							+'<div class="activeInputSection">'
							+'<label><input type="radio" value="1" checked="checked" name="active_'+parseInt(lastID + 1)+'"> Yes</label>&nbsp;&nbsp;'
							+'<label><input type="radio" value="0" name="active_'+parseInt(lastID + 1)+'"> No</label>'
							+'</div>'
							+'</span>'
							+'</td>'
							+'<td>'
							+'<a class="delete_slide_main" href="javascript:void(0)" data-delete="'+parseInt(lastID + 1)+'">Delete</a>'
							+'<a class="edit_slide_main" href="javascript:void(0)" data-edit="'+parseInt(lastID + 1)+'">Edit</a>'
							+'<a style="display: none;" class="update_slide_main" href="javascript:void(0)" data-edit="'+parseInt(lastID + 1)+'">Update</a>'
							+'</td>'
							+'</tr>';

	        jQuery.ajax({
	            type: "POST",
	            url: ajaxurl,
	            data: {
	                action: "ch_new_slider",
	                slider_name: name,
	                slider_slug: slug,
	                settings: settings,
	                active: active
	            },
	            success: function(data) {
	            	console.log(data);
	                if(data=='success'){
	                	$('table.widefat.fadeSliderMain tbody').append(html);
	                }
	            }
	        });
	        return false;
	    }); // End submit new slider



	    $(document.body).on('click', 'a.delete_slide_main', function(){
	    	$thisTr = $(this).closest('tr');
	    	$id = $(this).data('delete');
	    	jQuery.ajax({
	    		type: "POST",
	    		url: ajaxurl,
	    		data: {
	    			action: "delete_ch_slider",
	    			id: $id
	    		},
	    		success:function(data){
	    			$thisTr.remove();
	    		}
	    	});
	    });

	    // Color Picker 
	    $('.color-field').wpColorPicker();
	}); // End Document Ready 


	 // Check Url 404
	function UrlExists(url) {
	    var http = new XMLHttpRequest();
	    http.open('HEAD', url, false);
	    http.send();
	    return http.status;
	}
	function htmlEntities(str) {
    	return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	}