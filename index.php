<?php
	if(isset($_POST['sync']) && $_POST['sync']) {
		$syncUserUrl = "http://192.168.1.104/bytesbay/sync-user.php";
		
		$APIAuthToken = $_POST['token'];
		$response = file_get_contents($syncUserUrl."?token=".$APIAuthToken);
		die($response);
		
		$headers = [
					"Accept: application/json",
					];
					
		$params = ['token' => $APIAuthToken];
					
		$options = [
					  CURLOPT_URL => $syncUserUrl,
					  CURLOPT_RETURNTRANSFER => 1,
					  CURLOPT_TIMEOUT => 20,
					  CURLOPT_HTTPHEADER => $headers,
					  CURLOPT_POST => 1,
					  CURLOPT_POSTFIELDS => $params,
					  //for debug only
					  CURLOPT_SSL_VERIFYHOST => false,
					  CURLOPT_SSL_VERIFYPEER => false,
					];
		
		$response = json_encode([]);
				
		try {
			if ($curl = curl_init()) {
				if (curl_setopt_array($curl, $options)) {
					if ($response = curl_exec($curl)) {
						curl_close($curl);
					} else {
						throw new Exception(curl_error($curl));
					}
				} else {
					throw new Exception(curl_error($curl));
				}
			} else {
				throw new Exception('unable to initialize cURL');
			}
		} catch (Exception $e) {
			if (is_resource($curl)) {
				curl_close($curl);
			}
			throw $e;
		}
		
		print($response);
		die();
	}
?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
    	
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>BytesBay</title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_local_server_address" content="http://192.168.1.104/bytesbay/">
        <meta name="_online_server_address" content="https://bytesbay.naet-tech.com/">
		
		<link rel="icon" href="/favicon.PNG" type="image/png" />
        
        <link rel="stylesheet" href="captiveportal-bootstrap.min.css">
        <link rel="stylesheet" href="captiveportal-iconkit.min.css">
        <link rel="stylesheet" href="captiveportal-theme.min.css">
    </head>

    <body>
        <div class="auth-wrapper">
            <div class="container-fluid h-100">
                <div class="row flex-row h-100 bg-white">
                    <div class="col-xl-8 col-lg-6 col-md-5 p-0 d-md-block d-lg-block d-sm-none d-none">
                        <div class="lavalite-bg" style="background-image: url('captiveportal-register-bg.jpg')">
                            <div class="lavalite-overlay">
								<div class="text-center" style="padding-top: 20%;">
									<h4 class="text-white mx-auto pb-2" style="text-transform: uppercase; border-bottom: 5px solid #28a745; width: 35%;">BytesBay WiFi HotSpots</h4>
									<h1 class="text-white font-weight-bold" style="font-size: 3rem;">Unlimited. Reliable. Affordable.</h1>
									<div class="row" style="margin-top: 96px;">
										<div class="col-12">
											<ul class="settings m-0 p-0">
											  
											</ul>
										</div>
										<div class="col-12 mt-20">
											<span class="text-white mx-2">© <span id="year"></span> BytesBay. All rights reserved.</span>
										</div>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-7 my-auto p-0">
                        <!--login panel-->
						<div class="authentication-form panel mx-auto d-none" style="padding: 50px 0px 50px 0px" id="login-panel">
                            <h3>Sign In to BytesBay</h3>
                            <p>Happy to see you again!</p>
							   
                            <form class="ajax" id="login_form" action="api/login" method="post">
								<input type="hidden" name="_sync" value="1" />
								<input type="hidden" name="_next" value="subscriptions-panel" />
								<input type="hidden" name="_current" value="login-panel" />
								<div class="form-group">
									<input type="text" name="phone_number" class="form-control" placeholder="Phone Number" required="" >
									<i class="ik ik-phone"></i>
									<p class="d-none error" for="phone_number"></p>
								</div>
								<div class="form-group">
									<input type="password" name="password" class="form-control" placeholder="Password" required="">
									<i class="ik ik-lock"></i>
									<p class="d-none error" for="password"></p>
								</div>
								<div class="form-group text-right">
									<a class="forgot-password-link text-danger" href="#">Forgot Password ?</a>
								</div>
								<div id="login_form_feedback"></div>
								<div class="text-center mt-3">
									<button type="submit" class="btn btn-theme mr-2" id="login_form_submit">Sign In</button>
								</div>
							</form>
                            
                            <div class="register">
                                <p>Don't have an account? <a class="register-link text-danger" href="#">Create an account</a></p>
                            </div>
                        </div>
						<!--end login panel-->
						<!--register panel-->
						<div class="authentication-form panel mx-auto d-none" style="padding: 50px 0px 50px 0px" id="register-panel">
                            <h3>Create Account</h3>
							<p>Join us today! It takes a minute.</p>
							<form class="ajax" id="register_form" action="api/register" method="post">
								<input type="hidden" name="_next" value="subscriptions-panel" />
								<input type="hidden" name="_current" value="register-panel" />
								<div class="form-group">
									<input type="text" name="phone_number" class="form-control" placeholder="Phone Number" required="" >
									<i class="ik ik-phone"></i>
									<p class="d-none error" for="phone_number"></p>
								</div>
								<div class="form-group">
									<input type="password" name="password" class="form-control" placeholder="Password" required="">
									<i class="ik ik-lock"></i>
									<p class="d-none error" for="password"></p>
								</div>
								<div class="form-group">
									<input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required="">
									<i class="ik ik-lock"></i>
									<p class="d-none error" for="password_confirmation"></p>
								</div>
								<div id="register_form_feedback"></div>
								<div class="text-center">
									<button type="submit" class="btn btn-theme mt-2 mr-2" id="register_form_submit">Create Account</button>
								</div>
							</form>
							<div class="mt-4 text-center">
								<a href="#" class="login-link text-danger">Login</a>
							</div>
                        </div>
						<!--end register panel-->
						<!--forgot password panel-->
						<div class="authentication-form panel mx-auto d-none" style="padding: 50px 0px 50px 0px" id="forgot-password-panel">
							<h3>Forgot Password</h3>
							<div class="alert alert-warning fade show" role="alert">
								6 digit password reset code will be sent to your phone.
							</div>
							<form class="ajax" id="forgot_password_form" action="api/send-password-reset-code" method="post">
								<input type="hidden" name="_next" value="reset-password-panel" >
								<div class="form-group">
									<input type="text" name="phone_number" class="form-control" placeholder="Phone Number" required="" >
									<i class="ik ik-phone"></i>
									<p class="d-none error" for="phone_number"></p>
								</div>
								<div id="forgot_password_form_feedback"></div>
								<div class="text-center mt-1">
									<button type="submit" class="btn btn-theme mr-2" id="forgot_password_form_submit">Send Code</button>
								</div>
							</form>
							<div class="mt-4 text-center">
								<a href="#" class="cancel-link text-danger">Cancel</a>
							</div>
						</div>
						<!--end forgot password panel-->
						<!--end reset password panel-->
						<div class="authentication-form panel mx-auto d-none" style="padding: 50px 0px 50px 0px" id="reset-password-panel">
							<h3>Reset Password</h3>
							<div class="alert alert-warning fade show" role="alert">
								Password reset code expires in 5 minutes.
							</div>
							<form class="ajax" id="reset_form" action="api/reset-password" method="post">
								<input type="hidden" name="_next" value="login-panel" >
								<input type="hidden" name="_current" value="reset-password-panel" >
								<div class="form-group">
									<input type="text" name="phone_number" class="form-control user-phone-number" placeholder="Phone Number" value="" required="" readonly >
									<i class="ik ik-phone"></i>
									<p class="d-none error" for="phone_number"></p>
								</div>
								<div class="form-group">
									<input type="text" name="token" class="form-control" placeholder="Password Reset Code" required="">
									<i class="ik ik-zap"></i>
									<p class="d-none error" for="token"></p>
								</div>
								<div class="form-group">
									<input type="password" name="password" class="form-control" placeholder="Password" required="">
									<i class="ik ik-lock"></i>
									<p class="d-none error" for="password"></p>
								</div>
								<div class="form-group">
									<input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required="">
									<i class="ik ik-lock"></i>
									<p class="d-none error" for="password_confirmation"></p>
								</div>
								<div id="reset_form_feedback"></div>
								<div class="text-center pt-2">
									<button type="submit" class="btn btn-theme mr-2" id="reset_form_submit">Reset Password</button>
								</div>
							</form>
							<div class="mt-3 text-center">
								<form class="ajax" id="resend_code_form" action="api/send-password-reset-code" method="post">
									<input type="hidden" name="phone_number" class="user-phone-number" value="" >
									<div id="resend_code_form_feedback"></div>
									<div class="my-3">
										<button type="submit" class="btn btn-success mr-2" id="resend_code_form_submit">Resend Code</button>
									</div>
								</form>
								<a href="#" class="cancel-link text-danger">Cancel</a>
							</div>
						</div>
						<!--end reset password panel-->
						<!--subscriptions panel-->
						<div class="authentication-form panel mx-auto d-none" style="padding: 50px 0px 50px 0px" id="subscriptions-panel">
							<div class="mb-2">
							<label class="h6 mt-2"><b class="text-muted">Bal: </b> <b class="text-danger user-balance"></b></label>
							<button class="btn btn-theme float-right deposit-btn"><i class="ik ik-upload"></i>Deposit</button>
							</div>
                            <div class="alert alert-warning not-subscribed d-none" role="alert">
								You currently don't have an active subscription. Deposit money into your account and activate your preferred plan.
							</div>
							<div class="alert alert-info subscribed d-none" role="alert">
								Your current subscription is active until <strong class="allow-until"></strong>
							</div>
							<div class="form-radio">
								<form class="ajax mt-2" id="subscription_form" action="api/activate-plan" method="post">
									<input type="hidden" name="_next" value="subscriptions-panel" />
									<input type="hidden" name="_current" value="subscriptions-panel" />
									<div class="plans">
										<div class="alert alert-info" role="alert">
											Please wait. Loading plans...
										</div>
									</div>
									<div class="form-group m-0 p-0">
										<p class="d-none error" for="id"></p>
									</div>
									<div id="subscription_form_feedback"></div>
									<div class="text-center mt-4">
										<button type="submit" class="btn btn-theme" id="subscription_form_submit">Activate</button>
									</div>
								</form>
								<div class="text-center mt-3">
									<a href="#" class="logout-link text-danger">Logout</a>
								</div>
								<div class="alert alert-info mt-3" role="alert">
									You will be redirected to internet automatically once you subscribe. If there is delay just refresh this page.
								</div>
							</div>
							
                        </div>
						<!--end subscriptions panel-->
						<!--deposit panel-->
						<div class="authentication-form panel mx-auto d-none" style="padding: 50px 0px 50px 0px" id="deposit-panel">
							<div class="mb-2">
							<label class="h6 mt-2"><b class="text-muted">Bal: </b> <b class="text-danger user-balance"></b></label>
							<button class="btn btn-theme float-right refresh-btn"><i class="ik ik-rotate-ccw"></i>Refresh</button>
							</div>
                            <div class="alert alert-warning fade show" role="alert">
								Payment will be initiated on the phone number entered below. Check the phone and enter MPESA pin.
							</div>
							<form class="ajax" id="deposit_form" action="api/mpesa-deposit" method="post">
								<input type="hidden" name="_current" value="deposit-panel" />
								<div class="form-group">
									<label for="paying-phone-number">Phone number to iniate payment</label>
									<input type="text" name="phone_number" class="form-control pl-2" id="paying-phone-number" required="" >
									<p class="d-none error" for="phone_number"></p>
								</div>
								<div class="form-group">
									<label for="deposit-amount">Amount to deposit</label>
									<input type="number" name="amount" class="form-control pl-2" id="deposit-amount" required="" >
									<p class="d-none error" for="amount"></p>
								</div>
								<div id="deposit_form_feedback"></div>
								<div class="text-center mt-4">
									<button type="submit" class="btn btn-theme" id="deposit_form_submit">Deposit</button>
								</div>
							</form>
							<div class="text-center mt-2">
								<a href="#" class="d-block mt-3 mb-2 text-danger" id="back-to-subscriptions-link"><i class="ik ik-chevrons-left"></i> Back to Subscriptions</a>
								<a href="#" class="cancel-link text-danger">Cancel</a>
							</div>
                        </div>
						<!--end deposit panel-->
						<!--original login panel-->
						<form method="post" action="$PORTAL_ACTION$" class="d-non" id="original_login_form">
							<input name="auth_user" type="text" id="auth-user">
							<input name="auth_pass" type="text" id="auth-pass">
							<input name="auth_voucher" type="hidden">
						    <input name="redirurl" type="hidden" value="$PORTAL_REDIRURL$">
						    <input name="zone" type="hidden" value="$PORTAL_ZONE$">
							
							<input type="submit" name="accept" class="btn btn-theme d-non" id="original_login_form_submit" value="Sign In" >
						</form>
						<!--end original login panel-->
                    </div>
                </div>
            </div>
        </div>
        
        <script src="captiveportal-jquery-3.3.1.min.js"></script>
        <script src="captiveportal-bootstrap.min.js"></script>
        <script src="captiveportal-custom.js"></script>
    </body>
</html>
