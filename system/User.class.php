<?php
namespace Rosance
{
	use Rosance\Database;
	use Rosance\Callback;

	class User
	{
		public $id;
		public $First_Name;
		public $Last_Name;
		public $Email;
		private $Password;
		public $Premium;
		public $Business_Name;
		public $Profile_Pic;
		public $Provider;

		public function __construct($id,$fname,$lname,$email,$password,$premium,$bname,$profile_pic,$provider){
			$this->id = $id;
			$this->First_Name = $fname;
			$this->Last_Name = $lname;
			$this->Email = $email;
			$this->Password = $password;
			$this->Premium = $premium;
			$this->Business_Name = $bname;
			$this->Profile_Pic = $profile_pic;
			$this->Provider = $provider;
		}

		public function removeUser(){

		}

		public function upgradeUser(){

		}

		public function downgradeUser(){

		}
		
		public function Update($profile_pic, $fname, $lname){
			$database = new Database();
			$connection = $database->dbconnectmaster(); 
			$uid = $this->id;

			$profile_pic = mysqli_real_escape_string($connection ,$profile_pic);
			$fname = mysqli_real_escape_string($connection, $fname);
			$lname = mysqli_real_escape_string($connection, $lname);
		
			$query = "UPDATE users SET Profile_Pic='".$profile_pic."' , FirstName='".$fname."', LastName='".$lname."' WHERE UID='".$uid."'";
			$result = mysqli_query($connection, $query);
			$callback = new Callback();
			$database->dbclosemaster($connection);
			if($result)
			{
				$ncs_user = ["FirstName" => $fname,
				"LastName" => $lname];
				   $ncs_user = json_encode($ncs_user);
				setcookie("NCS_USER", $ncs_user , time() + (86400 * 30), "/");
				return $callback->SendSuccessToast("Settings updated successfully!");
			}
			else
			{
				return $callback->SendErrorToast("Something went wrong, please try again later!");
			}
		}
	}
}
?>
