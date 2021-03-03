<?php
session_start();
if(!isset($_SESSION['loggedIN']) || (!isset($_COOKIE['NCS_USER'])))
{
	header("Location: /registration");
}
if(!isset($_COOKIE['NCS_PROJECT']))
{
	header("Location: /projects");
}
if($_SERVER['REQUEST_URI'] == "/" or $_SERVER['REQUEST_URI'] == "/dashboard")
	header("Location: dashboard/overview");
require_once("autoload.php");
use Rosance\Data;
use Rosance\Project;
$data = new Data();
$project = new Project($_COOKIE['NCS_PROJECT']);
$globals = $data->GetGlobals();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Dashboard :: <?php echo $globals['COMPANY']; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<base href="<?php echo $data->UpdateURIBase("/overview"); ?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
	<link rel="stylesheet" href="dist/css/adminlte.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.0.2/sweetalert2.css">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<link href='images/favicon.ico' rel='icon'>
</head>
<body class="<?php echo $layout = $data->GetLayout("system/Layout.json","body");?>">
<div class="wrapper">
  <!-- Navbar -->
  <?php include("system/mini-navbar.html"); ?>
  
	<aside class="main-sidebar elevation-4 <?php echo $data->GetLayout("system/Layout.json","aside-sidebar"); ?>">
	  <!-- Brand Logo -->
	  <a href="dashboard" class="brand-link <?php echo $data->GetLayout("system/Layout.json","navbar-brand"); ?>">
	    <img src="images/favicon.ico"
	         alt="R/S"
	         class="brand-image img-circle elevation-3"
	         style="opacity: .8">
	    <span class="brand-text font-weight-light"><b style="color: white;">Rosance</b></span>
	  </a>

	  <!-- Sidebar -->
	  <div class="sidebar">

	  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo "images/templates/".$project->project_template.".jpg"; ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="javascript:void(0)" class="d-block"><?php echo ucfirst($project->project_name); ?></a>
        </div>
      </div>
		<!-- Sidebar Menu -->
		<?php 
		 echo $data->CreateMenu($project);
		?>
	    <!-- /.sidebar-menu -->
	  </div>
	  <!-- /.sidebar -->
	</aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark" id="Header"></h1>
          </div><!-- /.col -->
          <!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	  <div class="overlay row align-items-center h-100" id="overlay" style="background:#fff;display:none;">
			<div class="col-6 mx-auto text-center">
				<i class="fas fa-2x fa-sync-alt fa-spin"></i>
				<h2 class="mt-2">Loading <span>.</span><span>.</span><span>.</span></h2>
			</div>
		</div>        
		<div id="content"></div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
		<div class="float-left d-none d-sm-block">
      		<h6>Made with <span class="fa fa-heart" style="color:#ff4c4c;"></span> love!</h6>
    </div>
		<div class="float-right d-none d-sm-block">
					<h6>Version <?php echo $globals['VERSION']; echo " ".$globals['PRODUCTION']; ?></h6>
		</div>
  </footer>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="dist/js/adminlte.min.js"></script>
<script type="module" src="dist/js/dashboard.js"></script>
</body>
</html>
