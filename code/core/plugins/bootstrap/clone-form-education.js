/*
Author: Tristan Denyer (based on Charlie Griefer's original clone code, and some great help from Dan - see his comments in blog post)
Plugin repo: https://github.com/tristandenyer/Clone-section-of-form-using-jQuery
Demo at http://tristandenyer.com/using-jquery-to-duplicate-a-section-of-a-form-maintaining-accessibility/
Ver: 0.9.4.1
Last updated: Sep 24, 2014

The MIT License (MIT)

Copyright (c) 2011 Tristan Denyer

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
$(function () {
    $('#btnAdd').click(function () {
		//alert($('.clonedInput').length);
        
		var num = 0;
		last_id = $(".clonedInput:last").attr('id');
		//alert(last_id);//return;
		if(last_id) {
			num = last_id.replace('entry','');
			num = parseInt(num);
		}
		
		//num = $('.clonedInput').length-1; // Checks to see how many "duplicatable" input fields we currently have
		var newNum  = new Number(num + 1);      // The numeric ID of the new input field being added, increasing by 1 each time
                
		//var newElem = $('#entry' + num).clone().attr('id', 'entry' + newNum).fadeIn('slow'); // create the new element via clone(), and manipulate it's ID using newNum value
		var newElem = $('#entry0').clone().attr('id', 'entry' + newNum).fadeIn('slow'); // create the new element via clone(), and manipulate it's ID using newNum value
                
		/*  This is where we manipulate the name/id values of the input inside the new, cloned element
			Below are examples of what forms elements you can clone, but not the only ones.
			There are 2 basic structures below: one for an H2, and one for form elements.
			To make more, you can copy the one for form elements and simply update the classes for its label and input.
			Keep in mind that the .val() method is what clears the element when it gets cloned. Radio and checkboxes need .val([]) instead of .val('').
		*/
        // H2 - section
		newElem.find('.clone_heading').html('Institution #' + newNum);
                if(newNum == 1) {
                    newNum = "";
                }

        // Title - select
        //newElem.find('.label_qualification').attr('for', 'ID' + newNum + '_qualification');
        
        newElem.find('.clone_institution').attr('id', 'institution'+ newNum).attr('name', 'institution' + newNum).val('');
        newElem.find('.clone_institution_location').attr('id', 'institution_location'+ newNum).attr('name', 'institution_location' + newNum).val('');
        newElem.find('.clone_grade').attr('id', 'grade'+ newNum).attr('name', 'grade' + newNum).val('');
        newElem.find('.clone_yearofgraduation').attr('id', 'yearofgraduation'+ newNum).attr('name', 'yearofgraduation' + newNum).val('');
        
        newElem.find('.clone_qualification').attr('id', 'qualification'+ newNum).attr('name', 'qualification' + newNum).attr('class', 'clone_qualification' + newNum).val('');
        newElem.find('.clone_field_of_study').attr('id', 'field_of_study'+ newNum).attr('name', 'field_of_study' + newNum + '[]').attr('class', 'clone_field_of_study' + newNum).val('');
        
        newElem.find('.clone_achievement').attr('id', 'achievement'+ newNum).attr('name', 'achievement' + newNum).val('');
        
		newElem.find('#btnDel0').attr('id', 'btnDel'+ newNum).attr('name', 'btnDel' + newNum).val('');
//alert(newNum);
        
        // First name - text
        //newElem.find('.label_fn').attr('for', 'ID' + newNum + '_first_name');
        //newElem.find('.input_fn').attr('id', 'ID' + newNum + '_first_name').attr('name', 'ID' + newNum + '_first_name').val('');

        // Last name - text
        //newElem.find('.label_ln').attr('for', 'ID' + newNum + '_last_name');
        //newElem.find('.input_ln').attr('id', 'ID' + newNum + '_last_name').attr('name', 'ID' + newNum + '_last_name').val('');

        // Color - checkbox
        //newElem.find('.label_checkboxitem').attr('for', 'ID' + newNum + '_checkboxitem');
        //newElem.find('.input_checkboxitem').attr('id', 'ID' + newNum + '_checkboxitem').attr('name', 'ID' + newNum + '_checkboxitem').val([]);
        
        //newElem.find('.label_select').attr('for', 'ID' + newNum + 'qualification');
        //newElem.find('.input_select').attr('id', 'ID' + newNum + '_qualification').attr('name', 'ID' + newNum + '_qualification').val([]);

        // Skate - radio
        //newElem.find('.label_radio').attr('for', 'ID' + newNum + '_radioitem');
        //newElem.find('.input_radio').attr('id', 'ID' + newNum + '_radioitem').attr('name', 'ID' + newNum + '_radioitem').val([]);

        // Email - text
        //newElem.find('.label_email').attr('for', 'ID' + newNum + '_email_address');
        //newElem.find('.input_email').attr('id', 'ID' + newNum + '_email_address').attr('name', 'ID' + newNum + '_email_address').val('');

        // Twitter handle (for Bootstrap demo) - append and text
        //newElem.find('.label_twt').attr('for', 'ID' + newNum + '_twitter_handle');
        //newElem.find('.input_twt').attr('id', 'ID' + newNum + '_twitter_handle').attr('name', 'ID' + newNum + '_twitter_handle').val('');
          
		// Insert the new element after the last "duplicatable" input field
		$('#entry' + num).after(newElem);
		$('#ID' + newNum + '_title').focus();

		// Enable the "remove" button. This only shows once you have a duplicated section.
		$('#btnDel').show();
	
		// Right now you can only add 4 sections, for a total of 5. Change '5' below to the max number of sections you want to allow.
		if (newNum == 20)
			$('#btnAdd').hide().prop('value', "You've reached the limit"); // value here updates the text in the 'add' button when the limit is reached 
		
		newElem.find('.dont-clone').remove();
            $('#qualification'+ newNum).multiselect({
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:false,                   
                enableClickableOptGroups: false,
                inheritClass: true,
                buttonClass: 'form-control inputF clone_qualification'+newNum+'_button',
                buttonWidth: '100%',
                onChange: function(option, checked, select) {
                    //custom onchange for specific combobox
                    //                                 $('#state').multiselect('deselectAll',false);
    //                                 $('#state').multiselect('deselectRadio',false);
    //                                 $('#state').multiselect('showOnlyGroup',option.val());
    //                                 $('#state').multiselect('updateButtonText');


                }
            });

            $('#field_of_study'+ newNum).multiselect({
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:false,
                enableClickableOptGroups: true,
                inheritClass: true,
                buttonClass: 'form-control inputF clone_field_of_study'+newNum+'_button',
                buttonWidth: '100%',
                onChange: function(option, checked) {

                    // Get selected options.
                        var selectedOptions = $('#field_of_study'+ newNum+' option:selected');
                    if (selectedOptions.length >= 5) {
                        // Disable all other checkboxes.
                        var nonSelectedOptions = $('#field_of_study'+ newNum+' option').filter(function() {
                            return !$(this).is(':selected');
                        });
                        var dropdown = $('#field_of_study'+ newNum).siblings('.multiselect-container');
                        nonSelectedOptions.each(function() {
                            var input = $('input[value="' + $(this).val() + '"]');
                            input.prop('disabled', true);
                            input.prop('checked', false);
                            input.parent('li').addClass('disabled');
                        });
                    }
                    else {
                        // Enable all checkboxes.
                        var dropdown = $('#field_of_study'+ newNum).siblings('.multiselect-container');
                        $('#field_of_study'+ newNum+' option').each(function() {
                            var input = $('input[value="' + $(this).val() + '"]');
                            input.prop('disabled', false);
                            input.parent('li').addClass('disabled');
                        });
                    }
                                        }
            });
			
		newElem.find('.clone_qualification'+newNum+'_button').attr('id', 'qualification'+ newNum+'_button');
        newElem.find('.clone_field_of_study'+newNum+'_button').attr('id', 'field_of_study'+ newNum+'_button');

		//alert('#btnDel' + newNum + '');
		$('#btnDel'+newNum).click(function () {
			// Confirmation dialog box. Works on all desktop browsers and iPhone.
			if (confirm("Are you sure you wish to remove this institution? This action cannot be undone.")) {
				//var num = $('.clonedInput').length-1;
				// how many "duplicatable" input fields we currently have
				$('#entry' + newNum).slideUp('slow', function () {$(this).remove();
					// if only one element remains, disable the "remove" button
					////if (num - 1 === 1)
					////	$('#btnDel').hide();
					// enable the "add" button
					//$('#btnAdd').show().prop('value', "add section");
				});
			}
			return false; // Removes the last section you added
		});

	});

	/**$('#btnDel').click(function () {
		// Confirmation dialog box. Works on all desktop browsers and iPhone.
		if (confirm("Are you sure you wish to remove this section? This cannot be undone.")) {
			var num = $('.clonedInput').length-1;
			// how many "duplicatable" input fields we currently have
			$('#entry' + num).slideUp('slow', function () {$(this).remove();
				// if only one element remains, disable the "remove" button
				////if (num - 1 === 1)
				////	$('#btnDel').hide();
				// enable the "add" button
				$('#btnAdd').show().prop('value', "add section");
			});
		}
		return false; // Removes the last section you added
	});*/
	// Enable the "add" button
	$('#btnAdd').show();
	// Disable the "remove" button
	////$('#btnDel').show();
});