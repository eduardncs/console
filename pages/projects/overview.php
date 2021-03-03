<?php
session_start();
if(!isset($_SESSION['loggedIN']) or (!isset($_COOKIE['NCS_USER'])))
{
	header("Location: registration");
}
require_once("../../autoload.php");
use Rosance\Data;
use Rosance\User;
use Rosance\Project;
use Rosance\Editor;
$editor = new Editor();
$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
$globals = $data->GetGlobals();
if(isset($_POST['project']) && !empty($_POST['project']))
{
		setcookie("NCS_PROJECT", $_POST['project'], time() + (86400 * 30), "/");
		$project = new Project($_POST['project']);
		echo "<script>showsuccessprojectselection('Project ".$project->project_name." successfully selected! <br/> You will be redirected to dashboard in a moment')</script>";
}
if(isset($_GET['projecttocreate']))
{
	$get = json_decode($_GET['projecttocreate'],true);
	$data->createproject($_SESSION['loggedIN'], $get[0], $get[1]);
}
$projectsIOwn = $data->GetProjects($_SESSION['loggedIN'], "Owner");
$projectsIManage = $data->GetProjects($_SESSION['loggedIN'], "Admin");
if(empty($projectsIOwn))
{
?>
	<div class="card card-fluid" style="background:#F9FBFF;">
		<div class="card-body">
			<div class="row">
			<div class="col-md-7" style="padding-left: 100px; padding-top: 40px;">
				<h2>Welcome, <br>
				<?php 
  echo $user->First_Name." ".$user->Last_Name;
 ?></h2>
				<p class="text-muted">
				 Let's create a project
				</p>
				<p>
				<a href="projects/create" class="btn btn-primary" style="border 2px solid transparent; border-radius: 20px;">Create a new project</a>
				</p>
			</div>
				<div class="col-md-5">
					<img src="images/1.png" class="image image-fluid" style="width: 90%; height: 256px;">
				</div>
			</div>
			</div>
	</div>
		<?php }
		
		else { ?>
				<div class="card card-solid">
			<div class="card-header">
				<div class="card-tools">
				<a href="projects/create"   class="btn btn-default btn-pill">
				Create a new project
				</a>
				</div>
			</div>
			<div class="card-body">
				<div class="row d-flex flex-wrap">
		<?php foreach($projectsIOwn as $project)
		{
			$settings = $editor->getInfo($user,$project->project_name_short,"../../");
				?>
					<div class="col-md-4 projects elevation-2 card">
							<div class="overlay" style="display:none;">
								<a href="javascript:void(0)" projectId="<?php echo $project->project_id;?>" class="btn btn-primary btn-pill">Select project</a>
							</div>
						<div class="position-relative" align="center">
                      <img src="<?php echo "images/templates/".$project->project_template.".jpg"; ?>" alt="Photo 1" style="height:250px; width:100%;" class="img-fluid">
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="text-muted"><?php echo $project->project_name; ?></div>
								</div>
							</div>
						</div>
					</div>
		<?php } ?>

				</div>
			</div>
		</div>
		<?php } ?>
		<script type="text/javascript">
	$(document).ready(function(){$(".projects").each(function(){$(this).find("a").click(function(){var t=$(this).attr("projectId");$.ajax({url:"pages/projects/overview.php",type:"POST",data:{"project":t},success:function(t){$("#content").html(t)}})}),$(this).hover(function(){$(this).removeClass("elevation-2"); $(this).addClass("elevation-4");$(this).find(".overlay").stop(!0,!0).fadeIn("slow")},function(){$(this).removeClass("elevation-4"); $(this).addClass("elevation-2");$(this).find(".overlay").stop(!0,!0).fadeOut("slow")})})});
		</script>
