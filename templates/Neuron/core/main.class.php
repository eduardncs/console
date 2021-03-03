<?php
require_once("ssk.req.class.php");
require_once("database.class.php");
class main
{
	protected $published = false;

	public function setpagestate($state)
	{
		$this->published = $state;
	}
	public function getpagestate()
	{
		return $this->published;
	}
	public function getmenu()
	{
		$menu = file_get_contents("core/menu.json");
		return json_decode($menu, true);
	}
	public function getinfo()
	{
		$file = file_get_contents("core/widgets/info.json");
		return json_decode($file, true);
	}
	public function getWidgetJSON($widget)
	{
		$file = file_get_contents("core/widgets/".$widget.".json");
		return json_decode($file,true);
	}
	public function getDatabaseCredentials($defaultPath="core/D_C.json")
	{
		$ssk = new ssk();
		$ssk = $ssk->GetSSK();
		$encData = file_get_contents($defaultPath);
		$rawData = json_decode($encData,true);
		$trimmedData = [
			"server" => $this->decrypt($rawData[0]),
			"username" => $this->decrypt($rawData[1]),
			"password" => $this->decrypt($rawData[2]),
			"database" => $this->decrypt($rawData[3]),
		];
		return $trimmedData;
	}
	private function decrypt($string)
	{
		$ssk = new ssk();
		$ssk = $ssk->GetSSK();
		return openssl_decrypt ($string, "AES-128-CTR",
        $ssk, 0, substr($ssk,0,16));
	}

	function GetIP(){
		if ( !empty($_SERVER['HTTP_CLIENT_IP']) ) {
		 // Check IP from internet.
		 $ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
		 // Check IP is passed from proxy.
		 $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		 // Get IP address from remote address.
		 $ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	   }

	public function ShowSuccess($message, $redirectpage=null){
		if($redirectpage != null)
			return "<script>showsuccess('".$message."',false,'".$redirectpage."')</script>";
		return "<script>showsuccess('".$message."')</script>";
	}
	public function ShowError($message){
		return "<script>showerror('".$message."')</script>";
	}

	public function SendMessage($data)
	{
		if(empty($data))
			return;
		$credentials = $this->getDatabaseCredentials("D_C.json");
    	$database = new Database($credentials);
		$connection = $database->dbconnectmaster();

		$data['name'] = mysqli_real_escape_string($connection,$data['name']);
		$data['email'] = mysqli_real_escape_string($connection,$data['email']);
		$data['subject'] = mysqli_real_escape_string($connection,$data['subject']);
		$messageid = uniqid (rand (),false);
		$query = "INSERT INTO Messages(Date,Name,Email,Subject,Message,Ip) VALUES ('".$data['date']."','".$data['name']."','".$data['email']."','".$data['subject']."','".$messageid."','".$data['ip']."')";
		$result = mysqli_query($connection,$query);
		if($result)
		{
			$database->dbclosemaster($connection);
			//Now put the data into a .txt file
			$this->PlaceMessage(
				[
					"Id"=> $messageid,
					"Message"=> $data['message']
				]
			);
			return $this->ShowSuccess("Message successfully sent!");
		}
		$database->dbclosemaster($connection);
		return $this->ShowError("Something went wrong!");
	}

	private function PlaceMessage($message)
	{
		if(!is_dir("../Mailbox/"))
			mkdir("../Mailbox/");
		$file = fopen('../Mailbox/'.$message['Id'].".txt","w");
		if($message['Message'] != null)
			fwrite($file, $message['Message']);
		fclose($file);
	}
}
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']))
{
	$main = new Main();
	//Make the data

	$data = [
		"date" => date("Y-m-d H:i"),
 		"name" => strip_tags($_POST['name']),
		"email" => strip_tags($_POST['email']),
		"subject" => strip_tags($_POST['subject']),
		"message" => strip_tags($_POST['message']),
		"ip" => $main->GetIP()
	];
	echo $main->SendMessage($data);
}
?>
