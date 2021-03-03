<?php
namespace Rosance 
{
    use Rosance\Callback;
    use Rosance\User;
    use Rosance\Project;
    use Rosance\Data;
    use Rosance\Database;
    if(!isset($_SESSION))
        session_start();
    class shop extends Data
    {
        public function S_GetProducts(User $user, Project $project)
        {
            try
            {
                if(empty($user) or empty($project))
                    throw new \Exception();

                $globals = $this->GetGlobals();
                $connection = $this->dbconnectslave(
                    $globals['PREFIX']."_".$project->project_id
                );
                $query = "SELECT * FROM products ORDER BY Date DESC";
                $result = mysqli_query($connection, $query);
                $this->dbcloseslave($connection);
                if($result)
                {
                    return $result;
                }
                return null;
            }catch(\Exception $ex)
            {
                return null;
            }
        }

        public function S_GetCategories(Project $project)
        {
            $globals = $this->GetGlobals();
            $connection = $this->dbconnectslave(
                $globals['PREFIX']."_".$project->project_id
            );
            $query = "SELECT * FROM categories WHERE P_ID='0'";
            $result = mysqli_query($connection,$query);

            if($result->num_rows == 0)
                return "No parents found!";
                
            $ca = [];
            while($row = mysqli_fetch_assoc($result))
            {
                $ca[$row['ID']]["Parent"]["Name"] = $row['Name'];
                $ca[$row['ID']]["Parent"]["ID"] = $row['ID'];
                $ca[$row['ID']]["Children"] = [];
            }
            $ac = array_keys($ca);
            foreach($ac as $a)
            {
                $query1 = "SELECT * FROM categories WHERE P_ID='".$a."'";
                $result1 = mysqli_query($connection,$query1);
                while($s = mysqli_fetch_assoc($result1))
                {
                    $ca[$a]["Children"][] = $s;
                }
            }

            $this->dbcloseslave($connection);

            return $ca;
        }

        public function S_AddProduct(User $user,Project $project, $data)
        {
            try{
                $callback= new Callback();
                if(empty($user) or empty($project) or empty($data['productName']) or empty($data['productavailable']) or empty($data['productCategories']) or empty($data['BPrice']) or empty($data['featured']))
                    throw new \Exception("Some fields are required!");
                if(empty($data['picsA']))
                    throw new \Exception("Please select at least one picture for this product!");
                if(!empty($data['RPrice']) && ($data['BPrice'] <= $data['RPrice']))
                    throw new \Exception("Base price cannot be less than reduced price!");

                $globals = $this->GetGlobals();
                $connection = $this->dbconnectslave(
                    $globals['PREFIX']."_".$project->project_id
                );

                //Sanitize data
                $data['productName'] = mysqli_real_escape_string($connection, $data['productName']);
                //$data['productDescription'] = mysqli_real_escape_string($connection, $data['productDescription']);
                $data['productavailable'] = mysqli_real_escape_string($connection, $data['productavailable']);
                $data['productCategories'] = mysqli_real_escape_string($connection, $data['productCategories']);
                $data['BPrice'] = mysqli_real_escape_string($connection, $data['BPrice']);
                $data['RPrice'] = mysqli_real_escape_string($connection, $data['RPrice']);
                $data['APrice'] = mysqli_real_escape_string($connection, $data['APrice']);
                $data['D_Val'] = mysqli_real_escape_string($connection, $data['D_Val']);
                $data['Stock'] = mysqli_real_escape_string($connection, $data['Stock']);
                $data['picsA'] = mysqli_real_escape_string($connection, $data['picsA']);
                $data['featured'] = (boolean)mysqli_real_escape_string($connection, $data['featured']);

                $uniqueid = uniqid(rand(),false);
                $date = date("Y-m-d H:i");
                $pictures = explode(",",$data['picsA']);
                $pN = count($pictures);
                if($pN > 5)
                    throw new \Exception("You can only add 5 pictures per product!");
                $pictures = json_encode($pictures);

                $categories = explode(",",$data['productCategories']);
                $categories = json_encode($categories);

                $query = "INSERT INTO products(Name,Description,BPrice,RPrice,APrice,D_Val,Available,Date,Pictures,Categories,Stock,Featured) VALUES('".$data['productName']."','".$uniqueid."','".$data['BPrice']."','".$data['RPrice']."','".$data['APrice']."','".$data['D_Val']."','".$data['productavailable']."','".$date."','".$pictures."','".$categories."','".$data['Stock']."','".$data['featured']."')";
                $result = mysqli_query($connection,$query);
                $this->dbcloseslave($connection);
                //Create .txt file
                $this->S_CreateDescriptionFile($user, $project , $uniqueid, $data['productDescription']);
                if($result)
                {
                    return $callback->SendSuccessToast("Product successfully added!");
                }
                throw new \Exception("Something went wrong!");
            }catch(\Exception $ex){
                if(isset($connection) && $connection != null)
                    $this->dbcloseslave($connection);
                return $callback->SendErrorToast($ex->getMessage());
            }
        }

        private function S_CreateDescriptionFile(User $user, Project $project , $uniqueid, $data)
        {
            if(!file_exists('../../clients/'.$user->Business_Name.'/'.$project->project_name_short.'/prods/'))
                mkdir('../../clients/'.$user->Business_Name.'/'.$project->project_name_short.'/prods/',0777,true);
            
            $file = fopen('../../clients/'.$user->Business_Name.'/'.$project->project_name_short.'/prods/'.$uniqueid.'.txt',"w");
            fwrite($file, $data);
            fclose($file);
        }

        public function S_EditProduct(User $user , Project $project, $data)
        {
            try{
                $callback= new Callback();
                if(empty($user) or empty($project) or empty($data['productName']) or empty($data['productavailable']) or empty($data['productCategories']) or empty($data['BPrice']) or empty($data['featured']) or empty($data['ncs_id']))
                    throw new \Exception("Some fields are required!");
                if(empty($data['picsA']))
                    throw new \Exception("Please select at least one picture for this product!");
                if(!empty($data['RPrice']) && ($data['BPrice'] <= $data['RPrice']))
                    throw new \Exception("Base price cannot be less than reduced price!");

                $globals = $this->GetGlobals();
                $connection = $this->dbconnectslave(
                    $globals['PREFIX']."_".$project->project_id
                );

                //Sanitize data
                $data['productName'] = mysqli_real_escape_string($connection, $data['productName']);
                //$data['productDescription'] = mysqli_real_escape_string($connection, $data['productDescription']);
                $data['productavailable'] = mysqli_real_escape_string($connection, $data['productavailable']);
                $data['productCategories'] = mysqli_real_escape_string($connection, $data['productCategories']);
                $data['BPrice'] = mysqli_real_escape_string($connection, $data['BPrice']);
                $data['RPrice'] = mysqli_real_escape_string($connection, $data['RPrice']);
                $data['APrice'] = mysqli_real_escape_string($connection, $data['APrice']);
                $data['D_Val'] = mysqli_real_escape_string($connection, $data['D_Val']);
                $data['Stock'] = mysqli_real_escape_string($connection, $data['Stock']);
                $data['picsA'] = mysqli_real_escape_string($connection, $data['picsA']);
                $data['ncs_id'] = mysqli_real_escape_string($connection, $data['ncs_id']);
                $data['featured'] = (boolean)mysqli_real_escape_string($connection, $data['featured']);
                


                $date = date("Y-m-d H:i");
                $pictures = explode(",",$data['picsA']);
                $pN = count($pictures);
                if($pN > 5)
                    throw new \Exception("You can only add 5 pictures per product!");
                $pictures = json_encode($pictures);
                $categories = explode(",",$data['productCategories']);
                $categories = json_encode($categories);

                $query = "UPDATE products SET Name='".$data['productName']."',BPrice='".$data['BPrice']."',RPrice='".$data['RPrice']."',APrice='".$data['APrice']."',D_Val='".$data['D_Val']."',Available='".$data['productavailable']."',Date='".$date."',Pictures='".$pictures."',Categories='".$categories."',Stock='".$data['Stock']."',Featured='".$data['featured']."' WHERE ID='".$data['ncs_id']."'";
                $result = mysqli_query($connection,$query);
                $this->dbcloseslave($connection);
                
                if(!empty($data['ncs_did']))
                    $this->S_UpdateDescriptionFile($user, $project , $data['ncs_did'], $data['productDescription']);
                
                if($result)
                {
                    return $callback->SendSuccessToast("Product successfully updated!");
                }
                throw new \Exception("Something went wrong!");
            }catch(\Exception $ex){
                if(isset($connection) && $connection != null)
                    $this->dbcloseslave($connection);
                return $callback->SendErrorToast($ex->getMessage());
            }
        }

        private function S_UpdateDescriptionFile(User $user, Project $project , $id, $data)
        {
            if(!file_exists('../../clients/'.$user->Business_Name.'/'.$project->project_name_short.'/prods/'))
                mkdir('../../clients/'.$user->Business_Name.'/'.$project->project_name_short.'/prods/',0777,true);
            
            $file = fopen('../../clients/'.$user->Business_Name.'/'.$project->project_name_short.'/prods/'.$id.'.txt',"w");
            fwrite($file, $data);
            fclose($file);
        }

        public function S_RemoveProduct(User $user ,Project $project, $data)
        {
            try{
                $callback= new Callback();
                if(empty($user) or empty($project) or empty($data['ncs_id'])  or empty($data['ncs_did']))
                    throw new \Exception("Some fields are required!");

                $globals = $this->GetGlobals();
                $connection = $this->dbconnectslave(
                    $globals['PREFIX']."_".$project->project_id
                );

                //Sanitize data
                $data['ncs_id'] = mysqli_real_escape_string($connection, $data['ncs_id']);

                $query = "DELETE FROM products WHERE ID='".$data['ncs_id']."'";
                $result = mysqli_query($connection,$query);
                $this->dbcloseslave($connection);
                $this->S_RemoveFile($user, $project, $data['ncs_did']);
                if($result)
                {
                    return $callback->SendSuccessToast("Product successfully removed!");
                }
                throw new \Exception("Something went wrong!");
            }catch(\Exception $ex){
                if(isset($connection) && $connection != null)
                    $this->dbcloseslave($connection);
                return $callback->SendErrorToast($ex->getMessage());
            }
        }

        private function S_RemoveFile(User $user , Project $project , $file)
        {
            if(file_exists('../../clients/'.$user->Business_Name.'/'.$project->project_name_short.'/prods/'.$file.'.txt'))
                unlink('../../clients/'.$user->Business_Name.'/'.$project->project_name_short.'/prods/'.$file.'.txt');
        }
        
        public function S_Add_Category(Project $project , $data)
        {
        try{
            $callback = new Callback();
            if(empty($data['Name']) or empty($project) or empty($data['isParent']))
                throw new \Exception("Fields cannot be empty!");
            if(isset($data['Parent']) && empty($data['Parent']))
                throw new \Exception("Fields cannot be empty!");
            $globals = $this->GetGlobals();
            $connection = $this->dbconnectslave(
                $globals['PREFIX']."_".$project->project_id
            );
            //Sanitize data
            $data['Name'] = mysqli_real_escape_string($connection, $data['Name']);
            $data['isParent'] = mysqli_real_escape_string($connection, $data['isParent']);
            if(isset($data['Parent']))
                $data['Parent'] = mysqli_real_escape_string($connection, $data['Parent']);
            if($data['isParent'] == "true")
                $n = 0;
            else
                $n = $data['Parent'];
            
            
            $query = "INSERT INTO categories(Name,P_ID)VALUES('".$data['Name']."','".$n."')";
            $result = mysqli_query($connection,$query);
            $this->dbcloseslave($connection);
            if($result)
            {
                return $callback->SendSuccessToast("Category successfully created!");
            }
            else throw new \Exception("Something went wrong!");
        }
        catch(\Exception $ex){
            if(isset($connection) && $connection != null)
                $this->dbcloseslave($connection);
            return $callback->SendErrorToast($ex->getMessage());
        }
        }

        public function S_Remove_Category(Project $project , $data)
        {
            try {
                $callback = new Callback();
                if(empty($project) or empty($data))
                    throw new \Exception("Fields cannot be empty!");
                $globals = $this->GetGlobals();
                $connection = $this->dbconnectslave(
                    $globals['PREFIX']."_".$project->project_id
                );

                $data = mysqli_real_escape_string($connection, $data);
                //Check if is a parent or not
                $prequery = "SELECT * FROM categories WHERE P_ID='".$data."'";
                $preresult = mysqli_query($connection , $prequery);
                if($preresult->num_rows > 0)
                    throw new \Exception("Please remove children first !");
                
                $query = "DELETE FROM categories WHERE ID='".$data."'";
                $result = mysqli_query($connection, $query);
                $this->dbcloseslave($connection);
                if($result)
                {
                    return $callback->SendSuccessToast("Category successfully removed!");
                }
                throw new \Exception("Something went wrong!");
                
            }catch(\Exception $ex){
                if(isset($connection) && $connection != null)
                    $this->dbcloseslave($connection);
                return $callback->SendErrorToast($ex->getMessage());
            }
        }
    }

    class Product{
        public $id;
        public $name;
        public $descriptionid;
        public $BPrice;
        public $RPrice;
        public $APrice;
        public $available;
        public $date;
        public $extra;
        public $pictures;
        public $categories;
        public $stock;
        public $featured;
        public $D_Val;

        function __construct($data)
        {
            $get_arguments       = func_get_args();
            $number_of_arguments = func_num_args();
            if (method_exists($this, $method_name = '__construct'.$number_of_arguments)) {
                call_user_func_array(array($this, $method_name), $get_arguments);
            }
        }

        function __construct1($data)
        {
            $this->id = $data['ID'];
            $this->name = $data['Name'];
            $this->descriptionid = $data['Description'];
            $this->BPrice = $data['BPrice'];
            $this->RPrice = $data['RPrice'];
            $this->APrice = $data['APrice'];
            $this->available = $data['Available'];
            $this->extra = $data['Extra'];
            $this->pictures = json_decode($data['Pictures'],true);
            $this->categories = json_decode($data['Categories'],true);
            $this->stock = $data['Stock'];
            $this->date = $data['Date'];
            $this->featured = $data['Featured'];
            $this->D_Val = $data['D_Val'];
        }

        function __construct3(User $user , Project $project , $id)
        {
            $data = new Data();
            $globals = $data->GetGlobals();
            $connection = $data->dbconnectslave(
                $globals['PREFIX']."_".$project->project_id
            );
            $id = mysqli_real_escape_string($connection,$id);
            $query = "SELECT * FROM products WHERE ID='".$id."'";
            $result = mysqli_query($connection,$query);
            $data->dbclosemaster($connection);
            if($result)
            {
                self::__construct(mysqli_fetch_assoc($result));
            }else return;
        }

        public function GetPrices(User $user , Project $project)
        {
            $data = new Data();
            $info = $data->getWidgetJSON($user, $project, "info", "../../");
            if($this->RPrice != "" or $this->RPrice != "0")
                return $this->BPrice." / "."<del>".$this->RPrice."</del> <curency>".$info['Curency']."</curency>";
            return $this->BPrice." <curency>".$info['Curency']."</curency>";
        }

        public function GetMainPicture()
        {
            return $this->pictures[0];
        }


        public function GetDescription(User $user , Project $project , $id, $defpath ='../../')
        {
            return file_get_contents($defpath.'clients/'.$user->Business_Name.'/'.$project->project_name_short.'/prods/'.$id.'.txt');
        }

        public function GetVisibility()
        {
            if($this->available == "available")
                return "<span class='badge badge-success'>Available</span>";
            elseif($this->available == "notavailable")
                return "<span class='badge badge-danger'>Not available</span>";
            elseif($this->available == "preorder")
                return "<span class='badge badge-primary'>Preorder</span>";
        }

        public function StripName()
        {
            if(strlen($this->name) >= 37)
                return substr(strip_tags(html_entity_decode($this->name)),0,37).'...';
            return $this->name;
        }

    }
}
/* if(isset($_POST['action']) && ($_POST['action'] == "add"))
    {
        $data = new Data();
        $user = $data->GetUser($_SESSION['loggedIN']);
        $shop = new shop();
        $project = new Project($_COOKIE['NCS_PROJECT']);
        $shop->S_AddProduct($user,$project, $_POST);
    }
    if(isset($_POST['action']) && ($_POST['action'] == "edit") && isset($_POST['ncs_id']))
    {
        $data = new Data();
        $user = $data->GetUser($_SESSION['loggedIN']);
        $shop = new shop();
        $project = new Project($_COOKIE['NCS_PROJECT']);
        $shop->S_EditProduct($user, $project, $_POST);
    }
    if(isset($_POST['action']) && ($_POST['action'] == "remove") && isset($_POST['ncs_id']) && isset($_POST['ncs_did']) )
    {
        $data = new Data();
        $user = $data->GetUser($_SESSION['loggedIN']);
        $shop = new shop();
        $project = new Project($_COOKIE['NCS_PROJECT']);
        $shop->S_RemoveProduct($user,$project, $_POST);
    }
    if(isset($_POST['cat_name']) && isset($_POST['isparent']))
    {
        $shop = new shop();
        $project = new Project($_COOKIE['NCS_PROJECT']);
        if(isset($_POST['parent']))
            $data = ["Name"=>$_POST['cat_name'],"isParent"=>$_POST['isparent'], "Parent"=>$_POST['parent']];
        else
            $data = ["Name"=>$_POST['cat_name'], "isParent"=>$_POST['isparent']];
        $shop->S_Add_Category($project , $data);
    }
    if(isset($_POST['cat_id']) && isset($_POST['action']) && $_POST['action'] == "remove")
    {
        $shop = new shop();
        $project = new Project($_COOKIE['NCS_PROJECT']);
        $data = $_POST['cat_id'];
        $shop->S_Remove_Category($project , $data);
    } */
?>