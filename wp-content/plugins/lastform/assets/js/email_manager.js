jQuery(document).ready(function(){		
		/*******//////////////////Email Manager Scripts////////////////*********/
		var showC='true';
		var myVar;
		//Send Mailings
		jQuery('#origincode_contact_email_manager').on('click tap','#btn',function(){
				alert('This option is disabled for free version. Please upgrade to pro license to be able to use it.');		
			});

		//Choose Form
		jQuery('#origincode_contact_email_manager #origincode_form_choose').on('change',function(){
			var formsToShow=jQuery(this).val();
			jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					data: {
						"data": formsToShow,
						"action": 'origincode_email_action',
		                "task": 'showForms',
		                "nonce" : origincode_forms_obj.nonce
					},
					beforeSend: function(){
						jQuery('#origincode_contact_email_manager #table_overlay').css('display','block');					
					},
					success: function(response){
						var response = jQuery.parseJSON(response);
				   			if(response.output){		   				
				   				jQuery("#origincode_contact_email_manager").find("#origincode-table").html(response.output); 	
				   				jQuery('#origincode_contact_email_manager #table_overlay').css('display','none'); 
				            }
					},
					error: function(){
					}
				});
		});
		//Delete Subscriber
		jQuery('#origincode_contact_email_manager').on('click tap','.del_wrap',function(e){
			e.preventDefault();
			alert('This option is disabled for free version. Please upgrade to pro license to be able to use it.');	
		});
		//Add Subscriber
		jQuery('#origincode_contact_email_manager').on('click tap','.add_wrap',function(e){
			e.preventDefault();
			alert('This option is disabled for free version. Please upgrade to pro license to be able to use it.');	

		});
		setInterval(function(){
			var formId=jQuery('#origincode_contact_email_manager #origincode_form_choose').val();
			jQuery.ajax({
						type: "POST",
						url: ajaxurl,
						data: {
							data:formId,
							action: 'origincode_email_action',
			                task: 'refreshTable',
			                nonce : origincode_forms_obj.nonce
						},
						beforeSend: function(){
						},
						success: function(response){
							var response = JSON.parse(response);
				   			if(response.output){
				   				jQuery("#origincode_contact_email_manager #origincode-table").find("tbody").html(response.output);
				            }
						},
					});
		}, 5000);
		//Refresh
		function refreshTable(formID){
			var formId=jQuery('#origincode_contact_email_manager #origincode_form_choose').val();
			jQuery.ajax({
						type: "POST",
						url: ajaxurl,
						data: {
							"data":formID,
							"action": 'origincode_email_action',
			                "task": 'refreshTable',
		                	nonce : origincode_forms_obj.nonce
						},
						beforeSend: function(){
						},
						success: function(response){
							var response = jQuery.parseJSON(response);
				   			if(response.output){
				   				jQuery("#origincode_contact_email_manager #origincode-table").find("tbody").html(response.output);
				            }
						},
						error: function(){
						}
					});
		}

		if(origincode_forms_obj.mail_status=='start'){
			setInterval(function(){
				jQuery.ajax({
						type: "POST",
						url: ajaxurl,
						data: {
							"action": 'origincode_email_action',
			                "task": 'refreshProgress',
			                "nonce" : origincode_forms_obj.nonce
						},
						beforeSend: function(){
						},
						success: function(response){
							var response = jQuery.parseJSON(response);
							jQuery("#origincode_contact_email_manager").find("#progress_meter").css('width',response.percent+'%');
							jQuery("#origincode_contact_email_manager").find("#progress_time").text(response.need_time);
							if(response.cond=="finish"){
									showCont(showC);
								}
						},
						error: function(){
						}
					});
			},10000);
		}
		function loadingProcess(){
			jQuery.ajax({
						type: "POST",
						url: ajaxurl,
						data: {
							"action": 'origincode_email_action',
			                "task": 'refreshProgress'
						},
						beforeSend: function(){
						},
						success: function(response){
							var response = jQuery.parseJSON(response);
							jQuery("#origincode_contact_email_manager").find("#progress_meter").css('width',response.percent+'%');
							jQuery("#origincode_contact_email_manager").find("#progress_time").text(response.need_time);
							if(response.cond=="finish"){
									showCont(showC);
							}
						},
						error: function(){
						}
					});

		}
		function showCont(some){
			//jQuery('#button').on('click',function(){
				jQuery.ajax({
						type: "POST",
						url: ajaxurl,
						data: {
							"action": 'origincode_email_action',
			                "task": 'showCont',
			                "noCancel":some
						},
						beforeSend: function(){
						},
						success: function(response){
							var response = jQuery.parseJSON(response);
							if(response.output){
								jQuery("#origincode_contact_email_manager").find("#showCont").html(response.output);
							}
						},
						error: function(){
						}
					});
				//})
			
		}
		//Cancel
		jQuery('#origincode_contact_email_manager').on('click tap','#origincode_cancel',function(){
			var sub_choose_form=jQuery('#origincode_contact_email_manager #origincode_form_choose').val();
			jQuery.ajax({
						type: "POST",
						url: ajaxurl,
						data: {
							action: 'origincode_email_action',
			                task: 'origincode_cancel',
						},
						beforeSend: function(){
						},
						success: function(response){
							clearInterval(myVar);
							var cancel='false';
							showCont(cancel);
							jQuery("#origincode_contact_email_manager").find("#done").hide();
							refreshTable(sub_choose_form);
						},
						error: function(){
						}
					});
		})
		/*******//////////////////Email Manager Scripts////////////////*********/
});