<?php
namespace Rosance
{	 
	/**
	* This class is used for Database and Sign up/in related tasks
	* Login / Register uses a hashing algoritm for passwords
	* Copyright (C) 2020-2021 by Eduard Neacsu
	* Created for Rosance, https://rosance.com
	*/
	require_once("rosance_autoload.php");
	use Rosance\Callback;
	use Rosance\Data;
	class Database
	{
		public static $staticServer = "localhost";
		public $server = "localhost";
		protected $username = "root";
		protected $password = "";
		protected $database = "rosance";
		protected $google_credentals = "38eb28e73c9d43e1bb1843e69e4e358f.json";

		/**
		 * Opens a database connection with the master database
		 * @return mysqli_connection $connection
		 */
		public function dbconnectmaster()
		{
			try
			{
				$connection = mysqli_connect($this->server ,$this->username,$this->password, $this->database);
				return $connection;
			}
			catch(\Exception $ex)
			{
				return $ex;
			}
		}
		/**
		 * Closes a database connection
		 * @param mysqli_connection $connection
		 */
		public function dbclosemaster($connection)
		{
			mysqli_close($connection);
		}
		/**
		 * Open a connection to a slave database
		 * @param string $database
		 * @return mysqli_connection;
		 */
		public function dbconnectslave($database)
		{
			try
			{
				return mysqli_connect($this->server, $this->username, $this->password,$database);
			}
			catch (Exception $ex)
			{
				return $ex->getMessage();
			}
		}
		/**
		 * Closes a database connection
		 * @param mysqli_connection $connection
		 */
		public function dbcloseslave($connection)
		{
			mysqli_close($connection);
		}
		/**
		 * Login user to website
		 * @param string $email
		 * @param string $password
		 */
		public function loginUser($email , $password)
		{
			try
			{
				if(empty($email) or empty($password))
						throw new \Exception("Fields cannot be empty");
				$connection = $this->dbconnectmaster();
				$email = mysqli_real_escape_string($connection ,$email);
				$query = "SELECT * FROM users WHERE Email='".$email."'";
				$result = mysqli_query($connection, $query);
				if($result->num_rows == 0)
				{
					$this->dbclosemaster($connection);
					throw new \Exception("Email does not match!");
				}
				else
				{
					$row = mysqli_fetch_assoc($result);
					$passwordverified = password_verify($password, $row['Password']);
					if($passwordverified == false)
					{
						$this->dbclosemaster($connection);
						throw new \Exception("Password does not match!");
					}
					else
					{
							if($row['Activated'] == false)
							{
								$this->dbclosemaster($connection);
								throw new \Exception("Please check your mail inbox and activate your account!");
							}
							else
							{
							$this->dbclosemaster($connection);
							$callback = new Callback();
							if(!isset($_SESSION))
								session_start();
							$_SESSION['loggedIN'] = $row['UID'];
							$ncs_user = ["FirstName" => $row['FirstName'],
										"LastName" => $row['LastName'],
										"UID" => $row['UID'],
										"Provider" => "rosance"];
							$ncs_user = json_encode($ncs_user);
							setcookie("NCS_USER", $ncs_user , time() + (86400 * 30), "/");
							return $callback->SendSuccessOnMainPageWithRedirect("Authentication successfully!","dashboard");
							}
					}
				}
			}
			catch(\Exception $ex)
			{
				$callback = new Callback();
				return $callback->SendErrorToast($ex->getMessage());
			}
		}
		/**
		 * Login user into website using google as a provider
		 * @param string $token
		 */
		public function loginUserWithGoogle($token)
		{
			include_once 'google/vendor/autoload.php';

			$client = new \Google_Client(['client_id' => "42685965557-urskj9esf47q1rfti99dk6ud1kdtp1t4.apps.googleusercontent.com"]);
			$payload = $client->verifyIdToken($token);
			if ($payload) {
				try
				{
					if(empty($payload['email']) or empty($payload['family_name']) or empty($payload['given_name']))
						throw new \Exception("Some fields are coming empty from google, make sure email , family name and given name is not empty on your google account!");
				
					$connection = $this->dbconnectmaster();
					$email = mysqli_real_escape_string($connection, $payload['email']);
					$uid = mysqli_real_escape_string($connection, $payload['sub']);
					$query = "SELECT * FROM users WHERE UID='".$uid."' AND Email='".$email."'";
					$result = mysqli_query($connection, $query);
					if($result)
					{
						if($result->num_rows > 0)
						{
							//user is allready registered
							//just login
							$row = mysqli_fetch_assoc($result);
							if($row['Activated'] == false)
							{
								$this->dbclosemaster($connection);
								throw new \Exception("Please check your mail inbox and activate your account!");
							}

							if(empty($row['Business_Name']))
							{
								$this->dbclosemaster($connection);
								return "
								<script>
								wtoast('Whoops , you do not have a business name ...').then( _ => {
									$.ajax({
										url : 'pages/registration/business.php',
										type: 'post',
										cache: false,
										data: {'NCS_ID':'".$uid."'},
										beforeSend: function() { $('#overlay').show(); },
										success: function(data) {
											$('#overlay').hide();
											$('#content').html(data);
										},
										error: function() { $('#content').html(`<h2 class='text-center'>Sorry, the page was unreachable :( </h2>`); }
									})
								});
								</script>";
							}else {
								$this->dbclosemaster($connection);
								$callback = new Callback();
								if(!isset($_SESSION))
									session_start();
								$_SESSION['loggedIN'] = $row['UID'];
								$ncs_user = ["FirstName" => $row['FirstName'],
											"LastName" => $row['LastName'],
											"UID" => $uid,
											"Provider" => "google"];
								$ncs_user = json_encode($ncs_user);
								setcookie("NCS_USER", $ncs_user , time() + (86400 * 30), "/");
								return $callback->SendSuccessOnMainPageWithRedirect("Authentication successfully!","dashboard");
							}
						}
						else
						{
							//user is new , register him
							//just register
							$this->dbclosemaster($connection);
							$payload['email_verified'] == "true" ? $v = true : $v = false;
							return $this->registerUser(
								$payload['family_name'],
								$payload['given_name'],
								"notempty",
								"notempty",
								$payload['email'],
								null ,
								"google",
								$payload['sub'],
								$payload['picture'],
								$v
							);
						}
					}
					else
					{
						throw new \Exception("Something went wrong , please try another time!");
					}
				}
				catch(\Exception $ex)
				{
					$callback = new Callback();
					return $callback->SendErrorToast($ex->getMessage());
				}
			} else {
				$callback = new Callback();
				return $callback->SendErrorToast("Something went wrong with your google authentication, please choose a different authentication type!");
			}
		}
		/**
		 * Login user into website using Facebook as a provider
		 * @param string $token
		 */
		public function loginUserWithFacebook($token){
			require_once("Facebook/autoload.php");
			$fb = new \Facebook\Facebook([
				'app_id' => '847597679307514',
				'app_secret' => 'ef019d5a484663658ee1cd96f4288900',
				'default_graph_version' => 'v2.10'
			]);

			try {
				// Returns a `Facebook\Response` object
				$response = $fb->get('/me?fields=id,first_name,last_name,email,picture', $token);
			} catch(Facebook\Exception\ResponseException $e) {
				$callback = new Callback();
				return $callback->SendErrorToast('Facebook returned an error, please try again later ! #F0001');
			} catch(Facebook\Exception\SDKException $e) {
				$callback = new Callback();
				return $callback->SendErrorToast('Facebook returned an error, please try again later ! #F0002');
			}
			//E logat aici
			$user = $response->getGraphUser();
			try{
				if(empty($user['email']) or empty($user['first_name']) or empty($user['last_name']))
					throw new \Exception("Some fields are coming empty from Facebook, make sure email , family name and given name is not empty on your facebook account!");
				$connection = $this->dbconnectmaster();
				$email = mysqli_real_escape_string($connection, $user['email']);
				$uid = mysqli_real_escape_string($connection, $user['id']);
				$query = "SELECT * FROM users WHERE UID='".$uid."' AND Email='".$email."'";
				$result = mysqli_query($connection, $query);
				if($result)
				{
					if($result->num_rows > 0)
					{
						//user is allready registered
						//just login
						$row = mysqli_fetch_assoc($result);
						if($row['Activated'] == false)
						{
							$this->dbclosemaster($connection);
							throw new \Exception("Please check your mail inbox and activate your account!");
						}

						if(empty($row['Business_Name']))
						{
							$this->dbclosemaster($connection);
							return "
							<script>
							wtoast('Whoops , you do not have a business name ...').then( _ => {
								$.ajax({
									url : 'pages/registration/business.php',
									type: 'post',
									cache: false,
									data: {'NCS_ID':'".$uid."'},
									beforeSend: function() { $('#overlay').show(); },
									success: function(data) {
										$('#overlay').hide();
										$('#content').html(data);
										},
									error: function() { $('#content').html(`<h2 class='text-center'>Sorry, the page was unreachable :( </h2>`); }
								})
							});
							</script>";
						}else {
							$this->dbclosemaster($connection);
							$callback = new Callback();
							if(!isset($_SESSION))
								session_start();
							$_SESSION['loggedIN'] = $row['UID'];
							$ncs_user = ["FirstName" => $row['FirstName'],
										"LastName" => $row['LastName'],
										"UID" => $uid,
										"Provider" => "facebook"];
							$ncs_user = json_encode($ncs_user);
							setcookie("NCS_USER", $ncs_user , time() + (86400 * 30), "/");
							return $callback->SendSuccessOnMainPageWithRedirect("Authentication successfully!","dashboard");
						}
					}else {
						//inregistreaza-l
						//la asta trebuie verificat mailul
						$this->dbcloseslave($connection);
						return $this->registerUser(
							$user['first_name'],
							$user['last_name'],
							"notempty",
							"notempty",
							$user['email'],
							null,
							"facebook",
							$user['id'],
							$user['picture']['url'],
							false
						);
					}
				}else {
					throw new \Exception("Something went wrong ! #F0003");
				}

			}catch(\Exception $ex)
			{
				$callback = new Callback();
				return $callback->SendErrorToast($ex->getMessage());
			}

		}
		/**
		 * Register a new user into website
		 * If provider is Rosance you don't need to assign any value after $provider
		 * @param string $firstname
		 * @param string $lastname
		 * @param string $password
		 * @param string $password2
		 * @param string $email
		 * @param string $bname
		 * @param string $provider // Defaulted to "Rosance"
		 * @param string $id // Defaulted to null
		 * @param string $picture // Defaulted to "images/default.png"
		 * @param bool $email_verified // Defaulted to false
		 */
		public function registerUser($firstname, $lastname , $password, $password2, $email, $bname, $provider = "rosance", $id = null, $picture = "images/default.png", $email_verified = false)
		{
		try
		{
			if(empty($firstname) || empty($lastname) || empty($password) || empty($password2) || empty($email))
			{
				throw new \Exception("Fields cannot be empty");
			}
			if($password != $password2 && $provider == "rosance" )
			{
				throw new \Exception("Passwords does not match");
			}
			!empty($picture) ? $picture = $picture : $picture = "images/default.png";
			
			$connection = $this->dbconnectmaster();
			$firstname = mysqli_real_escape_string($connection ,$firstname);
			$lastname = mysqli_real_escape_string($connection ,$lastname);
			$email = mysqli_real_escape_string($connection, $email);
			$bname = mysqli_real_escape_string($connection, $bname);
			$picture = mysqli_real_escape_string($connection, $picture);
			$email_verified = mysqli_real_escape_string($connection, $email_verified);
			$query = "SELECT * FROM users WHERE Email='".$email."'";
			$result = mysqli_query($connection, $query);
			if($result->num_rows != 0)
			{
				$this->dbclosemaster($connection);
				throw new \Exception("Email allready registered");
			}
			$queryB = "SELECT * FROM users WHERE Business_Name='".$bname."'";
			$resultB = mysqli_query($connection,$queryB);
			if($resultB->num_rows != 0)
			{
				$this->dbclosemaster($connection);
				throw new \Exception("Business name allready registered");
			}
				//insert data into the database
			if($provider == "rosance")
			{
				$uid = uniqid (rand (),false);
				$password = password_hash($password, PASSWORD_DEFAULT);
			}
			else
			{
				$uid = $id;
				$password = password_hash(substr($id , 3,15), PASSWORD_DEFAULT);
			}
			$email_verified ? $token="0" : $token = uniqid(rand(),false);
			$date = date("Y-m-d H:i");
			
			$query = "INSERT INTO users (UID,Email,Token,FirstName,LastName,Password,Premium,Business_Name,Date,Activated, Provider, Profile_Pic) VALUES ('".$uid."','".$email."','".$token."','".$firstname."','".$lastname."','".$password."','false','".$bname."','".$date."','".$email_verified."','".$provider."','".$picture."')";
			
			$result = mysqli_query($connection,$query);
			if(!$result)
			{
				$this->dbclosemaster($connection);
				throw new \Exception("Something went wrong with this process! Please try again later ... ");
			}
				$this->dbclosemaster($connection);
				if(!$email_verified && $provider == "rosance")
				{
					$mail = $this->SendTokenEmail($email,$firstname,$lastname, $token, $uid);
					if($mail)
					{
						$callback = new Callback();
						return $callback->SendSuccessOnMainPage("Account registered successfully , please check your mailbox to complete the registration!");
					}
					else {
						throw new \Exception("Could not send the email , please try agaian later!");
					}
				}elseif($email_verified && $provider =="google") {
					return "
					<script>
					itoast('Account registered successfully. One more step tho ...').then( _ => {
						$.ajax({
							url : 'pages/registration/business.php',
							type: 'post',
							cache: false,
							data: {'NCS_ID':'".$uid."'},
							beforeSend: function() { $('#overlay').show(); },
							success: function(data) {
								$('#overlay').hide();
								$('#content').html(data);
							},
							error: function() { $('#content').html(`<h2 class='text-center'>Sorry, the page was unreachable :( </h2>`); }
						})
					});
					</script>";
				}elseif(!$email_verified && $provider != "rosance")
				{
					$mail = $this->SendTokenEmail($email,$firstname,$lastname, $token, $uid);
					if($mail)
					{
						return "<script>
						showsuccess('Account registered successfully. A couple more steps tho ... , a link containing an activation token was sent to ".$email."  , also check your spam folder. Meanwhile , please tell us your business name ').then( _ => {
							$.ajax({
								url : 'pages/registration/business.php',
								type: 'post',
								cache: false,
								data: {'NCS_ID':'".$uid."'},
								beforeSend: function() { $('#overlay').show(); },
								success: function(data) {
									$('#overlay').hide();
									$('#content').html(data);
								},
								error: function() { $('#content').html(`<h2 class='text-center'>Sorry, the page was unreachable :( </h2>`); }
							})
						});
						</script>";
					}
					else {
						throw new \Exception("Could not send the email , please try agaian later!");
					}
				}
			}
			catch(\Exception $ex)
			{
				$callback = new Callback();
				return $callback->SendErrorToast($ex->getMessage());
			}
		}
		
		public function finishRegisterUser($id , $bname)
		{
			try{
				if(empty($id) or empty($bname))
					throw new \Exception("Id or Business name is empty !");
				
				$connection  = $this->dbconnectmaster();
				$id = mysqli_real_escape_string($connection, $id);
				$bname = mysqli_real_escape_string($connection, $bname);
				$globals = $this->GetGlobals();

				//Check if Bname is not allready used
				$prequery = "SELECT * from users where Business_Name='".$bname."'";
				$preresult = mysqli_query($connection,$prequery);
				if($preresult->num_rows > 0)
				{
					$this->dbclosemaster($connection);
					throw new \Exception("Business name allready in use, please choose another one!");
				}

				$query = "UPDATE users SET Business_Name='".$bname."' WHERE UID='".$id."'";
				$result = mysqli_query($connection,$query);

				if($result)
				{
					if($globals['TEST'] == false)
						$this->createSubdomain($globals['PREFIX'],$globals['PWD'],$bname);

					$this->createFolder($bname,"../");
					$callback = new Callback();
					$this->SendWelcomeEmail();
					$query2 = "SELECT * FROM users WHERE UID='".$id."' AND Business_Name='".$bname."'";
					$result2 = mysqli_query($connection,$query2);
					$this->dbclosemaster($connection);
					if(!$result2)
						throw new \Exception("Something went wrong! Please try again later !");
					$row = mysqli_fetch_assoc($result2);
					if($row['Activated'] == false)
						throw new \Exception("Please check your mail inbox and activate your account !");
					if(!isset($_SESSION))
						session_start();
					$_SESSION['loggedIN'] = $row['UID'];
					$ncs_user = ["FirstName" => $row['FirstName'],
								"LastName" => $row['LastName'],
								"UID" => $id,
								"Provider" => "google"];
					$ncs_user = json_encode($ncs_user);
					setcookie("NCS_USER", $ncs_user , time() + (86400 * 30), "/");

					return $callback->SendSuccessOnMainPageWithRedirect("Account successfully created, Welcome to Rosance","dashboard");
				}
				else {
					throw new \Exception("Something went wrong,  please try again later !");
				}


			}catch(\Exception $ex)
			{
				$callback = new Callback();
				return $callback->SendErrorToast($ex->getMessage());
			}
		}
		/**
		 * Restore user session after inactivity
		 * @param string $user
		 * @param string $pass
		 */
		public function restoreUser($pass,$user)
		{
			try{
				if(empty($pass) or empty($user))
					throw new \Exception("Fields cannot be empty!");
				$connection = $this->dbconnectmaster();
				$pass = mysqli_real_escape_string($connection,$pass);
				$user = json_decode($user, true);
				$firstname = $user['FirstName'];
				$lastname = $user['LastName'];
				$useridq = "SELECT * FROM users WHERE FirstName='%s' AND LastName='%s'";
				$useridq = sprintf($useridq,$firstname,$lastname);
				$result = mysqli_query($connection,$useridq);
				if($result->num_rows == 0){
					$this->dbclosemaster($connection);
					throw new \Exception("There is a problem with your username , try clearing cookies and log in again");
				}
				$row = mysqli_fetch_assoc($result);
				$passwordverified = password_verify($pass, $row['Password']);
				if($passwordverified == false)
					{
						$this->dbclosemaster($connection);
						throw new \Exception("Password does not match!");
					}
					else
					{
						$this->dbclosemaster($connection);
						$callback = new Callback();
						session_start();
						$_SESSION['loggedIN'] = $row['UID'];
						$ncs_user = ["FirstName" => $row['FirstName'],
						"LastName" => $row['LastName'],"UID" => $row['UID']];
						$ncs_user = json_encode($ncs_user);
						setcookie("NCS_USER", $ncs_user , time() + (86400 * 30), "/");
						return $callback->SendSuccessOnMainPageWithRedirect("Re-Authentication successfully!","dashboard");
					}
				}catch(\Exception $ex)
				{
					$callback = new Callback();
					return $callback->SendErrorToast($ex->getMessage());
				}
		}
		/**
		 * Send an email using php mail server
		 * Return true on success , false on failure
		 * @param string $to
		 * @param string $fname
		 * @param string $lname
		 * @param string $token
		 * @param string $uid
		 * @return bool;
		 */
		private function SendTokenEmail($to, $fname , $lname, $token, $uid)
		{
			$subject = "Welcome to Rosance - Activation token";
			$message = '<!DOCTYPE html>';
			$message .= '<html xmlns="http://www.w3.org/1999/xhtml"><head>';
			$message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			$message .= '<title>Activation token from Rosance</title>';
			$message .= '<meta name="viewport" content="width=device-width,';
			$message .= 'initial-scale=1.0"/></head>';
			$message .= '<body style="margin: 0; padding: 0;"> ';
			$message .= '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
			$message .= '<tr><td style="padding: 10px 0 30px 0;">';
			$message .= '<table align="center" border="0" cellpadding="0" cellspacing="0"';
			$message .= ' width="600 >';
			$message .= '<tr><td align="center" style="padding: 40px 0 30px 0;">';
			$message .= '<img src="https://console.rosance.com/images/logo-big.png"';
			$message .= 'width="65%" alt= "Rosance">';
			$message .=	'</td></tr><tr><td bgcolor="#ffffff" ';
			$message .= 'style="padding: 40px 30px 40px 30px;">';
			$message .= '<table border="0" cellpadding="0" cellspacing="0"><tr>';
			$message .= '<td style="color: #153643;font-size: 24px;"><b>Welcome to Rosance,<br/>';
			$message .= $fname." ".$lname.'!</b>';
			$message .= '</td></tr><tr>';
			$message .= '<td style="padding: 20px 0 30px 0; color: #153643; font-size: 16px;">';
			$message .= 'First of all we would like to send you a warm welcome.';
			$message .= 'To finish your account creation please access the following link';
			$message .= ': <br><a href="';
			$message .= 'https://console.rosance.com/registration?token=';
			$message .= $token.'&uid='.$uid.'">';
			$message .= 'Click to activate account</a>';
			$message .= '</td></tr></table></td></tr><tr>';
			$message .= '<td bgcolor="#ee4c50" style="padding: 30px 30px 30px 30px;">';
			$message .= '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
			$message .= '<tr><td style="color: #ffffff; font-size: 14px;" width="75%">';
			$message .= '&reg; Rosance Inc , Getting started is now easy!<br/>';
			$message .= 'This email was sent automatically due to a registration on ';
			$message .= '<a href="https://rosance.com">Rosance</a>';
			$message .= 'If you did not register please ignore this email';
			$message .= '</td><td align="right" width="25%">';
			$message .= '<table border="0" cellpadding="0" cellspacing="0"><tr>';
			$message .= '<td style="font-family: Arial, sans-serif; ';
			$message .= 'font-size: 12px; font-weight: bold;">';
			$message .= '<a href="http://www.twitter.com/rosance4" style="color: #ffffff;">';
			$message .= '<img src="https://console.rosance.com/images/tw.gif"';
			$message .= 'width="38" height="38"/>';
			$message .= '</a></td><td style="font-size: 0;';
			$message .= 'line-height: 0;" width="20">&nbsp;</td>';
			$message .= '<td style="font-size: 12px; font-weight: bold;">';
			$message .=	'<a href="http://www.facebook.com/rosance4" style="color: #ffffff;">';
			$message .= '<img src="https://console.rosance.com/images/fb.gif"';
			$message .= 'width="38" height="38" style="display: block;" border="0" />';
			$message .= '</a></td></tr></table></td></tr>';
			$message .= '</table></td></tr></table></td></tr>';
			$message .= '</table></body></html>';
			
			$header = "From:noreply@rosance.com \r\n";
			$header .= "MIME-Version: 1.0 \r\n";
			$header .= "Content-type: text/html\r\n";


			$return = mail($to,$subject,$message, $header);
			if($return)
				return true;
			return false;
		}
		/**
		 * Resend the confirmation email to this email
		 * @param string $email
		 */
		public function ResendConfirmationEmail($email){
			try{
				if(empty($email))
					throw new \Exception("Email cannot be empty!");
				
				$connection = $this->dbconnectmaster();
				$email = mysqli_real_escape_string($connection,$email);

				$query = "SELECT * FROM users WHERE Email='".$email."'";
				$result = mysqli_query($connection,$query);
				$this->dbclosemaster($connection);

				if($result->num_rows == 0)
					throw new \Exception("Email was not found on our database, are you sure you registered first ?");
				while($row = mysqli_fetch_assoc($result))
				{
					$id = $row['UID'];
					$token = $row['Token'];
					$activated = $row['Activated'];
					$fname = $row['FirstName'];
					$lname = $row['LastName'];
					if($activated == 1)
						throw new \Exception("Account was activated before!");
					
					$mail = $this->SendTokenEmail(
							$email,
							$fname,
							$lname,
							$token,
							$id
						);
					if(!$mail)
						throw new \Exception("Mail could not be send, #Mailserver Error,  please contact rosance support!");

					$callback = new Callback();
					return $callback->SendSuccessOnMainPage("Email confirmation was successfully resent to ".$email);
				}

			}catch(\Exception $ex)
			{
				$callback = new Callback();
				return $callback->SendErrorToast($ex->getMessage());
			}
		}

		/**
		 * To be continued in further releases !!!
		 * Send an welcome Email to new users!!!
		 * Must get a nice template!
		 */
		private function SendWelcomeEmail()
		{

		}
		/**
		 * Activate user account. AKA email verify
		 * @param string $uid
		 * @param string $token
		 */
		public function ActivateAccount($uid, $token)
		{
			try{
			if(empty($uid) or empty($token))
				throw new \Exception("Fields cannot be empty!");
			
			$connection = $this->dbconnectmaster();
			$uid = mysqli_real_escape_string($connection,$uid);
			$token = mysqli_real_escape_string($connection,$token);
			$globals = $this->GetGlobals();
			$query = "SELECT * FROM users WHERE UID='".$uid."' AND Token ='".$token."'";
			$result = mysqli_query($connection , $query);
			if($result)
			{
				if($result->num_rows > 0)
				{
					$query1 = "UPDATE users SET Token='0', Activated='1' WHERE UID='".$uid."'";
					$result1 = mysqli_query($connection,$query1);
					if($result1)
					{
						$this->dbclosemaster($connection);
						//Create subdomain
						$row = mysqli_fetch_assoc($result);

						if($globals['TEST'] == false)
							$this->createSubdomain($globals['PREFIX'],$globals['PWD'],$row['Business_Name']);
							

						$this->createFolder($row['Business_Name'],"");

						if(!isset($_SESSION))
						    session_start();
						$_SESSION['loggedIN'] = $row['UID'];
						$ncs_user = ["FirstName" => $row['FirstName'],
									"LastName" => $row['LastName'],
									"UID" => $uid,
									"Provider" => "rosance"];
						$ncs_user = json_encode($ncs_user);
						setcookie("NCS_USER", $ncs_user , time() + (86400 * 30), "/");

						return "<script>
						setTimeout(function(){ showsuccess('Account successfully validated! Welcome to Rosance!',false,'dashboard'); }, 3000);
						</script>"; 

					}
					else
					{
						$this->dbclosemaster($connection);
						throw new \Exception("Something went wrong!");
					}
				}
				else
				{
					$this->dbclosemaster($connection);
					throw new \Exception("Account does not exist or has been allready activated");
				}
			}
			else
			{
				$this->dbclosemaster($connection);
				throw new \Exception("Something went wrong!");
			}
			}
			catch(\Exception $ex)
			{
				$callback = new Callback();
				return $callback->SendErrorOnMainPage($ex->getMessage());
			}
		}
		/**
		 * Create user folder inside clients area
		 * Returns true on success , false on failure
		 * @param string $bname
		 * @param string $prefix
		 * @return bool
		 */
		private function createFolder($bname,$prefix)
		{
			if(!is_dir($prefix.'clients/'.$bname.'/'))
			{
				$dir = mkdir($prefix.'clients/'.$bname.'/', 0777, true);
				if($dir)
					return true;
				else 
					return false;
			}
		}
		/**
		 * Create a subdomain using http header request to CPanel API
		 * using port localhost:2082
		 * @param string $cPanelUser
		 * @param string $cPanelPass
		 * @param string $dname
		 */
		public function createSubdomain($cPanelUser,$cPanelPass,$dname) {
            $buildRequest = "/json-api/cpanel?cpanel_jsonapi_user=user&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=SubDomain&cpanel_jsonapi_func=addsubdomain&domain=".$dname."&rootdomain=rosance.com&dir=%2Fconsole%2Fclients%2F".$dname."&disallowdot=1";
    
			$openSocket = fsockopen('localhost',2082);
			if(!$openSocket) {
				return "Socket error";
				exit();
			}
		
			$authString = $cPanelUser . ":" . $cPanelPass;
			$authPass = base64_encode($authString);
			$buildHeaders  = "GET " . $buildRequest ."\r\n";
			$buildHeaders .= "HTTP/1.0\r\n";
			$buildHeaders .= "Host:localhost\r\n";
			$buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
			$buildHeaders .= "\r\n";
		
			fputs($openSocket, $buildHeaders);
			while(!feof($openSocket)) {
				fgets($openSocket,128);
			}
			fclose($openSocket);
		}
		/**
		 * Reset users password
		 * Require re-authentication on success
		 * @param User $user
		 * @param string $old_pass
		 * @param string $new_pass
		 * @param string $new_pass2
		 */
		public function resetPassword(User $user, $old_pass,$new_pass,$new_pass2)
		{
			try{
				if(empty($old_pass) or empty($new_pass) or empty($new_pass2))
					throw new \Exception("Fields cannot be empty!");
				if($new_pass != $new_pass2)
					throw new \Exception("Passwords does not match");
				if($old_pass == $new_pass)
					throw new \Exception("New pass is the same as old pass");

				//check the pass in the database

				$connection = $this->dbconnectmaster();
				$old_pass = mysqli_real_escape_string($connection , $old_pass);
				$new_pass = mysqli_real_escape_string($connection , $new_pass);

				$query1 = "SELECT * FROM users WHERE UID='".$user->id."'";
				$result1 = mysqli_query($connection,$query1);
				if($result1)
				{
					if($result1->num_rows != 0)
					{
						$row = mysqli_fetch_assoc($result1);
						$passwordverified = password_verify($old_pass,$row['Password']);
						if(!$passwordverified)
							throw new \Exception("Old password is incorrect!");
						//old password is ok , lets make new password
						$new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
						$query2 = "UPDATE users SET Password='".$new_pass."' WHERE UID='".$user->id."'";
						$result2 = mysqli_query($connection,$query2);
						if($query2)
						{
							$this->dbclosemaster($connection);
							$callback = new Callback();
							session_destroy();
							return $callback->SendSuccessOnMainPageWithRedirect("Password successfully changed , Please login again!","registration");
						}
						else
							throw new \Exception("Changing password failed ,please try again later");
					}
					else
						throw new \Exception("Username does not exists!");
				}
				else
					throw new \Exception("Database problem , please try again!");
			}
			catch(\Exception $ex)
			{
				$this->dbclosemaster($connection);
				$callback = new Callback();
				return $callback->SendErrorOnMainPage($ex->getMessage());
			}
		}
		/**
		 * Remove an user and all his data from the server
		 */
		public function deleteUser($userID)
		{
			try{
			//First we need to sanitize id
				if(empty($userID))
					throw new \Exception("User id came blank!");
				$connection = $this->dbconnectmaster();
				$userID = mysqli_real_escape_string($connection, $userID);

				// Fetch all projects this user have

				$query = "SELECT * from projects where Owner='".$userID."'";
				$result = mysqli_query($connection,$query);
				if(!$result)
					throw new \Exception("Something went wrong!");

				$data = new Data();

				if($result->num_rows > 0)
				{
					//User has projects , lets delete em all
					$user = $data->GetUser($userID);
					while($row = mysqli_fetch_assoc($result))
					{
						$deletionResult = Data::RemoveProjectsAsync($user, $row['ProjectID'],$connection);
						if(!$deletionResult)
							throw new \Exception($deletionResult);
					}
				}
				//Remove also the business name folder inside clients

				if(file_exists("../clients/".$user->Business_Name."/"))
					$data->recurse_delete("../clients/".$user->Business_Name."/");
				
				//Now delete this user
				$queryU = "DELETE FROM users WHERE UID='".$userID."'";
				$resultU = mysqli_query($connection, $queryU);
				$this->dbclosemaster($connection);
				if(!$resultU)
					throw new \Exception("Something went wrong #2!");
				
				session_destroy();
				setcookie("NCS_USER" , '' , time()-3600 , '/' , '' , 0 );
				unset( $_COOKIE["NCS_USER"] );
				setcookie("NCS_PROJECT" , '' , time()-3600 , '/' , '' , 0 );
				unset( $_COOKIE["NCS_PROJECT"] );
				
				$callback = new Callback();
				return $callback->SendSuccessOnMainPageWithRedirect("We are sorry to see you go ... , but your request has been fullfilled!","/registration");
			
			}catch(\Exception $ex){
				$callback = new Callback();
				return $callback->SendErrorToast($ex->getMessage());
			}
		}
		/**
		 * Returns an array of global settings from Globals.ini.php
		 */
		public function GetGlobals()
		{
			return parse_ini_file("Globals.ini.php");
		}
	}
}
?>