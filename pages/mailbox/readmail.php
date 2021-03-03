<?php 
session_start();
require_once("../../autoload.php");
use Rosance\Data;
use Rosance\Project;
use Rosance\User;
use Rosance\Mail;
//Make the mail readen
$data = new Data();
$user = $data->getUser($_SESSION['loggedIN']);
$project = new Project($_COOKIE['NCS_PROJECT']);
if(isset($_POST['postID']))
{
  if(empty($_POST['postID']))
    exit();

  // Create Mail object
  $mail = new Mail($user, $project, $_POST['postID']);
  //The post will be marked as read
  $mail->markAsRead();
}
?>
              <div class="mailbox-read-info">
              <input type="hidden" id="mailID" value="<?php echo $mail->contentID; ?>">
                <h5><b><?php echo $mail->subject; ?></b></h5>
                <h6 class='pt-2'>From: <?php echo $mail->name; ?> < <?php echo "<a href='mailto:".$mail->email."'>".$mail->email."</a>"; ?> >
                  <span class="mailbox-read-time float-right"><?php echo $mail->date; ?></span></h6>
              </div>
              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message">
                <?php echo $mail->getMailContent(); ?>
              </div>
              <!-- /.mailbox-read-message -->
<script>
$(document).ready(function(){
  $("[data-toggle]").tooltip();
});
</script>