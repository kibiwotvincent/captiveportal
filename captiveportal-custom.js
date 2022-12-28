$(function () {
	const localServerAddress = $('meta[name="_local_server_address"]').attr('content');
	const onlineServerAddress = $('meta[name="_online_server_address"]').attr('content');
	const userPhoneNumber = readSession('user-phone-number');
	var _activePanel = readSession('active-panel');
	var loggedInUser = readSession('user');
	var user = null;
	
	if(_activePanel == null || _activePanel == "") {
		setActivePanel("login-panel");
	}
	else {
		setActivePanel(_activePanel);
	}
	
	if(userPhoneNumber != "") {
		$('.user-phone-number').val(userPhoneNumber);
	}
	
	if(loggedInUser !== null && loggedInUser !== "undefined") {
		if(_activePanel == "login-panel") {
			//redirect user to account if logged in already
			setActivePanel("subscriptions-panel");
		}
		else {
			user = JSON.parse(loggedInUser);
			$.ajax({
					type: "GET",
					url: onlineServerAddress+'api/user',
					data: {},
					dataType: "json",
					headers: {'Authorization': 'Bearer '+user.api_auth_token},
					success: function(response){ 
						user = response.data;
						console.log(user);
						
						fetchPlans();
						writeSession('user', JSON.stringify(user));
						$('#paying-phone-number').val(user.phone_number);
						//fill user balance and subscription notice
						$('.user-balance').html(user.balance_string);
						$('.allow-until').html(user.allow_until_string);
						
						if(user.subscribed == 1) {
							$('.subscribed').removeClass('d-none');
							$('.not-subscribed').addClass('d-none');
							doLogin();
						}
						else {
							$('.not-subscribed').removeClass('d-none');
							$('.subscribed').addClass('d-none');
						}
					}
			});
		}
	}
	else {
		if(_activePanel == "subscriptions-panel" || _activePanel == "deposit-panel") {
			setActivePanel("login-panel");
			refreshPage();
		}
	}
	
	//fetch site settings
	fillSettings();
	
	let date = new Date;
	let year = date.getFullYear();
	$('#year').html(year);
	
	$('.register-link').click(function (e) {
		e.preventDefault();
		setActivePanel("register-panel");
		refreshPage();
	});
	$('.login-link').click(function (e) {
		e.preventDefault();
		setActivePanel("login-panel");
		refreshPage();
	});
	$('.forgot-password-link').click(function (e) {
		e.preventDefault();
		setActivePanel("forgot-password-panel");
		refreshPage();
	});
	$('.cancel-link').click(function (e) {
		e.preventDefault();
		removeSession('user-phone-number');
		setActivePanel("login-panel");
		refreshPage();
	});
	$('.logout-link').click(function (e) {
		e.preventDefault();
		removeSession('user');
		removeSession('user-phone-number');
		setActivePanel("login-panel");
		refreshPage();
	});
	$('#back-to-subscriptions-link').click(function (e) {
		e.preventDefault();
		setActivePanel("subscriptions-panel");
		refreshPage();
	});
	$('.deposit-btn').click(function (e) {
		e.preventDefault();
		setActivePanel("deposit-panel");
		refreshPage();
	});
	$('.refresh-btn').click(function (e) {
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
		
		let APIAuthToken = "";
		
		if(loggedInUser !== null) {
			APIAuthToken = user.api_auth_token;
		}
		
		$.ajax({
			type: "POST",
			url: onlineServerAddress+$(formName).attr("action"),
			data: $(formName).serialize(),
			dataType: "json",
			headers: {'Authorization': 'Bearer '+APIAuthToken},
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
				
				//set phone number session if response has user phone number
				if(typeof response.data !== 'undefined' && response.data.phone_number != "") {
					$('.user-phone-number').val(response.data.phone_number);
					writeSession('user-phone-number', response.data.phone_number);
				}
				
				let nextPanel = $('form'+formName+' input[name="_next"]').val();
				let currentPanel = $('form'+formName+' input[name="_current"]').val();
				
				if(typeof currentPanel !== 'undefined' && currentPanel != "") {
					if(currentPanel == "login-panel" || currentPanel == "register-panel") {
						//save user
						writeSession('user', JSON.stringify(response.data));
					}
				}
				
				if(typeof nextPanel !== 'undefined' && nextPanel != "") {
					setActivePanel(nextPanel);
				}
				
				window.location.reload(true);
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
	
	function writeSession(sessionName, sessionValue) {
		sessionStorage.setItem(sessionName, sessionValue);
	}
	
	function removeSession(sessionName) {
		sessionStorage.removeItem(sessionName);
	}
	
	function readSession(sessionName) {
		let sessionItem = sessionStorage.getItem(sessionName);
		return sessionItem == 'null' ? null : sessionItem;
	}
	
	function setActivePanel(activePanel) {
		writeSession('active-panel', activePanel);
		//hide all other panels
		$('.panel').each(function(i) {
			if($(this).attr('id') == activePanel) {
				$(this).removeClass('d-none');
			}
			else {
				$(this).addClass('d-none');
			}
		});
	}
	
	function doLogin() {
		let userToLogin = readSession('user');
		
		if(userToLogin != "") {
			userToLogin = JSON.parse(userToLogin);
			//sync local and online database first
			$.post(localServerAddress+'sync-user.php', {'username': userToLogin.phone_number, 'token':userToLogin.hotspot_login_token}, function(jsonResponse) {
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
	}
	
	function fetchPlans() {
		$.ajax({
			type: "GET",
			url: onlineServerAddress+'api/plans',
			data: {},
			dataType: "json",
			success: function(response){ 
						let plansArray = response.data;
						
						let html = "";
						for(let i = 0; i < plansArray.length; i++) {
							let plan = plansArray[i];
							html += "<div class=\"form-group m-0 p-0\">"+
											"<div class=\"radio radio-danger radio-inline\">"+
												"<label>"+
													"<input type=\"radio\" name=\"id\" value=\""+plan.id+"\">"+
													"<i class=\"helper\"></i>"+plan.title+" <b>(Ksh "+plan.price+")</b>"+
												"</label>"+
											"</div>"+
										"</div>";
						}
						$('.plans').html(html);
					 }
		});
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