$(function () {
	const localServerAddress = $('meta[name="_local_server_address"]').attr('content');
	const onlineServerAddress = $('meta[name="_online_server_address"]').attr('content');
	
	//fetch site settings
	fillSettings();
	
	let date = new Date;
	let year = date.getFullYear();
	$('#year').html(year);
	
	$('.logout-link').click(function (e) {
		e.preventDefault();
		refreshPage();
	});
	
	
	$('form.ajax').submit(function (e) {
		e.preventDefault();
		let id = $(this).attr('id');
		ajaxPost('#'+id);
	});

	function ajaxPost(formName)
	{
		commandButton = formName+"_submit";
		commandButtonText = $(commandButton).html();
		
		$.ajax({
			type: "POST",
			url: onlineServerAddress+$(formName).attr("action"),
			data: $(formName).serialize(),
			dataType: "json",
			beforeSend: function(){
				$(commandButton).attr("disabled","disabled");
				$(formName+"_feedback").addClass("d-none");
				
				$(formName+"_submit").html(commandButtonText+" <i class=\"ik ik-loader\"></i>");
				
				resetFormStyle(formName);
			},
			complete: function(){
				$(commandButton).removeAttr("disabled").html(commandButtonText);
				return;
			},
			success: function(response) {
				let message = "<div class=\"alert alert-success role=\"alert\">"+response.message+"</div>";
				$(formName+"_feedback").html(message).removeClass("d-none");
				
				let user = response.data;
				if(user.subscribed == 1) {
					console.log("user is subscribed");
					doLogin(user);
				}
				else {
					console.log("user is not subscribed");
				}
			},
			error: function(response) {
				let jsonResponse = response.responseJSON;
				
				if(typeof jsonResponse.message !== 'undefined') {
					let message = "<div class=\"alert alert-danger role=\"alert\">"+jsonResponse.message+"</div>";
					$(formName+"_feedback").html(message).removeClass("d-none");
				}
				
				let statusCode = response.status;
				if(statusCode == 422) {
					/*validation errors*/
					let errors = jsonResponse.errors;
					
					let keysArray = Object.keys(errors);
					
					for(let key of keysArray) {
						updateFormStyle(formName, key, errors);
					};
					return;
				}
				
			}
		});
	}
	
	function updateFormStyle(formName, field, errors) {
		/*add is-invalid class to invalid inputs*/
		$('form'+formName+' input[type="text"], input[type="number"]').each(function(i) {
			if(field == $(this).attr('name')) {
				$(this).addClass('is-invalid');
			}
		});
		
		/*add error messages below each invalid input*/
		$('form'+formName+' .error').each(function(i) {
			if(field == $(this).attr('for')) {
				$(this).addClass('text-danger');
				$(this).removeClass('d-none').html(errors[field][0]);
			}
		});
	}
	
	function resetFormStyle(formName) {
		$('form'+formName+' input[type="text"], input[type="number"]').each(function(i) {
			$(this).removeClass('is-invalid');
		});
		
		$('form'+formName+' .error').each(function(i) {
			$(this).addClass('d-none').html("");
		});
	}
	
	function refreshPage() {
		window.location.reload(true);
	}
	
	function doLogin(user) {
		//sync local and online database first
		$.post(localServerAddress+'sync-user.php', {'username': user.phone_number, 'token':user.hotspot_login_token}, function(jsonResponse) {
			if(jsonResponse.status == 0) {
				return false;
			}	
			else {
				//populate original login button and click login
				let username = jsonResponse.username;
				let password = jsonResponse.password;
				$('#auth-user').val(username);
				$('#auth-pass').val(password);
				$('#original_login_form').submit();
				return true;
			}
		}, 'json');
	}
	
	function fillSettings() {
		$.ajax({
			type: "GET",
			url: onlineServerAddress+'api/settings/html',
			data: {},
			dataType: "html",
			success: function(response){ 
						$('.settings').html(response);
					 }
		});
	}
	
	
});