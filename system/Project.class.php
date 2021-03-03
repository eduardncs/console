<?php
namespace Rosance
{
    require_once("rosance_autoload.php");
    use Rosance\Database;
    class Project
    {
        public $project_id;
        public $project_name;
        public $project_owner;
        public $project_type;
        public $project_date;
        public $project_name_short;
        public $project_template;
        public function __construct($id){
            $data = $this->GetData($id);
            if($data != null)
            {
                $this->project_id = $data['ProjectID'];
                $this->project_name = $data['Project'];
                $this->project_owner = $data['Owner'];
                $this->project_type = $data['ProjectType'];
                $this->project_date = $data['Date'];
                $this->project_name_short = str_replace(" ","",$data['Project']);
                $this->project_template = lcfirst($data['Template']);
            }
        }

        private function GetData($id){
            $db = new Database();
            $connection = $db->dbconnectmaster();
            $id = mysqli_real_escape_string($connection,$id);
            $query = "SELECT * FROM projects WHERE ProjectID='%s'";
            $query = sprintf($query,$id);
            $result = mysqli_query($connection,$query);
            $db->dbclosemaster($connection);
            if($result->num_rows != 0)    
                return mysqli_fetch_assoc($result);
            return null;
        }
    }
}
?>