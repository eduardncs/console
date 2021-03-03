<?php
session_start();
if(isset($_GET['action']) && ($_GET['action'] == 'logout' or $_GET['action'] == "clearCookies"))
{
	session_destroy();
	setcookie("NCS_USER" , '' , time()-3600 , '/' , '' , 0 );
    unset( $_COOKIE["NCS_USER"] );
	setcookie("NCS_PROJECT" , '' , time()-3600 , '/' , '' , 0 );
    unset( $_COOKIE["NCS_PROJECT"] );
	header("Location: registration");
}
if(isset($_SESSION['loggedIN']) && (!isset($_GET['action'])))
{
    header("Location: dashboard");
}
require_once("autoload.php");
use Rosance\Database;
use Rosance\Data;
$data = new Data();
$globals = $data->GetGlobals();
if(isset($_GET['token']) && isset($_GET['uid']))
{
    $database = new Database();
    echo $database->ActivateAccount($_GET['uid'],$_GET['token']);
}
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sign up | <?php echo $globals['COMPANY'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-signin-client_id" content="42685965557-urskj9esf47q1rfti99dk6ud1kdtp1t4.apps.googleusercontent.com">
    <link rel="stylesheet" href="dist/css/adminlte.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.0.2/sweetalert2.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href='images/favicon.ico' rel='icon'>
  </head>
  <body class="hold-transition">
    <div class="sidenav">
      <div class="container d-flex h-100 bg-black; align-items-center">
        <div class="text-center" style="width:100%;" id="page-header">
        </div>
      </div>
    </div>
    <div class="main">
      <div class="container h-100">
        <div class="bg-white overlay row align-items-center justify-content-center h-100" id="overlay" style="position:relative;">
          <div class="col-10 mx-auto text-center">
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            <h2 class="mt-2" id="dots">Talking with the server, please wait <span>.</span><span>.</span><span>.</span></h2>	
          </div>
        </div>
        <div class="row align-items-center h-100">
            <div class="col-9 mx-auto" id="container">
              <div id="content" style="width:100%;"></div>
            </div>
        </div>
      </div>
    </div>
  <!--   Core JS Files   -->
  <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="https://apis.google.com/js/platform.js?onload=init" async defer></script>
  <script type="module" src="dist/js/registration.js"></script>
</body>
</html>
