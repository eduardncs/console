<?php
namespace Rosance
{   
	 /**
	* This class is used for Editor related tasks that involves file and database management inside user Project folder
	* Copyright (C) 2020-2021 by Eduard Neacsu
	* Created for Rosance, https://rosance.com
	*/
	use Rosance\Callback;
	require_once("phpQuery.php");
	class Editor extends Data
	{
		/**
		 * Get all pages inside client folder
		 * Can be filtered using $filter param
		 * @param User $user
		 * @param string $project
		 * @param string $defaultpath clients/ by default
		 * @param array $filer Null by default
		 * @return array $files
		 */
		public function GetPages(User $user, $project, $defaultpath="clients/", $filter = null)
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
					if($filter === null)
					{
						if($this->get_extension($entry) == "php")
							array_unshift($files, $entry);
					}else{
						for ($i=0; $i < count($filter); $i++) { 
							if($this->get_extension($entry) == $filter[$i])
								array_unshift($files,$entry);
						}
					}
				}
			}
			return $files;
		}
		/**
		 * Return the extenstion of a file
		 * @param string $file
		 * @return string $extension
		 */
		private function get_extension($file)
		{
			$extension = explode(".", $file);
			return $extension[1];
		}
		/**
		 * Return entire content of the folder images from user project
		 * Also the content inside images/url.json wich
		 * @param User $user
		 * @param Project $project
		 * @return array $files;
		 */
		public function getAlbum(User $user,Project $project)
		{
			try
			{
				$globals = $this->GetGlobals();
				$src = "../clients/".$user->Business_Name.'/'.$project->project_name_short."/images/";
				$dir = opendir($src);
				$files = ["UPLOADEDFROMURL" => [] , "UPLOADEDFROMCOMPUTER" => [], "UPLOADEDFROMCOMPUTERENTRIES" => [] ];
				$allowedextensions = array("jpg","jpeg","gif","ico","png");
				$jsonfile = array("json");
				while (false !== ($entry = readdir($dir)))
				{
					if(($entry == ".") or ($entry == "..") or (is_dir($src . '/' . $entry)))
					{
						continue;
					}
					else
					{
						if(in_array($this->get_extension($entry), $jsonfile))
						{
							$jsoncontents = json_decode(file_get_contents($src."/url.json"),true);
							if($jsoncontents !== null)
								if($jsoncontents['URLS'] !== null)
									foreach($jsoncontents['URLS'] as $item)
										array_unshift($files['UPLOADEDFROMURL'] , $item['URL']);
						}
						if(in_array($this->get_extension($entry), $allowedextensions))
						{
							array_unshift($files['UPLOADEDFROMCOMPUTER'] , $globals['GLOBAL_URL']."/clients/".$user->Business_Name."/".$project->project_name_short."/images/".$entry);
							array_unshift($files['UPLOADEDFROMCOMPUTERENTRIES'],$entry);
						}
					}
				}
				return $files;
			}
			catch(\Exception $ex)
			{
				return $ex;
			}
		}
		/**
		 * Returns a decoded JSON version of user's project info.json file
		 * Defaultpath is used to make distriction of relative and absolute paths of the files
		 * @param User $user
		 * @param string $project
		 * @param string $defaultpath Defaulted to "../../"
		 * @return string $filedecoded
		 */
		public function getInfo(User $user, $project,$defaultpath = "../../")
		{
			$path_to_file = $defaultpath.'clients/'.$user->Business_Name.'/'.$project.'/core/widgets/info.json';
			$filedecoded = json_decode(file_get_contents($path_to_file),true);
			return $filedecoded;
		}
		/** TO BE REMOVED WHEN I SEE THIS NEXT!!!! */
		public function ChangeInfoJson(User $user , Project $project , $data)
		{
			$callback = new Callback();
			if(empty($project) or empty($user) or empty($data))
				return $callback->SendErrorOnMainPage("Fields cannot be empty!");
			
			$contents = $this->getWidgetJSON($user,$project,"info");
			$contents['Title'] = $data['Title'];
			$contents['Favicon'] = $data['Favicon'];
			$contents['Curency'] = $data['Curency'];
			$contents['Logo'] = $data['Logo'];
			$contents['Meta']['Author'] = $data["Meta"]['Author'];
			$contents['Maps'] = $data['Maps'];
			$contentsEncoded = json_encode($contents);
			if($this->PutWidgetJSON($user , $project,"info",$contentsEncoded));
				return $callback->SendSuccessToast("Settings successfully updated!");
			return $callback->SendErrorToast("Something went wrong!");
		}
		/**
		 * Returns user portofolio widget
		 * @param User $user
		 * @param PRoject $project
		 * @param string $data;
		 */
		public function getPortofolio(User $user, Project $project)
		{
			if(empty($project) or empty($user))
				return null;
			
			$globals = $this->GetGlobals();
			$connection = $this->dbconnectslave(
				$globals['PREFIX']."_".$project->project_id
			);
			$query = "SELECT * FROM portofolio";
			$result = mysqli_query($connection,$query);
			$this->dbclosemaster($connection);
			if($result)
				return $result;
			else return null;
		}
		/**
		 * Add or edit an entry from the user's portofolio widget
		 * It all depends either if $data['Key'] exists in the database
		 * @param User $user
		 * @param Project $project
		 * @param string $data;
		 */
		public function EditPortofolio(User $user , Project $project , $data)
		{		
			$callback = new Callback();
			if(empty($project) or empty($user) or empty($data))
				return $callback->SendErrorToast("Fields cannot be empty!");
			
			if($data['Cover'] == "" or empty($data['Cover']))
				return $callback->SendErrorToast("Please select a cover photo");

			$globals = $this->GetGlobals();
			$connection = $this->dbconnectslave(
				$globals['PREFIX']."_".$project->project_id
			);
			//Sanitize data
			$data['Key'] = mysqli_real_escape_string($connection,$data['Key']);
			$data['Title'] = mysqli_real_escape_string($connection,$data['Title']);
			$data['Description'] = mysqli_real_escape_string($connection,$data['Description']);
			$data['Demo'] = mysqli_real_escape_string($connection,$data['Demo']);
			$data['Source'] = mysqli_real_escape_string($connection,$data['Source']);
			$data['Cover'] = mysqli_real_escape_string($connection,$data['Cover']);

			//Check if allready exists
			$query0 = "SELECT * FROM portofolio WHERE ID='".$data['Key']."'";
			$result0 = mysqli_query($connection,$query0);

			if(!$result0)
			{
				$this->dbclosemaster($connection);
				return $callback->SendErrorToast("Something went wrong, please try again #1!");
			}

			if($result0->num_rows > 0)
			{
				//Exista si este doar edit
				$query1 = "UPDATE portofolio SET Title='".$data['Title']."',Description='".$data['Description']."',Demo='".$data['Demo']."',Source='".$data['Source']."',Cover='".$data['Cover']."' WHERE ID='".$data['Key']."'";
				$result1 = mysqli_query($connection,$query1);
				$this->dbclosemaster($connection);
				if($result1)
					return $callback->SendSuccessToast("Project updated successfully!");
				else return $callback->SendErrorToast("Something went wrong , please try again #2!");
			}
			else
			{
				$date = date("Y-m-d H:i");
				//Trebuie introdusa
				$query2 = "INSERT INTO portofolio(Title,Description,Demo,Source,Cover,Date) VALUES ('".$data['Title']."','".$data['Description']."','".$data['Demo']."','".$data['Source']."','".$data['Cover']."','".$date."')";
				$result2 = mysqli_query($connection,$query2);
				$this->dbclosemaster($connection);
				if($result2)
					return $callback->SendSuccessToast("Project created successfully!");
				else return $callback->SendErrorToast("Something went wrong, please try again #3!");
			}
		}
		/**
		 * Remove an entry from the user's portofolio widget
		 * @param User $user
		 * @param PRoject $project
		 * @param string $data;
		 */
		public function RemoveFromPortofolio(User $user , Project $project , $data)
		{
			$callback = new Callback();
			if(empty($project) or empty($user) or empty($data))
				return $callback->SendErrorToast("Fields cannot be empty!");
			$globals = $this->GetGlobals();
			$connection = $this->dbconnectslave(
				$globals['PREFIX']."_".$project->project_id
			);
			//Sanitize data
			$data['Key'] = mysqli_real_escape_string($connection,$data['Key']);
			$query = "DELETE FROM portofolio WHERE ID='".$data['Key']."'";
			$result = mysqli_query($connection,$query);
			$this->dbcloseslave($connection);
			if(!$result)
				return $callback->SendErrorToast("Something went wrong!");
			return $callback->SendSuccessToast("Project removed successfully!");
		}
		/**
		 * Remove a link from the menu json file inside users project folder
		 * @param User $user
		 * @param Project $project
		 * @param string $key
		 */
		public function removeLink(User $user, Project $project, $key)
		{
			if(empty($key))
				return;
			$rawJSON = $this->getWidgetJSON($user,$project,"menu");

			for ($i=0; $i < count($rawJSON['Menu']); $i++) { 
				if(array_key_exists("Children", $rawJSON['Menu'][$i]))
				{
					for ($j=0; $j < count($rawJSON['Menu'][$i]['Children']); $j++) { 
						if($rawJSON['Menu'][$i]['Children'][$j]['Key'] == $key)
						{
							array_splice($rawJSON['Menu'][$i]['Children'],$j,1);
						}
					}
				}
				if($rawJSON['Menu'][$i]["Key"] == $key)
				{
					array_splice($rawJSON['Menu'],$i,1);
				}
			}

			$finaljson = json_encode($rawJSON);
			$result = $this->PutWidgetJSON($user, $project , "menu", $finaljson);
			$result = true;
			if($result)
				return ["success" => "Link removed successfully"];
			return ["error" => "Something went wrong ..."];
		}
		/**
		 * Add a new link into json file
		 * @param User $user
		 * @param Project $project
		 * @param string $key
		 * @param bool isFolder
		 */
		public function addLink(User $user, Project $project, $key, $isFolder)
		{
			if(empty($key))
				return;
			$rawJSON = $this->getWidgetJSON($user, $project , "menu");

			$arrt['Key'] = $key;
			if($isFolder)
			{
				$arrt['Text'] = "New folder";
				$arrt['Href'] = "javascript:void(0)";
				$arrt['P_Key'] = "1";
				$arrt['Children'] = [];
			}else{
				$arrt['Text'] = "New link";
				$arrt['Href'] = "#";
				$arrt['Target'] = "_self";
				$arrt['P_Key'] = "0";
			}
			$rawJSON['Menu'][] = $arrt;
			$result = $this->PutWidgetJSON($user,$project, "menu", json_encode($rawJSON));
			if($result)
				return ["success"=>"Success"];
			return ["error"=>"Something went wrong"];
		}
		/**
		 * Change a menu item chield parent and adjust it's index
		 * @param User $user
		 * @param Project $project
		 * @param string $who
		 * @param string $to
		 * @param string $neighbour
		 * @param bool $inverted
		 * @param bool isFirst
		 */
		public function move(User $user, Project $project, $who, $to, $neighbour, $inverted, $isFirst){
		try
		{
			if(!isset($who) or !isset($to) or !isset($neighbour) or !isset($inverted) or !isset($isFirst))
				throw new \Exception("Some fields came out blank , try reloading the page ...");
			$rawJSON = $this->getWidgetJSON($user, $project , "menu");
			/**
			 * Find the element in json
			 * Loop trought all json entities , if entity key is equal with $who you found $item;
			 * We have some local vars like $isChildren that is not set unless $tem is children of some element
			 * Also after finding $item we need to find Neighbour. 
			 */
			for ($i=0; $i < count($rawJSON['Menu']); $i++) { 
				if($rawJSON['Menu'][$i]['Key'] === $who)
				{
					$itemIndex = $i;
					$item = $rawJSON['Menu'][$i];
				}
				if(array_key_exists("Children",$rawJSON['Menu'][$i]))
				{
					for ($j=0; $j < count($rawJSON['Menu'][$i]['Children']); $j++) { 
						if($rawJSON['Menu'][$i]["Children"][$j]["Key"] === $who)
						{
							$isChildren = [
										"ParentIndex"=>$i,
										"MyIndex"=>$j
										];
							$item = $rawJSON['Menu'][$i]["Children"][$j];
						}
					}
				}
 				if($rawJSON['Menu'][$i]["Key"] === $neighbour)
				{
					$neighbourIndex = $i;
				}
 				if(array_key_exists("Children",$rawJSON['Menu'][$i]))
				{
					for ($k=0; $k < count($rawJSON['Menu'][$i]['Children']); $k++) { 
						if($rawJSON['Menu'][$i]["Children"][$k]["Key"] === $neighbour)
						{
							$neighbourFoundInChildren = ["ParentIndex"=>$i, "NeighbourIndex"=>$k];
						}
					}
				}
			}
			if(!isset($item))
				throw new \Exception("Item could not be found!");
			if($item['P_Key'] == "1")
				throw new \Exception("Folders cannot be added inside other folders yet ...");
			if($to === "menu-container-master")
				$item['P_Key'] = "0";
			else
				$item['P_Key'] = $to;
			
			//Now swap items 
			//Unset previous value
			if(!isset($isChildren))
			{
				unset($rawJSON['Menu'][$itemIndex]);
				$rawJSON['Menu'] = array_values($rawJSON['Menu']);
			}
			else
			{
				unset($rawJSON['Menu'][$isChildren['ParentIndex']]['Children'][$isChildren['MyIndex']]);
				$rawJSON['Menu'][$isChildren['ParentIndex']]['Children'] = array_values($rawJSON['Menu'][$isChildren['ParentIndex']]['Children']);
			}
			
			if(!$isFirst){
				if($inverted)
				{
					//Before
					if(!isset($neighbourFoundInChildren))
					{
						array_splice($rawJSON['Menu'],$neighbourIndex,0,array($item));
					}else{
						array_splice($rawJSON['Menu'][$neighbourFoundInChildren['ParentIndex']]['Children'],$neighbourFoundInChildren['NeighbourIndex'],0,array($item));
					}
				}else{
					//After
					if(!isset($neighbourFoundInChildren))
					{
						array_splice($rawJSON['Menu'],$neighbourIndex+1,0,array($item));
					}else{
						array_splice($rawJSON['Menu'][$neighbourFoundInChildren['ParentIndex']]['Children'],$neighbourFoundInChildren['NeighbourIndex']+1,0,array($item));
					}
				}
			}else {
				//This is Done!
				foreach($rawJSON['Menu'] as $key => $parent)
				{
					if($parent['Key'] === $to)
					{
						$rawJSON['Menu'][$key]['Children'][] = $item;
					}
				}
			}
			$result = $this->PutWidgetJSON($user,$project, "menu", json_encode(["Menu" => array_values($rawJSON['Menu'])] ));
			if($result)
				return ["success"=>"Success"];
			throw new \Exception("Something went wrong ...");

		} catch(\Exception $ex)
			{
				return ["error"=>$ex->getMessage()];
			}
		}
		/**
		 * Change a menu item index
		 * @param User $user
		 * @param Project $project
		 * @param string $who
		 * @param string $neighbour
		 * @param bool $inverted
		 */
		public function changeIndex(User $user, Project $project, $who, $neighbour, $inverted)
		{
			try
			{
				if(!isset($who) or !isset($neighbour) or !isset($inverted))
					throw new \Exception("Some fields came out blank , try reloading the page ...");

				$rawJSON = $this->getWidgetJSON($user, $project , "menu");

				for ($i=0; $i < count($rawJSON['Menu']); $i++) { 
					if($rawJSON['Menu'][$i]['Key'] === $who)
					{
						$itemIndex = $i;
						$item = $rawJSON['Menu'][$i];
					}
					if(array_key_exists("Children",$rawJSON['Menu'][$i]))
					{
						for ($j=0; $j < count($rawJSON['Menu'][$i]['Children']); $j++) { 
							if($rawJSON['Menu'][$i]["Children"][$j]["Key"] === $who)
							{
								$isChildren = [
											"ParentIndex"=>$i,
											"MyIndex"=>$j
											];
								$item = $rawJSON['Menu'][$i]["Children"][$j];
							}
						}
					}
					 if($rawJSON['Menu'][$i]["Key"] === $neighbour)
					{
						$neighbourIndex = $i;
					}
					 if(array_key_exists("Children",$rawJSON['Menu'][$i]))
					{
						for ($k=0; $k < count($rawJSON['Menu'][$i]['Children']); $k++) { 
							if($rawJSON['Menu'][$i]["Children"][$k]["Key"] === $neighbour)
							{
								$neighbourFoundInChildren = ["ParentIndex"=>$i, "NeighbourIndex"=>$k];
							}
						}
					}
				}
				if(!isset($item))
					throw new \Exception("Item could not be found!");
				//Now swap items 
				//Unset previous value
				if(!isset($isChildren))
				{
					unset($rawJSON['Menu'][$itemIndex]);
					$rawJSON['Menu'] = array_values($rawJSON['Menu']);
				}
				else
				{
					unset($rawJSON['Menu'][$isChildren['ParentIndex']]['Children'][$isChildren['MyIndex']]);
					$rawJSON['Menu'][$isChildren['ParentIndex']]['Children'] = array_values($rawJSON['Menu'][$isChildren['ParentIndex']]['Children']);
				}

				if($inverted)
				{
					//Before
					if(!isset($neighbourFoundInChildren))
					{
						array_splice($rawJSON['Menu'],$neighbourIndex,0,array($item));
					}else{
						array_splice($rawJSON['Menu'][$neighbourFoundInChildren['ParentIndex']]['Children'],$neighbourFoundInChildren['NeighbourIndex'],0,array($item));
					}
				}else{
					//After
					if(!isset($neighbourFoundInChildren))
					{
						array_splice($rawJSON['Menu'],$neighbourIndex+1,0,array($item));
					}else{
						array_splice($rawJSON['Menu'][$neighbourFoundInChildren['ParentIndex']]['Children'],$neighbourFoundInChildren['NeighbourIndex']+1,0,array($item));
					}
				}
				$result = $this->PutWidgetJSON($user,$project, "menu", json_encode(["Menu" => array_values($rawJSON['Menu'])] ));
				if($result)
					return ["success"=>"Success"];
				throw new \Exception("Something went wrong ...");

			}catch(\Exception $ex){
				return ["error"=>$ex->getMessage()];
			}
		}
		/**
		 * Edit a menu link
		 * @param User $user
		 * @param Project $project
		 * @param string $key
		 * @param bool $isFolder // Defaulted to false
		 * @param string $text
		 * @param string $href
		 * @param string $target
		 */
		public function editLink(User $user ,Project $project , $key, $isFolder = false, $text, $href, $target)
		{
			try
			{
				if(!isset($key))
					throw new \Exception("Some fields came out blank , try reloading the page ...");

				$callback = new Callback();
				$rawJSON = $this->getWidgetJSON($user, $project , "menu");

				for ($i=0; $i < count($rawJSON['Menu']); $i++) { 
					if($rawJSON['Menu'][$i]['Key'] === $key)
					{
						$itemIndex = $i;
					}
					if(array_key_exists("Children",$rawJSON['Menu'][$i]))
					{
						for ($j=0; $j < count($rawJSON['Menu'][$i]['Children']); $j++) { 
							if($rawJSON['Menu'][$i]["Children"][$j]["Key"] === $key)
							{
								$isChildren = [
											"ParentIndex"=>$i,
											"MyIndex"=>$j
											];
							}
						}
					}
				}
				if(!isset($itemIndex) && !isset($isChildren))
					throw new \Exception("Item could not be found!");

				if($isFolder)
				{	
					!isset($isChildren) ? $rawJSON["Menu"][$itemIndex]['Text'] = $text : $rawJSON["Menu"][$isChildren['ParentIndex']]['Children'][$isChildren['MyIndex']]['Text'] = $text;
				}else{
					!isset($isChildren) ? $rawJSON["Menu"][$itemIndex]['Text'] = $text : $rawJSON["Menu"][$isChildren['ParentIndex']]['Children'][$isChildren['MyIndex']]['Text'] = $text;
					!isset($isChildren) ? $rawJSON["Menu"][$itemIndex]['Href'] = $href : $rawJSON["Menu"][$isChildren['ParentIndex']]['Children'][$isChildren['MyIndex']]['Href'] = $href;
					!isset($isChildren) ? $rawJSON["Menu"][$itemIndex]['Target'] = $target : $rawJSON["Menu"][$isChildren['ParentIndex']]['Children'][$isChildren['MyIndex']]['Target'] = $target;
				}
				$result = $this->PutWidgetJSON($user,$project, "menu", json_encode(["Menu" => array_values($rawJSON['Menu'])] ));
				if($result)
					return $callback->SendSuccessToast("Link edited!");
				throw new \Exception("Something went wrong ...");
			}catch(\Exception $ex)
			{
				$callback = new Callback();
				return $callback->SendErrorToast($ex->getMessage());
			}
		}
		/**
		 * Securely upload a new file to the server on user specific area on the disk
		 * @param User $user
		 * @param Project $project
		 * @param $_FILES $file
		 */
		public function uploadFile(User $user,Project $project, $file) {
		try{
			include_once("../System/lib/Upload.php");
			if(empty($file))
				throw new Exception("Something went wrong :(");

			$upload =new \Upload('file');
			$upload->MIME_allowed = array(
				"image/jpeg",
				"image/pjpeg",
				"image/bmp",
				"image/gif",
				"image/png",
				"image/jpg",
				"image/x-icon",
				"image/svg",
			);
			$upload
				->file_name(true)
				->upload_to('../clients/'.$user->Business_Name."/".$project->project_name_short."/images/")
				->file_max_size(1000000 * 5)
				->run();
				
				if (!$upload->was_uploaded) {
					throw new Exception("There was an error: ".$upload->error);
				} else {
					$callback = new Callback();
					return $callback->SendSuccessOnMainPage("Media successfully uploaded!");
				}
			}catch(\Exception $ex){
				$callback = new Callback();
				return $callback->SendErrorOnMainPage($ex->getMessage());
			}
		}
		/**
		 * It's like "Uploading" a file but actualy only copy the url into an json file iwth a specific key...
		 * @param User $user
		 * @param Project $project
		 * @param string $url
		 */
		public function uploadFileFromURL(User $user, Project $project, $url){
			try{
				$jsonfile = file_get_contents("../clients/".$user->Business_Name."/".$project->project_name_short."/images/url.json");
				$content = json_decode($jsonfile,true);
				if(is_array($content))
					if(!array_key_exists("URLS",$content))
						$content = ["URLS" => []];
				if(!is_array($content['URLS']))
					$content['URLS'] = [];
				$array['URL'] = $url;
				array_push($content['URLS'] , $array);
				$content = json_encode($content);
				file_put_contents("../clients/".$user->Business_Name."/".$project->project_name_short."/images/url.json", $content);
				$callback = new Callback();
				return $callback->SendSuccessToast("Media successfully uploaded!");
			}catch(\Exception $ex){
				$callback = new Callback();
				return $callback->SendErrorOnMainPage($ex->getMessage());
			}
		}
		/**
		 * Remove media file from user image folder or from the json file
		 * @param User $user
		 * @param Project $project
		 * @param string $source
		 * @param string $id
		 */
		public function removeMedia(User $user, Project $project, $source, $id){
			try{
				$id = trim($id);
				if(empty($source) || empty($id))
					throw new Exception("Something went wrong... :(");
				$global_vars = $this->getGlobals();
				$callback = new Callback();
				switch($source){
					case "computer":
						$dir = "../clients/".$user->Business_Name."/".$project->project_name_short."/images/";
						$di = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
						$ri = new \RecursiveIteratorIterator($di, \RecursiveIteratorIterator::CHILD_FIRST);
						foreach ( $ri as $file )
						{
								if($file->getFileName() != str_replace($global_vars['GLOBAL_URL']."/clients/".$user->Business_Name."/".$project->project_name_short."/images/","",$id))
									continue;
								unlink($file);
								return $callback->SendSuccessToast("Media successfully removed!");
								break;
						}
					break;
					case "url":
						$jsonfile = file_get_contents("../clients/".$user->Business_Name."/".$project->project_name_short."/images/url.json");
						$content = json_decode($jsonfile,true);
						$result = ["URLS" => []];
			
						foreach($content['URLS'] as $keyz) 
						{
								if($keyz["URL"] != $id) {
										$result["URLS"][] = $keyz;
								}
						}
			
						$content = json_encode($result);
						file_put_contents("../clients/".$user->Business_Name."/".$project->project_name_short."/images/url.json", $content);
						return $callback->SendSuccessToast("Media successfully removed!");
					break;
					}
			}catch(\Exception $ex){
				$callback = new Callback();
				return $callback->SendErrorOnMainPage($ex->getMessage());
			}
		}
	}
}
?>