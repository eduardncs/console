<?php 
require_once("../../System/Database.class.php");
require_once("../../System/Data.class.php");
require_once("../../System/User.class.php");
require_once("../../System/Project.class.php");
require_once("../../System/Mail.class.php");
session_start();
$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
$project = new Project($_COOKIE['NCS_PROJECT']);

if(isset($_POST['to'])&&isset($_POST['subject'])&&isset($_POST['mailcontent']))
{
    $mail = new Mail($user, $project);
    echo $mail->sendMail($_POST['to'],$_POST['subject'], $_POST['mailcontent']);
}
?>
<link rel="stylesheet" href="plugins/summernote/summernote.css">
<form id="compose-mail">
<div class="card-body">
    <div class="form-group">
        <input class="form-control elevation-1" name="to" placeholder="To:">
    </div>
    <div class="form-group">
        <input class="form-control elevation-1" name="subject" placeholder="Subject:">
    </div>
    <div class="form-group">
        <textarea id="compose-textarea" name="mailcontent" class="form-control elevation-1"></textarea>
    </div>
</div>
<div class="card-footer">
    <div class="float-right">
        <button type="submit" id="btnSubmit" class="btn btn-primary disabled" disabled><i class="far fa-envelope"></i> Send</button>
    </div>
    <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> Discard</button>
</div>
</form>
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<script>
$("#compose-textarea").summernote({
    fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana', 'Roboto'],
    fontNamesIgnoreCheck: ['Roboto'],
    height: 300
  });
  $("#compose-mail").on("change",function(){
      $("#btnSubmit").removeClass("disabled");
      $("#btnSubmit").removeAttr("disabled");
  })
  $("#compose-mail").submit(function(event){
    event.preventDefault();
    var values = $("#compose-mail").serialize();
    $.ajax({
        url: 'pages/mailbox/compose.php',
        dataType:'html',
        method:'post',
        data:values,
        beforeSend: function(){
            $("#btnSubmit").html('<i class="fas fa-sync fa-spin"></i> Loading');
        },
        success: function(data){
            $("#btnSubmit").html('<i class="far fa-envelope"></i> Send');
            $("#mailContainer").html(data);
        }
    })
  })
</script>