<?php
session_start();
require_once("../autoload.php");
use Rosance\Data;
use Rosance\User;
use Rosance\Project;
use Rosance\Editor;
$data = new Data();
if(isset($_GET['projecttodelete']))
{
	$data->DeleteProject($_SESSION['loggedIN'], $_GET['projecttodelete']);
}
$user = $data->GetUser($_SESSION['loggedIN']);
if(!isset($_COOKIE['NCS_PROJECT'])):

?>
<div class="row"><div class="col-12 col-sm-12">
<div class="alert alert-danger alert-dismissible">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
<h5><i class="icon fas fa-ban"></i> Ooops!</h5>
No project selected , please create a project or select one to be able to access this page!
</div></div></div>
<?php else: 
	$editor = new Editor();
	$project = new Project($_COOKIE['NCS_PROJECT']);
	$info = $editor->getInfo($user,$project->project_name_short,"../");
?>

<div class="row">
	<div class="col-md-12">
		<div class="card card-outline card-primary">
			<div class="card-body">
				<div class="row">
					<div class="col-md-4 text-center">
						<img id="thumbnail" src="<?php echo "images/templates/".$project->project_template.".jpg"; ?>" alt="thumbnail" class="elevation-2" style="width:100%; height:200px;">
					</div>
					<div class="col-md-8 mt-3">
						<h4 class="ml-2"><?php echo $project->project_name; ?></h4>
						<span class="ml-2"><a class="text-muted" data-toggle="tooltip" data-placement="bottom" title="It might take up to 10-15 minutes for the link to work" target="_blank" href="https://<?php echo $user->Business_Name.".rosance.com/".$project->project_name_short."/"; ?>">https://<?php echo $user->Business_Name.".rosance.com/".$project->project_name_short."/"; ?></a></span>
					</div>
				</div>
			</div>
			<div class="card-footer">
			<div class="row">
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
					  <span class="description-text">PROJECT ID</span>
					  <h5 class="description-header"><?php echo $project->project_id;  ?></h5>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
					  <span class="description-text">ACCOUNT ID</span>
					  <h5 class="description-header"><?php echo $user->id; ?></h5>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
					  <span class="description-text">PRIVILEGES</span>
					  <h5 class="description-header"><?php if($user->Premium) echo "Premium"; else echo "Free"; ?></h5>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block">
					  <span class="description-text">PERMISIONS</span>
					  <h5 class="description-header"><?php if($project->project_owner == $user->id) echo "Owner"; else "Administrator"; ?></h5>
                    </div>
                    <!-- /.description-block -->
                  </div>
                </div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<script>
$("[data-toggle*=tooltip]").tooltip();
</script>