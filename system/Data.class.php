<?php
namespace Rosance
{
	require_once("rosance_autoload.php");
	use Rosance\Database;
	use Rosance\Callback;
	use phpQuery;
	class Data extends Database
	{
		/**
		 * Get an array of global variables to use across all scripts
		 */
		public function GetGlobals()
		{
			return parse_ini_file("Globals.ini.php");
		}

		/**
		 * Takes a string from the session that represent user id
		 * Returns an instance of type User
		 * @param string $userID
		 */
		public function GetUser($userID)
		{
			if(!empty($userID))
			{
				$connection = $this->dbconnectmaster();
				$userID = mysqli_real_escape_string($connection, $userID);
				$query = "SELECT * from users where UID='%s'";
				$query = sprintf($query, $userID);
				$result = mysqli_query($connection,$query);
				$row = mysqli_fetch_assoc($result);
				$this->dbclosemaster($connection);
				return new User($row['UID'], $row['FirstName'], $row['LastName'], $row['Email'], $row['Password'], $row['Premium'],$row['Business_Name'],$row['Profile_Pic'],$row['Provider']);
			}
			return null;
		}

		public function CreateMenu($project)
		{
			$uri = $this->UpdateURI();
			$menu = json_decode(file_get_contents("system/lib/menu.json"),true);
			$return_value = '
			<nav class="mt-2">
			<ul class="nav nav-sidebar flex-column '.$this->GetLayout("system/Layout.json","inside-sidebar").'" data-widget="treeview" role="menu" data-accordion="false">
			';
			foreach($menu as $link)
			{
				if($link['Access'] == "Basic")
				{
					$return_value .= sprintf('<li class="nav-item has-treeview %s">
					<a href="javascript:void(0)" class="nav-link %s">
					<i class="nav-icon %s"></i>
					<p>
						%s
						<i class="right fas fa-angle-left"></i>
					</p>
					</a>
					%s
				</li>',
					$this->IsActiveParent($uri,lcfirst($link['Text']),"TREEVIEW"),
					$this->IsActiveParent($uri,lcfirst($link['Text']),"DEFAULT"),
					$link['Icon'],
					$link['Text'],
					$this->getChildrenMenu($link,$link['Text'])
					);
				}
				elseif($link['Access'] == $project->project_type){
				//Build that up shit
				$return_value .= sprintf('<li class="nav-item has-treeview %s">
				<a href="javascript:void(0)" class="nav-link %s">
				<i class="nav-icon %s"></i>
				<p>
					%s
					<i class="right fas fa-angle-left"></i>
				</p>
				</a>
				%s
			</li>',
				$this->IsActiveParent($uri,lcfirst($link['Text']),"TREEVIEW"),
				$this->IsActiveParent($uri,lcfirst($link['Text']),"DEFAULT"),
				$link['Icon'],
				$link['Text'],
				$this->getChildrenMenu($link,$link['Text'])
				);
				}
			}
			return $return_value."</ul></nav>";
		}

		private function IsActiveParent($uri, $comparison , $mode)
		{
			if(strpos($uri, $comparison) == true)
			{
				if($mode == "DEFAULT")
					return "active";
				else if($mode == "TREEVIEW")
					return "menu-open";

			}
			return null;
		}

		private function IsActiveChildren($uri, $comparison)
		{
			if(strcasecmp("/".$comparison, $uri) == 0)
			{
				return "active";
			}
			return null;
		}

		private function getChildrenMenu($link,$parent)
		{
			$uri = $this->UpdateURI();
			$return_value = '<ul class="nav nav-treeview">';
			foreach($link['Children'] as $chield)
			{
				$return_value .= sprintf('<li class="nav-item">
				<a href="%s" target="%s" class="nav-link %s">
				<i class="far fa-circle nav-icon"></i>
				<p>%s</p>
				</a>
			</li>',
			$chield['Href'],
			$chield['Target'],
			$this->IsActiveChildren($uri,$parent."/".$chield['Text']),
			$chield['Text']
			);
			};
			return $return_value."</ul>";
		}

		public function UpdateURI()
		{
			$globals = self::GetGlobals();
			return str_replace($globals['URI'],"",$_SERVER['REQUEST_URI']);
		}

		public function UpdateURIBase($uri)
		{
			$globals = self::GetGlobals();
			return str_replace($uri,"",$_SERVER['REQUEST_URI']);
		}

		public function GetLayout($path, $dest)
		{
			$layout = json_decode(file_get_contents($path),true);
			$return = "" ;
			for($i=0; $i<count($layout[0][$dest]);$i++)
			{
				$keys = array_keys($layout[0][$dest][$i],true);
				
				if($layout[0][$dest][$i][$keys[0]] == "true")
					$return .= key($layout[0][$dest][$i])." ";
				elseif($layout[0][$dest][$i][$keys[0]] != "true" && $layout[0][$dest][$i][$keys[0]] != "false")
				{
					$return .= key($layout[0][$dest][$i])."-".$layout[0][$dest][$i][$keys[0]]." ";
				}
			}

			return $return;
		}


		/**
		 * Get projects asociated with the userid
		 * Type represents rank of the user to the projects
		 * Ex: $type = "Owner" or $type = "Admin"
		 * Owners have full control on the project 
		 * Admins cannot delete the project nor make any sensitive changes
		 * @param string $uid
		 * @param string $type
		 * @return array $tosend
		 * Returns an associative array
		 */
		public function GetProjects($uid, $type)
		{
			try
			{
				$connection = $this->dbconnectmaster();
				$uid = mysqli_real_escape_string($connection, $uid);
				switch($type)
				{
					case "Owner":
						$query = "SELECT * from projects where Owner='".$uid."' ORDER BY Date DESC";
					break;
					case "Admin":
						$query = "SELECT * from projects where Admin='".$uid."' ORDER BY Date DESC";
					break;
				}
				$result = mysqli_query($connection, $query);
				$tosend = [];
				for($i=0; $i< mysqli_num_rows($result); $i++)
				{
					$proj = mysqli_fetch_assoc($result);
					$tosend[] = new Project($proj['ProjectID']);
				}
				$this->dbclosemaster($connection);
				return $tosend;
			}
			catch(\Exception $ex)
			{
				return $ex;
			}
		}
		/**
		 * Returns data from a json file as an associative array \n
		 * File is located into /core/widgets folder width user's project
		 * @param User $user
		 * @param Project $project
		 * @param string $widgetName
		 * @param string $defaultpath Defaulted to "../"
		 * @return array jsondata;
		 */
		public function getWidgetJSON(User $user,Project $project, $widgetName, $defaultpath = "../")
		{
			$file = $defaultpath."clients/".$user->Business_Name.'/'.$project->project_name_short."/core/widgets/".$widgetName.".json";
			$jsondata = json_decode(file_get_contents($file),true);
			return $jsondata;
		}
		/**
		 * Update a json file inside User's project
		 * Returns either true on succcess or false on failure
		 * @param User $user
		 * @param Project $project
		 * @param string $widgetName
		 * @param string $jsonString
		 * @param string $defaultpath Defaulted to "../"
		 * @return bool $return;
		 */
		public function PutWidgetJSON(User $user,Project $project, $widgetName, $jsonString, $defaultpath = "../")
		{
			$file = $defaultpath."clients/".$user->Business_Name.'/'.$project->project_name_short."/core/widgets/".$widgetName.".json";
			$result = file_put_contents($file,$jsonString);
			if($result) return true;
			return false;
		}
		
		public function CheckUser($UID, $project)
		{
			$connection = $this->dbconnectmaster();
			$UID = mysqli_real_escape_string($connection, $UID);
			$project = mysqli_real_escape_string($connection, $project);
			$query = "SELECT * FROM projects WHERE Owner='".$UID."' AND ProjectID='".$project."'";
			$result = mysqli_query($connection, $query);
			$this->dbclosemaster($connection);

			if($result->num_rows > 0)
				return true;

			return false;
		}

		/**
		 * Copy all contents of a direcytory into another directory
		 * @param string $src
		 * @param string $dst
		 */
		private function recurse_copy($src,$dst)
		{
		$dir = opendir($src);
		@mkdir($dst);
		if(is_dir($src)){
			while(false !== ( $file = readdir($dir)) ) {
				if (( $file != '.' ) && ( $file != '..' )) {
					is_dir($src . '/' . $file) ? $this->recurse_copy($src . '/' . $file,$dst . '/' . $file) : copy($src . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
		}
		/**
		 * Delete all contents of a directory using RecuriveIterator
		 * All files are permanently deleted from the disks.
		 * Use this function with extreme caution
		 * I recomend var_dumping argument before executing function
		 * @param string $dir
		 * @return bool
		 */
		public function recurse_delete($dir)
		{
			if($dir == null)
				return;
			$di = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
			$ri = new \RecursiveIteratorIterator($di, \RecursiveIteratorIterator::CHILD_FIRST);
			foreach ( $ri as $file )
				$file->isDir() ?  rmdir($file) : unlink($file);
			
			return true;
		}
		/**
		 * Create a SSK class that will be used to store the users SSK
		 * @param User $user
		 * @param string $project
		 * @param string $ssk
		 */
		private function CreateSSK(User $user,$project,$ssk)
		{
			try
			{
				$project = str_replace(' ', '', $project);
				if(!is_dir("../clients/".$user->Business_Name.'/'.$project."/core/"))
					mkdir('../clients/'.$user->Business_Name.'/'.$project.'/core/', 0777, true);
				$mysettings = fopen('../clients/'.$user->Business_Name.'/'.$project.'/core/ssk.req.class.php', "w");
				fwrite($mysettings,"<?php class ssk{ protected \$ssk = '".$ssk."'; public function GetSSK(){ return \$this->ssk; }}  ?>");
				fclose($mysettings);
				return true;
			}
			catch(\Exception $ex)
			{
				return $ex;
			}
		}
		/**
		 * Create database credentials for an user all credentials are encrypted
		 * H stand for Host, U stand for Username, P stand for Password and D stand for Database
		 * Ex: $D_H is D_Host = "localhost"
		 * @param User $user
		 * @param string $project
		 * @param string $ssk
		 * @param string $D_H
		 * @param string $D_U
		 * @param string $D_P
		 * @param string $D_D
		 */
		private function CreateDatabaseCredentials(User $user,$project,$ssk, $D_H, $D_U, $D_P, $D_D)
		{
			try
			{
			$project = str_replace(' ', '', $project);
			$myfile = fopen('../clients/'.$user->Business_Name.'/'.$project.'/core/D_C.json', "w");
			fclose($myfile);
			$jsonfile = '../clients/'.$user->Business_Name.'/'.$project.'/core/D_C.json';
			$contents = [
										0 => $this->encrypt($D_H,$ssk),
										1 => $this->encrypt($D_U,$ssk),
										2 => $this->encrypt($D_P,$ssk),
										3 => $this->encrypt($D_D,$ssk)
								];
			$contents = json_encode($contents);
			file_put_contents($jsonfile, $contents);
			}
			catch(\Exception $ex)
			{
				return $ex;
			}
		}

		/**
		 * Encrypt a string using a specific ssk
		 * It uses openSSL AES-128-CTR encryption
		 * Returns the encrypted string
		 * @param string $string
		 * @param string $ssk
		 * @return string
		 */
		private function encrypt($string,$ssk)
		{
			$encryption_iv = substr($ssk,0,16);
			return openssl_encrypt($string, "AES-128-CTR", $ssk, 0, $encryption_iv);
		}

		/**
		 * Create user project
		 * It's the foundation of the entire Rosance arhitecture
		 * Make sure to change TEST mode into Globals.ini.php to true or false regarding on the development phase of the source
		 * @param string $userid
		 * @param string $project
		 * @param string $template
		 * @param string $type
		 */
		public function CreateProject($userid, $project, $template, $type)
		{
			try{
				if(empty($userid) or empty($project) or empty($template) or empty($type))
				{
					$callback = new Callback();
					throw new \Exception("Please fill up all fields !");
				}
				$connection = $this->dbconnectmaster();
				$userid = mysqli_real_escape_string($connection, $userid);
				$project = mysqli_real_escape_string($connection , str_replace(" ","",$project));
				$type = mysqli_real_escape_string($connection, $type);
				$template = ucfirst(mysqli_real_escape_string($connection,$template));
				$date = date("Y-m-d H:i");
				//Checking if user is premium or not , max number or projects for non premium is 2
				//Get the user now to see if he is a Premium
				$user = $this->GetUser($userid);
				if($user->Premium == "false")
				{
					$Pquery = "SELECT * FROM projects where Owner = '".$user->id."'";
					$Presult = mysqli_query($connection,$Pquery);
					if($Presult->num_rows >= 2)
					{
							$this->dbclosemaster($connection);
							$callback = new Callback();
							throw new \Exception("You have reached your maximum number of projects. Consider upgrading your account and try again.");
					}
				}
				$query = "SELECT * FROM projects where Project='".$project."'";
				$result = mysqli_query($connection, $query);
				if($result->num_rows > 0)
				{
					$this->dbclosemaster($connection);
					$callback = new Callback();
					throw new \Exception("Project name unavailable, please try another one!");
				}
				else
				{
					try
					{
						$globals = self::GetGlobals();
						$uniqueid = uniqid (rand (),false);
						$usr = $globals['PREFIX']."_".substr($uniqueid,0,7);
						$pass = substr($uniqueid,0,10);
						$dbnamegonnabe =  $globals['PREFIX']."_".$uniqueid;
						$query = "INSERT INTO projects (Owner,Project,ProjectType,ProjectID,Date,Template) VALUES ('".$userid."','".$project."','".$type."','".$uniqueid."','".$date."','".$template."')";
						$result = mysqli_query($connection , $query);
						//Create a database with the name of : $globals['prefix']_projectid
						//For production!
						//For production use Cpanel API
						if($globals['TEST'] == false){
							$this->CPAPI($uniqueid,$usr);
						}else{
						//For testing
						//For testing use mariaDB
						$query1 = "CREATE DATABASE ".$dbnamegonnabe;
						$result1 = mysqli_query($connection, $query1);
						$query2 = "CREATE USER '".$usr."'@'".$this->server."' IDENTIFIED BY '".$pass."'";
						$result2 = mysqli_query($connection, $query2);
						$query3 = "GRANT ALL PRIVILEGES ON ".$dbnamegonnabe.".* TO '".$usr."'@'".$this->server."'";
						$result3 = mysqli_query($connection,$query3); 
						}
						//Next step is to add tables acording to the type of website u want to create
						$this->dbclosemaster($connection);
						//Now Copy the template into users path
						$this->copyTemplate($user,$project,$template);
						//Now create a ssk to encrypt/decrypt db credentials
						$ssk  = uniqid (rand (),false);
						$this->CreateSSK($user,$project, $ssk);
						//Now create db credentials and store them acordingly
						//Credentals are public , but tho, it's imposible to crack them w/o knowing the ssk and vector code,
						//Vector and ssk are unique for each project created
						//Nor I will know the ssk of the projects w/o manually opening ssk.req.class.php
						$this->CreateDatabaseCredentials($user,$project , $ssk,
						$this->server,
						$usr,
						$pass,
							$dbnamegonnabe);
						//This is to be expanded, create documents needed for the website to work
						//On first partof the function you declare the name of the document
						//On second part of the function you insert the content of the document
						$this->CreateDocs($user,$project, 
						[
							'gdpr.txt',
							'tos.txt',
							'dscl.txt',
							'widgets/info.json'
						],
						['',
						'',
						'',
						'{"Title":"New project",
						"Logo":"'.$globals['GLOBAL_URL'].'images/placeholder.jpg",
						"Favicon":"https://rosance.com/images/placeholder.jpg",
						"Curency":"EUR",
						"Meta":{"Description":"Hello I am a website and I\'ve been created by Rosance","Author":"ROSANCE"},
						"Maps":"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3152.070907689272!2d144.96551661539212!3d-37.81180807975275!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642c99e207041%3A0xc358d059bfe29278!2sRussell+St%2C+Melbourne+VIC%2C+Australia!5e0!3m2!1sen!2sin!4v1486986489826"}'
						]
						);
						//After creating the reqired documents , copy the reqired scripts
						//Scripts are located into System/Scripts folder
						//This is  to be expanded in the future
						$this->CopyScripts($user,$project, 
						[
						'database.class.php'
						]);
						//Create folders in wich to be media placed
						$this->CreateMedia($user,$project);
						//Now run a series of querys to shape the database for the type of project user has created
						$this->CreateTables($user,$dbnamegonnabe,$type,$project);
						// if no errors will occur, We should get a success message , otherwise will throw an exception
						//either here or in the first try block
						$this->updateDOM($this->_getPages($user,$project));
						$callback = new Callback();
						return $callback->SendSuccessOnMainPageWithRedirect("Project ".$project." has been created successfully!","/projects");
					}
					catch(\Exception $ex)
					{
						$this->dbclosemaster($connection);
						$callback = new Callback();
						return $callback->SendErrorOnMainPage($ex->getMessage());
					}
				}
			}
			catch(\Exception $ex)
			{
				$callback = new Callback();
				return $callback->SendErrorOnMainPage($ex->getMessage());
			}
		}

		private function _getPages($user, $project, $defaultpath="../clients/")
		{
			$src = $defaultpath.$user->Business_Name."/".$project;
			$dir = opendir($src);
			$files = array();
			while (false !== ($entry = readdir($dir)))
			{
				if(($entry == ".") or ($entry == "..") or (is_dir($src . '/' . $entry)))
				{
					continue;
				}
				else
				{
					if(explode(".", $entry)[1] == "php")
							array_unshift($files, $defaultpath.$user->Business_Name."/".$project."/".$entry);
				}
			}
			return $files;
		}
		/**
		 * Loop throught all elements inside the dom adn find the elements with editable tag
		 * Then add an unique id to that place
		 */
		private function updateDOM($paths)
		{
			foreach($paths as $path)
			{
				$content = file_get_contents($path);
				$content = phpQuery::newDocumentPHP($content);
				foreach(pq($content)->find('*[editable*=editable]') as $itm)
				{
				$id = uniqid("_",false);
				pq($itm)->addClass($id);
				pq($itm)->attr("data-panelID",$id);
				}
				$contentEdited =  htmlspecialchars_decode($content->php());
				file_put_contents($path,$contentEdited);
			}
		}

		private function copyTemplate($user, $project , $template)
		{
			if(file_exists("../templates/".$template."/"))
				$this->recurse_copy("../templates/".$template."/", '../clients/'.$user->Business_Name.'/'.$project."/");
			else
				echo "../templates/".$template."/  does not exists";
		}
		/**
		 * CPanel API Controller
		 * Make sure to change CPanel user credentials into Globals.ini.php
		 * This function create a database , an user , and add the user to the database with all the priviledges
		 * Also adds the MASTER user to the database , MASTER user is found in Globals.ini.php
		 * @param string $uniqueid
		 * @param string $user
		 */
		public function CPAPI($uniqueid,$user)
		{
			$globals= self::GetGlobals();
			//Create Db
			$this->createDb($globals['PREFIX'],$globals['PWD'],$uniqueid);
			
			//Create User
			$this->createUser($globals['PREFIX'],$globals['PWD'],$user,substr($uniqueid,0,10));
			
			//Add user to DB - ALL Privileges
			$this->addUserToDb($globals['PREFIX'],$globals['PWD'],$globals['MASTER'],$globals['PREFIX']."_".$uniqueid,'ALL');
			
			$this->addUserToDb($globals['PREFIX'],$globals['PWD'],$user,$globals['PREFIX']."_".$uniqueid,'ALL');
		}
		/**
		 * Create database using HTTP header auth into CPanel API
		 * @param string $cPanelUser
		 * @param string $cPanelPass
		 * @param string $dbName
		 */
		public function createDb($cPanelUser,$cPanelPass,$dbName) 
		{
		$buildRequest = "/frontend/hostgator/sql/addb.html?db=".$dbName;

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
		 * Create a database user using HTTP header auth into CPanel API
		 * @param string $cPanelUser
		 * @param string $cPanelPass
		 * @param string $userName
		 * @param string $userPass
		 */
		public function createUser($cPanelUser,$cPanelPass,$userName,$userPass) 
		{
		$buildRequest = "/execute/Mysql/create_user?name=".$userName."&password=".$userPass;

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
		 * Remove a database user using HTTP header auth into CPanel API
		 * @param string $cPanelUser
		 * @param string $cPanelPass
		 * @param string $userName
		 */
		public function removeUser($cPanelUser,$cPanelPass,$userName) 
		{
		$buildRequest = "/execute/Mysql/delete_user?name=".$userName;

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
		 * Remove a database using HTTP header auth into CPanel API
		 * @param string $cPanelUser
		 * @param string $cPanelPass
		 * @param string $dbname
		 */
		public function removeDb($cPanelUser,$cPanelPass,$dbname) 
		{
		$buildRequest = "/execute/Mysql/delete_database?name=".$dbname;

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
		 * Adds an user to database using HTTP header auth into CPanel API
		 * @param string $cPanelUser
		 * @param string $cPanelPass
		 * @param string $userName
		 * @param string $userPass
		 */
		public function addUserToDb($cPanelUser,$cPanelPass,$userName,$dbName,$privileges) 
		{

		$buildRequest = "/frontend/hostgator/sql/addusertodb.html?user=".$userName."&db=".$dbName."&privileges=".$privileges;

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
		 * Create tables on the database takingnto consideration which type of project you have
		 * @param User $user
		 * @param string $dbname
		 * @param string $projectType
		 * @param string $project
		 */
		private function CreateTables(User $user,$dbname,$projectType, $project)
		{
			$connection = $this->dbconnectslave($dbname);
			switch($projectType)
			{
				case "Blog":
					if(!file_exists("../clients/".$user->Business_Name.'/'.$project."/blog"))
						mkdir("../clients/".$user->Business_Name.'/'.$project."/blog");
					$sql = file_get_contents("../system/scripts/SQL/blog.sql");
					$result = mysqli_multi_query($connection,$sql);
				break;
				case "Basic":
					$sql = file_get_contents("../system/scripts/SQL/basic.sql");
					mysqli_multi_query($connection , $sql);
				break;
				case "Shop":
					$sql = file_get_contents("../system/scripts/SQL/shop.sql");
					mysqli_multi_query($connection , $sql);
				break;
			}
			$this->dbcloseslave($connection);
		}
		/**
		 * Create required documents
		 * It takes an instance of type User , a string representing the projectname
		 * and two array of documents names and contents
		 * @param User $user
		 * @param string $project
		 * @param array $docs
		 * @param string $contents
		 */
		private function CreateDocs(User $user,$project,$docs,$contents = null)
		{
			for ($i=0; $i < count($docs); $i++) {
				$isfolder = explode('/',$docs[$i]);
				if(count($isfolder) > 1)
				{
					if(!file_exists('../clients/'.$user->Business_Name.'/'.$project.'/core/'.$isfolder[0]."/"))
						mkdir('../clients/'.$user->Business_Name.'/'.$project.'/core/'.$isfolder[0],0777,true);
				}
				$file = fopen('../clients/'.$user->Business_Name.'/'.$project.'/core/'.$docs[$i],"w");
				if($contents[$i] != null)
					fwrite($file, $contents[$i]);
				fclose($file);
			}
		}

		private function CopyScripts($user,$project, $scripts)
		{
			for ($i=0; $i < count($scripts); $i++) {
				copy("../system/scripts/".$scripts[$i] , '../clients/'.$user->Business_Name.'/'.$project.'/core/'.$scripts[$i]);
			}
		}

		private function CreateMedia($user,$project)
		{
			if(!file_exists("../clients/".$user->Business_Name.'/'.$project."/images"))
				mkdir("../clients/".$user->Business_Name.'/'.$project."/images");
		}

		public function DeleteProject($userid, $project)
		{
			try
			{
				$globals = self::GetGlobals();
				$connection = $this->dbconnectmaster();
				$userid = mysqli_real_escape_string($connection, $userid);
				$project = mysqli_real_escape_string($connection, $project);
				$user = $this->GetUser($userid);
				//Check if user is owner of the project
				$query0 = "SELECT * FROM projects WHERE Owner='".$userid."' AND ProjectID='".$project."'";
				$result = mysqli_query($connection , $query0);
				$pname = mysqli_fetch_assoc($result);
				if($result->num_rows > 0)
				{
					try
					{
						$query1 = "DELETE FROM projects WHERE Owner='".$userid."' AND ProjectID='".$project."'";
						$result = mysqli_query($connection, $query1);
						$dbname = $globals['PREFIX']."_".$project;
						//for production
						//USE CPANEL API
						if($globals['TEST'] == false)
						{
							$this->removeDb($globals['PREFIX'],$globals['PWD'],$dbname);
							$this->removeUser($globals['PREFIX'],$globals['PWD'],$globals['PREFIX']."_".substr($project,0,7));
						}else{
						//for testing
						//USER MARIADB
						$query2 = "DROP DATABASE ".$dbname."";
						$result2 = mysqli_query($connection, $query2);
						$query3 = "DROP USER ".$globals['PREFIX']."_".substr($project,0,7)."@".$this->server."";
						$result3 = mysqli_query($connection, $query3); 
						}
						$this->recurse_delete("../clients/".$user->Business_Name.'/'.str_replace(' ', '', $pname['Project']));
						rmdir("../clients/".$user->Business_Name.'/'.str_replace(' ', '', $pname['Project']));
						$this->dbclosemaster($connection);
						if(isset($_COOKIE['NCS_PROJECT']))
						{
								if($_COOKIE['NCS_PROJECT'] == $project)
								{
									setcookie("NCS_PROJECT" , '' , time()-3600 , '/' , '' , 0 );
									unset( $_COOKIE["NCS_PROJECT"] );
								}
						}
						$callback = new Callback();
						return $callback->SendSuccessOnMainPageWithRedirect("Project successfully deleted!", "projects");
					}
					catch(\Exception $ex)
					{
						$this->dbclosemaster($connection);
						$callback = new Callback();
						return $callback->SendErrorOnMainPage("We've encountered an error , please try again later!");
					}
				}
				else
				{
					$this->dbclosemaster($connection);
					$callback = new Callback();
					throw new \Exception("You are not the owner of the project!");
				}
			}
			catch(\Exception $ex)
			{
				$callback = new Callback();
				return $callback->SendErrorOnMainPage("We've encountered an error , please try again later!");
			}
		}

		public static function RemoveProjectsAsync($user,$projectID,$connection){
			try{
				$globals = (new self)->GetGlobals();
				$user->id = mysqli_real_escape_string($connection, $user->id);
				$project = mysqli_real_escape_string($connection, $projectID);
				//Check if user is owner of the project
				$query0 = "SELECT * FROM projects WHERE Owner='".$user->id."' AND ProjectID='".$project."'";
				$result = mysqli_query($connection , $query0);
				$pname = mysqli_fetch_assoc($result);
				if($result->num_rows > 0)
				{
					try
					{
						$query1 = "DELETE FROM projects WHERE Owner='".$user->id."' AND ProjectID='".$project."'";
						$result = mysqli_query($connection, $query1);
						$dbname = $globals['PREFIX']."_".$project;
						//for production
						//USE CPANEL API
						if($globals['TEST'] == false)
						{
							(new self)->removeDb($globals['PREFIX'],$globals['PWD'],$dbname);
							(new self)->removeUser($globals['PREFIX'],$globals['PWD'],$globals['PREFIX']."_".substr($project,0,7));
						}else{
						//for testing
						//USER MARIADB
						$query2 = "DROP DATABASE ".$dbname."";
						$result2 = mysqli_query($connection, $query2);
						$query3 = "DROP USER ".$globals['PREFIX']."_".substr($project,0,7)."@".Database::$staticServer."";
						$result3 = mysqli_query($connection, $query3); 
						}
						(new self)->recurse_delete("../clients/".$user->Business_Name.'/'.str_replace(' ', '', $pname['Project']));
						rmdir("../clients/".$user->Business_Name.'/'.str_replace(' ', '', $pname['Project']));
						return true;
					}
					catch(\Exception $ex)
					{
						return $ex->getMessage();
					}
				}
				else
				{
					return false;
				}
			}
			catch(\Exception $ex)
			{
				return $ex->getMessage();
			}
		}

		public function UpdateLegalDocuments(Project $project, User $user, $data)
		{
			try{
				$callback = new Callback();
				if(empty($project) or empty($user) or empty($data))
					throw new \Exception("Fields cannot be empty!");
				if($project->project_owner != $user->id)
					throw new \Exception("You are not the owner of the project so you cannot make these changes!");
				
				$path = [
					0 =>"../clients/".$user->Business_Name.'/'.$project->project_name_short."/core/gdpr.txt",
					1 =>"../clients/".$user->Business_Name.'/'.$project->project_name_short."/core/tos.txt",
					2 =>"../clients/".$user->Business_Name.'/'.$project->project_name_short."/core/dscl.txt"
				];

				for ($i=0; $i < count($data); $i++) { 
					$f = fopen($path[$i],"w");
					fwrite($f, $data[$i]);
					fclose($f);
				}

				return $callback->SendSuccessToast("Legal documents successfully updated!");
			}
			catch(\Exception $ex)
			{
				$callback = new Callback();
				return $callback->SendErrorToast($ex->getMessage());
			}
		}
		public function GetLegalDocuments(User $user, Project $project, $defaultpath = "../../")
		{
			if(empty($project) or empty($user))
				return;

			$tosend = [];

			$path1 = $defaultpath."clients/".$user->Business_Name.'/'.$project->project_name_short."/core/gdpr.txt";
			$path2 = $defaultpath."clients/".$user->Business_Name.'/'.$project->project_name_short."/core/tos.txt";
			$path3 = $defaultpath."clients/".$user->Business_Name.'/'.$project->project_name_short."/core/dscl.txt";

			$tosend["GDPR"] = file_get_contents($path1);
			$tosend["TOS"] = file_get_contents($path2);
			$tosend["DSCL"] = file_get_contents($path3);
			
			return $tosend;
		}

		public function GetMailbox(User $user , Project $project, $submenu = false , $context = 'inbox')
		{
			try {
				$globals = self::GetGlobals();
				$connection = $this->dbconnectslave(
					$globals['PREFIX']."_".$project->project_id
				);
				// Check if it is for submenu or is for main purpose
				if($submenu)
				{
					$query = "SELECT * FROM messages WHERE hasRead=0 ORDER BY Date DESC LIMIT 5";
					$result = mysqli_query($connection,$query);
					if($result)
					{
						$this->dbcloseslave($connection);
						return $result;
					}
					else
						throw new \Exception("Something went wrong!");
				}
				else
				{
					if($context=='inbox')
					{
						$query = "SELECT * FROM messages ORDER BY Date DESC";
					}
					else
					{
						$query = "SELECT * FROM smessages ORDER BY Date DESC";
					}
					$result = mysqli_query($connection,$query);
					if($result)
					{
						$this->dbcloseslave($connection);
						return $result;
					}
					else
						throw new \Exception("Something went wrong!");

				}
				
			} catch (Exception $ex) {
				$this->dbcloseslave($connection);
				return $ex->getMessage();
			}
		}

		public function RemoveMails(User $user, Project $project , $mails, $context='inbox')
		{
			try {

				$globals = self::GetGlobals();
				$connection = $this->dbconnectslave(
					$globals['PREFIX']."_".$project->project_id
				);
				$mails = json_decode($mails,true);
				if(is_array($mails)){
					for ($i=0; $i < count($mails); $i++) 
					{ 
						$mails[$i] = mysqli_real_escape_string($connection,$mails[$i]);
					}
					$_mails = "('".implode("','", array_map("trim",$mails))."')";
					if($context == 'inbox')
						$query = "DELETE FROM messages WHERE Message IN ".$_mails;
					elseif($context =='sent')
					$query = "DELETE FROM smessages WHERE Message IN ".$_mails;
				}else{
						if($context=='inbox')
							$query = "DELETE FROM messages WHERE Message=".$mails;
						elseif($context=='sent')
							$query = "DELETE FROM smessages WHERE Message=".$mails;
					}

				$result = mysqli_query($connection, $query);
				if($result)
				{
					$this->dbcloseslave($connection);
					//Remove .txt files
					if(is_array($mails)){
						foreach($mails as $mail)
						{
							$path = "../clients/".$user->Business_Name.'/'.$project->project_name_short."/Mailbox/".$mail.".txt";
							unlink($path);
						}
					}
					else
					{
						$path = "../clients/".$user->Business_Name.'/'.$project->project_name_short."/Mailbox/".$mails.".txt";
						unlink($path);
					}
					$callback = new Callback();
					return $callback->SendSuccessToast("Mails successfully deleted");
				}
				else
					throw new \Exception("Something went wrong!");
				
			} catch (Exception $ex) {
				$this->dbcloseslave($connection);
				$callback = new Callback();
				return $callback->SendErrorToast($ex->getMessage());
			}
		}

		public function installTemplate(User $user, Project $project, $template)
		{
			try{
				if(empty($user) or empty($project))
					throw new \Exception("Something went wrong. User or Project is empty!");
				$globals = self::GetGlobals();
				$connection = $this->dbconnectslave(
					$globals['PREFIX']."_".$project->project_id
				);

				
				
			}catch(\Exception $ex)
			{
				$this->dbclosemaster($connection);
				return $callback->SendErrorToast($ex->getMessage());
			}
		}

		public static function insertBefore($input, $index, $newKey, $element) {
			if (!array_key_exists($index, $input)) {
				throw new \Exception("Index not found");
			}
			$tmpArray = array();
			foreach ($input as $key => $value) {
				if ($key === $index) {
					$tmpArray[$newKey] = $element;
				}
				$tmpArray[$key] = $value;
			}
			return $input;
		}
		
		public static function insertAfter($input, $index, $newKey, $element) {
			if (!array_key_exists($index, $input)) {
				throw new \Exception("Index not found");
			}
			$tmpArray = array();
			foreach ($input as $key => $value) {
				$tmpArray[$key] = $value;
				if ($key === $index) {
					$tmpArray[$newKey] = $element;
				}
			}
			return $tmpArray;
		}
	}
}
?>