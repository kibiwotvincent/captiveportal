$(function () {
	const localServerAddress = $('meta[name="_local_server_address"]').attr('content');
	const onlineServerAddress = $('meta[name="_online_server_address"]').attr('content');
	
	//fetch site settings
	fillSettings();
	
	let date = new Date;
	let year = date.getFullYear();
	$('#year').html(year);
	
	//do login
	doLogin();
	
	function doLogin() {
		//sync local and online database first
		let u = $('#auth-user').val();
		let p = $('#auth-pass').val();
		
		$.post(localServerAddress+'sync-user.php', {'username': u, 'token': p}, function(jsonResponse) {
			if(jsonResponse.status == 0) {
				return false;
			}	
			else {
				//click login
				$('#login_form').submit();
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