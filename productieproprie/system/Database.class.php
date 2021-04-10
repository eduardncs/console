<?php
namespace Rosance
{
	/**
	* This class is used for Database and Sign up/in related tasks
	* Login / Register uses a hashing algoritm for passwords
	* Copyright (C) 2020-2021 by Eduard Neacsu
	* Created for Rosance, https://rosance.com
	*/
	class Database
	{
		public $server = "localhost";
		protected $username = "root";
		protected $password = "";
		protected $database = "productieproprie";

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
							if($row['Token'] != "")
							{
								$this->dbclosemaster($connection);
								throw new \Exception("Please check your mail inbox and activate your account!");
							}else{
							$this->dbclosemaster($connection);
							$ncs_user = [
										"FirstName" => $row['FirstName'],
										"LastName" => $row['LastName'],
										"Email" => $row['Email'],
										"UID" => $row['UID'],
										"Provider" => "productieproprie"];
							$ncs_user = json_encode($ncs_user);
							setcookie("NCS_USER", $ncs_user , time() + (86400 * 30), "/");
							return ["success" => "Authentication successfully!","dashboard"];
							}
					}
				}
			}
			catch(\Exception $ex)
			{
				return ["error" => $ex->getMessage()];
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
		 * @param string $provider // Defaulted to "productieproprie"
		 * @param string $id // Defaulted to null
		 * @param string $picture // Defaulted to "images/default.png"
		 * @param bool $email_verified // Defaulted to false
		 */
		public function registerUser($firstname, $lastname , $password, $password2, $email, $provider = "productieproprie", $id = null, $picture = "images/default.png", $email_verified = false)
		{
		try
		{
			if(empty($firstname) || empty($lastname) || empty($password) || empty($password2) || empty($email))
			{
				throw new \Exception("Va rugam completati toate campurile");
			}
			if($password != $password2 && $provider == "rosance" )
			{
				throw new \Exception("Parolele nu coincid");
			}
			!empty($picture) ? $picture = $picture : $picture = "images/default.png";
			
			$connection = $this->dbconnectmaster();
			$firstname = mysqli_real_escape_string($connection ,$firstname);
			$lastname = mysqli_real_escape_string($connection ,$lastname);
			$email = mysqli_real_escape_string($connection, $email);
			$picture = mysqli_real_escape_string($connection, $picture);
			$email_verified = mysqli_real_escape_string($connection, $email_verified);
			$query = "SELECT * FROM users WHERE Email='".$email."'";
			$result = mysqli_query($connection, $query);
			if($result->num_rows != 0)
			{
				$this->dbclosemaster($connection);
				throw new \Exception("Email deja inregistrat");
			}
				//insert data into the database
			if($provider == "productieproprie")
			{
				$uid = uniqid (rand (),false);
				$password = password_hash($password, PASSWORD_DEFAULT);
			}else{
				$uid = $id;
				$password = password_hash(substr($id , 3,15), PASSWORD_DEFAULT);
			}
			$email_verified ? $token="0" : $token = uniqid(rand(),false);
			$date = date("Y-m-d H:i");
			
			$query = "INSERT INTO users (UID,Email,Token,FirstName,LastName,Password,Date, Provider, ProfilePic) VALUES ('".$uid."','".$email."','".$token."','".$firstname."','".$lastname."','".$password."','".$date."','".$provider."','".$picture."')";
			
			$result = mysqli_query($connection,$query);
			if(!$result)
			{
				$this->dbclosemaster($connection);
				throw new \Exception("Ceva nu este in regula, va rugam incercati mai tarziu ...");
			}
				$this->dbclosemaster($connection);
				if(!$email_verified && $provider == "productieproprie")
				{
					/** Email neverificat si de la productieproprie */
					$mail = $this->SendTokenEmail($email,$firstname,$lastname, $token, $uid);
					if($mail)
					{
						return ["success" => "Cont creeat cu success , un link de activare a fost trimis la aceasta adresa de email !"];
					}else {
						throw new \Exception("Nu s-a putut trimite mail , va rugam incercati mai tarziu ...");
					}
				}elseif($email_verified && $provider =="google") {
					/** Email verificat de la google */
					return "";

				}elseif(!$email_verified && $provider != "productieproprie")
				{
					/** Email neverificat si de la facebook  */
					$mail = $this->SendTokenEmail($email,$firstname,$lastname, $token, $uid);
					if($mail)
					{
						return "";
					}else {
						throw new \Exception("Nu s-a putut trimite mail , va rugam incercati mai tarziu ...");
					}
				}
			}catch(\Exception $ex)
			{
				return ["error" => $ex->getMessage()];
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
			$subject = "Token activare cont - Productie Proprie";
			$message = '<!DOCTYPE html>';
			$message .= '<html xmlns="http://www.w3.org/1999/xhtml"><head>';
			$message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			$message .= '<title>Activare cont pe ProductieProprie.ro</title>';
			$message .= '<meta name="viewport" content="width=device-width,';
			$message .= 'initial-scale=1.0"/></head>';
			$message .= '<body style="margin: 0; padding: 0;"> ';
			$message .= '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
			$message .= '<tr><td style="padding: 10px 0 30px 0;">';
			$message .= '<table align="center" border="0" cellpadding="0" cellspacing="0"';
			$message .= ' width="600 >';
			$message .= '<tr><td align="center" style="padding: 40px 0 30px 0;">';
			$message .= '<img src="https://productieproprie.ro/images/logo-big.png"';
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
			$message .= 'https://productieproprie.ro/register?token=';
			$message .= $token.'&uid='.$uid.'">';
			$message .= 'Click to activate account</a>';
			$message .= '</td></tr></table></td></tr><tr>';
			$message .= '<td bgcolor="#ee4c50" style="padding: 30px 30px 30px 30px;">';
			$message .= '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
			$message .= '<tr><td style="color: #ffffff; font-size: 14px;" width="75%">';
			$message .= '&reg; Rosance Inc , Getting started is now easy!<br/>';
			$message .= 'This email was sent automatically due to a registration on ';
			$message .= '<a href="https://productieproprie.ro">Rosance</a>';
			$message .= 'If you did not register please ignore this email';
			$message .= '</td><td align="right" width="25%">';
			$message .= '<table border="0" cellpadding="0" cellspacing="0"><tr>';
			$message .= '<td style="font-family: Arial, sans-serif; ';
			$message .= 'font-size: 12px; font-weight: bold;">';
			$message .= '<a href="http://www.twitter.com/productiepropriero" style="color: #ffffff;">';
			$message .= '<img src="https://productieproprie.ro/images/tw.gif"';
			$message .= 'width="38" height="38"/>';
			$message .= '</a></td><td style="font-size: 0;';
			$message .= 'line-height: 0;" width="20">&nbsp;</td>';
			$message .= '<td style="font-size: 12px; font-weight: bold;">';
			$message .=	'<a href="http://www.facebook.com/productiepropriero" style="color: #ffffff;">';
			$message .= '<img src="https://productieproprie.ro/images/fb.gif"';
			$message .= 'width="38" height="38" style="display: block;" border="0" />';
			$message .= '</a></td></tr></table></td></tr>';
			$message .= '</table></td></tr></table></td></tr>';
			$message .= '</table></body></html>';
			
			$header = "From: noreply@productieproprie.ro \r\n";
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

			$query = "SELECT * FROM users WHERE UID='".$uid."' AND Token ='".$token."'";
			$result = mysqli_query($connection , $query);
			if($result)
			{
				if($result->num_rows > 0)
				{
					$query1 = "UPDATE users SET Token='' WHERE UID='".$uid."'";
					$result1 = mysqli_query($connection,$query1);
					if($result1)
					{
						$this->dbclosemaster($connection);
						//Create subdomain
						$row = mysqli_fetch_assoc($result);

						$ncs_user = [
									"FirstName" => $row['FirstName'],
									"LastName" => $row['LastName'],
									"Email" => $row['Email'],
									"UID" => $uid,
									"Provider" => "productieproprie"
								];
						$ncs_user = json_encode($ncs_user);
						setcookie("NCS_USER", $ncs_user , time() + (86400 * 30), "/");

						return ["success"=> "Cont activat cu success, bine ati venit!"]; 

					}
					else
					{
						$this->dbclosemaster($connection);
						throw new \Exception("Ceva nu este in regula, va rugam incercati mai tarziu ...");
					}
				}
				else
				{
					$this->dbclosemaster($connection);
					throw new \Exception("Acest cont nu exista sau a fost deja activat");
				}
			}
			else
			{
				$this->dbclosemaster($connection);
				throw new \Exception("Ceva nu este in regula, va rugam incercati mai tarziu ...");
			}
			}
			catch(\Exception $ex)
			{
				return ["error" => $ex->getMessage()];
			}
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
		 * @return array $categories
		 */
		public function fetchCategories(){
			$connection = $this->dbconnectmaster();
			$query = "SELECT * FROM categories";
			$result = mysqli_query($connection, $query);
			$this->dbclosemaster($connection);
			$categories = [];
			while($row = mysqli_fetch_object($result))
			{
				array_push($categories, $row);
			}
			return $categories;
		}
		/**
		 * @param string $mainCategory
		 * @return array $categories
		 */
		public function fetchSubCategories($mainCategory){
				try{
					if(empty($mainCategory))
						return null;
					$connection = $this->dbconnectmaster();
					$mainCategory = mysqli_real_escape_string($connection, $mainCategory);
					$query = "SELECT * FROM subcategories where Parent_Name='".$mainCategory."'";
					$result = mysqli_query($connection, $query);
					$this->dbclosemaster($connection);
					$categories = [];
					while($row = mysqli_fetch_object($result))
					{
						array_push($categories, $row);
					}
					return $categories;
			}catch(\Exception $ex)
			{
				return null;
			}
		}
	}
}
?>