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
require("system/Database.class.php");
require("system/Data.class.php");
require("system/Project.class.php");
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
	<base href="<?php if(isset($_GET['productID'])) echo $data->UpdateURIBase("/manage/".$_GET['productID']); else echo $data->UpdateURIBase("/manage"); ?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
	<link rel="stylesheet" href="dist/css/adminlte.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.0.2/sweetalert2.css">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<link rel="stylesheet" href="plugins/summernote/summernote.css">
	<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
	<link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
	<link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
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
		<!-- Sidebar Menu -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo "images/templates/".$project->project_template.".jpg"; ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="javascript:void(0)" class="d-block"><?php echo ucfirst($project->project_name); ?></a>
        </div>
      </div>
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
		  <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="javascript:void(0)" id="addproduct" class="btn btn-default btn-pill elevation-2 disabled" disabled="disabled">Update product</a></li>
            </ol>
          </div>
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
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="system/js/rosance.js"></script>
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<script src="plugins/select2/js/select2.full.min.js"></script>
<script>
$(document).ready( function()
{
	<?php if(!isset($_GET['productID'])){ ?>
		getpage("pages/shop/add", "E-Commence :: Add products");
	<?php } else {?>
		getpage("pages/shop/edit.php?productID="+<?php echo $_GET['productID']; ?>, "E-Commence :: Edit products");
	<?php } ?>
		getsubpage();
})
</script>
</body>
</html>
