<?php
session_start();
if(!isset($_SESSION['loggedIN']) or (!isset($_COOKIE['NCS_USER'])))
{
	header("Location: registration");
}
require_once("autoload.php");
use Rosance\Data;
$data = new Data();
$globals = $data->GetGlobals();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Create a new project | <?php echo $globals['COMPANY'] ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<base href="<?php echo $data->UpdateURIBase("/create"); ?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
	<link rel="stylesheet" href="dist/css/adminlte.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.0.2/sweetalert2.css">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<link href='images/favicon.ico' rel='icon'>
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-light">
    <div class="container">
		<div class="collapse navbar-collapse order-3" id="navbarCollapse">
			<ul class="navbar-nav">
				<li class="nav-item">
			        <a href="projects" class="nav-link">Projects</a>
			    </li>
				<li class="nav-item">
					<a href="contact" class="nav-link">Contact</a>
				</li>
			</ul>
			<ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto" id="userArea"></ul>
		</div>
    </div>
  </nav>
  <!-- /.navbar -->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark" id="Header">My Projects</h1>
          </div><!-- /.col -->
          <!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container h-100">
		    <div id="ajax"></div>
              <div class="overlay row align-items-center w-100 h-100 position-relative" id="overlay">
                <div class="col-6 mx-auto text-center">
                  <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                  <h2 class="mt-2">Loading <span>.</span><span>.</span><span>.</span></h2>
                </div>
              </div> 
            <div class="overlay row align-items-center h-100" id="poverlay" style="background:#fff;display:none;">
    			<div class="col-6 mx-auto text-center">
    				<i class="fas fa-2x fa-sync-alt fa-spin"></i></br>
    				<h2 class="mt-2" id="dots">Hang on , your project is being created <span>.</span><span>.</span><span>.</span></h2>	
    			</div>
    		</div>
		      <div id="content"></div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer text-center">
					<h6>Made with <span class="fa fa-heart" style="color:#ff4c4c;"></span> love!</h6>
          <div class="text-muted text-center">
					  <a href="legal/gdpr" class="text-muted">Privacy policy </a> |
            <a href="legal/tos" class="text-muted">Terms and Conditions </a> |
            <a href="legal/disclaimer" class="text-muted">Disclaimer </a> 
          </div>
          <div class="text-center text-muted">
          Copyright © 2020 Rosance
          </div>
  </footer>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="module" src="dist/js/projects.js"></script>
<script src="plugins/select2/js/select2.full.js"></script>
</body>
</html>
