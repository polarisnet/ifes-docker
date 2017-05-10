	/***************************************************************************************************
	 * get data from server using XMLHttpRequest
	 * call_back_func 	: function that will be call after request to the server is completed
	 * method 		  	: GET or HEAD or POST
	 * url 			  	: Request url
	 * field_id 	  	: id of the <tag> to display the content
	 * form_id  	  	: is need if the method use is POST , to get all the value of form elements
	 * field_element_id	: the element id of a field, used to dynamic assign value to this field
	*****************************************************************************************************/
	JS_HTTP_CURRENT_TEMPLATE = JS_SERVER + JS_ROOT;
	function callAjax(call_back_func, method, url, field_id, form_id, loading_func, field_element_id) {
				
		var requester 	= createXmlObject();
			method 		= method.toUpperCase();

		// Event handler for an event that fires at every state change,
		// for every state , it will run callback function.
		// Set the event listener
		requester.onreadystatechange = 	function() { stateHandler(requester, url, call_back_func, field_id, loading_func, field_element_id)}

		switch (method) {
			case 'GET':
			case 'HEAD':
				requester.open(method, url);
				//requester.overrideMimeType("text/html; charset=ISO-8859-1");
				requester.setRequestHeader("Content-Type", "text/html; charset=ISO-8859-1");
				requester.send(null);
				break;
			case 'POST':
				query = generate_query(form_id);
				requester.open(method, url);
				// In order to get the request body values to show up in $_POST 
				requester.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
				requester.send(query);
				break;
			default:
				alert('Error: Unknown method or method not supported');
				break;
		}
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////
	// JSON
	///////////////////////////////////////////////////////////////////////////////////////////////////
	/***************************************************************************************************
	 * get data from server using XMLHttpRequest
	 * call_back_func 	: function that will be call after request to the server is completed
	 * method 		  	: GET or HEAD or POST
	 * url 			  	: Request url
	 * field_id 	  	: id of the <tag> to display the content
	 * form_id  	  	: is need if the method use is POST , to get all the value of form elements
	 * field_element_id	: the element id of a field, used to dynamic assign value to this field
	*****************************************************************************************************/
	function callAjaxJson(call_back_func, method, url, field_id, form_id, loading_func, field_element_id) {
	
		var requester 	= createXmlObject();
			method 		= method.toUpperCase();

		// Event handler for an event that fires at every state change,
		// for every state , it will run callback function.
		// Set the event listener
		requester.onreadystatechange = 	function() { stateHandlerJson(requester, url, call_back_func, field_id, loading_func, field_element_id)}

		switch (method) {
			case 'GET':
			case 'HEAD':
				requester.open(method, url);
				requester.send(null);
				break;
			case 'POST':
				query = generate_query(form_id);
				requester.open(method, url);
				// In order to get the request body values to show up in $_POST 
				requester.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
				requester.send(query);
				break;
			default:
				alert('Error: Unknown method or method not supported');
				break;
		}
	}
	
	/***************************************************************************************************
	 * instantiate an XMLHttpRequest object 
	*****************************************************************************************************/
	function createXmlObject() {
		var xmlhttp = false;

		// create object in mozilla, safari, Internet Explorer version >= 7
		try{xmlhttp = new XMLHttpRequest(); xmlhttp.timeout = 36000000;}  // time in milliseconds
		catch (e) {
			// create object in Internet Explorer version >= 5
			try {xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");}
			catch (e) {
				// create object in Internet Explorer	
				try {xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}
				catch (e) {
					// create object in Ice Browser
					try {xmlhttp = window.createRequest();}
					catch(e){}
				}
			}
		}
		return xmlhttp;
	}
	
	/***************************************************************************************************
	 * httpReqest status
	*****************************************************************************************************/
	function stateHandler(requester, url, call_back_func, field_id, loading_func, field_element_id) {
		/*  status of the object's connection, (requester.readyState)
		0 - not initialized.
		1 - connection etablished.
		2 - request received.
		3 - answer in process.
		4 - Completed 
		*/ 
		
		if(field_element_id != '') { // Custom
			
			if (requester.readyState == 4) {
				 if (requester.status == 200) { 
					http_header = requester.getAllResponseHeaders();
					http_data   = requester.responseText;
					
					//alert(http_header+http_data);
					//alert(field_element_id);
					document.getElementById(field_element_id).innerHTML = http_data;
					
				 } else if (requester.status == 404){
					alert("Request URL does not exist : " + url);
				 } else {
					//alert("Error: Status code is " + request.status);
				 }
			}
			
		} else {

				if (requester.readyState == 4) {
					 if (requester.status == 200) {
						http_header = requester.getAllResponseHeaders();
						http_data   = requester.responseText;
						//alert(http_header+http_data);
						//alert(call_back_func);
						//eval("change_text(http_data,field_id)");
						eval(call_back_func + "(http_data, field_id)");
						
					 } else if (requester.status == 404){
						alert("Request URL does not exist : " + url);
					 } else {
						//alert("Error: Status code is " + requester.status);
					 }
				} else {
						
					if(loading_func != ''){
						eval(loading_func + "(field_id)");
					} else {
						http_data = show_loading_image();
						change_Loading_part(http_data, field_id);
					}
				}
		}
	}
	
	/***************************************************************************************************
	 * httpReqest status Json
	*****************************************************************************************************/
	function stateHandlerJson(requester, url, call_back_func, field_id, loading_func, field_element_id) {
		/*  status of the object's connection, (requester.readyState)
		0 - not initialized.
		1 - connection etablished.
		2 - request received.
		3 - answer in process.
		4 - Completed 
		*/ 
		
		if (requester.readyState == 4) { 
			 if (requester.status == 200) {
				http_header = requester.getAllResponseHeaders();
				//The eval function is very fast. However, it can compile and execute any JavaScript program, so there can be security issues.
				//data_object = eval('(' + requester.responseText + ')');
				//ref to http://www.json.org/js.html
				// include json.js (JSON parse)
				//When security is a concern it is better to use a JSON parser. A JSON parser will only recognize JSON text and so is much safer:
				text 		= requester.responseText;
				data_object = text.parseJSON();
				eval(call_back_func + "(data_object, field_id)");
			 } else if (requester.status == 404){
         		alert("Request URL does not exist : " + url);
		 	 } else {
         		//alert("Error: Status code is " + requester.status);
			 }
		} else {
			if(loading_func != ''){
				eval(loading_func + "(field_id)");
			} else {
				http_data = show_loading_image();
				change_Loading_part(http_data, field_id);
			}
		}
	}
	
	
	/***************************************************************************************************
	 * get all the form elements and make it as query string
	 * this function will be use if the ajax httpRequest method = post 
	*****************************************************************************************************/
	function generate_query(form_id){

		var objForm = document.getElementById(form_id);
		var query 	= '';

		for (i=0; i<objForm.elements.length; i++){
			if (document.getElementById(objForm.elements[i].name)) { /* check if the id is exist - for mozilla*/
				if (document.getElementById(objForm.elements[i].name).getAttribute('id') != '') { /* check if the id is exist - for IE */
					query += (i > 0 ? "&" : "");
            		query += escape(objForm.elements[i].name) + "=" + escape(objForm.elements[i].value);
				}
			}
		}
		
		return query;
	}
	
	
	/* in the product page , show the loading icon */
	function show_loading_image () {
		strLoading = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size:10px;"><tr><td align="center"><b>Loading..</b></td></tr><tr><td align="center" style="padding-top:10px;"><img src="'+JS_HTTP_CURRENT_TEMPLATE+'/media/site-image/loading1.gif" alt="Loading.." /></td></tr></table>';
		return strLoading;
	}
	function simple_checking_image (field_id) {
		if(field_id && document.getElementById(field_id)) {
			document.getElementById(field_id).innerHTML = '<center><img src="'+JS_HTTP_CURRENT_TEMPLATE+'/media/site-image/loading1.gif" alt="Checking.." /></center>';
		}
	}
	function simple_loading_image (field_id) {
		if(field_id && document.getElementById(field_id)) {
			document.getElementById(field_id).innerHTML = '<center><img src="'+JS_HTTP_CURRENT_TEMPLATE+'/media/site-image/loading1.gif" alt="Loading.." /></center>';
		}
	}
	function simple_sendingemail_image (field_id) {
		if(field_id && document.getElementById(field_id)) {
			document.getElementById(field_id).innerHTML = '<center><img src="'+JS_HTTP_CURRENT_TEMPLATE+'/media/site-image/loading1.gif" alt="sending email.." /></center>';
		}
	}
	function show_no_loading (field_id) {
		if(field_id && document.getElementById(field_id)) {
			document.getElementById(field_id).innerHTML = '';
		}
	}
	function show_test_loading (field_id) {
		if(field_id && document.getElementById(field_id)) {
			document.getElementById(field_id).innerHTML = '';
		}
	}
	
	/***************************************************************************************************
	 *change the div content to loading content*
	 * http_data is the loading image 
	*****************************************************************************************************/
	function change_Loading_part(http_data, field_id) {
 		if(field_id && document.getElementById(field_id)){
			document.getElementById(field_id).innerHTML = http_data;
		}
 	}
	
	/***************************************************************************************************
	 *change the div content *
	 * http_data is in string format 
	*****************************************************************************************************/
	function change_text(http_data, field_id) {
		//alert(document.getElementById(field_id).innerHTML);
		//alert(field_id);
		if(field_id && document.getElementById(field_id)) {
 			document.getElementById(field_id).innerHTML = http_data;
		}
 	}
	
	/***************************************************************************************************
	 *change the div content * for scheduled calls
	 * http_data is in string format 
	*****************************************************************************************************/
	function scheduledcalls_change_text(http_data, field_id) {
		if(field_id && document.getElementById(field_id)) {
			document.getElementById(field_id).innerHTML = http_data;
		}
		jsCheckScheduledCalls(1000, '');
	}

	/***************************************************************************************************
	 *change the div content *
	 * http_data is in string format 
	*****************************************************************************************************/
	function change_src(http_data, field_id) {
		//alert(document.getElementById(field_id).innerHTML);
		//alert(field_id);
 		if(field_id && document.getElementById(field_id)) {
 			document.getElementById(field_id).src = http_data;
		}
 	}
	
	/***************************************************************************************************
	 *change the div content *
	 * http_data is in string format 
	*****************************************************************************************************/
	function change_continuous_text(http_data, field_id) {
		//alert(document.getElementById(field_id).innerHTML);
 		if(field_id && document.getElementById(field_id)) {
 			document.getElementById(field_id).innerHTML += http_data;
		}
 	}

	/********************************************************************************************************************************
	// CUSTOMIZE FUNCTION
	//
	//*********************************************************************************************************************************


	/***************************************************************************************************
	 *change the selection box option *
	 *
	 * http_data must in this format : 
	 *  	KEY=>VALUE,KEY=>VALUE,KEY=>VALUE
	 *		OR
	 *		VALUE,VALUE,VALUE  
	 *		(if the key do not pass in , the key will be same as the value)
	*****************************************************************************************************/
	
	function change_selection(http_data, field_id){

		if (http_data != '' && field_id) {
			document.getElementById(field_id).options.length = 0;

			var key;
			var value;
			var word  = http_data.split(',');
			
			for(i=0; i< word.length; i++){
				value = word[i]. split('=>');
				key   = value[0];
				if (value.length > 1) { /* got pass in the key */
					value = value[1];
				} else {
					value = value[0]; /* do not pass in the key */
				}
				document.getElementById(field_id).options[i] = new Option(value,key);
				//alert(key + '=' + value);
			}
		}
	}

	function change_selection_json(data_object, field_id){
		if(data_object.Drivers.length > 0 && field_id && document.getElementById(field_id)){
			document.getElementById(field_id).options.length = 0;
		}
		
		for(i=0; i<data_object.Drivers.length; i++){
			if(field_id && document.getElementById(field_id)){
				document.getElementById(field_id).options[i] = new Option(data_object.Drivers[i].name, data_object.Drivers[i].value);
			}
		}
	}
	
