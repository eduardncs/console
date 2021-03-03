<?php
namespace Rosance
{
    require_once("rosance_autoload.php");
    use Rosance\Callback;
    require_once("Database.class.php");
    require_once("Data.class.php");
    require_once("User.class.php");
    require_once("Project.class.php");
    require_once("Callback.class.php");
    class Mail extends Data
    {
        public $template;
        private $user;
        private $project;
        public $id;
        public $name;
        public $subject;
        public $contentID;
        public $date;
        public $read;
        public $email;
        private $ip;

        function __construct2(User $user, Project $project)
        {
            $template = $this->GetTemplate();
        }
        function __construct(User $user, Project $project , $id , $context ='inbox')
        {
            $this->id = $id;
            $this->user = $user;
            $this->project = $project;
            if($context == 'inbox')
            {
                $data = $this->getMailReceived();
                if($data != null)
                {
                $this->name = $data['Name'];
                $this->subject = $data['Subject'];
                $this->contentID = $data['Message'];
                $this->ip = $data['Ip'];
                $this->read = $data['hasRead'];
                $this->date = $data['Date'];
                $this->email = $data['Email'];
                }
            }
            elseif($contect == 'sent')
            {
                $data = $this->getMailSent();
                if($data != null)
                {
                    $this->name = $data['Name'];
                    $this->subject = $data['Subject'];
                    $this->contentID = $data['Message'];
                    $this->date = $data['Date'];
                    $this->email = $data['Email'];
                }
            }
        
        }

        protected function getMailReceived()
        {
            $globals = $this->GetGlobals();
                $connection = $this->dbconnectslave(
                    $globals['PREFIX']."_".$this->project->project_id
                );
            //sanitize input
            $id = mysqli_real_escape_string($connection, $this->id);
            $query = "SELECT * FROM messages WHERE ID=".$id;
            $result = mysqli_query($connection,$query);
            $this->dbcloseslave($connection);
            if($result->num_rows != 0)
                return mysqli_fetch_assoc($result);
            return null;
        }

        protected function getMailSent()
        {
            $globals = $this->GetGlobals();
                $connection = $this->dbconnectslave(
                    $globals['PREFIX']."_".$this->project->project_id
                );
            //sanitize input
            $id = mysqli_real_escape_string($connection, $this->id);
            $query = "SELECT * FROM smessages WHERE ID=".$id;
            $result = mysqli_query($connection,$query);
            $this->dbcloseslave($connection);
            if($result->num_rows != 0)
                return mysqli_fetch_assoc($result);
            return null;
        }

        public function getMailContent()
        {
            $path = "../../clients/".$this->user->Business_Name.'/'.$this->project->project_name_short."/Mailbox/".$this->contentID.".txt";
            if(file_exists($path))
                return file_get_contents($path);
            return "<h1>Sorry</h1></br><h3>The mail does not exists anymore!</h3>";
        }

        public function markAsRead()
        {
            if($this->read)
                return;
            $globals = $this->GetGlobals();
                $connection = $this->dbconnectslave(
                    $globals['PREFIX']."_".$this->project->project_id
                );
            //sanitize input
            $id = mysqli_real_escape_string($connection , $this->id);
            $query = "UPDATE messages SET hasRead=1 WHERE ID=".$this->id;
            $result = mysqli_query($connection,$query);
            if($result)
                return true;
            return false;
        }

        public function SendMail($to,$subject,$content)
        {
            try{
                $callback = new Callback();
                if(empty($to) or empty($subject) or empty($content))
                {
                    throw new \Exception("Fields cannot be empty!");
                }
                $globals = $this->GetGlobals();
                $connection = $this->dbconnectslave(
                    $globals['PREFIX']."_".$this->project->project_id
                );
                //Sanitize inputs
                $to = mysqli_real_escape_string($connection,$to);
                $subject = mysqli_real_escape_string($connection,$subject);
                $content = mysqli_real_escape_string($connection,$content);
                $contentID = uniqid(rand(),false);
                $date = date("Y-m-d H:i");

                $query = "INSERT INTO smessages(Date,Email,Subject,Message) VALUES ('".$date."','".$to."','".$subject."','".$contentID."')";
                $result = mysqli_query($connection,$result);
                if($result)
                {
                    //Create the .txt file
                    $path = "../../clients/".$this->user->Business_Name.'/'.$this->project->project_name_short."/Mailbox/".$this->contentID.".txt";
                    $file = fopen($path,"w");
                    fwrite($file,$content);
                    fclose($file);
                    $this->dbclosemaster($connection);

                    $real = $this->SendRealMail(
                        $to,
                        $subject,
                        $content
                    );

                    return $callback->SendSuccessToast("Success!");
                }
                else
                    throw new \Exception("Something went wrong!");
            }
            catch(\Exception $ex)
            {
                $this->dbcloseslave($connection);
                return $callback->SendErrorToast($ex->getMessage());
            }
        }
        protected function SendRealMail($to, $subject, $content)
        {
            //Get email template
            $template = $this->GetWidgetJson($this->user, $this->project, "mail");
            
        }
    }
}


?>