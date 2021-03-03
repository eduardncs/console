<?php
session_start();
if(!isset($_SESSION['loggedIN']) || (!isset($_COOKIE['NCS_USER'])))
{
	header("Location: registration");
}
if(!isset($_COOKIE['NCS_PROJECT']))
{
	header("Location: projects");
}
require_once("autoload.php");
use Rosance\Data;
use Rosance\Editor;
use Rosance\Project;
use Rosance\User;

$data = new Data();
$editor = new Editor();
$project = new Project($_COOKIE['NCS_PROJECT']);
$user = $data->GetUser($_SESSION['loggedIN']);
?>
<html  lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Editor Mode - Rosance :: <?php echo $project->project_name; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.0.2/sweetalert2.css">
	<link rel="stylesheet" href="dist/css/adminlte.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
	<link rel="stylesheet" href="dist/css/editor.css">
	<link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="plugins/ion-rangeslider/css/ion.rangeSlider.min.css">
	<link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<link href='images/favicon.ico' rel='icon'>
</head>
<body class="hold-transition layout-top-navigation sidebar-collapse" style="overflow-x:hidden;">
	<div class="wrapper">
		<nav class="main-header navbar navbar-expand-lg navbar-light bg-light sticky-top" id="navbar" style="height:8%;">
			<a class="navbar-brand ml-3 pr-3 bordered-right" href="https://console.rosance.com/dashboard" ><img src="images/logo-big.png" style="width:100px;"></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item dropdown bordered-right">
						<a class="nav-link dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Page: <span id="currentpage">Home</span>
						</a>
					<div class="dropdown-menu pagesonwebsite" style="min-width:200px;" aria-labelledby="navbarDropdown">
					<h6 class="dropdown-header">Website pages</h6>
					<div class="dropdown-divider"></div>
					<?php
						$pages = $editor->GetPages($user,$project->project_name_short);
						foreach($pages as $page)
						{
						?>
					<a class="dropdown-item page" href="javascript:void(0)" id="<?php echo explode(".",$page)[0];?>"><?php echo ucfirst(explode(".",$page)[0]);?></a>
						<?php }?>
					</div>
					</li>
					<li class="nav-item dropdown bordered-right">
						<a class="nav-link dropdown-toggle" href="javascript:void(0)" id="viewNavbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						View perspective: <span id="cview">Desktop</span>
						</a>
					<div class="dropdown-menu pagesonwebsite" style="min-width:200px;" aria-labelledby="viewNavbarDropdown">
						<h6 class="dropdown-header">View modes</h6>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item page" id="chw-d" href="javascript:void(0)">Desktop</a>
						<a class="dropdown-item page" id="chw-m" href="javascript:void(0)">Mobile</a>
					</div>
					</li>
					<!-- <li class="nav-item bordered-right">
						<a class="nav-link" href="javascript:void(0)" id="devModeTrigger">Developer mode</a>
					</li> -->
				</ul>
			</div>
		</nav>
		<div class="content-wrapper" style="overflow:hidden;">
			<div id="widgetcontainer"></div>
			<div id="ajax"></div>
			<div id="media-master-container"></div>
			<div class="overlay overlay_main row text-center align-items-center justify-content-center bg-white" id="overlay" style="position:absolute;">
				<div class="col-6 mx-auto text-center">
					<i class="fas fa-2x fa-sync-alt fa-spin"></i>
					<h2 class="mt-2">Editor is loading all required assets, please wait <span>.</span><span>.</span><span>.</span></h2>
				</div>
			</div>
			<div class="d-flex" style="height:92%;">
				<div class="h-100 border-0 mr-auto bg-white" style="width:6%;" id="editor-sidebar-left"></div>
				<div class="viewport mx-auto d-flex" style="width:75%; height:100%">
					<iframe id="sandbox" width="100%" height="100%" class="sandbox mx-auto h-100" type="text/html"></iframe>
				</div>
				<div class="h-100 border-0 ml-auto pl-1 overflow" style="width:19%;" id="editor-sidebar-right">
					<div class="container-fluid" id="editor-sidebar-right"></div>
				</div>
			</div>
		</div>
	</div>
	<script>
	const BN = "<?php echo $user->Business_Name; ?>";
	const PN = "<?php echo $project->project_name_short; ?>";
	</script>
<noscript>YOU NEED TO HAVE JAVASCRIPT ENABLED IN ORDER FOR THIS PAGE TO WORK!</noscript>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="dist/js/adminlte.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript" src="plugins/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
<script type="text/javascript" src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script type="module" defer src="dist/js/editor.js"></script>
</body>
</html>
